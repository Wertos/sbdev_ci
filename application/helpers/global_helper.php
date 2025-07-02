<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_date_time($timestamp = 0) {
    if ($timestamp)
        return date("Y-m-d H:i:s", $timestamp);
    else
        return date("Y-m-d H:i:s");
}

function title_url($str, $separator = '-', $lowercase = FALSE) {
    if ($separator == 'dash') {
        $separator = '-';
    } else if ($separator == 'underscore') {
        $separator = '_';
    }

    $q_separator = preg_quote($separator);

    $trans = array(
        '&.+?;' => '',
        '(*UTF8)[^a-zа-яё0-9 _-]' => '',
        '\s+' => $separator,
        '(' . $q_separator . ')+' => $separator
    );

    $str = strip_tags($str);

    foreach ($trans as $key => $val) {
        $str = preg_replace("#" . $key . "#i", $val, $str);
    }

    if ($lowercase === TRUE) {
        $str = strtolower($str);
    }

    return trim($str, $separator);
}

function text_add(&$item, $key) {
    $item = '+' . $item;
}

function avatar($user, $size = 100) {

    $avatarpath = 'public/upload/avatars/' . $user;

    $image_properties = array(
        'src' => ($user != '' ? $avatarpath : 'public/assets/pic/default_avatar.jpg'),
        'class' => 'img-rounded img-responsive avatar',
        'width' => $size,
        'height' => $size
    );

    return img($image_properties);
}

function link_user($userid, $username, $title = '') {
    if ($username) {
        $title = ($title !== '') ? 'title="'.$title.'"' : '';
		return '<span ' . $title . ' class="clickable" onclick="userinfo(' . $userid . ')">' . $username . '</span>';
    } else
        return '<i>удалён</i>';
}

function mysql_human($timestamp = "", $format = "d/m/Y H:i:s") {
    if (empty($timestamp) || !is_numeric($timestamp))
        $timestamp = time();
    return date($format, $timestamp);
}

function _bbcode($str) {

    $str = trim(nl2br($str)); //new line break

    $str = parse_smileys($str, site_url('public/assets/pic/smilies/')); //add smileys support
    
//    $parser = new JBBCode\Parser();
//    $parser->addCodeDefinitionSet(new JBBCode\DefaultCodeDefinitionSet());
//    $parser->parse($str);
//    return $parser->getAsHtml();
	$bbcode = new BBCodeparser;
	return $bbcode->parse($str);
}

function view_helper($view, $vars = array(), $output = false) {
    $CI = &get_instance();
    return $CI->load->view('templates/' . $CI->config->item('default_theme') . '/helpers/' . $view, $vars, $output);
}

function poster($img = '', $prefix = 'micro_', $w = 30, $h = 40) {
	    $CI =& get_instance();
		$CI->load->library(array('image_lib'));

		if($img === '') return NULL;
				
		$orig_image = $CI->config->item('public_folder').'upload/images/orig_'.basename($img);
		
		if(!file_exists($orig_image)) {
			$options = array(
  				'http'=>array(
					'method'=>"GET",
					'header'=> array("Accept-language: ru",
          	    		"Cookie: 3iRzgF0BNefe1Q2AAw0000cy",
        	      		"User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.73 Safari/537.36 OPR/34.0.2036.42",
  			        	"Accept: text/html,application/xhtml+xml,application/xml;q=0.9,*\/*;q=0.8",
  			        ),
  				)
			);
			$context = stream_context_create($options);
			file_put_contents($orig_image, file_get_contents($img));
		}
		
		if(filesize($orig_image) == 0) return './public/assets/pic/'.$prefix.'noposter.png';

		if($prefix === 'orig_') return './public/upload/images/'.$prefix.basename($img);

		if(!file_exists($CI->config->item('public_folder').'upload/images/'.$prefix.basename($img))) {
			$config = [
							'image_library'  => 'gd2',
							'source_image'   => $orig_image,
							'create_thumb'   => TRUE,
							'maintain_ratio' => TRUE,
							'width'          => $w,
							'height'         => $h,		  	
							'thumb_marker'   => '',
							'master_dim'     => 'width',
							'new_image'      => $CI->config->item('public_folder').'upload/images/'.$prefix.basename($img)
			];
			$CI->image_lib->initialize($config);
			$CI->image_lib->resize();
			$CI->image_lib->clear();
		}
	  return './public/upload/images/'.$prefix.basename($img);
}

function CACHE() {
    $CI = &get_instance();
    $cache_type = $CI->config->item('cache_type');
    $CI->load->driver('cache');
    return $CI->cache->{$cache_type};
}

function CACHE_LIFE_TIME() {
    $CI = &get_instance();
    $cache_life_time = (int) $CI->config->item('cache_life_time');
    return ($cache_life_time === 0) ? 0 : intval($cache_life_time + rand(30, 90));
}

function proxycheck() {
    $proxy_headers = array(
        'HTTP_VIA',
        'HTTP_X_FORWARDED_FOR',
        'HTTP_FORWARDED_FOR',
        'HTTP_X_FORWARDED',
        'HTTP_FORWARDED',
        'HTTP_CLIENT_IP',
        'HTTP_FORWARDED_FOR_IP',
        'VIA',
        'X_FORWARDED_FOR',
        'FORWARDED_FOR',
        'X_FORWARDED',
        'FORWARDED',
        'CLIENT_IP',
        'FORWARDED_FOR_IP',
        'HTTP_PROXY_CONNECTION'
    );
    foreach($proxy_headers as $x){
        if (isset($_SERVER[$x])) {
           return 1;
		}
		return 0;
    }
}
