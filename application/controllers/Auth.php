<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends MY_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->helper('url');
        $this->load->library('ion_auth');

        // Load MongoDB library instead of native db driver if required
        $this->config->item('use_mongodb', 'ion_auth') ?
        $this->load->library('mongo_db') :
        $this->load->database();

        $this->form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'ion_auth'), $this->config->item('error_end_delimiter', 'ion_auth'));

        $this->lang->load('auth');
        $this->load->helper('language');
    }

    //redirect if needed, otherwise display the user list
    function index() {

        if (!$this->ion_auth->logged_in()) {
            //redirect them to the login page
            redirect('auth/login', 'refresh');
        } elseif (!$this->ion_auth->is_admin()) { //remove this elseif if you want to enable this for non-admins
            //redirect them to the home page because they must be an administrator to view this
            return show_error('You must be an administrator to view this page.');
        } else {
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : '';

            //list the users
            $this->data['users'] = $this->ion_auth->users()->result();
            foreach ($this->data['users'] as $k => $user) {
                $this->data['users'][$k]->groups = $this->ion_auth->get_users_groups($user->id)->result();
            }


            $this->data['title'] = "Пользователи";
//            $this->template->load('auth/index', $this->data);
            $this->template->content->view('auth/index', $this->data);
            $this->template->publish();

            //$this->_render_page('auth/index', $this->data);
        }
    }

    //log the user in
    function login() {

        $this->breadcrumb->append('Вход на сайт');
        if($this->data['logged_in'] == TRUE) redirect('/', 'refresh');
        //validate form input
        $this->form_validation->set_rules('identity', 'Пользователь', 'required');
        $this->form_validation->set_rules('password', 'Пароль', 'required');

        if ($this->form_validation->run() == true) {
            //check to see if the user is logging in
            //check for "remember me"
            $remember = (bool) $this->input->post('remember');

            if ($this->ion_auth->login($this->input->post('identity'), $this->input->post('password'), $remember)) {
                //if the login is successful
                //redirect them back to the home page
                $this->session->set_flashdata('info', $this->ion_auth->messages());
                $ref = $this->agent->referrer();
                redirect($ref, 'refresh');
            } else {
                //if the login was un-successful
                //redirect them back to the login page
                $this->session->set_flashdata('info', $this->ion_auth->errors());
                redirect('auth/login', 'refresh'); //use redirects instead of loading views for compatibility with MY_Controller libraries
            }
        } else {
            //the user is not logging in so display the login page
            //set the flash data error message if there is one
            $this->data['message'] = validation_errors();

            $this->data['identity'] = array(
                'name' => 'identity',
                'id' => 'identity',
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->form_validation->set_value('identity'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'class' => 'form-control',
                'type' => 'password',
            );



            $this->template->title = 'Авторизация';
            $this->template->content->view('auth/login', $this->data);
            $this->template->publish();
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

    function signup() {

        $this->breadcrumb->append('Регистрация на сайте');
        
        $this->config->load('recaptcha');
        $this->load->library('recaptcha', $this->config);

        // do not allow registration if logged in
        if ($this->ion_auth->logged_in()) {
            redirect('/');
        }

        //validate form input
        $this->form_validation->set_rules('username', 'Имя пользователя', 'required|callback_username_check');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|callback_email_check');
        $this->form_validation->set_rules('password', 'Пароль', 'required|matches[password_confirm]|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']');
        $this->form_validation->set_rules('password_confirm', 'Подтвердить пароль', 'required');
        $this->form_validation->set_rules('country', 'Страна', 'required|is_natural_no_zero');

        if ($this->form_validation->run() == TRUE) {
            $username = $this->input->post('username', TRUE);
            $email = strtolower($this->input->post('email', TRUE));
            $password = $this->input->post('password', TRUE);
            $captcha = $this->recaptcha->is_valid();
            $additional_data = array(
            	'country' => $this->input->post('country', TRUE)
            );

            /* $additional_data = array(
              'first_name' => $this->input->post('first_name'),
              'last_name' => $this->input->post('last_name'),
              'company' => $this->input->post('company'),
              'phone' => $this->input->post('phone'),
              ); */
        }

        if ($captcha == TRUE AND $this->form_validation->run() == TRUE AND $this->ion_auth->register($username, $password, $email, $additional_data) AND $this->ion_auth->login($username, $password)) {
            
            $this->session->set_flashdata('info', 'Регистрация прошла успешна');
            redirect("user/profile", 'refresh');
        } else {

            $this->data['message'] = '';
            if($captcha === FALSE AND $_POST) {
            	$this->data['message'] = 'Вы не поттвердили каптчу !';
            }
            //display the create user form
            //set the flash data error message if there is one
            $this->data['message'] .= (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : ''));

            $this->load->model('user_model');

            ###generate country list 
            $country = "<select name=\"country\" class=\"form-control\">\n";
            foreach ($this->user_model->list_all_countries() as $item) {
                $country .= "<option value=\"" . $item->id . "\">" . $item->name . "</option>\n";
            }
            $country .= "</select>\n";

            $this->data['country'] = $country;

            $this->data['username'] = array(
                'name' => 'username',
                'id' => 'username',
                'class' => 'form-control',
                'type' => 'text',
                'required' => 'required',
                'pattern' => '[0-9A-Za-zА-Яа-яЁё_-]{3,10}',
                'value' => $this->form_validation->set_value('username'),
            );
            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'class' => 'form-control',
                'type' => 'email',
                'required' => 'required',
                'pattern' => '^[^@\\s]+@[^@\\s]+\\.[^@\\s]+$',
                'placeholder' => 'mail@example.com',
                'value' => $this->form_validation->set_value('email'),
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'class' => 'form-control',
                'type' => 'password',
                'required' => 'required',
                'pattern' => '[\S]{6,10}',
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'class' => 'form-control',
                'type' => 'password',
                'required' => 'required',
                'pattern' => '[\S]{6,10}',
            );
            $this->data['captcha'] = $this->recaptcha->create_box();

            $this->template->title = 'Регистрация';
            $this->template->content->view('auth/signup', $this->data);
            $this->template->publish();
        }
    }

    //change password
    function change_password() {
        $this->form_validation->set_rules('old', $this->lang->line('change_password_validation_old_password_label'), 'required');
        $this->form_validation->set_rules('new', $this->lang->line('change_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
        $this->form_validation->set_rules('new_confirm', $this->lang->line('change_password_validation_new_password_confirm_label'), 'required');

        if (!$this->ion_auth->logged_in()) {
            redirect('auth/login', 'refresh');
        }

        $user = $this->ion_auth->user()->row();

        if ($this->form_validation->run() == false) {
            //display the form
            //set the flash data error message if there is one
            $this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('info');

            $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
            $this->data['old_password'] = array(
                'name' => 'old',
                'id' => 'old',
                'type' => 'password',
            );
            $this->data['new_password'] = array(
                'name' => 'new',
                'id' => 'new',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['new_password_confirm'] = array(
                'name' => 'new_confirm',
                'id' => 'new_confirm',
                'type' => 'password',
                'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
            );
            $this->data['user_id'] = array(
                'name' => 'user_id',
                'id' => 'user_id',
                'type' => 'hidden',
                'value' => $user->id,
            );



            $this->template->title = 'Сменить пароль';
            $this->template->content->view('auth/change_password', $this->data);
            $this->template->publish();
        } else {
            $identity = $this->session->userdata('identity');

            $change = $this->ion_auth->change_password($identity, $this->input->post('old'), $this->input->post('new'));

            if ($change) {
                //if the password was successfully changed
                $this->session->set_flashdata('info', $this->ion_auth->messages());
                $this->logout();
            } else {
                $this->session->set_flashdata('info', $this->ion_auth->errors());
                redirect('auth/change_password', 'refresh');
            }
        }
    }

    //forgot password
    function forgot_password() {

        $this->breadcrumb->append('Восстановить пароль');

        $this->form_validation->set_rules('email', $this->lang->line('forgot_password_validation_email_label'), 'required|valid_email');
        if ($this->form_validation->run() == false) {
            //setup the input
            $this->data['email'] = array('name' => 'email',
                'id' => 'email',
                'class' => 'form-control',
                'required' => 'required',
            );

            // if ($this->config->item('identity', 'ion_auth') == 'username') {
            //   $this->data['identity_label'] = $this->lang->line('forgot_password_username_identity_label');
            // } else {
            $this->data['identity_label'] = $this->lang->line('forgot_password_email_identity_label');
            // }
            //set any errors and display the form
            $this->data['message'] = (validation_errors()) ? validation_errors() : '';


            $this->template->title = 'Восстановить пароль';
            $this->template->content->view('auth/forgot_password', $this->data);
            $this->template->publish();
        } else {
            // get identity from username or email
            // if ($this->config->item('identity', 'ion_auth') == 'username') {
            //     $identity = $this->ion_auth->where('username', strtolower($this->input->post('email')))->users()->row();
            // } else {
            $identity = $this->ion_auth->where('email', strtolower($this->input->post('email')))->users()->row();
            // }
            if (empty($identity)) {
                $this->session->set_flashdata('info', 'Email не найден');
                redirect("auth/forgot_password", 'refresh');
            }

            //run the forgotten password method to email an activation code to the user
            $forgotten = $this->ion_auth->forgotten_password($identity->{$this->config->item('identity', 'ion_auth')});

            if ($forgotten) {
                //if there were no errors
                $this->session->set_flashdata('info', $this->ion_auth->messages());
                redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
            } else {
                $this->session->set_flashdata('info', $this->ion_auth->errors());
                redirect("auth/forgot_password", 'refresh');
            }
        }
    }

    //reset password - final step for forgotten password
    public function reset_password($code = NULL) {
        if (!$code) {
            show_404();
        }

        $user = $this->ion_auth->forgotten_password_check($code);

        if ($user) {
            //if the code is valid then display the password reset form

            $this->form_validation->set_rules('new', $this->lang->line('reset_password_validation_new_password_label'), 'required|min_length[' . $this->config->item('min_password_length', 'ion_auth') . ']|max_length[' . $this->config->item('max_password_length', 'ion_auth') . ']|matches[new_confirm]');
            $this->form_validation->set_rules('new_confirm', $this->lang->line('reset_password_validation_new_password_confirm_label'), 'required');

            if ($this->form_validation->run() == false) {
                //display the form
                //set the flash data error message if there is one
                $this->data['message'] = (validation_errors()) ? validation_errors() : '';

                $this->data['min_password_length'] = $this->config->item('min_password_length', 'ion_auth');
                $this->data['new_password'] = array(
                    'name' => 'new',
                    'id' => 'new',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['new_password_confirm'] = array(
                    'name' => 'new_confirm',
                    'id' => 'new_confirm',
                    'type' => 'password',
                    'pattern' => '^.{' . $this->data['min_password_length'] . '}.*$',
                );
                $this->data['user_id'] = array(
                    'name' => 'user_id',
                    'id' => 'user_id',
                    'type' => 'hidden',
                    'value' => $user->id,
                );
                $this->data['csrf'] = $this->_get_csrf_nonce();
                $this->data['code'] = $code;



                $this->template->title = 'Восстановить пароль';
                $this->template->content->view('auth/reset_password', $this->data);
                $this->template->publish();
            } else {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $user->id != $this->input->post('user_id')) {

                    //something fishy might be up
                    $this->ion_auth->clear_forgotten_password_code($code);

                    show_error($this->lang->line('error_csrf'));
                } else {
                    // finally change the password
                    $identity = $user->{$this->config->item('identity', 'ion_auth')};

                    $change = $this->ion_auth->reset_password($identity, $this->input->post('new'));

                    if ($change) {
                        //if the password was successfully changed
                        $this->session->set_flashdata('info', $this->ion_auth->messages());
                        $this->logout();
                    } else {
                        $this->session->set_flashdata('info', $this->ion_auth->errors());
                        redirect('auth/reset_password/' . $code, 'refresh');
                    }
                }
            }
        } else {
            //if the code is invalid then send them back to the forgot password page
            $this->session->set_flashdata('info', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //activate the user
    function activate($id, $code = false) {
        if ($code !== false) {
            $activation = $this->ion_auth->activate($id, $code);
        } else if ($this->ion_auth->is_admin()) {
            $activation = $this->ion_auth->activate($id);
            redirect("auth/index", 'refresh');
        }

        if ($activation) {
            //redirect them to the auth page
            $this->session->set_flashdata('info', $this->ion_auth->messages());
            redirect("auth/login", 'refresh');
        } else {
            //redirect them to the forgot password page
            $this->session->set_flashdata('info', $this->ion_auth->errors());
            redirect("auth/forgot_password", 'refresh');
        }
    }

    //deactivate the user
    function deactivate($id = NULL) {
        $id = $this->config->item('use_mongodb', 'ion_auth') ? (string) $id : (int) $id;

        $this->load->library('form_validation');
        $this->form_validation->set_rules('confirm', $this->lang->line('deactivate_validation_confirm_label'), 'required');
        $this->form_validation->set_rules('id', $this->lang->line('deactivate_validation_user_id_label'), 'required|alpha_numeric');

        if ($this->form_validation->run() == FALSE) {
            // insert csrf check
            $this->data['csrf'] = $this->_get_csrf_nonce();
            $this->data['user'] = $this->ion_auth->user($id)->row();

            $this->data['title'] = "Дезактивировать пользователя";
            $this->template->content->view('auth/deactivate_user', $this->data);
            $this->template->publish();

        } else {
            // do we really want to deactivate?
            if ($this->input->post('confirm') == 'yes') {
                // do we have a valid request?
                if ($this->_valid_csrf_nonce() === FALSE || $id != $this->input->post('id')) {
                    show_error($this->lang->line('error_csrf'));
                }

                // do we have the right userlevel?
                if ($this->ion_auth->logged_in() && $this->ion_auth->is_admin()) {
                    $this->ion_auth->deactivate($id);
                }
            }

            //redirect them back to the auth page
            redirect('auth/index', 'refresh');
        }
    }

    function _get_csrf_nonce() {
        $this->load->helper('string');
        $key = random_string('alnum', 8);
        $value = random_string('alnum', 20);
        $this->session->set_flashdata('csrfkey', $key);
        $this->session->set_flashdata('csrfvalue', $value);

        return array($key => $value);
    }

    function _valid_csrf_nonce() {
        if ($this->input->post($this->session->flashdata('csrfkey')) !== FALSE &&
                $this->input->post($this->session->flashdata('csrfkey')) == $this->session->flashdata('csrfvalue')) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    function logout() {
        $this->data['title'] = "Logout";
				//log the user out
        $logout = $this->ion_auth->logout();
				//redirect them to the login page
        $this->session->set_flashdata('info', $this->ion_auth->messages());
        redirect('/', 'refresh');
    }

    /**
    * Create a new group
    */
     public function create_group()
     {
        $this->data['title'] = $this->lang->line('create_group_title');
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
          redirect('auth/index', 'refresh');
        }
        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('create_group_validation_name_label'), 'required|alpha_dash');

        if ($this->form_validation->run() === TRUE)
        {
          $new_group_id = $this->ion_auth->create_group($this->input->post('group_name'), $this->input->post('description'));
          if ($new_group_id)
          {
            // check to see if we are creating the group
            // redirect them back to the admin page
            $this->session->set_flashdata('message', $this->ion_auth->messages());
            redirect("auth/index", 'refresh');
          }
        }
        else
        {
          // display the create group form
          // set the flash data error message if there is one
          $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

          $this->data['group_name'] = array(
                'class' => 'form-control',
		        'name'  => 'group_name',
    		    'id'    => 'group_name',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('group_name'),
          );
          $this->data['description'] = array(
                'class' => 'form-control',
                'name'  => 'description',
                'id'    => 'description',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('description'),
          );

     			$this->template->content->view('auth/create_group', $this->data);
    			$this->template->publish();
        }
      }

      /**
      * Edit a group
      *
      * @param int|string $id
      */
      public function edit_group($id)
      {
        // bail if no group id given
        if (!$id || empty($id))
        {
          redirect('auth/index', 'refresh');
        }

        $this->data['title'] = $this->lang->line('edit_group_title');
        if (!$this->ion_auth->logged_in() || !$this->ion_auth->is_admin())
        {
           redirect('auth/login', 'refresh');
        }
        $group = $this->ion_auth->group($id)->row();

        // validate form input
        $this->form_validation->set_rules('group_name', $this->lang->line('edit_group_validation_name_label'), 'required|alpha_dash');

        if (isset($_POST) && !empty($_POST))
        {
          if ($this->form_validation->run() === TRUE)
          {
            $group_update = $this->ion_auth->update_group($id, $_POST['group_name'], $_POST['group_description']);
            if ($group_update)
              {
                $this->session->set_flashdata('message', $this->lang->line('edit_group_saved'));
              }
              else
              {
                $this->session->set_flashdata('message', $this->ion_auth->errors());
              }
              redirect("auth/index", 'refresh');
           }
        }

        // set the flash data error message if there is one
        $this->data['message'] = (validation_errors() ? validation_errors() : ($this->ion_auth->errors() ? $this->ion_auth->errors() : $this->session->flashdata('message')));

        // pass the user to the view
        $this->data['group'] = $group;

        $readonly = $this->config->item('admin_group', 'ion_auth') === $group->name ? 'readonly' : '';

        $this->data['group_name'] = array(
                'class' => 'form-control',
                'name'    => 'group_name',
                'id'      => 'group_name',
                'type'    => 'text',
                'value'   => $this->form_validation->set_value('group_name', $group->name),
                $readonly => $readonly,
        );
        $this->data['group_description'] = array(
                'class' => 'form-control',
                'name'  => 'group_description',
                'id'    => 'group_description',
                'type'  => 'text',
                'value' => $this->form_validation->set_value('group_description', $group->description),
         );

         $this->template->content->view('auth/edit_group', $this->data);
         $this->template->publish();
      }
}

