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

class ModelHome extends Model {

    function getPictureCount() {
        $result = $this->db->query("SELECT count(picture_id) as count FROM 
                    ".DB_PREFIX."pictures u 
                    ");
        return $result->row['count'];
    }
    
    function getAlbumCount() {
        $result = $this->db->query("SELECT count(album_id) as count  FROM 
                    ".DB_PREFIX."albums u 
                    ");
        return $result->row['count'];
    }
    
    function getGalleryCount() {
        $result = $this->db->query("SELECT count(gallery_id) as count  FROM 
                    ".DB_PREFIX."gallery u 
                    ");
        return $result->row['count'];
    }        
}