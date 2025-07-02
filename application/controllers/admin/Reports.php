<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->data['admin_mod'])
            show_404();

        $this->load->library('session');
        $this->load->model('admin/reports_model', 'reports');
        $this->load->library("pagination");
        
    }

    public function index() {
        redirect('admin/reports/torrents');
    }

    public function torrents() {

        $limit = 20;
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $reports = $this->reports->get_reports_torrent($limit, $offset);


        $config = array();
        $config['base_url'] = site_url("admin/reports/torrents");
        $config['total_rows'] = $reports['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;
        $config['prefix'] = '/page/';
        $this->pagination->initialize($config);


        $data = array(
            'reports' => $reports['rows'],
            'count' => $reports['num_rows'],
            'pagination' => $this->pagination->create_links(),
        );

        $this->template->title = 'Жалобы на торренты';
        $this->template->content->view('admin/reports', $data);
        $this->template->publish();
    }

    public function comments() {
        $this->load->library('jbbcode');
        $this->load->helper('smiley');

        $limit = 20;
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $reports = $this->reports->get_reports_comments($limit, $offset);


        $config = array();
        $config['base_url'] = site_url("admin/reports/comments");
        $config['total_rows'] = $reports['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;
        $config['prefix'] = '/page/';
        $this->pagination->initialize($config);


        $data = array(
            'reports' => $reports['rows'],
            'count' => $reports['num_rows'],
            'pagination' => $this->pagination->create_links(),
        );

        $this->template->title = 'Жалобы на комментарии';
        $this->template->content->view('admin/reports', $data);
        $this->template->publish();
    }

    public function mark($id, $c = 0) {

        $id = (int) $id;

        if (!$this->db->get_where('reports', array('id' => $id))->row())
            show_404();


        $this->reports->update($id, array('modded_by' => $this->data['curuser']->id));

        $this->session->set_flashdata('info', 'Жалоба была успешно отмечена');

        if ($c == 0)
            redirect('admin/reports/torrents', 'refresh');
        else
            redirect('admin/reports/comments', 'refresh');
    }

    public function delete($id, $c = 0) {

        $id = (int) $id;

        if (!$this->db->get_where('reports', array('id' => $id))->row())
            show_404();

        $this->reports->delete($id);

        $this->session->set_flashdata('info', 'Жалоба успешно удалена');

        if ($c == 0)
            redirect('admin/reports/torrents', 'refresh');
        else
            redirect('admin/reports/comments', 'refresh');
    }

}