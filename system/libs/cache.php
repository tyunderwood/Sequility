<?php
final class Cache { 
	private $expire = 3600; 
	private $thisdir = 'cache';	

  	public function __construct() {
		$files = glob(DIR_SYSTEM."/".$this->thisdir."/" . 'cache.*');
    	
		if ($files) {
			foreach ($files as $file) {
				$time = substr(strrchr($file, '.'), 1);

      			if ($time < time()) {
					unlink($file);
      			}
    		}
		}
  	}

	public function get($key) {
		$files = glob(DIR_SYSTEM."/".$this->thisdir."/" . 'cache.' . $key . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			$handle = fopen($file, 'r');
      			$cache = fread($handle, filesize($file));
	  
      			fclose($handle);

	      		return unserialize($cache);
   		 	}
		}
  	}

  	public function set($key, $value) {
    	$this->delete($key);
		
		$file = DIR_SYSTEM."/".$this->thisdir."/" . 'cache.' . $key . '.' . (time() + $this->expire);
    	
		$handle = fopen($file, 'w');

    	fwrite($handle, serialize($value));
		
    	fclose($handle);
  	}
	
  	public function delete($key) {
		$files = glob(DIR_SYSTEM."/".$this->thisdir."/" . 'cache.' . $key . '.*');
		
		if ($files) {
    		foreach ($files as $file) {
      			unlink($file);
    		}
		}
  	}
}