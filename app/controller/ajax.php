<?php
/**
***************************************************************************************************
 * @Software    emediaPublishing 
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
    error_reporting(E_ALL);
    ini_set('display_errors','On');
    set_time_limit (0);

    define('NEW_LINE','<BR>');
    define('DOT','.');
    define('PIPE','|');
    define('COMMA',',');    
    define('CACHE_DIR','cache/');
    define('LOGFILE','log/logfile.dat');
  
    define('MAX_LIFE',7);   // 7 days cache file life
      
    define('MAX_CAROUSEL',16);
    define('CAROUSEL_SLATE',4);
 
    define('FIRST_GALLERY_ID',12);
 
    // that's the paypal samdbox app id.. get one before going to prod when site is ok
 
    define('PAYPAL_USERID','');  
    define('PAYPAL_PASSWORD',' ');  
    define('PAYPAL_SIGNATURE',' ');  

    define('PayPal_nvps_URL','https://api-3t.sandbox.paypal.com/nvp'); // SandBox URL
 
    define('PAYPAL_PAY_IMAGE','https://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');
         
    // that's the paypal samdbox app id.. get one before going to prod when site is ok    
    define('PAYPAL_APPLICATIONID','');  
 
    define('PAYPAL_ENDPOINT','https://svcs.sandbox.paypal.com/AdaptivePayments/Pay');
//$_REQUEST['debug']='active';

    define('FORCELOG',0);               // 1: force log no matter what; 0: let LOGLEVEL decide
    
    if (isset($_REQUEST['debug'])) {
        define('LOGLEVEL',1);   // set it to non zero to have a log
        } else {
        define('LOGLEVEL',0);   // set it to non zero to have a log
        }
      
    $debug = true; // should come from some init file
    //// leave debug true otherwise it fails... I couln't find why!!!
     
    define ('DEBUG',$debug);
  
 //file_put_contents(LOGFILE,print_r($_REQUEST,true)); exit;

    if (DEBUG) log_trace('START',print_r($_REQUEST,true));

    $origin = 'promo_carousel';
    if (isset($_REQUEST['origin'])) $origin = $_REQUEST['origin'];
           
    $cat_id = 0;
    if (isset($_REQUEST['cat_id'])) $cat_id = $_REQUEST['cat_id'];    // 0=no, otherwise category_id
    if ($cat_id != 0) $origin = 'new_albums';
             
    $mode = 'cats';
    if (isset($_REQUEST['mode'])) $mode = $_REQUEST['mode'];
  
    $user_id = 0;
    if (isset($_REQUEST['user_id'])) $user_id = $_REQUEST['user_id'];     

    //  jCarousel positions are 1-based.
    $first = 0;
    if (isset($_REQUEST['first'])) $first = $_REQUEST['first'];
    $last = 0;
    if (isset($_REQUEST['last'])) $last  =  $_REQUEST['last'];
 
    $search_level = 0;
    if (isset($_REQUEST['search_level'])) $search_level = $_REQUEST['search_level'];  // 0=cat, 1..N=sub-cats 

    $prod_id = 0;
    if (isset($_REQUEST['prod_id'])) $prod_id = $_REQUEST['prod_id'];     

    $pic_id = 0;
    if (isset($_REQUEST['pic_id'])) $pic_id = $_REQUEST['pic_id'];     

    $manager_id = 0;
    if (isset($_REQUEST['manager_id'])) $manager_id = $_REQUEST['manager_id'];     

    $album = '';  
    if (isset($_REQUEST['album'])) $album = $_REQUEST['album'];
 
    if ($album == 'top_liked') {
        $origin = 'new_albums';
        }
    
    $author = '';
    if (isset($_REQUEST['author'])) $author = trim($_REQUEST['author']);
 
    $q_text = '';  
 
    // comics publishing platform zormics
    $mapbefore = '';
    if (isset($_REQUEST['mapbefore'])) $mapbefore = $_REQUEST['mapbefore'];
    $mapafter = '';
    if (isset($_REQUEST['mapafter'])) $mapafter = $_REQUEST['mapafter'];    

    $itemidz = '';
    if (isset($_REQUEST['itemidz'])) $itemidz = $_REQUEST['itemidz'];  
    
    $src = '';
    if (isset($_REQUEST['src'])) $src = $_REQUEST['src'];     
    $properties = '';
    if (isset($_REQUEST['properties'])) $properties = $_REQUEST['properties'];     
      
    ///

    
    if ($mode == 'to_cats') {
        $mode = 'cats';
        $search_level = -1;
        }   

    $pledge_id = 0;
    if (isset($_REQUEST['pledge_id'])) $pledge_id = $_REQUEST['pledge_id'];

    $zip = '';  // not used but needed to avoid php warnings
    if (isset($_REQUEST['zip'])) $zip = $_REQUEST['zip'];
    
    //Config  
    $root_dir = $_SERVER['DOCUMENT_ROOT'];  
    define('ROOT_DIR', $root_dir); 
      
    // Configuration
    require_once(ROOT_DIR.'/config.php');
    require_once(ROOT_DIR.'/settings.php');
    require_once(ROOT_DIR.'/init.php');
    
    //if (DEBUG) log_trace('START',print_r($settings,true));
    if (DEBUG) log_trace('START',"origin=$origin");
    
    $cache_max_life = ROOT_DIR.'/milo/'.CACHE_DIR."max_life.dat";   // ends with /
    //file_put_contents($cache_max_life,'max_life='.MAX_LIFE);    // for spider
  
    // Startup
    require_once(DIR_SYSTEM . 'startup.php');    
    // Config
    $config = new Config();
 
    // Database 
    $db_link = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

    if (! $db_link) die("<error>Cannot connect</error></data>");
 
    switch ($origin) {
        case 'test_me':
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            echo '<foo>hello world</foo>';
            echo '</data>';
            break;

                        
        case 'eventCalendar':   // zormics
            header('Content-type: text/json');
            echo '[';
    
            load_events($user_id);
              
            echo ']';
            break;
       
        case 'update_likes':
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            update_likes($album,$mode);
            echo '</data>';
            break;
            
        case 'complete_crop':
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            update_thumb($src);
            echo '</data>';
            break;
        
        case 'release_now':
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            $count = release_now($pic_id);
             
            echo "<count>$count</count>";
            echo '</data>';
            break;
                       
        case 'crop_image':          
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            $sx = explode('_',$properties);

            $x = $sx[0];
            $y = $sx[1];
            $w = $sx[2];
            $h = $sx[3];
            $target_w = $sx[4];
            $target_h = $sx[5];            
            crop_image($src,$x,$y,$w,$h,$target_w,$target_h);
            echo '</data>';
            break;
                 
        case 'reorder_pages':   
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
            reorderPages($user_id,$itemidz,$mapbefore,$mapafter);
            echo '</data>';
            break;

        case 'authors':         
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
 
            
            if (DEBUG) log_trace('album',$album);
            if (DEBUG) log_trace('author',$author);
            
            $home_url = "&lt;a href='index.php?origin=new_albums' &gt;<b>All Albums</b>&lt;/a&gt;";
                
            if (strtolower($album) != 'title') {
                echo "<breadcrumbs>Search results for album: '$album' - $home_url</breadcrumbs>";  
                } else {
                echo "<breadcrumbs>Search results for author: '$author' - $home_url</breadcrumbs>";  
                }
                            
            if (strtolower($album) != 'title' || strtolower($author) != 'author') {
                search_authors_albums($user_id,$first,$last,$album,$author,$cat_id);  
                } else {
                load_authors($user_id,$first,$last,$cat_id);  
                }   
                     
            echo '</data>';
            break;
                        
        case 'new_albums':   // 
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';
 
            $home_url = "&lt;a href='index.php?origin=new_albums' &gt;<b>All Albums</b>&lt;/a&gt;";
            
            if ($album == 'top_liked') {
                echo "<breadcrumbs>Top Liked Albums - $home_url</breadcrumbs>";    
                load_albums($user_id,$first,$last,$cat_id,'likes');
                } else {
                echo "<breadcrumbs>All Albums (newest first)</breadcrumbs>";  
                load_albums($user_id,$first,$last,$cat_id,'all');
                }
                              
            echo '</data>';
            break;
        
        case 'author_albums':
            header('Content-Type: text/xml');
            echo '<data><origin>' . $origin . '</origin>';        
 
            
            $home_url = "&lt;a href='index.php?origin=new_albums' &gt;<b>All Albums</b>&lt;/a&gt;";
            
            echo "<breadcrumbs>My Albums - $home_url</breadcrumbs>";         
            load_author_albums($user_id,$first,$last,$cat_id);        
            echo '</data>';
            break;
  
        
        }   // end case
 
    exit;
 
function search_authors($user_id,$first,$last,$album,$author) {
    /* remember that private albums still show up in the carousel but
    when selected we askj for a token
    */    
    global $db_link;
    
    if (DEBUG) log_trace('search_authors author: ',$author);
    
    $admin_level = get_admin_level($user_id); 
 
    $max = $last - $first + 1;
    $first--;
    $total = 0;
    $morequery = ' AND al.pictures > 0 AND gl.albums > 0 ';
 
    $albums_tbl = DB_PREFIX.'albums';
    $gallery_tbl = DB_PREFIX.'gallery';
 
    $morequery .= " AND (LOCATE('$author',gl.name) != 0) ";
 
    if ($album != 'Title') $morequery .= " AND (LOCATE('$album',al.title) != 0) ";
    //if (DEBUG) log_trace('search_authors album: ',$album);
    
    if ($admin_level != ADMIN_LEVEL) {
        $morequery .= " AND (al.date_released <= NOW() 
                            OR al.date_released = 0 OR al.gallery_id = '$user_id'  )";
        }
       
    $first_author = FIRST_GALLERY_ID;
        
    $query = "SELECT *,al.image as image FROM $albums_tbl al
                LEFT JOIN $gallery_tbl gl 
                    ON (gl.gallery_id = al.gallery_id) 
                WHERE al.image != '' 
                AND (LOCATE('/noimage/', al.image) = 0) 
                $morequery 
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                AND al.gallery_id > $first_author
                ORDER BY al.date_released DESC
                LIMIT $first,$max ";
    
    if (DEBUG) log_trace('search_authors',$query);
       
    $result = $db_link->query($query);  
 
    if (DEBUG) log_trace('search_authors result',$result->num_rows);

    if ($result->num_rows == 0) {
    
        if ($first != 1) echo '  <mysql>EOF</mysql>';
        
        echo '  <total>' . $total . '</total>';
        if (DEBUG) log_trace('total',$total);     
        return 0;
        }
        
    $images = $result->rows;
    foreach ($images as $img) {
        echo '  <image>' . 
            HTTP_GALLERIA_THUMB . '/'. 
            $img['image'] . 
            '</image>';
        $total++;
        }


    foreach ($images as $img) {
        //$time_ago = time_ago($img['date_released']) . ' ago';
        $time_ago = $img['date_released'];
        echo '  <time_ago>' . $time_ago . '</time_ago>';
        }
      
 
    foreach ($images as $img) {
        echo '  <title>' . htmlspecialchars (addslashes($img['title'])) . '</title>';
        }
    
    foreach ($images as $img) {
        echo '  <gallery>' . $img['gallery_id'] . '</gallery>';
        }
    
    foreach ($images as $img) {
        echo '  <author>' . htmlspecialchars (addslashes($img['name'])) . '</author>';
        }
 
    
    foreach ($images as $img) {
        echo '  <album>' . $img['album_id'] . '</album>';
        }
    
    foreach ($images as $img) {
        echo '  <pictures>' . $img['pictures'] . '</pictures>';
        }
            
    foreach ($images as $img) {
        echo '  <first_paid_page>' . $img['first_paid_page'] . '</first_paid_page>';
        }

            
    foreach ($images as $img) {
        echo '  <description>' . htmlspecialchars (addslashes($img['description'])) . '</description>';
        }

    echo '  <mysql>OK</mysql>'; 
           
    echo '  <total>' . $total . '</total>';
    if (DEBUG) log_trace('total',$total);
}

function search_authors_albums($user_id,$first,$last,$album,$author,$cat_id) {
    /* remember that private albums still show up in the carousel but
    when selected we askj for a token
    */    
    global $db_link;
 
    if (strtolower($author) != 'author') return search_authors($user_id,$first,$last,$album,$author);
 
    $admin_level = get_admin_level($user_id);
     
    $max = $last - $first + 1;
    $first--;
    $total = 0;
 
    $morequery = " AND (LOCATE('$album',al.title) != 0) AND al.pictures > 0 "; 
    
    if ($admin_level != ADMIN_LEVEL) {
        $morequery .= " AND (al.date_released <= NOW() OR 
                             al.date_released = 0 OR 
                             al.gallery_id = '$user_id'  )";
        }
        
 
    $table = DB_PREFIX.'albums ';
    $gallery_tbl = DB_PREFIX.'gallery ';
 
    $first_author = FIRST_GALLERY_ID;
        
    $query = "SELECT *,al.image as image FROM $table al 
                LEFT JOIN $gallery_tbl gl 
                    ON (gl.gallery_id = al.gallery_id)       
                WHERE al.image != '' 
                AND (LOCATE('/noimage/', al.image) = 0) 
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                $morequery 
                AND al.gallery_id > $first_author
                ORDER BY al.date_released DESC
                LIMIT $first,$max ";
     
    if (DEBUG) log_trace('search_authors_albums',$query);
       
    $result = $db_link->query($query);  
 
    if (DEBUG) log_trace('search_authors_albums result',$result->num_rows);

    if ($result->num_rows == 0) {
    
        if ($first != 1) echo '  <mysql>EOF</mysql>';
        
        echo '  <total>' . $total . '</total>';
        if (DEBUG) log_trace('total',$total);     
        return 0;
        }
        
    $images = $result->rows;
    foreach ($images as $img) {
        echo '  <image>' . 
            HTTP_GALLERIA_THUMB . '/'. 
            $img['image'] . 
            '</image>';
        $total++;
        }


    foreach ($images as $img) {
        //$time_ago = time_ago($img['date_released']) . ' ago';
        $time_ago = $img['date_released'];
        echo '  <time_ago>' . $time_ago . '</time_ago>';
        }
      
 
    foreach ($images as $img) {
        echo '  <title>' . htmlspecialchars (addslashes($img['title'])) . '</title>';
        }
    
    foreach ($images as $img) {
        echo '  <gallery>' . $img['gallery_id'] . '</gallery>';
        }
 
    
    foreach ($images as $img) {
        echo '  <pictures>' . $img['pictures'] . '</pictures>';
        }
             
    foreach ($images as $img) {
        echo '  <album>' . $img['album_id'] . '</album>';
        }
            
    foreach ($images as $img) {
        echo '  <first_paid_page>' . $img['first_paid_page'] . '</first_paid_page>';
        }

            
    foreach ($images as $img) {
        echo '  <description>' . htmlspecialchars (addslashes($img['description'])) . '</description>';
        }
             
    foreach ($images as $img) {
        echo '  <author>' . htmlspecialchars (addslashes($img['name'])) . '</author>';
        }

    echo '  <mysql>OK</mysql>'; 
           
    echo '  <total>' . $total . '</total>';
    if (DEBUG) log_trace('total',$total);

}


function load_authors($user_id,$first,$last,$cat_id) { 
    /* remember that private albums still show up in the carousel but
    when selected we askj for a token
    */ 
    global $db_link;
 
    $admin_level = get_admin_level($user_id);
    // get author name
    $query = "SELECT * FROM ".DB_PREFIX."gallery
                WHERE  gallery_id = '$user_id' LIMIT 1 ";
 
    $result = $db_link->query($query); 
    $author = $result->row['name'];     
    // 
    $max = $last - $first + 1;
    $first--;
    $total = 0;
    $morequery =  " AND albums > 0 ";
    
    if ($admin_level != ADMIN_LEVEL) {
        $morequery .= " AND (date_released <= NOW() OR 
                            date_released = 0 OR gallery_id = '$user_id'  )";
        }
      
    $table = DB_PREFIX.'gallery';
 
    $first_author = FIRST_GALLERY_ID;
        
    $query = "SELECT * FROM $table  
                WHERE image != '' 
                AND (LOCATE('/noimage/', image) = 0) 
                $morequery 
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                AND gallery_id > $first_author
                LIMIT $first,$max ";
     
    if (DEBUG) log_trace('load_authors',$query);
       
    $result = $db_link->query($query);  
 
    if (DEBUG) log_trace('load_authors result',$result->num_rows);

    if ($result->num_rows == 0) {
    
        if ($first != 1) echo '  <mysql>EOF</mysql>';
        
        echo '  <total>' . $total . '</total>';
        if (DEBUG) log_trace('total',$total);     
        return 0;
        }
        
    $images = $result->rows;
    foreach ($images as $img) {
        echo '  <image>' . 
            HTTP_GALLERIA_THUMB . '/'. 
            $img['image'] . 
            '</image>';
        $total++;
        }

 
    foreach ($images as $img) {
        echo '  <title>' . htmlspecialchars (addslashes($img['name'])) . '</title>';
        }
    
    foreach ($images as $img) {
        echo '  <gallery>' . $img['gallery_id'] . '</gallery>';
        }
 
    
    foreach ($images as $img) {
        echo '  <pictures>' . $img['pictures'] . '</pictures>';
        }
             
    foreach ($images as $img) {
        echo '  <album>' . $img['album_id'] . '</album>';
        }
             
    foreach ($images as $img) {
        echo '  <author>' . htmlspecialchars (addslashes($author)) . '</author>';
        }

    echo '  <mysql>OK</mysql>'; 
                     
    echo '  <total>' . $total . '</total>';
    if (DEBUG) log_trace('total',$total);
}

function load_events($user_id) {
 
    global $db_link;
  
    $admin_level = get_admin_level($user_id);
    if (DEBUG) log_trace("load_events for admin_level: $admin_level user_id=",$user_id);

    $first = 1;
    $max = 10;
 
    $total = 0;

    $gallery_tbl = DB_PREFIX.'gallery ';
    $album_tbl = DB_PREFIX.'albums ';   
     
    $first_album = FIRST_ALBUM_ID;
    // looking for to be released albums
 
    $morequery =  " AND al.pictures > 0 ";
    $morequery .= " AND (al.date_released > NOW() )";
    
    $query = "SELECT *,al.image as image,al.date_released as released 
                FROM $album_tbl  al
                LEFT JOIN $gallery_tbl gl 
                    ON (gl.gallery_id = al.gallery_id)     
                 WHERE al.image != '' 
                AND (LOCATE('/noimage/', al.image) = 0) 
                AND al.album_id >= $first_album
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                $morequery 
                ORDER BY al.date_released DESC
                LIMIT $max "; 
                  
    if (DEBUG) log_trace("load_events tobe released albums",$query);
       
    $result = $db_link->query($query);  
 
    if (DEBUG) log_trace('load_events result 1',$result->num_rows);

    if ($result->num_rows != 0) {
  
        $rows = $result->rows;
    
        prep_events_json($rows,'Album');    
        }
   
    $total = 0;
    $picture_tbl = DB_PREFIX.'pictures ';   

    // looking for released albums with to be released pages 
    $morequery =  " AND al.pictures > 0 ";
    $morequery .= " AND (pic.date_released > NOW() )";
    
    $query = "SELECT *,al.image as image,pic.date_released as released,pic.title as p_title  
                FROM $picture_tbl  pic
                LEFT JOIN $album_tbl al 
                    ON (pic.album_id = al.album_id)  
                LEFT JOIN $gallery_tbl gl 
                    ON (gl.gallery_id = al.gallery_id)     
                 WHERE al.image != '' 
                AND (LOCATE('/noimage/', al.image) = 0) 
                AND al.album_id >= $first_album
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                $morequery 
                ORDER BY pic.date_released DESC
                LIMIT  $max "; 
                  
    if (DEBUG) log_trace("load_events tobe released pics",$query);
       
    $result = $db_link->query($query);  
 
    if (DEBUG) log_trace('load_events result 2',$result->num_rows);

    if ($result->num_rows != 0) {
  
        $rows = $result->rows;
    
        prep_events_json($rows,'Picture',',');    
        }    
}

function prep_events_json($rows,$type,$separator='') {
 
    foreach ($rows as $fvalue) {
    
        $date_released = strtotime($fvalue['released']) * 1000;
        $title = $fvalue['title'];
        if ($type == 'Picture') $title .= ' - '. htmlspecialchars (addslashes($fvalue['p_title']));
        
        $desc = htmlspecialchars (addslashes($fvalue['description']));
        $album_id = $fvalue['album_id'];
        $url = NICE_ALBUM_LINK.$album_id;    // won't be able to access unless ADMIN or owner
        $type .= ' Release';
        
        $jsonData = $separator;
        $jsonData .= '	{ "date": "'.$date_released.
                        '", "type": "'.$type.
                        '", "title": "'.$title.
                        '", "description": "'.$desc.
                        '", "url": "'.$url.'" } ';
                        
        if (DEBUG) log_trace('load_events result', $jsonData );
        echo $jsonData;
        
        $separator = ",";
        }
    
}
 
function load_albums($user_id,$first,$last,$cat_id,$mode) {
    /* remember that private albums still show up in the carousel but
    when selected we askj for a token
    */
    global $db_link;
 
    $admin_level = get_admin_level($user_id);
    if (DEBUG) log_trace("load_albums for admin_level: $admin_level cat_id=",$cat_id);

    $max = $last - $first + 1;
    $first--;
    $total = 0;
    $morequery =  " AND al.pictures > 0 ";
    
		if ($admin_level == 5) {
		$show_add_sql = " OR ( al.domain_owner = '1') ";
		} else {
		$show_add_sql = "";
		}
		
    switch ($mode) {
    
        case 'likes':    
            $morequery .= " AND likes > 0 ";
            $likes = 'likes, ';
        
            break;

        default:
            $likes = '';
          
            break;
        }
 
    if ($admin_level != ADMIN_LEVEL) {
        $morequery .= " AND (al.date_released <= NOW() OR 
                            al.date_released = 0 OR 
                            al.gallery_id = '$user_id'  )";
        }
   
    if ($cat_id != 0) {
        $morequery .= " AND (al.category_id = '$cat_id') ";
        }
                
    $table = DB_PREFIX.'albums ';
    $gallery_tbl = DB_PREFIX.'gallery ';
 
    $first_album = FIRST_ALBUM_ID;
 
    $query = "SELECT *,al.image as image FROM $table  al
                LEFT JOIN $gallery_tbl gl 
                    ON (gl.gallery_id = al.gallery_id)     
                 WHERE al.image != '' 
                AND (((LOCATE('/noimage/', al.image) = 0) 
                AND al.album_id >= $first_album
                AND (domain_owner = '0' OR domain_owner = '". DOMAIN_OWNER ."') 
                $morequery) $show_add_sql ) 
                ORDER BY $likes al.parent DESC, al.date_released DESC
                LIMIT $first,$max "; 
                  
    if (DEBUG) log_trace("load_albums for $mode: ",$query);
       
    $result = $db_link->query($query);  
    //if (DEBUG) echo "<load_promo>$query</load_promo>";    

    if (DEBUG) log_trace('load_albums result',$result->num_rows);

    if ($result->num_rows == 0) {
    
        if ($first != 1) echo '  <mysql>EOF</mysql>';
        
        echo '  <total>' . $total . '</total>';
        if (DEBUG) log_trace('total',$total);    
        return 0;
        }
        
    $images = $result->rows;
    foreach ($images as $img) {
    
        $jpegfile = DIR_GALLERIA .'/thumb' . '/'.  $img['image']; 
         
        shrink_jpeg($jpegfile);
        
        echo '  <image>' . 
            HTTP_GALLERIA_THUMB . '/'.  
            $img['image'] . 
            '</image>';
        $total++;
        }
   
    foreach ($images as $img) {
        //$time_ago = time_ago($img['date_released']) . ' ago';
        $time_ago = $img['date_released'];
        		if ($img['parent'] == '1') {
        		  echo '  <time_ago>' . date('Y-m-d H:i:s') . '</time_ago>';
        		} else {
        	 		echo '  <time_ago>' . $time_ago . '</time_ago>';
        		}
        }
         
    foreach ($images as $img) {
        echo '  <title>' . htmlspecialchars (addslashes($img['title'])) . '</title>';
        }
    
    foreach ($images as $img) {
        echo '  <gallery>' . $img['gallery_id'] . '</gallery>';
        }
 
    
    foreach ($images as $img) {
        echo '  <album>' . $img['album_id'] . '</album>';
        }

    
    foreach ($images as $img) {
        echo '  <pictures>' . $img['pictures'] . '</pictures>';
        }
            
    foreach ($images as $img) {
        echo '  <first_paid_page>' . $img['first_paid_page'] . '</first_paid_page>';
        }

            
    foreach ($images as $img) {
        echo '  <description>' . htmlspecialchars (addslashes($img['description'])) . '</description>';
        }
            
    foreach ($images as $img) {
		        if ($img['parent'] == '1') {
        		 echo '  <author>' . 'You on Sequility.com!' . '</author>';
        		} else {
        		 echo '  <author>' . htmlspecialchars (addslashes($img['name'])) . '</author>';
						}
        
        }
            
    echo '  <mysql>OK</mysql>'; 
                                   
    echo '  <total>' . $total . '</total>';
    if (DEBUG) log_trace('total',$total);
  
} 

function load_author_albums($user_id,$first,$last,$cat_id) {
    /* remember that private albums still show up in the carousel but
    when selected we askj for a token
    */
    global $db_link;
 
    $admin_level = get_admin_level($user_id);
    // get author name
    $query = "SELECT * FROM ".DB_PREFIX."gallery
                WHERE  gallery_id = '$user_id' LIMIT 1 ";
 
    $result = $db_link->query($query); 
    $author = $result->row['name'];     
    //
    $max = $last - $first + 1;
    $first--;
    $total = 0;
    $morequery =  " AND al.pictures > 0 ";
    
    if ($admin_level != ADMIN_LEVEL) {
        $morequery .= " AND (al.date_released <= NOW() OR 
                        al.date_released = 0 OR 
                        al.gallery_id = '$user_id'  )";
        }
    
    if ($cat_id != 0) {
        $morequery .= " AND (al.category_id = '$cat_id') ";
        }
                 
    $table = DB_PREFIX.'albums ';
    $gallery_tbl = DB_PREFIX.'gallery ';
     
/* show only logged in user in this domain or in no-domain (0) */
    $query = "SELECT * FROM $table  al
                WHERE al.image != '' 
                AND ((LOCATE('/noimage/', al.image) = 0) 
                AND al.gallery_id = '$user_id'
                $morequery
                AND (al.domain_owner = '0' OR al.domain_owner = '". DOMAIN_OWNER ."') 
								OR ( al.domain_owner = '1'))
                ORDER BY al.parent DESC, al.date_released DESC
                LIMIT $first,$max ";
 
    if (DEBUG) log_trace('load_author_albums',$query);
       
    $result = $db_link->query($query);  
    //if (DEBUG) echo "<load_promo>$query</load_promo>";    

    if (DEBUG) log_trace('load_author_albums result',$result->num_rows);

    if ($result->num_rows == 0) {
    
        if ($first != 1) echo '  <mysql>EOF</mysql>';
            
        echo '  <total>' . $total . '</total>';
        if (DEBUG) log_trace('total',$total);     
        return 0;
        }

    if ($admin_level == ADMIN_LEVEL) {
        $img_dir = HTTP_THUMB;
        } else {
        $img_dir = HTTP_GALLERIA_THUMB;
        }
            
    $images = $result->rows;
						
    foreach ($images as $img) {
        echo '  <image>' . 
            $img_dir . '/'. 
            $img['image'] . 
            '</image>';
        $total++;
        }
    
    foreach ($images as $img) {
        //$time_ago = time_ago($img['date_released']) . ' ago';
        $time_ago = $img['date_released'];
						if ($img['parent'] == '1') {
        		  echo '  <time_ago>' . date('Y-m-d H:i:s') . '</time_ago>';
        		} else {
        	 		echo '  <time_ago>' . $time_ago . '</time_ago>';
        		}
	       }
     
		 foreach ($images as $img) {
        echo '  <title>' . htmlspecialchars (addslashes($img['title'])) . '</title>';
        }

    foreach ($images as $img) {
        echo '  <gallery>' . $img['gallery_id'] . '</gallery>';
        }
 

    foreach ($images as $img) {
        echo '  <album>' . $img['album_id'] . '</album>';
        }

    foreach ($images as $img) {
        echo '  <pictures>' . $img['pictures'] . '</pictures>';
        }
      
    foreach ($images as $img) {
        echo '  <first_paid_page>' . $img['first_paid_page'] . '</first_paid_page>';
        }

      
    foreach ($images as $img) {
        echo '  <description>' . htmlspecialchars (addslashes($img['description'])) . '</description>';
        }
      
    foreach ($images as $img) {
						if ($img['parent'] == '1') {
        		 echo '  <author>' . 'You on Sequility.com!' . '</author>';
        		} else {
        		 echo '  <author>' . htmlspecialchars (addslashes($author)) . '</author>';
        		}
		    }
    
    echo '  <mysql>OK</mysql>'; 
                    
    echo '  <total>' . $total . '</total>';
    if (DEBUG) log_trace('total',$total);
    
} 

function get_admin_level($user_id) {
    
    if ($user_id == '') return 0;
    
    global $db_link;
        
    $query = "SELECT * FROM ".DB_PREFIX."user WHERE emb_user_id = '$user_id' ";
    $result = $db_link->query($query);
    $admin_level = $result->row['admin_level'];
    
    return $admin_level;
}

function update_likes($album,$mode) {
    
    global $db_link;
    
    if ($mode != 'like' && $mode != 'unlike') return;
    if ($mode == 'like') {
        $likes = 1;
        } else {
        $likes = -1;
        }
    
    $query = "UPDATE " . DB_PREFIX. "albums 
                    SET likes=likes + $likes
                    WHERE album_id = $album ";
                 
    $result = $db_link->query($query);                     
}

function update_thumb($src) {
    
    global $db_link;
    
    if ($src == '') {
        echo "<status>nok</status>";
        return 0;
        }
    
    $tmpfile = DIR_GALLERIA.'/tmp/'.$src;  
      
    if (!file_exists($tmpfile))  {
 
        echo "<status>nok</status>";
        return 0;
        }
 
    // get entry from pictures table
    $sortorder = get_pageno($src);

    // insert sortorder (page no) to file name as: page_ALBUM_SORTORDER_timestamp.jpg
    $sx = explode('_',$src);
    // we could replace sx[2] with a fix value or nothing to make retrieval easier
    $sz = explode('.' , $sx[2]);
    
    $src = $sx[0].'_'.$sx[1].'_'.$sortorder.'.'.$sz[1];
            
    $cropfile = DIR_GALLERIA.'/crop/'.$src; 
        
    echo "<foo>$cropfile</foo>";
    //copy tmp to crop
    @copy($tmpfile,$cropfile);
         
    //unlink tmp
    @unlink($tmpfile);    
}

function get_pageno($image) {
    
    global $db_link;
        
    $query = "SELECT * FROM  " . DB_PREFIX. "pictures  WHERE `image`= '$image' ";
    if (DEBUG) log_trace('get_pageno: ',$query); 
    
    $result = $db_link->query($query); 
  
    if ($result->num_rows != 0) {
        return $result->row['sortorder']; 
        }
        
    return 0;    
}


    
function shrink_jpeg($jpegfile,$quality=80) {
 
    $size = filesize($jpegfile); 
    //if (DEBUG) log_trace('shrink_jpeg',"$jpegfile -- $size"); 
    
    if ($size > 10000) {
        $info = getimagesize($jpegfile);
        $image = imagecreatefromjpeg($jpegfile);
        imagejpeg($image,$jpegfile, $quality);
        }
    }
     
function crop_image($src,$x,$y,$w,$h,$targ_w,$targ_h) {
    
    global $db_link;
    
    if ($src == '') {
        echo "<status>nok</status>";
        return 0;
        }
    
    $target = DIR_GALLERIA.'/large/'.$src;  
      
    if (!file_exists($target))  {
   
        echo "<status>nok</status>";
        return 0;
        }    
    //$targ_w = $targ_h = 150;
    $jpeg_quality = 90; 
 
    $img_r = imagecreatefromjpeg($target);
    $dst_r = ImageCreateTrueColor( $targ_w, $targ_h );

    imagecopyresampled($dst_r,$img_r,0,0,$x,$y, $targ_w,$targ_h,$w,$h);

//header('Content-type: image/jpeg');
    $output_filename = str_replace("large",'tmp', $target);
    imagejpeg($dst_r, $output_filename, $jpeg_quality);
 
    $cropname = '/'.GALLERIA.'/tmp/'.$src;
    echo "<cropname>$cropname</cropname>";
    
    // unless we click the crop btn, file stays in tmp
      
}

function reorderPages($user_id,$itemidz,$mapbefore,$mapafter) {
    
    global $db_link;
    
    echo "<newlist>$itemidz-$mapbefore-$mapafter</newlist>"; 
 
    $sections = explode("|", $itemidz);  
    $c_panel = $sections[0];
    $album_id = $sections[1];
    // check if $user_id owns album_id
 
    $query = "SELECT * FROM " . DB_PREFIX."albums WHERE album_id=$album_id ";
    
    if (DEBUG) log_trace('reorderPages',$query);
     
    $result = $db_link->query($query); 

    if ($result->num_rows == 0) {
        echo "<status>nok</status>";
        return 0;
        }
        
    $album = $result->row; 
          
    $gallery_id = $album['gallery_id'];
    if ($gallery_id != $user_id) {
        echo "<status>nok</status>";
        return 0;
        }
                        
    $before = explode("|", $mapbefore);  
    
    $after = explode("|", $mapafter);  
    if (count($before) != count($after)) {
        echo "<status>nok</status>";
        return 0;
        }

                 
    $status = '';
    // update status
    $i = 0;
    foreach ($before as $page) {
     
        $sx = explode(',',$after[$i]);
        
        $picture_id = $sx[0];
         
        $query = "UPDATE " . DB_PREFIX. "pictures SET sortorder=$page 
                    WHERE picture_id = $picture_id ";
                 
        $result = $db_link->query($query);  
        $status .= "$picture_id,$page|";
        $i++;
        }  
        
    echo "<status>$status</status>";    
}

function release_now($pic_id) {

    
    global $db_link;
    
    if ($pic_id == '0') return;
 
    $query = "UPDATE " . DB_PREFIX. "pictures 
                    SET date_released=NOW()
                    WHERE picture_id = $pic_id ";
                 
    $result = $db_link->query($query);  
     
    return $db_link->countAffected();
}
////////////////////////////////////////////////////////////////////////////////

function get_product_stock($prod_id,$id,$user_id,$zip) { }
    
function buyNow($user_id,$id) { }

function savePledge($data) { }

function get_next_sequence() { }
        
function confirmPledge($mode,$user_id,$pledge_id) { } 

function prep_confirm_payment($mode,$pledge_id,$user_id,$pledge,$merchantData,$storedata) { }

function get_pp_pay_key($params) { }

function parseAPIResponse($response){ }

function requestTokenString($params){ }

function prep_coupon($pledge_id,$user_id,$pledge,$merchantData,$storedata) { }

function getPrevCat($zip,$cat_id) { }
    
function getCatTaxo($zip,$cat_id) { }
 
function get_product_details($product_id,$id,$user_id) { }

function search_categories($first,$last,$search_level,$all_zips,$cat_id,$radius,$q_text) { }

function set_ads($cat_id,$zip,$index) { }

function get_ad_link($cat_id,$zip,$generic=true) { }

function get_store_ads($cat_id,$zip) { }

function search_products($first,$last,$cat_id,$zip,$q_text) { }

function count_promo_prods($zip,$cat_id) { }


function populate_promo_prod($zip,$cat_id) { }
         
function load_promo_prods($first,$last,$zip,$cat_id,$keywords) { } 


function populate_product($prod_id,$zip,$cat_id) { }

function get_county_zips($zip,$county) { }
  
function get_zips_in_radius($zip,$radius,$search_level) { }
function distance($lat1, $lon1, $lat2, $lon2, $unit='M') { }

function getLatLong($zip) { }
 
function populate_promo_cat($zip,$q_text) { }

function all_keywords_found($q_text) { } 

function search_pledges($origin,$first,$last,$search_level,$zip,$all_zips,$cat_id,$manager_id) { }
function load_active_pins($first,$last,$zip,$zip_list,$search_level,$cat_id,$manager_id) { } 

function load_related_pins($pledge_id,$first,$last,$user_id=0) { }

function load_expired_pins($first,$last,$zip,$zip_list,$search_level,$cat_id,$manager_id) { } 

function find_level_count($zip) { }

function count_promo_cats($zip,$search_level,$cat_id) { }

function load_promo_cats_text($first,$last,$zip,$search_level,$cat_id,$q_text) { }
         
function load_promo_cats($first,$last,$zip,$search_level,$cat_id) { } 

function prep_xml_reply($images,$zip) { }
 
 
///////////////////////////// general fx /////////////////////////////////////////

function prep_breadcrumbs($mode,$zip,$search_level,$cat_id) {
    // the breadcrumbs ID in index.php is now used
    // to display the title for what is shown rather than breadcrumbs
    // so this fx is not needed anymore
    
    return;
    
    global $db_link;
        
    //if (DEBUG) log_trace('prep breadcrumbs level cat mode',"$search_level -- $cat_id -- $mode");
 
    $breadcrumbs = array();
 
    $table = DB_PREFIX.'products_zip';
    
    if ($mode == 'prods') $search_level++;
 
    $table = DB_PREFIX.'categories_zip';
            
    $cat = $cat_id; 
    
    while ($search_level > 0){
        $search_level--;    
        $query = "SELECT * FROM $table  
                    WHERE level = $search_level 
                        AND category_id=$cat
                        AND zip = '$zip'  
                        LIMIT 1";
        //if (DEBUG) log_trace('prep breadcrumbs',$query);
        
        $result = $db_link->query($query); 
        $row = $result->row;
        $cat = $row['parent']; 
        $title = $row['title'];
        $cat_id = $row['category_id'];
        //if (DEBUG) log_trace('prep breadcrumbs breadcrumbs',print_r($row,true));
        $level = $search_level + 1;
        $html = "<a href='?mode=cats&cat_id=$cat_id&search_level=$level&zip=$zip' >$title</a>";
        
        $breadcrumbs[] = htmlspecialchars ($html);
    
        }    

        
    //if (DEBUG) log_trace('prep breadcrumbs breadcrumbs',print_r($breadcrumbs,true));
  
    if (count($breadcrumbs) == 0) {
        $breadcrumbs = (string) '{ORIGIN}';
        } else {
        $breadcrumbs = (string) $origin.'{ORIGIN} -> ' .implode(" -> ",array_reverse($breadcrumbs));
        }

    return $breadcrumbs; 
}

function uploadCurl($parray,$rs) {

    $cobj = curl_init();

    //set the url, number of POST vars, POST data
    curl_setopt($cobj, CURLOPT_HEADER, 0);
    curl_setopt($cobj, CURLOPT_VERBOSE, 0);
    curl_setopt($cobj, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    curl_setopt($cobj,CURLOPT_URL,$rs);
    curl_setopt($cobj,CURLOPT_POST,true);
    curl_setopt($cobj,CURLOPT_POSTFIELDS,$parray);
    curl_setopt($cobj,CURLOPT_RETURNTRANSFER,true);

    //execute post
    $response = curl_exec($cobj);
    $httpCode = curl_getinfo($cobj, CURLINFO_HTTP_CODE);
 
    if($httpCode == 404) {
        return '404$^$';
        }	
	$error = curl_errno ($cobj);

	curl_close($cobj);
	
	return $error.'$^$'.$response; 
 
}

function secureCurl($parray,$rs,$timeout=0) {
	
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
 
    if (DEBUG) log_trace('secureCurl',"Accessing spider.php: $uri");
    
	//cUrl
	$cobj = curl_init();
  
    curl_setopt($cobj, CURLOPT_URL, $uri);
    curl_setopt($cobj, CURLOPT_POST,count($parray));
	curl_setopt($cobj, CURLOPT_POSTFIELDS,$qs);
 
    curl_setopt($cobj, CURLOPT_FRESH_CONNECT, true);
    curl_setopt($cobj, CURLOPT_RETURNTRANSFER, true);
    
    if ($timeout != 0) curl_setopt($cobj, CURLOPT_TIMEOUT,$timeout);
    //security
    curl_setopt($cobj, CURLOPT_SSL_VERIFYPEER, false);
    // to set up correct SSL, visit: http://unitstep.net/blog/2009/05/05/using-curl-in-php-to-access-https-ssltls-protected-sites/

    //curl_setopt($cobj, CURLOPT_SSL_VERIFYHOST, 2);
    //curl_setopt($cobj, CURLOPT_CAINFO, getcwd() . "/CAcerts/BuiltinObjectToken-EquifaxSecureCA.crt");
	
    $response = curl_exec($cobj);

   
    $httpCode = curl_getinfo($cobj, CURLINFO_HTTP_CODE);
    
    if (DEBUG) echo "<curl>$httpCode</curl>"; 
    if($httpCode == 404) {
        return '404$^$';
        }	
	$error = curl_errno ($cobj);

	curl_close($cobj);
	
	return $error.'$^$'.$response;        
}
 

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

function log_trace($location,$data,$append=true) {
    
    if (! $append) {
        file_put_contents(LOGFILE,"location: $data");
        return;
        }
    if (LOGLEVEL == 0 && FORCELOG == 0) return;
 
    file_put_contents(LOGFILE,"$location: $data\n",FILE_APPEND);
}

function unjson($object,$tok) {

    $result = array();
    foreach ($object as $key=>$val) {
     
        if (substr($val,0,1) == '{' && substr($val,strlen($val)-1 ,1) == '}') {

            $result[$key] = json_decode ($val,true);
            $flat = implode(",$tok",$result[$key]);
            $result[$key] = $flat;
            } else {
           
            $result[$key] = $val;
            } 
             
       
        }
        
    return $result;
} 

// Recursive directory remove (like an O/S insensitive "rm -rf dirname")
function obliterate_dir($dir) {
  if (!file_exists($dir)) return true;
  if (!is_dir($dir) || is_link($dir)) return unlink($dir);
  foreach (scandir($dir) as $item) {
    if ($item == '.' || $item == '..') continue;
    if (!obliterate_dir($dir . DIRECTORY_SEPARATOR . $item)) {
      chmod($dir . DIRECTORY_SEPARATOR . $item, 0777);
      if (!obliterate_dir($dir . DIRECTORY_SEPARATOR . $item)) return false;
    };
  }
  return rmdir($dir);
}

function time_ago($timestamp) {

    $sx = explode(' ',$timestamp);
    $date = $sx[0];
    $time = $sx[1];
    
    $days = abs(ceil((strtotime($date)-strtotime("now"))/86400));
    if ($days > 0)  $timepast = $days." days";
    if ($days == 1)  $timepast = $days." day";
 
    $hours = abs(ceil((strtotime($time)-strtotime("now"))/3600));
    if ($days == 0) $timepast = "about ".$hours." hours";
    if ($hours == 1) $timepast = "about ".$hours." hour";  
     
    $minutes = abs(ceil((strtotime($time)-strtotime("now"))/60))-($hours*60);
    if ($hours == 0) $timepast = $minutes." minutes";
    if ($minutes == 1) $timepast = $minutes." minute";
 
    return $timepast;
} 
