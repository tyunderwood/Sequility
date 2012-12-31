<?php 
// Error Reporting
error_reporting(E_ALL);

// Check Version
if (version_compare(phpversion(), '5.1.0', '<') == TRUE) {
	exit('PHP5.1+ Required');
}

// Register Globals
if (ini_get('register_globals')) {
	ini_set('session.use_cookies', '1');
	ini_set('session.use_trans_sid', '0');
		
	session_set_cookie_params(0, '/');
	session_start();
	
	$globals = array($_REQUEST, $_SESSION, $_SERVER, $_FILES);

	foreach ($globals as $global) {
		foreach(array_keys($global) as $key) {
			unset($$key);
		}
	}
	
	ini_set('register_globals', 'Off');
}

// Magic Quotes Fix
if (ini_get('magic_quotes_gpc')) {
	function clean($data) {
   		if (is_array($data)) {
  			foreach ($data as $key => $value) {
    			$data[$key] = clean($value);
  			}
		} else {
  			$data = stripslashes($data);
		}
	
		return $data;
	}			
	
	$_GET = clean($_GET);
	$_POST = clean($_POST);
	$_COOKIE = clean($_COOKIE);
	
	ini_set('magic_quotes_gpc', 'Off');
}

// Set default time zone if not set in php.ini
if (!ini_get('date.timezone')) {
	@date_default_timezone_set(date('e', $_SERVER['REQUEST_TIME']));
}

/* Auto Load Function */ 
function __autoload($class_name) {
	/* Changing Classname to Lower Case*/ 
	$class_name = strtolower($class_name);
	
	/* Check the class is in Engin Directory
		or Else Check Library Direcotory*/ 
	if(is_file(DIR_SYSTEM . 'engine/'.$class_name.'.php')) {		
		require_once DIR_SYSTEM . 'engine/'.$class_name.'.php';		
		
	} else if(is_file(DIR_SYSTEM . 'libs/'.$class_name.'.php')){		
		require_once DIR_SYSTEM . 'libs/'.$class_name.'.php';		
	}	
}