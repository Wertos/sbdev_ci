<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Ajax extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        if ($this
            ->input
            ->is_ajax_request() === false)
        {
            show_404('', false);
        }
        $this
            ->output
            ->enable_profiler(false);
        $ajax = new stdClass();
        $ajax->action = $this
            ->uri
            ->segment(2);

        switch ($ajax->action)
        {
            case 'ajaxpag':
                $this
                    ->load
                    ->helper('text');
                $this
                    ->load
                    ->model('browse_model', 'browse');
            break;
            case 'delann':
                if (!$this
                    ->ion_auth
                    ->is_admin()) die();
                break;
            default:
                die();
            }
    }

    private function _send($data, $content_type = 'application/json', $charset = 'UTF-8')
    {

        $this
            ->output
            ->set_content_type($content_type, $charset)->set_output(json_encode($data));

    }

    public function delann()
    {
        $tid = (int)$this
            ->input
            ->post('tid') ? ? 0;
        $id = (int)$this
            ->input
            ->post('id') ? ? 0;
        $url = $this
            ->input
            ->post('url') ? ? '';
        $response = [];
        $this
            ->db
            ->delete('torrents_scrape', ['id' => $id]);

        $response['id'] = $id;
        $this->_send($response);
    }

    public function ajaxpag()
    {
        $cat_id = (int)$this
            ->input
            ->post('catid') ? ? 0;
        $start = (int)$this
            ->input
            ->post('start') ? ? 0;
        $response = [];
        $html = '';
        if ($start < 0) $start = 0;
        $data = $this
            ->browse
            ->get_all_home($cat_id, $start);

        foreach ($data as $row)
        {
            $metadata = '
                <ul class="list-inline home_metadata">
                                <li title="Размещён"><span class="glyphicon glyphicon-calendar"></span> ' . mysql_human($row->added, 'd/m/Y') . '</li>
                                <li title="Размер"><span class="glyphicon glyphicon-hdd"></span> ' . byte_format($row->size) . '</li>
                                <li title="Сидов" style="color: green;"><span class="glyphicon glyphicon-arrow-up"></span> ' . number_format($row->seeders) . '</li>
                                <li title="Личеров" style="color: red;"><span class="glyphicon glyphicon-arrow-down"></span> ' . number_format($row->leechers) . '</li>
                                <li title="Скачан" style="color: #255a6c;"><span class="glyphicon glyphicon-glyphicon glyphicon-saved"></span> ' . number_format($row->completed) . '</li>
                                <li title="' . number_format($row->downloaded) . '"><span class="glyphicon glyphicon-eye-open"></span> ' . number_format($row->views) . '</li>
                    <li class="navbar-right">
                        <ul class="list-inline">
                            <li>' . ($row->comments > 0 ? number_format($row->comments) . ' <span class="glyphicon glyphicon-comment"></span>' : '') . '</li>
                        </ul>
                    </li>
                </ul>';
            $html .= anchor('torrent/' . $row->id . ($row->url ? '-' . $row->url : '') , '<div class="poster-home" style="float:left;margin-right:3px;"><img data-html="true" title="<img width=\'100\' src=\'' . $row->poster . '\' />" src="' . $row->poster . '" width="30" height="40" alt="" /></div><h5 class="list-group-item-heading" title="' . $row->name . '">' . character_limiter($row->name, 500) . '</h5>' . $metadata, array(
                'class' => 'list-group-item',
                'id' => 'torrent-' . $row->id
            ));
        }
        $response['html'] = $html;
        $response['stop'] = 'false';
        $response['start'] = $start;
        $this->_send($response);
    }
}

