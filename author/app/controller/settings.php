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

class ControllerSettings extends Controller {
 
    function __construct() {
        $this->db = Registry::get('db');
        $this->id = 'content';
        $this->layout   = 'layout';
        $this->table   = DB_PREFIX.'setting';
        $this->template = $this->config->get('config_template') . 'settings.php';
        $this->load->model('settings');
        
        $this->admin_gallery = 1;
        $this->managers_gallery = 1;       
    }    
   
    function index() {
        //print_array($this->session->data); 
 
        $this->admin_level = $this->session->data['admin_level'];
        $this->emb_user_id = $this->session->data['emb_user_id'];
        
        if ($this->admin_level != ADMIN_LEVEL &&
            $this->admin_level != AUTHOR_LEVEL) {                         
            $location = "/admin/index.php?c=pictures&album_id=".
                        $this->managers_gallery.
                        "&gallery_id=".
                        $this->admin_gallery;
            header("Location: $location");
            }
                
        if ($this->admin_level == ADMIN_LEVEL) {
            $this->data['fvalue'] = $this->model_settings->getSettings();
            } else {
            $this->data['fvalue'] = $this->model_settings->getAuthorSettings($this->emb_user_id);
            }
//print_array($this->data);            
        $this->data['yn_dropdown'] = array( 'config_error_log',
                                            'config_error_display',
                                            'config_seo_url',
                                            'config_show_cats',
                                            'config_show_left_menu',
                                            'header_expand_btn'); 
        $this->render();
   
    }        
    
    function save() {

        $this->emb_user_id = $this->session->data['emb_user_id'];
        $this->admin_level = $this->session->data['admin_level'];
                    
        $fvalue = $this->request->post['fvalue'];
        if (is_array($fvalue)) {
            //print_array($fvalue); 
            //print_array($this->request->files);    

            // check help files
            foreach ($this->request->files as $key=>$val) {
 
                if ($val['name'] == '') continue;
 
                $xkey = str_replace('f__','',$key);
                //echo '<br>key='.$key.' val='.$val;
                  
                if (strpos($key,'help_filename') !== false) {
 
                    $this->save_help($val);  
                    $fvalue[$xkey] = $val['name'];  
                    } elseif ($key == 'f__about_filename') {
                    $this->save_about($val);  
                    $fvalue[$xkey] = $val['name'];  
          
                    } elseif ($key == 'f__news_filename') {
                    $this->save_news($val);  
                    $fvalue[$xkey] = $val['name'];  
          
                    } elseif ($key == 'f__config_background_image' ||
                              $key == 'f__config_logo' ||
                              $key == 'f__config_welcome_image') {
                        $this->save_picture($val);
                        $fvalue[$xkey] = $val['name'];
                    } elseif ($key == 'f__config_icon') {
                        $this->save_icon($val);
                        $fvalue[$xkey] = $val['name'];
                    }   

                }
            //
//print_array($fvalue); exit;
                
            if ($this->admin_level == ADMIN_LEVEL) {
                $this->model_settings->saveSettings($fvalue);
                } else {
                $this->model_settings->saveAuthorSettings($this->emb_user_id,$fvalue);
                }
          
            $fvalue['msg'] = "One or Several Mandatory Fields ar Missing";
            $this->index();
        }    
    
    } 
    
    function save_icon($file) {
    
        $targetDir = DIR_IMAGE;
        //if (DOMAIN_OWNER !== 0) $targetDir .= '/'.DOMAIN_OWNER;
        @mkdir($targetDir,0755,true);        
        
        if (is_uploaded_file($file['tmp_name']) && 
            $file['type'] == 'image/x-icon' &&
            $file['size'] != 0 &&
            $file['error'] == 0 &&
                is_writable($targetDir) ) {
                move_uploaded_file($file['tmp_name'],$targetDir."/".$file['name']);
                }
    }
    
    function save_picture($file) {
    
        $targetDir = DIR_IMAGE;
        //if (DOMAIN_OWNER !== 0) $targetDir .= '/'.DOMAIN_OWNER;
        @mkdir($targetDir,0755,true);        
        
        if (is_uploaded_file($file['tmp_name']) && 
            $file['type'] == 'image/png'&&  // for transparency
            $file['size'] != 0 &&
            $file['error'] == 0 &&
                is_writable($targetDir) ) {
                move_uploaded_file($file['tmp_name'],$targetDir."/".$file['name']);
                }
    }
    
    function save_help($file) {
        
        $targetDir = DIR_SYSTEM.'config';
        if (DOMAIN_OWNER !== 0) $targetDir .= '/'.DOMAIN_OWNER;
        @mkdir($targetDir,0755,true);
        //print_array($file);
        //echo  $targetDir."/".$file['name'].'--'.DOMAIN_OWNER; exit;
       
        if (is_uploaded_file($file['tmp_name']) && 
            $file['type'] == 'text/xml' &&
            $file['size'] != 0 &&
            $file['error'] == 0 &&
                is_writable($targetDir) ) {
                move_uploaded_file($file['tmp_name'],$targetDir."/".$file['name']);
                }
    
    }   
    
    
    function save_about($file) {
        
        $targetDir = DIR_SYSTEM.'config';
        if (DOMAIN_OWNER !== 0) $targetDir .= '/'.DOMAIN_OWNER;
        @mkdir($targetDir,0755,true);
        //print_array($file);
        //echo  $targetDir."/".$file['name'].'--'.DOMAIN_OWNER; exit;
       
        if (is_uploaded_file($file['tmp_name']) && 
            $file['type'] == 'text/xml' &&
            $file['size'] != 0 &&
            $file['error'] == 0 &&
                is_writable($targetDir) ) {
                move_uploaded_file($file['tmp_name'],$targetDir."/".$file['name']);
                }
    
    }
    
    function save_news($file) {
        
        $targetDir = DIR_SYSTEM.'config';
        if (DOMAIN_OWNER !== 0) $targetDir .= '/'.DOMAIN_OWNER;
        @mkdir($targetDir,0755,true);
        //print_array($file);
        //echo  $targetDir."/".$file['name'].'--'.DOMAIN_OWNER; exit;
       
        if (is_uploaded_file($file['tmp_name']) && 
            $file['type'] == 'text/xml' &&
            $file['size'] != 0 &&
            $file['error'] == 0 &&
                is_writable($targetDir) ) {
                move_uploaded_file($file['tmp_name'],$targetDir."/".$file['name']);
                }
    
    }    
          
}
 
