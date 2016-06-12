
function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return "";
}


function auto_reload(time) {
	
	if ( window.reload_function ) {
	clearInterval(window.reload_function);
	}
	
	if ( time >= 1 ) {
		
	count_down(time, 1);
		
		if ( document.getElementById("set_use_cookies").checked == false ) {
		alert('To use auto-refresh effectively, enable "Save coin values as cookie data" in "Program Settings". Otherwise you likely will be prompted to manually re-submit form data every auto-refresh.');
		}

	
	
	setCookie("coin_reload", time, 365);
	
	
	window.reload_function = setInterval(function() {
				location.reload(true);
				}, (time * 1000));
		
	
	
	}
	else {
	count_down(time, 0);
	setCookie("coin_reload", '', 365);
	}

}

function count_down(i, toggle) {
	
	if ( window.reload_countdown ) {
	clearInterval(window.reload_countdown);
	}

	if ( toggle == 1 ) {
		
	    window.reload_countdown = setInterval(function () {
		document.getElementById("reload_countdown").innerHTML = "(" + i + " seconds remaining)";
		i-- || clearInterval(int);  //if i is 0, then stop the interval
	    }, 1000);
	    
	}
	else {
	document.getElementById("reload_countdown").innerHTML = "";
	}
    
}



// Wait until the DOM has loaded before querying the document
$(document).ready(function(){



	if ( getCookie("coin_reload").length > 0 ) {
	
        //console.log('auto reload function triggered...');
	
	auto_reload(getCookie("coin_reload"));
    
	}
	
				
	$("#coins_table").tablesorter({
		
		// sort on the tenth column, order desc 
		sortList: [[9,1]],
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
			3: { 
			    sorter:'sortprices' 
			},
			4: { 
			    sorter:'sortprices' 
			},
			5: { 
			    sorter:'sortprices' 
			},
			6: { 
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
			});
