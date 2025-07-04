<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this
            ->breadcrumb
            ->append('Личный кабинет', 'user/profile');

        $this
            ->load
            ->model('user_model');
        $this
            ->load
            ->library('Datatables');
        $this
            ->template
            ->content
            ->view('helpers/user_tabs');
    }

    public function index($id = 0)
    {
        $this
            ->load
            ->library('ion_auth');

        if (!$this
            ->input
            ->is_ajax_request()) show_404(); //die('Not ajax request');
        $user = $this
            ->ion_auth
            ->user($id)->row();

        if (!$user) die('No user');

        $user->created_on = mysql_human($user->created_on);
        $user->last_login = timespan($user->last_login, time());
        $user->userfile = avatar($user->userfile);
        $user->country = $this
            ->user_model
            ->get_user_country($id)->name;
        $user->torrents = $this
            ->user_model
            ->count_user_torrents($id);
        $user->comments = $this
            ->user_model
            ->count_user_comments($id);

        $this->data['user'] = $user;
        $this->data['group'] = $this
            ->ion_auth
            ->get_users_groups($id)->row();

        //$this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/user', $this->data);
        view_helper('user', $this->data);
    }

    function profile()
    {

        $this
            ->breadcrumb
            ->append('Редактировать профиль');

        $this
            ->load
            ->library('form_validation');

        //redirect if not logged in
        if (!$this->data['logged_in'])
        {
            redirect('auth/login', 'refresh');
        }

        //logged in user's data
        $user = $this->data['curuser'];
        //if email is different than validate
        if ($this
            ->input
            ->post('email') != $user->email) $this
            ->form_validation
            ->set_rules('email', 'Email', 'required|valid_email|callback_email_check');

        //validate password if it was posted
        if ($this
            ->input
            ->post('password'))
        {
            $this
                ->form_validation
                ->set_rules('password', $this
                ->lang
                ->line('edit_user_validation_password_label') , 'required|min_length[' . $this
                ->config
                ->item('min_password_length', 'ion_auth') . ']|max_length[' . $this
                ->config
                ->item('max_password_length', 'ion_auth') . ']|matches[password_confirm]');
            $this
                ->form_validation
                ->set_rules('password_confirm', $this
                ->lang
                ->line('edit_user_validation_password_confirm_label') , 'required');
        }

        $this
            ->form_validation
            ->set_rules('country', 'Страна', 'is_natural_no_zero');

        ### working with userfile (avatar)
        $config['upload_path'] = $this
            ->config
            ->item('avatar_dir');
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = $this
            ->config
            ->item('avatar_size');
        $config['encrypt_name'] = true;
        $config['max_width'] = $this
            ->config
            ->item('avatar_resize_w');
        $config['max_height'] = $this
            ->config
            ->item('avatar_resize_h');
        $this
            ->load
            ->library('upload', $config);

        $image_errors = '';

        //if userfile not empty, do upload
        if (!empty($_FILES['userfile']['name']) && $this
            ->upload
            ->do_upload('userfile'))
        {

            $file = $this
                ->upload
                ->data(); //store file info
            

            /*
              //resize if image is larger than set in config
              if ($file['image_width'] > $this->config->item('avatar_resize_w') || $file['image_height'] > $this->config->item('avatar_resize_h')) {
            
              $config['source_image'] = $file['full_path'];
              $config['maintain_ratio'] = TRUE;
              $config['width'] = $this->config->item('avatar_resize_w');
              $config['height'] = $this->config->item('avatar_resize_h');
            
              $this->load->library('image_lib', $config);
            
              //if have errors with resizing store it
              if (!$this->image_lib->resize())
              $image_errors = $this->image_lib->display_errors();
              }
             * 
            */
        }
        else
        {
            //if have errors with upload store it
            $image_errors = $this
                ->upload
                ->display_errors();
        }

        ### here we display errors from file upload, resize or form validation
        if ($this
            ->form_validation
            ->run() == false || $image_errors != '')
        {

            ###generate country list
            $country = "<select name=\"country\" class=\"form-control\">\n";
            foreach ($this
                ->user_model
                ->list_all_countries() as $item)
            {
                $country .= "<option value=\"" . $item->id . "\"";
                if ($item->id == $user->country) $country .= " selected=\"selected\"";
                $country .= ">" . $item->name . "</option>\n";
            }
            $country .= "</select>\n";

            $this->data['country'] = $country;

            $this->data['avatar'] = avatar($user->userfile);

            $this->data['tabs'] = view_helper('user_tabs', '', true);

            $this->data['userfile'] = array(
                'name' => 'userfile',
                'type' => 'file'
            );

            $this->data['email'] = array(
                'name' => 'email',
                'id' => 'email',
                'type' => 'text',
                'class' => 'form-control',
                'value' => $this
                    ->form_validation
                    ->set_value('email', $user->email) ,
                'pattern' => '[^(\w)|(\@)|(\.)|(\-)]',
            );
            $this->data['password'] = array(
                'name' => 'password',
                'id' => 'password',
                'class' => 'form-control',
                'type' => 'password'
            );
            $this->data['password_confirm'] = array(
                'name' => 'password_confirm',
                'id' => 'password_confirm',
                'class' => 'form-control',
                'type' => 'password'
            );

            $this->data['message'] = (validation_errors() ? validation_errors() : ($this
                ->ion_auth
                ->errors() ? $this
                ->ion_auth
                ->errors() : $image_errors));
            $this->data['id'] = $user->id;
            $this
                ->template->title = 'Личный кабинет';
            $this
                ->template
                ->content
                ->view('profile', $this->data);
            $this
                ->template
                ->publish();
        }
        else
        {

            $data = array(
                'country' => $this
                    ->input
                    ->post('country') ,
                'email' => $this
                    ->input
                    ->post('email')
            );

            //update the password if it was posted
            if ($this
                ->input
                ->post('password')) $data['password'] = $this
                ->input
                ->post('password');

            //if file was uploaded than update db 'userfile'
            if (isset($file) && file_exists($this
                ->config
                ->item('avatar_dir') . $file['file_name']))
            {

                //if have current avatar for this user than delete
                if (file_exists($this
                    ->config
                    ->item('avatar_dir') . $user->userfile)) unlink($this
                    ->config
                    ->item('avatar_dir') . $user->userfile);

                $data['userfile'] = $file['file_name'];
            }

            $this
                ->ion_auth
                ->update($user->id, $data);

            $this
                ->session
                ->set_flashdata('info', "Профиль обновлён");

            redirect('user/profile', 'refresh');
        }
    }

    function torrents()
    {

        //redirect if not logged in
        if (!$this->data['logged_in'])
        {
            redirect('auth/login', 'refresh');
        }

        $this
            ->load
            ->library("pagination");

        if ($this
            ->input
            ->is_ajax_request())
        {

            $this
                ->datatables
                ->select('id, name, size, added, seeders, leechers, url, owner')
                ->edit_column('name', ($this->data['logged_in'] && $this->data['admin_mod'] ? anchor('torrent/edit/$2', "<span class='glyphicon glyphicon-pencil'></span>", array(
                'title' => 'Изминить',
                'class' => 'torrenttable_edit'
            )) : "") . anchor('torrent/$2-$3', '$1') , 'name, id, url, owner')
                ->from('torrents')
                ->where(array(
                'owner' => $this->data['curuser']
                    ->id
            ));

            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Мои торренты');
            $data = array(
                'torrenttable' => view_helper('ttable', '', true)
            );

            $this
                ->template->title = 'Мои торренты';
            $this
                ->template
                ->content
                ->view('browse', $data);
            $this
                ->template
                ->publish();
        }
    }

    function bookmarks()
    {

        //redirect if not logged in
        if (!$this->data['logged_in'])
        {
            redirect('auth/login', 'refresh');
        }

        $this
            ->load
            ->library("pagination");

        if ($this
            ->input
            ->is_ajax_request())
        {

            $this
                ->datatables
                ->select('b.t_id, t.id as id, t.name as name, t.size as size, t.added as added, t.seeders as seeders, t.leechers as leechers, t.url as url, t.owner as owner')
                ->edit_column('name', ($this->data['logged_in'] && $this->data['admin_mod'] ? anchor('torrent/edit/$2', "<span class='glyphicon glyphicon-pencil'></span>", array(
                'title' => 'Изминить',
                'class' => 'torrenttable_edit'
            )) : "") . anchor('torrent/$2-$3', '$1') , 'name, id, url, owner')
                ->from('bookmarks b')
                ->where(array(
                'b.user_id' => $this->data['curuser']
                    ->id
            ))
                ->join('torrents t', 't.id = b.t_id', 'left');
            //                    ->edit_column('name', ($this->data['logged_in'] && $this->data['admin_mod'] ? anchor('torrent/edit/$2', "<span class='glyphicon glyphicon-pencil'></span>", array('title' => 'Изминить', 'class' => 'torrenttable_edit')) : "") . anchor('torrent/$2-$3', '$1'), 'name, id, url, owner');
            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Мои закладки');
            $data = array(
                'torrenttable' => view_helper('ttable', '', true)
            );

            $this
                ->template->title = 'Мои закладки';
            $this
                ->template
                ->content
                ->view('bookmarks', $data);
            $this
                ->template
                ->publish();
        }
    }

    function comments()
    {

        $this
            ->breadcrumb
            ->append('Мои комментарии');

        //redirect if not logged in
        if (!$this->data['logged_in'])
        {
            redirect('auth/login', 'refresh');
        }
        $this
            ->load
            ->library('BBCodeParser');
        $this
            ->load
            ->helper('smiley');
        $this
            ->load
            ->library("pagination");

        $user = $this->data['curuser'];

        $limit = $this
            ->config
            ->item('comm_per_page');
        $page = ($this
            ->uri
            ->segment(4)) ? $this
            ->uri
            ->segment(4) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $comments = $this
            ->user_model
            ->comments($user->id, $limit, $offset);

        $config = array();
        $config['base_url'] = site_url("user/comments");
        $config['total_rows'] = $comments['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['prefix'] = '/page/';
        $config['suffix'] = '/#comm';
        $this
            ->pagination
            ->initialize($config);

        $data = array(
            'pagination' => $this
                ->pagination
                ->create_links() ,
            'comments' => $comments['rows']
        );

        $this
            ->template->title = 'Мои комментарии';
        $this
            ->template
            ->content
            ->view('user_comments', $data);
        $this
            ->template
            ->publish();
    }

    function email_check($str)
    {
        if ($this
            ->ion_auth
            ->email_check($str))
        {
            return false;
        }

        return true;
    }

    //log the user out
    function logout()
    {

        //log the user out
        $logout = $this
            ->ion_auth
            ->logout();

        //redirect them to the login page
        $this
            ->session
            ->set_flashdata('info', $this
            ->ion_auth
            ->messages());
        redirect('/', 'refresh');
    }

}

