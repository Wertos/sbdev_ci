<?php
class Pages extends MY_Controller
{

    function __construct()
    {

        parent::__construct();

    }

    public function rules()
    {
        $this
            ->breadcrumb
            ->append('Правила');
        $this
            ->template->title = 'Правила';
        $this
            ->template
            ->content
            ->view('rules');
        $this
            ->template
            ->publish();
    }

    public function secure()
    {
        $this
            ->breadcrumb
            ->append('Обход блокировок');
        $this
            ->template->title = 'Обход блокировок';
        $this
            ->template
            ->content
            ->view('secure');
        $this
            ->template
            ->publish();
    }

    public function what()
    {
        $this
            ->breadcrumb
            ->append('Как и зачем');
        $this
            ->template->title = 'Как и зачем';
        $this
            ->template
            ->content
            ->view('what');
        $this
            ->template
            ->publish();
    }

}

