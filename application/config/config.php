<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

### Custom config starts here
if (PHP_SAPI === 'cli') {
		$config['base_url'] = "http://localhos/";
		$_SERVER['HTTP_HOST'] = 'localhost';
		$_SERVER['SERVER_PORT'] = 80;
} else {
		$config['base_url'] = (string) "http".((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") ? "s" : "")."://".$_SERVER['HTTP_HOST'].str_replace(basename($_SERVER['SCRIPT_NAME']),"",$_SERVER['SCRIPT_NAME']);
}

$config['site_name'] = 'MySite';
$config['site_descr'] = 'Каталог магнет ссылок'; // site description
$config['site_keywords'] = 'site,key,words';
$config['site_favicon'] = 'favicon.ico';

$config['admin_email'] = 'admin@localhost';

// path to public folder
$config['public_folder'] = FCPATH.'public/'; // /var/www.../

$config['default_theme'] = 'default';
$config['cssjsver'] = (ENVIRONMENT == 'development') ? '?ver='.rand() : '';

$config['save_torrent'] = TRUE; // save torrent file to server or use only generated magnet links (for new torrents)
$config['update_torrent_time'] = 5; // how often user can update torrent stats in minutes
$config['browse_per_page'] = 50; // torrents per page (all, search, category)
$config['db_select_str'] = 'id, name, added, size, seeders, leechers, completed, url, comments, owner, poster, views, downloaded'; //SELECT from torrents for torrenttable function (DO NOT CHANGE!!!)
$config['related_nr'] = 10; // how many related torrents to show


$config['home_per_cat'] = 5; // how many torrents per category on home page

$config['avatar_size'] = 300; // max size tu upload in KB
$config['avatar_resize_w'] = 150; // resize avatar in width px
$config['avatar_resize_h'] = 150; // resize avatar in height px
$config['avatar_dir'] = $config['public_folder'] . 'avatars/'; //Avatar dir

$config['comm_per_page'] = 10; // comments per page
$config['comm_min_lengh'] = 5; // min charecters for comment
$config['comm_max_lengh'] = 200; // max cherecter for comment
$config['comm_flood_limit'] = 5; // flood! max comment
$config['comm_flood_minutes'] = 5; // flood! time period in minutes for comments

$config['cache_type'] = 'dummy'; //apc, apcu, memcached, wincache, file, dummy, redis
$config['cache_life_time'] = 180; // in sec.
$config['adult_cat'] = 17;

$config['social']['vkontakte'] = [
				'enable' => FALSE,
				'access_token' => ""
  ];
$config['social']['facebook'] = [
				'enable' => FALSE,
				'access_token' => ""
  ];

$config['announce_urls']['open'] = [
				'udp://torr.ws:2710/announce',
				'http://torr.ws:2710/announce',
				'http://87.248.186.252:8080/announce',
				'udp://[2001:67c:28f8:92::1111:1]:2710',
				'udp://tracker.leechers-paradise.org:6969/announce',
				'udp://46.148.18.250:2710',
				'udp://public.popcorn-tracker.org:6969/announce',
				'http://tracker.torrentyorg.pl/announce',
				'udp://182.176.139.129:6969/announce',
				'udp://tracker.tiny-vps.com:6969/announce',
				'udp://ipv6.leechers-paradise.org:6969',
				'udp://tracker.opentrackr.org:1337/announce',
				'udp://bt.megapeer.org:6969',
				'udp://zephir.monocul.us:6969',
				'udp://tracker.vanitycore.co:6969',
	];
$config['announce_urls']['adult'] = [
				'udp://torr.ws:2710/announce',
				'http://torr.ws:2710/announce',
				'udp://9.rarbg.to:2710/announce',
				'udp://9.rarbg.me:2710/announce',
				'udp://tracker.leechers-paradise.org:6969/announce',
				'udp://ipv6.leechers-paradise.org:6969',
				'udp://tracker.pornoshara.tv:2711/announce',
	];


$config['admin']['icons'] = 'asterisk,plus,euro,minus,cloud,envelope,pencil,glass,music,search,heart,star,star-empty,user,film,th-large,th,th-list,ok,remove,zoom-in,zoom-out,off,signal,cog,trash,home,file,time,road,download-alt,download,upload,inbox,play-circle,repeat,refresh,list-alt,lock,flag,headphones,volume-off,volume-down,volume-up,qrcode,barcode,tag,tags,book,bookmark,print,camera,font,bold,italic,text-height,text-width,align-left,align-center,align-right,align-justify,list,indent-left,indent-right,facetime-video,picture,map-marker,adjust,tint,edit,share,check,move,step-backward,fast-backward,backward,play,pause,stop,forward,fast-forward,step-forward,eject,chevron-left,chevron-right,plus-sign,minus-sign,remove-sign,ok-sign,question-sign,info-sign,screenshot,remove-circle,ok-circle,ban-circle,arrow-left,arrow-right,arrow-up,arrow-down,share-alt,resize-full,resize-small,exclamation-sign,gift,leaf,fire,eye-open,eye-close,warning-sign,plane,calendar,random,comment,magnet,chevron-up,chevron-down,retweet,shopping-cart,folder-close,folder-open,resize-vertical,resize-horizontal,hdd,bullhorn,bell,certificate,thumbs-up,thumbs-down,hand-right,hand-left,hand-up,hand-down,circle-arrow-right,circle-arrow-left,circle-arrow-up,circle-arrow-down,globe,wrench,tasks,filter,briefcase,fullscreen,dashboard,paperclip,heart-empty,link,phone,pushpin,usd,gdp,sort,sort-by-alphabet,sort-by-alphabet-alt,sort-by-order,sort-by-order-alt,sort-by-attributes,unchecked,expand,collapse-down,collapse-up,log-in,flash,log-out,new-window,record,save,open,saved,import,export,send,floppy-disk,floppy-saved,floppy-removed,floppy-save,floppy-open,credit-card,transfer,cutlery,header,compressed,earphone,phone-alt,tower,stats,sd-video,hd-video,subtitles,sound-stereo,sound-dolby,sound-5-1,sound-6-1,sound-7-1,copyright-mark,registration-mark,cloud-download,cloud-upload,tree-conifer,tree-deciduous';

### Custom config ends here


/*
  |--------------------------------------------------------------------------
  | Index File
  |--------------------------------------------------------------------------
  |
  | Typically this will be your index.php file, unless you've renamed it to
  | something else. If you are using mod_rewrite to remove the page set this
  | variable so that it is blank.
  |
 */
$config['index_page'] = '';

/*
  |--------------------------------------------------------------------------
  | URI PROTOCOL
  |--------------------------------------------------------------------------
  |
  | This item determines which server global should be used to retrieve the
  | URI string.  The default setting of 'AUTO' works for most servers.
  | If your links do not seem to work, try one of the other delicious flavors:
  |
  | 'AUTO'			Default - auto detects
  | 'PATH_INFO'		Uses the PATH_INFO
  | 'QUERY_STRING'	Uses the QUERY_STRING
  | 'REQUEST_URI'		Uses the REQUEST_URI
  | 'ORIG_PATH_INFO'	Uses the ORIG_PATH_INFO
  |
 */
$config['uri_protocol'] = 'REQUEST_URI';

/*
  |--------------------------------------------------------------------------
  | URL suffix
  |--------------------------------------------------------------------------
  |
  | This option allows you to add a suffix to all URLs generated by CodeIgniter.
  | For more information please see the user guide:
  |
  | http://codeigniter.com/user_guide/general/urls.html
 */

$config['url_suffix'] = '';

/*
  |--------------------------------------------------------------------------
  | Default Language
  |--------------------------------------------------------------------------
  |
  | This determines which set of language files should be used. Make sure
  | there is an available translation if you intend to use something other
  | than english.
  |
 */
$config['language'] = 'russian';

/*
  |--------------------------------------------------------------------------
  | Default Character Set
  |--------------------------------------------------------------------------
  |
  | This determines which character set is used by default in various methods
  | that require a character set to be provided.
  |
 */
$config['charset'] = 'UTF-8';

/*
  |--------------------------------------------------------------------------
  | Enable/Disable System Hooks
  |--------------------------------------------------------------------------
  |
  | If you would like to use the 'hooks' feature you must enable it by
  | setting this variable to TRUE (boolean).  See the user guide for details.
  |
 */
$config['enable_hooks'] = FALSE;


/*
  |--------------------------------------------------------------------------
  | Class Extension Prefix
  |--------------------------------------------------------------------------
  |
  | This item allows you to set the filename/classname prefix when extending
  | native libraries.  For more information please see the user guide:
  |
  | http://codeigniter.com/user_guide/general/core_classes.html
  | http://codeigniter.com/user_guide/general/creating_libraries.html
  |
 */
$config['subclass_prefix'] = 'MY_';


/*
  |--------------------------------------------------------------------------
  | Allowed URL Characters
  |--------------------------------------------------------------------------
  |
  | This lets you specify with a regular expression which characters are permitted
  | within your URLs.  When someone tries to submit a URL with disallowed
  | characters they will get a warning message.
  |
  | As a security measure you are STRONGLY encouraged to restrict URLs to
  | as few characters as possible.  By default only these are allowed: a-z 0-9~%.:_-
  |
  | Leave blank to allow all characters -- but only if you are insane.
  |
  | DO NOT CHANGE THIS UNLESS YOU FULLY UNDERSTAND THE REPERCUSSIONS!!
  |
 */
$config['permitted_uri_chars'] = 'a-z а-я 0-9~%.:_\-';


/*
  |--------------------------------------------------------------------------
  | Enable Query Strings
  |--------------------------------------------------------------------------
  |
  | By default CodeIgniter uses search-engine friendly segment based URLs:
  | example.com/who/what/where/
  |
  | By default CodeIgniter enables access to the $_GET array.  If for some
  | reason you would like to disable it, set 'allow_get_array' to FALSE.
  |
  | You can optionally enable standard query string based URLs:
  | example.com?who=me&what=something&where=here
  |
  | Options are: TRUE or FALSE (boolean)
  |
  | The other items let you set the query string 'words' that will
  | invoke your controllers and its functions:
  | example.com/index.php?c=controller&m=function
  |
  | Please note that some of the helpers won't work as expected when
  | this feature is enabled, since CodeIgniter is designed primarily to
  | use segment based URLs.
  |
 */
$config['allow_get_array'] = TRUE;
$config['enable_query_strings'] = FALSE;
$config['controller_trigger'] = 'c';
$config['function_trigger'] = 'm';
$config['directory_trigger'] = 'd'; // experimental not currently in use

/*
  |--------------------------------------------------------------------------
  | Error Logging Threshold
  |--------------------------------------------------------------------------
  |
  | If you have enabled error logging, you can set an error threshold to
  | determine what gets logged. Threshold options are:
  | You can enable error logging by setting a threshold over zero. The
  | threshold determines what gets logged. Threshold options are:
  |
  |	0 = Disables logging, Error logging TURNED OFF
  |	1 = Error Messages (including PHP errors)
  |	2 = Debug Messages
  |	3 = Informational Messages
  |	4 = All Messages
  |
  | For a live site you'll usually only enable Errors (1) to be logged otherwise
  | your log files will fill up very fast.
  |
 */
$config['log_threshold'] = 0;

/*
  |--------------------------------------------------------------------------
  | Error Logging Directory Path
  |--------------------------------------------------------------------------
  |
  | Leave this BLANK unless you would like to set something other than the default
  | application/logs/ folder. Use a full server path with trailing slash.
  |
 */
$config['log_path'] = APPPATH.'logs/';

/*
  |--------------------------------------------------------------------------
  | Date Format for Logs
  |--------------------------------------------------------------------------
  |
  | Each item that is logged has an associated date. You can use PHP date
  | codes to set your own date formatting
  |
 */
$config['log_date_format'] = 'Y-m-d H:i:s';

/*
  |--------------------------------------------------------------------------
  | Cache Directory Path
  |--------------------------------------------------------------------------
  |
  | Leave this BLANK unless you would like to set something other than the default
  | system/cache/ folder.  Use a full server path with trailing slash.
  |
 */
$config['cache_path'] = APPPATH.'cache/';

/*
  |--------------------------------------------------------------------------
  | Encryption Key
  |--------------------------------------------------------------------------
  |
  | If you use the Encryption class or the Session class you
  | MUST set an encryption key.  See the user guide for info.
  |
 */
$config['encryption_key'] = 'TorrentIndexByEdison634';

/*
|--------------------------------------------------------------------------
| Session Variables
|--------------------------------------------------------------------------
|
| 'sess_driver'
|
|	The storage driver to use: files, database, redis, memcached
|
| 'sess_cookie_name'
|
|	The session cookie name, must contain only [0-9a-z_-] characters
|
| 'sess_expiration'
|
|	The number of SECONDS you want the session to last.
|	Setting to 0 (zero) means expire when the browser is closed.
|
| 'sess_save_path'
|
|	The location to save sessions to, driver dependent.
|
|	For the 'files' driver, it's a path to a writable directory.
|	WARNING: Only absolute paths are supported!
|
|	For the 'database' driver, it's a table name.
|	Please read up the manual for the format with other session drivers.
|
|	IMPORTANT: You are REQUIRED to set a valid save path!
|
| 'sess_match_ip'
|
|	Whether to match the user's IP address when reading the session data.
|
|	WARNING: If you're using the database driver, don't forget to update
|	         your session table's PRIMARY KEY when changing this setting.
|
| 'sess_time_to_update'
|
|	How many seconds between CI regenerating the session ID.
|
| 'sess_regenerate_destroy'
|
|	Whether to destroy session data associated with the old session ID
|	when auto-regenerating the session ID. When set to FALSE, the data
|	will be later deleted by the garbage collector.
|
| Other session cookie settings are shared with the rest of the application,
| except for 'cookie_prefix' and 'cookie_httponly', which are ignored here.
|
*/
$config['sess_driver'] = 'database';
$config['sess_cookie_name'] = 'session';
$config['sess_expiration'] = 3100;
$config['sess_save_path'] = 'sessions';//APPPATH.'cache/sessions/';
$config['sess_match_ip'] = FALSE;
$config['sess_time_to_update'] = 300;
$config['sess_regenerate_destroy'] = TRUE;


/*
  |--------------------------------------------------------------------------
  | Cookie Related Variables
  |--------------------------------------------------------------------------
  |
  | 'cookie_prefix' = Set a prefix if you need to avoid collisions
  | 'cookie_domain' = Set to .your-domain.com for site-wide cookies
  | 'cookie_path'   =  Typically will be a forward slash
  | 'cookie_secure' =  Cookies will only be set if a secure HTTPS connection exists.
  |
 */
$config['cookie_prefix'] = "";
$config['cookie_domain'] = ".".$_SERVER['HTTP_HOST'];
$config['cookie_path'] = "/";
$config['cookie_secure'] = FALSE;

/*
  |--------------------------------------------------------------------------
  | Global XSS Filtering
  |--------------------------------------------------------------------------
  |
  | Determines whether the XSS filter is always active when GET, POST or
  | COOKIE data is encountered
  |
 */
$config['global_xss_filtering'] = FALSE;

/*
  |--------------------------------------------------------------------------
  | Cross Site Request Forgery
  |--------------------------------------------------------------------------
  | Enables a CSRF cookie token to be set. When set to TRUE, token will be
  | checked on a submitted form. If you are accepting user data, it is strongly
  | recommended CSRF protection be enabled.
  |
  | 'csrf_token_name' = The token name
  | 'csrf_cookie_name' = The cookie name
  | 'csrf_expire' = The number in seconds the token should expire.
 */
$config['csrf_protection'] = TRUE;
$config['csrf_token_name'] = 'protect';
$config['csrf_cookie_name'] = 'WjhfGHFhgdWVFH';
$config['csrf_expire'] = 7200;

/*
  |--------------------------------------------------------------------------
  | Output Compression
  |--------------------------------------------------------------------------
  |
  | Enables Gzip output compression for faster page loads.  When enabled,
  | the output class will test whether your server supports Gzip.
  | Even if it does, however, not all browsers support compression
  | so enable only if you are reasonably sure your visitors can handle it.
  |
  | VERY IMPORTANT:  If you are getting a blank page when compression is enabled it
  | means you are prematurely outputting something to your browser. It could
  | even be a line of whitespace at the end of one of your scripts.  For
  | compression to work, nothing can be sent before the output buffer is called
  | by the output class.  Do not 'echo' any values with compression enabled.
  |
 */
$config['compress_output'] = FALSE;

/*
  |--------------------------------------------------------------------------
  | Master Time Reference
  |--------------------------------------------------------------------------
  |
  | Options are 'local' or 'gmt'.  This pref tells the system whether to use
  | your server's local time as the master 'now' reference, or convert it to
  | GMT.  See the 'date helper' page of the user guide for information
  | regarding date handling.
  |
 */
$config['time_reference'] = 'local';


/*
  |--------------------------------------------------------------------------
  | Rewrite PHP Short Tags
  |--------------------------------------------------------------------------
  |
  | If your PHP installation does not have short tag support enabled CI
  | can rewrite the tags on-the-fly, enabling you to utilize that syntax
  | in your view files.  Options are TRUE or FALSE (boolean)
  |
 */
$config['rewrite_short_tags'] = FALSE;


/*
  |--------------------------------------------------------------------------
  | Reverse Proxy IPs
  |--------------------------------------------------------------------------
  |
  | If your server is behind a reverse proxy, you must whitelist the proxy IP
  | addresses from which CodeIgniter should trust the HTTP_X_FORWARDED_FOR
  | header in order to properly identify the visitor's IP address.
  | Comma-delimited, e.g. '10.0.1.200,10.0.1.201'
  |
 */
$config['proxy_ips'] = '';


/* End of file config.php */
/* Location: ./application/config/config.php */
