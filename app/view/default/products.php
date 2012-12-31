<div class="navigation">
<a href="<?php echo $url; ?>/">Catalog</a>
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
<div class="clear"></div>
<div id="container">
<?php if(count($fvalue)):
	   	  foreach($fvalue as $key=>$val): ?>
		        <div data-id="<?php echo $val['title'] ?>" class="pinbox" onmouseover="this.className='itemhover'" onmouseout="this.className='pinbox'">
<!--MK added action buttons -->
<div class="actions" >
            <div class="floatright">
                <a href="" class="Button Button11 WhiteButton">
                    <strong><em></em>Comment</strong><span></span>
                </a>
            </div>
            <div class="floatright">
                <a href="" class="Button Button11 WhiteButton">
                    <strong><em></em>Pledge</strong><span></span>
                </a>
                
                    
                <a href="" class="Button Button11 WhiteButton">
                    <strong><em></em>Like</strong><span></span>
                </a>
                    
                
            </div>
        </div>
<!--MK -->          
                <h2><a href="<?php echo $val['link'] ?>" rel="bookmark" title="<?php echo $val['title'] ?>">
                <?php echo $val['title'] ?></a></h2>
                <p><a href="<?php echo $val['link'] ?>" title="<?php echo $val['title'] ?>">
                <img src="<?php echo $val['image'] ?>" alt="<?php echo $val['title'] ?>" /></a></p>	
                <div class="clear"></div>
                </div>
		<?php if(!fmod($key+1,4)): ?><div class="clear"></div><?php endif; ?>
				
<?php     endforeach;
       endif;
?>		
</div>			
<div class="clear"></div>	
<?php echo $pagination; ?>
<div class="clear"></div>
