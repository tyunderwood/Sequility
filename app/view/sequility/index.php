<!-- /////////////////////////////////////////////// 

      start app\view\sequility\index.php 

////////////////////////////////////////////////////-->
<div class="navigation" style="display:none;" >
</div>
<div id="breadcrumbs" class="navigation pictext " ></div>


<div id="container">

<div id="map_canvas" style="display:none;top:0;left:0;position:absolute;width:200px; height:170px;"></div>

<div id="accordions_canvas" style="display:none;" ></div>
 
        		
<?php //echo 'level='.$this->session->data['admin_level']; 
    if ($this->session->data['emb_user_id'] && $this->session->data['admin_level'] >= AUTHOR_LEVEL): ?>
 <button id="add_album" >Add Album</button> 
 <div class="clear"></div>
<?php endif; ?> 
 
 <section class="slider" style="margin-top:0px; " >
    <div class="flexslider carousel" 
            style="border-color:#fff;">
 					
				<ul class="slides"  >	</ul> 
        
    </div>
 </section> 
 
<div style="margin-top:5px" ></div>

<div id="gallery_container" style="display:none;" > 

<?php 
 
    if(count($fvalue)):
//print_array($fvalue); exit;    
        $i=0;
		foreach($fvalue as $key=>$val):
		  if ($val['type'] > AUTHOR_LEVEL ) continue;
		  $i++;
?>
           <div class="gallerybox" onmouseover="this.className='itemhover gallerybox'" onmouseout="this.className='gallerybox'">
                <h2><a href="<?php echo $val['link'] ?>" rel="bookmark" title="<?php echo $val['name'] ?>">
                <?php echo $val['name'] ?></a></h2>
                <?php if ($val['type'] != SCROLLER) { ?>
                <p><a id="gallery_<?php echo  $val['sortorder']; ?>" href="<?php echo $val['link'] ?>" title="<?php echo $val['name'] ?>">
                
                <img width=<?php echo $config_thumb_width; ?> src="<?php echo $val['image'] ?>" alt="<?php echo $val['name'] ?>" /></a></p>	
                <?php } else { ?>
                <p><div id="vscroller" ></div></p>
                <?php }   ?>
                <div style="display:inline;" class="pictext"><!--prb trace 12-8-12 view/default--><?php 
                    //echo $val['albums'] . ' Pledges; ?></div>
                <div class="clear"></div>
           </div>
           

           <?php if (!fmod($i ,4)):?><div class="clear"></div><?php endif; ?>
<?php   endforeach;
    endif;
  
?>
</div>

</div>

