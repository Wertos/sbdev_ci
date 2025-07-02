<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    function __construct() {
        parent::__construct();

        //add global breadcrumb of homepage
        $this->breadcrumb->prepend('<span class="glyphicon glyphicon-home"></span>');
        $this->load->model('global_model');
        $this->load->library('user_agent');
        $this->load->helper('pag');

		# Copy Ip block start
		$this->load->library('ipblock');
		$rkn = new Roscomsos();
		$rkn->update_data();
		if($rkn->check_ip($this->input->ip_address())) {
			show_404();
		}
		
		# Copy Ip block end

		# Social connect
		if ($this->config->item('vkontakte', 'social')['enable'] === TRUE) {
			$this->load->library('social/vkontakte', $this->config->item('vkontakte', 'social'));
		}
		if ($this->config->item('facebook', 'social')['enable'] === TRUE) {
			$this->load->library('social/fasebook', $this->config->item('facebook', 'social'));
		}
		# Social connect
		
		global $logged_in;
        if ($this->_logged_in()) {
            $logged_in = TRUE;
            //load library if user logged in
            $this->load->library('ion_auth');
        } else {
            $logged_in = FALSE;
        }
        ###template 
        $this->template->title->default($this->config->item('site_descr'));
        $this->template->description->default($this->config->item('site_descr'));
        $this->template->powered = 'Powered by sbdev_ci & <a href="'.base_url().'">'.$this->config->item('site_name').'</a> & Codeigniter ver. '.CI_VERSION;
        $this->template->loadtime = 'Executed in ' . $this->benchmark->elapsed_time() . ' (' . $this->benchmark->memory_usage() . ')';
        $this->template->info = ($this->session->flashdata('info') ? '<div class="alert alert-info fade in" role="alert"><button data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>' . $this->session->flashdata('info') . '</div>' : '');

//        $this->template->stylesheet->add("public/assets/bootstrap/css/bootstrap.min.css");
//        $this->template->stylesheet->add("public/assets/bootstrap/css/bootstrap-theme.min.css");

        $this->template->stylesheet->add("public/assets/bootstrap/css/metro.min.css" . $this->config->item('cssjsver'));
        $this->template->stylesheet->add("public/assets/bootstrap/css/glyphicons-social.css" . $this->config->item('cssjsver'));
        $this->template->stylesheet->add("public/assets/" . $this->config->item('default_theme') . ".css" . $this->config->item('cssjsver'));

        $this->template->javascript->add("public/assets/js/jquery.min.js" . $this->config->item('cssjsver'));
        $this->template->javascript->add("public/assets/bootstrap/js/bootstrap.min.js" . $this->config->item('cssjsver'));
        $this->template->javascript->add("public/assets/js/main.js" . $this->config->item('cssjsver'));
        if(!$this->agent->is_robot()) {
//			$this->template->javascript->add("public/assets/js/slide.js");
		}
        
        $this->template->ajax = '
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModal" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-body" id="modal-body">
                    </div>
                </div>
            </div>
        </div>


        <div id="loading" class="text-center">
            <p>Подождите, загружаю...</p>
            <p class="text-center">' . img('public/assets/pic/ajax-loader.gif') . '</p>
        </div>
        ';
        
        ###template



        ###enable cache
        if (!$cats = CACHE()->get('sbdev_cats')) {
            $cats = $this->global_model->categories();
            // Save into the cache for 5 minutes
            CACHE()->save('sbdev_cats', $cats, 300);
        }
        ###enable cache

        $this->data = array(
            'curuser' => ($logged_in ? $this->ion_auth->user()->row() : FALSE),
            'admin_mod' => ($logged_in ? $this->ion_auth->in_group(array('admin', 'moderator')) : FALSE),
            'logged_in' => $logged_in,
            'categories' => $cats
        );


        //make global for views
        $this->load->vars($this->data);

        if(!$this->input->is_ajax_request()) {
		  ($logged_in ? ($this->ion_auth->is_admin() ? $this->output->enable_profiler(TRUE) : '') : '');
		}
		
		// set session user data
		$data = [
	      'useragent' => $this->agent->agent_string(),
		  'logged_in' => $logged_in,
		  'robot'     => $this->agent->is_robot() ? $this->agent->robot() : FALSE,
		  'ref'       => $this->agent->referrer() ?? FALSE,
		  'proxy'     => proxycheck()
		];
		$this->session->set_userdata('user', $data);
		unset($data);
    }

    private function _logged_in() {
        $this->load->library('session');
        $this->load->model('ion_auth_model');
        $this->ion_auth_model->trigger_events('logged_in');

        return (bool) $this->session->userdata('identity');
    }

}