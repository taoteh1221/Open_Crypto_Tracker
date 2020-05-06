
// Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


// Wait until the DOM has loaded before querying the document
$(document).ready(function(){
	

// Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
// (does NOT affect a standard javascript ELEMENT.submit() call)
$("form").submit(function(event) { 
app_reloading_placeholder();
});


// Render interface after loading (with transition effects)
$("#app_loading").hide(250, 'linear'); // 0.25 seconds

$("#content_wrapper").show(250, 'linear'); // 0.25 seconds
$("#content_wrapper").css('display','inline'); // MUST display inline to center itself cross-browser
  
  
	
// Charts background / border
$(".chart_wrapper").css({ "background-color": window.charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + window.charts_border });


// Dynamic table header updating
$("span.btc_primary_currency_pairing").html(window.btc_primary_currency_pairing); 


	////////////////////////////////////////////////////////
	
	autosize(document.querySelector('textarea[data-autoresize]'));

	//////////////////////////////////////////////////////////
  
  
  // Disclaimer / newbie warning / etc links
  $('.show').click(function() {
      $('.show_' + $(this).attr('id') ).toggle();
  });
  
  
  // Dynamically adjust admin tab content width
  $('.admin_change_width').click(function() {
  
  	if ( $(this).data('width') == 'full' ) {
  	$("#admin_wrapper").css('max-width','100%');
  	$("#admin_tab_content").css('max-width','100%');
  	}
  	else {
  	$("#admin_wrapper").css('max-width','1350px');
  	$("#admin_tab_content").css('max-width','1350px');
  	}
  
  });


	//////////////////////////////////////////////////////////
	
	if ( getCookie("coin_reload") ) {
		
		if ( getCookie("coin_reload").length > 0 ) {
	
        //console.log('auto reload function triggered...');
	
		auto_reload(getCookie("coin_reload"));
	
   	}
   
	}
	
	//////////////////////////////////////////////////////////
	
	if ( document.getElementById("coins_table") ) {
		
		$("#coins_table").tablesorter({
			
			sortList: [[sorted_by_col,sorted_by_asc_desc]],
			theme : tablesort_theme, // theme "jui" and "bootstrap" override the uitheme widget option in v2.7+
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
