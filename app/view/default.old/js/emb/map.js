<script type="text/javascript">

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

};
   
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
