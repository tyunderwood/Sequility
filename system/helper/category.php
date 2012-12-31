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

final class HelperCategory {
    private $db;
      public function __construct() {
        $this->db = Registry::get('db');       
        
      }
    
    /*
        Retrieve the Category List
    */    
    public function getCategories() {
            $result = $this->db->query("SELECT * FROM ".DB_PREFIX."categories 
                                        WHERE ( domain_owner = 0 OR domain_owner='".DOMAIN_OWNER."' )
                                        order by title");
            return $result->rows;
    }    
 
}