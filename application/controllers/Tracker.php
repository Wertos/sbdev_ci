<?php
class Tracker extends CI_Controller
{
    function __construct()
    {
        parent::__construct();

        $this
            ->load
            ->helper('file');

        $this->timenow = time();
        // Input var names
        // String
        $input_vars_str = array(
            'info_hash',
            'event',
        );
        // Numeric
        $input_vars_num = array(
            'port',
        );

        // Init received data
        // String
        parse_str(substr(strrchr($_SERVER['REQUEST_URI'], "?") , 1) , $_GET);

        foreach ($input_vars_str as $var_name)
        {
            $this->$var_name = (string)$_GET[$var_name] ? ? NULL;
        }
        // Numeric
        foreach ($input_vars_num as $var_name)
        {
            $this->$var_name = (float)$this
                ->input
                ->get($var_name) ? ? NULL;
        }
        //write_file($this->config->item('log_path').'announce.log', $_SERVER['QUERY_STRING']."\n",'a+');
        $this->ip = $this
            ->input
            ->ip_address();

    }

    public function announce()
    {
        // Verify required request params (info_hash, port)
        if (isset($this->info_hash))
        {
            if (get_magic_quotes_gpc()) $this->info_hash = stripslashes($this->info_hash);

            if (strlen($this->info_hash) == 20)
            {
                $this->info_hash = bin2hex($this->info_hash);
            }
            else if (strlen($this->info_hash) == 40)
            {
                $this->_verifyHash($this->info_hash) or $this->_msg_die('Invalid info_hash');
            }
            else
            {
                $this->_msg_die('Invalid info_hash');
            }
        }
        if (!isset($this->port) || $this->port <= 0 || $this->port > 0xFFFF)
        {
            $this->_msg_die('Invalid port');
        }
        if (!$this
            ->input
            ->valid_ip($this->ip))
        {
            $this->_msg_die("Invalid IP: " . $this->ip);
        }

        // Convert IP to HEX format
        $ip_sql = $this->_encode_ip($this->ip);

        // ----------------------------------------------------------------------------
        // Start announcer
        //
        $info_hash_sql = rtrim($this
            ->db
            ->escape_str($this->info_hash) , ' ');
        // Stopped event
        if ($this->event === 'stopped')
        {
            $this
                ->db
                ->delete('tracker', ['info_hash' => $info_hash_sql, 'ip' => $ip_sql, 'port' => $this->port]);
            die();
        }
        $data = ['info_hash' => $info_hash_sql, 'ip' => $ip_sql, 'port' => $this->port, 'update_time' => $this->timenow];
        $this
            ->db
            ->replace('tracker', $data);

        // Get cached output
        if (!$output = CACHE()->get('peer_' . md5($this->info_hash)))
        {
            // Retrieve peers
            $peers = '';
            $ann_interval = 1800; //$tr_cfg['announce_interval'] + mt_rand(0, 600);
            $rowset = $this
                ->db
                ->select('ip, port')
                ->where(['info_hash' => $info_hash_sql])->order_by('RAND()')
                ->get('tracker');

            foreach ($rowset->result_array() as $peer)
            {
                //var_dump($peer);
                $peers .= pack('Nn', ip2long($this->_decode_ip($peer['ip'])) , $peer['port']);
            }

            $output = ['interval' => (int)$ann_interval, 'min interval' => (int)$ann_interval, 'peers' => $peers, ];

            CACHE()->save('peer_' . md5($this->info_hash) , $output, CACHE_LIFE_TIME());
        }

        // Return data to client
        echo $this->_bencode($output);
        exit;
    }

    public function scrape()
    {
        // Verify required request params (info_hash, port)
        if (isset($this->info_hash))
        {
            if (get_magic_quotes_gpc()) $this->info_hash = stripslashes($this->info_hash);

            if (strlen($this->info_hash) == 20)
            {
                $this->info_hash = bin2hex($this->info_hash);
            }
            else if (strlen($this->info_hash) == 40)
            {
                $this->info_hash = strtolower($this->info_hash);
                $this->_verifyHash($this->info_hash) or $this->_msg_die('Invalid info_hash');
                $this->info_hash = hex2bin(trim($this->info_hash));
            }
            else
            {
                $this->_msg_die('Invalid info_hash');
            }
        }
        // ----------------------------------------------------------------------------
        // Start scraper
        //
        //		$info_hash_sql = $this->db->escape($this->info_hash);
        $info_hash_sql = $this->info_hash;
        //$info_hash_sql = rtrim($this->db->escape($this->info_hash), ' ');
        if (!$output = CACHE()->get('scrape_' . md5($this->info_hash)))
        {
            $row = $this
                ->db
                ->select('completed,seeders,leechers')
                ->where(['info_hash' => $info_hash_sql])->limit('1')
                ->get('torrents')
                ->row_array();
            //var_dump($rowset);
            $output = ['complete' => (int)$row['seeders'] + rand(10, 90) , 'downloaded' => (int)$row['completed'] + rand(10, 90) , 'incomplete' => (int)$row['leechers'] + rand(10, 90) , 'info_hash' => $this->info_hash];
            CACHE()
                ->save('scrape_' . md5($this->info_hash) , $output, CACHE_LIFE_TIME());
        }
        //var_dump($output);
        // Return data to client
        echo $this->_bencode($output);
        exit;
    }

    private function _verifyHash($input)
    {
        if (strlen($input) === 40 && preg_match('/^[0-9a-f]+$/', $input)) return true;
        else return false;
    }
    private function _encode_ip($ip)
    {
        $d = explode('.', $ip);
        return sprintf('%02x%02x%02x%02x', $d[0], $d[1], $d[2], $d[3]);
    }

    private function _decode_ip($ip)
    {
        return long2ip("0x{$ip}");
    }

    private function _str_compact($str)
    {
        return preg_replace('#\s+#', ' ', trim($str));
    }
    private function _msg_die($msg)
    {
        $output = $this->_bencode(array(
            'min interval' => (int)1800,
            'failure reason' => (string)$msg,
        ));
        die($output);
    }
    private function _bencode($var)
    {
        if (is_string($var))
        {
            return strlen($var) . ':' . $var;
        }
        else if (is_int($var))
        {
            return 'i' . $var . 'e';
        }
        else if (is_float($var))
        {
            return 'i' . sprintf('%.0f', $var) . 'e';
        }
        else if (is_array($var))
        {
            if (count($var) == 0)
            {
                return 'de';
            }
            else
            {
                $assoc = false;

                foreach ($var as $key => $val)
                {
                    if (!is_int($key))
                    {
                        $assoc = true;
                        break;
                    }
                }

                if ($assoc)
                {
                    ksort($var, SORT_REGULAR);
                    $ret = 'd';

                    foreach ($var as $key => $val)
                    {
                        $ret .= $this->_bencode($key) . $this->_bencode($val);
                    }
                    return $ret . 'e';
                }
                else
                {
                    $ret = 'l';

                    foreach ($var as $val)
                    {
                        $ret .= $this->_bencode($val);
                    }
                    return $ret . 'e';
                }
            }
        }
        else
        {
            trigger_error('bencode error: wrong data type', E_USER_ERROR);
        }
    }

}

