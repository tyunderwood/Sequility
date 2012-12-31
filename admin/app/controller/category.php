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



class ControllerCategory extends Controller {

    function __construct() {

        $this->db = Registry::get('db');
        $this->id = 'content';
        $this->table = DB_PREFIX.'categories';
        $this->template = $this->config->get('config_template') . 'category.php';        
        $this->layout   = 'layout';
        $this->data['category_id']=$this->category_id();
        $this->load->model('category');
           
        $this->all_category = 0;
         
    }        
        
    function index() {
 
        if ($this->session->data['admin_level'] != ADMIN_LEVEL &&
            $this->session->data['admin_level'] != AUTHOR_LEVEL) {                         //MK
            $location = "/admin/index.php";
            header("Location: $location");
            }
/*
        if ($this->session->data['admin_level'] == AUTHOR_LEVEL) {
            // get all general categories created by user admin level 1
            // author can only select one users_id = gallery_id here. If user is also domain owner
            // then cat applies to all galleries
            $this->data['fvalue'][0] = $this->model_category->getCategories($this->session->data['user_id']);
            //print_array($this->data['fvalue']);    
            } else {
            // admin level and domain owners can create (and delete) new categories 
            $this->data['fvalue'] = $this->model_category->getCategories();
        
            }  
*/
            /* will grab domain cats and generic cats */
            $this->data['cats'] = $this->model_category->getCategories();  
//print_array($this->data['fvalue']);      
        //get all the galleries in a dropdown using: this->commonhelpers->dropdown
        
        $this->render();
    }
    
    
    function add() {
        $this->edit();
    }    
    
    function edit() {
        if((int)$this->category_id) {
            $this->data['fvalue'] = $this->model_category->getCategory($this->category_id);
        }  
        $this->template = $this->config->get('config_template') . 'category_edit.php';
        $this->render();
        
    }
    
    function save() {
        
        $fvalue = $this->request->post['fvalue'];
        if($this->data['category_id']) {
            $fvalue['category_id'] = $this->data['category_id'];
            } else {
            $fvalue['category_id'] = '';
            }
            

        //uploading the image to server
        $this->load->helper('image');
        if($this->request->files['image']) {
		
	        if($fvalue['category_id'] && $this->request->files['image']['name']) removeimage($fvalue['old_image']);
            if($image = saveimage($this->request->files['image'],"cat",$this->session->data['user_id'])) {
                //removing hte old image
                image_resize($image,$this->config->get('gallery_thumb_width'), $this->config->get('gallery_thumb_height'),'thumb');			
                }    
            }
        $pathinfo = pathinfo($image);
        //echo $this->session->data['emb_user_id'];
     
        $fvalue['image'] = $pathinfo['filename'] . '.'.$pathinfo['extension']; 
 //print_array($fvalue);  exit;  
        if($fvalue['title']) {        
            $fvalue = $this->model_category->saveCategory($fvalue);
        } else {
            $fvalue['msg'] = "Mandatory Fields Missing";
        }        
        
        if($fvalue['msg']) {
            $this->data['fvalue'] = $fvalue;
            $this->template = $this->config->get('config_template') . 'category_edit.php';
            $this->render();
        } else 
            $this->redirect($this->url->http('category'));
    }
 
    function delete() {
        if((int)$this->category_id)  {
            $this->model_category->deleteCategory($this->category_id);
        }
        $this->redirect($this->url->http('category'));
    }
    
    private function category_id() {
        return $this->data['category_id'] = $this->category_id = $this->request->get['category_id']?$this->request->get['category_id']:$this->request->post['category_id'];                            
    }
}