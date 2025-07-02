<?php

function updatestats($tid, $del = 0)
{
//echo PHP_EOL.$del.PHP_EOL;
		$CI =& get_instance();

        $CI->load->library('parsing');
        $CI->load->model('details_model');

        $tid = intval($tid);

        $details = $CI->details_model->get_details($tid);
        $trackers = $CI->details_model->get_trackers($tid);

        if (!$details || !$trackers) {
            die("Нет такого торрента! $tid".PHP_EOL);
        }

        $scrapeTime = $CI->config->item('update_torrent_time');

        $dt_time = get_date_time(strtotime(get_date_time()) - $scrapeTime * 60);


        if ($details->last_update > $dt_time) {
//            die("Разрешено обновлять статистику не чаще 1 раза за " . $scrapeTime . " минут.");
        }

        $timeout = 3;
        $maxread = 1024 * 4;

        $udp = new udptscraper($timeout);
        $http = new httptscraper($timeout, $maxread);

        $seeders = $leechers = $completed = 0;
        foreach ($trackers as $work) {
            try {
                if (substr($work->url, 0, 6) == 'udp://') {
                    //var_dump($work->url);
                    $data = $udp->scrape($work->url, $work->info_hash);
                } else {
                    $data = $http->scrape($work->url, $work->info_hash);
                }
                $data = $data[$work->info_hash];
                $seeders = $seeders + intval($data['seeders']);
                $leechers = $leechers + intval($data['leechers']);
                $completed = $completed + intval($data['completed']);
                $array = array(
                    'state' => 'ok',
                    'error' => '',
                    'seeders' => intval($data['seeders']),
                    'leechers' => intval($data['leechers']),
                    'completed' => intval($data['completed']),
                    'last_update' => get_date_time()
                );
                $CI->details_model->update_torrents_scraper($tid, $work->url, $array); ///inserting data
            } catch (ScraperException $e) {
                $array = array(
                    'state' => 'error',
                    'error' => $e->getMessage(),
                    'seeders' => 0,
                    'leechers' => 0,
                    'completed' => 0,
                    'last_update' => get_date_time()
                );
                $CI->details_model->update_torrents_scraper($tid, $work->url, $array); ///inserting data
				if($del == 1) {
				  $CI->db->delete('torrents_scrape', ['tid' => $tid, 'url' => $work->url]);
				}
				//$CI->db->limit(1);
            }
        }
        //die();
        $array = array(
            'seeders' => $seeders,
            'leechers' => $leechers,
            'completed' => $completed,
            'last_update' => get_date_time(),
			'updated'     => 1
        );
        $CI->details_model->update_info($tid, $array); ///updating data
        //var_dump($array);
		if (PHP_SAPI === 'cli') return;
        
        if (!$CI->input->is_ajax_request()) {
			return;
        } else { // ajax calls
            $CI->load->view('templates/' . $CI->config->item('default_theme') . '/helpers/peers', $array);
        }


}

