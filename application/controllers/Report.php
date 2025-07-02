<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->database();
        $this->load->library('form_validation');

        if (!$this->input->is_ajax_request())
            die('Ajax only!');
    }

    public function send($id, $location) {

        $id = (int) $id;

        if (in_array($location, array('torrents', 'comments'))) {
            $location = $location;
            $this->db->where('id', $id);
            $this->db->from($location);
            if ($this->db->count_all_results() == 0)
                die('К чему вы жалобу пишете?');
        } else {
            die('К чему вы жалобу пишете?');
        }

        $curuser = $this->data['curuser'];

        /// look if user allready sent a report fot this $location and $id
        // count query
        $this->db->where('fid', $id);
        $this->db->where('location', $location);
        /// if logged in than look up by ID if not than IP
        ($curuser ? $this->db->where('sender', $curuser->id) : $this->db->where('ip', $this->input->ip_address()));
        $this->db->from('reports');

        if ($this->db->count_all_results() > 0) {
            echo '<div class="alert alert-danger" style="margin-bottom: 0px;">Вы уже отправили жалобу!</div>';
            die;
        }

        $this->form_validation->set_rules('report', 'Жалоба', 'trim|required|min_length[' . $this->config->item('comm_min_lengh') . ']|max_length[' . $this->config->item('comm_max_lengh') . ']');

        if ($this->form_validation->run() == TRUE) {

            $data = array(
                'fid' => $id,
                'comment' => $this->input->post('report', TRUE),
                'added' => time(),
                'location' => $location,
                'sender' => ($curuser ? $curuser->id : 0),
                'ip' => $this->input->ip_address()
            );

            $this->db->insert('reports', $data);

            $this->session->set_flashdata('message', 'Жалоба успешно отправлена!');

            die;
        } else {
            $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/report', array('id' => $id, 'value' => $this->form_validation->set_value('report'), 'location' => $location));
        }
    }

}