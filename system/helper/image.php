<?php
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
 
function image_resize($filename, $width, $height,$folder,$quality=90) {
    // always from folder 'large' to $folder
//echo DIR_GALLERIA."/".$filename.'--'."$width, $height";  
    if (!file_exists(DIR_GALLERIA."/".$filename)) {
        return;
    } 
 ini_set('memory_limit', '256M');
    $old_image = $filename;
    $pathinfo = pathinfo($filename);


    if ($pathinfo['dirname'] == $folder) return;
//print_array($pathinfo); 
    //$new_image =  substr($filename, 0, strrpos($filename, '.')) . '.'.$pathinfo['extension'];
    $new_image =  $folder.'/'. $pathinfo['basename'];
 
    if (!file_exists(DIR_GALLERIA."/".$new_image) || 
                    (filemtime(DIR_GALLERIA."/".$old_image) > filemtime(DIR_GALLERIA."/".$new_image))) {
        $tn_image = new Image(DIR_GALLERIA."/".$old_image);
        
        $tn_image->resize($width, $height);
 
        $tn_image->save(DIR_GALLERIA."/".$new_image,$quality);
    }
//echo  DIR_GALLERIA."/".$new_image;       
    return HTTP_GALLERIA."/".$new_image;
}

function removeimage($filename) {
    
    //@unlink(DIR_ALBUMS."/".$filename);
    @unlink(DIR_GALLERIA. '/thumb/' . $filename);
    @unlink(DIR_GALLERIA. '/large/' . $filename);
}

// changed default from admin to user so filename start always with user_1 (admin)
function saveimage($image,$header='user',$gallery_id=1,$album_id=0) {
     
//echo $header.'--'.$gallery_id.'--'.$album_id;   
    if (is_uploaded_file($image['tmp_name']) && 
        is_writable(DIR_GALLERIA.'/large') && 
        is_writable(DIR_GALLERIA.'/thumb')) {

        $as_of = time();
   
        switch ($header) {
            case 'album': $id = $gallery_id.'_'; break;
            case 'page': $id = $album_id.'_'; break;
            $id = '';
            }
            
        //if (! file_exists(DIR_ALBUMS.'/'.$folder_large)) mkdir(DIR_GALLERIA.'/'.$folder_large,0755,true); 
        //if (! file_exists(DIR_ALBUMS.'/'.$folder_thumb)) mkdir(DIR_GALLERIA.'/'.$folder_thumb,0755,true); 
 
        $filename =   "large/".$header.'_'.$id.$as_of; //MK added

        $pathinfo = pathinfo(DIR_GALLERIA."/".$image['name']);     //MK
        //$pathinfo = pathinfo(DIR_IMAGE."/".$image['name']);
        
        $filename = $filename . '.'.$pathinfo['extension'];
        $filename = strtolower($filename);
//echo $filename;   
        move_uploaded_file($image['tmp_name'],DIR_GALLERIA."/".$filename);

        if (file_exists(DIR_GALLERIA."/".$filename)) {
            return $filename;
        }
    }
}
 
?>