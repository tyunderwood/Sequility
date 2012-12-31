<div class="navigation" style="display:none;">
<a href="<?php echo $url; ?>/">Home</a>
<?php if (is_array($navigation)){ ?>
	<?php foreach($navigation as $nav){ ?>
		<?php if($nav['link']){ ?>
		   &raquo;	<a href="<?php echo $nav['link'] ?>" title="<?php echo $nav['title'] ?>"><?php echo $nav['title'] ?></a>
		<?php } else { ?>			
		   &raquo; <?php echo $nav['title'] ?>	
		<?php } ?>
		
	<?php }  ?>	
<?php } ?>
</div>
<div class="clear"></div>
 	

<?php 
    $display_val = 'block;';
    //if (! $emb_user_id) $display_val='none;';
    if (! $this->session->data['admin_level'] || 
        $this->session->data['admin_level'] == 0) $display_val='none;';
   
    if ($emb_user_id == $gallery_id && $album_id >= FIRST_ALBUM_ID) { 
  
?>
<button id="add_page" >Add Page</button> 
<?php 
    } 
?>

<div id="container" style="display:<?php echo $display_val; ?>" >You can move pages around. 
Click 'all' to view the whole album if you have more than one batch.
(Note that if you didn't create the album the changes will NOT persist)
 
<input type='hidden' style="display:none;" id="itemidz"  value='<?php echo $c_panel.'|'. $album_id; ?>' /> 

<ul id="list4" style="display:<?php echo $display_val; ?>" >
 
<?php 
 
    $width = $config_thumb_width;;
    $height = $config_thumb_height;;
    $link = '';
    
    $maxpageno = 0;  
    $count = 0; 
 
    if (count($fvalue)) {
            // print_array for debugging is at the end 
	   	  foreach($fvalue as $key=>$val){ 

            $maxpageno = $val['sortorder'];
 
            if (! $link) {
                $link = $val['link'];
                if (DEBUG) $link .= '&debug=active';
                }
    
            $date_released = $val['date_released'];   
            if ($date_released != 0) {
                $simple_date = date("Y-m-d",strtotime ($date_released));
                }
                
            if (strtotime ($date_released) > time()) {
                $date_released = "To be released: $simple_date";  
                ///$images .= '|'.$simple_date;
                } else {
                $date_released = "Released: $simple_date";
                if ($date_released == 0) $date_released = ''; 
                 
                } 
 
            $count++;
 
            //should be after $count++
            $plink = str_replace('/all?','/'.$count.'?',$link);
                                               
             ?>
		        <li  data-itemid='<?php echo $val['picture_id'].",".$val['sortorder']; ?>'>
 
                <div id="<?php echo 'picture_id_' . $val['picture_id']; ?>" class="picturebox" onmouseover="this.className='itemhover'" onmouseout="this.className='picturebox'">
                <h2><a href="<?php echo $plink; ?>" rel="bookmark" title="<?php echo $val['title'] ?>">
                <?php echo $val['title'] ?></a></h2>
                <p><a href="<?php echo $plink; ?>" title="<?php echo $val['title'] ?>">
                <img src="<?php echo $val['image'];  ?>" 
                    <?php echo 'width='.$width .' height='. $height; ?>
                    alt="<?php echo $val['title'] ?>" /></a></p>	
                <p><?php echo $date_released; ?></p> 
                </div></li>
		 
		  <div  style="display:none;" data-itemidx="<?php echo $val['sortorder']; ?>"></div>
		
<?php     }
       }
       
?>	

</ul>
    
</div>			
<div class="clear"></div>	
<?php echo $pagination;   ?>
<div class="clear"></div>
<script type="text/javascript">

    jQuery(document).ready(function() {
 
        $("#header").css({"display":"none"});

        localStorage.setItem('c_panel','<?php echo $c_panel; ?>');
        localStorage.setItem('t_panel','<?php echo $t_panel; ?>');     
        
        var images = prep_book_ends('front') + "\n" + 
                     "<?php echo $big_images; ?>" +
                     prep_book_ends('back') + "\n"; 
                       
        localStorage.setItem('images',images);       
 
        display('localStorage.images picture.php where I get images='+localStorage.getItem('images') ); 
    
        images = localStorage.getItem('images').split('\n');
 
        var panel_size = images.length - 2;   
        var panel_top = panel_size - 1;     

        var lastpageno = localStorage.getItem('lastpageno');
 
        lastpageno = parseInt(lastpageno,10) + 1;
        
        var maxpageno = <?php echo $maxpageno; ?>;           
       
        var link = '<?php echo $link; ?>';
 
        display('link is:'+link+' verbose='+verbose);
    
        if (link == '') {
 
            //alert("There are no pages in this album");
            link = '<?php echo '/?error=no_page'; ?>';    
            window.location = link;
            }
             
        var startat = '<?php echo $startat; ?>';
        
        //if (admin_level == 0) {
<?php 
        if ($emb_user_id != $gallery_id || $album_id < FIRST_ALBUM_ID) {  
?>   

 
            if (startat && startat != 1) {
                link += '&startat='+startat;  
                } else {
 
 //alert( 'lastpageno='+ lastpageno +' > ' + maxpageno + ' top='+panel_top )                 
                if (lastpageno > maxpageno) link += '&startat='+panel_top;  
                }
                  
            window.location = link;
            //}
<?php }      
?>  

    }); // end jQuery 

function prep_book_ends(type) {

    var http_host = '<?php echo HTTP_HOST; ?>';
    var per_page = '<?php echo $per_page; ?>';
    var ASSET_PICTURES = '<?php echo ASSET_PICTURES; ?>';
    
    var album_cover = '<?php echo $album_cover; ?>';
    var image = album_cover;
  
    var width_large = '<?php echo $config_large_width; ?>';  
    var height_large = '<?php echo $config_large_height; ?>';
    var title = '<?php echo $album_name; ?>';
    var sortorder = '0';
    var album_id = '0';
    var prev = '0';
    var crop_prev = 'off';
    var next = '0';
    var crop_next = 'off';
    var dummy_1 = 'false';
    var dummy_2 = 'false';
    var dummy_3 = '';
    var picture_id = '0';
    var date_released = '0';
    var description = '<?php echo $album_name; ?>';
 
    if (type == 'back') {
        sortorder = per_page;
        
        if (localStorage.getItem('c_panel') != localStorage.getItem('t_panel') ) {
            image = http_host+ '/'+ASSET_PICTURES+'/wait_100.gif';
            width_large = '100';
            height_large = '100';
            title = 'Please wait while we prepare your images';
            } else {
            title = 'By <?php echo $author_name; ?> (Please like and share)';
            }

        } else {
        if (localStorage.getItem('c_panel') != '1') {
            image = http_host+ '/'+ASSET_PICTURES+'/wait_100.gif';
            width_large = '100';
            height_large = '100';
            title = 'Please wait while we prepare your images';
            }        
        }
    
    var html = image+'|'+width_large+'|'+height_large+'|'+
               title+'|'+sortorder+'|'+album_id+'|'+prev+'|'+crop_prev+'|'+
               next+'|'+crop_next+'|'+dummy_1+'|'+dummy_2+'|'+
               picture_id+'|'+date_released + '|' + 
               description;
    return html;
    
};     
</script>
<?php
//print_array($fvalue); exit;
?>