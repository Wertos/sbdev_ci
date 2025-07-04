<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Torrent extends MY_Controller {

    function __construct() {
        parent::__construct();

        $this->load->model('details_model');
        $this->load->helper('bbeditor');
    }

    public function index($id) {

        $id = (int) $id;

	$this->load->library('BBCodeParser');
        $this->load->helper('smiley');
        $details = $this->details_model->get_details($id);
	$bookmarks = $this->details_model->check_bookmarks($id, $this->data['curuser']->id);
        $this->details_model->update_view($id);
        
        if (!$details)
            show_404();

        $this->breadcrumb->append('Все раздачи', 'browse');
        $this->breadcrumb->append($details->catname, $details->caturl);
        $this->breadcrumb->append($details->name);

        $this->load->library("pagination");

        ###do bbcode for decr
		$p = array('~(?:<br[^>]*>\s*)+((?:</?b\b[^>]*>\s*)+)~i', '~(?:<br[^>]*>\s*)+~i'); 
		$z = array("\\1<br/>\n",  "<br/>\n");
		$details->descr = preg_replace($p, $z ,_bbcode($details->descr));
//		$details->descr = _bbcode($details->descr);
//		$details->descr = $details->descr;

        ###bytes to file size
        $details->size = byte_format($details->size);
        ###poster
        $details->poster = '<img src="' . $details->poster . '" alt="' . htmlspecialchars($details->name) . '" />';

        ###date convert
        $details->added = mysql_human($details->added, 'd/m/Y');

//        var_dump($this->data['curuser']);

        ###getting related by generated title
        $title = title_url(urldecode($details->name));
        $title = explode('-', $title);
		foreach($title as $index => $words){
			if (mb_strlen($words) < 4) {
    			unset($title[$index]); 
    		}
		}
        array_walk($title, 'text_add'); ///add + sign for each element for fulltext search
        $title = implode(' ', $title);
        ###end getting related by generated title
        #
        ###comments starts here
        $limit = $this->config->item('comm_per_page');
        $page = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;
        $offset = $page == 0 ? 0 : ($page - 1) * $limit;

        $comments = ($details->comments > 0 ? $this->details_model->get_comments($id, $limit, $offset) : array('rows' => '', 'num_rows' => ''));


        $config = array();
        $config['base_url'] = site_url("torrent/" . $id . "-" . $details->url);
        $config['total_rows'] = $comments['num_rows'];
        $config['per_page'] = $limit;
        $config['uri_segment'] = 4;
        $config['prefix'] = '/page/';
        $config['suffix'] = '/#comm';
        $this->pagination->initialize($config);

	$moderate = ($this->data['logged_in'] && $this->data['admin_mod']) ? TRUE : FALSE;
        ###comments end
        //var_dump($bookmarks);
        $data = array(
            'details' => $details,
            'comments' => $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/comments', array('comments' => $comments['rows']), TRUE),
            'pagination' => $this->pagination->create_links(),
            'commentform' => ($this->data['logged_in'] ? $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/commentform', array('id' => $details->id), true) : ''),
            'category' => anchor($details->caturl, $details->catname),
            'edit' => ($this->data['logged_in'] && ($this->data['curuser']->id == $details->owner || $moderate) ? anchor('torrent/edit/' . $details->id, "<span class='glyphicon glyphicon-pencil'></span>", array('title' => 'Изминить', 'style' => 'color:blue;')) : ""),
            'delete' => ($moderate ? anchor('torrent/delete/' . $details->id, "<span class='glyphicon glyphicon-trash'></span>", array('title' => 'Удалить')) : ""),
            'related' => $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/related', array('rows' => $this->details_model->get_related($title, $id, $details->category)), true),
            'book_class' => $bookmarks['class'],
            'book_title' => $bookmarks['title'],
			'moderate'   => $moderate
        );


        $this->template->title = $details->name.' | '.$details->size;
        $this->template->content->view('details', $data);
        $this->template->publish();
    }

    public function edit($id) {

        $id = (int) $id;

        if (!$this->data['logged_in'])
            show_404();

        $user = $this->data['curuser'];

        $details = $this->details_model->get_details($id);

        $owner = ($user->id == $details->owner ? TRUE : FALSE);

        $this->breadcrumb->append($details->name);

        if ($owner == FALSE && !$this->data['admin_mod'])
            show_404();

        if (!$details)
            show_404();


        $this->load->library('session');
		$this->load->helper('security');

        $this->load->helper(array('form'));
        $this->load->library('form_validation');



        $this->form_validation->set_rules('title', 'Название', 'trim|required|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('poster', 'Постер', 'trim|required|prep_url');
        $this->form_validation->set_rules('descr', 'Описание', 'trim|required|min_length[50]');
        $this->form_validation->set_rules('category', 'Категория', 'required|is_natural_no_zero');


        if ($this->form_validation->run() == FALSE) {

            ###build category list
            $cat = "<select name=\"category\" class=\"form-control\">\n";
            foreach ($this->data['categories'] as $category) {
                $cat .= "<option value=\"" . $category->id . "\"";
                if ($category->id == $details->category)
                    $cat .= " selected=\"selected\"";
                $cat .= ">" . $category->name . "</option>\n";
            }
            $cat .= "</select>\n";

            $data = array(
                'details' => $details,
                'cat' => $cat,
                'errors' => (validation_errors() ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : '')
            );

            ###generate input fields
            $data['name'] = array(
                'name' => 'title',
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->form_validation->set_value('title', $details->name),
            );
            $data['poster'] = array(
                'name' => 'poster',
                'class' => 'form-control',
                'type' => 'text',
                'value' => $this->form_validation->set_value('poster', $details->poster),
            );

            $data['can_comment'] = form_checkbox('can_comment', 'accept', ($details->can_comment === 'yes' ? TRUE : FALSE));

            $this->template->title = 'Редактировать';
            $this->template->content->view('edit', $data);
            $this->template->publish();
        } else {

            $data = array(
                'name' => $this->input->post('title', TRUE),
                'descr' => $this->input->post('descr', TRUE),
                'poster' => $this->input->post('poster', TRUE),
                'category' => $this->input->post('category', TRUE),
                'can_comment' => ($this->input->post('can_comment') ? 'yes' : 'no'),
          		'url' => title_url($this->input->post('title', TRUE))
			);


            $this->details_model->update_info($id, $data); ///inserting data

            $this->session->set_flashdata('info', 'Торрент был успешно отредактирован!');
            CACHE()->delete(md5('details_'.$id));

            redirect('torrent/' . $id, 'refresh'); ///redirect on success
        }
    }

    public function add() {

        if (!$this->data['logged_in']) {
            $this->session->set_flashdata('info', 'Доступ к данной странице только для зарегистрированных пользователей!');
            redirect('auth/login', 'refresh');
        }

        $this->breadcrumb->append('Добавить новый торрент');

        $this->load->library('session');
        $this->load->helper('form');
	$this->load->helper('security');
        $this->load->library('form_validation');
        $this->load->library('parsing');

        $user = $this->data['curuser'];
	$new_filename = (string) '';
        if ($user->can_upload == 'no')
            show_error('Вам запрещено добавлять торренты!');

        ### validate form values
        $this->form_validation->set_rules('title', 'Название', 'trim|required|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('poster', 'Постер', 'trim|required|prep_url|valid_img');
        $this->form_validation->set_rules('descr', 'Описание', 'trim|required|min_length[50]');
        $this->form_validation->set_rules('category', 'Категория', 'required|is_natural_no_zero');

        ###find out new torrent ID
        //$new_id = $this->details_model->new_torrent_id();

        ###file upload config
        $config['upload_path'] = $this->config->item('public_folder') . 'upload/torrents/';
        $config['allowed_types'] = 'torrent';
        $config['max_size'] = '1048';
        $config['file_name'] = $new_filename = md5($user->id . microtime() . rand());//$new_id . '.torrent';
        $this->load->library('upload', $config);
	$this->upload->initialize($config);
//	die(microtime());
        ###Here we work with uploaded torrent file
        print_r($config['file_name']);
	$torrent_errors = '';
        $torrent_file = $config['upload_path'] . $new_filename . '.torrent';

        if ($this->form_validation->run() && $this->upload->do_upload('file')) {

            $dict = bdecode(file_get_contents($torrent_file));

            if (!isset($dict)) {
                $torrent_errors .= "<p>Что за хрень ты загружаешь? Это не бинарно-кодированый файл!</p>";
            }
            //save file to server?
            $save = ($this->config->item('save_torrent') == FALSE ? 'no' : 'yes');
            $torrent = new Torrenting($torrent_file);
            $magnet = $torrent->magnet(); //generate magnet link
            $torrent->comment($this->config->item('site_name')); //set comment (sitename)
            $torrent->save($torrent_file);

            $info = $dict['info'];

            list($dname, $pieces) = array($info['name'], $info['pieces']);

            if (isset($info['length']))
                $totallen = $info['length'];

            if (strlen($pieces) % 20 != 0) {
                $torrent_errors .= "<p>nvalid pieces</p>";
            }

            $filelist = array();
            if (isset($totallen)) {
                $filelist[] = array($dname, $totallen);
                $type = "single";
            } else {
                $flist = $info['files'];
                if (!isset($flist)) {
                    $torrent_errors .= "<p>missing both length and files</p>";
                }
                if (!count($flist)) {
                    $torrent_errors .= "<p>no files</p>";
                }
                $totallen = 0;
                foreach ($flist as $fn) {
                    list($ll, $ff) = array($fn['length'], $fn['path']);
                    $totallen += $ll;
                    $ffa = array();
                    foreach ($ff as $ffe) {
                        $ffa[] = $ffe;
                    }
                    if (!count($ffa))
                        $torrent_errors .= "<p>filename error</p>";
                    $ffe = implode("/", $ffa);
                    $filelist[] = array($ffe, $ll);
                    if ($ffe == 'Thumbs.db') {
                        $torrent_errors .= "<p>В торрентах запрещено держать файлы Thumbs.db!</p>";
                    }
                }
                $type = "multi";
            }

            // double up on the becoding solves the occassional misgenerated infohash
            $dict = BDecode(BEncode($dict));

            $infohash = sha1(BEncode($dict['info']));


            ### check if we have this torrent in database by infohash
            $q = $this->db->select("id, name, url")
                    ->from("torrents")
                    ->where("info_hash", (function_exists('hex2bin') ? hex2bin($infohash) : pack('H*', $infohash)))
                    ->get()
                    ->row();

            if ($q)
                $torrent_errors .= "<p>Данний релиз уже есть на сайте: " . anchor('torrent/' . $q->id . '-' . $q->url, $q->name) . "</p>";
        } else {
            $torrent_errors = $this->upload->display_errors();
        }




        ### here we start processing the form
        if ($this->form_validation->run() == FALSE || $torrent_errors != '') {



            ###build category list
            $category = "<select name=\"category\" class=\"form-control\">\n";
            foreach ($this->data['categories'] as $cat) {
                $category .= "<option value=\"" . $cat->id . "\">" . $cat->name . "</option>\n";
            }
            $category .= "</select>\n";



            $data = array(
                'cat' => $category,
                'errors' => (validation_errors() ? '<div class="alert alert-danger">' . validation_errors() . '</div>' : ($torrent_errors ? '<div class="alert alert-danger">' . $torrent_errors . '</div>' : ''))
            );

            ###generate input fields
            $data['file'] = array(
                'name' => 'file',
                'type' => 'file',
				'accept' => 'application/x-bittorrent',
            );
            $data['name'] = array(
                'name' => 'title',
                'class' => 'form-control',
                'type' => 'text',
                'required' => 'required',
                'value' => $this->form_validation->set_value('title'),
            );
            $data['poster'] = array(
                'name' => 'poster',
                'class' => 'form-control',
                'type' => 'url',
                'required' => 'required',
                'value' => $this->form_validation->set_value('poster'),
            );

            $data['can_comment'] = form_checkbox('can_comment', 'accept', TRUE);



            if (file_exists($torrent_file))
                unlink($torrent_file);


            $this->template->title = 'Добавить торрент';
            $this->template->content->view('add', $data);
            $this->template->publish();
        } else { ///IF NO ERRORS THAN WORK WITH DB
            ### db get and insert torrent announce urls

            ### db insert torrent data array
            $array = array(
                'name' => $this->input->post('title', TRUE),
                'descr' => $this->input->post('descr', TRUE),
                'poster' => $this->input->post('poster', TRUE),
                'category' => (int) $this->input->post('category', TRUE),
                'owner' => $user->id,
                'file' => $save,
                'url' => title_url($this->input->post('title', TRUE)),
                'added' => time(),
                'info_hash' => (function_exists('hex2bin') ? hex2bin($infohash) : pack('H*', $infohash)),
                'size' => $totallen,
                'numfiles' => count($filelist),
                'type' => $type,
                'magnet' => $magnet,
                'can_comment' => ($this->input->post('can_comment') ? 'yes' : 'no'),
                'modded' => ($this->ion_auth->in_group(array('admin', 'moderator', 'uploader')) ? 'yes' : 'no'),
		'file_name' => $new_filename
            );

            //inserting data and getting insert_id()
            $new_id = $this->details_model->add_info($array);

            if (empty($dict['announce-list']) && !empty($dict['announce']))
			                $dict['announce-list'][] = array($dict['announce']);

            if (!empty($dict['announce-list'])) {
                $parsed_urls = array();
                foreach ($dict['announce-list'] as $al_url) {
                    $al_url[0] = trim($al_url[0]);
                    if ($al_url[0] == 'http://retracker.local/announce')
                        continue;
                    if (!preg_match('#^(udp|http)://#si', $al_url[0]))
                        continue;
                    if (in_array($al_url[0], $parsed_urls))
                        continue;
                    $url_array = parse_url($al_url[0]);
                    if (substr($url_array['host'], -6) == '.local')
                        continue;
                    $parsed_urls[] = $al_url[0];

                    $array = array(
                        'tid' => $new_id,
                        'info_hash' => $infohash,
                        'url' => $al_url[0]
                    );

                    $this->details_model->add_trackers($array);
                }
            }


            ### inserting files into db
            foreach ($filelist as $file) {
                $array = array(
                    'torrent' => $new_id,
                    'filename' => $file[0],
                    'size' => $file[1]
                );
                $this->details_model->add_files($array);
            }

            //delete torrent file if set in config
            if ($save == 'no' && file_exists($torrent_file))
                unlink($torrent_file);

            //redirect on success to update stats page
            redirect('torrent/update/' . $new_id);
        }
    }

    public function delete($id) {

        $id = (int) $id;
//	$filename = $this->details_model->get_filename($id);
        
	// can delet only if user in $groups
        if (!$this->data['admin_mod']) {
            redirect('/');
        }

        if (!$details = $this->details_model->get_details($id)) {
            show_404();
        }
        $this->load->library('session');

        $this->details_model->delete_torrent($id); ///inserting data

        if (file_exists($this->config->item('public_folder').'upload/torrents/' . $details->file_name . '.torrent'))
            unlink($this->config->item('public_folder').'upload/torrents/' . $details->file_name . '.torrent'); //deleting the file

        $this->session->set_flashdata('info', 'Торрент был успешно удалён!');

        redirect('browse', 'refresh'); ///redirect on success
    }

    public function files($id) {

        $id = (int) $id;

        if (!$this->input->is_ajax_request())
            die('Not ajax request');

        $files = $this->details_model->get_files($id);

        if (!$files)
            die("Нет такого торрента!");

        $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/filelist', array('files' => $files));
    }

    public function trackers($id) {

        $id = (int) $id;

        if (!$this->input->is_ajax_request())
            die('Not ajax request');

        $trackers = $this->details_model->get_trackers($id);


        if (!$trackers)
            die("Нет такого торрента!");

        $this->load->view('templates/' . $this->config->item('default_theme') . '/helpers/trackerlist', array('trackers' => $trackers));
    }

    public function update($tid, $del = 0) {
		$this->load->helper('update');
		updatestats($tid, $del);
    }

    public function download($id) {
        $this->load->library('parsing');
        $this->load->helper('download');

        $id = (int) $id;
        
        $details = $this->details_model->get_details($id);
	$torrent_file = 'public/upload/torrents/' . $details->file_name . '.torrent';
        if (file_exists($torrent_file) && $details) {
            $name = $details->url . '.torrent';
			$torrent = new Torrenting( $torrent_file );
			$torrent->announce(FALSE);

			if((int)$details->category === $this->config->item('adult_cat')) {
    			$torrent->announce($this->config->item('announce_urls')['adult']);
				$torrent->comment(base_url().'torrent/'.$id);
				$torrent->save($this->config->item('public_folder').'/temp/'.$name);
			} else {
				$torrent->announce($this->config->item('announce_urls')['open']);
				$torrent->comment(base_url().'torrent/'.$id);
				$torrent->save($this->config->item('public_folder').'/temp/'.$name);
			}
            $data = file_get_contents($this->config->item('public_folder').'/temp/'.$name); // Read the file's contents
            unlink($this->config->item('public_folder').'/temp/'.$name);
			$this->db->set('downloaded', 'downloaded + 1', FALSE)->where('id', $id)->update('torrents');
			force_download($name, $data);
			return;
#            $data = file_get_contents($torrent_file); // Read the file's contents
#            $name = $details->url . '.torrent';
#            force_download($name, $data);
        } else {
            show_404();
        }
    }

    public function bookmarks($id = NULL) {
      if($this->data['curuser'] !== FALSE) {
      if($this->input->is_ajax_request()) {
        	
        	$id = (int) $this->input->post('id', TRUE);
	  	$data = $this->details_model->bookmarks($id, $this->data['curuser']->id);
	  	echo json_encode($data);
	  	exit();
	} else if ($id !== NULL) {

        	$data = $this->details_model->bookmarks($id, $this->data['curuser']->id);
        	($data['class'] == 'book') ? $this->session->set_flashdata('info', 'Закладка на раздачу успешно добавлена') : $this->session->set_flashdata('info', 'Закладка на раздачу успешно удалена');
        	redirect('torrent/' . $id, 'refresh');
      } else if($id === NULL AND !$this->input->is_ajax_request()) {
		redirect("user/bookmarks");
      }
     } else {
       redirect('auth/login');
     }
    }

}