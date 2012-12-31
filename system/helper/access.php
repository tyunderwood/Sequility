<?php
/** * @License		GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2012- Michel Kohon. All Rights Reserved.
 **************************************************************************************************
**/

final class HelperAccess {

	private $db;
 
  	function __construct() {
		$this->db = Registry::get('db');

        $this->session = Registry::get('session');
         
        $this->user_id = $this->session->data['emb_user_id'];
        if (! $this->user_id) $this->user_id = 0;
        
        $this->admin_level = $this->session->data['admin_level'];
        if (! $this->admin_level) $this->admin_level = 0;     
 	
  	}
    
    final function check_private($album_id) {
    
        $query = "SELECT * FROM ".DB_PREFIX."albums_access WHERE album_id='".
                    $this->db->escape($album_id)."' 
                    AND user_id=0 and `read`=0 
                    limit 1"; 
                                                  
        $result = $this->db->query($query);   
        $result_row = $result->row; 
        if (! $result_row) return false;
        return true;         
    }
    
    final function check_public($album_id) {
//if ($album_id != 6) return true;  
        $query = "SELECT * FROM ".DB_PREFIX."albums_access WHERE album_id='".
                    $this->db->escape($album_id)."' 
                    AND user_id=0 and (`read`=1 or `read`=-1)
                    limit 1"; 
                                                  
        $result = $this->db->query($query);
        $result_row = $result->row; 
        if (! $result_row) return false;
        return $result_row['read'];
               
    }
    
    final function set_privacy($album_id,$privacy) {
        
        $query = "SELECT * FROM ".DB_PREFIX."albums_access WHERE album_id='".
                    $this->db->escape($album_id)."' 
                    AND user_id=0  limit 1"; 
                                                  
        $result = $this->db->query($query);
        $result_row = $result->row; 
        
        if (! $result_row) {
            $query = "insert into ".DB_PREFIX."albums_access SET
                        `read`='$privacy',user_id='0',album_id='".
                    $this->db->escape($album_id)."' "; 
            } else {
            $query = "update ".DB_PREFIX."albums_access SET
                        `read`='$privacy' WHERE 
                        user_id='0' AND album_id='".
                    $this->db->escape($album_id)."' "; 
            }
        
                     
        $result = $this->db->query($query);        
    }
    
    final function check($album_id,$level) {
//if ($album_id != 6) return true;  
         
        if ($this->session->data['white_list'] == $album_id) return true;
          
        if ($this->admin_level != 0) return true;
        
        $query = "SELECT * FROM ".DB_PREFIX."albums WHERE album_id='".$this->db->escape($album_id)."' limit 1";
                                                  
        $result = $this->db->query($query);
        $result_row = $result->row;
        
        if ($result_row['gallery_id'] == $this->user_id) return true;
        
        // not an admin... not the owner... let's start checking the access table
        $query = "SELECT * FROM ".DB_PREFIX."albums_access WHERE album_id='".$this->db->escape($album_id)."'  ";
                                                  
        $result = $this->db->query($query); 
        $this->result_rows = $result->rows;
        if (! $this->result_rows) return 'no_access';       // no access at all
        
        $loggedin = false;
        if ($this->user_id != 0) $loggedin = true;
        /*
        admin: can read, add, change and delete. Must be logged in.
        add: must be logged in. Can read and add. No delete. Must be logged in.
        change: must be logged in. can read and change. No delete. Must be logged in.
        read: 0: no; 1:all; -1:logged in only;  
        */ 
        switch ($level) {
            case 'read':
                if ($loggedin) {
                    return $this->check_read_logged();
                     
                    } else {
                    return $this->check_read_guest();
                    
                    }
 
                break;
                
            case 'change':
                break;
            case 'add':
                break;
            case 'admin':
                break;               
            } 
       return 'no_access';  
    }

    final function check_read_logged() {
                    
        foreach ($this->result_rows as $row) {

            //check admin row against requested level
            if ($row['user_id'] == $this->user_id && $row['admin'] == 1) return true;
            if ($row['user_id'] == 0 && $row['admin'] == 1) return true;
            //check add row against requested level
            if ($row['user_id'] == $this->user_id && $row['add'] == 1) return true;
            if ($row['user_id'] == 0 && $row['add'] == 1) return true;        
            //check admin row against requested level
            if ($row['user_id'] == $this->user_id && $row['change'] == 1) return true;
            if ($row['user_id'] == 0 && $row['change'] == 1) return true;            
            //check read row against requested level
            if ($row['user_id'] == 0 && $row['read'] != 0) return true;
 
            if ($row['user_id'] == $this->user_id && $row['read'] != 0) return true;
                  
            } 
            
        return 'no_access'; 
    }
    

    final function check_read_guest() {
                    
        foreach ($this->result_rows as $row) {
 
            //check read row against requested level. no other access permitted when not logged in
            if ($row['user_id'] == 0 && $row['read'] == 1) return true;
             
            if ($row['user_id'] == 0 && $row['read'] == -1 ) return 'login_access';
            
            // email_access means we have a white list and user might be in it
            //if ($row['user_id'] == 0 && $row['read'] == -2 ) return 'email_access';
                     
            if ($row['user_id'] == $this->user_id && $row['read'] != 0) return true;
                     
            } 
            
        return 'token_access';     
    } 
    
    final function check_read_token($album_id,$token) {

        $query = "SELECT * FROM ".DB_PREFIX."albums_tokens 
                    WHERE album_id='".$this->db->escape($album_id)."' 
                    AND token='".$this->db->escape($token)."'  limit 1";
                                                  
        $result = $this->db->query($query);
        if (!$result->row) return 'not_white_listed';
        
        // check expiration date
        $val = $result->row;
      
        if (strtotime($val['date_expiration']) != 0) {
            if (strtotime ($val['date_expiration']) < time() ) return 'was_white_listed';
            } else {
            $week = 7 * 24 * 60 * 60;
            if (strtotime ($val['date_creation'])  + $week < time()) return 'was_white_listed';
            } 
     
        $this->session->data['white_list'] = $album_id;
        return true;
    }
    
    final function set_read_token($album_id,$token,$date_expiration) {
 
        if (! $this->check_private($album_id)) return false;
         
        $date_expiration = strtotime( $this->db->escape($date_expiration) );
        if ($date_expiration != '' && 
            $date_expiration != '0') {
            $date_expiration = " FROM_UNIXTIME ($date_expiration) ";
            } else {
            $date_expiration = ' DATE_ADD(NOW(),INTERVAL 1 WEEK) '; 
            }   
        
        // album_id + token is a unique key in table              
        $query = "replace into ".DB_PREFIX."albums_tokens SET
                        album_id='". $this->db->escape($album_id).
                        "'  , token='". $this->db->escape($token).
                        "'  ,date_added=NOW(),date_expiration= $date_expiration "; 
                        
        $this->db->query($query);
        /* signal we have a white list for this album
        $query = "replace into ".DB_PREFIX."albums_access SET
                        album_id='". $this->db->escape($album_id).
                        "'  ,  user_id='0', `read`= -2 "; 
                        
        $this->db->query($query);  
        */
        
        return true;      
    }  
}

