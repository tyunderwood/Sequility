<?php 
/**
***************************************************************************************************
 * @Software    Pin2Shop
 * @Author      Michel Kohon - michelk18@gmail.com
 * @Copyright   Copyright (c) 2012-. All Rights Reserved.
 * @License     GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://emediaboard.com. All Rights Reserved.
 **************************************************************************************************
**/
 
class ControllerAccountLogin extends Controller {
    function __construct() {
   
        $this->db = Registry::get('db');
        $this->id = 'content';
        $this->table = DB_PREFIX.'user';
        $this->load->model('users');
        $this->layout   = 'layout';
        $this->config = Registry::get('config');
 
    }    
        
    function index() {
 
        if ($this->request->get['mode'] == 'logoff') {
            $this->result = 'ok';
            $this->message = '';
             
            $this->user->logout();
 
            setcookie('zip',0,-1);
            
            echo json_encode(array(  
                'result'=>$this->result,
                'user_id'=> $this->session->data['user_id'],
                'user_zip'=>'',
                'user_name'=>$this->user->getUserName(),
                'message'=> $this->message   
                ));          
            exit;
            }
        
        if ($this->request->post['mode'] == 'checkLogged') {
       
            if (! $this->user->isLogged()) {
 
                $status = 'notloggedin';
 
                } else {
                $status = 'loggedin';
                }        
            echo json_encode(array(  'result'=>$status)); 
            exit;             
            }

            
        $this->email = trim($this->request->post['email']); 
        $this->pass = trim($this->request->post['password']); 
        $this->nick = trim($this->request->post['nickname']); 
      
        if ($this->request->post['mode'] == 'register') {
        
            if ($this->user->isLogged()) {
                $status = 'notloggedoff';
                echo json_encode(array( 'result'=>$status)); 
                exit; 
                }
                
            if ($this->user->isRegistered($this->email)) {
             
                $status = 'alreadyregistered';
                echo json_encode(array( 'result'=>$status)); 
                exit;             
            
                }   
                 
            $id = $this->user->register($this->email, $this->pass,$this->nick );
            $this->result = 'ok';
            echo json_encode(array(  
                'result'=>$this->result,  
                'email'=>$this->email,  
                //'pass'=>$this->pass,
                //'user_id'=> $this->session->data['user_id'],
                'nick_name'=>$this->nick  
                )); 
            exit;             
            }
                            
        if ($this->request->post['mode'] != 'login') {
            $this->redirect($this->config->get('config_site_dir').'/index.php');
            }            

        $this->result = 'ok';
                
        if (! $this->user->isRegistered($this->email)) {
             
            $status = 'notregistered';
            echo json_encode(array( 'result'=>$status)); 
            exit;             
            
            } 
        
        $fb = false;
        if ($this->pass == 'no-password-needed') $fb = true;
                       
        if (! $this->user->login($this->email, $this->pass,$fb)) {
            $this->result = 'notloggedin';
            }
            
        $user_zip = $this->user->getUserZip();
        if ($user_zip != '') {
            setcookie('zip',$user_zip );
            } else {
            $user_zip = $this->config->get('default_zip');
            }
        echo json_encode(array(  
            'result'=>$this->result,  
            'email'=>$this->email,  
            'user_zip'=>$user_zip,
            'store_id'=>$this->user->getStoreID(),      // should be zero if not merchant
            'store_zip'=>$this->user->getStoreZip(),    // should be zero if not merchant
            'twitter_token'=>$this->user->getTwitterToken(),
            'user_id'=> $this->session->data['user_id'],
            'nick_name'=>$this->user->getNickName()  
            )); 
        exit;         
    }
    function logoff() {
 //print_r($this->request->post);
 
        $this->result = 'ok';
        $this->message = '';
        $this->user->logout();
 
        echo json_encode(array(  
            'result'=>$this->result,
            'user_id'=> $this->session->data['user_id'],
            'user_name'=>$this->user->getUserName(),
            'message'=> $this->message 
            ));          
 
    }            
 

}
