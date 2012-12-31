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

class ControllerLayout extends Controller {
    protected function index() {
 
        $this->db = Registry::get('db');
 
        $this->table = DB_PREFIX.'user';
        $this->load->model('users');
        $this->load->helper('common');

        //$this->load->helper('gallery');
        
        $this->load->model('pictures');  //MK

        //$this->gl_helper = new HelperGallery();
     
        $this->load->model('gallery');  //MK
               
        $this->session = Registry::get('session');

        $this->data['wait_image'] = HTTP_HOST.'/pictures/wait_100.gif';
      
        $this->data['config_site_url'] =  $this->config->get('config_site_url');
        $this->site_title =  $this->config->get('config_site_title');
      
        // set default system zip for now
        $this->data['default_zip'] = $this->config->get('default_zip');
        
        // google analytics setup
        $google_analytics_code = $this->config->get('config_google_analytics_code'); 
 //echo $google_analytics_code;        
        if ($google_analytics_code === 0) { 
            $google_code = '<script>var google_analytic_status="no google analytics code";</script>';
            } else {
            $google_code = $this->get_google_analytics_code($google_analytics_code);
            }
        $this->data['google_analytics_code'] = $google_code;
        //
        $this->data['google_plus'] = $this->get_google_plus();
        
        $this->data['fb_app_id'] = FB_APP_ID;
        //echo $this->request->request['signed_request'];
 
        $this->data['background_images'] = $this->load_background_images();
        if ($this->request->request['signed_request']) {
             // facebook login and/or registration
             
            if ($this->request->request['signed_request'] != 'connected'){
                $response = $this->parse_signed_request($this->request->request['signed_request'],FB_APP_SECRET);
// print_array($response);
                /*
                getting back    [registration][name]
                            [registration][email]
                            [user_id]
                          
                */
            $parray = array();
            $rs = 'https://graph.facebook.com/oauth/access_token?client_id='.FB_APP_ID.
                  '&client_secret='.FB_APP_SECRET.
                  '&grant_type=fb_exchange_token&fb_exchange_token='.$response['oauth_token'];
              
                $res = secureCurl($parray,$rs);  
                $sx = explode("$^$",$res);
                $err = $sx[0]; 
 
                if ($err == 0) {
                    // not sure why I am getting an extended expiration date token
                    // was to avoid registring the app everytime but it's not working
                    // may be the facebook language is wrong and everything is fine? 
                    $new_token = str_extract($sx[1],'access_token=', '&expires');
//echo $new_token;                   
                    }
 
                $fb_id = $response['user_id'];
                $username = $response['registration']['email']; 
                $nickname = $response['registration']['name'];
                } else {
                $fb_id = $this->request->request['fbid']; 
                $username = $this->request->request['fbu']; 
                $nickname = $this->request->request['fbn'];     
 
                }
                
            if (! $this->user->isRegistered($username)) {
                $this->user->register($username, 
                                        'facebook', // will be encrypted
                                        $nickname,
                                        $fb_id);             
                } 
     
            if ($this->user->login($username,'no-password-needed',true)) {
                $user_id = $this->user->getId();
                }
                
            if ($user_id) $this->session->data['user_id'] = $user_id;
        
            }

        if (isset($this->session->data['user_id']) && $this->session->data['user_id']) {
            $nickname = $this->user->getNickName();
       
            $this->data['settings'] = "Account Settings: $nickname";
            $username = $this->user->getUserName(); // that's an email
            
            // settings update
            if (isset($this->request->request['mode'])) {
                //print_array($this->request->request); 
                if ($this->request->request['mode'] == 'set_author' )  {
                    $this->set_author($username);
                    }
                      
                if ($this->request->request['mode'] == 'admin_msg') {
                    $this->send_admin_msg($username);
                    }  
                                                    
                if ($this->request->request['mode'] == 'upd_pass' && 
                    isset($this->request->request['r_password1']))  {
                    $this->set_password($username);
                    }

                if ($this->request->request['mode'] == 'upd_account' && 
                    $this->request->request['user_zip'] != '')  {
                    $this->model_users->updateUserZip($this->session->data['user_id'],
                                                      $this->request->request['user_zip']);     
                                   
                    unset($this->request->request['mode']);
                    }
                                        
                if ($this->request->request['mode'] == 'share_page') {
                    $emails = $this->request->request['emails'];
                    $memo = $this->request->request['memo'];
                    $share_url = $this->request->request['share_url']; 
                    $user_id = $this->request->request['user_id'];
                    $album_id = $this->request->request['album_id'];
                    $date_expiration = $this->request->request['date_expiration'];
                    
                    $this->sendSharemail($emails,$memo,$nickname,$share_url,$album_id,$date_expiration,$user_id);
                    }  
  
                } 
            // twitter
            $twitter_token = $this->user->getTwitterToken();
 
            if ($twitter_token == '') {
            
                // getting back the merchant's screen name if app registration worked
                // meaning... api works, merchnat approved app
                
                //$twitter_token = $this->process_twitter_registration();
          
                unset($this->session->data['access_token']);
                 
                }  
        
            $this->session->data['twitter_token'] = $twitter_token;
 
            } else {
            //$this->data['settings'] = 'Register';
            //$this->data['more_settings'] = ' as an author';
            //
            $this->data['settings'] = 'Guest user';
            $this->data['more_settings'] = '';

            }
     
        $search_level = 0;
        $cat_id = 0; 
        $mode = 'cats';
   
        if (isset($this->request->request['search_level']) && 
            is_numeric($this->request->request['search_level'])) {
            $search_level = $this->request->request['search_level'];
            } else {
            $search_level = 0;
            }  
 //print_array($_REQUEST); exit;  
        $this->data['var_search_level'] = "var search_level=$search_level; \n";   // define a js variable

        if (isset($this->request->request['cat_id']) && 
            is_numeric($this->request->request['cat_id'])) {
            $cat_id = $this->request->request['cat_id'];
            } else {
            $cat_id = 0;
            } 
// echo 'cat='.$cat_id; exit;
        $this->data['var_cat_id'] = "var cat_id='$cat_id'; \n";   // define a js variable

        if (isset($this->request->request['mode'])) {
            $mode = $this->request->request['mode'];
            } else {
            $mode = 'cats';
            } 
 
        $this->data['var_mode'] = "var mode='$mode'; \n";   // define a js variable
 
        if (isset($this->request->request['zip'])) {
            $zip = $this->request->request['zip'];
            $this->session->data['zip'] = $zip; // store zip used in search
            } else {
            $zip = $this->data['default_zip']; // system default zip
            } 
 
        $this->data['var_zip'] = "var zip='$zip'; \n";   // define a js variable
 
        if (isset($this->request->request['q_text'])) {
            $q_text = $this->request->request['q_text'];
            $this->session->data['q_text'] = $q_text;  
            } else {
            $q_text = '';  
            } 
 
        $this->data['var_q_text'] = "var q_text='$q_text'; \n";   // define a js variable
        
                
        if (isset($this->request->request['radius'])) {
            $radius = $this->request->request['radius'];
            $this->session->data['radius'] = $radius; // store radius used in search
            } else {
            $radius = 0;  
            } 
 
        $this->data['var_radius'] = "var radius='$radius'; \n";   // define a js variable
 
        if (isset($this->request->request['picture_id'])) {
            $picture_id = $this->data['picture_id'] = $this->request->request['picture_id'];
            } else {
            $picture_id = $this->data['picture_id'];
            }

        if ($picture_id) {
            $pvalue = $this->model_pictures->getPicture($picture_id);  
            $this->data['page_title'] = $pvalue['title'];
            $this->data['page_desc'] = $pvalue['description'];
            } 

        $this->data['var_picture_id'] = "var picture_id='$picture_id'; \n";   // define a js variable
        
        if (isset($this->request->request['pledge_id'])) {
            $pledge_id = $this->request->request['pledge_id'];
            } else {
            $pledge_id = 0;
            } 

        if (isset($this->request->get['fb_feed'])) {
            $pledge_id = $this->request->get['fb_feed'];
            // ?c=products&album_id=4&picture_id=10764360&zip=78741&cat_id=506
            // need also to make sure we hook up w/ pledge creator
            
            $this->load->model('pledges');
            $pledge_row = $this->model_pledges->getPledge($pledge_id);  
//print_array($pledge_row); exit;
            $product_id = $pledge_row['product_id'];
            $zip = $pledge_row['zip'];
            $cat_id = $pledge_row['category_id'];
            $creator_id = $pledge_row['creator_id'];
            
            $redirect = "index.php?c=products&album_id=4&picture_id=$product_id".
                        "&zip=$zip&cat_id=$cat_id&active_pledge_id=$pledge_id";
             
            $this->redirect($redirect);
            exit;                  
            }  
             
        if (isset($this->request->request['origin']) &&
            $this->request->request['origin'] == 'actives') {
           
            $origin = $this->request->request['origin'];
            $pledge_id = 0;
            
            if (isset($this->request->request['pledge_id']) &&
                isset($this->request->request['rid'])) {
                
                if (isset($this->request->request['action'])) {
                    $this->load->model('pledges');
                    
                    $action = $this->request->request['action'];
                    switch ($action) {
                        case 'pp_confirm':
                            // coming from single.php to confirm the pledge
                            //$this->confirmPledge($this->request->request); 
                             break;
                        case 'pp_conclude':
                       
                            // coming from single.php to confirm the pledge
                            //$this->print_coupon($this->request->request); 
                            break;                                               
                        }

                    
                    } else {
                    /* /create or add pledge 
                    $zip = $this->session->data['zip'];
                    $pledge_details = $this->createPledge(  $zip,
                                                            $this->request->request['pledge_id'],
                                                            $this->request->request['active_pledge_id']);
                    */
                    }
                $pledge_id = 0;               
  
                } 
          
            } else {
            if (isset($this->request->request['origin'])) {
                $origin = $this->request->request['origin'];
                } else {
                if (! $this->session->data['user_id'] ||
                    $this->session->data['admin_level'] == 0) {
                    $origin = 'new_albums';
                    } else {
                    $origin = 'author_albums';
                    
                    } 
           
                }
 
            }
             
        $this->data['var_pledge_id'] = "var pledge_id='0'; \n";   // define a js variable

        $this->data['var_used_pledge_id'] = "var used_pledge_id='$used_pledge_id'; \n";   // define a js variable
 
        if (isset($this->session->data['user_id']) && $this->session->data['user_id'] != 0) {
                
            $user_id = $this->session->data['user_id'];
            $this->data['var_user_id'] = "var logged_user_id='$user_id'; \n";   // define a js variable
            } else {
            
            $user_id = 0;
            $this->data['var_user_id'] = "var logged_user_id='0'; \n";   // define a js variable
            }

        $manager_store_id = $this->session->data['manager_store_id'];
        if (isset($this->session->data['manager_store_id']) && $this->session->data['manager_store_id'] != 0) {
            $this->data['var_manager_store_id'] = "var manager_store_id='$manager_store_id'; \n";   // define a js variable
            } else {
            $this->data['var_manager_store_id'] = "var manager_store_id='0'; \n";   // define a js variable
            } 

        $admin_level = $this->session->data['admin_level'];
        
        if (! isset($admin_level) || $admin_level == '') $admin_level = 0;
        $this->data['var_admin_level'] = "var admin_level='$admin_level'; \n";   // define a js variable
                        
        $this->data['var_origin'] = "var origin='$origin'; \n";   // define a js variable
        //echo $this->data['var_origin']; exit;          
        $this->data['title'] = $this->config->get('config_site_title');
        $this->data['description'] = $this->config->get('config_site_description');
        $this->data['template'] = HTTP_SERVER."/app/view/".$this->config->get('config_template'); 
        
        $this->data['background_pattern'] = HTTP_IMAGE.'/'.$this->config->get('config_background_image');
        //echo HTTP_IMAGE.'/'.$this->config->get('config_background_image'); exit;
        
        // result should be: http://www.textility.org/pictures/14/t_textility.png               
        $this->data['logo'] = HTTP_IMAGE.'/'.$this->config->get('config_logo');
        $this->data['config_icon'] = HTTP_IMAGE.'/'.$this->config->get('config_icon'); 
        
        $this->data['config_sign_in_msg'] = $this->config->get('config_sign_in_msg'); 
        $this->data['call_to_action'] = $this->config->get('config_call_to_action'); 
        $this->data['masthead_color'] = $this->config->get('config_masthead_color');
        
        $domain = '';
        if (DOMAIN_OWNER != 0) $domain = DOMAIN_OWNER.'/';
        $this->data['welcome_image'] = ASSET_PICTURES.'/'.$domain.$this->config->get('config_welcome_image');
        
        $this->shrink_jpeg($this->data['welcome_image']);
        
        //
        if ($this->config->get('config_show_cats') == '1') {
            // get all domains

            $this->data['cats'] = $this->prepCategories();
            }  
        //         
        $this->data['config_dynamic_ajax'] = $this->config->get('config_dynamic_ajax');
        //echo $this->config->get('config_dynamic_ajax'); exit; 
        //paypal stuff
        $this->data['pp_flow_pay'] = $this->config->get('pp_flow_pay');
        $this->data['config_paypal_email'] = $this->config->get('config_paypal_email');
       
        //$this->data['pp_flow_pay'] = 'https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay';
        if (isset($this->request->request['payKey'])) $this->data['pp_paykey'] = $this->request->request['payKey'];
 
 	    // here we shl have something like: $this->data['label']['pledges'] = fx('pledges')
 	    // which will call $this->config->get('locale') to get the label in the language
 	    $this->data['label']['pledges'] = "Pledges";
        
        $this->data['site_info'] = $this->prep_site_info();   
        $this->data['store_info'] = $this->prep_store_info();
        $this->data['about'] = $this->prep_site_about();
        
        $this->data['config_thumb_width'] = $this->config->get('config_thumb_width');
        $this->data['config_thumb_height'] = $this->config->get('config_thumb_height');
        
        $this->data['config_large_width'] = $this->config->get('config_large_width');
        $this->data['config_large_height'] = $this->config->get('config_large_height');
        
        if ($this->request->request['album_id']) $this->data['album_id'] = $this->request->request['album_id']; 
        if ($this->request->request['gallery_id']) $this->data['gallery_id'] = $this->request->request['gallery_id']; 

        $this->data['header_expand_btn'] = $this->config->get('header_expand_btn'); 
        if ($this->config->get('config_show_left_menu') == 1) {
            $this->data['left_menu'] = '<a class="sliding-trigger" href="#">Menu</a> ';
            }
//print_array($this->data); exit;
        $this->template = $this->config->get('config_template') . 'layout.php';                
        $this->render(); 
    }
    
    private function prepCategories() {
     
        $rows = $this->model_gallery->getAlbumsCat();
        $cats = '';
 //print_r($rows);       
        foreach ($rows as $album_row) {
            if ($album_row['category_id'] == 0) {
                $href = HTTP_HOST;
                } else {
                $href = HTTP_HOST.'/cat/'.$album_row['category_id'];
          
                }
                
            $cats .= '<li><a href="'. $href .'" >' . $album_row['title'] . '</a></li>';
            }
                
        return $cats;
            
    }
    
    private function print_coupon($get) { }
     
    private function createPledge($zip,$pledge_id,$active_pledge_id) { }
    
    
    function process_twitter_registration() { }

    function get_bids($user_id,$pledge_id) { }

    function sendContactMail($type,$fvalue) {
 
        $recip = $this->config->get('config_email');
            
        $email = $fvalue['username'];
        $name = $fvalue['name'];
        $gallery_id = $fvalue['gallery_id'];

        $mail = new Mail();
        
        $mail->setFrom($email);
        
        $sender = $this->site_title;
        $mail->setSender($sender);

        $mail->setSubject("Contact Message");
 
        $message = $fvalue['message'];
        
        $mail->setTo($recip);    
        $mail->setHtml("<p style='color:blue;' >$message</p>");
        $mail->send();  
                
    }
      
    function sendAdminMail($type,$fvalue) {
        
        if ($type !='new_author') return;
        
        $from_email = $this->config->get('config_email');
            
        $email = $fvalue['username'];
        $name = $fvalue['name'];
        $gallery_id = $fvalue['gallery_id'];

        $mail = new Mail();
        
        $mail->setFrom($from_email);
        
        $sender = $this->site_title;
        $mail->setSender($sender);

        $mail->setSubject("New Author");
 
        $recip = $from_email;
        $message = "<p>A new author is now registered:<br/>$name [$email]<br/>user/gallery: $gallery_id</p>";
        
        $mail->setTo($recip);    
        $mail->setHtml("<p style='color:blue;' >$message</p>");
        $mail->send();  
        
        // now send to new author too

        $mail->setSubject("Welcome! You can now upload your comics.");
 
        $message = "<p>You are now registered as:<br/>$name [$email]<br/>user/gallery: Your gallery ID# is $gallery_id</p>";
         
        $mail->setTo($email);    
        $mail->setHtml("<p style='color:blue;' >$message</p>");
        $mail->send(); 
                       
    }
    
    function sendShareMail($emails,$memo,$nickname,$share_url,$album_id,$date_expiration,$user_id) {
//print_array($this->request->request);        
        $this->load->model('products');
        
        $this->load->helper('access');
        $this->access = new HelperAccess(); 
                
        if (trim($memo) == '') $memo = "Hey, check here what I have found that may interest you.";
        $mail = new Mail();
        
        $from = $this->config->get('config_email');
        
        $mail->setFrom($from);
        $mail->setSender(SITE_TITLE);
    
        $recipients = explode(',', $emails);
        
        $image = $master['image'];
        
        foreach ($recipients as $recip) { 
 
            // create entry in table albums_token 
            //if album is private
             
            $private = $this->access->set_read_token($album_id,$recip,$date_expiration);
            //                  
            $link = "<a href='$share_url' >Please, check this album</a>"; 

            $logo = $this->config->get('config_logo'); 
     
            if ($private) $memo .= '<p>Your access token is: '.$recip.'</p>';
            
            $message = "<memo><img src='$logo' /><p>$memo</p><p>$share_url</p></memo>";
            
            $mail->setSubject("A ".SITE_TITLE." friend is sharing an album");
          
            if (strpos($recip,'@emediaboard.com') !== false) {
                // all test users email go to webmaster@emediaboard.com
                $recip = 'webmaster@emediaboard.com';
                }
                
            $mail->setTo($recip);    
            $mail->setHtml("<p style='color:blue;' >From $nickname: $message</p>");
            $mail->send();        
            }
            
        $this->redirect($share_url);
    } 
    
    function prep_store_info() {
        
        $store_info = '';
        $store_info .= "<p>Terms and Conditions...</p>";                               
        return $store_info;
    }
    
    function prep_site_news() {
        
        $news_name = DIR_SYSTEM."config/".DOMAIN_OWNER ."/".NEWS_FILE;
            
        if (file_exists($news_name)) return $this->format_help($news_name);
        
        $news_name = DIR_SYSTEM."config/".NEWS_FILE;
        
        return $this->format_help($news_name);       
    
    }
    
    function prep_site_about() {
        
        $about_name = DIR_SYSTEM."config/".DOMAIN_OWNER ."/".ABOUT_FILE;
            
        if (file_exists($about_name)) return $this->format_help($about_name);
        
        $about_name = DIR_SYSTEM."config/".ABOUT_FILE;
        
        return $this->format_help($about_name);              
    }
    
    function prep_site_info() {
 
        if (DOMAIN_OWNER != 0) {
        
            $author_name = DIR_SYSTEM."config/" .DOMAIN_OWNER ."/".AUTHORS_HELP;
            $visitor_name = DIR_SYSTEM."config/".DOMAIN_OWNER ."/".VISITORS_HELP;
      
            if ($this->session->data['admin_level'] == AUTHOR_LEVEL &&
                file_exists($author_name)) {
                return $this->format_help($author_name); 
                }
   
            if (file_exists($visitor_name)) return $this->format_help($visitor_name);       
          
            }

        $author_name = DIR_SYSTEM."config/".AUTHORS_HELP;
        $visitor_name = DIR_SYSTEM."config/".VISITORS_HELP;
          
        if ($this->session->data['admin_level'] == AUTHOR_LEVEL) {
            return $this->format_help($author_name); 
            }
   
        return $this->format_help($visitor_name);
 
    } 
    
    function format_help($filename) {
    
        $xml_array = file($filename);
        return implode("<br/>",$xml_array);
    }
    
    function parse_signed_request($signed_request, $secret) {
 
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);
 
        // decode the data
        $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
 
        $data = json_decode(base64_decode((strtr($payload, '-_', '+/'))), true);
        return $data;
        
    }
	
	public function captcha() {
	
		$this->load->library('captcha');
		
		$captcha = new Captcha();
		
		$this->session->data['captcha'] = $captcha->getCode();
		
		$captcha->showImage();
	}
 
    private function set_password($username) {
                     
        $data = array('user_id'=>$this->session->data['user_id'],
                                  'password'=>$this->request->request['r_password1'],
                                  'username'=>$username);
                                 
        $this->model_users->saveUser($data);
        unset($this->request->request['mode']);
    }
                    
    private function set_author($username) {
                    
        if (!isset($this->session->data['captcha']) || 
            ($this->session->data['captcha'] != $this->request->post['captcha'])) {
            unset($this->request->post['captcha']);
            unset($this->session->data['captcha']);
                         
            $this->redirect("index.php?captcha=error"); 
            exit; 
            }

        $fvalue = array('gallery_id'=>$this->session->data['emb_user_id'],
                                    'name'=>$this->request->request['name'],
                                    'username'=>$username,
                                    'albums'=>0,
                                    'image'=>'',
                                    'sortorder'=>0,
                                    'type'=>6);
                                     
        $this->model_gallery->createGallery($fvalue); 
                
        $this->model_users->updateAdminLevel($this->session->data['user_id'],
                                             AUTHOR_LEVEL,
                                             $this->request->request['name']); 
                    
        $this->sendAdminMail('new_author',$fvalue);
                        
        unset($this->request->request['name']);
                   
        $location = "admin/index.php?c=gallery/edit&gallery_id=".$this->session->data['emb_user_id'];
                    
                    
        $this->redirect($location);
        exit;    
    }
    
    private function send_admin_msg($username) {
        //print_array($this->request->post); 
        //print_array($this->session->data);
        //exit;                    
        if (!isset($this->session->data['captcha']) || 
            ($this->session->data['captcha'] != $this->request->post['captcha'])) {
            unset($this->request->post['captcha']);
            unset($this->session->data['captcha']);
                         
            $this->redirect("index.php?captcha=error"); 
            exit; 
            }
        
        $message = $this->request->post['admin_msg_text'];
    
        $fvalue = array('gallery_id'=>$this->session->data['emb_user_id'],
                                    'name'=>$this->request->request['name'],
                                    'username'=>$username,
                                    'message'=>$message);
            
        $this->sendContactMail('admin_msg',$fvalue);    
    }
    
    private function get_domain_images($domain_owner,$hostname) {
 
        $min_width = $this->config->get('config_large_width');
        if ($min_width == 0) $min_width = MIN_BODY_WIDTH; // should not be possible...but never know
 //echo $domain_owner; exit;       
        $rows =  $this->model_gallery->getDomainAlbums($domain_owner,$min_width); 
 //print_array($rows); exit;

        $curdir = realpath (getcwd()); 
        if ($hostname != '') {
            $targetdir = $curdir.'/'.ASSET_PICTURES."/background/$hostname";
            } else {
            $targetdir = $curdir.'/'.ASSET_PICTURES.'/background';
            }

        $images = array();
        foreach ($rows as $val) {
         
            $images[] = $val['image'];
            $targetfile = $targetdir.'/'.$val['image'];
//die(DIR_GALLERIA.'/large/'.$val['image']);            
            if (file_exists($targetfile)) continue;
            if (! file_exists($targetdir)) @mkdir($targetdir,0755,true); 
            
            @copy(DIR_GALLERIA.'/large/'.$val['image'],$targetfile);
 
            $this->shrink_jpeg($targetfile,70); 
 
            }
            
        return $images;
    }
    
	private function load_background_images() {

        $curdir = realpath (getcwd()); 
 
         if (DOMAIN_OWNER != 0) {
            $images = $this->get_domain_images(DOMAIN_OWNER,SHORT_HOSTNAME); 
            } else { 
            $images = $this->get_domain_images(0,''); 
            }
            
        if (count($images) == 0) return '|'; 
// echo implode('|',$images); exit;
        if (count($images) == 1) $images[] = $images[0];
        
        return implode('|',$images);
    
    }
 
    
    function get_google_analytics_code($google_analytics_code) {
    
        $google_code = "
            <script type='text/javascript' charset='utf-8'>
   
            var _gaq = _gaq || [];
            _gaq.push(['_setAccount', '$google_analytics_code']);
            _gaq.push(['_trackPageview']);

            (function() {
                var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
                ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
            })();
            </script>  "; 
        
        return $google_code;            
    }   
    
    function get_google_plus() {
    
        //<!-- Place this tag where you want the +1 button to render. -->
        $google_plus = "<div class='g-plusone' 
                            data-size='medium' 
                            data-annotation='inline' 
                            data-href= '".HTTP_HOST."' 
                           
                            data-width='300'></div>";
 

        //<!-- Place this tag after the last +1 button tag. -->
        $google_plus .= "<script type='text/javascript'>
                        (function() {
                            var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                            po.src = 'https://apis.google.com/js/plusone.js';
                            var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                        })();
                        </script>";
        return $google_plus;    
    }
    
    function shrink_jpeg($jpegfile,$quality=80) {
 
        $size = filesize($jpegfile); 
        if ($size > 100000) {
            $info = getimagesize($jpegfile);
            $image = imagecreatefromjpeg($jpegfile);
            imagejpeg($image,$jpegfile, $quality);
            }
    }
}
