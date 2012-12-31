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

class ModelSettings extends Model {
    
    function getAuthorSettings($user_id) { 
     
        $result = $this->db->query("SELECT * FROM ".
                        $this->table."_author WHERE user_id = '$user_id' ORDER BY notes ");
        
        if ($result->num_rows == 0) {
            $result->rows = $this->createAuthorSettings($user_id);    
            } else {
            // check if any new settings in template
            
            $new_result = $this->appendAuthorSettings($user_id,$result);
            if ($new_result !== false) $result = $new_result;
            }            
        
        return $result->rows;
    }    
    
    private function appendAuthorSettings($user_id,$result) {
    
        $result_0 = $this->db->query("SELECT * FROM ".
                    $this->table."_author WHERE user_id = '0' ");
 //echo $result_0->num_rows.'--'.$result->num_rows; exit;           
        if ($result->num_rows == $result_0->num_rows) return false;
        
        // we have new rows... we don't deal with the case when we have less rows
        $templ_settings = array();
        foreach ($result_0->rows as $fvalue) {
            $templ_settings[ $fvalue['flag'] ] = $fvalue;
            }
            
        $current_settings = array();
        foreach ($result->rows as $fvalue) {
            $current_settings[ $fvalue['flag'] ] = $fvalue;
            }          
 
        foreach ($templ_settings as $key=>$val) {
       
            if (array_key_exists($key,$current_settings)) continue;
             
            $this->addAuthorSettings($user_id,$val);
            
            }              
            
        $result = $this->writeAuthorSettings($user_id);
        
        return $result;
                
    }
    
    private function createAuthorSettings($user_id) {
    
        $author_template_settings = $this->getAuthorSettings(0);
        
        foreach ($author_template_settings as $fvalue) {
            $this->addAuthorSettings($user_id,$fvalue);

            }
 
        $this->writeAuthorSettings($user_id);
        
        $result = $this->db->query("SELECT * FROM ".
                        $this->table."_author WHERE user_id = '$user_id'  ORDER BY notes ");
 
        return $result->rows;     
 
    }
    
    function addAuthorSettings($user_id,$fvalue) {
 
            $query = " insert into ".$this->table."_author SET `value`='".$fvalue['value']."', 
                                `flag`='".$fvalue['flag']."',user_id = '$user_id',
                                `notes`= '".$this->db->escape($fvalue['notes'])."' ";
                                
            $result = $this->db->query($query);

    
    }
    
    function writeAuthorSettings($user_id) {
    
        $result = $this->db->query("SELECT * FROM  
                    ".$this->table."_author WHERE user_id='$user_id'  ORDER BY notes ");
                    
        $content = "<?php \n";                    
        foreach($result->rows as $key=>$value) {
            $content .= '$author_settings["'.$value['flag'].'"]  = "'.
                html_entity_decode($value['value']).
                '"; '."\n";
            if ($value['flag'] == 'config_site_url') $hostname = $value['value'];    
        }
     
        if (! $hostname) return false;
        
        $content .= '$author_settings[\'domain_owner\']  = "'. $user_id. '"; '."\n";
                
        $hostname = str_replace('http://','',$hostname);
        $hostname = strtolower($hostname);
         
        @mkdir("../settings/$hostname",0755,true);
        
        $fp = fopen("../settings/$hostname/settings.php", 'w');
        fwrite($fp, $content);        
        fclose($fp);
        
        return $result;
        
    }        

    
    function saveAuthorSettings($user_id,$fvalue) {
            foreach($fvalue as $key=>$value) {
                $update = " UPDATE ".$this->table."_author SET value='$value' 
                            WHERE `flag`='$key' AND user_id='$user_id' ";
                $result = $this->db->query($update);
            }
            $this->writeAuthorSettings($user_id);
 
    }
    
    function getSettings() {    
        $result = $this->db->query("SELECT * FROM 
                    ".$this->table." ORDER BY notes ");
        return $result->rows;
    }    
    
    function saveSettings($fvalue) {
            foreach($fvalue as $key=>$value) {
                $update = " UPDATE ".$this->table." SET value='$value' WHERE `flag`='$key'; ";
                $result = $this->db->query($update);
            }
            $this->writeSettings();

    
    }
    
    function writeSettings() {
    
        $result = $this->db->query("SELECT * FROM 
                    ".$this->table." ");
                    
        $content = "<?php \n";                    
        foreach($result->rows as $key=>$value) {
            $content .= '$settings["'.$value['flag'].'"]  = "'.
                            html_entity_decode($value['value']).'"; '."\n";
                
        }

        $fp = fopen('../settings.php', 'w');
        fwrite($fp, $content);        
        fclose($fp);
    }
}
