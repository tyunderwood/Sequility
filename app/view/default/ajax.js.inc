 
    	$(function() {
     		$( "#dialog:ui-dialog" ).dialog( "destroy" );
    		
    		var email = $( "#email" ),
    		    r_email = $( "#r_email"),
    			password = $( "#password" ),
    			r_password = $( "#r_password" ),
    			r_password2 = $("#r_password2"),
    			r_nickname = $("#r_nickname"),
    			allFields = $( [] ).add( email ).add( password ),
    			r_allFields = $( [] ).add( r_email ).add( r_password ).add( r_password2 ).add( r_nickname ),
    		
                s_emails = $("#s_emails");    			
    			p_maxprice = $("#pledge_maxprice"),
    			p_expdate = $("#pledge_expdate"),

                ADMIN_LEVEL = '<?php echo ADMIN_LEVEL; ?>',
                AUTHOR_LEVEL = '<?php echo AUTHOR_LEVEL; ?>',
                tips = $( ".validateTips" );
 
    		function updateTips( t ) {
    			tips
    				.text( t )
    				.addClass( "ui-state-highlight" );
    			setTimeout(function() {
    				tips.removeClass( "ui-state-highlight", 1500 );
    			}, 500 );
    		};
    
            function checkValidity( o, n, reserved) {
    			
                if ( o.val() == reserved ) {
    				o.addClass( "ui-state-error" );
    				updateTips(  n + " is a reserved word." );
    				return false;
    			} else {
    				return true;
    			}            
            };
            
    		function checkLength( o, val, n, min, max ) {
 
                var buffer = $.trim(val);
                 
    			if ( buffer.length > max || buffer.length < min ) {
    				o.addClass( "ui-state-error" );
    				updateTips( "Length of " + n + " must be between " +
    					min + " and " + max + " chars." );
    				return false;
    			} else {
    				return true;
    			}
    		};
    
    		function checkRegexp( o, regexp, n ) {
    			if ( !( regexp.test( o.val() ) ) ) {
    				o.addClass( "ui-state-error" );
    				updateTips( n );
    				return false;
    			} else {
    				return true;
    			}
    		};
     
            function checkFieldsMatch(field1,field2,n) {
    			if ( field1.val() != field2.val() || field1.val() == '') {
    				field2.addClass( "ui-state-error" );
    				updateTips( n );
    				return false;
    			} else {
    				return true;
    			}
            
            };
 
            function isValidEmailAddress(o,elem,n) {
                
                return true;   // doesn't work with underscores   
                
                var emailExp = "^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$";
 	
                if(elem.match(emailExp)) return true;
                
                o.addClass( "ui-state-error" );
                updateTips(n);
                
                return false;
	 
            };            
 
            function submit_crop() {
                
           
                var url_link = "<?php echo $config_dynamic_ajax; ?>";	
                display('cropImage url_link: '+url_link);
    
                var filename = $('#dialog-crop img').attr('src');
                var sx = filename.split('/');
                filename = sx[sx.length-1];
                
                var origin = 'complete_crop';
                
                var str =   'origin=' + origin + '&src=' + filename;
              
                str += '&user_id='+ logged_user_id;  
 
                if (verbose) str += '&debug=1';  
        
                display('cropImage str: '+str);
  
                $.ajax(
                {
                  type: "POST",
                  url: url_link,
                  data: str,
                  async: true,
                  cache: false,
                  dataType: 'xml',
                  complete: function( xml ) {
                        //display('complete');
                        },
                  success: function( xml ) {
                        //display('success');
 
                        var foo = jQuery('foo', xml).text(); 
                        //display('foo='+foo)
                        $( "#dialog-crop" ).dialog( "close" );
                           
                        //just reload.. it's cleaner
                        window.location = 'index.php';
                          
                        }
                });  
            }
                     
            function submit_logoff() {
                      
        		var url_link = 
                    "<?php echo 'index.php?'.GET_CONTROLLER.'=account/login&mode=logoff'; ?>";		    
                //displayWait(true);
    
                $.ajax(
                {
                  type: "POST",
                  url: url_link,
                  data: '',
                  cache: false,
                  complete: function( message ) {
                        //displayWait(false);
                        },
                  success: function( message ) {
               
                        var obj = jQuery.parseJSON(message);
       
                        if (obj.result != 'ok') {
                            alert(obj.message); 
                            display('login&mode=logoff error: '+obj.result );
                            return false;
                            }
                        
                        $("#seqSearch input:text").val('');   
                       
                        //just reload.. it's cleaner
                        window.location = '?';
 
                        return true;
                        }
                });  
            }
            
            function submit_login() {
     
                var str = $("#login_form").serialize();   
                     
        		var url_link = 
                    "<?php echo 'index.php?'.GET_CONTROLLER.'=account/login'; ?>";		    
                display('submit login query: ' + str); 
   
                $.ajax(
                {
                  type: "POST",
                  url: url_link,
                  data: str,
                  cache: false,
                  async: false,
                  complete: function( message ) {
                        //displayWait(false);
                        },
                  success: function( message ) {
                   
                        var obj = jQuery.parseJSON(message);
       
                        if (obj.result != 'ok') {
                            display('submit login result: ' + obj.result); 
                            if (obj.result == 'notregistered') {
                                $( "#dialog-login" ).dialog( "close" );  
                                $( "#dialog-register" ).dialog( "open" );      
                                }
                            return false;
                            }
                           
                        var user_name = "Account Settings: " + obj.nick_name;   

                        user_id = obj.user_id;
                        user_zip = obj.user_zip;
                    
                        $( "#zip" ).val(user_zip);
                        
                        store_id = obj.store_id;
                        twitter_token = obj.twitter_token;
                        store_zip = obj.store_zip;
                        // we are also getting the twitter_name if merchant has one
                        
                        var html = '<span class="ui-button-text">Logoff</span>';             
                        $( "#user-name" ).text( user_name );
                        $( "#login-logoff" ).html(html);
                      
                        if (store_id != 0 && twitter_token == '') {
                            var tmp = '';
                            } else {
                            var tmp = 'none';
                            }
                            
                        $( ".store-manager").css('display',tmp);
                 
                        $( "#dialog-login" ).dialog( "close" );   
                        if (store_id != 0) {
                            window.location = 'index.php?c=cats&zip='+store_zip+'&origin=actives&search_level=1001';
                            return false;
                            }
 
                        if (typeof(login_cb_param) == 'undefined' ) {
                            // regular log in will exit here 
                            window.location = '';
                            return true;
                            }
                        
                        // my little simplified call back
                        switch (login_cb_param.cb) {
                            case 'pledgeItem'   : pledgeItem(login_cb_param.product_id,
                                                             login_cb_param.id); 
                                                    break;
                            case 'buyItem'      : buyItem(login_cb_param.id); 
                                                    break;
                            case 'shareItem'    : shareItem(login_cb_param.id); 
                                                    break;
                            case 'confirmPledge': confirmPledge(login_cb_param.user_id,
                                                                login_cb_param.pledge_id); 
                                                    break; 
                        }                       
 
                        window.location = '';                         
                        return true;
                        }
                });  
     
            }  
             		
    		$( "#dialog-login" ).dialog({
    			autoOpen: false,
    			height: 300,
    			width: 350,
    			modal: true,
    			 
    			buttons: {
    				Login: function() {
    					var bValid = true;
    					allFields.removeClass( "ui-state-error" );
        
                        if (email.val() == 'admin') email.val('admin@emediaboard.com');
               
    					bValid = bValid && checkLength( email, email.val(),"email", 6, 80 );
    					bValid = bValid && checkLength( password, password.val(),"password", 6, 16 );
     					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
    					//bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
    					bValid = bValid && checkRegexp( password, /^([0-9a-zA-Z])+$/, "Password field only allow : a-z 0-9" );
                    
    					if ( bValid ) {
                            submit_login(); 
    					    }
    				},
    				Cancel: function() {
    					$( "#dialog-login" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});
    
    		$( "#login-logoff" )
    			.button()
    			.click(function() {
    		 	display('logged_user_id ='+logged_user_id );
                    if (logged_user_id == 0) {

                        FB.getLoginStatus(function(response) {
                            display('getLoginStatus');
 
                            FB.login(function(response) {
                                if (response.authResponse) {
                                    display('Welcome!  Fetching your information.... ');
                                    FB.api('/me', function(response) {
                                        display('Good to see you, ' + response.id + ' email: '+ response.email );
                                        $( "#email" ).val(response.email);
                                        $( "#password" ).val('no-password-needed');
                                        $( "#nickname" ).val(response.name);
            
                                        submit_login();
                                        });
                                    
                                    } else {
                                    display('User cancelled login or did not fully authorize.');
                                    // login the old way
                                    $( "#dialog-login" ).dialog( "open" );
                                    }
                                
                                }, 
                                {scope: 'email'}
                                );                  
                             
                            })
 
                        // $( "#dialog-login" ).dialog( "open" ); // without fb
                        } else {
                      
                        // use FB.logout(); to log out of facebook too
                        submit_logoff();    
                        }  
    				
    			});

                         		
    		$( "#site-help" ).dialog({
    			autoOpen: false,
    			height: 500,
    			width: 800,
    			modal: true,
    			 
    			buttons: {
    				 
    				Close: function() {
    					$(this).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});

    		
    		$( "#help-site" )
    			.button()
    			.click(function() {  
                $( "#site-help" ).dialog( "open" );
              
    			});

            function update_localStorage(picture_id,title,description) {
            
                var images = localStorage.getItem('images').split('\n');
                var panel_size = images.length - 1;
                for (var i=0;i<panel_size;i++) {
                    img_info = images[i];
                    var sx = img_info.split('|');
                    if (picture_id != sx[12]) continue;
               
                    sx[3] = title;
                    sx[14] = description;
                    
                    img_info = sx.join('|');
                    images[i] = img_info;
                    localStorage.setItem('images',images.join('\n'));
                    return;
                    }
                
            };
            
            function submit_page_dialog() {
              
                var str = $("#edit_page_form").serialize();   
                display("submit_page_dialog: " + str);   
                 
        		var url_link = "<?php echo 'index.php?'.GET_CONTROLLER.'=pictures_singleEdit'; ?>";		    
    
                $.ajax(
                {
                  type: "POST",
                  url: url_link,
                  data: str,
                  cache: false,
                  async: false,
                  complete: function( message ) {
                         
                        },
                  success: function( message ) {
                   
                        var obj = jQuery.parseJSON(message);
       
                        if (obj.result != 'ok') {
                            
                            return false;
                            }
                        //alert(obj.message)
                        var picture_id = $("#picture_id").val();
                         
                        $( "#edit_page_dialog" ).dialog( "close" );  
                        
                        $( "#title_text_" + picture_id ).text(obj.title);
                   
                        $( "#page_desc_" + picture_id ).text(obj.description);  
                        
                        // update localStorage
                        update_localStorage(picture_id,obj.title,obj.description);
                        return true;
                        }
                });  
     
            }  
                      		
    		$( "#edit_page_dialog" ).dialog({
    			autoOpen: false,
    			height: 300,
    			width: 350,
    			modal: true,
    			 
    			buttons: {
    				Submit: function() {
    					var bValid = true;
    					allFields.removeClass( "ui-state-error" );
 
    					bValid = true;
    					if ( bValid ) {
                            submit_page_dialog(); 
    					    }
    				},
    				Cancel: function() {
    					$(this).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});
    		
            $(".edit_single")
                .button()
                .click(function(){
                    $( "#edit_page_dialog" ).dialog( "open" );
	           });
  
    		$( "#add_album" )
    			.button()
    			.click(function() {  
  		
                    display('logged_user_id='+ logged_user_id );
 
                    window.location = '<?php echo $config_site_url; ?>' +
                                '/admin/index.php?c=albums/add&gallery_id='+logged_user_id; 
    			});
    			

    		// only if correct rights 
    		$( "#add_page" )
    			.button()
    			.click(function() {  
    			     var al = '<?php echo $album_id; ?>';  
 			     
                     window.location = '<?php echo $config_site_url; ?>' +
                                '/admin/index.php?c=pictures/add&album_id='+al; 
    			});
    			
            	
            //////////////////// store mgt registration ////////////////
                         		
    		$( "#show-terms" ).dialog({
    			autoOpen: false,
    			height: 500,
    			width: 800,
    			modal: true,
    			 
    			buttons: {

    				Close: function() {
    					$( "#show-terms" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});
   			
   			//////////////////// zormics ////////////////////////////
    		$( "#dialog-crop" ).dialog({
    			autoOpen: false,
    			// height should be based on max height cropping
    			height: 390,
    			width: 350,
    			modal: true,
    			 
    			buttons: {
    				Crop: function() {
    			 
    					allFields.removeClass( "ui-state-error" );
                        submit_crop();  
   
    				},
    				Cancel: function() {
    					$( "#dialog-crop" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});
               			
   			$("#fb_signin")
			    .click(function(event) {
				event.stopPropagation();
    		 	display('logged_user_id ='+logged_user_id );
                    if (logged_user_id == 0) {

                        FB.getLoginStatus(function(response) {
                            display('getLoginStatus');
 
                            FB.login(function(response) {
                                if (response.authResponse) {
                                    display('Welcome!  Fetching your information.... ');
                                    FB.api('/me', function(response) {
                                        display('Good to see you, ' + response.id + ' email: '+ response.email );
                                        $( "#email" ).val(response.email);
                                        $( "#password" ).val('no-password-needed');
                                        $( "#nickname" ).val(response.name);
            
                                        submit_login();
                                        });
                                    
                                    } else {
                                    display('User cancelled login or did not fully authorize.');
                                    // login the old way
                                    $( "#dialog-login" ).dialog( "open" );
                                    }
                                
                                }, 
                                {scope: 'email'}
                                );                  
                             
                            })
 
                        // $( "#dialog-login" ).dialog( "open" ); // without fb
                        } else {
                      
                        // use FB.logout(); to log out of facebook too
                        submit_logoff();    
                        }  
    				
    			});
                    
   			$( "#start_comic")
   			  .click(function(event) {
			      event.stopPropagation();
   			      var register_field = $( "#user-name" ).text();
   			      if (register_field.indexOf("Guest") == 0) {
   			          alert("please login first");
                      return;  
                      }
                    $( "#user-name").click(); 
                    });
                    
            var saved_height=0;    
                    
   			$( "#img_expand")
   			  .click(function(event) {
   			      event.stopPropagation();
   			      if ($(this).text() == "-") {
   			        $("#header").css('height',saved_height);
                    $(this).text("+"); 
                    $(this).css('font-weight','bold');                       
                    } else {
                    saved_height = $("#header").css('height');
   			        $("#header").css('height','<?php echo $config_large_height; ?>px');
                    $(this).text("-"); 
                    $(this).css('font-weight','bold');         
                    $(this).attr("saved_height",saved_height);                    
                    }

                    });
                    
    			                 
            $( "#admin_msg_btn" ).click(function() { 
   					
                var bValid = true;
    			r_allFields.removeClass( "ui-state-error" );
 
                bValid = true;  
 
    			if ( bValid ) {
                    $( "#admin_msg_form").submit();
                    $( "#mysettings_tabs" ).dialog( "close" );
    				} else {
                    alert('Please fill in all the fields') 
                    }
    					       
                    });                    
            //////////////////// registration ///////////////////////////

    		$( "#user-name")
    			//.button()
    			.click(function() {
   			
    		 //$('*:contains("I am a simple string")');
    		        var register_field = $( "#user-name" ).text();
    		         
    		        if (register_field.indexOf("Guest") == 0) {
    		            $( "#login-logoff" ).click();
                       
                        return;
                        }
    		        
                    if (register_field.indexOf("Register") == 0) {
                        // old message now replaced with Guest
                        $( "#dialog-register" ).dialog( "open" );
                        } else {
 
                        $(function() {
		                      $( "#mysettings_tabs" ).tabs();
                        });
                        $( "#tabs-1_1" ).text("please wait while we prepare your data...");
                        $( "#mysettings_tabs" ).dialog( "open" );
              
                        $( "#mysettings_tabs" ).tabs( "option", "disabled", [4] );                
                        
                        var url_link = "<?php echo 'index.php?'.GET_CONTROLLER.'=mysettings'; ?>";	  
 
                        var str='';    
                        $.ajax(
                            {
                            type: "POST",
                            url: url_link,
                            data: str,
                            cache: false,
                            async: false,
                            complete: function( message ) {
                                //displayWait(false);
                                },
                            success: function( message ) {
                         
                                var obj = jQuery.parseJSON(message);
               
                                if (obj.result != 'ok' || obj.user_id == 0) {
         
                                    return false;
                                    }
                                
                                //this should be global :)
                                username = obj.user_name;
                                nickname = obj.nickname;
                                coupons = obj.coupons;
                                pledges = obj.pledges;
								
                                
                                //display(pledges)                                
                                
                                return true;
                                }
                            }); 
  
                        var tab = "user name: " + username + "</p><p>nickname: " + nickname;
                        $( "#tabs-1_1" ).html(tab);
 
  
                        if (admin_level == 0) {
                            tab = "<input  type='checkbox' " +
                                 " onclick='setAuthor(this);'  >Set me up as an author";
                            $( "#tabs-1_4" ).html(tab);     
                            } else if (admin_level == ADMIN_LEVEL){
                            tab = "<a>Registered as an author</a>";
                            $( "#tabs-1_4" ).html(tab);     
                            } else if (admin_level == AUTHOR_LEVEL){
                            tab = "<a>Registered as Superuser</a>" ;
                            $( "#tabs-1_4" ).html(tab);     
                            }
                        
                        //$( "#mysettings_tabs-1" ).html(tab_1);
 
		                $( "#account_form_btn" ).click(function() { 
                            var r_password1 = $( "#r_password1" );
                            var r_password2 = $( "#r_password2" );
                            if (checkFieldsMatch(r_password1,r_password2,"Your 2 passwords must be identical and not empty")) {
                                $( "#account_form").submit();
                                $( "#mysettings_tabs" ).dialog( "close" );
                                }
                                 
                            });
                      
                        //var tab_id = "#mysettings_tabs-2";
                        //createTinyTable(tab_id,coupons,"\n","|");
                        
                        //var tab_id = "#mysettings_tabs-3";
                        //createTinyTable(tab_id,pledges,"\n","|");
    		
    		            $( "#terms-show_btn" )
    			             .button()
    			             .click(function() {  
                                    $( "#show-terms" ).dialog( "open" );
                
    			                 });  
    			                 
		                $( "#author_form_btn" ).click(function() { 
   					
                            var bValid = true;
    					    r_allFields.removeClass( "ui-state-error" );
                            
                            var checkedTerms = $("#storeTermsConds").is(':checked');    
                   
                            bValid = bValid && (checkedTerms);  
 
    					    if ( bValid ) {
                                $( "#author_form").submit();
                                $( "#mysettings_tabs" ).dialog( "close" );
    					       } else {
                               alert('Please fill in all the fields') 
                               }
    					       
                            });
                                                    
                        }                				
    			});
    			
            $( "#mysettings_tabs" ).dialog({
    			autoOpen: false,
    			height: 550,
    			width: 700,
    			modal: true,
    			buttons: {
 
    				Close: function() {
    					$( "#mysettings_tabs" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				r_allFields.val( "" ).removeClass( "ui-state-error" );
    			} 
  
            }); 
     			
    		$( "#dialog-register" ).dialog({
    			autoOpen: false,
    			height: 500,
    			width: 350,
    			modal: true,
    			buttons: {
    				Register: function() {
    					var bValid = true;
    					r_allFields.removeClass( "ui-state-error" );
  
    					bValid = bValid && checkLength( r_email, r_email.val(),"email", 6, 80 ); 
                        bValid = bValid && isValidEmailAddress(r_email,r_email.val(),"Invalid mail address");    					
    					bValid = bValid && checkLength( r_password, r_password.val(),"password", 6, 16 );
    					
     					// From jquery.validate.js (by joern), contributed by Scott Gonzalez: http://projects.scottsplayground.com/email_address_validation/
    					//bValid = bValid && checkRegexp( email, /^((([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?$/i, "eg. ui@jquery.com" );
    					bValid = bValid && checkRegexp( r_password, /^([0-9a-zA-Z])+$/, "Password field only allows : a-z 0-9" );
                    
                        bValid = bValid && checkFieldsMatch(r_password,r_password2,"Your 2 passwords must be identical and not empty");
    					bValid = bValid && checkLength( r_nickname,  r_nickname.val(),"nickname", 6, 16 );
    					bValid = bValid && checkValidity( r_nickname, "nickname","Register");
                        if ( bValid ) {
                            submit_register(); 
    					    }
    				},
    				Cancel: function() {
    					$( "#dialog-register" ).dialog( "close" );
    				}
    			},
    			close: function() {
    				r_allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		}); 
/*
            function createTinyTable(table_id,data,row_separator,col_separator) {
                // used for coupons and pledges... not needed in zormics
                
                // create table
                var table = '<table class="ui-tinytable" >';
                table += '<thead><tr>';
                var lines = data.split(row_separator);
 
                var val = lines[0];
                var cols = val.split(col_separator);
                $.each(cols, function(key, val) {
                    table += '<th>' + val + "</th>"; 
                    });
                
                table += '</tr></thead>';
                table += '<tfoot><tr></tr></tfoot>';
                  
                table += '<tbody>';  
                for (var i=1;i<lines.length ;i++) {
                    table += '<tr>';  
                    var val = lines[i];
                    var cols = val.split(col_separator);
                    $.each(cols, function(key, val) {
                        table += '<td>' + val + "</td>"; 
                        });
                    table += '</tr>'; 
                    }  
                                   
                table += '</tbody>';        
                
                $(table_id).html(table);
            
                // make it nice
                $(table_id).tinytbl({
                    direction: 'ltr',
                    thead: true,
                    tfoot: false,
                    cols: 1,
                    focus: true,
                    height: 400
                    });
                
            };
*/            
            function submit_password() {
 
                var str = $("#dialog-account").serialize(); 
 
                $( "#dialog-account" ).submit(); 
                return false;    
            };  
                        
            function submit_register() {
     
                var str = $("#register_form").serialize();
                
                //display("register: " + str);
                        
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
                        display('submit register result: ' + obj.result); 
       
                        if (obj.result != 'ok') {
                            
                            if (obj.result == 'notregistered') {
                                $( "#dialog-register" ).dialog( "close" );  
                                return false;
                                }
                                
                            if (obj.result == 'notloggedoff') {
                                 
    				            alert( "Please log off before a new registration" );
    				            return false;
                                }
                                
                            if (obj.result == 'alreadyregistered') {
 
    				            alert( "This email is already registered" );
    				            return false;
                                
                                }
                                
                            updateTips('');    
                            return false;
                            }
                            
                        //var user_name = "Settings: " + obj.user_name;   
                            
                        $( "#dialog-register" ).dialog( "close" );  
                        $( "#dialog-login" ).dialog( "open" );  
                         
                        $( "#dialog-message" ).html("You can login now");
                        $( "#dialog-message" ).attr('title',site_title);
                        $( "#dialog-message" ).dialog({
                            modal: true,
			                buttons: {
				               Ok: function() {
					               $( this ).dialog( "close" );
					               $( "#dialog-login" ).dialog( "open" );
				                    }
			                 }
		                  }); 
                          
                        return true;
                        }
                });  
     
            };  
        ///////////////////////// pledge ///////////////////////////////////
/*            
            $(function() {
		      $( "#expdate" ).datepicker();
	        });  
*/                  
            function submit_pledge() {
 
                var str = $("#pledge_form").serialize(); 
                display("str="+str);
                $( "#dialog-pledge" ).dialog( "close" ); 
                $( "#expdate" ).datepicker( "destroy" );
                
                $( "#pledge_form" ).submit(); 
                return false;   // LOL.. will never return, will it
            };  
            		
    		$( "#dialog-pledge" ).dialog({ 
    			autoOpen: false,
    			height: 550,
    			width: 600,
    			modal: true, 
    			buttons: {
    				Pledge: function() {
    					var bValid = true;
    					allFields.removeClass( "ui-state-error" );
              
    					bValid = bValid && checkRegexp( p_maxprice,/^\$?\d+(\.(\d{2}))?$/,
                                "Max Price field only allows positive number with 2 decimals (1234.56) or none");
                        bValid = bValid && checkRegexp( p_expdate,/^\d{1,2}\/\d{1,2}\/\d{4}$/, "Please select a date");
    					if ( bValid ) {
                            submit_pledge(); 
    					    }
    				},
    				Cancel: function() {
    					$( "#dialog-pledge" ).dialog( "close" );
    					$( "#expdate" ).datepicker( "destroy" );
    				},
    				Help: function() {
    					$( "#pledge-help" ).dialog({ modal: true }); 
    				}
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});

         //////////////// share /////////////////////////////////
            function submit_share() {
 
                var str = $("#share_form").serialize(); 
                
                display("str="+str);
                
                $( "#dialog-share" ).dialog( "close" ); 
                $( "#share_form" ).submit(); 
                return false;   // LOL.. will never return, will it
            };  
                        		
    		$( "#dialog-share" ).dialog({ 
    			autoOpen: false,
    			height: 550,
    			width: 600,
    			modal: true, 
    			buttons: {
    				Send: function() {
    					var bValid = true;
    					allFields.removeClass( "ui-state-error" );

                        var emails = $.trim(s_emails.val());
 
    					if (emails == '' || emails.indexOf(',') == -1) {
                            bValid = isValidEmailAddress(s_emails,emails,"Empty or incorrect email list");
    					    } else {
    					    var email_list = emails.split(',');
    					    for (var i=0;i<email_list.length;i++) {
    					       var email = email_list[i];
                               bValid = bValid && isValidEmailAddress(s_emails,email,"Invalid email address: " + email);
                               bValid = bValid && checkLength(s_emails,email, "email", 6, 80 );
                               
                               if (! bValid) break;
                               } 
                            
                            }

     					if ( bValid ) {
                            submit_share(); 
    					    }
    				},
    				Cancel: function() {
    					$( "#dialog-share" ).dialog( "close" );
    				} 
    			},
    			close: function() {
    				allFields.val( "" ).removeClass( "ui-state-error" );
    			}
    		});    
                   
    	});