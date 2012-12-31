<?php echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<!-- deploy\app\view\sequility\layout.php 
appears to pull from root settings.php
-->
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en" xml:lang="en">
<head>
<title><?php echo $title; ?></title>
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<base href="<?php echo $base; ?>" />
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="<?php echo $tpath; ?>css/style.css" />
<style type="text/css">
<!--
body {
 	
	margin-left: 0px;
	margin-top: 0px;
	margin-right: 0px;
	margin-bottom: 0px;
	background-repeat: repeat-x;
}
-->
</style>
    
<!--link rel="stylesheet" href="<?php echo $tpath; ?>css/emb/smoothness/jquery-ui-1.8.18.custom.css" type="text/css" /--> 
 <link rel="stylesheet" type="text/css" media="screen" href="http://www.sequility.com//app/view/sequility/css/jqGrid/jquery-ui-1.8.1.custom.css" />
<link rel="stylesheet" href="http://www.sequility.com//app/view/sequility/css/style.css?v=1355450676" type="text/css" media="screen" />

<script type="text/javascript" src="<?php echo $tpath; ?>/js/jquery-1.7.1.min.js"></script>
<script type="text/javascript" src="<?php echo $tpath; ?>/js/custom.js"></script>
<script type="text/javascript" src="<?php echo $tpath; ?>js/emb/jquery-ui-1.8.18.custom.min.js"></script> 
<script type="text/javascript" src="<?php echo $tpath; ?>js/emb/jQuery_blockUI.js"></script>
<?php require_once('http://sequility/deploy/app/view/sequility/ajax.js.inc'); 
$tab= substr($_REQUEST['c'],0, 4); 
?>
</head>
<body style="font-family:Verdana, Arial, Helvetica, sans-serif; font-size:11px;margin-top:0;padding-top:0">
<div align="center">
<div>

    <div id="headerx" style="background-color:<?php echo $masthead_color;?>">
      <div  id="headerx_top" >		
 
        <div class="floatleft">
		<a href="http://www.sequility.com" title="<?php echo $description; ?>">
                <img src="<?php echo $logo; ?>"  alt="<?php echo $description; ?>" /></a>
            <div style="margin-top:-5px;" ><?php echo $description; ?></div>  		
                
      </div>    
    </div>
</div>


<div align='center'>

 
<table width="800" align="center" cellpadding="0" cellspacing="0" border="0">
  <tr>
    <td height="30" align="center">&nbsp;</td>
  <tr>
    <td align="center" width="100%">		  
	 <table cellpadding="0" width="100%" cellspacing="0" align="center" border="0">
	   <tr>
	   <td align="center">
	   	   <h2><?php echo $title;	 ?> </h2>
	   </td>
	   </tr>
	   <tr>
	   <td>
	   	   

	   
       <?php if($showmenu): ?> 
	   <div id="tabsB">
           <ul>           
			<li><a href="index.php?c=gallery"><span <?php if ($tab=="gall") echo "class='current'" ; ?> >Galleries</span></a></li>
			<li><a href="index.php?c=albums"><span <?php if ($tab=="albu") echo "class='current'" ; ?> >Albums</span></a></li>			
			<li><a href="index.php?c=pictures"><span <?php if ($tab=="pict") echo "class='current'" ; ?> >Pages</span></a></li>
<?php if ($admin_level == ADMIN_LEVEL || $user_id == DOMAIN_OWNER) { ?>
            <li><a href="index.php?c=category"><span <?php if ($tab=="cate") echo "class='current'" ; ?> >Categories</span></a></li>
<?php }  ?>		
       <li><a href="index.php?c=users"><span <?php if ($tab=="user") echo "class='current'" ; ?> >Account</span></a></li>
			<li><a href="index.php?c=login/logout"><span <?php if ($tab=="logi") echo "class='current'" ; ?> >Logout</span></a></li>

           </ul>
	   </div>
	   <?php endif; ?>
	   </td></tr>
	   <tr><td style="padding-left:5px">
	   <?php echo $content;?> 		
	   </td></tr></table>
  </td>
  </tr>
  <tr><td align="right">
	
  </td>
  </tr>
</table>
</div>

</body>
</html>
