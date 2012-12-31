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

class ControllerAlbums extends Controller {

    function __construct() {
    
        $this->db = Registry::get('db');
        $this->id = 'content';
        $this->table = DB_PREFIX.'albums';
        $this->template = $this->config->get('config_template') . 'albums.php';        
        $this->layout   = 'layout';                    
        $this->data['gallery_id']=$this->gallery_id();
		$this->load->model('albums');  
        $this->load->model('gallery');  
        
        $this->load->helper('access');
        $this->access = new HelperAccess();  

        $this->load->helper('helpers');
        $this->commonhelpers = new HelperHelpers();
                
        $this->admin_gallery = 1;
        $this->managers_gallery = 1;  
       
    }        

    function index() {        

        $this->user_id = $this->session->data['user_id']; 

    if (!$this->gallery_id) $this->gallery_id = $this->user_id;
        //echo $this->gallery_id;			
       
        $this->data['gvalue'] = $this->model_albums->getGallery($this->gallery_id);
        //print_array($this->data);   		
		/*
			Load the filter dropdown from helper gallery
		*/
        $this->load->model('gallery');
        $this->data['gallery_dropdown'] = $this->model_gallery->galleryDropdown($this->gallery_id);


        if ($this->session->data['admin_level'] != ADMIN_LEVEL &&
            $this->session->data['admin_level'] != AUTHOR_LEVEL) {                         //MK
            $location = "/admin/index.php?c=pictures&album_id=".
                        $this->managers_gallery.
                        "&gallery_id=".
                        $this->admin_gallery;
            header("Location: $location");
            }

        if ($this->session->data['admin_level'] == AUTHOR_LEVEL) $this->gallery_id=$this->session->data['user_id'];                      //MK
        //echo $this->gallery_id;                		        
        /*
            Get value using get method, if the value is null assign the default value
        */
        $page = $this->request->get['page'];
        if(!$page){
            $page = 1;
        }
        
        /* Pagination Code Starts Here */        
        $total = $this->data['total'] = $this->model_albums->totalAlbums($this->gallery_id);    
        $per_page = $this->config->get('albums_per_page');
        $offset = ($page - 1) * $per_page;
        $this->data['record_start'] = ($page-1)*$per_page;
        $this->data['page'] = $page;
        
        
        /* cancel last upload if requested */
        if ($this->request->get['mode'] == 'reset') $this->resetUpload($this->gallery_id);
        
        /* Get the album details using album_id */
        $this->data['fvalue'] = $this->model_albums->getAlbums($this->gallery_id,$offset,$per_page);    

        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->text = '';
        $pagination->limit = $per_page; 
        $pagination->url = $this->url->http('albums&gallery_id='.$this->gallery_id.'&page=%s');
        $this->data['pagination'] = $pagination->render();
        /* Pagination Code Ends Here */
        $this->render();
    }
    
    
    function add() {
        $this->edit();
    }
	
    function edit() {
 
        $album_id = $this->request->get['album_id'];
  
        $privacy_options = array(0=>'No one else but me can access this album',
                                 1=>'Everybody can access this album',
                                 -1=>'Registered users can access this album');
        $privacy = 0;
        
        if($album_id) {
            $this->data['fvalue'] = $this->model_albums->getAlbum($album_id);
            $this->data['gallery_id']=$this->gallery_id = $this->data['fvalue']['gallery_id'];
            $this->category_id = $this->data['fvalue']['category_id'];
            /* check if public read */
        
            $privacy = $this->access->check_public($album_id);
 
            } else {
            $this->user_id = $this->gallery_id = $this->session->data['user_id']; 
            $this->data['gallery_id'] = $this->gallery_id;
            $privacy = 1;   // all can read
            $this->category_id = 0;
            $this->data['fvalue']['domain_owner'] = DOMAIN_OWNER;
            }

        $this->data['privacy_dropdown'] = $this->commonhelpers->dropdown($privacy_options,$privacy);

        $domain_options = array(1=>'Visible only from this web site',
                                 0=>'Visible from all web sites in this eco-system');
 
        $domain = $this->data['fvalue']['domain_owner'];
        
        if ($domain != 0) $domain = 1;
        $this->data['domain_dropdown'] = $this->commonhelpers->dropdown($domain_options,$domain);

        $this->data['gallery_dropdown'] = $this->model_gallery->galleryDropdown($this->gallery_id);		

		/*
			Load the filter dropdown from helper category
		*/
		
        $this->load->model('category');
        $this->data['category_dropdown'] = $this->model_category->categoryDropdown($this->category_id);
		
        $this->data['gvalue'] = $this->model_albums->getGallery($this->gallery_id);
           
        $this->template = $this->config->get('config_template') . 'albums_edit.php';
        $this->render();                    
        
    }    
 
    function save() {
 
        $fvalue = $this->request->post['fvalue']; 
        //print_array($fvalue);   
        
        $msg = array();
        
        if($fvalue['title'] && $fvalue['gallery_id']) {
        
            $gallery_id = $fvalue['gallery_id'];
             
            //uploading the image to server
            $this->load->helper('image');
            if ($this->request->files['image']) { 

                if ($this->request->files['image']['size'] != 0) {  
                    $image = $this->load_cover($gallery_id,$fvalue);
                    }
                }  
            
            $pathinfo = pathinfo($image);
 
            //print_array($pathinfo);        
            $fvalue['image'] = $pathinfo['filename'] . '.'.$pathinfo['extension']; 
 
            $fvalue = $this->model_albums->saveAlbums($fvalue);
            $album_id = $fvalue['album_id'];
            //echo 'al='.  $album_id; exit;
      
            if ($this->request->files['image']) {     
                if ($this->request->files['PDFile']['size'] != 0) {  
                    if ($this->request->files['PDFile']['type'] != 'application/pdf') {
                        $msg[] = "You must select a PDF file";
                        } else {  
                         $this->load_PDF($album_id);
                        }                  
                    }
                }
                          
            //
            $this->access->set_privacy($album_id,$fvalue['privacy']);  
            //
            $filesDir = 'app/controller/jupload/site/galleria/';
            $manifest_name = $filesDir.'files/'.$this->gallery_id.'.manifest.txt';
            if (file_exists($manifest_name)) {
                
                $rs = HTTP_HOST.'/admin/index.php?c=albums/load_batch';  
                
                $parray = array();       
 
                $token = time().'.'.$this->gallery_id;
                file_put_contents($token,$manifest_name);
                $parray['curl'] = $token;  
                                   
                $parray['album_id'] = $album_id;
                $parray['manifest_name'] = $manifest_name;
                $parray['filesDir'] = $filesDir;
                

                $result = asynchCurl($parray,$rs); 
                /*
                if we have one or few files, by the time this process restarts after
                the 1 sec timeout of curl, the file ttlsize.txt might be gone, or it
                might be gone when we read it....
                */ 
                $msg[] = 'We are post-processing your images. Do NOT save again! You can continue working on existing albums. Do NOT create new albums until this one is ready.';
                $msg[] = 'You will get an email when your album is ready.';
                
                $ttlsize_name = $filesDir.'files/'.$this->gallery_id.'.ttlsize.txt';
                if (file_exists($ttlsize_name)) {
                    $ttlsize = file_get_contents($ttlsize_name) / 1000; //now in KB
                    $ttlsize = round($ttlsize / 1000); // now in MB
                    // we assume 1 MB = 1 minute 
                    $msg[] = "Based on the total size ($ttlsize MB) uloaded, it can take up to $ttlsize minutes";
                    } else {
                    $msg[] = "It may take up to 20 secs/images depending on the definition.";
                    }
                
                $busy_name = $filesDir.'files/'.$this->gallery_id.'.busy.txt'; 
                file_put_contents($busy_name,$fvalue['title']);
                
                } else {
                //die("error $manifest_name ". getcwd() );
                }  
                 
        } else {
            $msg[] = "Mandatory Fields Missing";
        }

        if(count($msg) != 0) {
            $this->data['msg'] = implode('<br/>',$msg);
            $this->edit();
        } else 
            $this->redirect($this->url->http('albums&gallery_id='.$this->gallery_id));
    } 
    
    function load_cover($gallery_id,$fvalue) {

        $image = '';
        
        if ($this->request->files['image']['size'] != 0) {          
            //removing the old image
            $old_file = $fvalue['old_image'];
            //echo $old_file;    
            //print_array($this->request->files);           
            // next line not yet tested  
            if ($fvalue['album_id']  && 
                $this->request->files['image']['name']) {
                removeimage($old_file);
                }
                    
            //echo $this->session->data['user_id'].'--'.$gallery_id;       
            if ($image = saveimage($this->request->files['image'],"album",$gallery_id)) {   
         
                image_resize($image,$this->config->get('album_thumb_width'), $this->config->get('album_thumb_height'),'thumb');
                  
                image_resize($image, $this->config->get('config_large_width'), $this->config->get('config_large_height'),'tmp');
                //
                $sx = explode('/',$image);
                $sx[0] = 'tmp';
                $tmpimage = implode('/',$sx);
                            
                copy(DIR_GALLERIA."/".$tmpimage,DIR_GALLERIA."/".$image);
                @unlink(DIR_GALLERIA."/".$tmpimage);
                //    
               
                }
            } 
                    
        return $image;                   
    }
    
    function load_PDF($album_id) {

        $fvalue = $this->model_albums->getAlbum($album_id);         
 //print_array($fvalue);                             
        
        $sortorder = $fvalue['pictures'] + 1;
                 
//print_array($this->request->files); exit; 
 
        // convert to jpg with imagemagik
        $tmp_name = $this->request->files['PDFile']['tmp_name'];
        //$filename = "foo.jpg"; // will create foo-0.jpg, foo-1.jpg,etc...
        $original_name = $this->request->files['PDFile']['name'];
        $as_of = time();
                  
        $filename = "page_". $album_id. "_" .$as_of. "_". "$sortorder.jpg";
                   
        $target = DIR_GALLERIA."/tmp/".$filename;
        
        $width = $this->config->get('config_large_width');
        $height = $this->config->get('config_large_height');

        $cde ="sudo convert $tmp_name -colorspace rgb -size $width". 'x' ."$height $target ";

        set_time_limit(0); 
             
        shell_exec($cde);
//echo $cde; exit;         
        // rename files
        $newpages = array();
        $index = 0;
        $filename =  "page_". $album_id. "_" .$as_of. "_". "$sortorder-$index.jpg";              
        while (file_exists(DIR_GALLERIA."/tmp/".$filename)) {
        
            $newname = "page_". $album_id. "_" .$as_of. "_";
            $pageno = $sortorder + $index;
            
            $newname .= "$pageno.jpg";
            
            @rename(DIR_GALLERIA."/tmp/".$filename,DIR_GALLERIA."/large/".$newname);
            
            $newpages[$pageno] = $newname;
            
            $index++;
            $filename =  "page_". $album_id. "_" .$as_of. "_". "$sortorder-$index.jpg";   
            }
        //print_array($newpages); exit;  
        $batch = array();
  
        foreach ($newpages as $key=>$val) {            
            //print_array($val); exit;           
            $image = "large/".$val;
                
            image_resize($image,$this->config->get('album_thumb_width'), $this->config->get('album_thumb_height'),'thumb');
                  
            image_resize($image, $this->config->get('config_large_width'), $this->config->get('config_large_height'),'tmp');
 
            $tmpimage = DIR_GALLERIA."/tmp/".$val;
            $newimage = DIR_GALLERIA.'/large/'.$val;
         
            copy($tmpimage,$newimage);
           
            @unlink($tmpimage); 
  
            $fvalue['image'] = $val;
            $fvalue['album_id'] = $album_id;
            $fvalue['title'] = "$original_name -- $key";
            $fvalue['url'] = '';
            $fvalue['category_id'] = 0;
            $fvalue['store_id'] = 0;
            $fvalue['zip'] = '';
            $fvalue['sortorder'] = $key;
            $fvalue['description'] = '';
            $fvalue['date_released'] = 0;
            
            // will force size to config... might distort the JPG
            // best when PDF printed as ledger/portrait/300dpi
            //list($width, $height, $type) = getimagesize($newimage);
            
            $fvalue['width_large'] = $width;
            $fvalue['height_large'] = $height; 
 
            $batch[] = $fvalue;  
          
                       
            }
        //print_array($batch);  
        if (count($batch) == 0) return false;
        
        $this->load->model('pictures');
        //echo 'picture loaded';
        $this->model_pictures->addManyPictures($album_id,$batch); 
                               
    }
    
    function resetUpload($gallery_id) {
 
        $filesDir = 'app/controller/jupload/site/galleria/'; 
            
        $this->cleanup($gallery_id,$filesDir.'files/');  
 
        $manifest_name = $filesDir.'files/'.$gallery_id.'.manifest.txt';
        @unlink($manifest_name);  
        $ttlsize_name = $filesDir.'files/'.$gallery_id.'.ttlsize.txt';
        @unlink($ttlsize_name); 
        $busy_name = $filesDir.'files/'.$gallery_id.'.busy.txt';
        @unlink($busyname); 
 
        
    }
        
    function load_batch() {
   
        $album_id = $this->request->post['album_id'];
        $manifest_name = $this->request->post['manifest_name'];
        $filesDir = $this->request->post['filesDir'];
 
        $this->load->model('pictures');
        $this->table = DB_PREFIX.'pictures ';  
        if (! file_exists($manifest_name)) return;
            
        $manifest = file_get_contents($manifest_name);
        $files = explode("\n",$manifest);
        //print_array($files);
        
        $as_of = time();
        $count = 1;
        
        $fvalue = $this->model_albums->getAlbum($album_id);
        $sortorder = $fvalue['pictures']; 
    
        $this->load->helper('image');
    
        $batch = array();
    
        foreach ($files as $fl) {
            if ($fl == '') continue;
            
            $image = $filesDir.$fl;
            if (! file_exists($image)) continue;
        
            set_time_limit(0); 
    
            $sx = explode('/',$image);
            $sx = explode('.',end($sx));
            $title = $sx[0];    // shld be DSCN1102
            
            $image_name = "page_" . $album_id . "_" . $as_of . "_" . $count . ".jpg";
            $large = "large/".$image_name;
            copy($image,DIR_GALLERIA.'/'.$large);
            
            image_resize($large,$this->config->get('config_thumb_width'), 
                                $this->config->get('config_thumb_height'),
                                'thumb');
                                     
            image_resize($large, $this->config->get('config_large_width'), 
                                 $this->config->get('config_large_height'),
                                 'tmp');
 
            $sx = explode('/',$large);
            $sx[0] = 'tmp';
            $tmpdir = implode('/',$sx);
                   
            copy( DIR_GALLERIA.'/'.$tmpdir ,  DIR_GALLERIA.'/'.$large);
             
            @unlink(DIR_GALLERIA.'/'.$tmpdir);
    
            $fvalue['image'] = $image_name;
            $fvalue['album_id'] = $album_id;
            $fvalue['title'] = $title;
            $fvalue['url'] = '';
            $fvalue['category_id'] = 0;
            $fvalue['store_id'] = 0;
            $fvalue['zip'] = '';
            $fvalue['sortorder'] = $sortorder + $count;
            $fvalue['description'] = '';
            $fvalue['date_released'] = 0;
            
            list($width, $height, $type) = getimagesize(DIR_GALLERIA.'/'.$large);
            $fvalue['width_large'] = $width;
            $fvalue['height_large'] = $height; 
            
                                    
            //$fvalue = $this->model_pictures->savePicture($fvalue);
            $batch[$count] = $fvalue;
            
            @rename($image,$image.'.done'); 
            $count++; 
            }
 
        $this->model_pictures->addManyPictures($album_id,$batch); 
 
        $this->table = DB_PREFIX.'albums';
        
        foreach ($files as $fl) {
            if ($fl == '') continue;
                        
            $image = $filesDir.$fl.'.done';
 
            @unlink($image); 
            }     
               
        @unlink($manifest_name);
        $ttlname_name = str_replace('manifest','ttlsize',$manifest_name);
        @unlink($ttlname_name);
 
        $busy_name = str_replace('manifest','busy',$manifest_name);
        @unlink($busy_name);
                    
        $mail = new Mail();
        
        $from = $this->config->get('config_email');
        
        $mail->setFrom($from);
        $mail->setSender(SITE_TITLE);
        $mail->setSubject("Your images are now ready");
 
        $this->load->model('users');
        $recip = $this->user->getUserName();
        
        $mail->setTo($recip);    
        $mail->setHtml("<p style='color:blue;' >You can now look at your album</p>");
        $mail->send();          
    }
    
    function cleanup($gallery_id,$src) {
 
        $dir = opendir($src);
        if (! $dir) return false;

        $list = array();
        $done = 'done';
        
        while ( false !== ( $file = readdir( $dir ) ) ) { 
 
            if ($file == '.' || $file == '..') continue;
         
            if (is_dir("$src/$file")) continue;
 
            $sx = explode(".",$file);
            if (end($sx) != $gallery_id) {
                if (end($sx) != $done) continue;
                array_pop($sx);
                if (end($sx) != $gallery_id) continue;
                }
                
            $image = $src.$file;
 
            @unlink($image); 
            @unlink($image.'.'.$done); 
            } 
       
        closedir($dir); 
 
    }
         
    /*
        Order the album values
    */
    function order() {

        $orderAr = $this->request->post['sortorder'];
        $page = $this->request->post['page'];
        if(count($orderAr)) {
            $this->model_albums->order($orderAr);
        }

        $this->redirect($this->url->http('albums&gallery_id='.$this->gallery_id.'&page='.$page));
    }    
    
    function delete() {
                    
        if($album_id = $this->request->get['album_id'])  {
            $this->model_albums->deleteAlbum($album_id);
        }        
        $this->redirect($this->url->http('albums&gallery_id='.$this->gallery_id));        
    }

    private function gallery_id() {  
        return $this->gallery_id=$this->request->get['gallery_id']?$this->request->get['gallery_id']:$this->request->post['gallery_id'];                            
    }
}