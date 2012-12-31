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

class ControllerPictures extends Controller {
    
    function __construct() {
        $this->id       = 'content';        
        $this->layout   = 'layout';
        $this->template = $this->config->get('config_template') .'pictures.php';        
        $this->load->model('pictures'); 
        
        $this->load->helper('access');
        $this->access = new HelperAccess();                           
    }
    /*
        getting the Album list from gallery
    */    
    function index() { 
         
        if(!(int)$this->album_id = $this->data['album_id'] = $this->request->request['album_id']) {
             $this->redirect($this->config->get('config_site_dir').'/index.php');
        }                

        /* access control */
        if (isset($this->request->post['email_token']) && 
            trim($this->request->post['email_token']) != '') {
            $access = $this->access->check_read_token($this->album_id,$this->request->post['email_token']);
            } else {
            $access = $this->access->check($this->album_id,'read');
            // if access = email_access we refresh page and bring up email form
            }
        
        if ($access !== true) $this->redirect($this->config->get('config_site_dir').
                                        '/index.php?access='.$access.'&al='.$this->album_id);     
  
               
        /* Navigation */
        $this->data['navigation'] = $this->model_pictures->albumNavLink($this->album_id);                     
        $cover_image = $this->data['navigation'][5];
       
        $this->data['author_name'] =  $this->data['navigation'][1]['title'];
        $this->data['album_name'] =  $this->data['navigation'][2]['title'];
 
        /* 0 means all pages are free. */
        $this->data['first_paid_page'] = $this->data['navigation'][6];
      
        /* Get value using get method, if the value is null assign the default value      */
        $page = $this->request->get['page'];
        if(!$page){
            $page = 1;
        }
        
        $startat = $this->request->get['startat'];  // all lower case
        if(!$startat){
            $startat = 1;
        } 
        //print_array($this->request->get);  
        $this->session->data['current_panel'] = $page;      
   
        /* Pagination Code Starts Here */        
        $total = $this->data['total'] = $this->model_pictures->totalPictures($this->album_id); 
        //echo 'total='.$total; exit;      
 
        if ($total == 0) $this->redirect($this->config->get('config_site_dir').'/index.php?error=no_page' );
              
        $per_page = $this->config->get('pictures_per_page');
        $this->data['per_page'] = $per_page;
        
        $offset = ($page - 1) * $per_page;        
        $this->data['t_panel'] = floor( ($total - 1)/$per_page) + 1;
         
        /* Get the picture details using album_id */
        $this->data['fvalue'] = $this->model_pictures->picturesDetails($this->album_id,$offset,$per_page);        
        //print_array($this->data['fvalue']);    exit;  
        //
        $this->data['big_images'] = $this->prepBigImages($this->data['fvalue']);
        //
          
        $this->data['startat'] = $startat;
         
        $this->data['c_panel'] = $page;
        $this->data['gallery_id'] = $this->data['navigation'][4];
        $this->data['user_id'] = $this->session->data['user_id'];
        $this->data['emb_user_id'] = $this->session->data['emb_user_id'];
        
        if ($total < $per_page) {
            $this->session->data['panel_count'] = $total;
            } else {
            $this->session->data['panel_count'] = $per_page;
            }

        $this->data['config_thumb_width'] = $this->config->get('config_thumb_width');
        $this->data['config_thumb_height'] = $this->config->get('config_thumb_height');
        
        $this->data['config_large_width'] = $this->config->get('config_large_width');
        $this->data['config_large_height'] = $this->config->get('config_large_height');
 
        $this->data['album_cover'] = HTTP_GALLERIA_LARGE.'/'. $cover_image;
            
        $pagination = new Pagination();
        $pagination->total = $total;
        $pagination->page = $page;
        $pagination->limit = $per_page; 
 
        $pagination->url = $this->seourl->rewrite('index.php?c=pictures&album_id='.$this->album_id.'&page=%s');

        if ($this->session->data['admin_level'] != 0) {  
            $this->data['pagination'] = $pagination->render();
            }
        /* Pagination Code Ends Here */
        
        $this->render();
 
    }

    function prepBigImages($fvalue) {
    
        $images = '';

        $width = $this->config->get('config_thumb_width');
        $height = $this->config->get('config_thumb_height');
 
        $link = '';
        
        //$maxpageno = 0;  
    
        if(count($fvalue) == 0) return false;
        
        // print_array for debugging is at the end 
   	    foreach($fvalue as $key=>$val){ 
   	  
            $images .= $val['image'].'|';
   
            $images .= $val['width_large'].'|'.$val['height_large'].'|';    
            $images .= $val['title'].'|'. $val['sortorder'] .'|'. $val['album_id'];
            //check prev/next crop
            $sx = explode('.',$val['image']);
            $filetype = '.' . end($sx);
            $prev = $val['sortorder'] - 1;
            $next = $val['sortorder'] + 1;

            //$maxpageno = $val['sortorder'];
        
            $images .= '|'. $prev;
            if (file_exists(GALLERIA.'/crop/page_' . $val['album_id'] . '_' . $prev . $filetype)) {
                $images .= '|on';
                } else {
                $images .= '|off';
                }
        
            $images .= '|'. $next;
            if (file_exists(GALLERIA.'/crop/page_' . $val['album_id'] . '_' . $next . $filetype)) {
                $images .= '|on';
                } else {
                $images .= '|off';
                }  
        
            // bwd   (probably useless now) 
            if ($fvalue[$key-1]) {
                $images .= '|true';
                } else {
                $images .= '|false';
                }

            // fwd   (probably useless now) 
            if ($fvalue[$key+1]) {
                $images .= '|true';
                } else {
                $images .= '|false';
                }                             
        
            $images .= '|'.$val['picture_id'];

            /* link contains:
            // http://www.emediadeal.com/pictures_single/pic/33/page/all?cp=1

            if (! $link) {
                $link = $val['link'];
                if (DEBUG) $link .= '&debug=active';
                }
            */
            
            $date_released = $val['date_released'];   
            if ($date_released != 0) {
                $simple_date = date("Y-m-d",strtotime ($date_released));
                }
 
            if (strtotime ( $date_released) > time()) {
                $date_released = "To be released: $simple_date";  
                $images .= '|'.$simple_date;
                } else {
                $date_released = "Released: $simple_date";
                if ($date_released == 0) $date_released = ''; 
                $images .= '|0';
                } 

            //
            $images .= '|'.$val['description'];
            $images .= '\n';    
    
            }
            
        return $images;                
    }
    
    function single() { 
                 
        //echo  'album_id='.$this->request->get['album_id'].' picture_id'.$this->request->get['picture_id'];
  
        if(!(int)$this->picture_id = $this->data['picture_id'] = $this->request->get['picture_id']) {
             $this->redirect($this->config->get('config_site_dir').'/index.php');
        }
        $this->data['config_dynamic_ajax'] = $this->config->get('config_dynamic_ajax');
  
        $this->data['navigation'] = $this->model_pictures->pictureNavLink($this->picture_id);
        $this->data['fvalue'] = $this->model_pictures->getPicture($this->picture_id);
        /*
            Get List of Picture_ids in the album 
        */
          
        $this->model_pictures->getPictureList($this->data['fvalue']['album_id']);
        $this->data['page_count'] = count($this->picture_list); 
        //print_array($this->picture_list);   exit;       

        $this->data['album_id'] =  $this->data['fvalue']['album_id'];
        if (! $this->request->get['cp']) $this->request->get['cp']=1; 
        $this->session->data['current_panel'] =  $this->request->get['cp'];
        
        $this->data['cp'] = $this->request->get['cp'];       
              
        $per_page = $this->config->get('pictures_per_page');
        $this->data['per_page'] = $per_page;
          
        if ($this->request->get['startat']) {
            $this->data['startat'] = $this->request->get['startat'];
            } else {
            if ($this->request->get['page']) {
                $this->data['startat'] = $this->request->get['page'];
                } else {
                $this->data['startat'] = 1;
                }
            }
        //print_array($this->data);            
        $next_panel = $this->model_pictures->getNextPanel($this->data['fvalue']['album_id'],$this->session->data['current_panel']);
        if ($next_panel) $this->data['next'] = $next_panel;
        $prev_panel = $this->model_pictures->getPrevPanel($this->data['fvalue']['album_id'],$this->session->data['current_panel']);
        if ($prev_panel) $this->data['prev'] = $prev_panel;  
 
        //$prev_panel=http://www.emediadeal.com/pictures/al/33/page/1        
        //$this->data['next'] = $this->model_pictures->getNextPicture();
        //$this->data['prev'] = $this->model_pictures->getPrevPicture();

        // should go to: /pictures/al/{album}/page/{page}
 
        $cpanel = $this->session->data['current_panel'];
        if ($cpanel=='') $cpanel=$this->session->data['current_panel']=1;
 
        $this->data['current_panel'] =  NICE_ALBUM_LINK. $this->data['fvalue']['album_id']. '/page/'.$cpanel;
      
        // should go to: /albums/gl/{user_id or gallery_id}/herge
        // take the top link of navigation... is hard codes to 1 in model/pictures.php
        $this->data['albums'] = $this->data['navigation'][1]['link'];
 
        $this->load->model('gallery'); //MK          
        $userdata = $this->model_gallery->getAuthorFromAlbum($this->data['album_id']);
        
        $this->data['nickname'] = str_replace(' ','-',$userdata['nickname']);
       
        $this->data['fb_id'] = $userdata['fb_id'];
//print_array($this->data); 
         
        $this->data['al_title'] = str_replace(' ','-',$userdata['title']);
        $this->data['gallery_id'] = $userdata['emb_user_id'];

        $this->data['top_liked'] = '/top_liked';
               
        $this->data['config_thumb_width'] = $this->config->get('config_thumb_width');
        $this->data['config_thumb_height'] = $this->config->get('config_thumb_height');
        
        $this->data['config_large_width'] = $this->config->get('config_large_width');
        $this->data['config_large_height'] = $this->config->get('config_large_height');
        //
        $this->data['private'] = $this->access->check_private($this->data['album_id']);
        if (! $this->data['private']) {
            $this->data['like_href'] = HTTP_HOST.NICE_ALBUM_LINK.
                                        $this->data['album_id']."/".
                                        $this->data['al_title']; 
            } else {
             $this->data['like_href'] = '';
            }
           
        //          
        $this->template = $this->config->get('config_template') .'single.php';
        $this->render();

        //MK   print
        if (isset($this->request->request['mycomic']) && 
            $this->request->request['mycomic'] == 'print') {
                $this->print_mycomic($this->request->request,$userdata);
 
                }		
		//MK end print 
                 
    }
 
    function checkAccess() {
        
        $album_id = $this->request->post['album_id'];
        
        $access = $this->access->check($album_id,'read');
        if ($access === true) $access = 'ok';
               
        echo json_encode(array(  
                'access'=>$access,  
                ));          
        exit;
    }
    
    function singleEdit() {

        $data = array();
        $picture_id = $this->request->post['picture_id'];
        
        $data['title'] = $this->request->post['title']; 
        $data['description'] = $this->request->post['description'];
        
        $this->message = $this->model_pictures->updatePictureData($picture_id,$data);
         
        $this->result = 'ok';
               
            echo json_encode(array(  
                'result'=>$this->result,
                'title'=>$data['title'],
                'description'=>$data['description'],
                'message'=> $this->message   
                ));          
            exit;
    }
    
    private function print_mycomic($parms,$userdata) {
        
        $album_id = $parms['al'];
        $nickname = $userdata['nickname'];
        $title = $userdata['title'];
        $pdf_file = GALLERIA.'/pdf/'.$album_id.'.pdf';
        
        /* we may want to unlink the file if the album has been change */
        if (file_exists($pdf_file)) return;
         
 //print_array($this->picture_title); 
        $pages = $this->model_pictures->getAllPictures($album_id);
//print_array($pages);exit;
 
        $page_nbr = count($pages);
        $pageno = 1;
        if ($page_nbr > 0) {
   
            $this->load->library('class.ezpdf'); 
            $pdf =& new Cezpdf("LETTER");
 
            $pdf->ezSetMargins(72,30,50,30);
            $pdf->selectFont(DIR_SYSTEM."fonts/Helvetica.afm");
  
            $pattern = "$title - Copyright $nickname - page {PAGENUM} of {TOTALPAGENUM}";
            $pdf->ezStartPageNumbers(300,10,10,'right',$pattern,1);
            
            $opts = array('right' => 200,'spacing' => 1);
//$pdf->ezText(print_r($userdata,true)."\n",10,$opts); exit;

     
            foreach ($pages as $pge) {
                    
                $image = GALLERIA.'/large/'.$pge['image'];

                $top = 10; $left = 20; $width = 550;            
                $pdf->addJpegFromFile($image,$left,$top,$width); 

                if ($pageno != $page_nbr) $pdf->ezNewPage();    
                
                $pageno++;                
                } 

            //$pdf->ezStream();   
            $this->writePDF($pdf,$pdf_file);         
            }
    }  

    function writePDF($pdf,$pdf_file) {
    
        $pdfcode = $pdf->ezOutput();
        $fp=fopen($pdf_file,'wb');
        fwrite($fp,$pdfcode);
        fclose($fp);    
    }       
}