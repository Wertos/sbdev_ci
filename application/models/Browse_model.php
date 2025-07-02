<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Browse_model extends CI_Model {

    public function get_all_home($cid, $start = 0) {
        return $this->db->select($this->config->item('db_select_str'))
                        ->from('torrents')
                        ->where(array('category' => $cid, 'modded' => 'yes'))
//                        ->where('added BETWEEN UNIX_TIMESTAMP(DATE_SUB(NOW(), INTERVAL 1 DAY)) AND UNIX_TIMESTAMP(NOW())')
//                        ->where('added > UNIX_TIMESTAMP(CURDATE())')
                        ->order_by("id", "desc")
                        ->limit($this->config->item('home_per_cat'), $start)
                        ->get()
                        ->result();
    }
    
	function sitemap() {
        return $this->db->select('id, url')
                ->from('torrents')->get()->result();
    }


    function browse($limit, $offset) {

        // results query
        $q = $this->db->select($this->config->item('db_select_str'))
                ->from('torrents')
                ->where('modded', 'yes')
                ->limit($limit, $offset)
                ->order_by('id', 'desc');

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('modded', 'yes');
        $this->db->from('torrents');

        $ret['num_rows'] = $this->db->count_all_results();


        return $ret;
    }

    function search($str, $limit, $offset) {

        // results query
        $q = $this->db->select($this->config->item('db_select_str'))
                ->from('torrents')
                ->where('modded = "yes" AND MATCH (name) AGAINST ("' . $str . '" IN BOOLEAN MODE)', NULL, FALSE)
                ->limit($limit, $offset);

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('modded = "yes" AND MATCH (name) AGAINST ("' . $str . '" IN BOOLEAN MODE)', NULL, FALSE);
        $this->db->from('torrents');

        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    function get_by_category($limit, $offset, $cid) {

        // results query
        $q = $this->db->select($this->config->item('db_select_str'))
                ->from('torrents')
                ->where(array('category' => $cid, 'modded' => 'yes'))
                ->limit($limit, $offset)
                ->order_by('id', 'desc');

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where(array('category' => $cid, 'modded' => 'yes'));
        $this->db->from('torrents');

        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    function get_by_owner($limit, $offset, $uid) {

        // results query
        $q = $this->db->select($this->config->item('db_select_str'))
                ->from('torrents')
                ->where(array('owner' => $uid, 'modded' => 'yes'))
                ->limit($limit, $offset)
                ->order_by('id', 'desc');

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where(array('owner' => $uid, 'modded' => 'yes'));
        $this->db->from('torrents');

        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    // Формирование RSS-ленты
    public function feeds_info($cat_id) {
				if($cat_id !== NULL) {
					$this->db->where(array('category' => $cat_id ,'modded' => 'yes'));				
				} else {
					$this->db->where(array('modded' => 'yes'));				
				}
				$this->db->order_by('id','desc');
				$this->db->limit(50);
				$query = $this->db->get('torrents');
		//Возвращаем массив с материалами для формирования ленты
				return $query->result_array();
    }
}