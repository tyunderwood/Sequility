<?php
/* general functions called by several controllers, not using models or other functions
*/
    
function prep_discounts($lines,$discounts,$store_price=0) {

    $from = $to = $pct = 0;
    foreach ($lines as $val) {
        $key = key($val);
        $curval = current($val);
    
        $sx = explode("_",$key); 
        $lineno = (int) $sx[1] + 1;        
        switch ($sx[0]) {
            case 'f': $from = $curval; break;
            case 't': $to = $curval; break;
            case 'p': $pct = $curval; break;
            }
        if ($from != 0 && $to != 0 && $pct != 0) {
            $px = (int) $store_price;
            $pc = (int) $pct;
            $newpx = $px - (($px * $pc) /100);
            
            $discounts[$lineno] = array('lineno'=>$lineno,'from'=>$from,'to'=>$to,
                                        'pct'=>$pct,'newpx'=>$newpx);
            if (count($discounts) >= 5) break;
                    
            $from = $to = $pct = 0;
            }       
        } 
    return $discounts;
}


function unjson($object,$newline=',<br/>') {

    $result = array();
    foreach ($object as $key=>$val) {
     
        if (substr($val,0,1) == '{' && substr($val,strlen($val)-1 ,1) == '}') {

            $result[$key] = json_decode ($val,true);
            $flat = implode($newline,$result[$key]);
            $result[$key] = $flat;
            } else {
           
            $result[$key] = $val;
            } 
             
       
        }
        
    return $result;
}

    
function has_alpha_channel($logo) {
        $readPng =    fopen    ($logo, "rb");
        $readAlp =    fread    ($readPng, 52);
        fclose    ($readPng);

        if(substr(bin2hex($readAlp),50,2) == "04" || substr(bin2hex($readAlp),50,2) == "06") {
            return true;
            }
            
        return false;
}
    
function convert_to_jpg($input_file) {
 
        $tmp = DIR_IMAGE."/tmp";
        if (! file_exists($tmp)) mkdir($tmp); 
        // never remove it as it's shared between users   ! 
 
        list($width, $height, $type) = getimagesize($input_file);
           
	    switch ($type) {
		  case 2:
			//$type = "jpeg";
			return $input_file;
			break;
		  case 3:
			//$type = "png";
			$input = imagecreatefrompng($input_file);
			$output_file = str_replace(".png",".jpg",$input_file);
			break;
		  case 1:
			//$type = "gif";
			$input = imagecreatefromgif($input_file);
			$output_file = str_replace(".gif",".jpg",$input_file);
			break;
		  default:
		      return '';
	       }

        $output = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($output,  255, 255, 255);
        imagefilledrectangle($output, 0, 0, $width, $height, $white);
        imagecopy($output, $input, 0, 0, 0, 0, $width, $height);
        
        $output_file = md5($output_file);
        $output_file = "$tmp/$output_file.jpg";
        imagejpeg($output, $output_file);
       
        return $output_file;
}
 