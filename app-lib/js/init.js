
// Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


window.zingAlert= function(){
  window.alert("PRIVACY ALERT!\n\nUsing the 'Download [filetype]' menu links sends the chart data to export.zingchart.com, to create the download file.\n\nTo preserve privacy, CHOOSE 'View As PNG' INSTEAD, then opposite-click over the chart and choose 'Save Image As', to save the PNG image to your computer.")
}


// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function(){ 

    
// PHP used instead for logging / alerts, but leave here in case we want to use pure-javascript
// cookie creation some day (which could help pre-detect too-large headers that crash an HTTP server)
// console.log( array_byte_size(document.cookie) );


// Main submit form ACTION should ALWAYS MATCH the browser window location
// (for UX of loading back to same page AFTER form submission)
$("#coin_amnts").attr('action', window.location);
    
	
// Render interface after loading (with transition effects)
$("#app_loading").hide(250, 'linear'); // 0.25 seconds
$("#content_wrapper").show(250, 'linear'); // 0.25 seconds
$("#content_wrapper").css('display','inline'); // MUST display inline to center itself cross-browser

  
// Charts background / border
$(".chart_wrapper").css({ "background-color": charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + charts_border });


// Repeatable styling
$("div.repeatable > div:first-child").css("border-top", "0.0em solid #808080");
$("div.repeatable > div:first-child").css("padding-top", "0.0em");
             

// Dynamic table header updating
$("span.bitcoin_primary_currency_pair").html(bitcoin_primary_currency_pair); 


// Firefox doesn't like '1em' for this attibute value, so we emulate it (for ALL browsers)
$('.password-container input[type="password"], .password-container input[type="text"]').css('padding-right', Math.round(set_font_size * 30) + 'px', "important");

	
// Highlightjs configs
hljs.configure({useBR: false}); // Don't use  <br /> between lines
	

// Show UTC time count in logs UI sections
start_utc_time(); 


// Highlightjs
load_highlightjs();


// Monitor admin iframes for auto-height adjustment WHEN THEY SHOW
monitor_iframe_sizes();


// 'Loading X...' UI notices
background_tasks_check();


// Random tips on the update page 
// https://codepen.io/kkoutoup/pen/zxmGLE
random_tips(); 


// If overriding any responsive menu CSS is needed
responsive_menu_override();

	
// For ALL nav menus (normal / compact sidebars, mobile top nav bar), we want to keep track of which
// nav item is active and it's associated content, and display it / mark nav links as active in interface
nav_menu('.admin-nav');
nav_menu('.user-nav');


     /////////////////////////////////////////////////////////////////////////////////////////////////////
     

     // Monitor admin iframes for load / unload events
     // MUST BE SET VERY EARLY, TO USE FURTHER BELOW!!!
     if ( is_iframe ) {
     admin_iframe_dom = window.parent.document.querySelectorAll('.admin_iframe');
     }
     else {
     admin_iframe_dom = document.querySelectorAll('.admin_iframe');
     }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

	// Activate auto-reload
	if ( get_cookie("coin_reload") && !is_admin ) {
	auto_reload();
	}
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Trading notes
	if ( typeof notes_storage != 'undefined' && localStorage.getItem(notes_storage) && $("#notes").length ) {
     $("#notes").val( localStorage.getItem(notes_storage) );
	}
    

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // If all cookie data is above threshold trigger, warn end-user in UI
     if ( typeof cookies_size_warning != 'undefined' && cookies_size_warning != 'none' ) {
          
     var header_size_warning_top_margin = is_admin ? 0 : 1.4;
     $("#header_size_warning").css("margin-top", header_size_warning_top_margin + "em");
          
     $("#header_size_warning").html(cookies_size_warning);
     
     $("#header_size_warning").css("display", "block");
     
     var cookies_size_warning_info = '<h5 class="red tooltip_title">Avoiding App Crashes From Excessive Cookie Size</h5>'
            
            			+'<p class="coin_info red" style=" white-space: normal;">Web servers have a pre-set header size limit (which can be adjusted within it\'s own server configuration), which varies depending on the server software you are using.</p>'
            
            			+'<p class="coin_info red" style=" white-space: normal;"><span class="red">IF THIS APP GOES OVER THOSE HEADER SIZE LIMITS, IT WILL CRASH!</span></p>'
            
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">STANDARD SERVER HEADER SIZE LIMITS (IN KILOBYTES)...</span><br />Apache: 8kb<br />NGINX: 4kb - 8kb<br />IIS: 8kb - 16kb<br />Tomcat: 8kb - 48kb</p>'
            
            			+'<p class="coin_info red" style=" white-space: normal;"><span class="red">In FUTURE versions of this app, selected price charts and news feeds will be moved to "local storage" in your web browser. Until then, please try to MINIMIZE how many price charts and news feeds you select for viewing. Selecting fewer coins in "watch only" mode will also cut down on cookie storage too.</span></p>';

	

           // Wait 1.5 seconds before Initiating
           setTimeout(function(){
                                       
			$('#cookies_size_warning_info').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: cookies_size_warning_info,
			css: balloon_css()
			});
		
                                  
           }, 1500);
     
     }
     

     /////////////////////////////////////////////////////////////////////////////////////////////////////
     
     
     if ( is_firefox ) {
     $("#sidebar label.pl_mn_lab").css('transform', 'scale(.75) translateY(0rem) translateX(0.25rem)', "important");
     $("#sidebar #quant_font_percent").css('padding-left', '0.35rem', "important");
     $("#sidebar #quant_font_percent").css('padding-right', '0.1rem', "important");
     }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Emulate a cron job every X minutes...
	if ( emulated_cron_enabled && !is_iframe ) {
     cron_already_ran = false;
     emulated_cron(); // Initial load (RELOADS from WITHIN it's OWN logic every minute AFTER)
	}
	
	
	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// On resize of browser viewport
	addEventListener("resize", (event) => {
     // If overriding any responsive menu CSS is needed
     responsive_menu_override();
	});
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Load access stats
     $('.load_access_stats_onclick').on({
        "click":function(e){
         ct_ajax_load('type=access_stats', '#access_stats_data', 'latest access stats', false, true, true); // Secured / tablesorter
         }
     });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Viewing "Issues Help & Status" sidebar link(s), 
     // sets js timestamp of WHEN LAST VIEWED (to js storage)
     $('.show_report_issues').on({
        "click":function(e){
         localStorage.setItem(issues_page_visit_time_storage, Date.now() );
         }
     });


     // Make sure local storage data is parsed as an integer (for javascript to run math on it)
     var issues_page_last_visit = parseInt( localStorage.getItem(issues_page_visit_time_storage) , 10);
     
     //console.log('latest_important_dev_alerts_timestamp = ' + latest_important_dev_alerts_timestamp);
     //console.log('issues_page_last_visit = ' + issues_page_last_visit);
                     
     // Determine if we should highlight "Issues / Status" sidebar link(s),
     // if an "important" status update has been added AFTER last page view
     if ( isNaN(issues_page_last_visit) || latest_important_dev_alerts_timestamp >= issues_page_last_visit ) {
     $(".show_report_issues").css({ "border": '4px dotted red'});
     $(".show_report_issues").css({ "border-radius": '1em'});
     $(".show_report_issues .nav-image").css({ "border-radius": '0.3em'});
     $(".show_report_issues .nav-image").css({ "background-color": 'red'});
     }
                     

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

     // INITIAL vertical scroll should always start at top for UX
     $('html, body').animate({
     scrollTop: 0
     }, 'slow');
	
     // Load our vertical scroll position (if checks pass within the function's logic)
     // (WE ALREADY LOAD set_scroll_position() in background_tasks_check() FOR NEWS / CHARTS PAGES [ONLY AFTER THEY ARE FULLY LOADED])
     if ( !is_admin && $(location).attr('hash') != '' && $(location).attr('hash') != '#news' && $(location).attr('hash') != '#charts' ) {
     set_scroll_position(); // Run AFTER showing content
     }
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Dynamically style modals AFTER THEY OPEN (AFTER the dynamically-created elements are created)
     $('.modal_style_control').on({
        "click":function(e){
             
             // Wait 0.1 seconds
             setTimeout(function(){
             interface_font_percent( (set_font_size * 100), false, '.modaal-content-container', 'reg' );
     	   }, 100);
              
         }
     });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // open or close alerts
    $('.toggle_alerts').on('click', function () {
             $('#alert_bell_area').toggleClass('hidden');
    });
	
	
    $('#alert_bell_area').on('click', function () {
             $('#alert_bell_area').toggleClass('hidden');
    });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Dynamically style / control admin logout interfacing
     $('.admin_logout').on({
        "click":function(e){
         
         var confirm_admin_logout = confirm("Click OK to continue logging out of the admin area.");
             
             if ( confirm_admin_logout && Base64.decode(gen_csrf_sec_token) != 'none' ) {
             $("#app_loading").show(250, 'linear'); // 0.25 seconds
             $("#app_loading_span").html("Please wait, logging out...").css("color", "#ff4747", "important");
             $("#content_wrapper").hide(250, 'linear'); // 0.25 seconds
             return true;
             }
             else {
             return false;
             }
              
         }
     });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Auto-focus admin authentication form's username feild
    if ( $("#admin_login").length ) {
    	setTimeout(function(){
        $("#admin_username").filter(':visible').focus();
    	}, 1000); 
    }
    else if ( $("#set_admin").length ) {
    	setTimeout(function(){
        $("#set_username").filter(':visible').focus();
    	}, 1000);
    }
    else if ( $("#reset_admin").length ) {
    	setTimeout(function(){
        $("#reset_username").filter(':visible').focus();
    	}, 1000);
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Monitor iframes for added 'repeatable' elements, so we can auto-adjust the height
	// https://github.com/naugtur/insertionQuery
	if ( is_iframe ) {
	     
          insertionQ('.repeatable div').every(function(element){
               
          console.log('repeatable element added, adjusting iframe height...');
               
               // Reset iframe heights after 1 second
               setTimeout(function() {
                    
                   admin_iframe_dom.forEach(function(iframe) {
                   iframe_size_adjust(iframe);
                   });
                   
               }, 1000);
          
          });

     }
     

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
    // (does NOT affect a standard javascript ELEMENT.submit() call)
    $("form").submit(function(event) {

    // Force scrolling to top of page on submit (for better UX)    
    scroll(0,0);
    
    form_submit_queued = true;
    
        // Redirect reloaded page to show portfolio, IF we saved user settings OR updated the portfolio
        if ( $(location).attr('hash') == '#update' || $(location).attr('hash') == '#settings' ) {
        this.action = 'index.php';
        }
    
        // We have to run app_reloading_check(1) here, 
        if ( app_reloading_check(1) == 'no' ) {
        event.preventDefault();
        console.log('Default action stopped for: submit');
        return false;
        }
        
    });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Emulate sticky-positioned elements in #secondary_wrapper,
	// IF we set overflow: auto; CSS to automate controlling scroll positioning
	// (which DISABLES a container from having functional sticky-positioned elements within it)
	if ( !is_iframe ) {
	     
          window.addEventListener('scroll', function (e) {
     
          dynamic_position('#alert_bell_area', 'emulate_sticky');
     
          dynamic_position('#background_loading', 'emulate_sticky');
     
          dynamic_position('.iframe_loading_placeholder', 'emulate_sticky');
     
          dynamic_position('.page_title', 'emulate_sticky');
     
          dynamic_position('.countdown_notice', 'emulate_sticky');
          
          });
     
     }
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Show / hide password
     $('.toggle-show-password').on({
         "click":function(e){

         $(this).toggleClass("gg-eye");

         $(this).toggleClass("gg-eye-alt");
       
         var input_elm = $("input[data-name=" + $(this).attr('data-name') + "]");
       
             if ( input_elm.attr("type") === "password" ) {
             input_elm.attr("type", "text");
             }
             else if ( input_elm.attr("type") === "text" ) {
             input_elm.attr("type", "password");
             }
              
          }
     });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // iframe info balloon text sizes are wonky for some reason in LINUX PHPDESKTOP (but works fine in modern browsers)
     if ( app_container == 'phpdesktop' && app_platform == 'linux' ) {
     var adjusted_font_size_percent = is_iframe ? 70 : 100;
     }
     else {
     var adjusted_font_size_percent = 100;
     }
               
                    
     // Dynamically style balloon tooltips ON DOCUMENT LOADED
     interface_font_percent( (set_font_size * adjusted_font_size_percent), false, '.balloon-tooltips', 'reg' );


     // Dynamically style balloon tooltips AFTER THEY OPEN (AFTER the dynamically-created elements are created)
     $('.tooltip_style_control').mouseover(function(){
             
             // Wait 0.1 seconds
             setTimeout(function(){
                  
             interface_font_percent( (set_font_size * adjusted_font_size_percent), false, '.balloon-tooltips', 'reg' );
          
             // Convert numbers to browser locale, BUT ONLY ON THE PORTFOLIO PAGE
             // (AS WE EASILY MESS UP THE NUMBER CALCULATIONS SERVER-SIDE, IF CONVERTED IN FORM DATA TOO)
             // !!!!!!!!!!NEVER DUPLICATE A CSS PATH IN ANY WAY, OR IT CORRUPTS NUMBER DATA!!!!!!!!!!!!!
             convert_numbers('.balloon-tooltips .coin_info', pref_number_format);

     	   }, 100);
     	   

             console.log( $(this).attr("id") );
           
     });


     // Dynamically style balloon tooltips AFTER THEY OPEN (AFTER the dynamically-created elements are created)
     $('.tooltip_style_control').mouseout(function(){
             
     });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
     
     // Do setting changes check for 3-deep sidebar menu area
     $('#sidebar ul li ul.dropdown-menu a:not(.settings_save)').on({
        "click":function(e){
             
             
              // IF user CHANGED admin config settings data via interface,
              // confirm whether or not they want to skip saving their changes
              if ( is_admin && unsaved_admin_config ) {
                       
              var confirm_skip_saving_changes = confirm("You have UN-SAVED setting changes. Are you sure you want to leave this section without saving your changes (using the RED SAVE BUTTON in the menu area)?");
                  
                  if ( !confirm_skip_saving_changes ) {
                       
                  e.preventDefault();

                  $("a.dropdown-item").removeClass("secondary-select");
                  $(this).addClass("secondary-select");

                  return false;                 

                  }
                  else {        
                  
                  unsaved_admin_config = false;

                  $('#collapsed_sidebar .admin_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
                  $('#sidebar .admin_settings_save').addClass('blue');
                  $('#sidebar .admin_settings_save').removeClass('red_bright');

                  }

              }
              else if ( !is_admin && unsaved_user_config ) {
                       
              var confirm_skip_saving_changes = confirm("You have UN-SAVED setting changes. Are you sure you want to leave this section without saving your changes (using the RED SAVE BUTTON in the menu area)?");
                  
                  if ( !confirm_skip_saving_changes ) {
                       
                  e.preventDefault();

                  $("a.dropdown-item").removeClass("secondary-select");
                  $(this).addClass("secondary-select");

                  return false;                 

                  }
                  else {        
                  
                  unsaved_user_config = false;

                  $('#collapsed_sidebar .user_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
                  $('#sidebar .user_settings_save').addClass('blue');
                  $('#sidebar .user_settings_save').removeClass('red_bright');

                  }

              }
                  
              
         }
     });
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Updating admin settings
	if ( is_admin && is_iframe ) {
              
     // Checking for CHANGED form data          
     var check_update_form = document.querySelector('#update_config');

          
          if ( check_update_form != null ) {
             
          var hashed_id = CryptoJS.MD5(check_update_form.conf_id.value).toString();


               // Listen for input events on the form
               check_update_form.addEventListener('input', function (event) {
               red_save_button('iframe');
               });
          
          
     
               $('.admin_settings_save', window.parent.document).on({
                  "click":function(e){
                       
                       
                       // This makes sure only the VISIBLE PAGE'S FORM IS SUBMITTED (otherwise EVERY page submits!)
                       // (the form fields DON'T NEED TO BE SHOWING 'IN THE TOP FOLD', SO THIS *IS* RELIABLE)
                       var admin_id = window.frameElement.id.replace("iframe", "admin");
         
              
                       // If we temporarily disabled the nav save buttons
                       if ( disable_nav_save_buttons ) {
                        
                       e.preventDefault();
                        
                       alert(disable_nav_save_buttons);
                        
                       return false;   
                        
                       }
                       else if ( $('#' + admin_id, window.parent.document).is(":visible") ) {
                       
                       console.log('admin area id IS VISIBLE = ' + admin_id);
                       
                       
                            // We used hash of conf_id, so we can always access the slot without parsing non-alphanumeric characters
                            if ( typeof parent.admin_interface_check[hashed_id] != 'undefined' && parent.admin_interface_check[hashed_id]['missing_interface_configs'] ) {
                            
                            alert('INCOMPLETE admin INTERFACING config detected for the "' + parent.admin_interface_check[hashed_id]['affected_section'] + '" ' + parent.admin_interface_check[hashed_id]['interface_config_type'] + '. The missing config(s) are HIGHLIGHTED IN RED below in this section. Please have your web developer add these required INTERFACING config parameters for this ' + parent.admin_interface_check[hashed_id]['interface_config_type'] + '.');
          
                            event.preventDefault();
                            
                            return false;
          
                            }
                            else {
         
                            form_submit_queued = true;
                   
                            parent.admin_settings_save_init = true; // Allows auto-refreshing of any admin areas that require it
          
                            $(window.frameElement).contents().find("#update_config").submit();
          
                            }
     
                       }
                   
                   
                   }
               });
          
          
          }
     
     
     }
     // Update user area
	else if ( !is_admin ) {
              
     // Checking for CHANGED form data          
     var check_update_form = document.querySelector('#coin_amnts');

          
          if ( check_update_form != null ) {


               // Listen for input events on the form
               check_update_form.addEventListener('input', function (event) {
               red_save_button();
               });
          
     
               $('.user_settings_save').on({
                  "click":function(e){
         
                   form_submit_queued = true;
                       
                   $(check_update_form).submit();
                   
                   }
               });
               
          
          }

     
     }
	

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

    // Admin iframes LOADING
    admin_iframe_dom.forEach(function(iframe) {
         
    
          // Store an array of all admin iframe IDs, for use in footer.php
          if ( typeof iframe.id != 'undefined' ) {

          all_admin_iframe_ids.push(iframe.id);
          
               if ( is_iframe ) {
               var iframe_desc = '(scanned from iframe)';
               }
               else {
               var iframe_desc = '(scanned from parent)';
               }

          //console.log('admin iframe ID '+iframe_desc+' = ' + iframe.id);
          
          }
          
       
          // When admin iframe loads / reloads
          iframe.addEventListener('load', function() {
          
          // Reset selected dropdown navs being selected in 3-deep nav
          $(".custom-3deep a.dropdown-item").removeClass("secondary-select");
          
          // Reset admin 'save settings' tracking
          unsaved_admin_config = false;

          $('#collapsed_sidebar .admin_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
          $('#sidebar .admin_settings_save').addClass('blue');
          $('#sidebar .admin_settings_save').removeClass('red_bright');
          
          // Always scroll to top left on load / reload for UX
          iframe.contentWindow.scrollTo(0,0);
    
          iframe_size_adjust(iframe);
          $("#"+iframe.id+"_loading").fadeOut(250);
          $("#"+iframe.id).fadeIn(250);
          
          
              // Before admin iframe unloads
              // (MUST BE NESTED IN 'load', AND USE contentWindow)
              iframe.contentWindow.addEventListener('beforeunload', function() {
              $("#"+iframe.id+"_loading").fadeIn(250);
              $("#"+iframe.id).fadeOut(250);
              scroll(0,0); // Force scrolling to top of iframe (so end-user always sees notices above iframe [iframe loading / background tasks, etc])
              });

          
              // Before admin iframe submits
              // (MUST BE NESTED IN 'load', AND USE contentWindow)
              iframe.contentWindow.addEventListener('submit', function() {
                   // logic here if needed
              });

          
          });
      
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Before page unload
    window.addEventListener('beforeunload', function (e) {
        
    // (suppress the 'loading' subsection for iframes from showing, when leaving the admin area)
    // (worse case is cancelled, and 'loading...' doesn't show for admin iframes again until parent page reload)
    $("div#admin_tab_content div div.iframe_loading_placeholder").html('');
    $("div#admin_tab_content div div.iframe_loading_placeholder").removeClass("loading");
    
    // Scroll position for secondary user area pages
    store_scroll_position(); 
        
        // If background tasks are still running, force a browser confirmation to refresh / leave / close
        if ( background_tasks_status == 'wait' ) {
            
            if ( form_submit_queued == true ) {
            form_submit_queued = false;
            }
            
        $("#background_loading_span").html("Please wait, finishing background tasks...").css("color", "#ff4747", "important");
        
        event.preventDefault();
        
        console.log('Default action stopped for: beforeunload');
        
        e.returnValue = '';
        
        return false;
        
        }
        else {
        app_reload_notice('Reloading...');
        }
        
    }); 


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Mark correct nav wrapper as active in interface
	if ( is_admin && !is_iframe ) {

	var nav_selector = $('.admin-nav-wrapper');

	var nav_unselector = $('.user-nav-wrapper');
	
     $('#adminSubmenu').toggleClass('show');

	}
	else if ( !is_iframe ) {
	     
	var nav_selector = $('.user-nav-wrapper');
	
	var nav_unselector = $('.admin-nav-wrapper');
	
     $('#userSubmenu').toggleClass('show');

	}
	
	
	if ( !is_iframe ) {
	     
     	nav_selector.each(function(){
     	$(this).addClass('active');
     	});
     	
     	
     	nav_unselector.each(function(){
     	$(this).removeClass('active');
     	});
	
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////

    
    // Mirror hidden errors output in the footer over to the alert bell area with javascript
    // Run AFTER check to see if alerts are present
    // NOT IFRAME
    if ( !is_iframe ) {
	
        // See if any alerts are present
        if ( $('#app_error_alert').html() == '' ) {
        $('#app_error_alert').html('No new runtime alerts.');
        }
        else {
        $(".toggle_alerts").attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        
    $('#alert_bell_area').html( "<span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span><div style='min-height: 0.7em;'></div>" + $('#app_error_alert').html() );
    
    }
    // IS IFRAME
    else {
        
        if ( $('#app_error_alert', window.parent.document).html() == 'No new runtime alerts.' && $('#iframe_error_alert').html() != '' ) {
        $('#app_error_alert', window.parent.document).html( $('#iframe_error_alert').html() );
        $(".toggle_alerts", window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        else if ( $('#iframe_error_alert').html() != '' ) {
        $('#app_error_alert', window.parent.document).html( $('#app_error_alert', window.parent.document).html() + $('#iframe_error_alert').html() );
        $(".toggle_alerts", window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        
    $('#alert_bell_area', window.parent.document).html( "<span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span><div style='min-height: 0.7em;'></div>" + $('#app_error_alert', window.parent.document).html() );
        
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Admin hashed nonce inserted in admin iframe forms
    if ( is_iframe && is_admin && !is_login_form ) {
    
     
         if ( Base64.decode(admin_area_sec_level) == 'medium' ) {
     
         var forms_array = document.getElementsByTagName("form");
         
         
             for (var form_count = 0; form_count < forms_array.length; form_count++) {
                     
             has_medium_security_nonce = false;
                 
             inputs_array = forms_array[form_count].getElementsByTagName("input");
                 
                 
                 for (var input_count = 0; input_count < inputs_array.length; input_count++) {
                     
                     if ( inputs_array[input_count].name == 'medium_security_nonce' ) {
                     has_medium_security_nonce = true;
                     }
                 
                 }
                 
                 
                 if ( has_medium_security_nonce == false ) {
                     
                 new_input = document.createElement("input");
             
                 new_input.setAttribute("type", "hidden");
                 
                 new_input.setAttribute("name", "medium_security_nonce");
                 
                 new_input.setAttribute("value", Base64.decode(medium_sec_token) );
                 
                 forms_array[form_count].appendChild(new_input);
                 
                 }
                 
             
             }
             
         
         }
         
     }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // 2FA field / ID info (for UX) inserted in admin iframe forms
    // (so a value is propigated for each form, from the 2FA input field)
    if ( is_iframe && is_admin && !is_login_form ) {
         
     
         if ( Base64.decode(admin_area_2fa) != 'off' || $(window.parent.document.location).attr('hash') == '#admin_security' ) {
     
         var forms_array = document.getElementsByTagName("form");
         
         
             for (var form_count = 0; form_count < forms_array.length; form_count++) {
                     
             has_2fa_input = false;
                 
             inputs_array = forms_array[form_count].getElementsByTagName("input");
                 
                 
                 for (var input_count = 0; input_count < inputs_array.length; input_count++) {
                     
                     if ( inputs_array[input_count].name == '2fa_code' ) {
                     has_2fa_input = true;
                     }
                 
                 }
                 
                 
                 if ( has_2fa_input == false ) {
                     
                 new_input = document.createElement("input");
             
                 new_input.setAttribute("type", "hidden");
                 
                 new_input.setAttribute("name", "2fa_code");
                 
                 new_input.setAttribute("value", "");
                 
                 new_input.setAttribute("class","2fa_code_target");
                 
                 forms_array[form_count].appendChild(new_input);
                 
                 //////////////////////////////////////////////
                     
                 new_input = document.createElement("input");
             
                 new_input.setAttribute("type", "hidden");
                 
                 new_input.setAttribute("name", "2fa_code_id");
                 
                 new_input.setAttribute("value", "2fa_code_0"); // Default (still changes if user input is from another ID)
                 
                 new_input.setAttribute("class","2fa_code_id_target");
                 
                 forms_array[form_count].appendChild(new_input);
                 
                 }
                 
             
             }
         
     
             $( "input.2fa_code_input" ).on( "input", function() {
             $('input.2fa_code_target').val( $(this).val() );
             $('input.2fa_code_id_target').val( $(this).attr('id') );
             });
              
         
         }
         

     }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Plus-minus elements (font size interface ONLY)
     $('.btn-number').click(function(e){
         e.preventDefault();
         
         fieldName = $(this).attr('data-field');
         type      = $(this).attr('data-type');
         var input = $("input[name='"+fieldName+"']");
         var currentVal = Number(input.val());
         if (!isNaN(currentVal)) {
             if(type == 'minus') {
                 
                 if(currentVal > input.attr('min')) {
                     input.val(currentVal - 1).change();
                 } 
                 if(Number(input.val()) == input.attr('min')) {
                     $(this).attr('disabled', true);
                 }
     
             } else if(type == 'plus') {
     
                 if(currentVal < input.attr('max')) {
                     input.val(currentVal + 1).change();
                 }
                 if(Number(input.val()) == input.attr('max')) {
                     $(this).attr('disabled', true);
                 }
     
             }
         } else {
             input.val(0);
         }
     });
     ////
     ////
     $('.input-number').focusin(function(){
        $(this).data('oldValue', $(this).val());
     });
     ////
     ////
     $('.input-number').change(function() {
         
         minValue =  Number($(this).attr('min'));
         maxValue =  Number($(this).attr('max'));
         valueCurrent = Number($(this).val());
         
         name = $(this).attr('name');
         if(valueCurrent >= minValue) {
             $(".btn-number[data-type='minus'][data-field='"+name+"']").removeAttr('disabled')
         } else {
             alert('Sorry, the minimum value was reached');
             $(this).val($(this).data('oldValue'));
         }
         if(valueCurrent <= maxValue) {
             $(".btn-number[data-type='plus'][data-field='"+name+"']").removeAttr('disabled')
         } else {
             alert('Sorry, the maximum value was reached');
             $(this).val($(this).data('oldValue'));
         }
         
         
     });
     ////
     ////
     $(".input-number").keydown(function (e) {
             // Allow: backspace, delete, tab, escape, enter and .
             if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 190]) !== -1 ||
                  // Allow: Ctrl+A
                 (e.keyCode == 65 && e.ctrlKey === true) || 
                  // Allow: home, end, left, right
                 (e.keyCode >= 35 && e.keyCode <= 39)) {
                      // let it happen, don't do anything
                      return;
             }
             // Ensure that it is a number and stop the keypress
             if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                 e.preventDefault();
             }
         });
         

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
         
    // https://manos.malihu.gr/jquery-custom-content-scroller/
    // https://github.com/malihu/malihu-custom-scrollbar-plugin/issues/329
    // (SCROLLING FOR COLLAPSED SIDEBAR [WHEN IT IS LONGER THAN THE SCREEN HEIGHT])
    $("#sidebar").mCustomScrollbar({
              
              theme: scrollbar_theme,
              scrollInertia: 200,
              mouseWheel:{
                          scrollAmount: 200,
                          normalizeDelta: true
                          },
                          
    });
          
          
    // UNstyle sidebar on-click (REGULAR sidebar 3-deep (last) sub-menu)
    // (EVEN IN IFRAMES)
    $('a.custom-unstyle-dropdown-item').on('click', function() {
               
    $("a.dropdown-item").removeClass("secondary-select");
          
          if ( is_iframe ) {
          $("a.dropdown-item", window.parent.document).removeClass("secondary-select");
          }
               
    });
          
          
    // Init sidebar (IF NOT IFRAME)
    if ( !is_iframe ) {
    
         
         
         // RESET OPEN ON CLICK REGULAR sidebar 3-deep (last) sub-menu
         $('.sidebar-item :not(.custom-3deep)').on({
              "click":function(e){
              custom_3deep_menu_open = false;
              }
          });
         

         
         // ACTIVATE / DEACTIVATE CSS FOR SHOWING SUBMENUS: COMPACT sidebar 2-deep (last) sub-menu
         // (so we can have sidebar be scrollable when we are not showing a submenu)
         $('#collapsed_sidebar .dropdown-toggle').on({
              "click":function(e){
              compact_submenu(this);
              }
          });
          
          
          // Reset COMPACT sidebar CSS on hiding of dropdown menu
          // (allows vertical scrolling AFTER being closed)
          $('#collapsed_sidebar .dropdown-toggle').on('hidden.bs.dropdown', function () {
          compact_submenu();
          });

         
         // KEEP OPEN ON CLICK: COMPACT sidebar 2-deep (last) sub-menu
         $('#collapsed_sidebar .dropdown-menu').on({
              "click":function(e){
              e.stopPropagation();
              }
          });
          
          
          // UNUSED, BUT KEEP FOR NOW
          const coll_sb = document.querySelector('#collapsed_sidebar');
          coll_sb.addEventListener('scroll', () => {
          collapsed_sidebar_scroll_position = coll_sb.scrollTop; // reuse `coll_sb` innstead of querying the DOM again
          //console.log('#collapsed_sidebar scroll position = ' + collapsed_sidebar_scroll_position);
          }, {passive: true});
         
         
         // KEEP OPEN ON CLICK REGULAR sidebar 3-deep (last) sub-menu
         // ALSO RESET OPEN ON CLICK Custom 3-deep (last) sub-menu
         $('#sidebar_menu .dropdown-menu').on({
              "click":function(e){
              custom_3deep_menu_open = false;
              e.stopPropagation();
              }
          });
       
         
         
         // SET ADMIN AREA ACTIVE: ALL sidebars 2-deep sub-menu
         // (SYNCES WITH ANY OTHER MENU SELECTING)
         $('.admin-nav-wrapper a[aria-expanded="false"]').on({
              "click":function(e){
	
               	$('.admin-nav-wrapper').each(function(){
               	$(this).addClass('active');
               	});
               	
               	
               	$('.user-nav-wrapper').each(function(){
               	$(this).removeClass('active');
               	});    
                   
              }
          });
          
          
     
          // Toggle sidebar
         $('.sidebar_toggle').on('click', function () {
               
         toggle_sidebar();
               
          // Save user's last preferred sidebar mode (for next app load)
          var sidebar_check = $('#sidebar').css('margin-left');
               
              if ( sidebar_check == '0px' ) {
              localStorage.setItem(sidebar_toggle_storage, "closed");
              }
              else {
              localStorage.setItem(sidebar_toggle_storage, "open");
              }
            
         });
         
         

         // SET USER AREA ACTIVE: ALL sidebars 2-deep sub-menu
         // (SYNCES WITH ANY OTHER MENU SELECTING)
         $('.user-nav-wrapper a[aria-expanded="false"]').on({
              "click":function(e){
	
               	$('.user-nav-wrapper').each(function(){
               	$(this).addClass('active');
               	});
               	
               	
               	$('.admin-nav-wrapper').each(function(){
               	$(this).removeClass('active');
               	});    
                   
              }
          });
          
          
          
          // OPEN MAIN LINK ON CLICK (REGULAR sidebar 3-deep (last) sub-menu),
          // #ONLY AFTER# IT HAS OPENED THE SUBMENU AT LEAST ONCE
          $('li.custom-3deep').on('click', function() {
              
              if ( $(this).hasClass('open-first') ) {
              
              var $cust_men_a = $(this).children('a.dropdown-toggle');
              
                  if ( $cust_men_a.length && $cust_men_a.attr('href') && custom_3deep_menu_open != false ) {
                  custom_3deep_menu_open = false; // Reset
                  location.href = $cust_men_a.attr('href');
                  }
                  else if ( $cust_men_a.length && $cust_men_a.attr('href') ) {
                  custom_3deep_menu_open = true;
                  }
                  else if ( !$cust_men_a.hasClass('show') ) {
                  custom_3deep_menu_open = false;
                  }
                  
              }
              
          });
          
          
          
          // Style on-click (REGULAR sidebar 3-deep (last) sub-menu)
          $('a.dropdown-item').on('click', function() {
          $("a.dropdown-item").removeClass("secondary-select");
          $(this).addClass("secondary-select");
          });
         
         

         // Admin submenu
         $('.admin-nav-wrapper a[aria-expanded]').on({
              "click":function(e){
              
                   if ( $(this).hasClass("active") != true ) {
                        
                   $(this).addClass("active");
                   
                   $('.user-nav-wrapper').removeClass("active");
                   
                   $('.user-nav-wrapper a[aria-expanded]').removeClass("active");

                   $('.user-nav-wrapper a[aria-expanded="true"]').attr('aria-expanded', 'false');
                   
                        $("#adminSubmenu").fadeIn(250, function() {
                        $(this).addClass("show");
                        });
                        
                        $("#userSubmenu").fadeOut(250, function() {
                        $(this).removeClass("show");
                        });
                   
                   }
                   
              }
          });
         
         
         
         // User submenu
         $('.user-nav-wrapper a[aria-expanded]').on({
              "click":function(e){
              
                   if ( $(this).hasClass("active") != true ) {
                   
                   $(this).addClass("active");

                   $('.admin-nav-wrapper').removeClass("active");

                   $('.admin-nav-wrapper a[aria-expanded]').removeClass("active");

                   $('.admin-nav-wrapper a[aria-expanded="true"]').attr('aria-expanded', 'false');
                   
                        $("#userSubmenu").fadeIn(250, function() {
                        $(this).addClass("show");
                        });
                        
                        
                        $("#adminSubmenu").fadeOut(250, function() {
                        $(this).removeClass("show");
                        });

                   }
                   
              }
          });
         
          
          // Clicks INSIDE IFRAMES should close COLLAPSED SIDEBAR OPEN SUBNAV menus
          $("iframe").on("load", function(){
              
              // The workaround for equivelent of onclick event for inside iframe contents
              $(this).contents().on("mousedown, mouseup, click", function(){
              compact_submenu();
              });
              
          });
    
    
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

         // #MUST# BE THE #LAST RUN LOGIC# IN INIT.JS!
            	     
         // Wait 2 seconds before Initiating
         // (otherwise everything is NOT always registered yet for DOM manipulations)
         setTimeout(function(){
          
         // Sort the portfolio AFTER checking for privacy mode
         sorting_portfolio_table();
         
         sorting_generic_tables(true);
         
         paged_tablesort_sizechange();
         
         resize_password_notes();
         
         // Convert numbers to browser locale, BUT ONLY ON THE PORTFOLIO PAGE
         // (AS WE EASILY MESS UP THE NUMBER CALCULATIONS SERVER-SIDE, IF CONVERTED IN FORM DATA TOO)
         // !!!!!!!!!!NEVER DUPLICATE A CSS PATH IN ANY WAY, OR IT CORRUPTS NUMBER DATA!!!!!!!!!!!!!
         convert_numbers('.data .app_sort_filter', pref_number_format);
         convert_numbers('.data .crypto_worth', pref_number_format);
         convert_numbers('.portfolio_summary .private_data', pref_number_format);
         
         
             // If we are in the user section, AND gains / losses have been calculated,
             // then put the gain / loss summary in the page title
             if (
             !is_iframe && !is_admin && !is_login_form
             && $('#gain_loss_data').text().length && $('#gain_loss_data').text() != ''
             ) {
             doc_title_stats = $('#gain_loss_data').text();
             }
         
              
	    // Check if privacy mode for assets held is enabled
	    // (#MUST# RUN AFTER INIT.JS HAS SET ALL DYN VARS, and after doc_title_stats set above)
         privacy_mode(); 

         init_range_sliders();
         
         
              $('textarea[data-autoresize]').each(function(){
                autosize(this);
              }).on('autosize:resized', function(){
              
                   admin_iframe_dom.forEach(function(iframe) {
                   iframe_size_adjust(iframe);
                   });
                                             
              });
         
         }, 2000);
         

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
});
