<?php
/**
***************************************************************************************************
 * @Software    emediaPublishing loosely based on ajaxMint, a PHP MVC framework by ajapandian - arajapandi@gmail.com
 * @Author      Michel Kohon 
 * @Copyright	Copyright (c) 2012. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE  
 * This license covers all scripts, PHP, javascript, css, html, etc... called directly or indirectly from this root 
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2012 http://emediacart.com. All Rights Reserved.
 **************************************************************************************************
**/


class ModelCategory extends Model {
    
    function __construct() {
        $this->load->helper('category');
        $this->gl_helper = new HelperCategory();

        $this->all_category = 0;
    }
        
    function categoryDropdown($sel='') {
 
        $this->load->helper('helpers');
        $this->commonhelpers = new HelperHelpers();
        
        $query = "SELECT * FROM ".DB_PREFIX."categories ";
        $query .= " WHERE domain_owner = '".DOMAIN_OWNER."' OR domain_owner = '0' ";
 
        $sel = $this->category_id;
      
        $query .= " order by domain_owner DESC ";
        
        $result = $this->db->query($query);
        return $this->commonhelpers->dropdown($result->rows,$sel);

    } 
    function getCategories() {            
            return $this->gl_helper->getCategories();            
    }

    function getCategory($category_id) {
			if(!(int)$category_id)
				return false;
				
	        $result = $this->db->query("SELECT * FROM ".$this->table." where category_id='".$this->db->escape($category_id)."'");
            return $result->row;                    
    }
        
    function deleteCategory($category_id){
            return $this->db->query("delete FROM ".$this->table." where category_id='".$this->db->escape($category_id)."'");
    }
    
    function saveCategory($fvalue) {            
 
            $oldRow = $this->getCategory($fvalue['category_id']); 
            
            if ($oldRow) {
                $albums = $oldRow['albums'];
                $domain_owner = $oldRow['domain_owner'];
                $image = $oldRow['image'];
                $newRow = false;
                } else {
                $albums = 0;
                $newRow = true;
                if ($this->session->data['admin_level'] == ADMIN_LEVEL) {
                    $domain_owner = 0;
                    } else {
                    $domain_owner = DOMAIN_OWNER;
                    }
                $image = GENERIC_COVER;
                }
         
            $endString = ",albums='$albums',domain_owner='$domain_owner'  ";
            
            // REPLACE works exactly like INSERT, except that if an old row in the table has the same value as a new row for a PRIMARY KEY or a UNIQUE index, the old row is deleted before the new row is inserted          
            $beginString = " REPLACE ".$this->table." set category_id='".$this->db->escape($fvalue['category_id'])."' ,";        //MK
  
            if ($fvalue['image'] && $fvalue['image'] != '.') {
                $imgCon = ", image='".$this->db->escape($fvalue['image'])."'";
                } else {
                $imgCon = ", image='$image' ";
                }
                
            $result = $this->db->query($beginString." title='". 
                                        $this->db->escape($fvalue['title']). 
                                        "' $imgCon
                                        ".$endString
                                        );
                                        
            if ($newRow) $fvalue['category_id'] = $this->db->getLastId();                
            return $fvalue;
    }
}
