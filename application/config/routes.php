<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

$route['default_controller'] = "browse/home";
//$route['404_override'] = 'browse/home';

$route['browse'] = "browse/index";
$route['announce'] = 'tracker/announce';
$route['scrape'] = 'tracker/scrape';

$route['auth'] = 'auth/index';
$route['rss']  = 'browse/rss';
$route['rss\.xml'] = 'browse/rss';
$route['sitemap\.xml'] = 'browse/sitemap';

$route['torrent/(:num)'] = "torrent/index/$1";
$route['torrent/(:num)-(:any)'] = "torrent/index/$1";
$route['user/(:num)'] = "user/index/$1";

$route['browse/search/(:any)/category/(:num)'] = "browse/search/$1/category/$2";
$route['browse/search/(:any)'] = "browse/search/$1";

$route['rss\.xml?id=(:num)'] = "browse/rss/$1";

$route['([a-z-]+)'] = "browse/category/$1";
$route['([a-z-]+)/page/(:num)'] = "browse/category/$1";

$route['rss/(:num)']  = "browse/rss/$1";

/* End of file routes.php */
/* Location: ./application/config/routes.php */
