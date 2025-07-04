<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Browse extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this
            ->load
            ->model('browse_model', 'browse');
        $this
            ->load
            ->library('Datatables');
        $this
            ->load
            ->helper('torrenttable');

    }

    public function home()
    {
        $this
            ->breadcrumb
            ->append($this
            ->config
            ->item('site_name') . ' - ' . $this
            ->config
            ->item('site_descr'));
        $this
            ->load
            ->helper('text');
        $this
            ->template
            ->content
            ->view('home');
        $this
            ->template
            ->publish();
    }

    public function index()
    {

        if ($this
            ->input
            ->is_ajax_request())
        {
            $this
                ->datatables
                ->select('id, name, size, added, seeders, leechers, url, owner, poster')
                ->edit_column('name', ($this->data['logged_in'] && $this->data['admin_mod'] ? anchor('torrent/edit/$2', "<span class='glyphicon glyphicon-pencil'></span>", array(
                'title' => 'Изминить',
                'class' => 'torrenttable_edit'
            )) : "") . anchor('torrent/$2-$3', '$1') , 'name, id, url, owner')
                ->from('torrents');
            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Все раздачи');
            $data['torrenttable'] = view_helper('ttable', '', true);
            $this
                ->template->title = 'Все раздачи';
            $this
                ->template
                ->content
                ->view('browse', $data);
            $this
                ->template
                ->publish();
        }
    }

    function search($str = '', $cat_id = 0)
    {

        $url_add = (int)$this
            ->input
            ->post('cat-id') > 0 ? '/category/' . $this
            ->input
            ->post('cat-id') : '';

        if ($this
            ->input
            ->post('q')) redirect('browse/search/' . title_url($this
            ->input
            ->post('q')) . $url_add);

        if ($str == '') redirect('browse');

        $sql_and = '';

        $cat_id = (int)$this
            ->uri
            ->uri_to_assoc(2) ['category'];

        if ($cat_id > 0)
        {
            $sql_add = 'AND category = ' . $cat_id;
        }

        $str = explode('-', urldecode($str));
        array_walk($str, 'text_add'); ///add + sign for each element for fulltext search
        $title = explode('-', $title);
        foreach ($str as $index => $words)
        {
            if (mb_strlen($words) < 4)
            {
                unset($str[$index]);
            }
        }

        $str = implode(' ', $str);
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
                ->where('modded = "yes" ' . $sql_add . ' AND MATCH (name) AGAINST ("' . $str . '" IN BOOLEAN MODE)', NULL, false);
            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Все раздачи', 'browse');
            $this
                ->breadcrumb
                ->append('Результат поиска');
            $data['torrenttable'] = view_helper('ttable', '', true);
            $this
                ->template->title = 'Результат поиска';
            $this
                ->template
                ->content
                ->view('browse', $data);
            $this
                ->template
                ->publish();
        }
    }

    public function category($url)
    {
        $q = $this
            ->db
            ->select('id, name')
            ->from('categories')
            ->where("url", $url);

        $cid = $q->get()
            ->row();

        //if no category with this URL
        if (!$cid)
        {
            show_404();
        }

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
                'category' => $cid->id,
                'modded' => 'yes'
            ));
            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Все раздачи', 'browse');
            $this
                ->breadcrumb
                ->append($cid->name);
            $data['torrenttable'] = view_helper('ttable', '', true);
            $this
                ->template->title = $cid->name;
            $this
                ->template
                ->content
                ->view('browse', $data);
            $this
                ->template
                ->publish();
        }
    }

    public function user($id)
    {

        $id = (int)$id;

        $q = $this
            ->db
            ->select('id, username')
            ->from('users')
            ->where("id", $id)->get()
            ->row();

        //if no user with this id
        if (!$q) show_404();

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
                'owner' => $id,
                'modded' => 'yes'
            ));
            echo $this
                ->datatables
                ->generate();
        }
        else
        {
            $this
                ->breadcrumb
                ->append('Все раздачи', 'browse');
            $this
                ->breadcrumb
                ->append($q->username);
            $data['torrenttable'] = view_helper('ttable', '', true);
            $this
                ->template->title = $q->username;
            $this
                ->template
                ->content
                ->view('browse', $data);
            $this
                ->template
                ->publish();
        }
    }

    public function sitemap()
    {
        $data = $this
            ->browse
            ->sitemap(); //select urls from DB to Array
        header("Content-Type: text/xml;charset=iso-8859-1");
        view_helper('sitemap', array(
            'data' => $data
        ));
    }

    public function rss($id = NULL)
    {
        if (!$logged_in)
        {
            $this
                ->session
                ->set_flashdata('info', 'Только для зарегистрированных !!');
            redirect('/', 'refresh');
        }
        $data = array(
            'feeds' => $this
                ->browse
                ->feeds_info($id)
        );
        header("Content-Type: application/rss+xml;charset=utf-8");
        $this
            ->load
            ->view('templates/' . $this
            ->config
            ->item('default_theme') . '/rss_view', $data);
    }

}

