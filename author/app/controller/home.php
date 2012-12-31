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
 
class ControllerHome extends Controller {

    function __construct() {
        $this->id = 'content';
        $this->template = $this->config->get('config_template') . 'home.php';
        $this->layout   = 'layout';                        
    }    
        
    function index() {
        $this->load->model('home');        
        $this->data['picture'] = $this->model_home->getPictureCount();
        $this->data['album'] = $this->model_home->getAlbumCount();
        $this->data['gallery'] = $this->model_home->getGalleryCount();
        $this->render();
    }
    
}