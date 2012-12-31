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

class ControllerIndex extends Controller {
    
    function __construct() {
        $this->id       = 'content';
        $this->layout   = 'layout';
        $this->template = $this->config->get('config_template') .'index.php';
        $this->load->model('gallery');
    }
    
    function index() {
        
        $this->data['config_thumb_width'] = $this->config->get('config_thumb_width');
        $this->data['config_thumb_height'] = $this->config->get('config_thumb_height');
            
        $this->data['fvalue'] = $this->model_gallery->galleryDetails();
//print_array($this->data['fvalue']); exit;
        $this->render();
    }
}
