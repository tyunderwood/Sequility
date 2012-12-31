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

final class HelperProducts {

	private $db;
	
  	function __construct() {
		$this->db = Registry::get('db');
  	}
    
	final function query_picture($from,$album_id,$gallery_id,$start=0,$limit=12) {

        $where = array(); 
        if((int)$album_id)
             $where[] = " pic.album_id='".$this->db->escape($album_id)."' ";					 

        if((int)$gallery_id) 
             $where[] = " gl.gallery_id='".$this->db->escape($gallery_id)."' ";
			 
        /* implode the where condition with AND keyword and add WHERE condition before*/
        if($where_cond = implode(" AND ",$where))
		    $where_cond = " WHERE ".$where_cond;

        $limitString = '';

        if($limit)
            $limitString = "LIMIT $start,$limit";		
		
		$result = $this->db->query("SELECT $from
                       FROM 
                       ".DB_PREFIX."pictures pic 
                       left outer join ".DB_PREFIX."albums al ON (al.album_id=pic.album_id)
                       left outer join ".DB_PREFIX."gallery gl ON (gl.gallery_id=al.gallery_id)	
                       $pictureCon
                       $where_cond
                       ORDER BY pic.sortorder
                       $limitString
                       ");
        return $result;
	
    }
    function getPictures($album_id,$start=0,$limit=12,$gallery_id='') {                
        $result = $this->query_picture(' pic.*,al.title as album_title '
                                    ,$album_id
                                    ,$gallery_id
                                    ,$start
                                    ,$limit
                                    );
        return $result->rows;
    }
    
    function totalPictures($album_id,$gallery_id='') {
        $result = $this->query_picture(' count(pic.picture_id) as total ',$album_id,$gallery_id);
        return $result->row['total'];
    }    
}