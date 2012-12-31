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

final class HelperUser {
    
      private $user_id;
      private $username;
      private $permission = array();

      public function __construct() {
        $this->db = Registry::get('db');
        $this->request = Registry::get('request');
        $this->session = Registry::get('session');
    
        if (isset($this->session->data['user_id'])) {
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . 
                        "user WHERE user_id='" . (int)$this->db->escape($this->session->data['user_id']) .
                         "'");
            
            if ($user_query->num_rows) {
                $this->user_id = $user_query->row['user_id'];
                $this->username = $user_query->row['username'];
                $this->nickname = $user_query->row['nickname'];
         
                $this->twitter_token = $user_query->row['twitter_access_token'];
                
                $this->store_id = $user_query->row['store_id'];
                $this->user_zip = $user_query->row['zip'];
 
                $this->db->query("UPDATE " . DB_PREFIX . "user SET ip='" . 
                            $this->db->escape($this->request->server['REMOTE_ADDR']) . 
                            "',last_login=NOW() WHERE user_id = '" . 
                            (int)$this->session->data['user_id'] . "'");

            } else {
                $this->logout();
            }
        }
      }
      
      public function register($username, $password,$nickname,$fb_id='') {
      
        $this->db->query("INSERT INTO " . DB_PREFIX . 
                            "user SET username = '" . $this->db->escape($username) . 
                            "', password = '" . md5($this->db->escape($password)) . 
                            "', nickname =  '" . $this->db->escape($nickname) . 
                            "', status = 1 ");
                            
        //MK
        $this->user_id = $this->db->getLastId(); 
        
        $q = "UPDATE " . DB_PREFIX . "user SET emb_user_id='" . $this->user_id . "' ";
                            
        if ($fb_id != '') $q .= ", fb_id = '$fb_id' ";
                                    
        $q .= " WHERE user_id = '" . $this->user_id . "'";

                            
        $this->db->query($q);
                  
        return $this->user_id;
      }
      
      public function isRegistered($username) {
      
            $user_query = $this->db->query("SELECT * FROM " . DB_PREFIX . 
                    "user WHERE username='" . $this->db->escape($username) . "'  ");

            if (!$user_query->num_rows) return false;
            
            return true;      
      }
       
      public function login($username, $password,$fb=false) {
        
        $q = "SELECT * FROM " . DB_PREFIX . "user WHERE username='" . 
                        $this->db->escape($username) .  "' ";
             
        if (!$fb) $q .= " AND password = '" . md5($this->db->escape($password)) . "'";
                        
        $user_query = $this->db->query($q);
           
        if ($user_query->num_rows) {
            // don't forget to unset in logout
            $this->session->data['user_id'] = $user_query->row['user_id'];
            
            $this->session->data['emb_user_id'] = $user_query->row['emb_user_id'];
            
            $this->session->data['manager_store_id'] = $user_query->row['store_id'];
            $this->session->data['admin_level'] = $user_query->row['admin_level'];
            
            $this->session->data['twitter_token'] = $user_query->row['twitter_access_token'];
            
            $this->user_id = $user_query->row['user_id'];
            $this->username = $user_query->row['username'];
            $this->nickname = $user_query->row['nickname'];
            
            // name is for private messages which we don't use now
            $this->twitter_name = $user_query->row['twitter_screen_name'];
            
            $this->twitter_token = $user_query->row['twitter_access_token'];
            $this->store_id = $user_query->row['store_id'];
                
            $this->user_zip = $user_query->row['zip'];
                
            if ($this->store_id != 0) {
                $store_data = $this->db->query("SELECT * FROM " . DB_PREFIX . 
                        "stores WHERE store_id='".$this->store_id."' ");
                $this->store_zip = $store_data->row['zip'];
                } else {
                $this->store_zip = 0;
                }
                               
              return true;
        } else {
 
            return false;
        }
      }

      public function logout() {
        unset($this->session->data['user_id']); 
        unset($this->session->data['emb_user_id']); 
        
        unset($this->session->data['manager_store_id']); 
        unset($this->session->data['admin_level']); 
        unset($this->session->data['twitter_token']); 
        
        $this->user_id = '';
        $this->username = '';
        $this->nickname = '';
      }

      public function hasPermission() {
        if ($this->user_id) {
              return TRUE;
        } else {
              return FALSE;
        }
      }
  
      public function isLogged() {
        return $this->session->data['user_id']; // changed from $this->user_id = '';
      }
  
      public function getId() {
        return $this->user_id;
      }
    
      public function getUserName() {
        return $this->username;
      }    
     
      public function getUserZip() {
        return $this->user_zip;
      } 
          
      public function getStoreZip() {
        return $this->store_zip;
      } 
          
      public function getToken($user_id) {
        $row = $this->db->query("SELECT username,password FROM " . DB_PREFIX . 
                        "user WHERE user_id='$user_id'");
                       
        $username = $row->row['username'];
        $password = $row->row['password'];
                        
        $token = md5("$username.$password");
        // used when a token
        return $token;
      }  
            
      public function getNickName() {
        return $this->nickname;
      }
      
      public function getStoreID() {
        return $this->store_id;
      } 
      
      public function getTwitterToken() {
        return $this->twitter_token;
      }               
}
?>
