
// Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com

window.zingAlert= function(){
  window.alert("PRIVACY ALERT!\n\nUsing the 'Download [filetype]' menu links sends the chart data to export.zingchart.com, to create the download file.\n\nTo preserve privacy, CHOOSE 'View As PNG' INSTEAD, then opposite-click over the chart and choose 'Save Image As', to save the PNG image to your computer.")
}

// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function(){
    
    
// PHP used instead for logging / alerts, but leave here in case we want to use pure-javascript
// cookie creation some day (which could help pre-detect too-large headers that crash an HTTP server)
// console.log( array_byte_size(document.cookie) );

    
    // If all cookie data is above threshold trigger, warn end-user in UI
    if ( cookies_size_warning != 'none' ) {
    $("#header_size_warning").css({ "display": "block" });
    $("#header_size_warning").html(cookies_size_warning + '. (warning thresholds are adjustable in the Admin Config Power User section)');
    }
        
    
privacy_mode(); // Privacy mode for assets held
	

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


    // Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
    // (does NOT affect a standard javascript ELEMENT.submit() call)
    $("form").submit(function(event) { 
        
        // Checking if privacy mode is enabled (which should disable updating anything)
        if ( app_reloading_placeholder(0) == false ) {
        event.preventDefault();
        return false;
        }
        
    });


// Render interface after loading (with transition effects)
$("#app_loading").hide(250, 'linear'); // 0.25 seconds

$("#content_wrapper").show(250, 'linear'); // 0.25 seconds
$("#content_wrapper").css('display','inline'); // MUST display inline to center itself cross-browser
  
// Charts background / border
$(".chart_wrapper").css({ "background-color": window.charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + window.charts_border });

// Dynamic table header updating
$("span.btc_prim_currency_pair").html(window.btc_prim_currency_pair); 


//////////////////////////////////////////////////////////////////////////////


    // We only want to load our vertical scroll position on secondary start pages that are't background-loading AFTER page load
    // (WE ALREADY LOAD get_scroll_position() in charts_loading_check() AND feeds_loading_check() FOR THE DYNAMIC PAGE LOADING)
    if ( $(location).attr('hash') != '' && $(location).attr('hash') != '#news' && $(location).attr('hash') != '#charts' ) {
    get_scroll_position('init'); // Run AFTER showing content
    }

random_tips(); // https://codepen.io/kkoutoup/pen/zxmGLE

start_utc_time();

autosize(document.querySelector('textarea[data-autoresize]'));


///////////////////////////////////////////////////////////////////////////////

  
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


	//////////////////////////////////////////////////////////
	
	if ( getCookie("coin_reload") ) {
	auto_reload();
	}
	
	//////////////////////////////////////////////////////////
	
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
	    
	//////////////////////////////////////////////////////////
  
  
	$('#top_tab_nav').each(function(){
		
	// For each set of tabs, we want to keep track of
	// which tab is active and it's associated content
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
	
	
	//////////////////////////////////////////////////////////

});
