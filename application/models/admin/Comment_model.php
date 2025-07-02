<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Comment_model extends CI_Model {

    function get_comments($limit, $offset) {
        // results query
        $q = $this->db->select('c.fid, c.id, c.text, c.user, c.added, c.editedby, u.username, u.userfile, e.username AS editedbyname, t.url, t.name AS torrentname')
                ->from('comments c')
                ->join('users u', 'c.user = u.id', 'left')
                ->join('users e', 'c.editedby = e.id', 'left')
                ->join('torrents t', 'c.fid = t.id', 'left')
                ->limit($limit, $offset)
                ->order_by('c.id', 'desc');


        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->from('comments');
        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    function get_user_comments($id, $limit, $offset) {
        // results query
        $q = $this->db->select('c.fid, c.id, c.text, c.user, c.added, c.editedby, u.username, u.userfile, e.username AS editedbyname, t.url, t.name AS torrentname')
                ->from('comments c')
                ->join('users u', 'c.user = u.id', 'left')
                ->join('users e', 'c.editedby = e.id', 'left')
                ->join('torrents t', 'c.fid = t.id', 'left')
                ->where('c.user', $id)
                ->limit($limit, $offset)
                ->order_by('c.id', 'desc');


        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('user', $id);
        $this->db->from('comments');
        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

}