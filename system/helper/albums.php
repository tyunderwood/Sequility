<?php
/**
***************************************************************************************************
 * @Software    AjaxMint Gallery
 * @Author      Rajapandian - arajapandi@gmail.com
 * @Copyright   Copyright (c) 2010-2011. All Rights Reserved.
 * @License     GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://ajaxmint.com. All Rights Reserved.
 **************************************************************************************************
**/

final class HelperAlbums {

    private $db;
    
      public function __construct() {
        $this->db = Registry::get('db');
      }
    
    /*
        Retrieve the Gallery List
    */    
    function getAlbums($gallery_id,$start=0,$limit=12,$order='sortorder') {
	
            /* if galler_id is there, will execute*/
			$gallerycon='';
            if((int)$gallery_id) 
                    $gallerycon = " WHERE al.gallery_id='".$this->db->escape($gallery_id)."' ";

            $limitString = '';
            if($limit)
                $limitString = "LIMIT $start,$limit";
 
            $q = "SELECT *,al.image as album_image FROM ".DB_PREFIX."albums AS al
                    join ".DB_PREFIX."gallery AS gl ON (gl.gallery_id=al.gallery_id)		
                    $gallerycon
                    ORDER BY al.$order
                    $limitString ";
                                                                                
 
            $result = $this->db->query($q);                                        
            return $result->rows;                    
    }
    
    /*
        Getting the Gallery Main Image from album or Pictures.
    */
    function getImage($album_id) {
        if($album_id) {
            $result = $this->db->query('
                       SELECT pic.image AS image
                       FROM '.DB_PREFIX.'pictures AS pic 
                       WHERE
                       pic.album_id='.$this->db->escape($album_id).' 
                       ORDER BY pic.sortorder ASC LIMIT 1
                    ');    
                    
            if(!$result->row['image']) 
                return 'noimage.jpg';
                
            return $result->row['image'];
        }
    }    
    
    /* Get the total number of albums*/
    function totalAlbums($gallery_id) {
        /* if galler_id is there, will execute*/
		    $gallerycon='';
         if((int)$gallery_id) 
            $gallerycon = " WHERE al.gallery_id='".$this->db->escape($gallery_id)."' ";	

        $result = $this->db->query("SELECT
                                        count(al.album_id) as total
                                        FROM
                                        ".DB_PREFIX."albums AS al 
                                        $gallerycon
                                        ");
        return $result->row['total'];
    }
}