<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Comments extends MY_Controller
{

    function __construct()
    {
        parent::__construct();

        $this
            ->load
            ->model('comments_model');
        $this
            ->load
            ->helper(array(
            'smiley',
            'security'
        ));
        $this
            ->load
            ->library(array(
            'BBCodeParser', /*'jbbcode',*/
            'form_validation'
        ));

        if (!$this
            ->input
            ->is_ajax_request()) die('Ajax only!');

        if (!$this->data['logged_in']) die('Logged in only!');
    }

    public function add()
    {

        $errors = array();

        ### validate form values
        $this
            ->form_validation
            ->set_error_delimiters('', '');
        $this
            ->form_validation
            ->set_rules('id', 'ID', 'required|is_natural_no_zero');
        $this
            ->form_validation
            ->set_rules('text', 'Комментарии', 'trim|required|min_length[' . $this
            ->config
            ->item('comm_min_lengh') . ']|max_length[' . $this
            ->config
            ->item('comm_max_lengh') . ']');

        if ($this
            ->form_validation
            ->run() == false) $errors[] = validation_errors();

        $tid = $this
            ->input
            ->post('id');
        $text = $this
            ->input
            ->post('text', true);
        $user = $this->data['curuser'];
        $userid = $user->id;

        $res = $this
            ->db
            ->get_where('torrents', array(
            'id' => $tid
        ))->row();

        if (!$res) $errors[] = 'Нет такого торрента';

        if ($res->can_comment == 'no' || $user->can_comment == 'no') $errors[] = 'Комментарии отключены!';

        $minutes = $this
            ->config
            ->item('comm_flood_minutes');
        $limit = $this
            ->config
            ->item('comm_flood_limit');

        ###flood protection
        $groups = array(
            'admin',
            'moderator'
        );
        if (!$this
            ->ion_auth
            ->in_group($groups))
        {
            // count query
            $this
                ->db
                ->where("user = " . $userid . " AND location = 'torrents' AND added > UNIX_TIMESTAMP() - " . $minutes);
            $this
                ->db
                ->from('comments');

            $count = $this
                ->db
                ->count_all_results();

            if ($count >= $limit) $errors[] = 'Флуууд?! Максимум ' . $this
                ->config
                ->item('comm_flood_limit') . ' комментарий в периоде ' . $this
                ->config
                ->item('comm_flood_minutes') . ' минут';
        }

        if (empty($errors))
        { //if no errors than proceed
            $data = array(
                'user' => $userid,
                'fid' => $tid,
                'added' => time() ,
                'text' => $text,
                'location' => 'torrents'
            );

            $new_id = $this
                ->comments_model
                ->new_comment_id();
            CACHE()
                ->delete(md5('details_' . $tid));
            $this
                ->comments_model
                ->add($data, $tid);
            view_helper('comments', array(
                'comments' => $this
                    ->comments_model
                    ->get_last($new_id)
            ));
        }
        else
        {

            //    echo "<div class='alert alert-danger comment_errors'><ul class=\"list-unstyled list_errors\">";
            foreach ($errors as $error) echo "<li class='alert alert-danger comment_errors'>" . $error . "</li>";
            //    echo "</ul></div>";
            
        }
    }

    public function delete($commentid)
    {

        if (!$this->data['admin_mod']) die;

        $commentid = (int)$commentid;

        $res = $this
            ->comments_model
            ->get_by_id($commentid);

        if (!$res) die('Нет такого комментария');

        $torrentid = $res->fid;
        CACHE()
            ->delete(md5('details_' . $torrentid));
        $this
            ->comments_model
            ->delete($commentid, $torrentid);
    }

    function quote($cid)
    {

        $cid = intval($cid);

        $res = $this
            ->comments_model
            ->get_by_id($cid);

        if (!$res) die('Нет такого комментария');

        echo $res->text;
    }

    function edit($commentid)
    {

        $commentid = (int)$commentid;

        $res = $this
            ->comments_model
            ->get_by_id($commentid);

        if (!$res) die('Нет такого комментария');

        $user = $this->data['curuser'];

        $owner = ($user->id == $res->user ? true : false);

        if ($owner == false && !$this->data['admin_mod']) die('No permission');

        $this
            ->load
            ->helper('bbeditor');

        $errors = array();

        if (isSet($_POST['textedit']))
        {
            $this
                ->form_validation
                ->set_error_delimiters('', '');
            $this
                ->form_validation
                ->set_rules('id', 'ID', 'required|is_natural_no_zero');
            $this
                ->form_validation
                ->set_rules('textedit', 'Комментарии', 'trim|required|min_length[' . $this
                ->config
                ->item('comm_min_lengh') . ']|max_length[' . $this
                ->config
                ->item('comm_max_lengh') . ']');
            if ($this
                ->form_validation
                ->run() == false) $errors[] = validation_errors();
        }

        if ($this
            ->input
            ->post('textedit') && empty($errors))
        {

            $data = array(
                'text' => $this
                    ->input
                    ->post('textedit', true) ,
                'editedby' => $user->id
            );

            $this
                ->comments_model
                ->edit($data, $commentid);

            die;
        }
        elseif ($errors)
        {

            foreach ($errors as $error) echo "<li class='alert alert-danger comment_errors'>" . $error . "</li>";

            die;
        }

        echo form_open('', array(
            'id' => 'edit_comment_form'
        ) , array(
            'id' => $commentid
        ));

        bbeditor('textedit', $res->text, '', 5);

        echo '<button type="submit" style="margin-top:10px;" class="btn btn-success btn-xs">Редактировать</button>';
        echo " <button data-dismiss=\"modal\" style=\"margin-top:10px;\" class=\"btn btn-danger btn-xs\">Отменить</button>";

        echo form_close();
    }

}

