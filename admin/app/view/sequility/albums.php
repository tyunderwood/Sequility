<form action="index.php?c=albums/order" method="post">
<table width="100%" border="0" class="tborder">
<tr><td align="left" class="admin">
		<?php if($gvalue['name']) echo $gvalue['name']." &raquo; "; ?>Albums: &raquo; 
	</td>
    <td align="right">
		Filter: <select name="gallery_id" id="galleryFilter" style="width:120px">
				<option value="">Select Gallery</option>
				<?php echo $gallery_dropdown; ?>
				</select>
	</td></tr>

<tr><td colspan="2" width="100%">	
	<table width="100%" cellpadding="0" class="tborder bborder" cellspacing="0" border="0">	
	<tr style="height:30px">
		<th width="5%">#</th>
		<th width="40%">Title</th>
		<th width="10%">Author</th>		
		<th width="10%">Page count</th>		
		<th width="10%">Pages</th>						
		<th width="15%">Edit/Delete</th>
		<th width="10%">Order</th>				
	</tr>	
	<?php foreach ($fvalue as $key=>$fval): ?>
	<tr class="<?php echo fmod($key,2)?'altrow':''; ?>">
		<td width="5%"><?php echo $record_start+$key+1;?></td>
		<td width="40%"><?php echo $fval['title']; ?></td>
		<td width="10%"><?php echo $fval['name']; ?></td>		
		<td width="10%" align="center"><?php echo $fval['pictures']; ?></td>
	 	<td width="10%" align="center"><a href="index.php?c=pictures&album_id=<? echo $fval['album_id']; ?>&gallery_id=<? echo $fval['gallery_id']; ?>">Manage</a> </td>		
	 	<td width="15%" align="center">
			<a href="index.php?c=albums/edit&album_id=<? echo $fval['album_id']; ?>">Edit</a> / <a href="index.php?c=albums/delete&album_id=<? echo $fval['album_id']; ?>&gallery_id=<? echo $fval['gallery_id']; ?>" id="delete">Delete</a></td>
		<td width="10%" align="center"><a href="javascript:;" class="moveup"><img src="<?php echo $tpath; ?>/images/up.png" border="0"/></a> <a href="javascript:;" class="movedown"><img src="<?php echo $tpath; ?>/images/down.png" border="0" /></a><input name="sortorder[]"  type="hidden" value="<?php echo $fval['album_id']; ?>"/></td>	
	</tr>
	<?php endforeach; ?>
</table>
</td></tr>	
<tr><td colspan="2" align="right">
		<span style="float:left"><?php echo $pagination; ?></span><? if($gallery_id): ?><input type="submit" class="button" name="submit" value="Update" /><?php endif; ?></td></tr>
</table>
<input type="hidden" name="gallery_id" value="<? echo $gallery_id; ?>" />
<input type="hidden" name="record_start" value="<?php echo $record_start;?>" />
<input type="hidden" name="page" value="<?php echo $page;?>" />

<Br />
<div align="center"><input type="button" onclick="location.href='index.php?c=albums/add&gallery_id=<? echo $gallery_id; ?>'" class="button" value="Add Album"/> &nbsp; <input type="button" onclick="location.href='index.php?c=gallery'" class="button" value="Back to Gallery"/></div>
</form>
