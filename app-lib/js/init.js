
// Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


// Wait until the DOM has loaded before querying the document
$(document).ready(function(){
	

// Show "app loading" placeholder when submitting ANY form JQUERY SUBMIT METHOD, OR CLICKING A SUBMIT BUTTON
// (does NOT affect a standard javascript ELEMENT.submit() call)
$("form").submit(function() { 
app_reloading_placeholder();
});


// Render interface after loading (with transition effects)
$("#body_loading").hide(300, 'linear');

// SAFELY emulate sleeping for 0.3 seconds (to allow above animations to fully run / display before reloading)
setTimeout(function(){ console.log("setTimeout for 0.3 seconds..."); }, 300);

$("#body_wrapper").show(300, 'linear');
  
  
	
// Charts background / border
$(".chart_wrapper").css({ "background-color": window.charts_background });
$(".chart_wrapper").css({ "border": '2px solid ' + window.charts_border });


// Dynamic table header updating
$("span.btc_primary_currency_pairing").html(window.btc_primary_currency_pairing); 


	////////////////////////////////////////////////////////
	
	autosize(document.querySelector('textarea[data-autoresize]'));

	//////////////////////////////////////////////////////////
  
  $( '.show' ).click(function() {
      $( '.show_' + $(this).attr('id') ).toggle();
  });

	//////////////////////////////////////////////////////////
	
	if ( getCookie("coin_reload").length > 0 ) {
	
        //console.log('auto reload function triggered...');
	
	auto_reload(getCookie("coin_reload"));
    
	}
	
	//////////////////////////////////////////////////////////
	
				
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
	    
	//////////////////////////////////////////////////////////
  
  
	$('ul.tabs').each(function(){
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
