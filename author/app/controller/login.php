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
  
class ControllerLogin extends Controller {

    function __construct() {
        $this->id = 'content';
        $this->template = $this->config->get('config_template') . 'login.php';
        $this->layout   = 'layout';
        
    }    
    
    function index() {
    
        if ($this->user->isLogged() == 1)
            $this->redirect($this->url->http('gallery'));    

        if ($this->request->req() == 'post') {        
            
            $this->db = Registry::get('db');

            if ($this->validate() === true) {

                $this->redirect($this->url->http('gallery'));
            }
        }
        $this->render();
    }
    
    function logout()  {
        $this->user->logout();
        $this->redirect($this->url->http('login'));
    }
    
    private function validate() {
        $fvalue = $this->request->post['fvalue'];        
        $this->error = 1;
        if (isset($fvalue['username']) && 
            isset($fvalue['password']) && 
            $this->user->login($fvalue['username'], $fvalue['password'])) {
            $this->error = 0;
        } else {
            $this->data['msg'] = "Username / Password Mismatch";
        }

        // admin_level: 0=none; 1=superuser; 5=merchant/creator; 10=store
        if (!isset($this->session->data['admin_level']) || 
            $this->session->data['admin_level'] == 0) {
//echo "manager:".$this->session->data['admin_level'];  
            $this->data['msg'] = "Only Superuser and authors can login here";  
            $this->user->logout();
                     
            return false;
        }
      
        if($this->error) {
            return false;
        } else {
            return true;
        }
    }
}
