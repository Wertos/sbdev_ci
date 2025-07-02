<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

function get_pag ($cat_id) {
	$CI =& get_instance();
	$name  = $CI->security->get_csrf_token_name();
	$value = $CI->input->cookie($CI->config->item("csrf_cookie_name"));

	$pag_html = '
    <div class="btn-group btn-group-xs">
      <button disabled id="pag_prev_'.$cat_id.'" class="bold btn btn-default" onclick="pager('.$cat_id.', $(\'#catid_\'+'.$cat_id.').data(\'start\'),\''.$name.'\',\''.$value.'\', \'back\');">&laquo;</button>
      <button id="pag_next_'.$cat_id.'" class="bold btn btn-default" onclick="pager('.$cat_id.', $(\'#catid_\'+'.$cat_id.').data(\'start\'),\''.$name.'\',\''.$value.'\', \'next\');">&raquo;</button>
    </div>';
	return $pag_html;
}