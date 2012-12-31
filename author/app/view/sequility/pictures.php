<style>
 select {
 	width:120px;
 }
</style>
<table width="100%" border="0" class="tborder">
   <tr><td align="left"  class="admin">
		Pages:- <?php if($gvalue['name']) echo $gvalue['name']." &raquo; "; ?><?php if($avalue['title']) echo $avalue['title'].""; ?>
   </td>
   <td align="right">
		   <form action="" method="get">
		   Gallery: <select name="gallery_id" id="galleryDropdown" style="width:120px">
				<option value="">Select Gallery</option>
				<?php echo $gallery_dropdown; ?>
				</select>
				Album:<span id="album_dropdown"><select name="album_id" style="width:120px">
			<option value="">Select Album</option>
			<?php echo $albums_dropdown; ?> 
			</select></span> <input type="submit" name="Search" value="Go" class="button" name="" />   
			<input type="hidden" name="c" value="pictures" />
		   </form>			
   </td>   
   </tr>
   <tr>
   <td valign="top" colspan="2">
   <form action="index.php?c=pictures/order" method="post">
		 <table width="100%" border="0" cellpadding="1" cellspacing="1">				 
		 <tr><td colspan="6">	
			<table width="100%" class="tborder bborder" cellpadding="1" cellspacing="1">
			<tr class="first" style="height:30px">
				<th width="5%">#</th>
				<th width="20%">Album</th>
				<th width="30%">Page Title/Edit</th>	
				<th width="10%">Thumb</th>				
				<th width="10%">Page#</th>
				<th  style="display:none;" width="15%">Order</th>
				<th>Delete</th>		
			</tr>			
			<?php foreach ($fvalue as $key=>$fval): ?>
			
			<tr class="<?php echo fmod($key,2)?'altrow':''; ?>">
				<td width="5%"><?php echo $record_start+$key+1;?></td>
				<td width="20%"><?php echo $fval['album_title']; ?></td>
				<td width="30%"><a href="index.php?c=pictures/edit&picture_id=<? echo $fval['picture_id']; ?>&album_id=<? echo $fval['album_id']; ?>"><?php echo $fval['title']; ?></a></td>
				<td width="10%"><img class="thumb_img" src="<?php 
 
                    $creator_id = $fval['creator_id'];
 
                    $folder = $creator_id.'/'.$fval['album_id'];    //MK
                    echo HTTP_GALLERIA_THUMB. '/'. $fval['image'];  
                    
                    ?>" width="<?php echo $thumb_width; ?>" 
                        height="<?php echo $thumb_height; ?>" /></td>						
						
				<td width="10%"><? echo $fval['sortorder']; ?></td>
                <td style="display:none;" width="15%" align="center"><a href="javascript:;" class="moveup"><img src="<?php echo $tpath; ?>/images/up.png" border="0"/></a> <a href="javascript:;" class="movedown"><img src="<?php echo $tpath; ?>/images/down.png" border="0" /></a><input name="sortorder[]"  type="hidden" value="<?php echo $fval['picture_id']; ?>"/></td>
					<td>
					<a href="index.php?c=pictures/delete&picture_id=<? echo $fval['picture_id']; ?>&album_id=<? echo $fval['album_id']; ?>" id="delete" ><img src="<?php echo $tpath; ?>images/delete.png" alt="Delete"></a></td>
				
			</tr>
			<?php endforeach; ?>
			</table>
		</td></tr>	
		<tr><td colspan="7" align="right">
				<span style="float:left"><?php echo $pagination; ?></span><?php if($album_id): ?><input type="submit" class="button" name="submit" value="Update" /><?php endif; ?>
		</td></tr>
		</table>
		<input type="hidden" name="album_id" value="<? echo $album_id; ?>" />
		<input type="hidden" name="record_start" value="<?php echo $record_start;?>" />
		<input type="hidden" name="page" value="<?php echo $page;?>" />
		<input type="hidden" name="gallery_id" value="<? echo $gallery_id; ?>" />
		</form>
	</td>	
	</tr>
	</table><br />
<div align="center"><input type="button" onclick="location.href='index.php?c=pictures/add&album_id=<? echo $album_id; ?>'" class="button" value="Add Picture"/> &nbsp; <input type="button" onclick="location.href='index.php?c=albums&gallery_id=<? echo $gallery_id; ?>'" class="button" value="Back"/></div>

 
<script type="text/javascript">

jQuery(document).ready(function() { 

    $('.thumb_img').mouseover(function(){
      $(this).stop().animate({"width": "<?php echo $thumb_width*4; ?>px","height":"<?php echo $thumb_height * 4; ?>px" }, 0,'swing');
    }).mouseout(function(){ 
      $(this).stop().animate({"width": "<?php echo $thumb_width; ?>px" ,"height":"<?php echo $thumb_height; ?>px"}, 0,'swing');
    }); 
    
}); // end jQuery 
    
</script>    