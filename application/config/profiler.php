<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| Profiler Sections
| -------------------------------------------------------------------------
| This file lets you determine whether or not various sections of Profiler
| data are displayed when the Profiler is enabled.
| Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/profiling.html
|
*/

//$config['config']          = FALSE;
//$config['queries']         = FALSE;
$config = [
	'benchmarks'          => FALSE,
	'config'              => FALSE,
	'controller_info'     => FALSE,
	'get'                 => FALSE,
	'http_headers'        => FALSE,
	'memory_usage'        => FALSE,
	'post'                => FALSE,
	'queries'             => TRUE,
	'uri_string'          => FALSE,
	'session_data'        => FALSE,
	'query_toggle_count'  => FALSE,
];

/* End of file profiler.php */
/* Location: ./application/config/profiler.php */