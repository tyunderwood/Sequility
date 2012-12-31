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

class Modelusers extends Model {

    function getUser($user_id) {
            $result = $this->db->query("SELECT * FROM ".$this->table." where user_id='".$user_id."'");
            return $result->row;                    
    }
    
    function saveUser($fvalue) {
            $con='';
            if(!$fvalue['user_id'])exit;
            if($fvalue['user_id']) {
                $result = $this->db->query("update ".$this->table." set
                        password='".md5($fvalue['password'])."',
                        username='".$fvalue['username']."'
                        where            
                        user_id='".$fvalue['user_id']."'"
                        );    
            }
            return $fvalue;
    }

}
