<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reports_model extends CI_Model {

    function get_reports_torrent($limit, $offset) {

        // results query
        $q = $this->db->select('r.*, u.username AS sendername, m.username AS mod_by, t.url, t.name AS torrentname')
                ->from('reports r')
                ->join('users u', 'r.sender = u.id', 'left')
                ->join('users m', 'r.modded_by = m.id', 'left')
                ->join('torrents t', 'r.fid = t.id', 'left')
                ->where('r.location', 'torrents')
                ->limit($limit, $offset)
                ->order_by('r.id', 'desc');


        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('location', 'torrents');
        $this->db->from('reports');
        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    function get_reports_comments($limit, $offset) {

        // results query
        $q = $this->db->select('r.*, u.username AS sendername, m.username AS mod_by, c.text AS comment_text')
                ->from('reports r')
                ->join('users u', 'r.sender = u.id', 'left')
                ->join('users m', 'r.modded_by = m.id', 'left')
                ->join('comments c', 'r.fid = c.id', 'left')
                ->where('r.location', 'comments')
                ->limit($limit, $offset)
                ->order_by('r.id', 'desc');


        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('location', 'comments');
        $this->db->from('reports');
        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }
    
    
    function delete($id) {
        $this->db->delete('reports', array('id' => $id));
    }
    
    function update($id, $data) {
        $this->db->update('reports', $data, 'id = ' . $id);
    }
    

}