<?php /* ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
using html5 doc type as instructed by Google
<?php */ ?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" dir="ltr" lang="en-US">
<head profile="http://gmpg.org/xfn/11">
 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;">
 
<title><?php echo $title; ?></title>
<link rel="shortcut icon" href="<?php echo $icon; ?>" type="image/x-icon">
<link rel="icon" href="<?php echo $icon; ?>" >
<meta name="description" content="<?php echo $description; ?>" />
<link rel="stylesheet" href="<?php echo $template; ?>css/style.css" type="text/css" media="screen" />
    
<link rel="stylesheet" href="<?php echo $template; ?>css/emb/smoothness/jquery-ui-1.8.18.custom.css" type="text/css" /> 
 
<style type="text/css">
 
#display_log {
    // for debugging only... CSS folks, please don't change it or move it
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
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jQuery_blockUI.js"></script>
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.cookies.2.2.0.min.js"></script>
<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.ui.tinytbl.js"></script>

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.ui.selectmenu.js"></script>
 
<script type="text/javascript" src="<?php echo $template; ?>js/jCarousel/jquery.jcarousel.min.js"></script>
 
<script type="text/javascript" src="<?php echo $template; ?>js/jqGrid/i18n/grid.locale-en.js"></script>
<script type="text/javascript" src="<?php echo $template; ?>js/jqGrid/jquery.jqGrid.min.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?php echo $template; ?>js/tableAddRow/jquery.table.addrow.js" type="text/javascript" ></script>
<script type="text/javascript" src="<?php echo $template; ?>js/vscroller/vscroller.js" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo $template; ?>js\emb\jquery.touchwipe.1.1.1.js" type="text/javascript" ></script>

<script type="text/javascript" src="<?php echo $template; ?>js/emb/jquery.dragsort-0.5.1.min.js"></script>
 
<link rel="stylesheet" type="text/css" href="<?php echo $template; ?>css/jCarousel/skins/pin2shop/skin.css" /> 

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/jqGrid/jquery-ui-1.8.1.custom.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/jqGrid/ui.jqgrid.css" />

<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/emb/jquery.ui.tinytbl.css" />
<link rel="stylesheet" type="text/css" media="screen" href="<?php echo $template; ?>css/vscroller/vscroller.css" /> 

<script  defer src="<?php echo $template; ?>js/flexslider/jquery.flexslider.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $template; ?>css/flexslider/flexslider.css" />
 
<?php require_once('js_vars.php'); ?>
<?php  //echo getcwd(); exit;  //phpinfo(INFO_ENVIRONMENT|INFO_VARIABLES); print_array(debug_backtrace()); exit; ?> 
<script type="text/javascript" src="<?php //echo GMAPKEY_PIN2SHOP; ?>" ></script>
<script type="text/javascript" src="<?php //echo $template; ?>js/emb/gmap3.min.js"></script>
 
<script type="text/javascript" src="https://www.paypalobjects.com/js/external/dg.js"></script> 

<script src="<?php echo $template; ?>js/jcrop/jquery.Jcrop.min.js"></script>
<link rel="stylesheet" href="<?php echo $template; ?>css/jcrop/jquery.Jcrop.css" type="text/css" />

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
        
function setStoreMgr(obj) {
        
    var settings = $('#mysettings_tabs');
 
    if (! $(obj).is(':checked')) {
        settings.tabs('disable',4);
        return;  
        }

    settings.tabs('enable',4);
    settings.tabs('select',4);  
             
};
      
function showMap(obj,index,czip) {

    //display('initialize_map...czip: '  + czip + ' zip: ' + zip);  
    restore_map_button();
        
    var id = obj.id;
    
    display('visible map: ' + visible_map + ' -- id: ' + id);

    if (visible_map == id) {
        return hideMap();
        }

    var map_id = "#" + id;
    var latLong = $(map_id).attr("latLong");

    //display('latlong='+latLong)
    var sx = latLong.split('|');
    latitude = parseFloat(sx[0]);
    longitude = parseFloat(sx[1]);
     
    map_id = map_id + "_map";

    var position = $(map_id).offset();

    var mod = index % max_tiles;  
    if (mod == 0) mod = max_tiles;  
    var left = tiles[mod];     

    var top = position.top;
    display( 'map_id=' + map_id + ' index ' + index + ' top ' + top);
 
    $("#map_canvas").css("left",left);
    $("#map_canvas").css("top",top);
    $("#map_canvas").css("z-index", 10000);
       
    if (visible_map == '' || zip != czip) {
        //$('#map_canvas').gmap3('destroy');     
        zip = czip;
        initialize_map(); 
        }
     
    $("#map_canvas").css("display","");

    map_id = "#" + id; 
    
    saved_bot_button_html = $(map_id).parent().html();
    saved_bot_button_id = map_id;
     
    var button = '';
 
        button += '<a  id="' + id + '" href="javascript:void(0);" onclick="hideMap();" ' +
                 ' class="Button WhiteButton"   >';
        button += '<strong><em></em>' + 'Hide Map' + '</strong><span></span></a>';
    
    $(map_id).parent().html(button);
 
    visible_map = id;
     
    return false;
};

function hideMap() {
    
    //display('hide map');
    $("#map_canvas").css("display","none");
    visible_map = '';
 
    restore_map_button();

};

function restore_map_button() {
    
    if (saved_bot_button_id == '') return;
    
    $(saved_bot_button_id).parent().html(saved_bot_button_html);
    saved_bot_button_id = '';
    saved_bot_button_html = '';

}
   
function initialize_map() {
 
    display('initialize_map...zip: '  + zip);  
   
    $('#map_canvas').gmap3({action:'clear'});
     
    $('#map_canvas').gmap3({
        action: 'addMarker',
        address: zip,
        map:{
            center: true,
            zoom: 12 
        
            } 
 
        });  
 
    var n = latitude + 0.01;
    var e = longitude + 0.02;
    var s = latitude - 0.01;
    var w = longitude - 0.01;
   
    $('#map_canvas').gmap3( {
            action: 'addRectangle',
            rectangle:{
                options:{
                    bounds: {n: n,e: e, s: s,w: w },
                    fillColor : "#FFAF9F",
                    strokeColor : "#FF512F",
                    clickable:true
                    }
                } 
            });  
        
};

</script>


<script type="text/javascript">

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
    $('#zipSearch select').selectmenu({
            style:'dropdown',
            change: selectmenuChange,
            width:170,
            maxHeight: 200
            } );

    $('#zipSearch .ui-widget').css('font-size','0.9em');
};
 
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

jQuery(document).ready(function() {
//
    var query_vars = [];

    check_friendly_url();

    getQueryParms();
//alert (query_vars )
    if (query_vars['author']) {
        $( "#author" ).val(query_vars['author']);
        $( "#album" ).val('Title');
        origin = 'author_albums';
        
        // get all the albums for an author
        start_search();
         
        }
        
    if (query_vars['title']) {
        $( "#album" ).val(query_vars['title']);
        $( "#author" ).val('Author');
        origin = 'author_albums';
        
        // get all the albums for an author
        start_search();
         
        }        
//
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
 
    		var author = $( "#author" ).val(); 
       
    		var album = $( "#album" ).val(); 
    		
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
//alert('getQueryParms: '+q)      
    var needle = '/';
    
    if (q.indexOf(needle) != -1 &&
        q.indexOf('?') == -1) {

        var sx = q.split(needle); 
         
        if (sx.length < 2) return;
        
        // 'pictures' might be a controller name and we should probably test for all of them
        if (sx[1] == 'admin') return;
        if (sx[1] == 'pictures') return;
        if (sx[1] == 'albums') return;   
        if (sx[1] == 'index.php') return;  
        
        query_vars['author'] = sx[1];  
        return; 
       
        }

};

function getQueryParms() {

    var hash;
    var q = document.URL.split('?')[1];

    if (q != undefined){
        q = q.split('&');
        for(var i = 0; i < q.length; i++){
            hash = q[i].split('=');
            query_vars.push(hash[1]);
            query_vars[hash[0]] = hash[1];
            }
        }  
};
    
////////////////////// JS NEW CAROUSEL //////////////////////////////

    var flexhtml = new Array();
    var flextotal = 0;
   
function flexcarousel_setup(flexhtml) {

    var first_slide = 0;
    var last_slide = 0;
    var max_view = 4;
     
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
        move: 1, 
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
              
    if (logged_user_id != 0) str += '&user_id='+ logged_user_id;  
 
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
    var latLong = new Array();
    var maxlevel = new Array();
 
    var details_id = new Array();
    var user_id = new Array();
    var attributes = new Array();
    var gallery = new Array();
    var albums = new Array();
  
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
    
    if (total == 0 || isNaN(total)) {
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
 
    jQuery('gallery', xml).each(function(i) {
        gallery[i] = jQuery(this).text();
        
    }); 
 
    jQuery('album', xml).each(function(i) {
        albums[i] = jQuery(this).text();
        
    }); 
            
    var params = {};

    jQuery('image', xml).each(function(i) {
        var index = first + i;
 
        var c_url = jQuery(this).text();
        //display('flexcarousel_itemAddCallback...c_url '  + c_url); 
 
        params = { 
            'control': 'occupied',
            'origin': origin, 
            'url': c_url,
            'title': titles[i],
            'uid':uid, 
            'gallery': gallery[i],
            'albums': albums[i],
            'zip': zip, 
            'width': width,
            'height': height, 
            'dummy': 'dummy'                 
            };
 
        flexhtml[i] = flexcarousel_prepHTML(params);
       
        //display('flexcarousel_itemAddCallback...flexhtml '  + flexhtml); 
      
    });

    //display('flexcarousel_itemAddCallback '+flexhtml);
      
    if (first == 1) {
        flexcarousel_setup(flexhtml);             
        } else {
        flexhtml.length = total;
        } 
 
};

function flexcarousel_prepHTML(params) {
 
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
        var link = '<?php echo HTTP_HOST; ?>/pictures/al/'+albums+'/';
        
        } else {
        var link = '<?php echo HTTP_HOST; ?>/pictures/al/'+albums+'/';
        }
 
    var html = '';
    html += '<li >';
    
    html +='<a href="' + link + '" >';
    
    html += '<img  id="' + uid + 
                    '_map" src="' + url +  '"  ';
 
    html += ' /></a>';
                                     
    html += '<p>' +  title + '</p>';
    
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
 
    loaded = false;
    author = $( "#author" ).val();
    album = $( "#album" ).val();
    origin = 'authors';

    //remove single page carousel
 
    $(".flexslider_single").remove();
    $(".slider").append("<div class='flexslider_single carousel' style='margin-left: 0px;margin-right: 0px;'><ul class='slides'></ul></div>");
 
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
 
    prep_authors_select();  // should be back to cat
            
    if (verbose) $( "#display_log" ).css("display",'block'); 
 
// end jQuery       
});
       
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
<body >
<div id="fb-root"></div>
<script>
 
    var channel_url = '<?php echo $config_site_url; ?>'+'/channel.html';
    var fb_app_id = '<?php echo $fb_app_id; ?>'; 
 
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

 
  // Load the SDK Asynchronously
(function(d){
  
     var js, id = 'facebook-jssdk', ref = d.getElementsByTagName('script')[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement('script'); js.id = id; js.async = true;
     js.src = "//connect.facebook.net/en_US/all.js";
     ref.parentNode.insertBefore(js, ref);
}(document));
   
   
</script>
 
<div align="center">
	<div class="page">
<span style="floatleft;"> 
    <form id="seqSearch" style="margin-left:30px;" >
    
        <input  style="height:32;float:left;width:150px;" type="text" name="author" id="author"  
                    value="Author" 
                    class="text ui-widget-content ui-corner-all" />
     
        <input  style="margin-left:2px;margin-right:2px;float:left;width:150px;" type="text" 
                    name="album" id="album"  
                    value="Title" 
                    title="Title"
                    class="text ui-widget-content ui-corner-all" /> 
                         
        <input type="hidden" name="origin" value="seqSearch" />
        
    </form><span  style="float:left;" >   
    <button id="search"  style="font-size:98%;" >Search</button> 
    </span>
 
</span> 
		<div id="headerx" >
		<span style="floatleft">
			<a href="<?php echo $url; ?>" title="<?php echo $title; ?>"><img src="<?php echo $logo; ?>" border="0" alt="<?php echo $title; ?>" /></a>
		</span>

		<span>
	 
        <ul id="Navigation">

        <li ><a href="javascript: void(0)" id="user-name" ><?php echo $settings.$more_settings; ?></a></li>
        
        <li>
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
            <button id="login-logoff" >Logoff</button>

        <?php } else {  ?> 
            <button id="login-logoff" >Login</button>
        <?php } ?>  
        
        <button id="help-site" >Help</button>

        <a href="?c=layout&twitter_reg=step_1"><img style="display:<?php echo $tmp1; ?>; 
                vertical-align: middle;" class="store-manager"
                        src="<?php echo $tpath; ?>/images/lighter.png" title="Sign in with Twitter"/></a>

        </li>
         
        </ul>		
        </span>	
<?php /* ?>
<span style="floatleft;"> 
    <form id="seqSearch" style="margin-left:30px;" >
    
        <input  style="height:32;float:left;width:150px;" type="text" name="author" id="author"  
                    value="Author" 
                    class="text ui-widget-content ui-corner-all" />
     
        <input  style="margin-left:2px;margin-right:2px;float:left;width:150px;" type="text" 
                    name="album" id="album"  
                    value="Title" 
                    title="Title"
                    class="text ui-widget-content ui-corner-all" /> 
                         
        <input type="hidden" name="origin" value="seqSearch" />
        
    </form><span  style="float:left;" >   
    <button id="search"  style="font-size:98%;" >Search</button> 
    </span>
 
</span>
<?php */ ?>        
        </div>
        <div id="header" >

            <div class="header_buttons">
         
            <!-- facebook -->
            <img id="fb_signin" src="/pictures/fb_signin.png" style="margin-left:40px;cursor:pointer" />
            <img id="start_comic" src="/pictures/start_comic.png" style="margin-left:40px;cursor:pointer" />
            </div>
        </div>
         
		<div id="content">	
 						
				<?php echo $content; ?>					

	
		</div>

		<p id="display_log"  ></p> 
        <div class="footer">
 
			<span class="floatright"><a href="http://www.emediaboard.com/" target="_blank">eMediaboard (c)</a></span>
        
        </div>

</div>
    	
</div>
        
<!-- //////////////////////login form ///////////////////////////--> 
 
<div id="dialog-container">
<div id="site-help" title="How does it work?" ><?php echo $site_info; ?></div>
<div id="dialog-login" title="Login Form">
	<p class="validateTips">All form fields are required.</p>
            
	<form id="login_form" >
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
<div id="show-terms" title="Sequility Terms and Conditions For participating Authors" ><?php echo $store_info; ?></div>


<div id="dialog-register" title="Registration Form">
	<p class="validateTips">Welcome. Please fill in the form.</p>
    
	<form id="register_form" >
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
		<li><a href="#mysettings_tabs-1">Contact</a></li>
 		
		<li style="display:none;" ><a href="#mysettings_tabs-2">Coupons</a></li>
        <li style="display:none;" ><a href="#mysettings_tabs-3">Pledges</a></li>
       
		<li><a href="#mysettings_tabs-4">Password</a></li>
 
        <li><a href="#mysettings_tabs-5">Store Mgt.</a></li>
        
	</ul>
	<div id="mysettings_tabs-1" >
		<p id="tabs-1_1" ></p>
		<p id="tabs-1_2" ></p>
		<p id="tabs-1_3" >
		<form id="contact_form" method="post">
 
		<input type="hidden" name="mode" value="upd_contact" />  
                    	
        </form> 
        </p>
        <p id="tabs-1_4" ></p>
	</div>
	<div id="mysettings_tabs-2" style="display:none;" ></div>	
	<div id="mysettings_tabs-3" style="display:none;" ></div>	
  
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
    
	   <form id="author_form" method="post">
	   <fieldset>
 
		<label for="name">Public name</label>
		<input type="text" name="name" id="name" class="text ui-widget-content ui-corner-all" />
		 
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
</div>
 
</div>

 
</div> 
<?php  
if (isset($pp_paykey)) { 
 
    echo "<div style='display:none;' ><form action='". $pp_flow_pay . "' target='PPDGFrame'>
      <input id='type' type='hidden' name='expType' value='light'>
      <input id='payKey' type='hidden' name='payKey' value='" . 
      $pp_paykey . 
      "'>
      <input type='submit' id='ppsubmitBtn' value='Pay with PayPal'>
        <script>
            var dg = new PAYPAL.apps.DGFlow({ trigger: 'ppsubmitBtn' });
        </script>
    </form></div>";
    }
?> 
                
<div style="display:none"
    class="fb-registration" 
    data-fields="[{'name':'name'}, {'name':'email'} ]" 
    data-redirect-uri="<?php echo $config_site_url; ?>"
</div>

</body>
</html>
 