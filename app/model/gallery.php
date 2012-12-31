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


class ModelGallery extends Model {

    function __construct() {
        $this->load->helper('gallery');
        $this->helper = new HelperGallery();
    }

    /*
        Gallery Details using getGallery function
    */
    function galleryDetails() {
        $details = $this->getGallerys();
        $return = array();
        foreach($details as $key=>$value) {
        
            if ($value['gallery_id'] < FIRST_ALBUM_ID) continue;
            
            $return[$key] = $value;
            if(!$value['image']) {
                $value['image'] = $this->helper->getImage($value['gallery_id']);
            }
            if ($value['image'] === ADMIN_SILHOUETTE) {
                //$http = HTTP_THUMB;
                //$url = "javascript:void(0);";
                $http = HTTP_HOST.'/'.ASSET_PICTURES;
                $value['image'] = ADMIN_SILHOUETTE;
                $url = 'index.php?c=albums&gallery_id='.$value['gallery_id'].'&title='.$value['name'];
                } else {
                $http = HTTP_GALLERIA_THUMB;
                $url = 'index.php?c=albums&gallery_id='.$value['gallery_id'].'&title='.$value['name'];
                }
            $album_image = $http . '/'.$value['image']; //MK  
                       
            $return[$key]['image'] = $album_image;  //HTTP_THUMB.'/'.$value['image'];
          
            $return[$key]['link'] = $url;   //$this->seourl->rewrite($url);            
        }
        return $return;
    }

    /*
        Retrieve the Gallery List
    */    
    function getGallerys() {
            return $this->helper->getGallerys();
    }
    
    
    function createGallery($fvalue) {            
          
            $result = $this->db->query("SELECT MAX(sortorder) as max FROM ".DB_PREFIX."gallery WHERE type = 6");
            $max = $result->row['max'] + 1; 
           
            $type = $fvalue['type'];
                     
            $endString = " albums=0,type='$type' ";
                       
            $beginString = "INSERT INTO ".DB_PREFIX."gallery set gallery_id='".$this->db->escape($fvalue['gallery_id'])."' ";        //MK
  
            $image = ADMIN_SILHOUETTE;  
            
            $imgCon = " image='$image'";
            
            $name = $fvalue['name'];
            
            $query = $beginString .", name='$name', $imgCon ,sortorder='$max', $endString";
                     
            $result = $this->db->query($query);
           
    }   
   
    function getAuthorFromAlbum($album_id) {
            
        $result = $this->db->query("SELECT * FROM ".DB_PREFIX."albums al
                join ".DB_PREFIX."user user on (al.gallery_id=user.emb_user_id) 
                WHERE album_id = '$album_id' LIMIT 1");
    
        return $result->row;
    } 
    
    function getAlbumsCat() {

        $domain_owner=DOMAIN_OWNER; 
        $query = "SELECT * FROM ".DB_PREFIX."categories ";
     
        $query .= " WHERE ( domain_owner = '0' OR  domain_owner = '$domain_owner') ";
 
        $query .= " ORDER BY title ";
// echo $query; exit;       
        $result = $this->db->query($query);
        
        return $result->rows;    
    
    }

    
    function getDomainAlbums($domain_owner,$min_width=0) {
 
        $query = "SELECT *,pic.image as image FROM ".DB_PREFIX."albums al ";
 
        $query .= " JOIN ".DB_PREFIX."pictures pic  on (al.album_id=pic.album_id) ";
 
        $query .= " WHERE (al.date_released <= NOW() OR al.date_released = 0) 
                        AND al.album_id >= ". FIRST_ALBUM_ID.
                        " AND (al.first_paid_page = 0 OR pic.sortorder < al.first_paid_page) ";
        if ($min_width != 0) $query .= " AND pic.width_large >= $min_width ";
                        
        $query .= " AND (pic.date_released <= NOW() OR pic.date_released = 0) ";
                        
        if ($domain_owner != 0) $query .= " AND al.domain_owner = '$domain_owner' ";
        
        $query .= "GROUP BY pic.album_id ";
        $query .= " ORDER BY al.likes LIMIT 100 "; 
 //echo $query; exit;       
        $result = $this->db->query($query);
        
        return $result->rows;    
    
    }       
}
