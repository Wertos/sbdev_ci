<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Torrents_model extends CI_Model {

    function modded() {

        $q = $this->db->select('t.id, t.modded, t.owner, t.added, t.name, t.url, u.username')
                ->from('torrents t')
                ->join('users u', 'u.id = t.owner', 'left')
                ->where('t.modded', 'no')
                ->order_by('t.id', 'asc');

        $ret['rows'] = $q->get()->result();

        // count query
        $this->db->where('modded', 'no');
        $this->db->from('torrents');

        $ret['num_rows'] = $this->db->count_all_results();

        return $ret;
    }

    function do_modded($id) {

        $this->db->update('torrents', array('modded' => 'yes'), 'id = ' . $id);
    }

    function get($id) {
        return $this->get_where('torrents', array('id' => $id))->row();
    }

}