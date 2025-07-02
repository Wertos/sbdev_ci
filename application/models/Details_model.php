<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Details_model extends CI_Model {

    function get_details($id) {
		if(!$details = CACHE()->get(md5('details_'.$id))) {
		  $q = $this->db->select('t.*, c.name AS catname, c.url AS caturl, u.username, r.modded_by AS report')
                ->from('torrents t')
                ->join('categories c', 'c.id = t.category', 'left')
                ->join('users u', 'u.id = t.owner', 'left')
                ->join('reports r', 'r.fid = '.$id, 'left')
                ->where('t.id', $id)
                ->limit(1);
		  $details = $q->get()->row();
		  CACHE()->save(md5('details_'.$id), $details, CACHE_LIFE_TIME());
		}
		return $details;
    }

    function get_trackers($id) {
		if(!$trackers = CACHE()->get(md5('trackers_'.$id))) {
		  $query = $this->db->get_where('torrents_scrape', array('tid' => $id));
		  $trackers = $query->result();
		  CACHE()->save(md5('trackers_'.$id), $trackers, CACHE_LIFE_TIME());
        }
		return $trackers;
    }

    function get_files($id) {
			if(!$files = CACHE()->get(md5('files_'.$id))) {
	        $query = $this->db->get_where('files', array('torrent' => $id));
	        $files = $query->result();
					CACHE()->save(md5('files_'.$id), $files, CACHE_LIFE_TIME());
	    }
        return $files;
    }

    function get_comments($id, $limit, $offset) {
        // results query
		if(!$ret = CACHE()->get(md5('comments_'.$id))) {
        	$q = $this->db->select('c.id, c.text, c.user, c.added, c.editedby, u.username, u.userfile, e.username AS editedbyname')
                ->from('comments c')
                ->join('users u', 'c.user = u.id', 'left')
                ->join('users e', 'c.editedby = e.id', 'left')
                ->where('c.fid = ' . $id . ' AND c.location = "torrents"')
                ->limit($limit, $offset)
                ->order_by('c.id', 'desc');

        	$ret['rows'] = $q->get()->result();

        	// count query
        	$this->db->where(array('fid' => $id, 'location' => 'torrents'));
    	    $this->db->from('comments');
	        $ret['num_rows'] = $this->db->count_all_results();
            CACHE()->save(md5('comments_'.$id), $ret, CACHE_LIFE_TIME());
        }
        return $ret;
    }

    function update_info($id, $data) {
        $this->db->update('torrents', $data, 'id = ' . $id);
    }

    function update_view($id) {
        $this->db->set('views', 'views + 1', FALSE)->where('id', $id)->update('torrents');
    }

    function update_torrents_scraper($id, $url, $data) {
        $this->db->where(array('tid' => $id, 'url' => $url));
		$this->db->limit(1);
        $this->db->update('torrents_scrape', $data);
    }

    function add_info($data) {
        $this->db->insert('torrents', $data);

        return $this->db->insert_id();
    }

    function add_trackers($data) {
        $this->db->insert('torrents_scrape', $data);
    }

    function add_files($data) {
        $this->db->insert('files', $data);
    }

    function delete_torrent($id) {
        $this->db->delete('torrents', array('id' => $id));
        $this->db->delete('torrents_scrape', array('tid' => $id));
        $this->db->delete('files', array('torrent' => $id));
        $this->db->delete('comments', array('fid' => $id, 'location' => 'torrents'));
    }

    function get_related($title, $tid, $cid) {
        $words = explode(' ',$title);
		foreach($words as $k){
			if(preg_match("/(rip|mux|screen|lesync)/ui", $k)){
 				unset($k);
   			} else {
   			  	$a[] = $k;
   			}
		}
   		unset($words, $title);
   		$words = $a;
		$title = implode(' ',array_slice($words, 0, 2));
        $title = mb_strtolower($title);
//		$title = trim(preg_replace('/\+\d+/', '', $title));
#        $q = $this->db->select($this->config->item('db_select_str'))
#                ->from('torrents')
#                ->where('MATCH (name) AGAINST ("' . $title . '" IN BOOLEAN MODE) AND id != ' . $tid . ' AND category = ' . $cid, NULL, FALSE)
#                ->limit($this->config->item('related_nr'));

        $q = $this->db->select($this->config->item('db_select_str').', MATCH (name) AGAINST ("' . $title . '" IN BOOLEAN MODE) AS score')
                ->from('torrents')
                ->where('MATCH (name) AGAINST ("' . $title . '" IN BOOLEAN MODE) AND id != ' . $tid . ' AND category = ' . $cid, NULL, FALSE)
                ->order_by('added, score', 'DESC')
                ->limit($this->config->item('related_nr'));

        return $q->get()->result();
    }

    function new_torrent_id() {
        $q = $this->db->query("SHOW TABLE STATUS LIKE 'torrents'");
        $new_id = $q->row_array();
        return $new_id['Auto_increment'];
    }
		
	function bookmarks($id = NULL, $user_id) {
		    
        if($id !== NULL) {
        		$q = $this->db->select('id')->from('bookmarks')->where(array('t_id'=>$id,'user_id'=>$user_id))->limit(1);
        		
        		if($q->get()->row()) {
        				$this->db->delete('bookmarks', array('t_id' => (int) $id, 'user_id' => (int) $user_id));
        						return ['class' => '', 'action' => 'deleted', 'error'  => $this->db->error()];
        		} else {
        				$this->db->insert('bookmarks', array('t_id' => (int) $id, 'user_id' => (int) $user_id));
        						return ['class' => 'book', 'action' => 'add', 'error'  => $this->db->error()];
        		}
        } else {
        //TODO bookmarks list cur user !!!
        }
    }
		
	function check_bookmarks($id = NULL, $user_id) {
	    if($id !== NULL) {
            		$q = $this->db->select('id')->from('bookmarks')->where(array('t_id'=>$id,'user_id'=>$user_id))->limit(1);
        		
        		if($q->get()->row()) {
        						return ['class' => 'book', 'title' => 'Удалить из закладок'];
        		} else {
        						return ['class' => '', 'title' => 'Добавить в закладки'];
        		}
    	    } else {
    				return NULL;
    	    }
	}


}
