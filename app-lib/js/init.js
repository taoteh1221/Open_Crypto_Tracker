
// Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


window.zingAlert= function(){
  window.alert("PRIVACY ALERT!\n\nUsing the 'Download [filetype]' menu links sends the chart data to export.zingchart.com, to create the download file.\n\nTo preserve privacy, CHOOSE 'View As PNG' INSTEAD, then opposite-click over the chart and choose 'Save Image As', to save the PNG image to your computer.")
}


// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function(){ 
    
    
// PHP used instead for logging / alerts, but leave here in case we want to use pure-javascript
// cookie creation some day (which could help pre-detect too-large headers that crash an HTTP server)
// console.log( array_byte_size(document.cookie) );

    
// Render interface after loading (with transition effects)
$("#app_loading").hide(250, 'linear'); // 0.25 seconds
$("#content_wrapper").show(250, 'linear'); // 0.25 seconds
$("#content_wrapper").css('display','inline'); // MUST display inline to center itself cross-browser

  
// Charts background / border
$(".chart_wrapper").css({ "background-color": charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + charts_border });


// Dynamic table header updating
$("span.btc_prim_currency_pair").html(btc_prim_currency_pair); 


// Random tips on the update page 
// https://codepen.io/kkoutoup/pen/zxmGLE
random_tips(); 

// Show UTC time count in logs UI sections
start_utc_time(); 
	
// 'Loading X...' UI notices
background_tasks_check();


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Activate auto-reload
	if ( get_cookie("coin_reload") ) {
	auto_reload();
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Monitor admin iframes for auto-height adjustment WHEN THEY SHOW
    reset_iframe_heights();

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Trading notes
	if ( typeof notes_storage != 'undefined' && localStorage.getItem(notes_storage) && $("#notes").length ) {
     $("#notes").val( localStorage.getItem(notes_storage) );
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	if ( emulated_cron_enabled ) {
	
     // Emulate a cron job every X minutes...
     cron_already_ran = false;
    
     emulated_cron(); // Initial load (RELOADS from WITHIN it's OWN logic every minute AFTER)
	
	}

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // If all cookie data is above threshold trigger, warn end-user in UI
    if ( typeof cookies_size_warning != 'undefined' && cookies_size_warning != 'none' ) {
    $("#header_size_warning").css({ "display": "block" });
    $("#header_size_warning").html(cookies_size_warning + '. (warning thresholds are adjustable in the Admin Config Power User section)');
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // We only want to load our vertical scroll position on secondary start pages that are't background-loading AFTER page load
    // (WE ALREADY LOAD get_scroll_position() in charts_loading_check() AND feeds_loading_check() FOR THE DYNAMIC PAGE LOADING)
    if ( $(location).attr('hash') != '' && $(location).attr('hash') != '#news' && $(location).attr('hash') != '#charts' ) {
    get_scroll_position('init'); // Run AFTER showing content
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
    // (does NOT affect a standard javascript ELEMENT.submit() call)
    $("form").submit(function(event) { 
    
    form_submit_queued = true;
    
        // We have to run app_reloading_check() here, 
        if ( app_reloading_check(1) == 'no' ) {
        event.preventDefault();
        return false;
        }
        
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Dynamically adjust admin tab content width
    $('.admin_change_width').click(function() {
  
      	if ( $(this).data('width') == 'full' ) {
      	$("#admin_wrapper").css('max-width','100%');
      	$("#admin_tab_content").css('max-width','100%');
      	}
      	else {
      	$("#admin_wrapper").css('max-width','1200px');
      	$("#admin_tab_content").css('max-width','1200px');
      	}
  
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Set proper body zoom for desktop editions
    if ( app_edition == 'desktop' ) {
        
         // Page zoom logic
         if ( localStorage.getItem('currzoom') ) {
         currzoom = localStorage.getItem('currzoom');
         }
         else {
         currzoom = 100;
         }
        
    // Just zoom body / show new zoom level in GUI,
    // and reset #topnav and #app_loading and #change_font_size to 100% beforehand
    // (iframes zoom onload in other init logic)
    $('#topnav').css('zoom', ' ' + 100 + '%');
    $('#change_font_size').css('zoom', ' ' + 100 + '%');
    $('#app_loading').css('zoom', ' ' + 100 + '%');
    $('body').css('zoom', ' ' + currzoom + '%');
    $("#zoom_show_ui").html(currzoom + '%');
                     
    }


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
	
	
    // Monitor admin iframes for load / unload events
    const admin_iframe_load = document.querySelectorAll('.admin_iframe');
    ////
    admin_iframe_load.forEach(function(iframe) {
       
          // When admin iframe loads
          iframe.addEventListener('load', function() {
    
          iframe_height_adjust(iframe);
          $("#"+iframe.id+"_loading").fadeOut(250);
          
              // Before admin iframe unloads
              // (MUST BE NESTED IN 'load', AND USE contentWindow)
              iframe.contentWindow.addEventListener('beforeunload', function() {
              $("#"+iframe.id+"_loading").fadeIn(250);
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
        e.returnValue = '';
        
        }
        
    }); 


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
        $("#alert_bell_image").attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        
    $('#alert_bell_area').html( "<span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span><br />" + $('#app_error_alert').html() );
    
    }
    // IS IFRAME
    else {
        
        if ( $('#app_error_alert', window.parent.document).html() == 'No new runtime alerts.' && $('#iframe_error_alert').html() != '' ) {
        $('#app_error_alert', window.parent.document).html( $('#iframe_error_alert').html() );
        $("#alert_bell_image", window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        else if ( $('#iframe_error_alert').html() != '' ) {
        $('#app_error_alert', window.parent.document).html( $('#app_error_alert', window.parent.document).html() + $('#iframe_error_alert').html() );
        $("#alert_bell_image", window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
        }
        
    $('#alert_bell_area', window.parent.document).html( "<span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span><br />" + $('#app_error_alert', window.parent.document).html() );
        
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// For each set of user area tabs, we want to keep track of
	// which tab is active and it's associated content
	$('#top_tab_nav').each(function(){
		
	var $active, $content, $links = $(this).find('a');

	// If the location.hash matches one of the links, use that as the active tab.
	// If no match is found, use the first link as the initial active tab.
	$active = $($links.filter('[href="'+location.hash+'"]')[0] || $links[0]);
	$active.addClass('active');

	$content = $($active[0].hash);

	    // Hide the remaining content
	    $links.not($active).each(function () {
	    $(this.hash).hide();
	    });

	    // Bind the click event handler
	    $(this).on('click', 'a', function(e){
	    // Make the old tab inactive.
	    $active.removeClass('active');
	    $content.hide();
  
	    // Update the variables with the new link and content
	    $active = $(this);
	    $content = $(this.hash);
  
	    // Make the tab active.
	    $active.addClass('active');
	    $content.show();
  
	    // Prevent the anchor's default click action
	    e.preventDefault();
	    });
	  
	
	});


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Plus-minus elements (DESKTOP EDITION'S zoom in / out interface ONLY)
    if ( app_edition == 'desktop' ) {
    
        // Plus button
        $('#plusBtn').on('click',function(){
        
        var step = 2;
        currzoom = parseFloat(currzoom) + step; 
        $('body').css('zoom', ' ' + currzoom + '%');
        
        localStorage.setItem('currzoom', currzoom);
        $("#zoom_show_ui").html(currzoom + '%');
        //console.log(currzoom);
        
            if ( is_admin == true ) {
                admin_iframe_load.forEach(function(iframe) {
                iframe_height_adjust(iframe);
                });
            }
        
        });
    
        // Minus button
        $('#minusBtn').on('click',function(){
        
        var step = 2;
        currzoom = parseFloat(currzoom) - step; 
        $('body').css('zoom', ' ' + currzoom + '%');
        
        localStorage.setItem('currzoom', currzoom);
        $("#zoom_show_ui").html(currzoom + '%');
        //console.log(currzoom);
        
            if ( is_admin == true ) {
                admin_iframe_load.forEach(function(iframe) {
                iframe_height_adjust(iframe);
                });
            }
        
        });
        
    } // END page zoom logic



	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

    // Init sidebar (IF NOT IFRAME)
    if ( !is_iframe ) {
         
         
         $("#sidebar").mCustomScrollbar({
              theme: "minimal"
         });
         
         
         // KEEP OPEN ON CLICK Custom 3-deep (last) sub-menu
         // ALSO RESET OPEN ON CLICK Custom 3-deep (last) sub-menu
         $('#sidebar_menu .dropdown-menu').on({
              "click":function(e){
              custom_3deep_menu_on = false;
              e.stopPropagation();
              }
          });
         
         
         // RESET OPEN ON CLICK Custom 3-deep (last) sub-menu
         $('.sidebar-item :not(.custom-3deep)').on({
              "click":function(e){
              custom_3deep_menu_on = false;
              }
          });
          
          
          // OPEN MAIN LINK ON CLICK (Custom 3-deep (last) sub-menu),
          // #ONLY AFTER# IT HAS OPENED THE SUBMENU AT LEAST ONCE
          $('li.custom-3deep').on('click', function() {
           
           var $cust_men_el = $(this);
           
              if ( $cust_men_el.hasClass('open-first') ) {
              
              var $cust_men_a = $cust_men_el.children('a.dropdown-toggle');
              
                  if ( $cust_men_a.length && $cust_men_a.attr('href') && custom_3deep_menu_on != false ) {
                  custom_3deep_menu_on = false;
                  location.href = $cust_men_a.attr('href');
                  }
                  else if ( $cust_men_a.length && $cust_men_a.attr('href') ) {
                  custom_3deep_menu_on = true;
                  }
                  else if ( !$cust_men_a.hasClass('show') ) {
                  custom_3deep_menu_on = false;
                  }
                  
              }
              
          });
          
     
         $('.sidebar_toggle').on('click', function () {
             // open or close navbar
             $('#sidebar').toggleClass('active');
             $('#secondary_wrapper').toggleClass('active');
             // close dropdowns
             $('.collapse.in').toggleClass('in');
             // and also adjust aria-expanded attributes we use for the open/closed arrows
             // in our CSS
             $('a[aria-expanded=true]').attr('aria-expanded', 'false');
         });
         
    
    }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Admin hashed nonce inserted in admin iframe forms
    if ( is_iframe && is_admin ) {
    
     
         if ( Base64.decode(admin_area_sec_level) == 'enhanced' ) {
     
         var forms_array = document.getElementsByTagName("form");
         
         
             for (var form_count = 0; form_count < forms_array.length; form_count++) {
                     
             has_enhanced_security_nonce = false;
                 
             inputs_array = forms_array[form_count].getElementsByTagName("input");
                 
                 
                 for (var input_count = 0; input_count < inputs_array.length; input_count++) {
                     
                     if ( inputs_array[input_count].name == 'enhanced_security_nonce' ) {
                     has_enhanced_security_nonce = true;
                     }
                 
                 }
                 
                 
                 if ( has_enhanced_security_nonce == false ) {
                     
                 new_input = document.createElement("input");
             
                 new_input.setAttribute("type", "hidden");
                 
                 new_input.setAttribute("name", "enhanced_security_nonce");
                 
                 new_input.setAttribute("value", Base64.decode(enhanced_sec_token) );
                 
                 forms_array[form_count].appendChild(new_input);
                 
                 }
                 
             
             }
             
         
         }
         
     }


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
     // Plus-minus elements (font size interface ONLY)
     $('.btn-number').click(function(e){
         e.preventDefault();
         
         fieldName = $(this).attr('data-field');
         type      = $(this).attr('data-type');
         var input = $("input[name='"+fieldName+"']");
         var currentVal = parseInt(input.val());
         if (!isNaN(currentVal)) {
             if(type == 'minus') {
                 
                 if(currentVal > input.attr('min')) {
                     input.val(currentVal - 1).change();
                 } 
                 if(parseInt(input.val()) == input.attr('min')) {
                     $(this).attr('disabled', true);
                 }
     
             } else if(type == 'plus') {
     
                 if(currentVal < input.attr('max')) {
                     input.val(currentVal + 1).change();
                 }
                 if(parseInt(input.val()) == input.attr('max')) {
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
         
         minValue =  parseInt($(this).attr('min'));
         maxValue =  parseInt($(this).attr('max'));
         valueCurrent = parseInt($(this).val());
         
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
	
	
// Check if privacy mode for assets held is enabled (#MUST# RUN AFTER INIT.JS HAS SET ALL DYN VARS)
privacy_mode(); 

// Sort the portfolio AFTER checking for privacy mode
sorting_portfolio_table();


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

    // #MUST# BE THE #LAST RUN LOGIC# IN INIT.JS!
    $('textarea[data-autoresize]').each(function(){
      autosize(this);
    }).on('autosize:resized', function(){
      //console.log('textarea height updated');
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	
});
