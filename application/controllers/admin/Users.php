<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends MY_Controller {

    function __construct() {
        parent::__construct();


        if (!$this->ion_auth->is_admin())
            show_404();

        $this->load->library('form_validation');
        $this->load->library('session');
        $this->load->library("pagination");
        $this->load->helper('form');
        $this->load->helper('security');
        $this->load->model('admin/users_model', 'users');
    }

    public function index() {


        if ($this->input->post('username'))
            $username = xss_clean($this->input->post('username'));
        else
            $username = null;

        $limit = 50;

        $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;

        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $results = $this->users->all($username, $limit, $offset);


        //  $data['num_results'] = $results['num_rows'];
        // pagination
        $config = array();
        $config['base_url'] = site_url("admin/users/");
        $config['total_rows'] = $results['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 6;
        $config['prefix'] = '/page/';
        $this->pagination->initialize($config);

        $data['usersearch'] = array(
            'name' => 'username',
            'class' => 'form-control',
            'type' => 'text',
            'placeholder' => 'Поиск'
        );

        $data['pagination'] = $this->pagination->create_links();
        $data['results'] = $results['rows'];
        $data['num_results'] = $results['num_rows'];

        $this->template->title = 'Список пользователей';
        $this->template->content->view('admin/users', $data);
        $this->template->publish();
    }

    public function delete($id) {

        $id = (int) $id;

        $user = $this->ion_auth->user($id)->row();

        if (!$user)
            show_404();

        //we don't want users to edit own details
        if ($id === $this->ion_auth->user()->row()->id)
            show_error('Нельзя так делать');



        if ($this->ion_auth->delete_user($id)) {

            $this->session->set_flashdata('info', 'Пользователь успешно удалён');

            ///delete avatar file if exist
            if (file_exists('public/upload/avatars/' . $user->userfile))
                @unlink('public/upload/avatars/' . $user->userfile);
        } else {
            $this->session->set_flashdata('info', $this->ion_auth->errors());
        }

        redirect('admin/users', 'refresh');
    }

    function edit($id) {

        $id = (int) $id;

        $user = $this->ion_auth->user($id)->row();

        if (!$user)
            show_404();

        //we don't want users to edit own details
        if ($id == $this->ion_auth->user()->row()->id)
            show_error('Нельзя так делать');

        $this->load->helper('language');



        $groups = $this->ion_auth->groups()->result_array();
        $currentGroups = $this->ion_auth->get_users_groups($id)->result();



        if ($this->input->post('username') != $user->username)
            $this->form_validation->set_rules('username', 'Имя пользователя', 'required|callback_username_check');

        if ($this->input->post('email') != $user->email)
            $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');

        //validate password if it was posted
        if ($this->input->post('password')) {
            $this->form_validation->set_rules('password', $this->lang->line('edit_user_validation_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this->form_validation->set_rules('password_confirm', $this->lang->line('edit_user_validation_password_confirm_label'), 'required');
        }


        $this->form_validation->set_rules('country', 'Страна', 'is_natural_no_zero');
        $this->form_validation->set_rules('groups', $this->lang->line('edit_user_validation_groups_label'), 'xss_clean');



        if ($this->form_validation->run() == FALSE) {

            ###generate country list
            $this->load->model('user_model');

            $country = "<select name=\"country\" class=\"form-control\">\n";
            foreach ($this->user_model->list_all_countries() as $item) {
                $country .= "<option value=\"" . $item->id . "\"";
                if ($item->id == $user->country)
                    $country .= " selected=\"selected\"";
                $country .= ">" . $item->name . "</option>\n";
            }
            $country .= "</select>\n";


            $data = array(
                'user' => $user,
                'message' => validation_errors()
            );

            $data['country'] = $country;

            $data['username'] = array(
                'name' => 'username',
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->form_validation->set_value('username', $user->username),
            );
            $data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this->form_validation->set_value('email', $user->email),
            );


            $data['can_comment'] = form_checkbox('can_comment', 'accept', ($user->can_comment === 'yes' ? TRUE : FALSE));
            $data['can_upload'] = form_checkbox('can_upload', 'accept', ($user->can_upload === 'yes' ? TRUE : FALSE));
            $data['del_avatar'] = form_checkbox('del_avatar', 'accept');


            $data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'class' => 'form-control',
                'type' => 'password'
            );
            $data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'class' => 'form-control',
                'type' => 'password'
            );

            $data['groups'] = $groups;
            $data['currentGroups'] = $currentGroups;




            $this->template->title = $user->username;
            $this->template->content->view('admin/edit_user', $data);
            $this->template->publish();
        } else {



            $data = array(
                'username' => $this->input->post('username'),
                'country' => $this->input->post('country'),
                'email' => $this->input->post('email'),
                'can_upload' => ($this->input->post('can_upload') ? 'yes' : 'no'),
                'can_comment' => ($this->input->post('can_comment') ? 'yes' : 'no')
            );

            if ($this->input->post('del_avatar')) {
                $data['userfile'] = '';
                if (file_exists('public/upload/avatars/' . $user->userfile))
                    unlink('public/upload/avatars/' . $user->userfile);
            }

            $groupData = $this->input->post('groups');

            if (isset($groupData) && !empty($groupData)) {

                $this->ion_auth->remove_from_group('', $id);

                foreach ($groupData as $grp) {
                    $this->ion_auth->add_to_group($grp, $id);
                }
            }



            //update the password if it was posted
            if ($this->input->post('password'))
                $data['password'] = $this->input->post('password');



            $this->ion_auth->update($user->id, $data);

            $this->session->set_flashdata('info', 'Пользователь успешно отредактирован');

            redirect('admin/users/edit/' . $id, 'refresh');
        }
    }

    function email_check($str) {
        if ($this->ion_auth->email_check($str)) {
            return FALSE;
        }

        return TRUE;
    }

    function username_check($str) {
        if ($this->ion_auth->identity_check($str)) {
            return FALSE;
        }

        return TRUE;
    }

}