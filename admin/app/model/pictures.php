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

class ModelPictures extends Model {

    function __construct() {
        $this->load->helper('pictures');
        $this->p_helper = new HelperPictures();	
    }
    
    function getPictures($album_id,$offset,$per_page,$gallery_id) {
        
        $result = $this->p_helper->getPictures($album_id,$offset,$per_page,$gallery_id);
 
        $store_id = $this->session->data['manager_store_id'];
 
        if ($store_id == 0) return $result;
        
        $new_result = array();
        
        foreach ($result as $res) {
            if ($res['store_id'] != $store_id) continue;
            $new_result[] = $res;
            }
                  
        return $new_result;
    }
    
    function order($orderAr){
        $record_start = $this->request->post['record_start'];
        foreach($orderAr as $key=>$val) {
            $this->db->query("update
                    ".$this->table." 
                    set sortorder='".($record_start+$key+1)."' 
                    where picture_id='".$val."'");
        }
    }

    function getPicture($picture_id) {            
        $result = $this->db->query("SELECT * FROM ".$this->table." where picture_id='".$picture_id."'");
        return $result->row;                    
    }
    
    function deletePicture($picture_id) {
            
        $result = $this->db->query("SELECT * FROM ".$this->table." where picture_id='".$picture_id."'");
        removeimage($result->row['image']);
        return $this->db->query("delete FROM ".$this->table." where picture_id='".$picture_id."'");    
    }
    
    function totalPictures($album_id,$gallery_id) {
        return $this->p_helper->totalPictures($album_id,$gallery_id);
    }

    function addManyPictures($album_id,$batch) {
 
            foreach ($batch as $fvalue) {
                       
            $query = "insert into ".DB_PREFIX."pictures "; 
            $query .= "  (title,url,category_id,store_id,zip,
                                sortorder,album_id,width_large,height_large,
                                date_updated,date_released,description,
                                image,date_added) VALUES ";
   
                $query .= "( '" . $fvalue['title'] ."' , ";
                $query .= "'" . $fvalue['url'] ."' , ";
                $query .= "'" . $fvalue['category_id'] ."' , ";
                $query .= "'" . $fvalue['store_id'] ."' , ";
                $query .= "'" . $fvalue['zip'] ."' , ";
                
                $query .= "'" . $fvalue['sortorder'] ."' , ";
                $query .= "'" . $fvalue['album_id'] ."' , ";
                $query .= "'" . $fvalue['width_large'] ."' , ";
                $query .= "'" . $fvalue['height_large'] ."' , ";
                
                $query .= "'" . $fvalue['date_updated'] ."' , ";
                $query .= "'" . $fvalue['date_released'] ."' , ";
                $query .= "'" . $fvalue['description'] ."' , ";
 
                $query .= "'" . $fvalue['image'] ."' , ";
                $query .= "'" . $fvalue['date_added']."' ) ";
 
            $result = $this->db->query($query); 
            set_time_limit (0);     
                }
            $this->updateAlbum($album_id);
    }
        
    function addManyPictures_fast($album_id,$batch) {
              
            $query = "insert into ".DB_PREFIX."pictures "; 
            $query .= "  (title,url,category_id,store_id,zip,
                                sortorder,album_id,width_large,height_large,
                                date_updated,date_released,description,
                                image,date_added) VALUES ";
     
            $values = "";
            foreach ($batch as $fvalue) {
            
                $values .= "( '" . $fvalue['title'] ."' , ";
                $values .= "'" . $fvalue['url'] ."' , ";
                $values .= "'" . $fvalue['category_id'] ."' , ";
                $values .= "'" . $fvalue['store_id'] ."' , ";
                $values .= "'" . $fvalue['zip'] ."' , ";
                
                $values .= "'" . $fvalue['sortorder'] ."' , ";
                $values .= "'" . $fvalue['album_id'] ."' , ";
                $values .= "'" . $fvalue['width_large'] ."' , ";
                $values .= "'" . $fvalue['height_large'] ."' , ";
                
                $values .= "'" . $fvalue['date_updated'] ."' , ";
                $values .= "'" . $fvalue['date_released'] ."' , ";
                $values .= "'" . $fvalue['description'] ."' , ";
 
                $values .= "'" . $fvalue['image'] ."' , ";
                $values .= "'" . $fvalue['date_added']."' ),";
                
                }
                
             
            $query .= trim($values, ',');
        
            $result = $this->db->query($query); 
 
            $this->updateAlbum($album_id);
    }
    
    function updateAlbum($album_id) {
        
        $this->db->query("update ".DB_PREFIX."albums 
                    set pictures=(select count(picture_id)
                    from 
                    ".DB_PREFIX."pictures 
                    where album_id='". $album_id ."'
                    )
                    where album_id='". $album_id ."'");      
    }
    
    function savePicture($fvalue) {            
    
        if($fvalue['picture_id']) {
            $beginString = " update ".$this->table." set ";
            $endString = " where    picture_id='".$fvalue['picture_id']."' ";
        }else {
            $beginString = "insert into ".$this->table." set";
            $endString = " ,date_added=NOW()";
        }
//print_array($fvalue);        
        if($fvalue['image'] && $fvalue['image'] != '.') $imgCon = ", image='".$fvalue['image']."'";
        
        if( $fvalue['date_released'] ) {
 
            $date_released = strtotime( $this->db->escape($fvalue['date_released']) );
            if ($date_released != '' && 
                $date_released != '0') {
                $date_released = " FROM_UNIXTIME ($date_released) ";
                } else {
                $date_released = 0; 
                }         
            
            } else {
            $date_released = 0; 
            }
            
        $result = $this->db->query($beginString." 
                title='".$fvalue['title']."',
                url='".$fvalue['url']."',    
                category_id='".$fvalue['category_id']."',
                store_id='".$fvalue['store_id']."',
                zip='".$fvalue['zip']."',
                sortorder='".$fvalue['sortorder']."', 
                album_id='".$fvalue['album_id']."',
                width_large='".$fvalue['width_large']."',
                height_large='".$fvalue['height_large']."',
                date_updated=NOW(),
                date_released=$date_released,
                description='".$fvalue['description']."'
                $imgCon "
                .$endString
        );    

        $this->db->query("update ".DB_PREFIX."albums 
                    set pictures=(select count(picture_id)
                    from 
                    ".$this->table."
                    where album_id='".$fvalue['album_id']."'
                    )
                    where album_id='".$fvalue['album_id']."'");    
        
        return $fvalue;
        
    }
}