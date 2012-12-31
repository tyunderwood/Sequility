<div class="navigation" style="display:none"><!--prb trace 12-8-12 view/default/albums.php-->
<a href="<?php echo $url; ?>/">Home</a>
<?php if(is_array($navigation)): ?>
	<?php foreach($navigation as $nav):  ?>
		<?php if($nav['link']): ?>
		   &raquo;	<a href="<?php echo $nav['link'] ?>" title="<?php echo $nav['title'] ?>"><?php echo $nav['title'] ?></a>
		<?php else: ?>			
		   &raquo; <?php echo $nav['title'] ?>	
		<?php endif; ?>
		
	<?php endforeach;  ?>	
<?php endif; ?>

</div>
<?php 
 
    // this page is called when users click on 'albums' in the vignettes page or
    // when /albums/gl/USER_ID/NICKNAME is used BUT should NOT be called
    // instead index.php should be called
    
    if ($emb_user_id == $gallery_id){ ?>
 <button id="add_album"  >Add Album</button> 
<?php } ?>
<div class="clear"></div>
<div id="container">
<?php if(count($fvalue)){
//print_array($fvalue);
	   foreach($fvalue as $key=>$val){ 
       
       $first_paid_page = $val['first_paid_page']; // to be used later
  
?>
		<div class="albumbox" onmouseover="this.className='itemhover albumbox'" onmouseout="this.className='albumbox'">
                <h2><a href="<?php echo $val['link'] ?>" rel="bookmark" title="<?php echo $val['title'] ?>">
                <?php echo $val['title'] ?></a></h2>
                <p><a href="<?php echo $val['link'] ?>" title="<?php echo $val['title'] ?>">
                <img width="<?php echo $width; ?>" height="<?php echo $height; ?>" src="<?php echo $val['image']; ?>"  alt="<?php echo $val['title'] ?>" /></a></p>	
                <div class="pictext"><?php echo $val['pictures']; ?> Pages</div>
                <div class="clear"></div>
                </div>
		<?php if(!fmod($key+1,3)):?><div class="clear"></div><?php endif; ?>			
<?php      }
       }
?>		
</div>			
<div class="clear"></div>	
<?php echo $pagination; ?>
<div class="clear"></div>
 
