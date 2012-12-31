<form action="index.php?c=category/order" method="post">

<table class="tborder" border="0" width="100%">
<tr><td colspan="6" align="left" class="admin">
		Category Management
</td></tr>	
<tr><td colspan="5" valign="top">	
	<table width="100%" class="tborder bborder" cellpadding="0" border="0" cellspacing="0">
     <tr class="first" style="height:30px">
		<th width="5%">#</th>
		<th width="55%">Title</th>	
		<th width="10%">Sub Categories</th>			
		<th width="20%">Edit/Delete</th>		
		 			
	</tr>	
	<?php foreach ($cats as $key=>$fval){ 
        if ($fval['domain_owner'] == 0 &&
            $this->session->data['admin_level'] != ADMIN_LEVEL ) continue;
    ?>
	
	<tr class="<?php echo fmod($key,2)?'altrow':''; ?>">
		<td width="5%"><?php echo $key+1;?></td>
		<td width="55%"><?php echo $fval['title']; ?></td>
		<td width="10%"><a href="index.php?c=category&category_id=<? echo $fval['category_id']; ?>">Manage</a></td>								
	 	<td width="20%">
			<a href="index.php?c=category/edit&category_id=<? echo $fval['category_id']; ?>">Edit</a> / <a href="index.php?c=category/delete&category_id=<? echo $fval['category_id']; ?>" id="delete">Delete</a></td>
 	
	</tr>
	<?php } ?>
	</table>
</td></tr>	
<tr><td colspan="6" align="right">
		<input type="submit" class="button" name="submit" value="Update" />
</td></tr>
</table>

</form><br />
<?php if ($this->session->data['admin_level'] == ADMIN_LEVEL 
            || $this->session->data['user_id'] == DOMAIN_OWNER) { ?>
<div align="center"><input type="button" onclick="location.href='index.php?c=category/add'" class="button" value="Add Category"/></div>
<?php } ?>