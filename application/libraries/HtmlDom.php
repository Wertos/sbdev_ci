<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class HtmlDom
{
    public function __construct()
    {
        require_once APPPATH.'third_party/simpleHTMLdom/simple_html_dom.php';
    }
}