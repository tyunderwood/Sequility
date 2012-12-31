<?php
final class Router {
	protected $class;
	protected $method;
	protected $args = array();

	public function __construct($route, $args = array()) {
		$path = '';
 	
		if(count($parts = explode('/', str_replace('../', '', $route))) <= 1) {
			$parts = explode('_',$route);
		}
		array_filter($parts);

		foreach ($parts as $part) { 
			$path .= $part;
						
			if (is_dir(DIR_APPLICATION . 'app/controller/' . $path)) {
			
				$path .= '/';
				
				array_shift($parts);
				
				continue;
			}
			if (is_file(DIR_APPLICATION . 'app/controller/' . $path . '.php')) {
			
                $this->class = $path;
				array_shift($parts);
				
				break;
			}
			
			if ($args) {
				$this->args = $args;
			}
		}

		$method = array_shift($parts);
			
		if ($method) {
			$this->method = $method;
		} else {
			$this->method = 'index';
		}
	}
	
	public function getClass() {
		return $this->class;
	}
	
	public function getMethod() {
		return $this->method;
	}
	
	public function getArgs() {
		return $this->args;
	}
}
?>