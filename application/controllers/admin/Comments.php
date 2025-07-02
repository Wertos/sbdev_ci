<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Comments extends MY_Controller {

    function __construct() {
        parent::__construct();


        if (!$this->data['admin_mod'])
            show_404();

        $this->load->library('session');
        $this->load->model('admin/comment_model', 'comments');
        $this->load->model('comments_model', 'comm');
        $this->load->library("pagination");
        //$this->load->library('jbbcode');
        $this->load->helper('smiley');
		$this->load->library('BBCodeParser');
    }
    
    public function index() {
        redirect('admin/comments/all');
    }

    public function all() {

        $limit = $this->config->item('comm_per_page');
        $page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $comments = $this->comments->get_comments($limit, $offset);


        $config = array();
        $config['base_url'] = site_url("admin/comments/all");
        $config['total_rows'] = $comments['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 5;
        $config['prefix'] = '/page/';
        $this->pagination->initialize($config);


        $data = array(
            'comments' => $comments['rows'],
            'count' => $comments['num_rows'],
            'pagination' => $this->pagination->create_links(),
        );



        $this->template->title = 'Комментарии';
        $this->template->content->view('admin/comments', $data);
        $this->template->publish();
    }

    public function user($uid) {

        $uid = (int) $uid;

        $limit = $this->config->item('comm_per_page');
        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $comments = $this->comments->get_user_comments($uid, $limit, $offset);


        $config = array();
        $config['base_url'] = site_url("admin/comments/user/" . $uid);
        $config['total_rows'] = $comments['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['prefix'] = '/page/';
        $this->pagination->initialize($config);


        $data = array(
            'comments' => $comments['rows'],
            'count' => $comments['num_rows'],
            'pagination' => $this->pagination->create_links(),
        );



        $this->template->title = 'Комментарии';
        $this->template->content->view('admin/comments', $data);
        $this->template->publish();
    }

}