<?php
/**
***************************************************************************************************
 * @Software    AjaxMint Gallery
 * @Author      Rajapandian - arajapandi@gmail.com
 * @Copyright    Copyright (c) 2010-2011. All Rights Reserved.
 * @License        GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://ajaxmint.com. All Rights Reserved.
 **************************************************************************************************
**/

final class HelperGallery {
    private $db;
      public function __construct() {
        $this->db = Registry::get('db');       
        
      }
    
    /*
        Retrieve the Gallery List
    */    
    public function getGallerys() {
            $result = $this->db->query("SELECT * FROM ".DB_PREFIX."gallery order by sortorder");
            return $result->rows;
    }    
    
    /*
        Getting the Gallery Main Image from album or Pictures.
    */
    private function getImage($gallery_id) {
        if($gallery_id) {
            $result = $this->db->query('
                    select 
                     if(
                      (image),(image),
                      (
                       select pic.image 
                       from '.DB_PREFIX.'pictures as pic 
                       join '.DB_PREFIX.'albums as al on (al.album_id=pic.album_id) 
                       where
                       al.gallery_id='.$this->db->escape($gallery_id).' order by al.sortorder,pic.sortorder ASC limit 1
                      )
                      ) as image 
                    from '.DB_PREFIX.'albums 
                    where gallery_id='.$this->db->escape($gallery_id).'
                    ');    
            if(!$result->row['image']) 
                return 'noimage.jpg';
                
            return $result->row['image'];
        }
    }
    
    public function getCityStateCounty($zip) {
            $result = $this->db->query("SELECT * FROM ".DB_PREFIX."zip_codes b
                                         WHERE zip_code = '$zip' ");
            return array('city'=>$result->row['city'],
                         'state'=>$result->row['state'],
                         'county'=>$result->row['county']);    
    
    }
}