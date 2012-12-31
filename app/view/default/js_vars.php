<?php
// cookies free

    // this is inside a javascript tag
    define ('GMAPKEY_PIN2SHOP',"http://maps.googleapis.com/maps/api/js?key=AIzaSyBOPG-26taOXkWGLMuMRZuqdQ_6DoiR5yU&sensor=false");
    //define paypal URL
    define ('PAYPAL_TEST_SERVER',"https://www.sandbox.paypal.com/cgi-bin/webscr");
    define ('PAYPAL_SERVER',"https://www.paypal.com/cgi-bin/webscr");
    
    define('CAROUSEL_SLATE',4);
    //define('FB_APP_ID','326738482574');
    //define('FB_APP_SECRET','17d6a64ff37def59928fd650ee0763da');
 
    // debug is set in index.php and work with the admin setup for log_errors
 //print_array($_COOKIE ); print_array( $this->session->data);
 //if ($_SERVER['QUERY_STRING'] != 'debug=inactive') exit;;    
    if (DEBUG) {
        $debug = 'true';
        } else {
        $debug = 'false';
        }
 
    define ('VERBOSE',$debug);
    
    echo ' <script type="text/javascript" language="javascript">';  
    
    echo "var verbose = ". VERBOSE .";  \n";   
    echo "var max_tiles = ". CAROUSEL_SLATE .";  \n";  
    echo "var site_title = '". SITE_TITLE ."';  \n";   

    // set to true will open the debug console too
        
    //echo ' var google_adSense = "' . $google_adSense. '"; ' . "\n";
    //echo ' var google_ad_client = "' . GOOGLE_ADSENSE_ID. '"; ' . "\n";
    //echo ' var google_ad_slot = "' . GOOGLE_ADSENSE_SLOT. '"; ' . "\n";
 
    //echo ' var HTTP_REFERER  =  "' . $_SESSION['HTTP_REFERER'] . '"; ' . "\n";    
    //echo ' var APP_URL  =  "' . $_SESSION['APP_URL']  . '"; ' . "\n";  
    
    //echo ' var WEBHOST  =  "' . $_SESSION['WEBHOST'] . '"; ' . "\n";    
    //echo ' var MAIN_DIR  =  "' . $_SESSION['MAIN_DIR'] . '"; ' . "\n";  
     
    //echo ' var eMediaSESSIONID = "' . $_SESSION['sid'] . '"; ' . "\n"; 
     
    //echo ' var language = "' . $_SESSION['language'] . '";' . "\n"; 
    //echo ' var country = "' . $_SESSION['country'] . '";' . "\n"; 
         
    //echo ' var paypalCol = "' . PAYPALCOL . '"; ' . "\n"; 
    // echo ' var trackLocation = false; ' . "\n"; 
        
    echo ' var googleMap = false; ' . "\n"; 
    echo ' var googleMapKey = "' . GMAPKEY_PIN2SHOP . '";' . "\n"; 
    //echo ' var GOOGLE_SITENAME = "' . $_SESSION['GOOGLE_SITENAME'] . '";' . "\n"; 
   
    echo ' var paypalTestServer = "' . PAYPAL_TEST_SERVER . '";' . "\n"; 
    echo ' var paypalServer = "' . PAYPAL_SERVER . '";' . "\n"; 
     
    //echo ' var EMB_payPal_result = "' . $_SESSION['EMB_payPal_result'] . '"; ' . "\n"; 

    //echo ' var googleCalendarID = "' . $_SESSION['googleCalendarID'] . '"; ' . "\n"; 
 
     echo '</script>';  

?>
