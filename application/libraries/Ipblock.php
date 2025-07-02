<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ipblock
{
    public function __construct()
    {
        require_once APPPATH.'third_party/IpBlock/roscomsos.php';
    }
}