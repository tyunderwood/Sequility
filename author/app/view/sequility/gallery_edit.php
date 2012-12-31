<!-- deploy\app\view\sequility\gallery_edit.php -->
<form enctype="multipart/form-data" method="post" action="index.php?c=gallery/save">
<table cellpadding="0" cellspacing="5" border="0" class="tborder" width="100%">
<tr><td colspan="6" align="left" class="admin">
		Gallery Edit
	</td></tr>
<?php if($fvalue['msg']): ?>
	<tr><td colspan="2" align="center"><?php echo $fvalue['msg']; ?></td></tr>
<?php endif; ?>	
<tr><td>Name:</td><td><input type="text" name="fvalue[name]" id="name" value="<? echo $fvalue['name']; ?>"  size="60"  /></td></tr>
<tr><td>Type:</td><td><input type="text" name="fvalue[type]" id="type" value="<? echo $fvalue['type']; ?>"  size="4"  /></td></tr>

<tr><td>Cover :</td><td><input type="file" name="image" id="image"  size="60"  /><br />
	<?php if($fvalue['image']): ?><div align="center"><a href="<? echo HTTP_GALLERIA_LARGE."/".$fvalue['image']; ?>" target="_blank">View Existing Image</a></div><?php endif; ?></td></tr>


<tr><td colspan="2" align="center">
		<input name="gallery_id" id="gallery_id" type="hidden" value="<? echo $gallery_id; ?>"  size="60"  />		
		<input name="fvalue[old_image]" id="image" type="hidden" value="<? echo $fvalue['image']; ?>"  size="60"  />				
		<input type="submit" value="Save" class="button"/>
		<input type="button" onclick="location.href='index.php?c=gallery'" class="button" value="Back"/>
</td></tr>		
</table>

</form>
