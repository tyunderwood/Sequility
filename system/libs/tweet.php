<?php
final class Tweet {
	
	protected $consumer_key;
	protected $consumer_secret;
	protected $accessToken;
	protected $accessTokenSecret;
    protected $credentials;
    protected $oauth;
    
	public function __construct($user_id,$consumer_key,$consumer_secret,$accessToken='',$accessTokenSecret='') {
 		
		$this->config = Registry::get('config');
		$this->session = Registry::get('session');
 		
        $this->consumer_key = $consumer_key;
		$this->consumer_secret = $consumer_secret;
		$this->accessToken = $accessToken;
		$this->accessTokenSecret = $accessTokenSecret;

        include_once DIR_SYSTEM.'twitter/twitteroauth.php'; 

        if ($accessToken == '' && $accessTokenSecret == '' && $user_id != 0) {
            /* Build TwitterOAuth object with client credentials. */
            $this->oauth = new TwitterOAuth($consumer_key, $consumer_secret);

            /* Get temporary credentials. */
            $call_back_url = $this->config->get('config_site_url')."/?twitter_reg=step_2&user_id=$user_id";
            $request_token = $this->oauth->getRequestToken($call_back_url);

            if ($this->oauth->http_code != 200) return null; 
                        
            if (! $request_token['oauth_callback_confirmed']) return null;

            /* Save temporary credentials to session. */
            $this->session->data['oauth_token'] = $token = $request_token['oauth_token'];
            $this->session->data['oauth_token_secret'] = $request_token['oauth_token_secret'];    

            $url = $this->oauth->getAuthorizeURL($token);
            // $url=https://api.twitter.com/oauth/authenticate?oauth_token=1r5zhgt2DxElmxxxxxxxxvP7bp6ZRxDG5lNOIIQ
 
            header('Location: ' . $url);        // go to tweeter
            exit;
                           
            } else {
            
            $this->oauth = new TwitterOAuth($consumer_key,$consumer_secret, $accessToken, $accessTokenSecret);
            
            if ($user_id == 0) return $this->oauth; // normal user tweet
            
            // step_3 of merchant's registration
          
            $oauth_verifier = $_REQUEST['oauth_verifier'];  // do not use $this->request->get here
          
            $access_token = $this->oauth->getAccessToken(null,$oauth_verifier);
          
            if ($this->oauth->http_code != 200) return null; 
            
            /* Save the access tokens. Normally these would be saved in a database for future use. */
            $this->session->data['access_token'] = $access_token;     
            /* Remove no longer needed request tokens */
            unset($this->session->data['oauth_token']);
            unset($this->session->data['oauth_token_secret']); 
            
            return $this->oauth;
            
            }

	}
	
    public function tweet($msg) {
    
        /*
        How all this works? What the merchant does:
        
        1. Create a twitter account
        2. Go to: https://dev.twitter.com/apps and create a new app
        3. In Settings, selects: Read, Write and Access direct messages 
        4. Clicks the "update" button
        5. In Details, click on "recreate your access token"
            make sure you have a dummy call back URL
            you must cliecj re-create if you change the read,write,direct messages settings
        6. In your store Settings, select the Twitter tab and enter the consumer key, secret, token request and secret
   
        directory created:
            system\twitter    
            
        files added:
            system\twitter\twitteroauth.php
            system\twitter\OAuth.php
            
        */
 
        //echo "Connected as @" . $credentials->screen_name;
        // Post our new "hello world" status
        //$statuses = array('screen_name'=>$target,'text' => $msg,'wrap_links' => true);
        //$response = $this->oauth->post('direct_messages/new', $statuses );
 
        $response = $this->oauth->post('statuses/update', array('status' => $msg,'wrap_links' => true));
      
 //var_dump($response); exit;
    } 
   
}   // end class - all private functions for this class must be inside
 
