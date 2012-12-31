<?php
/**
 *index.php
 * 
 *This is for Digital Goods testing
 *
 */

echo "<html><head><title>Digital Goods Tester</title></head><body><p>Digital Goods 25.00 transaction:</p>";
//Setup Request Headers:
//THIS IS WHERE YOU WOULD PLACE YOUR API CREDENTIALS AND APP ID
///************************************//
//Please note this is not considered Secure//
//You would want to store your credentials encrypted//
//and in another file or database//
//***************************************************//
 //setup headers with creds:
 $headers[0] = "Content-Type: text/namevalue"; // either text/namevalue or text/xml
    $headers[1] = "X-PAYPAL-SECURITY-USERID: emedia_1333284296_biz_api1.gmail.com";//API user
    $headers[2] = "X-PAYPAL-SECURITY-PASSWORD: 1333284321";//API PWD
    $headers[3] = "X-PAYPAL-SECURITY-SIGNATURE: APEFwRA0gRPYIhDJWEl20VERi6VkAZsOzGvhTKwNzKNYSYPgJ7FgiPlg ";//API Sig
    
    // that's the paypal samdbox app id.. get one before going to prod when site is ok
    $headers[4] = "X-PAYPAL-APPLICATION-ID: APP-80W284485P519543T";//APP ID
 $headers[5] = "X-PAYPAL-REQUEST-DATA-FORMAT: NV";//Set Name Value Request Format
 $headers[6] = "X-PAYPAL-RESPONSE-DATA-FORMAT: NV";//Set Name Value Response Format


$endpoint = "https://svcs.sandbox.paypal.com/AdaptivePayments/Pay";
echo "<br />Endpoint: " . $endpoint . "<br />";
echo "<br />Headers: <br />";
print_r($headers);
echo "<br />";
$api_str = requestPayKey();
echo "<br />Request String: " . $api_str . "<br />";
//make the API Call and echo out the headers and the request string
$response = PPHttpPost($endpoint, $api_str, $headers);
echo "<br /><br />Response: <br />";
print_r($response);
//parse the response
$response_ar = explode("&", $response);
$p_response = parseAPIResponse($response_ar);

//for now
print_r($p_response);
//Build the HTML Form
echo " <script src ='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<form action='https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay' target='PPDGFrame'>
      <input id='type' type='hidden' name='expType' value='light'>
      <input id='paykey' type='hidden' name='payKey' value='" . $p_response['payKey'] . "'>
      <input type='submit' id='submitBtn' value='Pay with PayPal'>
<script>
    var dg = new PAYPAL.apps.DGFlow({
        // the HTML ID of the form button
        trigger: 'submitBtn'
    });
</script>
</form>";
echo "</body></html>";
 function parseAPIResponse($response){
$parsed_response = array();
foreach ($response as $i => $value) 
 {
  $tmpAr = explode("=", $value);
  if(sizeof($tmpAr) > 1) {
   $parsed_response[$tmpAr[0]] = $tmpAr[1];
  }
 }

 return $parsed_response;
 }
function requestPayKey(){
//request token string:
$reqstr = "actionType=PAY&cancelUrl=http://www.paypal.com&currencyCode=USD&returnUrl=http://www.pin2shop.com&requestEnvelope.errorLanguage=en_US&receiverList.receiver(0).amount=25.00&receiverList.receiver(0).email=emedia_1333284296_biz@gmail.com";
return $reqstr;
}
function PPHttpPost($my_endpoint, $my_api_str, $headers){
 // setting the curl parameters.
 $ch = curl_init();
 curl_setopt($ch, CURLOPT_URL, $my_endpoint);
 curl_setopt($ch, CURLOPT_VERBOSE, 1);
 curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
 //curl_setopt($ch, CURLOPT_HEADER, 1); // tells curl to include headers in response, use for testing
 // turning off the server and peer verification(TrustManager Concept).
 curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
 curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
 curl_setopt($ch, CURLOPT_POST, 1);
 // setting the NVP $my_api_str as POST FIELD to curl
 curl_setopt($ch, CURLOPT_POSTFIELDS, $my_api_str);
 // getting response from server
 $httpResponse = curl_exec($ch);
 if(!$httpResponse)
 {
   $response = "$API_method failed: ".curl_error($ch)."(".curl_errno($ch).")";
   return $response;
 }

 return $httpResponse;
}
?>
