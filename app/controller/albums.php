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
  
class ControllerAlbums extends Controller {
    
    function __construct() {
        $this->id       = 'content';
        $this->layout   = 'layout';
        $this->template = $this->config->get('config_template') .'albums.php';
        $this->load->model('albums');
    }
    /*
        getting the Album list from gallery
    */    
    function index() {
        // called after a search only
        
        if(!(int)$this->gallery_id = $this->data['gallery_id'] = $this->request->get['gallery_id']) {
             $this->redirect($this->config->get('config_site_dir').'/index.php');
        }
        
        $this->data['navigation'] = $this->model_albums->galleryNavLink($this->gallery_id);
    
        /*
            Get value using get method, if the value is null assign the default value
        */
        $page = $this->request->get['page'];
        if(!$page){
            $page = 1;
        }
        
        $this->data['width'] = $this->config->get('album_thumb_width');
        $this->data['height'] = $this->config->get('album_thumb_height');

        /* Pagination Code Starts Here */        
        $total = $this->data['total'] = $this->model_albums->totalAlbums($this->gallery_id);
        $per_page = $this->config->get('albums_per_page');
        $offset = ($page - 1) * $per_page;

        /* Get the album details using album_id */
        $this->data['fvalue'] = $this->model_albums->albumsDetails($this->gallery_id,$offset,$per_page);
//print_array($this->data['fvalue']);exit;
//print_array($this->data);  
        $this->data['user_id'] = $this->session->data['user_id'];
        $this->data['emb_user_id'] = $this->session->data['emb_user_id'];
      
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $per_page;  
        $pagination->url = $this->seourl->rewrite('index.php?c=albums&gallery_id='.$this->gallery_id.'&page=%s');
        $this->data['pagination'] = $pagination->render();
        /* Pagination Code Ends Here */
        $this->render();
        }    
}