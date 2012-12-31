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

final class HelperPictures {

	private $db;
 
  	function __construct() {
		$this->db = Registry::get('db');

        $this->session = Registry::get('session');
         
        $this->user_id = $this->session->data['emb_user_id'];
        if (! $this->user_id) $this->user_id = 0;
        
        $this->admin_level = $this->session->data['admin_level'];
        if (! $this->admin_level) $this->admin_level = 0;     
 	
  	}
    
	final function query_picture($from,$album_id,$gallery_id,$start=0,$limit=12) {

        $user_id = $this->user_id;

        $where = array(); 
        if((int)$album_id)
             $where[] = " pic.album_id='".$this->db->escape($album_id)."' ";					 

        if((int)$gallery_id) 
            $where[] = " gl.gallery_id='".$this->db->escape($gallery_id)."' ";
 
        if ($admin_level < AUTHOR_LEVEL) {
            
            $where[] = " (pic.date_released <= NOW() 
                    OR pic.date_released = 0 OR gl.gallery_id = '$user_id'  )";
            }
 
        $where[] = " ( pic.sortorder < al.first_paid_page 
                        OR al.first_paid_page = 0 
                        OR gl.gallery_id = '$user_id' )";
 
        /* implode the where condition with AND keyword and add WHERE condition before*/
        if($where_cond = implode(" AND ",$where))
		    $where_cond = " WHERE ".$where_cond;

        $limitString = '';

        if($limit)
            $limitString = "LIMIT $start,$limit";		
   
        $query = "SELECT $from
                       FROM 
                       ".DB_PREFIX."pictures pic 
                       left outer join ".DB_PREFIX."albums al ON (al.album_id=pic.album_id)
                       left outer join ".DB_PREFIX."gallery gl ON (gl.gallery_id=al.gallery_id)	
                       $pictureCon
                       $where_cond
                       ORDER BY pic.sortorder
                       $limitString ";   
                       
 //echo  "query_picture   $query</br>";
 	
		$result = $this->db->query($query);
                       
                        
        return $result;
	
    }
    function getPictures($album_id,$start=0,$limit=12,$gallery_id='') {    
                       
//echo  "getPictures  $this->user_id -- $this->admin_level </br>";
 	                
        $result = $this->query_picture(' pic.*,al.title as album_title '
                                    ,$album_id
                                    ,$gallery_id
                                    ,$start
                                    ,$limit
                                    );
        return $result->rows;
    }
    
    function totalPictures($album_id,$gallery_id='') {
 //echo  "totalPictures  $this->album_first_paid_page </br>";

        $result = $this->query_picture(' count(pic.picture_id) as total ',$album_id,$gallery_id);
        return $result->row['total'];
    }    
}