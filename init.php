<?php
//debug
//echo "1. init called<br />2.";
 //print_r($author_settings);
 //echo "<br />3.";  


/**
***************************************************************************************************
 * @Software    AjaxMint Gallery
 * @Author      Rajapandian - arajapandi@gmail.com
 * @Copyright	Copyright (c) 2010-2011. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://ajaxmint.com. All Rights Reserved.
 **************************************************************************************************
**/
 
 
// added 11-4-12 PRB to overcome error----------------------------------------------
ini_set("memory_limit","12M"); 
// orginal commented out 
//ini_set("memory_limit",$settings['config_memory_limit'].'M');

/* Root Directory moved in calling script
 
//$root_dir = dirname(__FILE__);    //MK removed 
$root_dir = $_SERVER['DOCUMENT_ROOT'];  //MK added
define('ROOT_DIR', $root_dir);
*/

///////////////////////// multi domains management //////////////////////////////
    if ($_SERVER['PHP_SELF'] == '/admin/index.php') {
        $hostdir = '../';
        } elseif ($_SERVER['PHP_SELF'] == '/app/controller/ajax.php') {
        $hostdir = '../../';
        } else {
        $hostdir = '';
        }
		//debug		
    //echo $hostdir."<br />4." ;    
		
    if (isset($_SERVER['REMOTE_HOST'])) $hostname = $_SERVER['REMOTE_HOST'];
    if (isset($_SERVER['HTTP_X_FORWARDED_HOST'])) $hostname = $_SERVER['HTTP_X_FORWARDED_HOST'];
    if (isset($_SERVER['HTTP_HOST'])) $hostname = $_SERVER['HTTP_HOST'];
//echo $hostname;		
	if ($hostname) { 
 
        
        $hostname = strtolower($hostname);
        if (strpos($hostname,'www.') === false) $hostname = 'www.'.$hostname;
        define('SHORT_HOSTNAME',$hostname);
        
        $filename = $hostdir."settings/$hostname/settings.php";
     
        if (file_exists($filename)) {
           
            require_once($filename);
            
            // the author attached to the current URL (www.emediadeal.com) has his own settings
            foreach ($author_settings as $key=>$value) {
                $settings[$key] = $value;
                }  
//print_array($settings);   
            if (! $author_settings['domain_owner']  || 
                $author_settings['domain_owner'] == '') {
                $domain_owner = 0;
                } else {
                $domain_owner = $author_settings['domain_owner'];
                }
            define('DOMAIN_OWNER',$domain_owner);  
            } else {
            //echo 'Sorry. This site is not active'; exit;
            define('DOMAIN_OWNER',0);  
            }
        } else {
        define('SHORT_HOSTNAME','');
        define('DOMAIN_OWNER',0);  
        }
//echo DOMAIN_OWNER.'--'.SHORT_HOSTNAME; 
//print_array($_SERVER); 
//exit; 
////////////////////////////////////////////////////////////////////////////////


//define('APP_DIR', $settings['config_site_host']);
define('HTTP_HOST', $settings['config_site_url']);
//print_array($settings);
//getting current directory path
$exploded_path = explode('/', dirname($_SERVER['PHP_SELF']));
$current_dir = count($exploded_path)>1?end($exploded_path):'';
define('CURRENT_DIR', "/".$current_dir);


/* define http url */
$http_server = !defined('APP_MAIN') ? HTTP_HOST.CURRENT_DIR : HTTP_HOST;
define('HTTP_SERVER', $http_server);

/* Current Application Directory */
$dir_app = !defined('APP_MAIN') ? ROOT_DIR.CURRENT_DIR : ROOT_DIR;
define('DIR_APPLICATION', $dir_app."/");

/* System Direcoty */
define('DIR_SYSTEM', ROOT_DIR.'/system/');

/*
	Assets Directory
*/
$upload_dir = 'pictures';
define('ASSET_PICTURES',$upload_dir);

if (DOMAIN_OWNER != 0) $upload_dir .= '/'.DOMAIN_OWNER;

define('HTTP_IMAGE', HTTP_HOST.'/'.$upload_dir);
define('HTTP_THUMB', HTTP_HOST.'/'.$upload_dir.'/thumb');
define('HTTP_LARGE', HTTP_HOST.'/'.$upload_dir.'/large');
//echo HTTP_IMAGE; exit;

/* nice links */
define('NICE_ALBUM_LINK','/pictures/al/');

/* Image Directory Path */
define('DIR_IMAGE', ROOT_DIR."/".$upload_dir);

//echo DIR_IMAGE; exit;
/*
	Albums Directory
*/
$galleria_dir = 'galleria';

define('GALLERIA',$galleria_dir);
define('HTTP_GALLERIA', HTTP_HOST.'/'.GALLERIA);
define('HTTP_GALLERIA_THUMB', HTTP_HOST.'/'.GALLERIA.'/thumb');
define('HTTP_GALLERIA_LARGE', HTTP_HOST.'/'.GALLERIA.'/large');

/* Albums Directory Path */
define('DIR_GALLERIA', ROOT_DIR."/".GALLERIA);
 
define('AUTHOR_LEVEL',5);
define('ADMIN_LEVEL',1);

define('BLANK_GIF','/app/view/default/images/blank.gif'); 

define('MAX_RETURN_ROWS',16);   // tested also with 5
define('FIRST_ALBUM_ID',6);   // tested also with 5
define('SCROLLER',3);
define('MIN_BODY_WIDTH',960);
define('ADMIN_SILHOUETTE','admin_silhouette.jpg');
define('GENERIC_COVER','generic_cover.jpg');