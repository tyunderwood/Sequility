<?php
/**
***************************************************************************************************
 * @Software    Pin2Shop
 * @Author      Michel Kohon - michelk18@gmail.com
 * @Copyright   Copyright (c) 2012-. All Rights Reserved.
 * @License     GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://emediaboard.com. All Rights Reserved.
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
                $query = "UPDATE ".$this->table." SET
                        password='".md5($fvalue['password'])."',
                        username='".$fvalue['username']."'
                        WHERE user_id='".$fvalue['user_id']."'";
                     
                $result = $this->db->query($query);    
            }
            return $fvalue;
    }

    function updateUserZip($user_id,$zip) {
 
        $query = "UPDATE ".$this->table." SET
                    zip='$zip' 
                    WHERE user_id=$user_id ";
                     
        $result = $this->db->query($query);    
 
    }

    function updateAdminLevel($user_id,$level,$nickname) {
 
        $query = "UPDATE ".$this->table." SET
                    admin_level='$level' ,nickname='$nickname'
                    WHERE user_id=$user_id ";
                     
        $result = $this->db->query($query);    
 
    }
        
    function setTwitterToken($user_id,$token) {
 
            if ($user_id != 0) {
                $result = $this->db->query("UPDATE ".$this->table." SET
                        twitter_access_token='$token'
                        WHERE            
                        user_id='$user_id'"
                        );    
            }
   
    }
}
