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

class ControllerPictures extends Controller {

    function __construct() {
        $this->db = Registry::get('db');		
        $this->id = 'content';
        $this->layout   = 'layout';    
        $this->template = $this->config->get('config_template') . 'pictures.php';
        $this->data['album_id']=$this->album_id();
        $this->data['gallery_id']=$this->gallery_id();
       
        $this->data['config_dynamic_ajax'] = $this->config->get('config_dynamic_ajax'); 
        $this->data['user_id'] = $this->session->data['user_id'];
      
        $this->data['picture_id']=$this->picture_id();
        $this->load->model('pictures');
        $this->load->model('albums');
        
        $this->load->model('users'); //MK
          
        $this->model_albums->table=DB_PREFIX.'albums';
        if($this->album_id) {
                 $this->data['avalue'] = $this->model_albums->getAlbum($this->album_id);
                 $this->data['gallery_id']=$this->gallery_id=$this->data['avalue']['gallery_id'];
                 $this->data['gvalue'] = $this->model_albums->getGallery($this->gallery_id);        
            }
            
        $this->data['thumb_width'] = $this->config->get('config_thumb_width') *0.25;
        $this->data['thumb_height'] = $this->config->get('config_thumb_height') * 0.25;
        
        
        $this->table = DB_PREFIX.'pictures';
        /* Assign the gallery & album dropdown*/ 
        $this->albumGalleryDropdown(); 
		

    }    
    
    function index() {
        /*
            Get value using get method, if the value is null assign the default value
        */
        $page = $this->request->get['page'];
        if(!$page){
            $page = 1;
        }
 			
//print_array($this->session->data); 
      
        /* Pagination Code Starts Here */        
        $total = $this->data['total'] = $this->model_pictures->totalPictures($this->album_id,$this->gallery_id);    
        $per_page = $this->config->get('pictures_per_page');
        $offset = ($page - 1) * $per_page;                
        //for sort and number
        $this->data['record_start'] = ($page-1)*$per_page;        
        $this->data['page'] = $page;                
                
        /* Get the picture details using album_id */                            
        $this->data['fvalue'] = $this->model_pictures->getPictures($this->album_id,$offset,$per_page,$this->gallery_id);
 
        // loop thru fvalue to set a creator_id if no gallery_id
        
        for ($i=0;$i<count($this->data['fvalue']);$i++) {
            if (!$this->gallery_id) {
                $this->data['fvalue'][$i]['creator_id'] = 14;
                } else {
                $this->data['fvalue'][$i]['creator_id'] = $this->gallery_id;
                }
            }
            
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->text = '';        
        $pagination->limit = $per_page; 
        $pagination->url = $this->url->http('pictures&album_id='.$this->album_id.'&gallery_id='.$this->gallery_id.'&page=%s');
        $this->data['pagination'] = $pagination->render();
        /* Pagination Code Ends Here */        
                        
        $this->render();
    }
    
    function add() {
        
        $this->edit();
    }
	
    function edit() {

        if($this->picture_id) {
            $this->data['fvalue'] = $this->model_pictures->getPicture($this->picture_id);
        }    
//print_array($this->data['fvalue']); exit;
  
        $user_id = $this->session->data['user_id']; 
         
        $admin_level = $this->session->data['admin_level'];
        $store_id = $this->session->data['manager_store_id'];
        
        $this->session->data['new_ad'] = false;
        if ($this->data['fvalue']['store_id'] == 0)  $this->session->data['new_ad'] = true;
//print_r($this->session->data);  
        
        if ($this->data['fvalue']['store_id'] != 0 &&
            $this->data['fvalue']['store_id'] != $store_id) {
            // cannot edit someone else ad
            die("This ad is not yours. You cannot edit it.");
            }
                  
        $this->data['fvalue']['store_id'] = $store_id;
        $this->data['fvalue']['zip'] = $this->postal_code();
        //$this->data['fvalue']['category_id'] = $this->cat_id();
 
        $this->template = $this->config->get('config_template') . 'pictures_edit.php';
        $this->render();
        
    }

    function delete() {
        $this->load->helper('image');
        if($picture_id = $this->request->get['picture_id'])  {
            $this->model_pictures->deletePicture($picture_id);
            // update albums table
            
            $this->model_pictures->updateAlbum($this->data['album_id']);
            
        }
        $this->redirect($this->url->http('pictures&album_id='.$this->album_id));
    }
    
    function callback() {
        $flag = $this->request->post['flag'];
        if($flag == 'albums') {
            $gallery_id = $this->request->post['gallery_id'];
            $this->load->model('albums');
            echo "<select name='album_id'><option value=''>Select..</option>".$this->model_albums->albumsDropdown($gallery_id)."</select>";
        }
        exit;
    }
    
    function save() {        
        set_time_limit (300);
        
        $fvalue = $this->request->post['fvalue'];
        if($this->gallery_id && $this->album_id) {
            $this->edit();
        }

        $fvalue['album_id'] = $this->album_id;
        if($fvalue['title'] && $fvalue['album_id']) {
            //uploading the image to server
            $this->load->helper('image');
            if($this->request->files['image']) {
                  //removing hte old image
                if($fvalue['picture_id'] && $this->request->files['image']['name'])removeimage($fvalue['old_image']);
			
                $user_id = $this->user->getId();    //MK added for p2s
                $gallery_id = $this->gallery_id;     //MK that's the default author's gallery
                $album_id = $fvalue['album_id'];     //album
                
                if ($image = saveimage($this->request->files['image'],"page",$gallery_id,$album_id)) {
                    
                    image_resize($image,$this->config->get('config_thumb_width'), $this->config->get('config_thumb_height'),'thumb');
                    
                              
                    $quality = $this->config->get('config_image_quality'); 
                    if (! $quality) $quality = 90;
                     
                    image_resize($image, $this->config->get('config_large_width'), $this->config->get('config_large_height'),'tmp',$quality);
                    //
                    $sx = explode('/',$image);
                    $sx[0] = 'tmp';
                    $tmpimage = implode('/',$sx);
 
//echo HTTP_GALLERIA.'/'.$image.'--gl: '.$gallery_id.'--al: '.$album_id;                     
                    copy(DIR_GALLERIA."/".$tmpimage,DIR_GALLERIA."/".$image);
                    @unlink(DIR_GALLERIA."/".$tmpimage);
                    //                  
  
                list($width, $height, $type) = getimagesize(HTTP_GALLERIA.'/'.$image);
                $fvalue['width_large'] = $width;
                $fvalue['height_large'] = $height; 

        
//print_array($fvalue); exit;
                          
                }
            }    
          
        $pathinfo = pathinfo($image);
 
//print_array($pathinfo);        
        $fvalue['image'] = $pathinfo['filename'] . '.'.$pathinfo['extension']; 
         
            $admin_level = $this->session->data['admin_level'];
            /*
            if ($admin_level != 1) {
                if ($this->session->data['new_ad']) unset($fvalue['picture_id']);
                }
            */            		
            $fvalue = $this->model_pictures->savePicture($fvalue);
			
        } else {
            $fvalue['msg'] = "Mandatory Fields Missing";
        }        
        
        if($fvalue['msg']) {
            $this->data['fvalue'] = $fvalue;    
            $this->template = $this->config->get('config_template') . 'pictures_edit.php';
            $this->render();
        } else 
            $this->redirect($this->url->http('pictures&album_id='.$this->album_id));
        
    }
    function order() {

        $orderAr = $this->request->post['sortorder'];
        $page = $this->request->post['page'];
        if(count($orderAr)) {
            $this->model_pictures->order($orderAr);
        }
        $this->redirect($this->url->http('pictures&album_id='.$this->album_id.'&page='.$page.'&gallery_id='.$this->gallery_id));
    }    
    
    private function album_id(){
        return    $this->album_id = $this->request->get['album_id']?$this->request->get['album_id']:$this->request->post['album_id'];
    }
    
    private function gallery_id() {
        return $this->gallery_id=$this->request->get['gallery_id']?$this->request->get['gallery_id']:$this->request->post['gallery_id'];                            
    }
    
    private function picture_id() {
        return $this->picture_id=$this->request->get['picture_id']?$this->request->get['picture_id']:$this->request->post['picture_id'];                            
    }

    
    private function postal_code() {
        return $this->postal_code=$this->request->get['zip']?$this->request->get['zip']:$this->request->post['zip'];                            
    }
    
    private function cat_id() {
        // cat_id should come from the album
        return $this->cat_id=$this->request->get['cat_id']?$this->request->get['cat_id']:$this->request->post['cat_id'];                            
    }
      	
    private function albumGalleryDropdown() {
        $this->load->model('gallery');
        $this->data['gallery_dropdown'] = $this->model_gallery->galleryDropdown($this->gallery_id);
        if($this->gallery_id)
           $this->data['albums_dropdown'] = $this->model_albums->albumsDropdown($this->gallery_id,$this->album_id);
    }		
}