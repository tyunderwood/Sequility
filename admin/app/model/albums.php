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

class ModelAlbums extends Model {

    function __construct() {
        $this->load->helper('albums');
        $this->al_helper = new HelperAlbums();
        $this->merchant_album = 3;  // not used now
        $this->store_album = 4;
     
    }
    
    function getAlbums($gallery_id,$offset,$per_page) {
        return $this->al_helper->getAlbums($gallery_id,$offset,$per_page);
    }
    
    function totalAlbums($gallery_id) {
        return $this->al_helper->totalAlbums($gallery_id);
    }

    function albumsDropdown($gallery_id='',$sel_album_id='') {
 
        $this->load->helper('helpers');
        $this->commonhelpers = new HelperHelpers();
        
        $query = "SELECT album_id,title FROM ".DB_PREFIX."albums 
                        WHERE gallery_id='".$this->db->escape($gallery_id)."' ";
        
        if ($sel_album_id != '') {
            $query .= " AND album_id = '$sel_album_id' LIMIT 1";    
            } else {
            $query .= " ORDER BY title";
            }
                                         
        $result = $this->db->query($query);
               
        return $this->commonhelpers->dropdown($result->rows,$sel_album_id);

    } 
    
    function order($orderAr){
        $record_start = $this->request->post['record_start'];
        foreach($orderAr as $key=>$val) {
            $this->db->query("update
                    ".$this->table." 
                    set sortorder='".($record_start+$key+1)."' 
                    where album_id='".$this->db->escape($val)."'");
        }    
    }

    function getGallery($gallery_id) {
        $result = $this->db->query("SELECT * FROM ".DB_PREFIX."gallery where gallery_id='".$this->db->escape($gallery_id)."'");
        return $result->row;
    }
    
    function getAlbum($album_id) {
        $result = $this->db->query("SELECT * FROM ".$this->table." where album_id='".$this->db->escape($album_id)."'");    
        return $result->row;
    }
    
    function deleteAlbum($album_id){ 
        $this->load->model('pictures');
        $result = $this->db->query("SELECT * FROM ".DB_PREFIX."pictures where album_id='".$this->db->escape($album_id)."'");

        if(is_array($result->rows)) {
            $this->load->helper('image');
            foreach($result->rows as $val) {
                $this->model_pictures->table=DB_PREFIX."pictures";
                $this->model_pictures->deletePicture($val['picture_id']);
                $this->table=DB_PREFIX."albums";
            }
        }
        return $this->db->query("delete FROM ".$this->table." where album_id='".$this->db->escape($album_id)."'");                            
    } 

    function saveAlbums($fvalue) {
    
        if($fvalue['sortorder'])$fvalue['sortorder']=0;

        if($fvalue['album_id']) {
            $album_id = $fvalue['album_id'];
            $beginString = " update ".$this->table." set ";
            $endString = " where    album_id='".$this->db->escape($fvalue['album_id'])."' ";
        }else {
            $album_id = 0;
            $beginString = "insert into ".$this->table." set ";
        }    
        
        $imgCon = '';
        if ($fvalue['image'] && $fvalue['image'] != '.') {
            $imgCon .= ", image='".$this->db->escape($fvalue['image'])."'";
            } else {
            if ($fvalue['old_image'] == '') {
                $imgCon .= ", image='".GENERIC_COVER."'"; 
                } else {
                $imgCon .= ", image='".$this->db->escape($fvalue['old_image'])."'";
                }
            }
        
        if ( $fvalue['date_released'] ) {
 
            $date_released = strtotime( $this->db->escape($fvalue['date_released']) );
            
            if ($date_released != '' && 
                $date_released != '0') {
                $imgCon .= ", date_released= FROM_UNIXTIME ($date_released) ";
                } else {
                $imgCon .= ", date_released=NOW() ";
                }            
            
            } else {
            $imgCon .= ", date_released=NOW() ";
            }
 
        if ( $fvalue['domain_owner'] != 0) {
            $imgCon .= ",domain_owner='". DOMAIN_OWNER."' ";
            } else {
            $imgCon .= ",domain_owner='0' ";
            }
            
        $query = $beginString." 
                    title='".$fvalue['title']."'
                    ,sortorder='".$fvalue['sortorder']."'
                    ,first_paid_page='".$fvalue['first_paid_page']."'
                    ,description='".$fvalue['description']."'
                    $imgCon 
                    ,gallery_id='".$this->db->escape($fvalue['gallery_id'])."' ".
                    ", date_created=NOW() ".
                    ",category_id='".$this->db->escape($fvalue['category_id'])."' ".
                    $endString;
                    
//print_array($fvalue); echo $query; exit;
        $result = $this->db->query($query);  
        
        if ($album_id == 0) $fvalue['album_id'] = $this->db->getLastId();  
 
        $query = "update ".DB_PREFIX."gallery 
                        set albums=(select count(album_id)
                        from 
                        ".$this->table."
                        where gallery_id='".$this->db->escape($fvalue['gallery_id'])."'
                        )
                        where gallery_id='".$this->db->escape($fvalue['gallery_id'])."'";
//echo $query; exit;                               
        $this->db->query($query);

        return $fvalue;
    }

}