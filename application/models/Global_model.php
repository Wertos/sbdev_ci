<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_model extends CI_Model {

    function categories() {
        	return $this->db->select('c.*, COUNT(t.id) AS count')
       					->from('categories c')
					->join('torrents t', 't.category = c.id', 'left')
					->group_by('c.id')
                	->order_by('c.sort', 'asc')
                	->get()->result();
    }

    function un_modded() {
		if(!$unmodded = CACHE()->get('unmodded')) {
	        $this->db->where('modded', 'no')
    	   				->from('torrents');
            
            $unmodded = $this->db->count_all_results();
            $unmodded = $unmodded > 0 ? $unmodded : 'null';
            CACHE()->save('unmodded', $unmodded, CACHE_LIFE_TIME());
        }
        return $unmodded;
    }

}