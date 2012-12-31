<?php
final class SeoUrl {
	public $enable_url;
	public function rewrite($link) {
 
		if($this->enable_url == 1) {
			$url_data = parse_url(str_replace('&amp;', '&', $link));
		
			$url = ''; 
				
			$data = array();
			
			parse_str($url_data['query'], $data);

            $data = $this->organize_query($data);
				
			foreach ($data as $key => $value) {
				
				switch($key) {
 				    
					case GET_CONTROLLER:
					    $url .= '/'.$value;		
					    unset($data[GET_CONTROLLER]);
                        break;
					case 'gallery_id':
					    $url .= '/gl/'.$this->getValue($value);
					    unset($data['gallery_id']);						
					    break;
					case 'album_id':
					    $url .= '/al/'.$this->getValue($value);
                        unset($data['album_id']);
					    break;
					case 'picture_id':
				 	    $url .= '/pic/'.$this->getValue($value);	
					    unset($data['picture_id']);		
	                    break;
                    case 'page':
					    $url .= '/page/'.$value;		
					    unset($data['page']);
                        break;
                    case 'title':
					    $url .= '/'.$this->getValue($value);			
					    unset($data['title']);
					    break;

					case 'products':
				 	    $url .= '/products';	
					    unset($data['products']);		
	                    break;	
 				
                    case 'cat_id':
					    $url .= '/cat/'.$this->getValue($value);			
					    unset($data['cat_id']);
					    break; 
/* 					    
                    case 'startat':
                    // is it needed... and if so, should be startat lower case?
					    $url .= '/startat/'.$this->getValue($value);			
					    unset($data['startat']);
					    break;
*/                                                                   
                    default:
 
                        break;                    
                    }
	
				}
	
				if ($url) {
					unset($data[GET_CONTROLLER]);
 			
					$query = '';
				
					if ($data) {
						foreach ($data as $key => $value) {
							$query .= '&' . $key . '=' . $value;
						}
						
						if ($query) {
							$query = '?' . str_replace('&amp;', '&', trim($query, '&'));
						}
					}
					
					return HTTP_SERVER . $url . $query;
				} else {
					return HTTP_SERVER . "/index.php?" . $link;
				}	
			} else {
				return HTTP_SERVER . "/index.php?" . $link;			
			}		
	}
	
	function getValue($text) {

        return $text;

	
		if($text)
			return $this->safetext($text);
		else 
			return $this->safetext("Simple Gallery");
	}
	function safetext($title){

		$title = str_replace("&", "and", $title);		
		$arrStupid = array('feat.', 'feat', '.com', '(tm)', ' ', '*', "'s",  '"', ",", ":", ";", "@", "#", "(", ")", "?", "!", "_",
							 "$","+", "=", "|", "'", '/', "~", "`s", "`", "\\", "^", "[","]","{", "}", "<", ">", "%", "™");

		$title = htmlentities($title);
  		$title = preg_replace('/&([a-zA-Z])(.*?);/','$1',$title);
		$title = strtolower("$title");
		$title = str_replace(".", "", $title);
		$title = str_replace($arrStupid, "-", $title);
		$flag = 1;
			while($flag){ 
  			  $newtitle = str_replace("--","-",$title);
				if($title != $newtitle) { 
					$flag = 1;
				 }
				else $flag = 0;
 			  $title = $newtitle;
			}
		$len = strlen($title);
		if($title[$len-1] == "-") {
			$title = substr($title, 0, $len-1);
		}
		return $title;
    }

    function organize_query($data) {
    
        reset($data);
        $newdata = array();
        if (array_key_exists ('c',$data)) $newdata['c'] = $data['c'];
        if (array_key_exists ('album_id',$data)) $newdata['album_id'] = $data['album_id'];
        if (array_key_exists ('picture_id',$data)) $newdata['picture_id'] = $data['picture_id'];
        if (array_key_exists ('cat_id',$data)) $newdata['cat_id'] = $data['cat_id'];
        if (array_key_exists ('zip',$data)) $newdata['zip'] = $data['zip'];
        reset($data);
        
        $protected = array('c','album_id','picture_id','cat_id','zip');
        foreach ($data as $key=>$val) {
            if (in_array($key,$protected)) continue;
            $newdata[$key] = $val;
            }
        
        reset($newdata);
        return $newdata; 
    }
}