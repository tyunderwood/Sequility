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
  
class ControllerUsers extends Controller {

    function __construct() {
        $this->db = Registry::get('db');
        $this->id = 'content';
        $this->table = DB_PREFIX.'user';
        $this->load->model('users');
        $this->layout   = 'layout';
    }    
        
    function index() {
        $this->edit();
    }
    
    function edit() {
                
        $user_id = $this->user->getId();

        if($user_id) {
            $this->data['fvalue'] = $this->model_users->getUser($user_id);
        }

        $this->template = $this->config->get('config_template') . 'users_edit.php';
        $this->render();
    }
    
    function save() {
        
        $fvalue = $this->request->post['fvalue'];
        $fvalue['user_id']=$this->user->getId();
        if(!$fvalue['username'] || !$fvalue['password'] || !$fvalue['cfmpassword']) {
             $msg = "Mandatory Fields Missing";
        } elseif(strlen($fvalue['password'])<5 || ($fvalue['cfmpassword'] !=$fvalue['password'])) {
            $msg = "Password Mismatch(Minimum 5 Char Required)";
        } else {
            $msg = "Updated Sucessfully!";
            $this->model_users->saveUser($fvalue);
        }
        $this->data['msg'] = $msg;
        $this->edit();
    }
}