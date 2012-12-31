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

class ModelAlbums extends Model {

    function __construct() {
        $this->load->helper('albums');
        $this->helper = new HelperAlbums();
    }
        
    function albumsDetails($gallery_id,$start=0,$limit=12) {
   
        $details = $this->helper->getAlbums($gallery_id,$start,$limit);
        $return = array();
        foreach($details as $key=>$value) {
            $return[$key] = $value;
            if(!$value['album_image']) {
                $value['album_image'] = $this->helper->getImage($value['album_id']);
            }
 
            $album_image = HTTP_GALLERIA_THUMB.'/'. $value['album_image']; //MK         
            $return[$key]['image'] = $album_image; //HTTP_THUMB.'/'.$value['album_image'];
            
            $return[$key]['link'] = $this->seourl->rewrite('index.php?c=pictures&album_id='.$value['album_id'].'&title='.$value['title']);            
        }
        return $return;
    }
    
    function galleryNavLink($gallery_id) {
        $result = $this->db->query("SELECT
                                        gl.gallery_id,
                                        gl.name as gallery
                                        FROM
                                        ".DB_PREFIX."gallery AS gl 
                                        WHERE
                                        gl.gallery_id='".$this->db->escape($gallery_id)."'
                                        limit 1");
            
        $result_row = $result->row;
        $return = array();
        $return[1]['title']=$result_row['gallery'];

        return $return;
    }
        
    function totalAlbums($gallery_id) {
        return $this->helper->totalAlbums($gallery_id);
    }    
}