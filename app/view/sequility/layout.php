<?php /* ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
using html5 doc type as instructed by Google
<?php */ ?>
 
<!DOCTYPE html>
<!--////////////////////////////////////////////

     start app/view/sequility/layout.php

////////////////////////////////////////////////-->

<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" 
        content="width=device-width; 
        initial-scale=1.0; 
    
        user-scalable=1;">
 
<title><?php echo $title; ?></title>
<link rel="shortcut icon" href="<?php echo $config_icon; ?>" type="image/x-icon">
<link rel="icon" href="<?php echo $config_icon; ?>" >
<meta name="description" content="<?php echo $description; ?>" />

<meta name="author" content="emediaboard, novility" />
<meta name="keywords" content="novility,novels, sequencial,serialized,comics" />

<link rel="stylesheet" href="<?php echo $template; ?>css/style.css?v=<?php echo time(); ?>" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo $template; ?>css/media.css?v=<?php echo time(); ?>" type="text/css"  />

 
<style type="text/css">
 
#display_log {
    // for debugging only... CSS folks, please don't change or move it
    clear: both;
    width: auto;
    height: 250px;
    overflow: scroll;
    border: 1px solid #666;
    background-color: #fcfcfc;
    padding: 10px;
    display:none; 
}

</style>
 
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery-1.7.1.min.js"></script> 
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery-ui-1.8.18.custom.min.js"></script> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/jqGrid/jquery-ui-1.8.1.custom.css" />

<script  defer src="<?php echo $template; ?>js/flexslider/jquery.flexslider.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $template; ?>css/flexslider/flexslider.css" />
 
<?php 
    
    require_once('js_vars.php'); 

/* ?>
    
<link rel="stylesheet" href="<?php echo $template; ?>css/emb/smoothness/jquery-ui-1.8.18.custom.css" type="text/css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/emb/jquery.ui.tinytbl.css" />
<link rel="stylesheet" type="text/css" href="<?php echo $template; ?>css/jCarousel/skins/pin2shop/skin.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/vscroller/vscroller.css" /> 
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/jqGrid/ui.jqgrid.css" />

<script type="text/javascript" src="<?php echo $template; ?>js/jCarousel/jquery.jcarousel.min.js"></script>

<script type="text/javascript" src="<?php echo GMAPKEY_PIN2SHOP; ?>" ></script>
<script type="text/javascript" src="<?php echo $template; ?>js/emb/gmap3.min.js"></script>
<script type="text/javascript" src="https://www.paypalobjects.com/js/external/dg.js"></script> 

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jQuery_blockUI.js"></script>

<script type="text/javascript" src="<?php echo $template; ?>js/jqGrid/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="<?php echo $template; ?>js/jqGrid/jquery.jqGrid.min.js" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.ui.tinytbl.js"></script>
	
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.timers.js"></script>

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.cookies.2.2.0.min.js"></script>

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.ui.selectmenu.js"></script>
 
<script type="text/javascript" src="<?php echo $template; ?>js/vscroller/vscroller.js" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo $template; ?>js/tableAddRow/jquery.table.addrow.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?php echo $template; ?>js/emb/map.js"></script>

<?php */ ?> 


<script type="text/javascript">
    
    
    var geocoder;
    var map;
    var visible_map = '';
 
    var latitude = 0.0;  
    var longitude = 0.0;
    
    var saved_bot_button_html = '';
    var saved_bot_button_id = '';
 
<?php
    echo $var_search_level;
    echo $var_cat_id;
    echo $var_mode;
    echo $var_zip;
    echo $var_q_text;

    echo $var_radius;
    echo $var_origin;
    echo $var_pledge_id;
    
    echo $var_user_id;
    echo $var_picture_id;
    echo $var_manager_store_id;
    echo $var_admin_level;
  
?>
    var query_vars = {};    //MK1 made it an object

    var author='';  
    var album=''; 
    var background_album = '';
     
    var ASSET_PICTURES = '<?php echo ASSET_PICTURES; ?>';
    var DOMAIN_OWNER = '<?php echo DOMAIN_OWNER; ?>'; 
    var SHORT_HOSTNAME = '<?php echo SHORT_HOSTNAME; ?>'; 
    
function setAuthor(obj) {
        
    var settings = $('#mysettings_tabs');
 
    if (! $(obj).is(':checked')) {
        settings.tabs('disable',4);
        return;  
        }

    settings.tabs('enable',4);
    settings.tabs('select',4);  
             
};
 
    //var search_level = 0;   // start at category level
    //var max_tiles = 4;  // is now from PHP via pin2shop_js_vars.php
    
    var jcar = '';
  
    var first = 0;
    var last = 0;
    
    var loaded = false;
    //if (!zip) var zip = '0';
    //var radius = '0';
    //var search = 0;
    var title_zip = '';
    
    var tiles = new Array();
   
    var getActives_timer = 0;
    var pledgeDetails = new Array();
    var pledgeDetailsIsOpen;

    var g_state = '';
    var scroll_count = 0;   // px
    
function displayWait(carousel) {
        
    carousel.size(1);
    var source = '<?php echo $wait_image; ?>';
    carousel.add(1, "<p><img src='" + source + "' /></p><p>Please wait while we retrieve the data...</p>");
};

function displayWaitImage() {

    var source = '<?php echo $wait_image; ?>';
    document.write("<p><img src='" + source + "' /></p><p>Please wait while we retrieve the data...</p>");

};

function displayWaitWheel(obj) {

    $( ".catalog_bot_buttons" ).css("display",'none');

    var source = '<?php echo $wait_image; ?>';
    var image = "<p><img src='" + source + "' /></p><p>Please wait while we retrieve the data...</p>";
    $(obj).html(image);
};
 
function prep_authors_select() {
 
    display(' prep_authors_select ');
 /*
    for (var i=0;i <  menu.length;i++) {
            
        var sx = menu[i].split('|');
        var cat_name = sx[0];
        var cat_id = sx[1];
 
        
        } 

 */
 /*
    $('#zipSearch select').selectmenu({
            style:'dropdown',
            change: selectmenuChange,
            width:170,
            maxHeight: 200
            } );

    $('#zipSearch .ui-widget').css('font-size','0.9em');
*/ 
   
};


/* 
function selectmenuChange(event,selindex) {

    var cat_id = ($('#zipSearch select').val())
    //display(  'itemAddCallback selectmenuChange cat: ' + cat_id); 
                 
    var zipval = $( "#zip" ).val(); 
    var radius = $( "#radius" ).val(); 
    
    var search_level = $('#zipSearch select').attr('search_level');
    //display(  'itemAddCallback selectmenuChange search_level: ' + search_level); 
 
    var location = "?mode=cats&cat_id="+cat_id + "&search_level="+search_level+"&zip="+zipval;
    window.location = location;
   
};
*/
function manageAdSpace(obj) {
    
    
    var windowSizeArray = [ "width=200,height=200",
                                    "width=300,height=400,scrollbars=yes" ];
    var windowSize = windowSizeArray[$(this).attr("rel")];
 
    var cat_id = $(".advertise_img").attr('cat');   // could be zero if top level ad
    var link = $(".advertise_img").attr('link');
    if (link != '') {
        var windowName = "advertisement";//$(this).attr("name");
  
        //window.open(link, windowName, windowSize);    // to open as a new window
        window.open(link, windowName);                  // to open as a new tab
        
        }
    
    var cat_name = $("#breadcrumbs").text();    
    var zip = $(".advertise_img").attr('zip');
     
    if (manager_store_id == 0) {  
        alert('Login as an author and buy this space'); 
        return;
        }
        
    //. Postal: '+zip + ' cat=' + cat_id + ' name:' +cat_name);

//  /admin/index.php?c=pictures/edit&picture_id=1&album_id=1    
    var url = "/admin/index.php?c=pictures/edit&picture_id=1&album_id=1&zip="+ zip +
                " &cat_id="+cat_id;
 
    var windowName = "admin";//$(this).attr("name");

    //window.open(url, windowName, windowSize);     // to open as a new window
    window.open(url, windowName);                   // to open as a new tab
 

};
  
/**
 * Item html creation helper.
 */
function redirectToManagerView(index) {
//c=products&album_id=4&picture_id=887957&zip=78741&cat_id=42&search_level=2001&pledge_id=16

    var params = pledgeDetails[index];
    
    var eval_str = ''; 
    $.each(params, function(key, val) {
        eval_str += "var "+ key + "='" + val + "' \n"; 
        //display(key + '=' + val);  
    }); 
    eval(eval_str);
    
    var location = '<?php echo HTTP_HOST; ?>/?c=products&album_id=4&picture_id=0&zip='+ zip + 
                        '&search_level=1001&pledge_id=' + pledge_id;
    display('redirectToManagerView location: '+ location); 
 
    window.location = location;

 
};

function postToFeed(pledge_id) {
 
    var config_site_url = '<?php echo $config_site_url; ?>';
    var fb_app_id = '<?php echo FB_APP_ID; ?>'; 
     
    var windowSizeArray = [ "width=200,height=200",
                                    "width=300,height=400,scrollbars=yes" ];
    var windowSize = windowSizeArray[$(this).attr("rel")];
 
    var link = "https://www.facebook.com/dialog/feed";
    link += "?app_id="+ fb_app_id;
    link += "&link="+config_site_url+"/index.php?fb_feed=" + pledge_id;
    link += "&picture="+config_site_url+"/pictures/logo.png";
    link += "&name=Attract More Pledgers";
    link += "&caption=Pin Your Needs, Get Followers";
    link += "&description=The more you attract, the more you save";
    //link += "&redirect_uri="+config_site_url+"/feed_response";  
    link += "&redirect_uri=http://www.facebook.com";  
    
    display("link="+link);
 
    window.open(link, "Attract Your Facebook Friends", windowSize);    // to open as a new window
 
};
 
function getPledgeDetails(index) { 
  
    display('getPledgeDetails index: ' + index + ' pledgeDetailsIsOpen: ' + pledgeDetailsIsOpen);
/*    
    if (manager_store_id != 0) {
        // store manager view
        //display('store manager view: '+manager_store_id);
        redirectToManagerView(index);
        }
*/        
    if (pledgeDetailsIsOpen != 0) {
    
        var old_accord = '#accordion_' + pledgeDetailsIsOpen;

        $(old_accord).css("display","none");   
        $(old_accord).css("z-index", 0);
     
        var but = "<strong><em></em><?php echo $label['pledges'] ?></strong><span></span>"; 
        $("#active_pins_"+pledgeDetailsIsOpen+"_button .PledgeDetails").html(but);
        $("#expired_pins_"+pledgeDetailsIsOpen+"_button .PledgeDetails").html(but);
 
        if (pledgeDetailsIsOpen == index) {
            pledgeDetailsIsOpen = 0;
            return false; 
            }
             
        }
            
    var params = pledgeDetails[index];
    
    var eval_str = ''; 
    $.each(params, function(key, val) {
        eval_str += "var "+ key + "='" + val + "' \n"; 
        display(key + '=' + val);  
    }); 
    eval(eval_str);
    
    var url_link = "<?php echo $config_dynamic_ajax; ?>";	
    
    var str = 'origin=active_pins&mode=prods&first=' + first + 
              '&last=' + last + 
              //'&zip=' + zip + 
              '&pledge_id=' + pledge_id + 
              '&user_id=' + logged_user_id;  // if not logged in user_id = 0
              // user_id will allow dynamic to share max price

    if (verbose) str += '&debug=1';
              
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
       
            createAccordionTab(index,xml);
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                     
                    },

        dataType: 'xml'
    } );
    return false;
    
};
 
function createAccordionTab(index,xml) {
    // xml contains all the entries for the pledge id#... need to loop
    // we will create a new accordion for each pledge (index)
    
    //display('createAccordionTab');
 
    var accord = 'accordion_' + index;
    
    if ( $('#'+accord).length != 0) return showPledgeDetails(index);
    
    //display('create new accordion: '+accord);
    var new_accordion = '<div id="' + accord + 
                    '" style="display:none;top:0;left:0;position:absolute;width:200px;"></div>';
    
    $('#accordions_canvas').append(new_accordion);   // create empty new accordion
    
    var details_id = new Array();
    var attributes = new Array();
    var user_id = new Array();
    var nickname = new Array();
 
    jQuery('details_id', xml).each(function(i) {
        details_id[i] = jQuery(this).text();
        //display('createAccordionTab...details_id  '  + details_id[i] + ' i=' + i);   
    }); 

    jQuery('attributes', xml).each(function(i) {
        attributes[i] = jQuery(this).text();
         
    }); 

    jQuery('nickname', xml).each(function(i) {
        nickname[i] = jQuery(this).text();
         
    });

    var expiration = jQuery('expiration', xml).text();
 
    var pledge_id = jQuery('pledge_id', xml).text();
    
    var start_price = jQuery('start_price', xml).text();
    
    var pledge_master = 'Pledge id#' + pledge_id + '<br/>Expires on: ' + expiration;
 
    var newtab = '<h3><a href="#" ><?php echo $label['pledges'] ?>' +               
                 '</a></h3><div>' + pledge_master + '</div>';
  
    $('#'+accord).append(newtab);    
           
    jQuery('user_id', xml).each(function(i) {
        user_id[i] = jQuery(this).text();
    
        var content = attributes[i];
        if (logged_user_id == user_id[i]) content += '<br/>Max. Price: '+start_price;
        var newtab = '<h3><a href="#" >By: '+ nickname[i] + 
                        '</a></h3><div>'+content + '</div>';
  
        $('#'+accord).append(newtab);    
    
    });
 
    $('#'+accord).accordion();
 
    $('.ui-accordion-content').css("font-family","Arial,Helvetica,sans-serif");  
    $('.ui-accordion-content').css("font-size","13px");   
    $('.ui-accordion-content').css("height","55px");   
    
    //$('#'+accord).accordion( "option", "clearStyle", true );
 
    showPledgeDetails(index);
               
};

function showPledgeDetails(index) {
        
    //display('showPledgeDetails of index: ' +index);
    //display('showPledgeDetails of pledgeDetailsIsOpen: ' +pledgeDetailsIsOpen);
    var params = pledgeDetails[index];
 
    var accord = '#accordion_' + index;
    
    pledgeDetailsIsOpen = index;     
    
    var eval_str = ''; 
    $.each(params, function(key, val) {
        eval_str += "var "+ key + "='" + val + "' \n"; 
        //display(key + '=' + val);  
    }); 
    eval(eval_str);
        
    //display('showPledgeDetails of ' +details_id);
 
    uid = '#' + uid;
          
    // we got the record id in pledge_details
    var position = $(uid).offset();

    var mod = index % max_tiles;  
    if (mod == 0) mod = max_tiles;  
    var left = tiles[mod];     

    var top = position.top - 110;
    //display( 'showPledgeDetails uid=' + uid + ' index ' + index + ' top ' + top);
 
    $(function() {
		$(accord).accordion({
			autoHeight: false,
			navigation: true
		});
	});
 
    $(accord).css("left",left);
    $(accord).css("top",top);
     
    $(accord).css("z-index", 10000);    
    $(accord).css("display","");   

    // click anywhere to close ///////
    $(document).click(function() {
        $(accord).css("display","none"); 
        var but = "<strong><em></em><?php echo $label['pledges'] ?></strong><span></span>"; 
        $("#active_pins_"+pledgeDetailsIsOpen+"_button .PledgeDetails").html(but);
        $("#expired_pins_"+pledgeDetailsIsOpen+"_button .PledgeDetails").html(but);
        pledgeDetailsIsOpen = 0;
    }); 

    $(accord).click(function(e) {
        e.stopPropagation(); // This is the preferred method.
    }); 
    /////////////////////////////////
    
    var but = "<strong><em></em>Close</strong><span></span>"; 
    $("#active_pins_"+index+"_button .PledgeDetails").html(but);  // text replaces < and > with &gt... and val is not working
    $("#expired_pins_"+index+"_button .PledgeDetails").html(but);  // text replaces < and > with &gt... and val is not working

     
    return false;   // to avoid href
};
 
/////////////////////////////////////////////////////////////////////////////// 

function getQueryParms() {
 
    var hash;
    var q = document.URL.split('?')[1];

    if (q != undefined){
        q = q.split('&');
        for(var i = 0; i < q.length; i++){
        
            var hash = q[i].split('=');
            query_vars[hash[0]] = hash[1];
  
            }
        } 
        
};

jQuery(document).ready(function() {
 
    //var author='';  
    //var album=''; 
    
    /*
    If you don't login (guest) you can see all albums with pages, released and
    with read access to all.
    If you login as an author you can see your albums and have access to batches and cropping
    If you login as a vanilla user, you can see all albums with pages, released and
    with read access to all and logged in users.
    */
    display('jQuery ready... starting the show origin:'+origin);
    
    check_friendly_url();   

    if (query_vars['author']) {
 
        $( "#author" ).val(query_vars['author']);
 
        $( "#album" ).val('Title');
        origin = 'author_albums';
        display('author found in query_vars: ' + query_vars['author']);
        // get all the albums for an author
        start_search();
         
        }
 
    if (query_vars['title']) {        
 
        $( "#album" ).val(query_vars['title']);
  
        $( "#author" ).val('Author');
        origin = 'author_albums';
        display('title found in query_vars');
        // get all the albums for an author
        start_search();
         
        }        

    $( "#author" )
        // avoid bubbling when checking keycode works with: keydown and return false
        .keydown(function(event){
        
            if(event.keyCode == 13){
                $("#search").click();
                return false;
                } 
            })              // no comma or anything if another action is set after
    	.click(function() {
    		 
            //$('form :input').val(""); // reset all the fields
            $(this).val("");
            $( "#album" ).val("Title");
            
 			$( "#author" ).css("color",'');
    	});

    $( "#album" )
        // avoid bubbling when checking keycode works with: keydown and return false
        .keydown(function(event){
        
            if(event.keyCode == 13){
                $("#search").click();
                return false;
                } 
            })              // no comma or anything if another action is set after
    	.click(function() {
    		 
            $(this).val("");
            $( "#author" ).val("Author");

 		    $( "#album" ).css("color",'');
    	});
               
    $( "#search" )
    	.button()    // use ui-buttons.. set the nice round look
    	.click(function() {
  
            //hideMap();
 
            author = $( "#author" ).val(); 
            album = $( "#album" ).val(); 
    		
            if (author == 'Author' && album == 'Title') {
                alert("Please enter an author or/and a title");
                return false;
                } 
 
            start_search();
            
    				
    	});

	$("#list4" ).dragsort({  
                dragSelector: "div", 
                dragBetween: true, 
                dragEnd: saveOrder4, 
                placeHolderTemplate: "<li class='placeHolder'><div></div></li>" 
                });

    // authors     
    $("#gallery_0")
        .click(function() {
        //window.location = '<?php echo HTTP_HOST; ?>/?c=cats&origin=catalog&search_level=0&zip=' + zip;
    	window.location = '<?php echo HTTP_HOST; ?>/?c=layout&origin=authors';
        });  
    
    // publishing	
    $("#gallery_1")
        .click(function() {
      
        if (logged_user_id != 0) {
            window.location = '<?php echo HTTP_HOST; ?>/?c=albums&gallery_id='+logged_user_id;
            } else {
            alert('You must be logged in as an author to access this function');
            }
        });    
    	
    $("#gallery_2")
        .click(function() {
        // search_level <> 0 to trigger displaying an 'add pledge' button
        //window.location = '<?php echo HTTP_HOST; ?>/?c=cats&origin=expired&search_level=0&zip=' + zip;
    	}); 
              	
    dispatch();

function check_friendly_url() {
/*
    These are the rules used in .htaccess:
    
RewriteRule ^(admin/)$ /admin/index.php [L]
#next rule replaces all spaces with underscores
RewriteRule    (.*)\ (.*)      $1_$2 [N]
RewriteRule ^([a-zA-Z0-9_]+)/$ index.php/$1 [L]
RewriteRule ^([a-zA-Z0-9_]+)$ index.php/$1/ [L]
    
    Because the author redirection is done with JS we need this
    check_friendly_url() to correctly set the query parms (and input boxes)
    I barely understand why they work... but after 24 hours of work
    I finally got those to work... so be it
*/
    var q = document.URL;
    q = q.replace("http://","");
    
    var needle = '/';
    
    if (q.indexOf(needle) != -1 &&
        q.indexOf('?') == -1) {

        var sx = q.split(needle); 
         
        if (sx.length < 2) return;
        
        // 'pictures' might be a controller name and we should probably test for all of them
        if (sx[1] == 'admin') return;
        if (sx[1] == 'pictures') return;
 
        if (sx[1] == 'albums') {
            query_vars['author'] = sx.pop();
            return;
            }  
             
        if (sx[1] == 'index.php') return;  
        
        if (sx[1] == 'top_liked') {
            query_vars['title'] = sx[1];
            sx[1] = '';
            }
            
        query_vars['author'] = sx[1];  
      
    
        }

};


function check_captcha() {
 
    //query_vars = {}; 
    top.getQueryParms(); 

    if (query_vars.captcha != 'error') return;
 
    $( "#error-msg p" ).text("Incorrect captcha value. Please try again"); 
    $( "#error-msg" ).dialog({ title: "Captcha Error Message" });
  
    $( "#error-msg" ).dialog({
    	autoOpen: true,
    	modal: true,
    	buttons: {
    		Ok: function() {
    			$( this ).dialog( "close" );
    			 
    			$( "#user-name" ).click();
    			}
    		} 
    });          
};
		

////////////////////// JS NEW CAROUSEL //////////////////////////////

    var flexhtml = new Array();
    var flextotal = 0;
   
function flexcarousel_setup(flexhtml) {

    var first_slide = 0;
    var last_slide = 0;
    var max_view = 4;
    var max_move = max_view;    // originally 1
     
    $('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        slideshow: false,
        itemWidth: 200,
        itemMargin: 10, 
        controlNav: true,
       
        maxItems: max_view, 
        //before: function(slider){ display('before ' + slider.count) },
        prevText: "Previous",
        start: function(slider){
            
            localStorage.setItem('images',''); 
            localStorage.setItem('lastpageno',0);  
   
            display('flexcarousel_setup start 1st='+first_slide+' last='+last_slide);
            first_slide = 1;
            
            if (flexhtml.length < max_view) {
                
                for (var i= max_view - flexhtml.length;i<max_view;i++) flexhtml[flexhtml.length] = '<li></li>'; 
                }
            // add slides here
            for (var i=0;i<flexhtml.length;i++) {
                slider.addSlide(flexhtml[i]); 
                slider.flexAnimate(0);               
                }
            last_slide = first_slide + flexhtml.length - 1; 
           
            display('flexcarousel_setup last slide='+ last_slide);
            //slider.flexAnimate(0);
        
        },
        move: max_move, 
        end: function(slider) {
            display("flexcarousel_setup need more pix. 1st="+first_slide+' last='+last_slide);
            //display('flexcarousel_setup currentSlide='+slider.currentSlide);
            
            first_slide = last_slide + 1; 
            
            get_more_slides(first_slide,last_slide); 
                
            display('flexcarousel_setup flexhtml.length='+flexhtml.length+ '-- flextotal='+flextotal);
           
            if (flextotal == 0) {
 
                return;
                }
                
            for (var i=first_slide;i<flexhtml.length;i++) {
                slider.addSlide(flexhtml[i]);
                slider.flexAnimate(first_slide);
                }
            last_slide = first_slide + flexhtml.length - 1;
            //slider.flexAnimate(first_slide);                
            }
        });
 
  
         
};

function get_more_slides(first_slide,last_slide) {
 
    var max_return_rows = parseInt(<?php echo MAX_RETURN_ROWS - 1; ?>,10);
    var last = first_slide + max_return_rows;
 
    display('get_more_slides '+max_return_rows +' '+last);
    display('get_more_slides first_slide='+first_slide +' last_slide='+last_slide);
 
    if (last_slide > max_return_rows) flexcarousel_getItems(first_slide,last);
    //display('get_more_slides '+flexhtml);
        
};
  
function flexcarousel_getItems(first,last) {
  
    var url_link = "<?php echo $config_dynamic_ajax; ?>";	
   
    display('flexcarousel_getItems...first: '  + first);  
    //display('flexcarousel_getItems...origin: '  + origin);  
 
    loaded = true;
 
    //display('flexcarousel_getItems...jcar: '  + jcar);  
 
    display('flexcarousel_getItems...first: '  + first); 
    display('flexcarousel_getItems...last: '  + last); 
  
    var str = 'origin=' + jcar + '&mode=' + mode + '&search_level=' + search_level +
              '&first=' + first + '&last=' + last + '&author=' + author + 
              '&album=' + album + '&cat_id=' + cat_id +
              '&pledge_id=' + pledge_id;
              
    //if (logged_user_id != 0) 
        str += '&user_id='+ logged_user_id;  
 
    if (verbose) str += '&debug=1';
    display('flexcarousel_getItems...str: '  + str);  
    
    var async_val = false;
    if (first == 1) async_val = true;
             
    $.ajax( {
        type: "POST",
        url: url_link,
        cache: false,
        data: str,
        async: async_val,
        error: function(xml,textStatus, errorThrown) {
            display("ajax error: "+textStatus+" error thrown: "+errorThrown);
            
            },        
        success: function(xml) {
            display("url_link: "+url_link);
            flexcarousel_itemAddCallback( first,  last, xml);
  
        },        
        complete: function( message ) {
                     //displayWait(carousel);
                     
                    },

        dataType: 'xml'
    } );
   
};

 
function flexcarousel_itemAddCallback( first, last, xml) {
 
    display('flexcarousel_itemAddCallback...first: '  + first);     
    display('flexcarousel_itemAddCallback...last: '  + last);     

    search = 0;
 
    // test call back
    var origin = jQuery('origin', xml).text();
    var titles = new Array();
    var time_ago = new Array();
    
    var latLong = new Array();
    var maxlevel = new Array();
 
    var details_id = new Array();
    var user_id = new Array();
    var attributes = new Array();
    var gallery = new Array();
    var albums = new Array();
    var pictures = new Array();
    var first_paid_page = new Array();
    var description = new Array();
    var author = new Array();
    
    var cat_menu = new Array();
 
    var ad_cat = new Array();
 
    var ad_zip = new Array();
    var msg  = '';
    var uid = logged_user_id; 
    
    display('flexcarousel_itemAddCallback...uid: '  + uid);  
 
    if (jQuery('mode', xml).text()) {
        mode = jQuery('mode', xml).text();
        search_level = 99;
        }
 
    var breadcrumbs = jQuery('breadcrumbs', xml).text();
 
    $("#breadcrumbs").html(breadcrumbs);
    
    // Set the size of the carousel
    var total = jQuery('total', xml).text();
 
    total = parseInt(total,10);
    display('flexcarousel_itemAddCallback...total: '  + total); 
     
    flextotal = total;          // save in global

    var mysql = jQuery('mysql', xml).text();
 
    display('flexcarousel_itemAddCallback...mysql: '  + mysql); 
        
    if (total == 0 || isNaN(total)) {
        if (mysql == 'EOF') return;
        $("#breadcrumbs").html("There were no entries matching your request ");
        alert("There are no entries matching your request "); 
        //window.location = '<?php echo HTTP_HOST; ?>';
        return;
        }

  
    var width = '<?php echo $config_thumb_width; ?>';
    var height = '<?php echo $config_thumb_height; ?>';
 
    jQuery('title', xml).each(function(i) {
        titles[i] = jQuery(this).text();  
        display('flexcarousel_itemAddCallback...title '  + titles[i]);     

    });


    jQuery('time_ago', xml).each(function(i) {
        
        time_ago[i] = jQuery(this).text();  
        time_ago[i] = "Released: " + $.timeago(time_ago[i]);
					
        display('flexcarousel_itemAddCallback...time_ago '  + time_ago[i]);     

    });
     
    jQuery('gallery', xml).each(function(i) {
        gallery[i] = jQuery(this).text();
        
    }); 
 
    jQuery('album', xml).each(function(i) {
        albums[i] = jQuery(this).text();
        
    }); 

 
    jQuery('pictures', xml).each(function(i) {
        pictures[i] = jQuery(this).text();
        
    }); 
     
    jQuery('first_paid_page', xml).each(function(i) {
        first_paid_page[i] = jQuery(this).text();
        
    }); 

     
    jQuery('description', xml).each(function(i) {
        description[i] = jQuery(this).text();
    }); 
     
    jQuery('author', xml).each(function(i) {
        author[i] = jQuery(this).text();
    }); 
    
    var params = {};

    jQuery('image', xml).each(function(i) {
        var index = first + i;
 
        var c_url = jQuery(this).text();
        //display('flexcarousel_itemAddCallback...c_url '  + c_url); 
 
        params = { 
            'control': 'occupied',
            'index': index,
            'origin': origin, 
            'url': c_url,
            'title': titles[i],
            'uid':uid, 
            'gallery': gallery[i],
            'albums': albums[i],
            'zip': zip, 
            'pictures': pictures[i],
            'first_paid_page': first_paid_page[i], 
            'width': width,
            'height': height, 
            'description': description[i],
            'author':author[i],
            'time_ago':time_ago[i],
            'dummy': 'dummy'                 
            };
 
        flexhtml[i] = flexcarousel_prepHTML(params);
       
        //display('flexcarousel_itemAddCallback...flexhtml '  +flexhtml ); 
      
    });

    //display('flexcarousel_itemAddCallback '+flexhtml);
      
    if (first == 1) {
        flexcarousel_setup(flexhtml);         
       
        check_captcha();
             
        } else {
        flexhtml.length = total;
        } 
 
};


function flexcarousel_prepHTML(params) {
       //display('flexcarousel_prepHTML: started/called'); 
    var eval_str = ''; 
    $.each(params, function(key, val) {
        eval_str += "var "+ key + "='" + val + "' \n"; 
        //display('flexcarousel_getItemHTML: '+ origin + ': ' + key + '=' + val);   
    }); 
    eval(eval_str);
    // pictures_single/pic/{album}/page-1?cp=1
    //display('admin level='+admin_level); 
 
    if (typeof(admin_level) == 'undefined' || admin_level == 0) {
        // vanilla user
        //var link = '<?php echo HTTP_HOST; ?>/pictures_single/pic/'+albums+'/page-1?cp=1';
				//display('flexcarousel_prepHTML: admin level= first case'); 
        var link = '<?php echo HTTP_HOST.NICE_ALBUM_LINK; ?>'+albums+'/';
        
        } else {
				//display('flexcarousel_prepHTML: admin level= second case'); 
    				if (albums=='157') {
    				var link = "author/index.php?c=albums/add&gallery_id="+logged_user_id ;
    				} else {
    				var link = '<?php echo HTTP_HOST.NICE_ALBUM_LINK; ?>'+albums+'/';
    				}
 			
        }
 
    var pages = pictures + ' page'; 
    if (pictures > 1) pages += 's';
    
    var html = '';
		html += '<li class="albumbox" >';

    html += '<h2><a href="javascript:void();" ';
    html += ' onmouseover="showMore('+index+');" ';
    html += ' onmouseout="showLess('+index+');" ';  
    html += '>' +  title + ' </a></h2>';
    
    html += '<span  class="imageX" id="imageX_'+index+'" ';
    html +=  ' style="width:'+width+'px;height:'+height+'px;" '; 
  
    html += '>';  
        
    html +='<a href="' + link + '"  onmouseover="return check_access('+albums+'); " >';
 
    html += '<img  id="alb_'+ albums + '" title="" src="' + url +  '"  ';
 
    html +=  ' width="'+ width + '" height="' + height + '"  ';
    
    html += ' /></a>';
 
    html += '<span class="textX" id="textX_'+index+'" ';
    html += 'style="display:none;top:20px;position:absolute;width:'+width+'px;height:'+ height+'px;" '; 
    html += '>'; 
 
    html += '<h3 class="ui-widget-header ui-corner-all">Author: '+author+'</h3>'; 
        
    html += '<h4 class="ui-widget-content ui-corner-all">' + time_ago + '</h4>';
    
    html += '<p>';

    if (description == '') description = 'There is no description for this album'; 
                 
    html += description; 
    html += '</p>'; 
    html += '</span>';
 
    html += '</span>';
                                            
    html += '<div  class="pictext" >' +  pages + '</div>';
    html += '<div class="clear"></div>';
    
    html += '</li>';
	       
    return html;
}; 

 
//////////////////////////// zormics //////////////////////////////////////////    

function get_new_albums() {

    jcar = 'new_albums';  
    display('get new_albums max_tiles=' + max_tiles);

    flexcarousel_getItems(first,last);

};

      
function get_authors() {

    jcar = 'authors';  
    display('get authors max_tiles=' + max_tiles);
 
    reset_flexslider(true);
      
};

function get_author_albums() {

    jcar = 'author_albums';  
    display('get author_albums max_tiles=' + max_tiles);
 
    reset_flexslider(true);
    
};

function reset_flexslider(refill) {
    
    display('reset_flexslider >'  +  typeof(flexhtml) +'<');
    
    if (typeof(flexhtml) != 'undefined') {
        flexhtml.length = 0;
        $(".flexslider").remove();
        $(".slider").append("<div class='flexslider carousel' style='margin-left: 0px;margin-right: 0px;'><ul class='slides'></ul></div>");
        }
        
    if (refill) flexcarousel_getItems(first,last);
};
 
function saveOrder4() {

	var data = $("#list4 li").map(function() { return $(this).data("itemid"); }).get();
	var datax = $("#list4 div").map(function() { return $(this).data("itemidx"); }).get();
    var itemidz = $("#itemidz").val(); 

    // need to collect all tags with id=picture_id_ 
    
    updateServer(itemidz,data,datax); // 2,1,3,4,5... example of new list... see pictures.php
//alert(data);   

};

function mapArrayToString(src) {
    var blkstr = $.map(src, function(val) {
    return val;
    }).join("|");
    return blkstr;
    //alert(blkstr)
};
            
function updateServer(itemidz,mapafter,mapbefore) {
// src is an array

    var url_link = "<?php echo $config_dynamic_ajax; ?>";
    
// alert('logged_user_id='+logged_user_id)
    var str = 'origin=reorder_pages&mapafter=' + 
            mapArrayToString(mapafter) + 
            '&mapbefore=' +
            mapArrayToString(mapbefore) +
            '&itemidz=' + itemidz +
            '&user_id='+logged_user_id +
            '&v=' + (new Date()).getTime();     
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
            var newlist = jQuery('newlist', xml).text();
            var status = jQuery('status', xml).text();
            
            display('return value: '+newlist+'--status:'+status);
            if (status == 'notok') return;

        },        
        complete: function( message ) {
             //alert('ok');
             
            },

        dataType: 'xml'
    } );

};  
  
////////////////////////////////////////////////////////////////////////////////
 
function start_search() {
 
    display('start search');
    
    loaded = false;
    author = $( "#author" ).val();
    album = $( "#album" ).val();
    origin = 'authors';

    //remove single page carousel
 
    $(".flexslider_single").remove();
    $(".slider").append("<div class='flexslider_single carousel' style='display:none;margin-left: 0px;margin-right: 0px;'><ul class='slides'></ul></div>");
 
    dispatch();
};

function dispatch() {
  
    display('dispatch origin: >'  +  origin +'<');
 
    radius = 0;
 
    first = 1;
    last = <?php echo MAX_RETURN_ROWS; ?>
       
    switch (origin) {
        
        case 'new_albums':
            get_new_albums();
            break;

        
        case 'author_albums':
            get_author_albums();
            break;
             
        case 'authors':
            get_authors();
            break;
                       
        } 
	   
};
    
    // include the login button fx 
    <?php include('ajax.js.inc'); ?>

    if ($("#ppsubmitBtn").length > 0){
        $("#ppsubmitBtn").click();
        }

/* no need in zormics for now

    if (! verbose) {
        $('#vscroller').vscroller({
            newsfeed:'<?php echo $newsfile; ?>', 
            speed:1000,
            stay:4000,
            cache:false
            });    
        } else {
        $('#vscroller').html('No news while testing!');
        }   
*/ 
    prep_authors_select();  // should be back to cat
            
    if (verbose) $( "#display_log" ).css("display",'block'); 
 
    // sliding panel  
    $(".sliding-trigger").click(function(){
 
		$(".sliding-panel").toggle("fast");
		$(this).toggleClass("active");
	 
		return false;
	});
/* 
    $('#headerx').mouseover(function(){
      toggle_header();
    }).mouseout(function(){ 
      toggle_header();
    });
*/
     
    show_header();
            
    check_error();

    background_album = get_background();

    $( "#mysettings_tabs" ).bind( "tabsselect", function(event, ui) {
        showCalendar(ui);
    });   
// end jQuery       
});
 
function showMore(index) {

    var options = {}; 
    var element =  "#imageX_"+index+" a";
 
    $(element).hide();
    
    var element =  "#textX_"+index; 
 
    $(element).show();
 
}; 


function showLess(index) {

    var options = {}; 
    var element =  "#textX_"+index;
 
    $(element).hide();
    
    var element =  "#imageX_"+index+" a"; 
    $(element).show();


};

function check_access(album_id) {
 
    var element = "#alb_"+album_id;
    
    if ($(element).attr('title') != '') return;
 
    var url_link = "<?php echo 'index.php?'.GET_CONTROLLER.'=pictures_checkAccess'; ?>";
 
    var str = 'album_id=' + album_id;
    if (verbose) str += '&debug=1';  
       
    display('check_access str: '+str);
                     
    $.ajax( {
            type: "POST",
            url: url_link,
            cache: false,
            data: str,
            async: false,
            error: function(xml,textStatus, errorThrown) {
                    display("ajax error: "+textStatus+" error thrown: "+errorThrown);
                    },        
            success: function(message) {
                    
                    var obj = jQuery.parseJSON(message);
                    
                    display('check_access success: '+obj.access);
                            
                    if (obj.access == 'token_access' || 
                        obj.access == 'no_access') {
                        $(element).attr('title','Private album.');
                        } else {
                        $(element).attr('title','Accessible album');
                        } 
 
                    },        
            complete: function( message ) {
                    //display('check_access complete');   
                    } 
            } );  
};

function showCalendar(ui) { 
        
    //alert(ui.index+'--'+ui.tab )
 
    
    if (ui.index != 2) return false;

    //var url_link = 'app/controller/events.json.php';
    var str = '&origin=eventCalendar&user_id=' + logged_user_id; 
    if (verbose) str += '&debug=1';       
           
    var url_link = "<?php echo $config_dynamic_ajax; ?>";
 
    $("#eventCalendar").eventCalendar({
        eventsjson: url_link,
        params: str,
		minSliderHeight: '220px',
        minCalendarWidth: '625px',		 
        showDescription: true,
        eventsScrollable: true
    });
};
 
function check_error() {
 
    getQueryParms(); 
 
    var action = 0;
    if (query_vars.error == 'no_page') {
        $( "#error-msg p" ).text("This album has no accessible page!"); 
        $( "#error-msg" ).dialog({ title: "Album Error Message" });
        action = 1;
        }  
        
    if (query_vars.access == 'no_access') {
 
        $( "#error-msg p" ).text('This is a private album.');
        $( "#error-msg" ).dialog({ title: "Access Control Message" });
        action = 1;
        } 

    if (query_vars.access == 'login_access') {
 
        $( "#error-msg p" ).text('Please login to access this album');
        $( "#error-msg" ).dialog({ title: "Access Control Message" });
        action = 1;
        } 

    if (query_vars.access == 'not_white_listed') {
 
        $( "#error-msg p" ).text('Your email is not white listed. You cannot access this album');
        $( "#error-msg" ).dialog({ title: "Access Control Message" });
        action = 1;
        } 

    if (query_vars.access == 'was_white_listed') {
 
        $( "#error-msg p" ).text('Your email authorization has expired. You cannot access this album anymore');
        $( "#error-msg" ).dialog({ title: "Access Control Message" });
        action = 1;
        } 
                
    if (query_vars.access == 'token_access') {
 
        $( "#dialog-email" ).dialog({ title: "Access Control Message" });
        action = 2;
        } 
        
    if (action == 0) return;
    
    if (action == 1) {
        $( "#error-msg" ).dialog({
    	   autoOpen: true,
    	   modal: true,
    	   buttons: {
    		Ok: function() {
    			$( this ).dialog( "close" );
 		
    			window.location = 'index.php';  // to get rid of all url params
    			}
    		  } 
            });     
        }  
 
    if (action == 2) {
 
        $( "#dialog-email" ).dialog({
            autoOpen: true,
            modal: true,
    			 
    		buttons: {
    				Ok: function() {
 
 
                        $("#email_form #album_id").val(query_vars.al); 
                        var str = $("#email_form").serialize(); 
 //alert("str="+str);
                        $( "#dialog-email" ).dialog( "close" ); 
 
                        $( "#email_form" ).submit();  
    					  
    				},
    				Cancel: function() {
    					$( this ).dialog( "close" );
    				}
    			},
    		close: function() {
    				 
    			}
            });     
        }                     
            
};

function goto_album(album_id) {

    if (album_id == 0 || album_id == '') return;
    
    window.location = '<?php echo NICE_ALBUM_LINK; ?>' + album_id+'/';
};
  
function get_background() {
 
    //the list must be created in controller based on all pix in /pictures/backgound
    var images = '<?php echo $background_images; ?>';
  
    var randomImages = images.split('|');
    var rndNum = 0;
   
    if (localStorage.getItem('rndNum') == 'undefined') localStorage.setItem('rndNum',0);
    while (localStorage.getItem('rndNum') == rndNum) rndNum = Math.floor(Math.random() * randomImages.length); 
    
    localStorage.setItem('rndNum',rndNum);
    
    var image = "url(/" + ASSET_PICTURES +  "/background/";
    if (DOMAIN_OWNER != 0) image += SHORT_HOSTNAME +'/';
    image += randomImages[rndNum] + ") ";

    var sx = randomImages[rndNum].split('_');
    var album_id = sx[1];
 
    $("#header").css("background-image",image);
    var width = $("#header").css("width");
 
    $("#header").css("background-size",width);
    
    return album_id;
};

function show_header() {
    
    $('#headerx').addClass("headerx_max");          
    $('#headerx_right').show();

};
function toggle_header() {
 
    $('#headerx').toggleClass("headerx_max");          
    $('#headerx_right').toggle();
 
};

function show_news() {
 
    if ($( "#user-name" ).text().indexOf("Guest") == 0) {
        alert("Please log in first");
        return;
        }
        
    $( ".sliding-panel").toggle("slow");
    $( ".sliding-trigger").toggleClass("active");
                  
    $( "#mysettings_tabs" ).tabs();
    $( "#tabs-1_1" ).text("please access the account settings  from the top navbar");
    $( "#mysettings_tabs" ).dialog( "open" );
              
    $( "#mysettings_tabs" ).tabs( "option", "disabled", [0,1,2,3,4,5,] );                
                    
    $( "#mysettings_tabs" ).tabs('select', 6);

}

function show_settings() {
 
    if ($( "#user-name" ).text().indexOf("Guest") == 0) {
        alert("Please log in first");
        return;
        }
        
    $( ".sliding-panel").toggle("slow");
    $( ".sliding-trigger").toggleClass("active");
                  
    $( "#mysettings_tabs" ).tabs();
    $( "#tabs-1_1" ).text("please access the account settings  from the top navbar");
    $( "#mysettings_tabs" ).dialog( "open" );
              
    $( "#mysettings_tabs" ).tabs( "option", "disabled", [0,2,3,4,5,6] );                
                    
    $( "#mysettings_tabs" ).tabs('select', 1);
  
};

function show_about() {
        
    $( ".sliding-panel").toggle("slow");
    $( ".sliding-trigger").toggleClass("active");
                  
    $( "#mysettings_tabs" ).tabs();
    $( "#tabs-1_1" ).text("please access the account settings  from the top navbar");
    $( "#mysettings_tabs" ).dialog( "open" );
              
    $( "#mysettings_tabs" ).tabs( "option", "disabled", [0,1,2,3,4,6] );                
                    
    $( "#mysettings_tabs" ).tabs('select', 5);
};
       
/**
 * Helper function for printing out debug messages.
 * Not needed for jCarousel.
 */
var row = 1;

function display(s) {
    // Log to Firebug (getfirebug.com) if available
    //if (window.console != undefined && typeof window.console.log == 'function')
      //  console.log(s);
    //alert("verbose="+verbose); 
    if (! verbose) return;
 
    if (row >= 1000)
        var r = row;
    else if (row >= 100)
        var r = '&nbsp;' + row;
    else if (row >= 10)
        var r = '&nbsp;&nbsp;' + row;
    else
        var r = '&nbsp;&nbsp;&nbsp;' + row;

    jQuery('#display_log').html(jQuery('#display_log').html() + r + ': ' + s + '<br />').get(0).scrollTop += 10000;
 
    row++;
};

function checkMinMax(val,min,max) {

    if (! isNumber(val)) {
        val = 0;
        return true;
        }
 
    if (val >= min && val <= max) return true;
    
    return false;
 
};

function isNumber(n) {
  return !isNaN(parseFloat(n)) && isFinite(n);
};
// Removes leading and ending whitespaces
function trim( value ) {

    if (value.length == 0) return '';	
    if (typeof(value) != 'string') return ''; 
 
    var sInString = value.replace( /^\s+/g, "" );// strip leading
    var result = sInString.replace( /\s+$/g, "" );// strip trailing	
    
    return result;
    
};
  
</script>

</head>
<?php flush(); ?>
<body style="background-image: url('<?php echo $background_pattern; ?>');">
<div id="fb-root"></div>
<script>
 
    var channel_url = '<?php echo $config_site_url; ?>'+'/channel.html';
    var fb_app_id = '<?php echo $fb_app_id; ?>'; 
//alert(channel_url) 
    var fb_name = '';
    var fb_email = '';
    var fb_uid = '';
  
    window.fbAsyncInit = function() {
   
        FB.init({
 
        appId      : fb_app_id,     // App ID
        channelUrl : channel_url,   // Channel File
        status     : true,          // check login status
        cookie     : true,          // enable cookies to allow the server to access the session
        xfbml      : true           // parse XFBML
        });
 
        FB.Event.subscribe('edge.create',
            function(href, widget) {
                //alert('You liked the URL: ' + href);
                updateLikes(href,'like');
                }
        );

        FB.Event.subscribe('edge.remove', 
            function(href, widget) {
                //alert('You just unliked '+href);
                updateLikes(href,'unlike');
                }
        ); 
                    
        // Additional initialization code here
        FB.getLoginStatus(function(response) {
            if (response.status === 'connected') {
                // the user is logged in and has authenticated your
                // app, and response.authResponse supplies
                // the user's ID, a valid access token, a signed
                // request, and the time the access token 
                // and signed request each expire
                // the signed request will be decrypted in controller/layout
            
            display("connected to facebook and app is authenticated");   
                        
            fb_uid = response.authResponse.userID;
            display('fb uid='+fb_uid);
            
            var url = '/me?fields=name,email';
            FB.api(url, function (response) {
                fb_name = response.name;
                fb_email = response.email;
               
                $( "#email" ).val(fb_email);
                $( "#password" ).val('no-password-needed');
                $( "#nickname" ).val(fb_name);
                
                submit_login();
                
                });
                
            var accessToken = response.authResponse.accessToken;
            } else if (response.status === 'not_authorized') {
                // the user is logged in to Facebook, 
                // but has not authenticated your app
                
                display("logged in to facebook");                  
                } else {
                // the user isn't logged in to Facebook.
                
                display("not logged in to facebook");                  
                }
            }); 
    };
 
 
(function(d, s, id) {
    
    var fb_app_id = '<?php echo $fb_app_id; ?>'; 

    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) return;
    js = d.createElement(s); js.id = id;
    js.src = "//connect.facebook.net/en_US/all.js#xfbml=1&appId="+fb_app_id;
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));  

function updateLikes(href,mode) {
    // delta=1 to add and -1 to remove
    var sx = href.split('al/');
    sx = sx[1].split('/');
    var album_id = sx[0];
    

    var url_link = "<?php echo $config_dynamic_ajax; ?>";
 
    var str = 'origin=update_likes&album=' + album_id +'&mode='+mode;    
    display('updateLikes str='+str);
       
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
 

        },        
        complete: function( message ) {
             //alert('ok');
             
            },

        dataType: 'xml'
    } );
};
/* */   
</script>
 
<?php echo $left_menu; ?>

<div align="center">
<div>

    <div id="headerx" style="background-color:<?php echo $masthead_color;?>">
      <div  id="headerx_top" >		
 
        <div class="floatleft">
			<a href="<?php echo $url; ?>" title="<?php echo $description; ?>">
                <img src="<?php echo $logo; ?>"  alt="<?php echo $description; ?>" /></a>
            <div style="margin-top:-5px;" ><?php echo $description; ?></div>  		
        
        </div>
    
        <div id="headerx_right" style="height:70px;">
            <div class="floatright ">
	 
                <ul id="Navigation">

                <li  style="margin-top:10px;" >
        <?php 

            $tmp1 = 'none'; 
            $tmp2 = ''; 
            if (isset($this->session->data['user_id']) && $this->session->data['user_id']) {
                $user_id = $this->session->data['user_id'];
     
                if ($this->session->data['manager_store_id'] != 0 && 
                    $this->session->data['twitter_token'] == ''){
                    // we have a merchant that hasn't yet approved pin2shop on twitter
                    $tmp1 = ''; 
                    $tmp2 = 'none';
                    }
        ?>
                    <button id="login-logoff"   style="font-size:98%;" >Logoff</button>

        <?php } else {  ?> 
                    <button id="login-logoff"   style="font-size:98%;" >Login</button>
        <?php } ?>  
        
                    <button id="help-site"   style="font-size:98%;" >Help</button>

                    <a href="?c=layout&twitter_reg=step_1"><img style="display:<?php echo $tmp1; ?>; 
                            vertical-align: middle;" class="store-manager"
                            src="<?php echo $tpath; ?>/images/lighter.png" 
                            title="Sign in with Twitter"/></a>

                </li>

                <li style="margin-top:5px;" ><a href="javascript: void(0)" id="user-name" ><?php echo $settings.$more_settings; ?></a></li>
                
                </ul>		
            </div>	
            <div class="floatright"  style=" margin-top:10px;" > 
                <form id="seqSearch" >
    
                <input type="text" 
                    name="author" id="author" 
                    value="Author"
                    title="Type in an author name or part of it" 
                    class="text ui-widget-content ui-corner-all" />
     
                <input type="text" 
                    name="album" id="album" 
                    value="Title" 
                    title="Type in a title name or part of it"
                    class="text ui-widget-content ui-corner-all" /> 
                         
                <input type="hidden" name="origin" value="seqSearch" />
        
                </form>
                <div  class="floatleft " >   
                    <button id="search"  style="font-size:98%;" >Search</button> 
                </div>
            </div>        
        
        </div> 
        
      </div>    
    </div>
</div><div class="page">
 
    <div id="header" onclick="goto_album(background_album);" >

        <div id="welcome" 
            style="width:330px;height:330px; background-image:url(/<?php echo $welcome_image; ?>)"></div>
        <div id="error-msg" title="" ><p></p></div>
        <div class="header_buttons">
        <?php if ($header_expand_btn == '1') { ?>
            <span id="img_expand"  
                style="margin-right: 15px;color:white;float: right;background-color:orange;display: inline-block;padding:6px;font-size:18pt; cursor:pointer" >
                <b>+</b></span> 
        <?php } ?>        
            <span id="start_comic"  
                style="margin-right: 65px;color:white;float: right;background-color:#D01616;display: inline-block;padding:6px;font-size:18pt; cursor:pointer" >
                <?php echo $call_to_action; ?></span>            
            <span id="fb_signin"  
                style="margin-right: 20px;color:white;float: right;background-color:#3B71C1;display: inline-block;padding:6px;font-size:18pt; cursor:pointer" >
                <?php echo $config_sign_in_msg; ?>
                </span>

        </div>
    </div>
  
    <div> 
		<div id="content" style="margin-left:10px;" >	
 						
				<?php echo $content; ?>					

	
		</div>

		<p id="display_log"  ></p> 
        <div class="footer">
        
            <span class="floatleft"><?php echo $google_plus; ?></span>
			<span class="floatright"><a href="http://www.emediaboard.com/" target="_blank">eMediaboard (c)</a></span>
        
        </div>

    </div>
  	
</div>
<!-- ////////////////////// edit single///////////////////////////--> 
<div id="edit_page_dialog" title="Edit Page data">
         
	<form id="edit_page_form" method="post">
	<fieldset>
 		<label for="title">Page Title</label>
		<input type="text" name="title" value="<?php echo $page_title; ?>" class="text ui-widget-content ui-corner-all" />
		<label for="description">Page Description</label>
		<textarea rows="3" cols="30" name="description" class="ui-widget-content ui-corner-all" ><?php echo $page_desc; ?></textarea>
	</fieldset>
 
    <input type='hidden' name='picture_id' id='picture_id' value='<?php echo $picture_id; ?>' /> 
 
	</form>
</div>
<!-- //////////////////////login form ///////////////////////////--> 
 
<div id="dialog-container" style="display:none;">
<div id="site-help" title="How does it work?" ><?php echo $site_info; ?></div>
<div id="dialog-email" title="White List Control">
        
	<form id="email_form" action="index.php?c=pictures" method="post">
	<fieldset>
 		<label for="email">This album is private but you if you received an invite it contains a token.<br/>Token:</label>
		<input type="text" name="email_token" id="email_token" value="" class="text ui-widget-content ui-corner-all" />
 	</fieldset>
	<input type='hidden' name='album_id' id='album_id' value='' /> 
 
	</form>
</div>
<div id="dialog-login" title="Login Form">
	<p class="validateTips">All form fields are required.</p>
            
	<form id="login_form" method="post">
	<fieldset>
 		<label for="email">Email</label>
		<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
		<label for="password">Password</label>
		<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	<input type='hidden' name='mode' value='login' /> 
    <input type='hidden' name='nickname' id='nickname' value='' />      
	</form>
	
</div>
<div id="show-terms" title="Terms and Conditions For participating Authors" ><?php echo $store_info; ?></div>


<div id="dialog-register" title="Registration Form" >
	<p class="validateTips">Welcome. Please fill in the form.</p>
    
	<form id="register_form" method="post">
	<fieldset>
 		<label for="email">Email</label>
		<input type="text" name="email" id="r_email" value="" class="text ui-widget-content ui-corner-all" />

		<label for="password">Password</label>
		<input type="password" name="password" id="r_password" value="" class="text ui-widget-content ui-corner-all" />

		<label for="password2">Re-type Password</label>
		<input type="password" name="password2" id="r_password2" value="" class="text ui-widget-content ui-corner-all" />

		<label for="nickname">Pick a nickname</label>
		<input type="text" name="nickname" id="r_nickname" value="" class="text ui-widget-content ui-corner-all" />
	
    </fieldset>
	<input type='hidden' name='mode' value='register' />  
	</form>
</div>
<!-- using jquery tabs -->
<div id="mysettings_tabs"  style="display:none;"  title="My Settings"> 
	<ul>
		<li><a href="#mysettings_tabs-1">Account</a></li>
 		
		<li><a href="#mysettings_tabs-2">Contact</a></li>
        <li><a href="#mysettings_tabs-3">Schedule</a></li>
		<li><a href="#mysettings_tabs-4">Password</a></li>
        
        <li style="display:none;" ><a href="#mysettings_tabs-5">Author Setup</a></li>
        
        <li><a href="#mysettings_tabs-6">About</a></li>
        <li><a href="#mysettings_tabs-7">News</a></li>
	</ul>
 	
	<div id="mysettings_tabs-1" >
		<p id="tabs-1_1" ></p>
		<p id="tabs-1_2" ></p>
		<p id="tabs-1_3" >
		<form id="contact_form" method="post">
 
		<input type="hidden" name="mode" value="upd_account" />  
                    	
        </form> 
        </p>
        <p id="tabs-1_4" ></p>
	</div>
	<div id="mysettings_tabs-2">
    

	   <p class="validateTips">Please fill in the form to send a message to the admin.</p>
    
	   <form id="admin_msg_form" method="post">
	   <fieldset>
 
		<label for="admin_msg_text">Your Message</label>
		<textarea name="admin_msg_text"  rows="3" cols="65" class="text ui-widget-content ui-corner-all" ></textarea>
        <br/>
		<label for="captcha">Enter the code in the box below:</label>
		<input type="text" name="captcha" id="captcha" 
                value="" autocomplete="off" 
                class="text ui-widget-content ui-corner-all" />
		<br/>
         
        <p><img src="index.php?c=layout/captcha" id="captcha" /></p>
        <br/> 
         	
        </fieldset>
        <input id="admin_msg_btn"  value="Send"  
        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" 
        role="button" aria-disabled="false"  />
	   
       <input type="hidden" name="mode" value="admin_msg" />  
	   </form>
           
    </div>	
    
	<div id="mysettings_tabs-3" >
        <!-- should be tab#2 starting at 0 -->
		<div id="eventCalendar"  ></div>
 
    </div>	
  
    <div id="mysettings_tabs-4" >

	   <p class="validateTips">Please fill in the form to change your password.</p>
    
	   <form id="account_form" method="post">
	   <fieldset>
 
		<label for="r_password1">Password</label>
		<input type="password" name="r_password1" id="r_password1" class="text ui-widget-content ui-corner-all" />

		<label for="r_password2">Re-type Password</label>
		<input type="password" name="r_password2" id="r_password2" class="text ui-widget-content ui-corner-all" />
 	
        </fieldset>
        <input id="account_form_btn"  value="Update"  
        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" 
        role="button" aria-disabled="false"  />
	   
       <input type="hidden" name="mode" value="upd_pass" />  
	   </form>
    </div>	
    
    <div id="mysettings_tabs-5" title="Author Setup" > 

	   <p class="validateTips">Please read the Terms and Conditions
       and approve them before clicking the "Set me Up" button. 
       Once set up you will get a welcome email. Thanks!
       </p>
    
	   <form id="author_form" method="post" >
	   <fieldset>
 
		<label for="name">Public name</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />

		<label for="captcha">Enter the code in the box below:</label>
		<input type="text" name="captcha" id="captcha" 
                value="" autocomplete="off" 
                class="text ui-widget-content ui-corner-all" />
		<br/>
         
        <p><img src="index.php?c=layout/captcha" id="captcha" /></p>
        <br/> 
        
        <input  type='checkbox' id='storeTermsConds' 
            name='storeTermsConds'  >I agree with the Terms and Conditions</a> 

        </fieldset>
	   
        <input id="author_form_btn"  value="Set me Up!"  
        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" 
        role="button" aria-disabled="false"  />
	    
        <input id="terms-show_btn"  value="Show me the T&C"  
        class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-only" 
        role="button" aria-disabled="false"  />

       <input type="hidden" name="mode" value="set_author" />  
	   </form>    
    </div>
    
    <div id="mysettings_tabs-6" title="About" >
    <?php echo $about; ?>
    </div>
    
    
    <div id="mysettings_tabs-7" title="News" >
    <?php echo $news; ?>
    </div>    
</div>
 
</div>

 
</div> 
<!-- /////////////////////// sliding panel /////////////////////////////-->  
<div class="sliding-panel">
<?php if ($this->session->data['admin_level'] == ADMIN_LEVEL) { ?>
 <p>How to set up a new domain: read multidomains.dat</p>
 <?php } ?>
<div style="clear:both;"></div>
<div class="columns">
	<div class="colleft">
	<h3>Navigation</h3>
		<ul>
			<li><a href="<?php echo HTTP_SERVER; ?>" title="home">Home</a></li>
<!--
			<li><a href="javascript:void();" onclick="show_header();" title="Login/Register">Login</a></li> 
			<li><a href="javascript:void();" onclick="show_header();" title="Search">Search</a></li> 
-->			
            <li><a href="<?php echo HTTP_SERVER; ?>/admin" title="admin">Admin</a></li>
			<li><a href="javascript:void();" onclick="show_settings();" title="contact">Contact</a></li>
			<li><a href="javascript:void();" onclick="show_news();" title="news">News</a></li>
			<li><a href="javascript:void();" onclick="show_about();" title="about">About</a></li>
		</ul>
	</div>

    <?php if ($cats) { ?>
	<div class="colleft">
	<h3>Categories</h3>
		<ul>
			<?php echo $cats; ?>
		</ul>
	</div>
    <?php } ?>
    	
	<div class="colright">
		<h3>Social Stuff</h3>
		<ul>
			<li><a href="http://twitter.com/emediaboard" title="Twitter">Twitter</a></li>
			<li><a href="http://designbump.com/user/emediaboard" title="DesignBump">DesignBump</a></li>
			<li><a href="http://digg.com/users/emediaboard" title="Digg">Digg</a></li>
			<li><a href="http://delicious.com/emediaboard" title="Del.Icio.Us">Del.Icio.Us</a></li>
			<li><a href="http://designmoo.com/users/emediaboard" title="DesignMoo">DesignMoo</a></li>
		</ul>
	</div>
</div>
<div style="clear:both;"></div>

</div>
        
<!-- //////////////////////end sliding panel ///////////////////////////--> 
<?php  

if (isset($pp_paykey)) { 
 
    echo "<div style='display:none;' ><form action='". $pp_flow_pay . "' target='PPDGFrame'>
            <input id='type' type='hidden' name='expType' value='light'>
            <input id='payKey' type='hidden' name='payKey' value='" . 
            $pp_paykey .  "'>
            <input type='submit' id='ppsubmitBtn' value='Pay with PayPal'>
            <script>
            var dg = new PAYPAL.apps.DGFlow({ trigger: 'ppsubmitBtn' });
            </script>
            </form></div>";
    }

    // analytics_code
    echo $google_analytics_code; 
    
?>                 
<div style="display:none"
    class="fb-registration" 
    data-fields="[{'name':'name'}, {'name':'email'} ]" 
    data-redirect-uri="<?php echo $config_site_url; ?>"
</div>

</body>
<!-- add some more javascripts here -->
 
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.dragsort-0.5.1.min.js"></script>

<script type="text/javascript" src="<?php echo $template; ?>js/ddslick.js"></script>

<script src="<?php echo $template; ?>js/jcrop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php echo $template; ?>css/jcrop/jquery.Jcrop.css" type="text/css" />

<!-- Core CSS File. The CSS code needed to make eventCalendar works -->
<link rel="stylesheet" href="<?php echo $template; ?>css/eventCalendar/eventCalendar.css">

<!-- Theme CSS file: it makes eventCalendar nicer -->
<link rel="stylesheet" href="<?php echo $template; ?>css/eventCalendar/eventCalendar_theme_responsive.css">

<script src="<?php echo $template; ?>js/eventCalendar/jquery.eventCalendar.js?v=<?php echo time(); ?>" type="text/javascript"></script>

<script src="<?php echo $template; ?>js/eventCalendar/jquery.timeago.js?v=<?php echo time(); ?>" type="text/javascript"></script>

</html>
 