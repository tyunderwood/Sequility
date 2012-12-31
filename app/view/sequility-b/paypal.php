<?php
echo "<html><head><title>Digital Goods Tester</title></head><body><p>Digital Goods 25.00 transaction:</p>";
echo " <script src ='https://www.paypalobjects.com/js/external/dg.js' type='text/javascript'></script>
<form action='https://www.sandbox.paypal.com/webapps/adaptivepayment/flow/pay' target='PPDGFrame'>
      <input id='type' type='hidden' name='expType' value='light'>
      <input id='paykey' type='hidden' name='payKey' value='" . $_REQUEST['payKey'] . "'>
      <input type='submit' id='submitBtn' value='Pay with PayPal'>
<script>
    var dg = new PAYPAL.apps.DGFlow({
        // the HTML ID of the form button
        trigger: 'submitBtn'
    });
</script>
</form>";
echo "</body></html>";
