<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Torrents extends MY_Controller {

    function __construct() {
        parent::__construct();

        if (!$this->ion_auth->in_group(array('admin', 'moderator')))
            show_404();

        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->helper('form');
        $this->load->model('admin/torrents_model', 'torrents');
        $this->load->model('details_model');
    }

    public function modded($id = FALSE) {

        if ($id) {
            $id = (int) $id;

            if (!$this->details_model->get_details($id))
                show_404();

            $this->torrents->do_modded($id);

            $this->session->set_flashdata('info', 'Торрент был отмечен как проверен');

            redirect('admin/torrents/modded', 'refresh');

            die;
        }



        $result = $this->torrents->modded();


        $data = array(
            'items' => $result['rows'],
            'num_items' => $result['num_rows'],
        );


        $this->template->title = 'Торренты ожидающие проверку';
        $this->template->content->view('admin/modded', $data);
        $this->template->publish();
    }

    public function delete($id) {

        $id = (int) $id;


        if (!$this->details_model->get_details($id))
            show_404();


        $this->details_model->delete_torrent($id); ///inserting data


        if (file_exists('public/upload/torrents/' . $id . '.torrent'))
            unlink('public/upload/torrents/' . $id . '.torrent'); //deleting the file

        $this->session->set_flashdata('info', 'Торрент был успешно удалён!');

        redirect('admin/torrents/modded', 'refresh');
    }

}