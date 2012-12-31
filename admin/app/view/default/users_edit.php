<form enctype="multipart/form-data" method="post" action="index.php?c=users/save">
<table cellpadding="0" cellspacing="5" border="0" width="100%" class="tborder" >
<?php if($msg): ?>
	<tr><td colspan="2" align="center"><?php echo $msg; ?></td></tr>
<?php endif; ?>	
<tr><td>Username*:</td><td><input type="text" name="fvalue[username]" id="email" value="<? echo $fvalue['username']; ?>"  size="60"  /></td></tr>

<tr><td>Password:</td><td><input type="password" name="fvalue[password]" id="password" value=""  size="60"  /></td></tr>
<tr><td>Confirm Password:</td><td><input type="password" name="fvalue[cfmpassword]" id="cfmpassword" value=""  size="60"  /></td></tr>

<tr><td colspan="2" align="center">
		<input type="submit" value="Update" class="button"/>		

</td></tr>		
</table>

</form>
