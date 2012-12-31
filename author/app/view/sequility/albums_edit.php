<?php
    $filesDir = 'app/controller/jupload/site/galleria/';
    $link = $filesDir."index.php?uid=$gallery_id";
    $busy_name = $filesDir.'files/'.$gallery_id.'.busy.txt';
?>

<form enctype="multipart/form-data" method="post" action="index.php?c=albums/save">
<table cellpadding="0" cellspacing="5" border="0" class="tborder" width="100%">
<tr><td colspan="6" align="left"  class="admin">

		Albums:- <?php if($gvalue['name']) echo $gvalue['name']." &raquo; "; ?>	 <?php if($fvalue['title']) echo $fvalue['title']." &raquo; "; ?>	
	</td></tr>
<?php if($msg){ ?>
	<tr><td colspan="2" align="center"><?php echo $msg; ?></td></tr>
<?php 
        } 
    if (file_exists($busy_name)) {
        $title = file_get_contents($busy_name);
        echo '<tr><td colspan="2" align="center">Please wait till your album ('.$title.') is created</td></tr>';
        echo '</table></form>';
       
        } else {
  
?>	
<tr><td>Title:</td><td><input type="text" name="fvalue[title]" id="title" value="<? echo $fvalue['title']; ?>"  size="60"  /></td></tr>
<tr><td>Description:</td><td><textarea rows="3" cols="60" name="fvalue[description]" id="description" ><? echo $fvalue['description']; ?></textarea></td></tr>

<!--tr><td>Category:</td><td><select name="fvalue[category_id]" id="category_id">
				<option value="">Select Category</option>
				<?php echo $category_dropdown; ?>
				</select></td></tr-->

<tr><td>Gallery:</td><td><select name="fvalue[gallery_id]" id="gallery_id">
				<option value="">Select Gallery</option>
				<?php echo $gallery_dropdown; ?>
				</select></td></tr>

<!--tr><td>First Paid Page:</td><td><input type="text" name="fvalue[first_paid_page]" id="first_paid_page" value="<? echo $fvalue['first_paid_page']; ?>"  size="20"  /></td></tr-->

<tr><td>Release Date:</td><td><input type="text" name="fvalue[date_released]" id="date_released" value="<? echo $fvalue['date_released']; ?>"   class="text ui-widget-content ui-corner-all"/></td></tr>

<!--tr><td>Domain:</td><td><select name="fvalue[domain_owner]" id="privacy">
				<option value="">Select Domain</option>
				<?php echo $domain_dropdown; ?>
				</select></td></tr-->
				
<tr><td>Privacy:</td><td><select name="fvalue[privacy]" id="privacy">
				<option value="">Select Type</option>
				<?php echo $privacy_dropdown; ?>
				</select></td></tr>
 			
<tr><td>Album Cover :</td><td><input type="file" name="image" id="image"  size="60"  /><br />
	<?php if($fvalue['image']): ?><div align="center"><a href="<? echo HTTP_GALLERIA_LARGE."/".$fvalue['image']; ?>" target="_blank">View Existing Image</a></div><?php endif; ?></td></tr>


<tr><td colspan="2" align="center">
		<input name="fvalue[album_id]" id="album_id" type="hidden" value="<? echo $fvalue['album_id']; ?>"  size="60"  />
		<input name="gallery_id" id="gallery_id" type="hidden" value="<? echo $gallery_id; ?>"  size="60"  />		
		<input name="fvalue[old_image]" id="image" type="hidden" value="<? echo $fvalue['image']; ?>"  size="60"  />				
		<input type="submit" value="Save" class="button"/>
        <?php if($gallery_id): ?><input type="button" onclick="location.href='index.php?c=albums&gallery_id=<? echo $gallery_id; ?>'" class="button" value="Back to Albums"/><?php endif; ?>
</td></tr>		
</table>

</form>
<script type="text/javascript">
            
    $("#date_released" ).datepicker(); 

function showApplet() {
    
    $("#applet_iframe").toggle();
};    

function hideApplet() {
    if ($("#applet_iframe" ).css("display") != 'inline') return;
    
    var options = {};     
    $("#applet_iframe").hide('blind', options, 'fast'); 
}; 
            
</script>
<?php } ?>