/**
***************************************************************************************************
 * @Software    AjaxMint Gallery
 * @Author      Rajapandian - arajapandi@gmail.com
 * @Copyright	Copyright (c) 2010-2011. All Rights Reserved.
 * @License		GNU GENERAL PUBLIC LICENSE
 **************************************************************************************************
 This source code is licensed under the terms of the GNU GENERAL PUBLIC LICENSE
 http://www.gnu.org/licenses/gpl.html
 **************************************************************************************************
 Copyright (c) 2010-2011 http://ajaxmint.com. All Rights Reserved.
 **************************************************************************************************
**/

$(document).ready(function() {

    $(".moveup,.movedown").click(function(){
										  
        var row = $(this).parents("tr:first");		
		
        if($(this).closest('td').parent().attr('sectionRowIndex')==1 && $(this).is(".moveup")) {
			
			//do nothing
			
		} else {
			if ($(this).is(".moveup")) {
				row.insertBefore(row.prev());
			} else {
				row.insertAfter(row.next());
			}
		}
		
    });
    
    $("#galleryDropdown").change(function(){
        var gallery_id=$(this).val();
        $.ajax({
             type: 'post',
             url: 'index.php?c=pictures/callback',
             dataType: 'html',
             data:{'flag':'albums','gallery_id':gallery_id},
             success: function (html) {
                 $('#album_dropdown').html(html);
             }
         });
    });
    
    $("#delete").click(function(){
          if(!confirm("Are you sure..you want to delete?")) {
              return false;
          }
    });
    $("#delete").click(function(){
          if(!confirm("Are you sure..you want to delete?")) {
              return false;
          }
    });	
    $("#galleryFilter").change(function(){
          if($(this).val())
		  location.href='index.php?c=albums&gallery_id='+$(this).val();
    });		
	
});