<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Utils extends MY_Controller {

    function __construct() {
        parent::__construct();


        if (!$this->ion_auth->is_admin())
            show_404();

#        $this->load->library('form_validation');
#        $this->load->library('session');
#        $this->load->library("pagination");
#        $this->load->helper('form');
#        $this->load->helper('security');
#        $this->load->model('admin/users_model', 'users');
		$this->load->dbutil();
	}

    public function index() {
//        var_dump($this->db->database);  die();
        $query = $this->db->query('SHOW TABLES');
		foreach ($query->result_object() as $row)
		{
			//$data['tblname'] = $row->Tables_in_torr;
			$data['tdlsize'] = $this->db->query("SHOW TABLE STATUS LIKE '$row->Tables_in_torr'");
			var_dump($data);
die();
		}         
        

        $this->template->title = 'Работа с базой данныз';
        $this->template->content->view('admin/dbutil', $data);
        $this->template->publish();
    }
}