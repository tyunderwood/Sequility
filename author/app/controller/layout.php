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
  
class ControllerLayout extends Controller {
    protected function index() {

 //print_array($this->session->data); 

        //MK
        $this->data['user_id'] = $this->session->data['user_id'];
        $this->data['admin_level'] = $this->session->data['admin_level'];
 
        $this->data['title'] = $this->config->get('config_site_title');
        $this->data['description'] = $this->config->get('config_site_description');
        $this->data['base'] = HTTP_SERVER.'/';
        
        $layoutMain = array();
        $layoutMain['logout'] = $this->url->http('login/logout');
        $this->data['showmenu'] = false;	        
        if($this->user->isLogged())
              $this->data['showmenu'] = true;		           
            
        $this->data['logo'] = HTTP_HOST.'/pictures/'.$this->config->get('config_logo');
        $this->data['icon'] = $this->config->get('config_icon');
        $this->data['masthead_color'] = $this->config->get('config_masthead_color');
        if ($this->data['masthead_color'] == '') $this->data['masthead_color'] = 'black';
        
//print_array($this->data); exit;
        $this->template = $this->config->get('config_template') . 'layout.php';                
        $this->render();
    }
}
?>
