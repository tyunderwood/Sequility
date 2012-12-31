<form action="index.php?c=gallery/order" method="post">

<table class="tborder" border="0" width="100%">
<tr><td colspan="6" align="left" class="admin">
		Gallery Management
</td></tr>	
<tr><td colspan="5" valign="top">	
	<table width="100%" class="tborder bborder" cellpadding="0" border="0" cellspacing="0">
     <tr class="first" style="height:30px">
		<th width="5%">#</th>
		<th width="55%">Gallery Title/Edit</th>	
		<th width="10%">Albums</th>			
		<th width="10%" style="display:none;" >Order</th>
		<th width="20%" style="display:none;" >Delete</th>					
	</tr>	
	<?php foreach ($fvalue as $key=>$fval): ?>
	
	<tr class="<?php echo fmod($key,2)?'altrow':''; ?>">
		<td width="5%"><?php echo $key+1;?></td>
		<td width="60%"><a href="index.php?c=gallery/edit&gallery_id=<?php echo $fval['gallery_id']; ?>"><?php echo $fval['name']; ?></a></td>
		<td width="15%"><a href="index.php?c=albums&gallery_id=<? echo $fval['gallery_id']; ?>">Manage</a></td>								
	 	<td width="10%" style="display:none;"  align="center"><a href="javascript:;" class="moveup"><img src="<?php echo $tpath; ?>images/up.png" border="0"/></a> <a href="javascript:;" class="movedown"><img src="<?php echo $tpath; ?>/images/down.png" border="0" /></a><input name="sortorder[]"  type="hidden" value="<?php echo $fval['gallery_id']; ?>"/></td>
		<td width="10%" style="display:none;" >
		<a href="index.php?c=gallery/delete&gallery_id=<? echo $fval['gallery_id']; ?>" id="delete">
		<img src="<?php echo $tpath; ?>images/delete.png" alt="Delete"></a></td>
			
	</tr>
	<?php endforeach; ?>
	</table>
</td></tr>	
<tr><td colspan="6" align="right">
		<input type="submit" class="button" name="submit" value="Update" />
</td></tr>
</table>

</form><br />
<?php if ($this->session->data['admin_level'] == ADMIN_LEVEL ) { ?>
<div align="center"><input type="button" onclick="location.href='index.php?c=gallery/add'" class="button" value="Add Gallery"/></div>
<?php } ?>