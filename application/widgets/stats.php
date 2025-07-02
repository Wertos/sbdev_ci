<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stats extends Widget {

    public function display() {
        if (!$stats = CACHE()->get('sbdev_stats')) {
            $this->db->select_sum('leechers')
            		 ->select_sum('seeders')
            		 ->select_sum('size');
            $stats = $this->db->where('modded', 'yes')->get('torrents')->row();
            $stats->count = $this->db->where('modded', 'yes')->count_all_results('torrents', false);
            CACHE()->save('sbdev_stats', $stats, CACHE_LIFE_TIME());
        }
        //print_r($stats);
        $data = [
            'torrents' => $stats->count,
            'seeders' => $stats->seeders,
            'leechers' => $stats->leechers,
            'size' => byte_format($stats->size)
        ];

        $this->view('widgets/stats', $data);
    }

}