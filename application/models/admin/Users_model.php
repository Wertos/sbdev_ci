<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users_model extends CI_Model {

    function all($username, $limit, $offset) {
        // results query
        $q = $this->db->select('*')
                ->from('users')
                ->like('username', $username)
                ->limit($limit, $offset)
                ->order_by('id', 'asc');

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->like('username', $username);
        $this->db->from('users');

        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

}