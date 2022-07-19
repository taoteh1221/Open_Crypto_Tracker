
// Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


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
$(".chart_wrapper").css({ "background-color": window.charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + window.charts_border });


// Dynamic table header updating
$("span.btc_prim_currency_pair").html(window.btc_prim_currency_pair); 


// Check if privacy mode for assets held is enabled
privacy_mode(); 

// Random tips on the update page 
// https://codepen.io/kkoutoup/pen/zxmGLE
random_tips(); 

// Show UTC time count in logs UI sections
start_utc_time(); 


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Activate auto-reload
	if ( getCookie("coin_reload") ) {
	auto_reload();
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Monitor admin iframes for auto-height adjustment WHEN THEY SHOW
    $(".admin_iframe").each(function(){
    iframe_adjuster.observe(this);
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Trading notes
	if ( typeof notes_storage != 'undefined' && localStorage.getItem(notes_storage) && $("#notes").length ) {
    document.getElementById("notes").value = localStorage.getItem(notes_storage);
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
	
	
    // See if any alerts are present
    if ( $('#app_error_alert').html() == '' ) {
    $('#app_error_alert').html('No new runtime alerts.');
    }
    else {
    $("#alert_bell_image").attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
    }


// Mirror hidden errors output in the footer over to the alert bell area with javascript
// Run AFTER check to see if alerts are present
$('#alert_bell_area').html( "<span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span><br />" + $('#app_error_alert').html() );


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
    // Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
    // (does NOT affect a standard javascript ELEMENT.submit() call)
    $("form").submit(function(event) { 
    
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
    
          iframe_adjust(iframe);
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
        if ( window.background_tasks_status == 'wait' ) {
        $("#background_loading_span").html("Please wait, finishing background tasks...").css("color", "#ff4747", "important");
        event.preventDefault();
        e.returnValue = '';
        }
        
    }); 


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
	
	
    // Page zoom support for chrome (only shown in desktop app) 
    // (firefox skews the entire page, safari untested)
    if ( app_edition == 'desktop' ) {
    
        // Plus button
        $('#plusBtn').on('click',function(){
        
        var step = 2;
        currzoom = parseFloat(currzoom) + step; 
        $('body').css('zoom', ' ' + currzoom + '%');
        
        localStorage.setItem('currzoom', currzoom);
        $("#zoom_show_ui").html(currzoom + '%');
        //console.log(currzoom);
        
            if ( window.is_admin == true ) {
                admin_iframe_load.forEach(function(iframe) {
                iframe_adjust(iframe);
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
        
            if ( window.is_admin == true ) {
                admin_iframe_load.forEach(function(iframe) {
                iframe_adjust(iframe);
                });
            }
        
        });
        
    } // END page zoom logic


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
	// Table data sorter config
	if ( document.getElementById("coins_table") ) {
		
		$("#coins_table").tablesorter({
			
			sortList: [ [sorted_by_col, sorted_asc_desc] ],
			theme : theme_selected, // theme "jui" and "bootstrap" override the uitheme widget option in v2.7+
			textExtraction: sort_extraction,
			widgets: ['zebra'],
		    headers: {
				
			// disable sorting of the first column (we can use zero or the header class name)
			  '.no-sort' : {
			  // disable it by setting the property sorter to false
			  sorter: false
			  },
					0: { 
					sorter:'sortprices' 
					},
					2: { 
					sorter:'sortprices' 
					},
					3: { 
					sorter:'sortprices' 
					},
					6: { 
					sorter:'sortprices' 
					},
					7: { 
					sorter:'sortprices' 
					},
					9: { 
					sorter:'sortprices' 
					},
					10: { 
					sorter:'sortprices' 
					}
			
			}
			
		});
		
		
		// add parser through the tablesorter addParser method 
		$.tablesorter.addParser({ 
			// set a unique id 
			id: 'sortprices', 
			is: function(s) { 
			// return false so this parser is not auto detected 
			return false; 
			}, 
			format: function(s) { 
			// format your data for normalization 
			return s.toLowerCase().replace(/\,/,'').replace(/ggggg/,'').replace(/\W+/,''); 
			}, 
			// set type, either numeric or text 
			type: 'numeric' 
		}); 
	
	
	}


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	

    // #MUST# BE THE #LAST RUN LOGIC# IN INIT.JS!
    $('textarea[data-autoresize]').each(function(){
      autosize(this);
    }).on('autosize:resized', function(){
      //console.log('textarea height updated');
    });


	/////////////////////////////////////////////////////////////////////////////////////////////////////
	
	
});
