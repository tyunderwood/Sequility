<!-- deploy\app\view\sequility\login.php -->

<form id="form1" name="form1" method="post" action="index.php?c=login">
<table width="50%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#F7F7F7">
      <tr>
        <td colspan="3" class="textredbold">Login</td>
      </tr>
	  <tr><td colspan="3" class="content" align="center" ><?php if($msg != ''): 
		   																echo $msg; 
																	  endif;
																?>&nbsp;</td></tr>
	  <tr>
        <td width="125" rowspan="4"><img src="<?php echo $tpath; ?>/images/lgicon.gif"  /></td>
      </tr>
      <tr>
        <td height="30" class="content">Username</td>
        <td align="left">
          <input name="fvalue[username]" type="text" />
        </td>
      </tr>
      <tr>
        <td height="30" class="content">Password</td>
        <td align="left"><input name="fvalue[password]" type="password" /></td>
      </tr>
      <tr>
        <td >&nbsp;</td>
        <td height="40" align="left"><input type="submit" class="button" value="Login" name="submit"/></td>
      </tr>
    </table>	
</form>

		
