<?php
/**
***************************************************************************************************
 * @Software    emediaPublishing loosely based on ajaxMint, a PHP MVC framework by ajapandian - arajapandi@gmail.com
 * @Author      Michel Kohon 
 * @Copyright	Copyright (c) 2012. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE  
 * This license covers all scripts, PHP, javascript, css, html, etc... called directly or indirectly from this root 
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2012 http://emediacart.com. All Rights Reserved.
 **************************************************************************************************
**/

    $debug_cookie = 0;
    if (  $_COOKIE['debug'] == 'active' ||   // un-comment to have debug in every page.. w/ possible side-effects for standard users
        $_GET['debug'] == 'active') $debug_cookie = 1;  
    if ($_GET['debug'] == 'inactive') {
        $debug_cookie = 0;  
        }
 
    //$remote_ip = $_SERVER['REMOTE_ADDR'];
 
//Config moved from init.php
$root_dir = $_SERVER['DOCUMENT_ROOT'];  //MK added
define('ROOT_DIR', $root_dir);
// Configuration 
 
define('APP_MAIN','1');
define('GET_CONTROLLER','c');   // MK

require_once('config.php');
require_once('settings.php');
require_once('init.php');

// Startup
require_once(DIR_SYSTEM . 'startup.php');
require_once(DIR_SYSTEM . 'helper/user.php');   //MK added
 
// Loader
$loader = new Loader();
Registry::set('load', $loader);

// Config
$config = new Config();
Registry::set('config', $config);

// Database 
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
Registry::set('db', $db);


//print_array($author_settings); exit;
//echo 'config  '.$config->get('config_dynamic_ajax');  exit;
//settings

// removed following 3 lines to overome error PRB------------------------------------
foreach ($settings as $key=>$value) {
  $config->set($key,$value);
}
                 
define('SITE_TITLE',$config->get('config_site_title'));
 
/* facebook */
define('FB_APP_ID',$config->get('fb_app_id'));
define('FB_APP_SECRET',$config->get('fb_app_secret'));

/* help files */
define('VISITORS_HELP',$config->get('visitors_help_filename'));
define('AUTHORS_HELP',$config->get('authors_help_filename'));
define('ABOUT_FILE',$config->get('about_filename'));
define('NEWS_FILE',$config->get('news_filename'));

$config->set('locale','en');     // set language here for now

$log = new Logger($config->get('config_error_filename'));
Registry::set('log', $log);
 
/* to active debug, run the script w/ debug=active
    the ajax server will create a file in controller/log or whe ever it's defined in ajax.php
    config_error_log in the settings table must also be set to 1
    
    That double control allows specific debugging per session
    The $_GET statement must be at the top as somewhere $_GET, $_COOKIE, etc are reset
*/

 if ($config->get('config_error_log') == 1) {
    if ($debug_cookie == 1) {
        define('DEBUG',true);
        setcookie('debug','active',0,'/'); 
         
        //file_put_contents('debug_debug.dat',print_array(,true)."\n",FILE_APPEND); 
        } else {
        define('DEBUG',false);
        setcookie('debug','inactive',0,'/');
         
        }
    } else {
    define('DEBUG',false);
    setcookie('debug','inactive',0,'/');
       
    }
                                   
// Error Handler
function error_handler($errno, $errstr, $errfile, $errline) {
    global $config, $log;
    
    switch ($errno) {       
        case E_WARNING:
        case E_USER_WARNING:
            $errors = "Warning";
            break;
        case E_ERROR:
        case E_USER_ERROR:
            $errors = "Fatal Error";
            break;
        default:
            $errors = "Unknown";
            break;
    }
    
    if ($config->get('config_error_display') && $errors) {
        echo '<b>' . $errors . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
    }
    
    if ($config->get('config_error_log') && $errors) {
        $log->write('PHP ' . $errors . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
    }

    return true;
}


// set to the user defined error handler
set_error_handler('error_handler');

// Request
$request = new Request();
//echo $request->server['REMOTE_ADDR']; exit;
Registry::set('request', $request); 


/* this is to login to the pin2shop twitter account only
$accessToken = $config->get('accessToken');
$accessTokenSecret = $config->get('accessTokenSecret');
*/

// Response
$response = new Response();
$response->addHeader('Content-Type', 'text/html; charset=utf-8');
Registry::set('response', $response);

// Cache
Registry::set('cache', new Cache());

// Url
Registry::set('url', new Url());

// Session
$session = new Session();
Registry::set('session', $session);
 
// User
$user = new HelperUser();
Registry::set('user', $user);

 
//seo urls
$seourl = new SeoUrl();
$seourl->enable_url=$config->get('config_seo_url');
Registry::set('seourl', $seourl);
    
// Front Controller 
$controller = new Front();

// Router
if (isset($request->get[GET_CONTROLLER])) {
    $action = new Router($request->get[GET_CONTROLLER]);
} else {
    $action = new Router('index');
}

// Dispatch
$controller->dispatch($action, new Router('index'));

// Output
$response->output($config->get('config_compression'));
 
    
function print_array($array, $level=0){ 
    $num = $level === 0 ? 0 : 4; 
    $colors = array(    'bracket'=>'#999999', 
                        'value'=>'#FF0000', 
                        'array'=>'#009900', 
                        'arrow'=>'#999999', 
                        'type'=>'#0000FF', 
                        'key'=>'#000000'); 
    echo '<span style="color: ' . $colors['array'] . ';">'; 
     
    echo "Array\n" . str_pad('', ($level * 4) + ($level * $num), " ") . "(\n"; 
    $keys = array_keys($array); 
    foreach($keys as $key) { 
        echo '<div>'.str_pad('', ($level * 4) + ($level * $num), "-"); 
        echo '    <span style="color: ' . $colors['bracket'] . ';">'; 
        echo '[<span style="color: ' . $colors['key'] . ';">' . $key . '</span>]'; 
        echo '</span> <span style="color: ' . $colors['arrow'] . ';">=></span> '; 
        if (is_array($array[$key])){ 
            print_array($array[$key], $level + 1); 
            }else{ 
            echo ' <span style="color: ' . $colors['value'] . ';">' . $array[$key] . '</span>'; 
            echo ' <span style="color: ' . $colors['type'] . ';">' . gettype($array[$key]) . "</span>\n"; 
            } 
        echo '</div>';
        } 
    echo str_pad('', ($level * 4) + ($level * $num), " ") . ")</span>\n"; 
} 


function str_extract($str,$token_start,$token_end='')  {
 
    $strin = '/' . $str;    // to start at 1
    $st = strpos($strin,$token_start,1);
   
    if ($st == 0) return '';
    $st += strlen($token_start);
 
    if ($token_end == '') {
        $sp = strlen($strin);
        } else {
        $sp = strpos($strin, $token_end,$st);
        if ($sp === false) return substr($strin, $st);
        }
 
    if ($sp == 0) return '';
 
    $res =  substr($strin, $st ,$sp - $st );
    
    return $res;
    
} 


function secureCurl($parray,$rs) {

	$qs = "";
	
	foreach($parray as $par=>$value){ 
		$qs = $qs . "$par=" . urlencode($value) . "&";
		}
	$qs = trim($qs,'&');	
 
    if ($qs == '') {
        $uri = $rs;
        } else {
        $uri = "$rs?$qs";
        }

	//cUrl
	$cobj = curl_init();
  
    curl_setopt($cobj, CURLOPT_URL, $uri);
    curl_setopt($cobj, CURLOPT_POST,count($parray));
	curl_setopt($cobj, CURLOPT_POSTFIELDS,$qs);
//echo $uri.'--'.count($parray); exit;       
    curl_setopt($cobj, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($cobj, CURLOPT_RETURNTRANSFER, true);
    //curl_setopt($cobj, CURLOPT_TIMEOUT, 5);
    //security
    curl_setopt($cobj, CURLOPT_SSL_VERIFYPEER, false);
    // to set up correct SSL, visit: http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/

    //curl_setopt($cobj, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($cobj, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");
	
    $response = curl_exec($cobj);
   
    $httpCode = curl_getinfo($cobj, CURLINFO_HTTP_CODE);

    if($httpCode == 404) {
        return '404$^$';
        }	
	$error = curl_errno ($cobj);

	curl_close($cobj);
	
	return $error.'$^$'.$response;        
}
 
function list_files($src) { 

    $dir = opendir($src);
    if (! $dir) return false;

    $list = array();

    while ( false !== ( $file = readdir( $dir ) ) ) { 
 
        if ($file == '.' || $file == '..') continue;
         
        if (is_dir("$src/$file")) continue;
      
        $list[] = $file;

        } 
       
    closedir($dir);
    return $list; 
} 
