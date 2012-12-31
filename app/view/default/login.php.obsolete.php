<?php /* This PHP doesn't seem to be used. Using instead ajax.js.inc 
I rename it .obsolete.php
*/?>
<meta charset="utf-8">
	
	<style>
		 
		label, input { display:block; }
		input.text { margin-bottom:12px; width:95%; padding: .4em; }
		fieldset { padding:0; border:0; margin-top:25px; }
	 
		div#users-contain { width: 350px; margin: 20px 0; }
		div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
		div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
		.ui-dialog .ui-state-error { padding: .3em; }
		.validateTips { border: 1px solid transparent; padding: 0.3em; }
	</style>
	<script>
  
    $(document).ready(function() {  
   
    	$(function() {
     		$( "#dialog:ui-dialog" ).dialog( "destroy" );
    		
    		var email = $( "#email" ),
    			password = $( "#password" ),
    			allFields = $( [] ).add( email ).add( password ),
    			tips = $( ".validateTips" );
    
    		function updateTips( t ) {
    			tips
    				.text( t )
    				.addClass( "ui-state-highlight" );
    			setTimeout(function() {
    				tips.removeClass( "ui-state-highlight", 1500 );
    			}, 500 );
    		}
    
    		function checkLength( o, n, min, max ) {
    			if ( o.val().length > max || o.val().length < min ) {
    				o.addClass( "ui-state-error" );
    				updateTips( "Length of " + n + " must be between " +
    					min + " and " + max + "." );
    				return false;
    			} else {
    				return true;
    			}
    		}
    
    		function checkRegexp( o, regexp, n ) {
    			if ( !( regexp.test( o.val() ) ) ) {
    				o.addClass( "ui-state-error" );
    				updateTips( n );
    				return false;
    			} else {
    				return true;
    			}
    		}
     
            function submit_logoff() {
                      
        		var url_link = 
                    "<?php echo 'index.php?'.GET_CONTROLLER.'=account/login&mode=logoff'; ?>";		    
                //displayWait(true);
    
                $.ajax(
                {
                  type: "GET",
                  url: url_link,
                  data: '',
                  cache: false,
                  complete: function( message ) {
                        //displayWait(false);
                        },
                  success: function( message ) {
               
                        var obj = jQuery.parseJSON(message);
       
                        if (obj.result != 'ok') {
                            alert(obj.result); 
                            return false;
                            }
 
                        var html = '<span class="ui-button-text">Login</span>';             
                        $( "#user-name" ).text('Register');
                        $( "#login-logoff" ).html(html);
                        //$( "#new-request" ).css('display','none');
  
                        return true;
                        }
                });  
            }
            function submit_login() {
     
                var str = $("form").serialize();        
        		var url_link = 
                    "<?php echo 'index.php?'.GET_CONTROLLER.'=account/login'; ?>";		    
                //displayWait(true);
    
                $.ajax(
                {
                  type: "POST",
                  url: url_link,
                  data: str,
                  cache: false,
                  complete: function( message ) {
                        //displayWait(false);
                        },
                  success: function( message ) {
                        var obj = jQuery.parseJSON(message);
       
                        if (obj.result != 'ok') {
                            alert(obj.result); 
                            return false;
                            }
                        var user_name = "Settings: " + obj.user_name;   
    
                        var html = '<span class="ui-button-text">Logoff</span>';             
                        $( "#user-name" ).text( user_name );
                        $( "#login-logoff" ).html(html);
                        //$( "#new-request" ).css('display','inline');
                        
                        $( "#dialog-form" ).dialog( "close" );    
                        return true;
                        }
                });  
     
            }  
             		
    		$( "#dialog-form" ).dialog({
    			autoOpen: false,
    			height: 300,
    			width: 350,
    			modal: true,
    			buttons: {
    				"Login": function() {
    					var bValid = true;
    					allFields.removeClass( "ui-state-error" );
    
                        if (email.val() == 'admin') email.val('admin@emediaboard.com');
                        
    					bValid = bValid && checkLength( email, "email", 6, 80 );
    					bValid = bValid && checkLength( password, "password", 6, 16 );
     					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
    					//bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
    					bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
                    
    					if ( bValid ) {
                            submit_login(); 
    					    }
    				},
    				Cancel: function() {
    					$( "#dialog-form" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});
    
    		$( "#login-logoff" )
    			.button()
    			.click(function() {
    		 
                    if ($( "#login-logoff span" ).text() == 'Login') {
                        $( "#dialog-form" ).dialog( "open" );
                        } else {
                         submit_logoff();
                        }  
    				
    			});
/*    			
    		$( "#new-request" )
    			.button()
    			.click(function() {  
                var url_link = 
                    "<?php echo 'index.php?'.GET_CONTROLLER.'=products'; ?>";	  
                    $(location).attr('href',url_link);
    			}); 
*/                   			
    	});
	});   // end of jQuery	 
	</script>



<div id="dialog-container">

<div id="dialog-form" title="Create new user">
	<p class="validateTips">All form fields are required.</p>
         
	<form  >
	<fieldset>
 		<label for="email">Email</label>
		<input type="text" name="email" id="email" value="" class="text ui-widget-content ui-corner-all" />
		<label for="password">Password</label>
		<input type="password" name="password" id="password" value="" class="text ui-widget-content ui-corner-all" />
	</fieldset>
	<input type='hidden' name='mode' value='login' />  
	</form>
</div>
 
</div> 
 
