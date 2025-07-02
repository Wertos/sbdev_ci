<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parsing {

    public function __construct() {
        require_once APPPATH . 'third_party/scraper/torrent.php';
        require_once APPPATH . 'third_party/scraper/bdecode.php';
        require_once APPPATH . 'third_party/scraper/bencode.php';
        require_once APPPATH . 'third_party/scraper/httptscraper.php';
        require_once APPPATH . 'third_party/scraper/udptscraper.php';
#        require_once APPPATH . 'third_party/scraper/scraper.php';
#        require_once APPPATH . 'third_party/scraper/scraper1.php';

    }

}