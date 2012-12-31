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
 
class ControllerMysettings extends Controller {
 
    function __construct() {
 
        $this->db = Registry::get('db');
        $this->id = 'content';
         
        $this->load->model('users');
        //$this->load->model('pledges');
        $this->layout   = 'layout';
        $this->config = Registry::get('config'); 
 
    }    
       
    function index() {
 
        if (isset($this->session->data['user_id']) && 
            $this->session->data['user_id']) {
            $this->showAccount();
            } else {
            echo json_encode(array(  
                'result'=>'ok',           
                'user_id'=>0 
                ));          
            exit;            
            }
 
    }
 
    function showAccount() {
        
        $nickname = $this->user->getNickName();
                 
        $user_id = $this->session->data['user_id']; 
/*        
        $store_id = $this->user->getStoreID();

        $coupons = "Pledge ID# |Shop |Accepted Px |Status|Created |Expiration| Redeem| Action \n"; 
        $range = array('paid-coupon','paid');  
        $image = 'print.png';  
        $action_link = HTTP_HOST.
                "/?c=layout&user_id=$user_id&pledge_id={PLEDGE_ID}&store_id={STORE_ID}&origin=actives&action=pp_conclude&rid=0";
        
        $coupon_lines = $this->getPledges(1,$user_id,$range,$image,$action_link); 
        if ($coupon_lines == '') $coupon_lines = 'No coupon|||||||';
        $coupons .= $coupon_lines;
        
        if ($store_id != 0) {
            $image = '';
            $action = '';
            } else {
            $image = 'buy.png';
            $action = 'Action';
            }
          
        $pledges = "Pledge ID# |Shop |Bid |Offered |Status|Created |Expiration| $action \n"; 
        $range = array('open');
        
   
        $token = $this->user->getToken($user_id);
 
        $action_link = HTTP_HOST.
                "/?c=products&search_level=2001&album_id=4&picture_id={PRODUCT_ID}&zip={ZIP}".
                "&pledge_id={PLEDGE_ID}&cat_id={CAT_ID}&user_id=$user_id&token=$token&id={ID}";
        
        $pledge_lines = $this->getPledges(2,$user_id,$range,$image,$action_link); 
        if ($pledge_lines == '') $pledge_lines = "No pledge|||||||";
        $pledges .= $pledge_lines;
 */       
        echo json_encode(array(  
            'result'=>'ok',           
            'user_id'=>$user_id,
            'user_name'=>$this->user->getUserName(),
            'nickname'=>$nickname,
            'coupons'=>'0',
            'pledges'=>'0'  
            ));          
        exit;
    
    }

    function getPledges($mode,$user_id,$range,$image,$action_link) {
    
        $result = $this->model_pledges->getUserPledges($user_id);
        
        $pledges = '';
        foreach ($result as $pledg) {
            
            $zip = $pledg['zip'];
            $product_id = $pledg['product_id'];
            $cat_id = $pledg['category_id'];

            $id = $pledg['prod_id'];  // products_details id
    
            $start_price = $pledg['start_price'];
            $final_price = $pledg['final_price'];
             
            $status = $pledg['buy_status'];
            if (! in_array($status,$range)) continue; 
            
            $offer = sprintf("%01.2f",$final_price / 100);   
            $bid = sprintf("%01.2f",$start_price / 100);   
            
            $store_id = $pledg['store_id'];
            $pledge_id = $pledg['pledge_id'];

            $descriptor = $pledg['descriptor'];   
            
            $date_creation = substr($pledg['date_creation'],0,10);
            
            $date_expiration = substr($pledg['date_expiration'],0,10);
 
            $action_image = HTTP_SERVER."/app/view/".$this->config->get('config_template')."/images/$image";
 
            if ($action_image != '') $action = "<a href='$action_link' ><img src='$action_image'  /></a>";
            
            switch ($mode) {
                case 1:
                    $pledge_line = "$pledge_id |$descriptor |$offer |$status | $date_creation| $date_expiration | $date_redeem | $action \n";
                    break;
                case 2: 
                    $pledge_line= "$pledge_id |$descriptor |$offer |$bid |$status | $date_creation| $date_expiration | $action \n";
                    break;
                }
            
            $pledge_line = str_replace('{PLEDGE_ID}',$pledge_id,$pledge_line);
            $pledge_line = str_replace('{STORE_ID}',$store_id,$pledge_line);
            $pledge_line = str_replace('{CAT_ID}',$cat_id,$pledge_line);
            $pledge_line = str_replace('{ZIP}',$zip,$pledge_line);
            $pledge_line = str_replace('{PRODUCT_ID}',$product_id,$pledge_line);
            $pledge_line = str_replace('{ID}',$id,$pledge_line);
 
            $pledges .= $pledge_line;
            
            }
            
        return $pledges;
    }
 
}
