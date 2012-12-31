<?php 
 
    // to go to the book: 
    // http://www.emediadeal.com/pictures/al/6/z-comme-zorglub for album
    // to go to the current page, simply do not set $like_href 
       
    //$like_href = HTTP_HOST."/pictures/al/$album_id/$al_title"; 
    
//echo $like_href;
    // to go to a title: http://www.emediadeal.com/?title= tintin in prague
    // to go to an author: http://www.emediadeal.com/?author=herge      
    // $nickname and $al_title have dashes instead of spaces
 //echo $like_href;
 
    ?> 
<div class="clear"></div>

<div class="paging" >
<?php if ($this->session->data['emb_user_id'] == $gallery_id) {	?>  
<div id="dialog-crop" title="Confirm Picture Crop">
	<p class="validateTips"></p>
            
	<form id="crop_form" >
	<img src="" />
	<input type='hidden' name='mode' value='crop' /> 
   
	</form>
</div>
<div class="floatleft"><a class="single_buttons" href="<?php echo $albums; ?>">Albums</a></div> 
<div class="floatleft"><a class="single_buttons" href="<?php echo $current_panel; ?>">Current Batch</a></div> 
<div id="cropbtn" class="floatleft"><a class="single_buttons" href="javascript: toggleCrop(); ">Enable Cropping</a></div> 

<div id="cropspecs" class="floatleft" style="display:none;"><a  class="single_buttons" ></a></div> 

<div id="release_now" style="display:none;" class="floatleft"><a class="single_buttons" href="javascript: releaseNow(); ">Release Now</a></div> 
 
<?php } else { ?> 
<div class="floatleft" ><a class="single_buttons"  href="<?php echo $albums; ?>">Albums</a></div> 

<div class="floatleft"><a class="single_buttons"  href="http://www.facebook.com/<?php echo $fb_id; ?>" target="_blank" >Meet <?php echo $nickname; ?></a></div> 
<?php } ?>
<div class="floatleft">                  
    <form action="<?php 
        echo str_replace('&', '&amp;', 'index.php?c=pictures/single&mycomic=print&al='.$album_id); 
        ?>" method="post" enctype="multipart/form-data" id="mycomic_print">

    <a title="Print Comic" onclick="$('#mycomic_print').submit(); printPDF(<?php echo $album_id; ?>); " 
            id="print_mycomic"  ><img class="printbtn"  src="/<?php echo ASSET_PICTURES; ?>/print-24x24.png" 
             height=14px  /></a> 
                 
    <div>
    <input type="hidden" name="redirect" value="<?php 
        echo str_replace('&', '&amp;', $redirect); 
        ?>" />                
    </div>
    </form>
</div>
<?php if ($like_href != '') { ?>
<div class="fb-like floatleft" 
    data-href="<?php echo $like_href; ?>" 
    data-send="false" 
    data-layout="button_count" 
    data-width="90" 
    data-show-faces="true" >
</div>
<div class="fb-send  floatleft" 
    data-href="<?php echo $like_href; ?>" >
</div>
<div class="floatleft"><a class="single_buttons" href="<?php echo $top_liked; ?>" >Top Liked</a></div>
<?php } ?>

<div class="floatleft"><a  class="single_buttons"  
    href="javascript: sharePage(currentSlide,<?php echo $cp; ?>)" >Share Album</a></div>
<?php if ($this->session->data['admin_level'] != 0) {	?>	 
    <?php if($next): ?><div class="floatright marginNext"><a href="<?php echo $next; ?>">Next</a></div><?php endif; ?> 
 
    <?php if($prev): ?> <div class="floatright marginNext"><a href="<?php echo $prev; ?>">Previous</a></div><?php endif; ?> 
<?php }  ?> 
            
</div> 
<div class="clear"></div>
	<div align="center" >
<section id="center_block" class="slider" 
    style="margin-left:-5px;width:<?php echo $config_large_width; ?>px; ">
    <div class="flexslider_single carousel" style="margin-left: 0px;margin-right: 0px;">
 
        <ul class="slides"   >
 	
<?php if($fvalue): 

        $last = $this->session->data['panel_count'];  
        $frame_width = $config_large_width; 
 //print_array($fvalue);  exit;
        // we only have the 1st page in $fvalue and $last tells us how
        // many pages in the current batch so we can create place holders
        // and jquery can then fill in with the actual large images stores
        // in the PC local buffer               
        for ($i=0;$i<$last+2;$i++) {
        
?>
 
<li style="display:none;">
 
	<h2 id="title_<?php echo $i; ?>" align="center" class="page_title" ><?php  
        echo $fvalue['title'];  // will be overidden by data in localstorage js
        ?>
    </h2>
 
	<div id="dimg_<?php echo $i; ?>">
    <img id="limg_<?php echo $i; ?>" src="<?php 
        if ($i == 0) {
            echo $fvalue['image'];
            } else {
            echo BLANK_GIF;
            }
        echo '" title="'. $fvalue['title'].'" ';
        ?> />
	
    <div id="desc_<?php echo $i; ?>" class="alignleft page_description"><?php 
            //echo $fvalue['description']; ?></div>
	<div class="clear"></div>
	</div> 
</li>

<?php }  ?>

<?php  
        endif; ?>
        </ul> 
    </div>
 </section> 
<?php if ($this->session->data['admin_level'] != 0 &&
          $this->session->data['emb_user_id'] == $gallery_id) {	?>	  
    <a class="edit_single dd-container" href="javascript: void();">Edit</a>  
<?php } ?>
    <a class="page_nav dd-container" href="javascript: void();">Pages</a>  
    
    <p id="wait_p" align="center" style="display:none;" ><img src="<?php echo $wait_image; ?>" /></p>
 
               
	
</div>
<div id="dialog-container-pledge"  style="display:none;"> 
<div id="dialog-pledge" title="I pledge to buy">
	<p class="validateTips"></p>
             
	<form id="pledge_form"  method="post"  action="?<?php echo GET_CONTROLLER; ?>=layout">
	<fieldset>
 		<label for="product">This product:</label>
		<input type="text" name="product" id="pledge_product" value="" class="text ui-widget-content ui-corner-all" />
		
        <label for="reference">Ref. Id#</label>
		<input type="text" name="reference" id="pledge_reference" value="" class="text ui-widget-content ui-corner-all" />
		
        <label for="attributes">Details</label>
		<textarea style="width:96%;" name="attributes" id="pledge_attributes" class="text ui-widget-content ui-corner-all" />....</textarea>
        <br/>
		
        <label for="maxprice">I am ready to pay up to:</label>
		<input type="text" name="maxprice" id="pledge_maxprice" value="" class="text ui-widget-content ui-corner-all" />
		<br/>
        <label for="expdate">I need the merchandise no later than:</label>
		<input type="text" name="expdate" id="pledge_expdate" value="Click to select a date..." class="text ui-widget-content ui-corner-all" />
	   <br/>
	 
    <br/>
	
    </fieldset>
	<input type='hidden' name='origin' value='actives' />  
	<input type='hidden' name='rid'  id='pledge_rid' value='' />  
	<input type='hidden' name='store_price' id='pledge_store_price' value='' />  
    <input type='hidden' name='pledge_id' id='pledge_id' value='<?php echo $pledge_id; ?>' />  
    <input type='hidden' name='active_pledge_id' id='active_pledge_id' value='' />  
 
	</form>
</div>
</div>
<div id="pledge-help" style="display:none;"><?php echo $pledge_info; ?></div>
<div id="dialog-message" ></div>

<div id="discount-table" 
    style="display:none;position:absolute;top:20;left:20;height: auto; width:400px;" 
    class="ui-jqgrid ui-widget ui-widget-content ui-corner-all" ><?php echo $discount_table; ?>
</div>

<div id="dialog-share" title="Share Form"  style="display:none;">
	<p class="validateTips">Welcome. Please fill in the form.
    Your friends will be able to access this album for a week</p>
    
	<form id="share_form" method="post"   action="?<?php echo GET_CONTROLLER; ?>=layout">
	<fieldset>
 		<label for="emails">Comma Separated Emails</label>
		<input type="text" name="emails" id="s_emails" value="" 
            class="text ui-widget-content ui-corner-all" />

		<label for="memo">Write a note for your friend(s)  (optional)</label>
		<textarea name="memo" id="s_memo" style="width:550px;" 
            class="text ui-widget-content ui-corner-all" /></textarea>
<?php if ($private) { ?>
        <label for="date_expiration">Expiration date (optional)</label>
        <input type="text" name="date_expiration" id="date_expiration" 
            class="text ui-widget-content ui-corner-all"/> 
<?php } else { ?> 
         <input type='hidden' name='date_expiration' id='date_expiration_0' value='0' /> 
<?php } ?>
    </fieldset>
    <input type='hidden' name='share_url' id='share_url' value='' />  
    <input type='hidden' name='user_id' id='user_id' value='' /> 
    <input type='hidden' name='album_id' value='<?php echo $album_id; ?>' />   
	<input type='hidden' name='mode' value='share_page' />  
	</form>
</div>

<!-- /////////////////////// page select /////////////////////////////-->  
<!-- we are not using a standard select tag as it doesn't always work on all android browsers -->  

<div class="page_select">
<p>Go to:</p>
<div id="ddSlick" ></div> 
 
</div>
 
<script type="text/javascript">
 
    $("#date_expiration" ).datepicker(); 
    
<?php
    // prep ddSlick drop drop
    $ddData = '';
       
    for ($page=1;$page<=$page_count;$page++) {  
        
        $image = $fvalue['image'];
        $ddData .= '{text: "page '.$page.'",
                    value: "' . $page . '",
                    //imageSrc: "'.$image.'",
                    //description: "possibly from the pictures table entry",
                    selected: false},';
        
        }            
    $ddData = trim($ddData,',');
    echo "var ddData = [".$ddData."];";  
    // end prep
    
    echo $var_product_data;
 
    echo "var user_id='".$user_id."'; ";

    echo "var ASSET_PICTURES='" . ASSET_PICTURES . "'; ";
    
?>
    var ddSlick_init = false;
    
    var flex_pic_ids = [];
    var flex_rel_dates = [];
    
    var login_cb_param = '';
    
    var currentSlide = 0; 
    var crop_element = '';
    var jcrop_api = null; 
 
    var jcrop_status = 0;
    var crop_width = <?php echo $config_thumb_width; ?>;
    var crop_height = <?php echo $config_thumb_height; ?>;
    var crop_x = 0;    
    var crop_y = 0;    
    var crop_x2 = crop_x + crop_width;
    var crop_y2 = crop_y + crop_height;
   
    var crop_w = 0;
    var crop_h = 0;
         
jQuery(document).ready(function() {

    $("#header").css({"display":"none"});
   
    flexcarouselsingle_setup();
 
    display('loadFromStorage');
       
    loadFromStorage();
 
    $('.prevnext_page').mouseover(function(){
      //moving the div left a bit is completely optional
      //but should have the effect of growing the image from the middle.
      $(this).stop().animate({"width": "<?php echo $config_thumb_width; ?>px","height":"<?php echo $config_thumb_height; ?>px" }, 0,'swing');
    }).mouseout(function(){ 
      $(this).stop().animate({"width": "44px" ,"height":"38px"}, 0,'swing');
    }); 

    $('.printbtn').mouseover(function(){
      $(this).stop().animate({"width": "24px","height":"24px" }, 0,'swing');
    }).mouseout(function(){ 
      $(this).stop().animate({"width": "14px" ,"height":"14px"}, 0,'swing');
    });
 
    $(".page_nav").click(function(){
 
		$(".page_select").toggle("fast");
		$(this).toggleClass("active");
 	
	});
   
    $('#ddSlick').ddslick({
        data: ddData,
        width: 200,
        height: 200,
        defaultSelectedIndex: 0,
        //imagePosition: "left",
        //selectText: "Page",
        onSelected: function (data) {
             //alert(data.selectedIndex);
             var pageno = parseInt(data.selectedIndex,10) + 1;
             
             if (ddSlick_init) goto_page(pageno);
             ddSlick_init = true;
             
             return false;
        }
    }); 
                           
}); // end jQuery 


function goto_page(sel) {

      var current_panel = <?php echo $this->session->data['current_panel']; ?>;
      
      var panel_count = <?php echo $this->session->data['panel_count']; ?> 
 
      var cp = Math.ceil(sel  / panel_count);
      display('current_panel='+current_panel+' panel_count='+panel_count+'-- sel='+sel+'--cp='+cp);
      var cpx = cp - 1;
      display('cpx='+cpx);
      page = sel - (cpx * panel_count);
      
      var gallery = '<?php echo $album_id; ?>';
      localStorage.setItem('lastpageno ',0);
      
      var link = '<?php echo HTTP_HOST.NICE_ALBUM_LINK; ?>' + gallery+'/page/'+cp + '/startat/'+page+'/';   
       
      display('goto: '+link)    
      window.location = link;   
};

<?php 
    // insert croping fx inside this php IF
    if ($this->session->data['admin_level'] != 0 &&
        $this->session->data['emb_user_id'] == $gallery_id) {	?>	 
     
        function prep_release_btn(crop_element) {
            // crop_element gives the current flexslider element [0..N]
            var sx = crop_element.split('_');
            var index = sx[1];
            // all actual table picture_id were stored in flex_pic_ids
            var rel_date = flex_rel_dates[index];
            //alert(crop_element+'--'+rel_date) 

            var display = '';
            if (rel_date == 0) display = 'none';  
            $('#release_now').css('display',display);
    
        };
        
        function releaseNow() {
            
            // crop_element gives the current flexslider element [0..N]
            var sx = crop_element.split('_');
            var index = sx[1];
            // all actual table picture_id were stored in flex_pic_ids
            var picture_id = flex_pic_ids[index];
    
            var url_link = "<?php echo $config_dynamic_ajax; ?>";	
     
            var str =   'origin=release_now' + 
    //                    '&author=' + logged_user_id + 
                        '&pic_id=' + picture_id;
                  
    //        str += '&user_id='+ logged_user_id;  
     
            if (verbose) str += '&debug=1';  
       
            display('releaseNow str: '+str);
                     
            $.ajax( {
                type: "POST",
                url: url_link,
                cache: false,
                data: str,
                async: true,
                error: function(xml,textStatus, errorThrown) {
                    display("ajax error: "+textStatus+" error thrown: "+errorThrown);
                    },        
                success: function(xml) {
                    display('releaseNow success');
                    var count = jQuery('count', xml).text();
                    if (count == 1) {
                        var msg = 'This page is now released';
                        } else {
                        var msg = 'Something went wrong. Please use the admin panel to release this page';
                        }
                    alert(msg);
                    
                    },        
                complete: function( message ) {
                    display('releaseNow complete');   
                    },
    
                dataType: 'xml'
                } );         
        };
        
        function showCoords(c) {
 
            // c.x, c.y, c.x2, c.y2, c.w, c.h
            
            crop_x = Math.round(c.x);
            crop_y = Math.round(c.y); 
            crop_w = Math.round(c.w);
            crop_h = Math.round(c.h); 
                        
            var specs = 'width: '+ crop_w+'px, height: '+ crop_h+'px';
//var specs = 'x: '+ crop_x+'px, y: '+ crop_y+'px';
            $("#cropspecs a").text(specs);     

 
        };
        
        function toggleCrop() {
            display("toggleCrop jcrop_status="+jcrop_status);
            
            if (jcrop_status == 1) {
                disableCrop();
                } else {
                enableCrop();
                }
        };
         
        function enableCrop() {

            display("enableCrop crop_x="+crop_x+" crop_y="+crop_y);
            jcrop_api.enable();
            
            $("#cropbtn a").text("Crop");   
 
            jcrop_status = 1;
                              
            if (crop_x == 0 && crop_y == 0) {
                var msg = "Please position your cursor at the top left corner of the area to select";
                    msg += ", click and move cursor to open up a cropping section. You can move it around";
                    msg += " and when ready click the 'crop' button at the top to crop or to cancel out.";
                    
                alert(msg);
                    
                return;
                }
             
            
            crop_x2 = crop_x + crop_width;
            crop_y2 = crop_y + crop_height;
             
            
            var specs = 'width: '+crop_width+'px, height: '+crop_height+'px';
            display("enableCrop specs"+specs);
            
            $("#cropspecs a").text(specs);    
            $("#cropspecs").toggle(true);                
 
            jcrop_api.animateTo([crop_x,crop_y,crop_x2,crop_y2]);
 

        };
         
        function disableCrop() {
     
            display("disableCrop "+crop_x+'--'+ crop_y+' jcrop_status='+jcrop_status);
 
            if (crop_x == 0 && crop_y == 0) return;

            cropImage();      
               
            jcrop_api.disable();
            jcrop_status = 0;
            jcrop_api.release();
            $("#cropbtn a").text("Enable Cropping");    
 
            $("#cropspecs a").text('');    
            $("#cropspecs").toggle(false);            
        };
            
        function activateCrop(element) {
     
            jQuery(function($) {
                $(element).Jcrop({
                    //onSelect: cropImage,
                    aspectRatio: 1,
                    minSize: [crop_width,crop_height],
                    //maxSize: [440,380]
                    //setSelect:   [ 100, 100, 50, 50 ],
                    onChange: showCoords
                    },
                    function(){
                 
                        display('activateCrop jcrop_api='+jcrop_api+ ' jcrop_status='+jcrop_status);
     
                        jcrop_api = this;
 
                        jcrop_api.disable();
                        } 
                    );
                });    
        };
      
        function cropImage(){
 
            var cx = crop_x;  
            var cy = crop_y;  
            var cw = crop_w;
            var ch = crop_h;
            
            display('cropImage:' + cx + ',' + cy);
  
            var t_w = crop_width;
            var t_h = crop_height; 
               
            var properties = cx +'_'+ 
                             cy +'_'+ 
                             cw +'_'+ 
                             ch +'_'+
                             t_w +'_'+
                             t_h;
            display('properties='+properties);
                                         
            var filename = $(crop_element).attr('src');
             
            var sx = filename.split('/');
            filename = sx[sx.length-1];
            var sx = filename.split('_');
            var album_id = sx[1];
            
            display('cropImage filename: '+filename);
          
            var url_link = "<?php echo $config_dynamic_ajax; ?>";	
            display('cropImage url_link: '+url_link);
        
            var str =   'origin=crop_image' + 
                        '&author=' + logged_user_id + 
                        '&album=' + album_id + 
                        '&properties=' + properties + 
                        '&src=' + filename;
                  
            str += '&user_id='+ logged_user_id;  
     
            if (verbose) str += '&debug=1';  
            
            display('cropImage str: '+str);
                        
            $.ajax( {
                type: "POST",
                url: url_link,
                cache: false,
                data: str,
                async: true,
                error: function(xml,textStatus, errorThrown) {
                    display("ajax error: "+textStatus+" error thrown: "+errorThrown);
                    },        
                success: function(xml) {
                    display('cropImage success');
                    // here popup a crop confirm form with crop image and ok/cancel
                    if (jQuery('cropname', xml).text()) {
                        var cropname = jQuery('cropname', xml).text();
                        display("xml.cropname: "+ cropname);
                        
                        $ ( "#dialog-crop img" ).attr('src', cropname);
                    
                        $( "#dialog-crop" ).dialog( "open" );

                        }
                    },        
                complete: function( message ) {
                    display('cropImage complete');  
                    crop_x = crop_y =0;
 
                    },
    
                dataType: 'xml'
                } );        
            };
    
            function load_next_batch(next_batch) {
     
            };
<?php }  ?> 
 
function flexcarouselsingle_setup() {
    
    var frame_width = <?php echo $config_large_width; ?>;      // that should come from config_large_width
    var startPage = <?php echo $startat; ?>;
    var last_page = <?php echo $page_count; ?>; 
    var cp = <?php echo $cp; ?>;
    var per_page = <?php echo $per_page; ?>;
 
    
    var images = '';
    var panel_size = 0;
   
    display('flexcarouselsingle_setup itemWidth: '+frame_width);
    
    $('.flexslider_single').flexslider({
        namespace: 'flex1-',
        animation: "slide",
        animationLoop: false,
        slideshow: false,
        itemWidth: frame_width,
        itemMargin: 0, 
        controlNav: true, 
        maxItems: 1, 
        //before: function(slider){ display('before ' + slider.count) },
      
        startAt: startPage,
        // there is another 'after' below....???
        //after: function(){ window.scrollTo(0,0); },
        
        prevText: "Previous",
        
        start: function(slider){
 
            images = localStorage.getItem('images').split('\n');
            panel_size = images.length - 2;        
            var slider_count = slider.count-1;
    
            currentSlide = slider.currentSlide;  
            
            if (admin_level != '0') {
          
                crop_element = '#limg_'+ slider.currentSlide;
                display('flexcarouselsingle_setup activate Crop 1 '+crop_element);
                activateCrop(crop_element);
                prep_release_btn(crop_element); // should also use panel no
                }
 
            $('.prevnext_page').click(function(){
                if ($(this).hasClass('next')) {
                    slider.flexAnimate(slider.getTarget("next"), true);
                    } else {
                    slider.flexAnimate(slider.getTarget("prev"), true);
                    }
                });
                
            $('.prevnext_page_x').click(function(){
                if ($(this).hasClass('next')) {
                    slider.flexAnimate(slider.getTarget("next"), true);
                    } else {
                    slider.flexAnimate(slider.getTarget("prev"), true);
                    }
                });                            
        },
        
        move: 1,
        
        before: function(slider) {
 
            var nextSlide = ((cp -1) * per_page) + slider.animatingTo;
            nextSlide = nextSlide % per_page;
   
            if (nextSlide == 0) {
                //alert('nextSlide='+nextSlide );   
                // let's grab the next batch now... async
                
                // we store it in: localStorage.setItem('images_batchno') 
                 
                //load_next_batch(cp); // never went further...
                }
                                       
        },
        
        after: function(slider) {
            //display('before last_page:'+last_page+' cp:'+cp + ' slider.animatingTo:'+  slider.animatingTo+' per_page'+ per_page); 
            var nextSlide = ((cp -1) * per_page) + slider.animatingTo;
            var lastSlide = last_page + 1;
            if (nextSlide > lastSlide) {
                //alert('stop me');
                slider.flexAnimate(slider.getTarget("prev"), true);
                }
            //window.scrollTo(0,0);
            
            currentSlide = slider.currentSlide;
            
            if (admin_level != '0') {
        
                jcrop_api.destroy();
          
                crop_element = '#limg_'+ slider.currentSlide;
                display('flexcarouselsingle_setup activate Crop 2 '+crop_element); 
             
                activateCrop(crop_element);
                prep_release_btn(crop_element);
                }
     
                             
            if (currentSlide == 0) { 
     
                var href= $('#title_0'+ ' .prev_href').attr('href'); 
                // if href is undefined... we are at the book cover          
                if (href) window.location = href; 
           
                //var elem = '#title_0'+ ' .prev';
                //$(elem).click();      // won't work on android or google browsers
                } 
 
        }, 
 
        end: function(slider) {
            // happen before last page shows up
     
            var panel_top = panel_size;
 
            var href= $('#title_'+panel_top+ ' .next_href').attr('href'); 
            if (href) window.location = href; 
            
            //var elem = '#title_'+panel_top+ ' .next';
            //$(elem).click();      // won't work on android or google browsers
            }
        });
 
  
         
};
 
function loadFromStorage() {
 
    display('localStorage.images single.php where I process images='+localStorage.getItem('images') );
 
    var images = localStorage.getItem('images').split('\n');
    var img = '';
    var img_info = '';
   
    var frame_width = <?php echo $config_large_width; ?>;      // that should come from config_large_width
 
    var panel_size = images.length - 1;
    var panel_top = panel_size - 1;
//alert(panel_size) 
 
    for (var i=0;i<panel_size;i++) {
    
        display('single.php i='+i); 
    
        img_info = images[i];
        var sx = img_info.split('|');
        img = sx[0];
        var limg = img.replace(/thumb/g,'large');
        
        var width = sx[1];
        var height = sx[2];
        var title = sx[3];
        var pageno = sx[4];
        pageno = parseInt(pageno,10);
        
        var album_id = sx[5];
        var prev = sx[6];
        var prev_img = sx[7];
        var next = sx[8];
        var next_img = sx[9];    
        var bwd = sx[10];
        var fwd = sx[11];
        var picture_id = sx[12];
        display(' picture_id: ' + picture_id);
        
        var rel_date = sx[13];

        var description = sx[14];
        var objx = document.getElementById('desc_'+ i);  
        var html = '<span id="page_desc_' + picture_id + '" >' + description + '</span>'; 
        objx.innerHTML = html;
 
        if (album_id != 0) localStorage.setItem('lastpageno',pageno);   // should be sortorder
        display('localStorage.lastpageno: ' + localStorage.getItem('lastpageno') );
        
        flex_pic_ids[i] = picture_id;
        flex_rel_dates[i] = rel_date;
        
        var obj = document.getElementById('limg_'+i);   
        if (!obj) continue;

        if (width > frame_width) {     
            var ratio = frame_width/width;
            width = frame_width;
            height = height * ratio;
            }  

        display('single.php img='+limg+'w: '+width+' h:'+height+ ' pageno='+pageno); 

        obj.src = limg; 
        obj.title = title; 
          
        obj.height=height; 
        obj.width=width;
 
        var objx = document.getElementById('title_'+ i);  
        // get the file type...
        sx = limg.split('.');
        var filetype = sx[sx.length-1];
        
            
        display('loadFromStorage next: ' + next);
        display('loadFromStorage prev: ' + prev);
              
        if (prev_img == 'on') {
            var prev_page = 'prevnext_page ';
            var cropsrcprev = '/<?php echo GALLERIA; ?>/crop/page_' + 
                            album_id + '_' + prev + '.'+filetype;
            } else {
            var cropsrcprev = '/'+ASSET_PICTURES+'/go-previous-orange.png';
            var prev_page = 'prevnext_page_x ';
            } 
/* 
        var prev_cursor = '';
        if (bwd == 'true') prev_cursor = 'cursor:pointer;';
        var next_cursor = '';
        if (fwd == 'true') next_cursor = 'cursor:pointer;';
*/
        var prev_cursor = next_cursor = 'cursor:pointer;';
        
        if (next_img == 'on') {
            var next_page = 'prevnext_page ';
            var cropsrcnext = '/<?php echo GALLERIA; ?>/crop/page_' + 
                            album_id + '_' + next + '.'+filetype;
            } else {
            var cropsrcnext = '/'+ASSET_PICTURES+'/go-next-orange.png';
            var next_page = 'prevnext_page_x ';
            }

        var html = '';
        
        if (i == 0) {
            var prev_panel = '<?php echo $prev; ?>';
     
            display('loadFromStorage prev_panel >' + prev_panel+'<');
 
            if (prev_panel != '') {
                
                // that manage the next/prev top arrow based nav
                html += '<a class="prev_href" href="' + prev_panel + '" >';
                html += '<img onclick="" title="Previous" class="' + prev_page + ' prev" src="'+ cropsrcprev + 
                        '" style="'+prev_cursor+'margin:10px;height:38px;" align="center" />';
                html += '</a>';
                }
            } else {
            //display("prev_page="+prev_page+' cropsrcprev '+cropsrcprev+' prev_cursor '+prev_cursor);

            html += '<img onclick="" title="Previous" class="' + prev_page + ' prev" src="'+ cropsrcprev + 
                        '" style="'+prev_cursor+'margin:10px;height:38px;" align="center" />';
      
            }
  
        html += '<span id="title_text_' + picture_id + '" >' + title + '</span>'; 

        if (i == panel_top) {
            // get next panel
            var next_panel = '<?php echo $next; ?>';
 
            if (next_panel != '') {
                //that manage the next/prev top arrow based nav
                html += '<a class="next_href" href="' + next_panel + '" >';
                html += '<img onclick="" title="Next" class="' + next_page + ' next" src="'+ cropsrcnext + 
                        '" style="'+next_cursor+'margin:10px;height:38px;" align="center" />';
                html += '</a>';
                }
            } else {
            html += '<img onclick="" title="Next" class="' + next_page + ' next" src="'+ cropsrcnext + 
                        '" style="'+next_cursor+'margin:10px;height:38px;" align="center" />';  
            } 
     
        objx.innerHTML = html;   
 
        }
       
};
 
function sharePage(currentSlide,currentPanel) {
    //alert(currentSlide+'--'+currentPanel) 
    if (logged_user_id == 0) {
        alert("Please login to share albums or pages");
        return;
        }
         
    var album_id = '<?php echo $album_id; ?>';   
    var http_host = '<?php echo HTTP_HOST.NICE_ALBUM_LINK; ?>';
 
    var link = http_host + album_id+'/page/'+ currentPanel+'/startat/'+currentSlide+'/';  

    //alert(link)
    
    $( "#share_url").val(link);
  
    $( "#user_id").val(logged_user_id);
    $( "#dialog-share" ).dialog( "open" );
};

function getProductDetails(product_id,id) { 
 	
    display('getProductDetails user_id='+user_id);	    
    //displayWait(true);
    var url_link = "<?php echo $config_dynamic_ajax; ?>"; 
 
    var str = 'origin=product_details&id='+id+
                '&user_id='+user_id + '&product_id=' + product_id + 
                '&v=' + (new Date()).getTime();;  
    
    if (verbose) str += '&debug=1';
    
    display("url_link: "+str);
    
    $.ajax( {
        type: "POST",
        url: url_link,
        cache: true,
        data: str,
        asynch: false,
        error: function(xml,textStatus, errorThrown) {
            display("ajax error: "+textStatus );
            },
        success: function(xml) {
            var status = jQuery('status', xml).text(); // same as id
            display("getProductDetails status: "+status);
            
            var active_pledge_id = jQuery('active_pledge_id', xml).text();
            display("getProductDetails active_pledge_id: "+active_pledge_id);
            if (status != 'ok') {
 
                alert(status);
                
                return false;
                }
            
            $("#dialog-pledge").dialog('open');
            $("#pledge_expdate" ).datepicker(); 
            $(".ui-datepicker" ).css("display","none"); 
            
            var rid = jQuery('rid', xml).text(); // same as id
            display("getProductDetails record_id: "+rid);
            
            var name = jQuery('name', xml).text();   
            var ref = jQuery('reference', xml).text();  
            var attributes = jQuery('attributes', xml).text(); 
     
            var image = jQuery('image', xml).text(); 
            var price = jQuery('price', xml).text(); 
 
            $("#pledge_reference").val(ref); 
            $("#pledge_product").val(name); 
            $("#pledge_maxprice").val(price); 
            $("#pledge_store_price").val(price); 
 
            $("#pledge_attributes").val(attributes);
            $("#pledge_rid").val(rid);   
            
            $("#active_pledge_id").val(active_pledge_id);   
            
            var msg = 'Your pledge will be added to pledge ID#: '+active_pledge_id + ' open for the same merchandise';
            if (active_pledge_id != 0) $("#dialog-pledge .validateTips").html(msg);
               
            return false;
 
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                    display('getProductDetails ajax complete');	   
                    },

        dataType: 'xml'
    } );

    
     display('getProductDetails end');	  
    
};

function rejectPledge(obj,id,pledge_id) {
 
    display('rejectPledge id: ' + id);
 
    if (id == 0) {
        var query = '?c=products&album_id=4&picture_id='+picture_id+'&zip='+zip+'&cat_id='+cat_id+'&rid='+id;
        query += '&pledge_id='+pledge_id;
        query += '&f_0=0&t_0=0&p_0=0&publish=true&status=rejected';
        display('rejectPledge query: ' + query);
 
        window.location = '<?php echo HTTP_HOST; ?>/' + query;
        // bye bye... reject the whole pledge    
        }

    // remove one item.. change label and add to 'remove' field in DOM
    var index = lookRemovedList(id);
    display('rejectPledge index: ' + index);
    
    if (index != -1) {
        updateRemoveList(id,'out');
        var label = 'Delete';
        } else {
        updateRemoveList(id,'in');
        var label = 'Insert';        
        }
        
    var value = '<strong><em></em>' + label + '</strong><span></span>';    
    $(obj).html(value); 
  
};

function updateRemoveList(id,mode) {

    var list = $("#discount_remove").html();
    var newlist = '';
    
    if (list.indexOf(',') != -1) {
        
        var sx = list.split(',');
        
        for (var i=0;i<sx.length;i++) {
            var rid = sx[i];
            if (rid == '') continue;  
            if (rid == id && mode == 'out') continue;
            newlist += rid + ',';
            }  
        }

     if (mode == 'in') newlist += id + ','; 
     display('rejectPledge newlist: ' + newlist);
     
     $("#discount_remove").html(newlist);    
        
};

function lookRemovedList(id) {

    var list = $("#discount_remove").html();
    if (list.indexOf(',') == -1) return -1;
    var sx = list.split(',');
     
    for (var i=0;i<sx.length;i++) {
        var rid = sx[i];  
        if (rid == id) return i;
        }  
    return -1;
};

function managePledge(obj,nbr_pledges,id,pledge_id) {
    // id is the unique record id number in products_details and nbr_pledges is zero when rejected
    // id == zero when we have a reject/discount all
    //display('managePledge id: ' + id + ' nbr of pledges: ' + nbr_pledges);
 
    if (nbr_pledges == 0) {
        rejectPledge(obj,id,pledge_id);
        return false;
        }
        
    // open a discount grid
    var position = $(obj).offset();

    var left = Math.round(position.left)
    var top = Math.round(position.top);
 
    var height = $("#discount-table").css("height");
    var width = $("#discount-table").css("width");

    height = height.replace(/px/g, "");
    width = width.replace(/px/g, "");

    //display(top + ' ' + left + ' ' + height + ' ' + width)    
        
    top = top - height - 10;
    left = left - width;
    
    $("#discount-table").css("left",left);
    $("#discount-table").css("top",top);
    $("#discount-table").css("z-index", 10000);

    $('.discount_from_qty').removeClass( "ui-state-error" );
    $('.discount_to_qty').removeClass( "ui-state-error" );
    $('.discount_pct').removeClass( "ui-state-error" );
    
	$(".addTableRow").btnAddRow({rowNumColumn:"rowNumber"});
	$(".delTableRow").btnDelRow();
 
    $("#pledge_rid").html(id);
    $("#pledge_id").html(pledge_id);
    
    $("#discount-table").css('display',''); // show table    
};

function closeDiscountTable() {
    $("#discount-table").css("z-index", 0);
    
    $("#discount-table").css('display','none');
};

function cleanDiscountTableErrors() {
    $('.discount_from_qty').removeClass( "ui-state-error" );
    $('.discount_to_qty').removeClass( "ui-state-error" );
    $('.discount_pct').removeClass( "ui-state-error" );
};

function submitDiscountTable(mode) {
 
    var from_qty = new Array;
    var to_qty = new Array;
    var pct = new Array;
    $('.discount_from_qty').removeClass( "ui-state-error" );
    $('.discount_to_qty').removeClass( "ui-state-error" );
    $('.discount_pct').removeClass( "ui-state-error" );
    $(".discount-table-err").html('');
    
    $('.discount_from_qty').each(function(index) {
        //display(index + ': ' + $(this).val());
        from_qty[index] = $(this).val();
    });

    $('.discount_to_qty').each(function(index) {
        //display(index + ': ' + $(this).val());
        to_qty[index] = $(this).val();
    });
        
    $('.discount_pct').each(function(index) {
        //display(index + ': ' + $(this).val());
        pct[index] = $(this).val();
    }); 
 
    if (pct.length > 5) {
        errorDiscountTable(index,5,0);
        return;    
        }
        
    for (var index=0;index<pct.length;index++) {
        //display(index + ': ' + from_qty[index] + '--' +  to_qty[index]+ '--' +  pct[index]);
        if (! isNumber(from_qty[index])) {
            errorDiscountTable(index,10,1);
            return;
            }
        if (parseInt(from_qty[index],10)  == 0 ) {
            errorDiscountTable(index,15,1);
            return;
            }
                        
        if (! isNumber(to_qty[index])) {
            errorDiscountTable(index,10,2);
            return;
            }  
        if (parseInt(to_qty[index],10)  == 0 ) {
            errorDiscountTable(index,15,2);
            return;
            }
                                           
        if (parseInt(from_qty[index],10)  > parseInt(to_qty[index],10) ) {
            errorDiscountTable(index,30,3);
            return;
            }
            
        if (! isNumber(pct[index])) {
            errorDiscountTable(index,10,4);
            return;
            }
            
        if (parseInt(pct[index],10)  == 0 ) {
            errorDiscountTable(index,15,4);
            return;
            }
             
        if (index > 0) {
            if (parseInt(from_qty[index],10)  < parseInt(to_qty[index-1],10) ) {
                errorDiscountTable(index,100,3);
                return;
                }
            if (parseInt(pct[index],10) < parseInt(pct[index-1],10) ) {
                errorDiscountTable(index,110,8);
                return;
                } 
                
            if (parseInt(pct[index],10)  > 100 ) {
                errorDiscountTable(index,120,4);
                return;
                }                                        
            }    
        }
 
    var rid = $("#pledge_rid").html();
    var pledge_id = $("#pledge_id").html();
    
    // c=products&album_id=4&picture_id=887957&zip=7xxxx&cat_id=42
    var query = '?c=products&album_id=4&picture_id='+picture_id+'&zip='+zip+'&cat_id='+cat_id+'&rid='+rid;
    
    var removed= $("#discount_remove").html();
    query += '&pledge_id='+pledge_id+'&remove='+removed;
       
    for (var index=0;index<pct.length;index++) {
        query += '&f_'+index+'=' + from_qty[index] + 
                 '&t_'+index+'=' + to_qty[index] + 
                 '&p_'+index+'=' + pct[index];
                 
        if (mode == 1) query += '&publish=true&status=active';
                 
        }
 
    closeDiscountTable();
    
    window.location = '<?php echo HTTP_HOST; ?>/' + query;
};

function errorDiscountTable(index,errno,location) {

    //display('errorDiscountTable errno ' + errno + ' index ' + index + ' location ' + location);
    // display some modal warning window here
    var err = '';
   
    switch (location) {
        case 1:
        case 3:
            $('.discount_from_qty').each(function(i) {
                if (index == i) {
                    $(this).addClass( "ui-state-error" );
                    }
                }); 
             
        case 2:
        case 3:
            $('.discount_to_qty').each(function(i) {
                if (index == i) {
                    $(this).addClass( "ui-state-error" );
                    }
                });
         
        case 4:    
        case 8:
            $('.discount_pct').each(function(i) {

                if (index == i) {
                    $(this).addClass( "ui-state-error" );
                    }
                });
                           
        }
        
 
    switch (errno) {
        case 5:   err = 'You can have a max. of 5 lines'; break;
        case 10:  err = 'Fields must have numbers'; break;
        case 15:  err = 'Fields cannot be null'; break;
        case 30:  err = 'To Qty must be greater than From Qty'; break;
        case 100: err = 'From Qty must be greater than To Qty of line above'; break;
        case 110: err = 'Pct must be greater than than the one on line above'; break;
        case 120: err = 'Pct cannot be greater than 100'; break;
        }
    
    $(".discount-table-err").html(err);
 
   
};
 
function pledgeItem(product_id,id) {
    // id is the unique record id number in products_details
    
    display('products_details table id: ' + id);
    // ajax controller to get record and pop up pledge form, go to sign on/login if needed 
    display('product_id: ' + product_id);
     
    display("And user_id is: "+user_id);

    //we need to pass product_id to check_logged...      
    if (! check_logged(id,user_id,'pledgeItem','Please login to submit your pledge','pin2Shop'))  { 
        login_cb_param.product_id = product_id;
        return false;
        }
    getProductDetails(product_id,id);
    
    return false;   
};   

function confirmPledge(mode,user_id,pledge_id) {  
 
    display('confirmPledge: mode=' + mode + ' user_id=' + user_id + ' pledge_id=' + pledge_id ); 
     
    if (! check_logged(0,user_id,'confirmPledge','Please login to confirm your pledge','pin2Shop')) { 
        login_cb_param.user_id = user_id;
        login_cb_param.pledge_id = pledge_id;
        return false;
        }
    display('confirmPledge... now logged in'); 
    
    if (mode == 1) {
        payPPAdaptive(user_id,pledge_id);  
        } else {
        printCoupon(user_id,pledge_id);  
        }
    
};

function printCoupon(user_id,pledge_id) {
    /*
    Check: https://cms.paypal.com/us/cgi-bin/?cmd=_render-content&content_ID=developer/e_howto_api_APPayAPI
    Here is our sandbox: https://developer.paypal.com/cgi-bin/devscr?cmd=_sandbox-acct-session
    use our private email and pe..4U
    */
               
	var url_link = "<?php echo $config_dynamic_ajax; ?>";
       
    var str = 'mode=pledges&user_id='+user_id+'&pledge_id='+pledge_id;
    str += '&origin=pledge_coupon';   
    
    if (verbose) str += '&debug=1'; 
    
    display("url_link: "+str);
    
    $.ajax( {
        type: "POST",
        url: url_link,
        cache: true,
        data: str,
        asynch: false,
        error: function(xml,textStatus, errorThrown) {
            display("ajax error: "+textStatus+" error thrown: "+errorThrown);
            },        
        success: function(xml) {
       
            var msg = jQuery('msg', xml).text();
            display("msg: "+msg);
    
            var forward_link = jQuery('forward_link', xml).text();
            display("forward_link: "+forward_link);   
 
            var payment = jQuery('payment', xml).text();
            display("payment: "+payment); 
                        
            var currency = jQuery('currency', xml).text();
            display("currency: "+currency); 
                                 
            var title = jQuery('title', xml).text();
            display("title: "+title);             
 
            $( "#dialog-message" ).html(msg);
            $( "#dialog-message" ).attr('title',title);
            $( "#dialog-message" ).dialog({
			     modal: true,
			     buttons: {
                    Coupon: function() {
					   $( this ).dialog( "close" );
					   window.location = forward_link;
				    },
                    	
				    Cancel: function() {
					   $( this ).dialog( "close" );
					 
				    }				
			     }
		      });        
                    
            return false;
               
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                     
                    },

        dataType: 'xml'
    } );    
};

function payPPAdaptive(user_id,pledge_id) {
 
	var url_link = "<?php echo $config_dynamic_ajax; ?>";
       
    var str = 'mode=pledges&user_id='+user_id+'&pledge_id='+pledge_id;
    str += '&origin=pledge_confirm';   
    
    if (verbose) str += '&debug=1'; 
    
    display("payPPAdaptive url_link: "+str);
    
    $.ajax( {
        type: "POST",
        url: url_link,
        cache: true,
        data: str,
        asynch: false,
        error: function(xml,textStatus, errorThrown) {
            display("ajax error: "+textStatus+" error thrown: "+errorThrown);
            },        
        success: function(xml) {
       
            var msg = jQuery('msg', xml).text();
            display("msg: "+msg);
 
            var callback_ok = jQuery('callback_ok', xml).text();
            display("callback_ok: "+callback_ok);   
            
            var callback_cancel = jQuery('callback_cancel', xml).text();
            display("callback_cancel: "+callback_cancel);   
            
            var payment = jQuery('payment', xml).text();
            display("payment: "+payment); 
            
            var paypal_image = jQuery('paypal_image', xml).text();
            display("paypal_image: "+paypal_image);     
                                 
            var currency = jQuery('currency', xml).text();
            display("currency: "+currency); 
                                 
            var title = jQuery('title', xml).text();
            display("title: "+title);             
            
            var paykey = jQuery('paykey', xml).text();
            display("paykey: "+paykey);  
        
            if (paykey == 0) {
                alert("Oops! Problem with payPal. Please try again later");
                return false;
                }
 
            $( "#dialog-message" ).html(msg);
                
            $( "#dialog-message" ).attr('title',title);
            $( "#dialog-message" ).dialog({
			     modal: true,
			     buttons: {
     
				    Pay: function() {
					   $( this ).dialog( "close" );
					   
					   // next line goes to home which is cleaner
					   document.location = '?payKey='+paykey;
					   
                       //next line to refresh this page while adding token
                       //document.location.search = insertParam('payKey', paykey); 

				    },
    
				    Cancel: function() {
					   $( this ).dialog( "close" );
					 
				    }				
			     }
		      });        
                    
            return false;
               
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                     
                    },

        dataType: 'xml'
    } ); 
};

function buyItem(id,user_id) {

    display('products_details table id buy: ' + id + ' user: ' + user_id);
     
    if (! check_logged(id,user_id,'buyItem','Please login to buy this product','pin2Shop')) return false;
 
 		    
    //displayWait(true);
	var url_link = "<?php echo $config_dynamic_ajax; ?>";
       
    var str = 'origin=buy_now&prod_id='+id+'&user_id='+user_id;  
    
    if (verbose) str += '&debug=1';
    
    display("url_link: "+str);
     
    $.ajax( {
        type: "POST",
        url: url_link,
        cache: true,
        data: str,
        asynch: false,
        error: function(xml,textStatus, errorThrown) {
            display("ajax error: "+textStatus+" error thrown: "+errorThrown);
            },        
        success: function(xml) {
       
            var msg = jQuery('msg', xml).text();
            display("msg: "+msg);
 
            var callback_ok = jQuery('callback_ok', xml).text();
            display("callback_ok: "+callback_ok);   
            
            var callback_cancel = jQuery('callback_cancel', xml).text();
            display("callback_cancel: "+callback_cancel);   
            
            var payment = jQuery('payment', xml).text();
            display("payment: "+payment); 
            
            var paypal_image = jQuery('paypal_image', xml).text();
            display("paypal_image: "+paypal_image);     
                                 
            var currency = jQuery('currency', xml).text();
            display("currency: "+currency); 
                                 
            var title = jQuery('title', xml).text();
            display("title: "+title);             
            
            var paykey = jQuery('paykey', xml).text();
            display("paykey: "+paykey);  
        
            if (paykey == 0) {
                alert("Oops! Problem with payPal. Please try again later");
                return false;
                }
 
            $( "#dialog-message" ).html(msg);
                
            $( "#dialog-message" ).attr('title',title);
            $( "#dialog-message" ).dialog({
			     modal: true, 
			     buttons: {
     
				    Pay: function() {
					   $( this ).dialog( "close" );
					   
					   // next line goes to home which is cleaner
					   document.location = '?payKey='+paykey;
					   
                       //next line to refresh this page while adding token
                       //document.location.search = insertParam('payKey', paykey); 

				    },
    
				    Cancel: function() {
					   $( this ).dialog( "close" );
					 
				    }				
			     }
		      });        
                    
            return false;
               
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                     
                    },

        dataType: 'xml'
    } ); 

};

function shareItem(id,user_id) {
    /*
    Share will allow to enter a list of emails and send a link such as:
    http://www.pin2shop.com:8001/index.php?c=products&album_id=4&picture_id=891217&zip=xxxxx&cat_id=78
    we need:
    from email
    to email list
    the url
    */
    display('products_details table id share: ' + id); 
    
    if (! check_logged(id,user_id,'shareItem','Please login to share your finding','pin2Shop')) return false;
   
    var search = window.location.search;
    var sx = search.split('&');
    search = '';
    for (var i=0;i<sx.length;i++) {
        var param = sx[i];
        var sxx = param.split('=');
        if (sxx[0] == 'user_id') continue;
        if (sxx[0] == 'token') continue;
        if (sxx[0] == 'pledge_id') continue;
        if (sxx[0] == 'id') continue;
        search += param + '&';
        }    
    search += 'id='+ id; // nicely close the search part
    
    var fromURL = window.location.protocol + 
        '//' + window.location.host +  
        window.location.pathname + 
        search + 
        window.location.hash;
             
    display('fromURL='+fromURL);

    $( "#share_url").val(fromURL);
    $( "#user_id").val(id);
    //popup form to get emails
    $( "#dialog-share" ).dialog( "open" );
 
 
};
 
function check_logged(id,user_id,cb,msg,title) {
    
    if (user_id != 0) {
        display("user is logged");
        return true;
        } else {
        login_cb_param = { 
            'cb': cb,
            'id': id}        
        
        $( "#dialog-message" ).html(msg);
        $( "#dialog-message" ).attr('title',title);
        $( "#dialog-message" ).dialog({
			modal: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
					$( "#dialog-login" ).dialog( "open" );
				}
			}
		});        
        
        return false;
        }  
};

function showBigMap(obj,zip) {
    // zip is not used
    
    var location = $(obj).html();
    // remove <br>
    location = location.replace(/<br>/g, "");    
    display('showBigMap...location: '  + location);  
  
    var map = '<div id="bigmap_canvas"  ></div>';      
  
    var title = "Locate the store at: "+location;
    
    $( "#dialog-message" ).html(map);
    $( "#dialog-message" ).attr('title',title);
    $( "#dialog-message" ).dialog({
		modal: true,
		buttons: {
			Close: function() {
    
                $( this ).dialog( "option", "width", dialog_width );
                $( this ).dialog( "option", "height", dialog_height );	
                		
				$( this ).dialog( "close" );
					
				}
			}
		});  

    var dialog_width = $( "#dialog-message" ).dialog( "option", "width" );
    var dialog_height = $( "#dialog-message" ).dialog( "option", "height" );
 
    $( "#dialog-message" ).dialog( "option", "width", 400 );
    $( "#dialog-message" ).dialog( "option", "height", 400 );
		
    $('#bigmap_canvas').css('height','300px');
    $('#bigmap_canvas').css('width','360px');
    $('#bigmap_canvas').css('top','0');
    $('#bigmap_canvas').css('left','0');
           
    //alert(location)
    $('#bigmap_canvas').gmap3({action:'clear'});
     
    $('#bigmap_canvas').gmap3({
        action: 'addMarker',
        address: location,
        map:{
            center: true,
            zoom: 15 
        
            } 
 
        });     
    
	
           
};

function insertParam(key, value) {
    
    key = escape(key); value = escape(value);
    var kvp = document.location.search.substr(1).split('&');

    var i=kvp.length; 
    var x; 
    while(i--) {
        x = kvp[i].split('=');

        if (x[0]==key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
            }
        }

    if(i<0) {kvp[kvp.length] = [key,value].join('=');}
 
    return kvp.join('&');  
};

function printPDF(album_id) {
    
    // do not use a window name with spaces... will not work w/ IE
    window.open('/app/controller/printPDF.php?al='+album_id ,'Print', 'status=no,location=no,width=900,height=550,resizable=1,scrollbars=1');
    
};
</script>
