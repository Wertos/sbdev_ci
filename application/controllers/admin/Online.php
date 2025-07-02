<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Online extends MY_Controller {

    function __construct() {
        parent::__construct();


        if (!$this->ion_auth->is_admin())
            show_404();

#        $this->load->library('form_validation');
#        $this->load->library('session');
#        $this->load->library("pagination");
#        $this->load->helper('form');
#        $this->load->helper('security');
#        $this->load->model('admin/users_model', 'users');
    }

    public function index() {
         
	$server_addr = $_SERVER["SERVER_ADDR"];
       	$sessions = $this->db->select('*')
			->from($this->config->item('sess_save_path'))
			->where('timestamp > UNIX_TIMESTAMP() - '.$this->config->item('sess_time_to_update'))
			->where('ip_address <> "0.0.0.0" AND ip_address <> "'.$server_addr.'"')
			->order_by('timestamp', 'ASC')
			->group_by('ip_address')
			->get()->result_array();

        $count_all = count($sessions);
        $userlist = [];
        $botlist = [];
        $count_bot = 0;
		$count_reg = 0;
		foreach ($sessions as $session) {
			$sdata = $this->DecodeSession($session['data']);
			//var_dump($sdata);
			echo $sdata['user']['proxy'].' -> '.$session['ip_address'].' -> '.$sdata['user']['useragent'].'<br />';
#			if($sdata['user']['robot'] !== FALSE) {
#				$s['bot'] = $sdata['user']['robot'];
#				$count_bot++;
				//continue;
#			}
#			if($sdata['user']['logged_in'] === TRUE) {
 #               $s['user'] = link_user($sdata['user_id'], $sdata['username']);
#				$count_reg++;
				//continue;
#			}
		}
//var_dump(implode($s,"<br>"));
/*		
		$users = array_unique($userlist);
		$bots = array_unique($botlist);
		$data = array(
            'total' => $count_all,
            'registered' => count($users),
            'userlist' => implode(', ', $users),
            'botlist' =>  implode(', ', $bots),
            'countbot'=>  count($bots)
        );
*/


//        die();
#        $this->template->title = 'Список пользователей';
#        $this->template->content->view('admin/utils', $data);
#        $this->template->publish();

    }

	function DecodeSession($sess_string)
	{
    	// save current session data
    	//   and flush $_SESSION array
    	$old = $_SESSION;
	    $_SESSION = array();

    	// try to decode passed string
	    $ret = session_decode($sess_string);
    	if (!$ret) {
        	// if passed string is not session data,
        	//   retrieve saved (old) session data
        	//   and return false
        	$_SESSION = array();
        	$_SESSION = $old;

        	return false;
	    }

    	// save decoded session data to sess_array
	    //   and flush $_SESSION array
	    $sess_array = $_SESSION;
	    $_SESSION = array();

	    // restore old session data
    	$_SESSION = $old;

	    // return decoded session data
	    return $sess_array;
	}

}