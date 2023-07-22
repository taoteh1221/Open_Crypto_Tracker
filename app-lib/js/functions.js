
// Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com


/////////////////////////////////////////////////////////////


function force_2_digits(num) {
return ("0" + num).slice(-2);
}


/////////////////////////////////////////////////////////////


function storage_app_id(var_name) {
return Base64.decode(ct_id) + "_" + var_name;
}
	

/////////////////////////////////////////////////////////////


function delete_cookie( name ) {
document.cookie = name + '=; SameSite=Strict; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


/////////////////////////////////////////////////////////////


function array_byte_size(val) {
return new Blob([JSON.stringify(val)]).size;
}


/////////////////////////////////////////////////////////////


function set_target_action(obj_id, set_target, set_action) {
document.getElementById(obj_id).target = set_target;
document.getElementById(obj_id).action = set_action;
}


/////////////////////////////////////////////////////////////


function to_timestamp(year,month,day,hour,minute,second) {
 var datum = new Date(Date.UTC(year,month-1,day,hour,minute,second));
 return datum.getTime()/1000;
}


/////////////////////////////////////////////////////////////


function background_loading_notices(message) {

    if ( $("#background_loading_span").html() != 'Please wait, finishing background tasks...' ) {
    $("#background_loading_span").html(message).css("color", "#dd7c0d", "important");
    }

}


/////////////////////////////////////////////////////////////


function is_int(value) {
  
  if (isNaN(value)) {
  return false;
  }

return Number.isInteger( parseFloat(value) );

}


/////////////////////////////////////////////////////////////


function get_url_param($key) {

var queryString = window.location.search;

var urlParams = new URLSearchParams(queryString);

return urlParams.get($key);

}


/////////////////////////////////////////////////////////////


function refresh_image(imgElement, imgURL) {   
     
// create a new timestamp, to force-refresh image
timestamp = new Date().getTime();        
el = document.getElementById(imgElement);        
queryString = "?t=" + timestamp;           
el.src = imgURL + queryString;    
 
}    


/////////////////////////////////////////////////////////////


function autoresize_update() {
    
const all_autosize_textareas = document.querySelectorAll("[data-autoresize]");

    all_autosize_textareas.forEach(function(textarea) {
    autosize.update(textarea);
    });

}


/////////////////////////////////////////////////////////////


function store_scroll_position() {

// IN CASE we are loading / POSTING DATA ON a different start page than the portfolio page,
// STORE the current scroll position before the page reload
// WE ONLY CALL THIS FUNCTION ONCE PER PAGE UNLOAD (body => onbeforeunload)
localStorage.setItem(scroll_position_storage, window.scrollY);

}


/////////////////////////////////////////////////////////////


var sort_extraction = function(node) {

// Sort with the .app_sort_filter CSS class as the primary sorter
sort_target = $(node).find(".app_sort_filter").text();

// Remove any commas from number sorting
return sort_target.replace(/,/g, '');

}


/////////////////////////////////////////////////////////////


function iframe_url(name, val=null, mode='get') {

     if ( mode == 'get' ) {
     return localStorage.getItem(name);
     }
     else if ( mode == 'set' ) {
     localStorage.setItem(name, val);
     }
     else if ( mode == 'delete' ) {
     localStorage.removeItem(name);
     }

}


/////////////////////////////////////////////////////////////


function set_cookie(cname, cvalue, exdays) {
     
d = new Date();
d.setTime(d.getTime() + (exdays*24*60*60*1000));

is_secure = app_edition == 'server' ? ' Secure' : '';
expires = "expires="+d.toUTCString();

document.cookie = cname + "=" + cvalue + "; " + expires + "; SameSite=Strict;" + is_secure;

}


/////////////////////////////////////////////////////////////


function update_alert_percent() {

	if ( document.getElementById("alert_percent").value == "yes" ) {
	document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|"
	+ document.getElementById("percent_change_amnt").value + "|" + document.getElementById("percent_change_filter").value + "|" 
	+ document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
	}
	else {
	document.getElementById("use_alert_percent").value = "";
	}

}


/////////////////////////////////////////////////////////////


function start_utc_time() {

today = new Date();
date = today.getUTCFullYear() + '-' + force_2_digits(today.getUTCMonth() + 1) + '-' + force_2_digits( today.getUTCDate() );
time = force_2_digits( today.getUTCHours() ) + ":" + force_2_digits( today.getUTCMinutes() ) + ":" + force_2_digits( today.getUTCSeconds() );

$("span.utc_timestamp").text('[' + date + ' ' + time + '.000]');

utc_time = setTimeout(start_utc_time, 1000);

}


/////////////////////////////////////////////////////////////


function add_css_class_recursively(topElement, CssClass) {

$(topElement).addClass(CssClass);

    $(topElement).children().each(
            function() {
                 $(this).addClass(CssClass);
                 add_css_class_recursively($(this), CssClass);
            }
    );
            
}


/////////////////////////////////////////////////////////////


// https://mottie.github.io/tablesorter/docs/
function reset_tablesorter(priv_pmode) {
    
col = priv_pmode == 'on' ? 0 : sorted_by_col;

$("#coins_table").find("th:eq("+col+")").trigger("sort");

    // Reverse the sort, if it's decending (1)
    if ( priv_pmode != 'on' && sorted_asc_desc > 0 ) {
    $("#coins_table").find("th:eq("+col+")").trigger("sort");
    }

}


/////////////////////////////////////////////////////////////


function cron_run_check() {
	
//console.log('loaded charts = ' + charts_loaded.length + ', all charts = ' + charts_num);

	if ( cron_already_ran == true ) {
	return 'done';
	}
	else {
	background_loading_notices("Checking / Running Scheduled Tasks...");
	$("#background_loading").show(250); // 0.25 seconds
	return 'active';
	}

}


/////////////////////////////////////////////////////////////


function validate_form(form_id, field) {
	
x = document.forms[form_id][field].value;

  if (x == "") {
  alert(field + " must be populated.");
  return false;
  }
  else {
  $("#" + form_id).submit();
  }
  
}


/////////////////////////////////////////////////////////////


function load_highlightjs(id=false) {

     if ( id == false ) {
       document.querySelectorAll('pre code').forEach((elm) => {
         hljs.highlightElement(elm);
       });
     }
     else {
     hljs.highlightElement( document.getElementById(id) );
     //hljs.highlightBlock();
     }

}


/////////////////////////////////////////////////////////////


function charts_loading_check() {
	
//console.log('loaded charts = ' + charts_loaded.length + ', all charts = ' + charts_num);

    // NOT IN ADMIN AREA (UNLIKE CRON EMULATION)
	if ( charts_loaded.length >= charts_num || is_admin == true ) {
	return 'done';
	}
	else {
	background_loading_notices("Loading Charts...");
	$("#background_loading").show(250); // 0.25 seconds
	return 'active';
	}

}


/////////////////////////////////////////////////////////////


function feeds_loading_check() {
	
//console.log('loaded feeds = ' + feeds_loaded.length + ', all feeds = ' + feeds_num);

    // NOT IN ADMIN AREA (UNLIKE CRON EMULATION)
	if ( feeds_loaded.length >= feeds_num || is_admin == true ) {
	return 'done';
	}
	else {
	background_loading_notices("Loading News Feeds...");
	$("#background_loading").show(250); // 0.25 seconds
	return 'active';
	}

}


/////////////////////////////////////////////////////////////


// https://jsfiddle.net/TheAL/ednxgwrj/ 
function footer_banner(js_storage, notice_html) {
     
document.write('<div class="footer_banner">' + notice_html + '<button class="footer_banner_button">I Understand</button></div>');

var footer_notice = $('.footer_banner');

     if ( localStorage.getItem(js_storage) != "understood" ) {
         footer_notice.slideDown(500);
     }
     
     $('.footer_banner .footer_banner_button').click(function () {
         footer_notice.slideUp(500);
         localStorage.setItem(js_storage, "understood");
     });

}


/////////////////////////////////////////////////////////////


function get_scroll_position(tracing) {

	// If we are using a different start page than the portfolio page,
	// RETRIEVE any stored scroll position we were at before the page reload
    if ( $(location).attr('hash') != '' && !isNaN( localStorage.getItem(scroll_position_storage) ) ) {
        
    	$('html, body').animate({
       	scrollTop: localStorage.getItem(scroll_position_storage)
    	}, 'slow');
    		
    }
    // Reset if we're NOT starting on a secondary page
    else {
	localStorage.setItem(scroll_position_storage, 0);
    }

}


/////////////////////////////////////////////////////////////


function compact_submenu(elm=false) {
     
//console.log('closing COMPACT submenu');

     if ( elm && $(elm).hasClass("show") == true ) {
     
     $("#collapsed_sidebar").css('overflow','unset');
     
     dynamic_position( $('#collapsed_sidebar .dropdown-menu.show'), false, true );
     
     }
     else {
                   
     $('#collapsed_sidebar a[aria-expanded]').removeClass("active");
                   
     $('#collapsed_sidebar a[aria-expanded]').removeClass("show");
                   
     $('#collapsed_sidebar ul').removeClass("show");
     
     $('#collapsed_sidebar a[aria-expanded="true"]').attr('aria-expanded', 'false');
               
     $("#collapsed_sidebar").css('overflow-x','hidden');
                   
     $("#collapsed_sidebar").css('overflow-y','auto');

     }
              
}


/////////////////////////////////////////////////////////////


function toggle_sidebar() {

// open or close navbar
$('#sidebar').toggleClass('active');
$('#secondary_wrapper').toggleClass('active');
              
// close dropdowns
$('.collapse.in').toggleClass('in');
              
// and also adjust aria-expanded attributes we use for the open/closed arrows
// in our CSS
$('a[aria-expanded="true"]').attr('aria-expanded', 'false');

// Scroll left, if we are wider than the page (for UX)
scroll_start();

}

	
/////////////////////////////////////////////////////////////


function app_reloading_check(form_submission=0, new_location=0) {

        
    // Disable form updating in privacy mode
    if ( get_cookie('priv_toggle') == 'on' && form_submission == 1 ) {
    alert('Submitting data is not allowed in privacy mode.');
    return 'no'; // WE NORMALLY DON'T RETURN DATA HERE BECAUSE WE ARE REFRESHING OR SUBMITTING, SO WE CANNOT USE RETURN FALSE RELIABLY
    }
    // If this is an ADMIN submenu section, AND we are NOT in the admin area,
    // AND no iframe URL has been set yet, don't reload / load new page yet (we want to show the submenu options first)
    else if ( new_location != 0 && !is_admin && new_location.split('#')[1] == 'admin_plugins' && iframe_url(admin_iframe_url) == null ) {
    return;
    }
    else {
    app_reload(form_submission, new_location);
    }

}


/////////////////////////////////////////////////////////////


function chart_toggle(obj_var) {
  
show_charts = $("#show_charts").val();
	
	if ( obj_var.checked == true ) {
	$("#show_charts").val("[" + obj_var.value + "]" + "," + show_charts);
	}
	else {
	$("#show_charts").val( show_charts.replace("[" + obj_var.value + "],", "") );
	}
	
  
show_charts = $("#show_charts").val(); // Reset var with any new data

// Error checking
$("#show_charts").val( show_charts.replace(",,", ",") );
	
}


/////////////////////////////////////////////////////////////


function crypto_val_toggle(obj_var) {
  
show_crypto_val = $("#show_crypto_val").val();
	
	if ( obj_var.checked == true ) {
	$("#show_crypto_val").val("[" + obj_var.value + "]" + "," + show_crypto_val);
	}
	else {
	$("#show_crypto_val").val( show_crypto_val.replace("[" + obj_var.value + "],", "") );
	}
	
  
show_crypto_val = $("#show_crypto_val").val(); // Reset var with any new data

// Error checking
$("#show_crypto_val").val( show_crypto_val.replace(",,", ",") );
	
}


/////////////////////////////////////////////////////////////


function feed_toggle(obj_var) {
  
show_feeds = $("#show_feeds").val();
	
	if ( obj_var.checked == true ) {
	$("#show_feeds").val("[" + obj_var.value + "]" + "," + show_feeds);
	}
	else {
	$("#show_feeds").val( show_feeds.replace("[" + obj_var.value + "],", "") );
	}
	
  
show_feeds = $("#show_feeds").val(); // Reset var with any new data

// Error checking
$("#show_feeds").val( show_feeds.replace(",,", ",") );
	
}


/////////////////////////////////////////////////////////////


function human_time(timestamp) {
    
date = new Date(timestamp),

datevalues = [
             date.getFullYear(),
             date.getMonth()+1,
             date.getDate(),
             date.getHours(),
             date.getMinutes(),
             date.getSeconds(),
             ];

return datevalues[0] + '/' + datevalues[1] + '/' + datevalues[2] + ' @ ' + datevalues[3] + ':' + datevalues[4] + ':' + datevalues[5];

}


/////////////////////////////////////////////////////////////


function show_more(id, change_text=0) {
	
	if ( $("#"+id).is(":visible") ) {
	$("#"+id).hide(250, 'linear'); // 0.25 seconds
	}
	else {
	$("#"+id).show(250, 'linear'); // 0.25 seconds
	}
	
	if ( $("#" + change_text).text() == 'Show More' ) {
	$("#" + change_text).text('Show Less');
	}
	else if (  $("#" + change_text).text() == 'Show Less' ) {
	$("#" + change_text).text('Show More');
	}

}


/////////////////////////////////////////////////////////////


// JAVASCRIPT COOKIE ENCODING / DECODING IS #NOT# COMPATIBLE 
// WITH PHP COOKIE AUTO ENCODING / DECODING!! 
// ONLY USE THIS FOR CHECKS ON COOKIE VALS EXISTING ETC ETC!!
function get_cookie(cname) {
	
name = cname + "=";
ca = document.cookie.split(';');

    for(i=0; i<ca.length; i++) {
        c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    
return false;

}


/////////////////////////////////////////////////////////////


function safe_add_remove_class(class_name, element, mode) {
    
    if ( mode == 'add' ) {
    
        if ( document.getElementById(element) ) {
        document.getElementById(element).classList.add(class_name); 
        }
        
    }
    else if ( mode == 'remove' ) {
    
        if ( document.getElementById(element) ) {
        document.getElementById(element).classList.remove(class_name); 
        }
        
    }

    
}


/////////////////////////////////////////////////////////////


function iframe_height_adjust(elm) {


    // Set proper page zoom on the iframe
    if ( app_edition == 'desktop' ) {
    elm.contentWindow.document.body.style.zoom = currzoom + '%';
    }


    // Now that we've set any required zoom level, adjust the height
    if ( elm.id == 'iframe_system_stats' || elm.id == 'iframe_security' ) {
    extra = 1000;
    }
    else {
    extra = 120;
    }


elm.height = (elm.contentWindow.document.body.scrollHeight + extra) + "px";
              
}


/////////////////////////////////////////////////////////////


function ajax_placeholder(px_size, align, message=null, display_mode=null){
    
    
     if ( display_mode ) {
     display_mode = 'display: ' + display_mode + '; ';
     }


	if ( message ) {
	img_height = px_size - 2;
	return '<div class="align_' + align + '" style="'+display_mode+'white-space: nowrap; font-size: ' + px_size + 'px;"><img src="templates/interface/media/images/auto-preloaded/loader.gif" height="' + img_height + '" alt="" style="position: relative; vertical-align:middle;" /> ' + message + ' </div>';
	}
	else {
	img_height = px_size;
	return '<div class="align_' + align + '" style="'+display_mode+'"><img src="templates/interface/media/images/auto-preloaded/loader.gif" height="' + img_height + '" alt="" /></div>';
	}
	

}


/////////////////////////////////////////////////////////////


function get_coords(elem) { // crossbrowser version
    var box = elem.getBoundingClientRect();

    var body = document.body;
    var docEl = document.documentElement;

    var scrollTop = window.pageYOffset || docEl.scrollTop || body.scrollTop;
    var scrollLeft = window.pageXOffset || docEl.scrollLeft || body.scrollLeft;

    var clientTop = docEl.clientTop || body.clientTop || 0;
    var clientLeft = docEl.clientLeft || body.clientLeft || 0;

    var top  = box.top +  scrollTop - clientTop;
    var left = box.left + scrollLeft - clientLeft;

    return { top: Math.round(top), left: Math.round(left) };
}


/////////////////////////////////////////////////////////////


function set_admin_security(obj) {

		if ( obj.value == "normal" || obj.value == "enhanced" ) {
	     var int_api_key_reset = confirm("'Enhanced' and 'Normal' admin security modes are currently BETA (TEST) FEATURES, AND USING THEM MAY LEAD TO ISSUES UPDATING YOUR APP CONFIGURATION (editing from the PHP config files will be DISABLED).\n\nYou can RE-DISABLE these BETA features AFTER activating them (by setting the security mode back to 'High'), and you will be able to update your app configuration from the PHP config files again.");
		}
		else {
	     var int_api_key_reset = confirm("High security admin mode requires you to update your app configuration from the PHP config files.");
		}
		
		if ( int_api_key_reset ) {
		$("#toggle_admin_security").submit(); // Triggers iframe "reloading" sequence
		}
		else {
		$('input[name=opt_admin_sec]:checked').prop('checked',false);
		$('#opt_admin_sec_' + $("#sel_admin_sec").val() ).prop('checked',true);
		}

}


/////////////////////////////////////////////////////////////


function text_to_download(textToWrite, fileNameToSaveAs) {
     
var textFileAsBlob = new Blob([textToWrite], {type:'text/plain'}); 
var downloadLink = document.createElement("a");
downloadLink.download = fileNameToSaveAs;
downloadLink.innerHTML = "Download File";

    	if (window.webkitURL != null)
    	{
    		// Chrome allows the link to be clicked
    		// without actually adding it to the DOM.
    		downloadLink.href = window.webkitURL.createObjectURL(textFileAsBlob);
    	}
    	else
    	{
    		// Firefox requires the link to be added to the DOM
    		// before it can be clicked.
    		downloadLink.href = window.URL.createObjectURL(textFileAsBlob);
    		downloadLink.onclick = destroyClickedElement;
    		downloadLink.style.display = "none";
    		document.body.appendChild(downloadLink);
    	}
    
downloadLink.click();

}


/////////////////////////////////////////////////////////////


function play_audio_alert() {

audio_alert = document.getElementById('audio_alert');
				
				
	if ( audio_alert.canPlayType('audio/mpeg') ) {
	audio_alert.setAttribute('src','templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.mp3');
	}
	else if ( audio_alert.canPlayType('audio/ogg') ) {
	audio_alert.setAttribute('src','templates/interface/media/audio/Intruder_Alert-SoundBible.com-867759995.ogg');
	}
				
	
	// If subsections are still loading, wait until they are finished
     if ( $("#background_loading").is(":visible") ) {
     setTimeout(play_audio_alert, 1000); // Wait 1000 millisecnds then recheck
     return;
     }
     else {
	audio_alert.autoplay = true;
	audio_alert.play();
     }


}


/////////////////////////////////////////////////////////////


function select_all(toggle, form_name) {
	
    var checkbox, i=0;
    while ( checkbox=document.getElementById(form_name).elements[i++] ) {
    	
        if ( checkbox.type == "checkbox" ) {
            
            if ( form_name == 'activate_charts' && checkbox.checked != toggle.checked ) {
        	  checkbox.checked = toggle.checked;
            chart_toggle(checkbox);
            }
            
            else if ( form_name == 'activate_feeds' && checkbox.checked != toggle.checked ) {
        	  checkbox.checked = toggle.checked;
            feed_toggle(checkbox);
            }
            
            else if ( form_name == 'coin_amnts' && checkbox.checked != toggle.checked ) {
        	  checkbox.checked = toggle.checked;
            watch_toggle(checkbox);
            }
            
        }
        
    }
     
}


/////////////////////////////////////////////////////////////


function watch_toggle(obj_var) {
	
num_val = $("#"+obj_var.value+"_amnt").val();
num_val = num_val.replace(/,/g, '');
		
		if ( obj_var.checked == true ) {
			
			// If there is a valid coin amount OR this is MISCASSETS, uncheck it
			if ( num_val >= min_crypto_val_test || obj_var.value == 'miscassets' || obj_var.value == 'ethnfts' || obj_var.value == 'solnfts' ) {
			obj_var.checked = false;
			}
			else {
			$("#"+obj_var.value+"_amnt").val(watch_only_flag_val);
			$("#"+obj_var.value+"_amnt").attr("readonly", "readonly");
			}
		
		}
		else {
			
			if ( num_val < min_crypto_val_test ) {
			$("#"+obj_var.value+"_amnt").val("");
			}
			
		$("#"+obj_var.value+"_amnt").removeAttr("readonly");
		$("#"+obj_var.value+"_amnt").val( $("#"+obj_var.value+"_restore").val() );
		
		}
	
}


/////////////////////////////////////////////////////////////


function copy_text(elm_id, alert_id) {
	
  elm = document.getElementById(elm_id);

  // for Internet Explorer
  if(document.body.createTextRange) {
    range = document.body.createTextRange();
    range.moveToElementText(elm);
    range.select();
    document.execCommand("Copy");
    document.getElementById(alert_id).innerHTML = 'Copied to clipboard.';
  }
  // other browsers
  else if(window.getSelection) {
    selection = window.getSelection();
    range = document.createRange();
    range.selectNodeContents(elm);
    selection.removeAllRanges();
    selection.addRange(range);
    document.execCommand("Copy");
    document.getElementById(alert_id).innerHTML = 'Copied to clipboard.';
  }
  
}


/////////////////////////////////////////////////////////////


// https://codepen.io/kkoutoup/pen/zxmGLE
function random_tips() {
	
	if ( typeof quoteSource == 'undefined' ) {
	return;
	}
	
//getting a new random number to attach to a quote and setting a limit
randomNumber= Math.floor(Math.random() * quoteSource.length);
			
//set a new quote

newQuoteText = quoteSource[randomNumber].quote;
newQuoteGenius = quoteSource[randomNumber].name;
			
quoteContainer = $('#quoteContainer');
      
quoteContainer.html( ajax_placeholder(15, 'left') );

	//fade out animation with callback
   quoteContainer.fadeOut(250, function(){
	
   quoteContainer.html('<p>'+newQuoteText+'</p>'+'<p id="quoteGenius">'+'-'+newQuoteGenius+'</p>');
   
   //fadein animation.
   quoteContainer.fadeIn(250);
        
   });  


}


/////////////////////////////////////////////////////////////


// !!! WARNING !!!
// NEEDS overflow: auto; CSS ***UNLESS USING 'defaults'***, which DISABLES sticky elements in the container we are scrolling
// (so we MUST emulate sticky positioning with dynamic_position() in containers that use scrollLeft|Top)
function scroll_start(direction='defaults', elm=false) {  
       
       
     if ( elm != false ) {
     var scroll_width = $(elm).width();
     var scroll_height = $(elm).height();
     }


     setTimeout(function() {
     
     
        if ( direction == 'defaults' || typeof scroll_width == 'undefined' || typeof scroll_height == 'undefined' ) {
        scroll(0,0); // Defaults
        console.log('Scroll defaults used.');
        }
        else if ( direction == 'left' ) {
        $(elm).scrollLeft(0);
        }
        else if ( direction == 'right' ) {
        $(elm).scrollLeft(scroll_width);
        }
        else if ( direction == 'top' ) {
        $(elm).scrollTop(0);
        }
        else if ( direction == 'bottom' ) {
        $(elm).scrollTop(scroll_height);
        }
        
     
     }, 250);
     
}


/////////////////////////////////////////////////////////////


function monitor_iframe_heights() {


     iframe_height_adjuster = new IntersectionObserver(entries => {
         
         entries.forEach(entry => {
           
         const is_intersecting = entry.isIntersecting;
           
             // If this element has an ID attribute set, AND IS SHOWING IN BROWSER VIEWPORT
             if ( is_intersecting && typeof entry.target.id != 'undefined' ) {
                  
                  // On iframe load
                  document.getElementById(entry.target.id).addEventListener("load", function() {
                       
                       // Wait 1 second AFTER iframe load, then adjust height	
                       // (to let iframe top / height 'register' with browser)
     			   setTimeout(function(){
                       iframe_height_adjust(entry.target);
                       //console.log(entry.target.id + ' showing.');
     		        }, 1000);
     		        
                  });
				
             }
             
         });
     
     });
     
     
    $(".admin_iframe").each(function(){
    iframe_height_adjuster.observe(this);
    });
    

}


/////////////////////////////////////////////////////////////


function load_iframe(id, url=null) {

var $iframe = $('#' + id);
    
    
    // If the iframe exists in the current main page
    if ($iframe.length) {
         
         
         // Save the original frame src if not done yet
         if ( !orig_iframe_src[id] ) {
         orig_iframe_src[id] = $iframe.attr('src');
         }
         
         
         // If no URL set, AND current iframe src is not ALREADY the original src,
         // then load it...otherwise skip
         if ( url == null && $iframe.attr('src') != orig_iframe_src[id] ) {
         url = orig_iframe_src[id];
         }
         else if ( url == null ) {
         return;
         }
         
         
    $iframe.attr('src',url);
    
    // Reset any stored value (not needed after setting it)
    iframe_url(admin_iframe_url, null, 'delete');

    return false;

    }
    // Otherwise save the iframe data to use once the section loads
    // (unless it's a null value [used on index links etc])
    else if ( url != null ) {
    iframe_url(admin_iframe_url, url, 'set');
    }
    
    
return true;

}
   

/////////////////////////////////////////////////////////////


function light_chart_time_period(light_chart_days, mode) {
      
// Whole integer time periods only (otherwise UI shows "day[s]")
      
      if ( mode == 'short' ) {
   
         if ( light_chart_days == 'all' ) {
         time_period_text = light_chart_days.toUpperCase();
         }
         else if ( light_chart_days >= 365 && Number.isInteger(light_chart_days / 365) ) {
         time_period_text = (light_chart_days / 365) + 'Y';
         }
         else if ( light_chart_days >= 30 && Number.isInteger(light_chart_days / 30) ) {
         time_period_text = (light_chart_days / 30) + 'M';
         }
         else if ( light_chart_days >= 7 && Number.isInteger(light_chart_days / 7) ) {
         time_period_text = (light_chart_days / 7) + 'W';
         }
         else {
         time_period_text = light_chart_days + 'D';
         }
      
      }
      else if ( mode == 'long' ) {
   
         if ( light_chart_days == 'all' ) {
         time_period_text = light_chart_days.charAt(0).toUpperCase() + light_chart_days.slice(1);
         }
         else if ( light_chart_days >= 365 && Number.isInteger(light_chart_days / 365) ) {
         plural = ( (light_chart_days / 365) > 1 ? 's' : '' );
         time_period_text = (light_chart_days / 365) + ' Year' + plural;
         }
         else if ( light_chart_days >= 30 && Number.isInteger(light_chart_days / 30) ) {
         plural = ( (light_chart_days / 30) > 1 ? 's' : '' );
         time_period_text = (light_chart_days / 30) + ' Month' + plural;
         }
         else if ( light_chart_days >= 7 && Number.isInteger(light_chart_days / 7) ) {
         plural = ( (light_chart_days / 7) > 1 ? 's' : '' );
         time_period_text = (light_chart_days / 7) + ' Week' + plural;
         }
         else {
         plural = ( light_chart_days > 1 ? 's' : '' );
         time_period_text = light_chart_days + ' Day' + plural;
         }
      
      }
   
   
return time_period_text;
   
}


/////////////////////////////////////////////////////////////


function emulated_cron() {
    
cron_already_ran = false;

background_tasks_check(); 


      $.ajax({
            type: 'GET',
            url: 'cron.php?cron_emulate=1',
            async: true,
            contentType: "application/json",
            dataType: 'json',
            success: function(response) {
                
                if ( typeof response.result != 'undefined' ) {
                console.log( "cron emulation RESULT: " + response.result + ', at ' + human_time( new Date().getTime() ) );
                }
            
                // If flagged to display error in GUI
                if ( typeof response.display_error != 'undefined' ) {
                
                    if ( $('#alert_bell_area').html() == 'No new runtime alerts.' ) {
                    $('#alert_bell_area').html( response.result );
                    }
                    else {
                    $('#alert_bell_area').html( $('#alert_bell_area').html() + '<br />' + response.result );
                    }
                
                $(".toggle_alerts").attr("src","templates/interface/media/images/auto-preloaded/notification-" + theme_selected + "-fill.png");
                
                }
            
            
            cron_already_ran = true;
            
            background_tasks_check();  
            
            },
            error: function(e) {
            console.log( "cron emulation: ERROR at " + human_time( new Date().getTime() ) );
            cron_already_ran = true;
            background_tasks_check(); 
            }
        });
    
setTimeout(emulated_cron, 60000); // Re-check every minute (in milliseconds...cron.php will know if it's time)
    
}  


/////////////////////////////////////////////////////////////


function app_reload(form_submission, new_location) {

    
    // Wait if anything is running in the background
    // (emulated cron / charts / news feeds / etc)
    if ( background_tasks_status == 'wait' ) {
        
    $("#background_loading_span").html("Please wait, finishing background tasks...").css("color", "#ff4747", "important");
            
    reload_recheck = setTimeout(app_reload, 1500, form_submission);  // Re-check every 1.5 seconds (in milliseconds)
    
    return;
    
    }
    // ADD ANY LOGIC HERE, TO RUN BEFORE THE APP RELOADS
    else if ( background_tasks_status == 'done' ) {
    
    clearTimeout(reload_recheck);
    
        if ( form_submission == 0 || new_location != 0 || form_submit_queued == true ) {
             
        var loading_message = new_location != 0 ? 'Loading...' : 'Reloading...';
            
        $("#app_loading").show(250, 'linear'); // 0.25 seconds
        $("#app_loading_span").html(loading_message);
            
        // Transition effects
        $("#content_wrapper").hide(250, 'linear'); // 0.25 seconds
            
           // Close any open modal windows
     	 modal_windows.forEach(function(open_modal) {
           $(open_modal).modaal("close");
     	 });
        
        }
    
    
        // Reload, ONLY IF WE ARE NOT #ALREADY RELOADING# VIA SUBMITTING DATA (so we don't cancel data submission!),
        // OR IF WE ARE LOADING A #NEW# LOCATION
        if ( form_submission == 0 && typeof new_location != 'undefined' && new_location != 0 ) {
        window.location = new_location;
        }
        else if ( form_submission == 0 ) {
        window.location.reload(true); // Reload same location
        }
    
    
    }
    

}
	
	
/////////////////////////////////////////////////////////////


function render_names(name) {
	
render = name.charAt(0).toUpperCase() + name.slice(1);


	Object.keys(secondary_mrkt_currencies).forEach(function(currency) {
	re = new RegExp(currency,"gi");
     render = render.replace(re, currency.toUpperCase() );
	});
		
		
render = render.replace(/btc/gi, "BTC");
render = render.replace(/nft/gi, "NFT");
render = render.replace(/coin/gi, "Coin");
render = render.replace(/bitcoin/gi, "Bitcoin");
render = render.replace(/exchange/gi, "Exchange");
render = render.replace(/market/gi, "Market");
render = render.replace(/forex/gi, "Forex");
render = render.replace(/finex/gi, "Finex");
render = render.replace(/stamp/gi, "Stamp");
render = render.replace(/flyer/gi, "Flyer");
render = render.replace(/panda/gi, "Panda");
render = render.replace(/pay/gi, "Pay");
render = render.replace(/swap/gi, "Swap");
render = render.replace(/iearn/gi, "iEarn");
render = render.replace(/pulse/gi, "Pulse");
render = render.replace(/defi/gi, "DeFi");
render = render.replace(/ring/gi, "Ring");
render = render.replace(/amm/gi, "AMM");
render = render.replace(/ico/gi, "ICO");
render = render.replace(/erc20/gi, "ERC-20");
render = render.replace(/okex/gi, "OKex");
render = render.replace(/mart/gi, "Mart");
render = render.replace(/gateio/gi, "Gate.io");
render = render.replace(/dex/gi, "DEX");
render = render.replace(/coingecko/gi, "CoinGecko.com");
render = render.replace(/alphavantage/gi, "AlphaVantage");

return render;

}


/////////////////////////////////////////////////////////////


function background_tasks_check() {
        
        
        if (
		feeds_loading_check() == 'done' && charts_loading_check() == 'done' && cron_run_check() == 'done'
		) {
		    
		$("#background_loading").hide(250); // 0.25 seconds
		
    		// Run setting scroll position AGAIN if we are on the news page,
    		// as we start out with no scroll height before the news feeds load
    		if ( $(location).attr('hash') == '#news' ) {
    		get_scroll_position('news'); 
    		}
    		// Run setting scroll position AGAIN if we are on the charts page,
    		// as we start out with no scroll height before the charts load
    		else if ( $(location).attr('hash') == '#charts' ) {
    		get_scroll_position('charts'); 
    		}
    	
         	background_tasks_status = 'done';
         	
         	clearTimeout(background_tasks_recheck);
		
		}
		else {
		    
		background_tasks_recheck = setTimeout(background_tasks_check, 1000); // Re-check every 1 seconds (in milliseconds)
	
    	     background_tasks_status = 'wait';
    
		}
		
    	
//console.log('background_tasks_check: ' + background_tasks_status);

}


/////////////////////////////////////////////////////////////


window.pw_prompt = function(options) {
    
promptCount = 0;
    
    var lm = options.lm || "Password:",
        bm = options.bm || "Submit";
    if(!options.callback) { 
        alert("No callback function provided! Please provide one.") 
    };
                   
    var prompt = document.createElement("div");
    prompt.className = "pw_prompt";
    
    var submit = function() {
        options.callback(input.value);
        document.body.removeChild(prompt);
    };

    var close_modal = document.createElement("span");
    close_modal.innerHTML = "X";
    close_modal.title = "Close";
    close_modal.className = "close_prompt";
    
       close_modal.onclick = function() {
       prompt.remove();
       return;
       };
    
    prompt.appendChild(close_modal);

    var label = document.createElement("label");
    label.innerHTML = lm;
    label.for = "pw_prompt_input" + (++promptCount);
    prompt.appendChild(label);

    var input = document.createElement("input");
    input.id = "pw_prompt_input" + (promptCount);
    input.type = "password";
    input.addEventListener("keyup", function(e) {
        if (e.keyCode == 13) submit();
    }, false);
    prompt.appendChild(input);

    var button = document.createElement("button");
    button.textContent = bm;
    button.addEventListener("click", submit, false);
    prompt.appendChild(button);

    document.body.appendChild(prompt);
    
	setTimeout(function(){
    $(input).filter(':visible').focus();
	}, 1000);
    
};


/////////////////////////////////////////////////////////////


function sats_val(sat_increase) {

to_trade_amnt = Number(document.getElementById("to_trade_amnt").value);

sat_target = Number(document.getElementById("sat_target").value);


	if ( sat_increase == 'refresh' ) {
	num_total = (sat_target).toFixed(8);
	}
	else {
	sat_increase = Number(sat_increase);
	
	num_total = (sat_increase + sat_target).toFixed(8);
	
	document.getElementById("sat_target").value = num_total;
	}


target_prim_currency = ( num_total * btc_prim_currency_val );

target_total_prim_currency = ( (to_trade_amnt * num_total) * btc_prim_currency_val );


	document.getElementById("target_prim_currency").innerHTML = target_prim_currency.toLocaleString(undefined, {
    minimumFractionDigits: 8,
    maximumFractionDigits: 8
	});

document.getElementById("target_btc").innerHTML = num_total;

	document.getElementById("target_total_prim_currency").innerHTML = target_total_prim_currency.toLocaleString(undefined, {
    minimumFractionDigits: 2,
    maximumFractionDigits: 2
	});

document.getElementById("target_total_btc").innerHTML = (to_trade_amnt * num_total).toFixed(8);

}


/////////////////////////////////////////////////////////////


function string_check(doc_id_alert, elm_id, ui_name) { 

regex_is_lowercase_alphanumeric = /^[0-9a-z]+$/;
regex_starts_letter = /^[a-z]/;

var1 = document.getElementById(elm_id);
message = document.getElementById(doc_id_alert);
    
goodColor = "#10d602";
badColor = "#ff4747";


	if ( !var1 ) {
     message.style.color = badColor;
     message.innerHTML = "Enter " + ui_name + "."
     return false;
	}
	else if( !var1.value.match(regex_is_lowercase_alphanumeric) ) {
     var1.style.backgroundColor = badColor;
     message.style.color = badColor;
	message.innerHTML = ui_name + ' MUST contain ONLY LOWERCASE alphanumeric characters.';
	return false;
	}
	else if( !var1.value.match(regex_starts_letter) ) {
     var1.style.backgroundColor = badColor;
     message.style.color = badColor;
	message.innerHTML = ui_name + ' MUST START with a letter.';
	return false;
	}
	else if ( var1.value.length < 4 || var1.value.length > 30 ) {
     var1.style.backgroundColor = badColor;
     message.style.color = badColor;
     message.innerHTML = ui_name + ' MUST be between 4 and 30 characters long.';
     return false;
	}
	else {
     var1.style.backgroundColor = goodColor;
     message.style.color = goodColor;
     message.innerHTML = ui_name + " OK.";
	return true;
	}

}


/////////////////////////////////////////////////////////////


function dynamic_position(elm, mode=false, compact_sidebar=false) {

var docViewTop = $(window).scrollTop();
var docViewBottom = docViewTop + $(window).height();

var elmTop = $(elm).offset().top;
var elmBottom = elmTop + $(elm).height();

var is_showing = ( (elmBottom <= docViewBottom) && (elmTop >= docViewTop) );

     
     // IF compact sidebar, we tweak things differently
     if ( compact_sidebar == true ) {
     var elmTopParent = get_coords($(elm)[0].parentElement)['top'];
     var elmBottomParent = elmTopParent + $(elm).height();
     }


     // Emulate 'sticky' CSS mode
     if ( mode == 'emulate_sticky' ) {
     $(elm).css("top", Math.round(docViewTop) + "px", "important");
     }
     // If element isn't fully showing on page, try to make it show as fully as possible
     else if( !is_showing ) {
     
     console.log('A page element is not FULLY showing on the screen, attempting to auto-adjust now (as best as we can)...');
          
     
          // IF compact sidebar, we tweak things differently
          if ( compact_sidebar == true ) {
     
          var extra_top_up = $(elm).height() > $(window).height() ? 0 : 25;
          
          var top_val = Math.round(elmBottomParent - docViewBottom + extra_top_up);
          
          // HERE WE NEED TO COMPLETELY REWRITE ENTIRE STYLE FOR ELEMENT, TO FORCE CSS STYLE CHANGES
          $(elm).attr('style', 'top: -' + top_val + 'px !important; left: 55px !important;'); 
          
          console.log('Page element auto-adjusted to CSS "top" value of: -' + top_val);
          
          }
          // Everything else
          else {
               
          var top_val = Math.round(elmBottom - docViewBottom);

          $(elm).css("top", top_val + "px", "important");

          console.log('Page element auto-adjusted to CSS "top" value of: ' + top_val);

          }
          
     
     
     }

    
}


/////////////////////////////////////////////////////////////


function sorting_portfolio_table() {

	
    // https://mottie.github.io/tablesorter/docs/
	// Table data sorter config
	if ( document.getElementById("coins_table") ) {
	        
	// Set default sort, based on whether privacy mode is on        
	set_sort_list = get_cookie('priv_toggle') == 'on' ? [0,0] : [sorted_by_col, sorted_asc_desc];
		
		
    	$("#coins_table").tablesorter({
    			
    			sortList: [ set_sort_list ],
    			theme : theme_selected, // theme "jui" and "bootstrap" override the uitheme widget option in v2.7+
    			textExtraction: sort_extraction,
    			widgets: ['zebra'],
    		     headers: {
    				
            			// disable sorting (we can use column number or the header class name)
            			'.no-sort' : {
            			// disable it by setting the property sorter to false
            			sorter: false
            			},
            			// disable sorting (we can use column number or the header class name)
            			'.num-sort' : {
            			sorter: 'sortprices'
            			}
    			
    			}
    			
    	});
		
		
		// add parser through the tablesorter addParser method 
		$.tablesorter.addParser({
		     
			// set a unique id 
			id: 'sortprices', 
			
			// return false so this parser is not auto detected 
			is: function(s) { 
			return false; 
			}, 
			
			// format your data for normalization 
			format: function(s) { 
			return s.toLowerCase().replace(/\,/,'').replace(/ggggg/,'').replace(/\W+/,''); 
			}, 
			
			// set type, either numeric or text 
			type: 'numeric',
			
		}); 
	
	
	}


}


/////////////////////////////////////////////////////////////


function interface_font_percent(font_val, iframe_elm=false) {
     
var font_size = font_val * 0.01;
var font_size = font_size.toFixed(3);
     
var line_height = font_size * 1.35;
var line_height = line_height.toFixed(3);
     
var medium_font_size = font_size * 0.75;
var medium_font_size = medium_font_size.toFixed(3);
     
var medium_line_height = medium_font_size * 1.35;
var medium_line_height = medium_line_height.toFixed(3);
     
var small_font_size = font_size * 0.55;
var small_font_size = small_font_size.toFixed(3);
     
var small_line_height = small_font_size * 1.35;
var small_line_height = small_line_height.toFixed(3);


     if ( iframe_elm != false ) {
     var font_elements = $(font_size_css_selector, iframe_elm.contentWindow.document);
     var medium_font_elements = $(medium_font_size_css_selector, iframe_elm.contentWindow.document);
     var small_font_elements = $(small_font_size_css_selector, iframe_elm.contentWindow.document);
     }
     else {
     var font_elements = $(font_size_css_selector);
     var medium_font_elements = $(medium_font_size_css_selector);
     var small_font_elements = $(small_font_size_css_selector);
     }


// Standard (we skip sidebar HEADER area)
font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + font_size + "em !important;" });
////
font_elements.attr('style', function(i,s) { return (s || '') + "line-height : " + line_height + "em !important;" });


// Medium
medium_font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + medium_font_size + "em !important;" });
////
medium_font_elements.attr('style', function(i,s) { return (s || '') + "line-height : " + medium_line_height + "em !important;" });


// Small
small_font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + small_font_size + "em !important;" });
////
small_font_elements.attr('style', function(i,s) { return (s || '') + "line-height : " + small_line_height + "em !important;" });

        
     if ( iframe_elm == false && is_admin == true ) {
          
     iframe_font_val = font_val; // ALREADY A GLOBAL, DON'T USE 'var x'


          iframe_text_adjuster = new IntersectionObserver(entries => {
              
              entries.forEach(entry => {
              interface_font_percent(iframe_font_val, entry.target);
              });
          
          });
          
          
          $(".admin_iframe").each(function(){
          iframe_text_adjuster.observe(this);
          });
          
     
     // Reset iframe heights after 3.5 seconds (to give above loops time to finish)
     setTimeout(reset_iframe_heights, 3500);
     
     }
     // We don't want to re-set the cookie everytime an iframe is processed,
     // So just set when adjusting the main document
     else {
     set_cookie("font_size", font_size, 365);
     }
     

}


/////////////////////////////////////////////////////////////


function system_logs(elm_id) {

$('#' + elm_id + '_alert').text('Refreshing, please wait...');
        	
var log_area = $('#' + elm_id); // Needs to be set as a global var, for the initial page load
      
// Blank out existing logs that are showing
log_area.text('');
    
log_file = elm_id.replace(/_log/ig, '.log');
        	
log_lines = $('#' + elm_id + '_lines').val();

not_whole_num = (log_lines - Math.floor(log_lines)) !== 0;


	if ( not_whole_num ) {
	set_lines = 100;
	$('#' + elm_id + '_lines').val(set_lines);
	}
	else {
	set_lines = log_lines;
	}
    	  
    	  	
   // Get log data
	$.getJSON("ajax.php?token=" + Base64.decode(logs_csrf_sec_token) + "&type=log&logfile=" + log_file + '&lines=' + set_lines, function( data ) {
      
   	data_length = data.length;
   	
   	loop = 0;
		$.each( data, function(key, val) {
			
   		if ( $('#' + elm_id + '_space').is(":checked") ) {
      	log_area.append(val + "\n"); // For UX / readability, add an extra space between log lines
   		}
   		else {
      	log_area.append(val); // For raw log format
   		}
   		
      
      loop = loop + 1;
      
      	// Finished looping
    		if (loop == data_length) {
   		
   			     // Wait 4 seconds for it to fully load in the html element, then set scroll to bottom	
				setTimeout(function(){
				     
				log_area.scrollTop(log_area[0].scrollHeight);

                    load_highlightjs(elm_id);
				
				$('#' + elm_id + '_alert').text('');

				}, 4000);
	
    		}
    		
      });
              
	});
	
}


/////////////////////////////////////////////////////////////


function check_pass(doc_id_alert, doc_id_pass1, doc_id_pass2) {
	
pass1 = document.getElementById(doc_id_pass1);
pass2 = document.getElementById(doc_id_pass2);
message = document.getElementById(doc_id_alert);


regex_has_space = /\s/;
regex_has_number = /\d+/;
regex_has_lowercase = /[a-z]/;
regex_has_uppercase = /[A-Z]/;
regex_has_symb = /[!@#$%^&*]/;
    
    
goodColor = "#10d602";
badColor = "#ff4747";
    
    
    // Check length / compare values
   if ( !pass1 ) {
        message.style.color = badColor;
        message.innerHTML = "Enter your password."
        return false;
    }
   else if ( pass1.value.match(regex_has_space) ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password MUST NOT not contain any spaces."
        return false;
    }
	else if ( pass1.value.length < 12 || pass1 && pass1.value.length > 40 ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password must be between 12 and 40 characters long."
        return false;
	}
   else if ( !pass1.value.match(regex_has_number) ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password MUST contain at least 1 number."
        return false;
    }
   else if ( !pass1.value.match(regex_has_lowercase) ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password MUST contain at least 1 LOWERCASE letter."
        return false;
    }
   else if ( !pass1.value.match(regex_has_uppercase) ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password MUST contain at least 1 UPPERCASE letter."
        return false;
    }
   else if ( !pass1.value.match(regex_has_symb) ) {
        pass1.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Password MUST contain at least 1 symbol."
        return false;
    }
   else if ( !pass2 || pass1 && pass2 && pass1.value != pass2.value ) {
        pass1.style.backgroundColor = goodColor;
        pass2.style.backgroundColor = badColor;
        message.style.color = badColor;
        message.innerHTML = "Passwords do not match."
        return false;
    }
   else {
        pass1.style.backgroundColor = goodColor;
        pass2.style.backgroundColor = goodColor;
        message.style.color = goodColor;
        message.innerHTML = "Password OK."
        return true;
   }

}


/////////////////////////////////////////////////////////////


function row_alert(tr_id, alert_type, color, theme) {


    $( document ).ready(function() {
    	
      
			if ( color == 'yellow' ) {
			zebra_odd_loss = ( theme == 'light' ? '#d3bb5b' : '#6d5a29' );
			zebra_even_loss = ( theme == 'light' ? '#efd362' : '#564a1e' );
			}
			
			if ( color == 'green' ) {
			zebra_odd_gain = ( theme == 'light' ? '#7dc67d' : '#3d603d' );
			zebra_even_gain = ( theme == 'light' ? '#93ea93' : '#2d492d' );
			}
					
		 
			if ( color != 'no_cmc' ) {
			
				if ( color == 'yellow' && !alert_color_loss ) {
				alert_color_loss = zebra_odd_loss;
				}
				
				
				if ( color == 'green' && !alert_color_gain ) {
				alert_color_gain = zebra_odd_gain;
				}
					
				
				if ( color == 'yellow' ) {
				
				$('.tablesorter tr#' + tr_id).css("background", alert_color_loss);
				$('.tablesorter tr#' + tr_id + ' td').css("background", alert_color_loss);
				$('.tablesorter tr#' + tr_id).css("background-color", alert_color_loss);
				$('.tablesorter tr#' + tr_id + ' td').css("background-color", alert_color_loss);
				
				}
				
				
				if ( color == 'green' ) {
				
				$('.tablesorter tr#' + tr_id).css("background", alert_color_gain);
				$('.tablesorter tr#' + tr_id + ' td').css("background", alert_color_gain);
				$('.tablesorter tr#' + tr_id).css("background-color", alert_color_gain);
				$('.tablesorter tr#' + tr_id + ' td').css("background-color", alert_color_gain);
				
				}
					
					
				// Zebra stripes
				if ( color == 'yellow' ) {
				
					if ( alert_color_loss == zebra_odd_loss ) {
					alert_color_loss = zebra_even_loss;
					}
					else if ( alert_color_loss == zebra_even_loss ) {
					alert_color_loss = zebra_odd_loss;
					}
				
				}
				else if ( color == 'green' ) {
				
					if ( alert_color_gain == zebra_odd_gain ) {
					alert_color_gain = zebra_even_gain;
					}
					else if ( alert_color_gain == zebra_even_gain ) {
					alert_color_gain = zebra_odd_gain;
					}
					
				}
				
			
				// Audio, if chosen in settings
				if ( !audio_alert_played && alert_type == 'visual_audio' ) {
				audio_alert_played = true;
				play_audio_alert();
				}
			
			
			}

    
    });
    

}


/////////////////////////////////////////////////////////////


function auto_reload() {


	if ( reload_time ) {
	time = reload_time;
	}
	else if ( get_cookie("coin_reload") ) {
	time = get_cookie("coin_reload");
	}
	else {
	return;
	}
	

	if ( document.getElementById("set_use_cookies") ) {
		
		
		if ( reload_countdown ) {
		clearInterval(reload_countdown);
		}

		
		if ( time >= 1 ) {
			
			
			if ( document.getElementById("set_use_cookies").checked == false ) {
				
			use_cookies = confirm(' You must enable "Use cookies to save data" on the "Settings" page before using this auto-refresh feature. \n \n Click OK below to enable "Use cookies to save data" automatically NOW, or click CANCEL to NOT enable cookie data storage for this app.');
			
				if ( use_cookies == true ) {
					
				set_cookie("coin_reload", time, 365);
				
				$("#use_cookies").val(1);
				
				document.getElementById("reload_notice").innerHTML = "(reloading app, please wait...)";
				
					setTimeout(function () {
						$("#coin_amnts").submit();
					}, 2000);
				
				}
				else{
				$("#select_auto_refresh").val('');
				return false;
				}
			
			}
			else {
				

                // If subsections are still loading, wait until they are finished
                if ( $("#background_loading").is(":visible") || charts_loaded.length < charts_num || feeds_loaded.length < feeds_num ) {
                setTimeout(auto_reload, 1000); // Wait 1000 milliseconds then recheck
                return;
                }
                else {
                   
                set_cookie("coin_reload", time, 365);
               	
    			 int_time = time - 1; // Remove a second for the 1000 millisecond (1 second) recheck interval
    			
    			
                	reload_countdown = setInterval(function () {
                    
                    round_min = Math.floor(int_time / 60);
                    	
                    sec = ( int_time - (round_min * 60) );
                    	
                    	
                    	// "00:00" formatting...
                    	
                         if ( round_min < 10 ) {
                         round_min = '0' + round_min;
                         }
                    	
                    	
                         if ( sec < 10 ) {
                         sec = '0' + sec;
                         }
                          
                    
                    	if ( int_time >= 60 ) {
                              
                    
                   	     $("span.countdown_notice").html("<b>(auto-reload: " + round_min + ":" + sec + ")</b>"); // Main pages
                   	     $("span.countdown_notice_modal").html("<b>(auto-reload: " + round_min + ":" + sec + ")</b>"); // Modal pages
                      
                    	}
                    	else {
                    	$("span.countdown_notice").html("<b>(auto-reload: 00:" + sec + ")</b>"); // Main pages
                    	$("span.countdown_notice_modal").html("<b>(auto-reload: 00:" + sec + ")</b>"); // Modal pages
                    	}
            				
            				if ( int_time == 0 ) {
                 		     app_reloading_check();
            				}
                
                
                 	int_time-- || clearInterval(reload_countdown);  // Clear if 0 reached
                 
                 	}, 1000);
    	    
                   
                }
   
   
			}
		
		
		}
		else {
		set_cookie("coin_reload", '', 365);
		$("span.countdown_notice").html(""); // Main pages
		$("span.countdown_notice_modal").html(""); // Modal pages
		}
	
	
	}


}


/////////////////////////////////////////////////////////////


// For ALL nav menus (normal / compact sidebars, mobile top nav bar), we want to keep track of which
// nav item is active and it's associated content, and display it / mark nav links as active in interface
function nav_menu($chosen_menu) {


     	$($chosen_menu).each(function(){
     		
     	var $active, $content, $curr_content_id, $links = $(this).find('a');
     	
     	var $area_file = is_admin == true ? 'admin.php' : 'index.php';
     
     	// If the location.hash matches one of the links, use that as the active nav item.
     	// If no match is found, use the first link as the initial active nav item.
     	
     	$active = $($links.filter('[href="' + $area_file + window.location.hash + '"]')[0] || $links[0]);
     	  
     	    
     	    // Show INITIAL nav element (IF NO MATCHING CONTENT FOR URL HASH FOUND)
     	    if ( typeof $curr_content_id == 'undefined' ) {
     	    $active.addClass('active');
     	    }
     	
     
     	$content = $($active[0].hash);
     	
     	//console.log('hash = ' + $active[0].hash)
     
         
     	    // Hide all other content
     	    $links.not($active).each(function () {
              $(this).removeClass('active');
     	    $(this.hash).hide();
     	    });
     	
     	
     	    // Get CURRENT content ID (if the attribute exists)
     	    if ( typeof $content.attr('id') !== 'undefined' ) {
     	    $curr_content_id = '#' + $content.attr('id');
     	    }
              // Otherwise, CLEANLY force value as undefined
              else {
              $curr_content_id = (function () { return; })();
              console.log('could not find CURRENT content id!');
              }
     	    
     	
     	// Show INITIAL content element
     	$($curr_content_id).addClass('active');
     	$($curr_content_id).show();
              
              
              // Set the page's title to top of page
              if ( !is_login_form ) {
              dynamic_position('.page_title', 'emulate_sticky');
              }
     
     
     	    // Bind the click event handling for clicking different nav item's 'a' tags
     	    $(this).on('click', 'a', function(e){
       
     	    // Update the variables with the new link and content
     	    $active = $(this);
     	    $content = $(this.hash);
     
         
     	    // Hide all other content
              $($chosen_menu + ' a').removeClass('active');
              
              
          	    $links.not($active).each(function () {
                   $(this).removeClass('active');
          	    $(this.hash).hide();
          	    });
     	
     	
     	         // Get NEW content ID (if the attribute exists)
          	    if ( typeof $content.attr('id') !== 'undefined' ) {
          	    $curr_content_id = '#' + $content.attr('id');
          	    }
          	    // Otherwise, CLEANLY force values as undefined
          	    else {
          	    $curr_content_id = (function () { return; })();
          	    console.log('could not find NEW content id!');
          	    }
     
     	         
            	    // Make the NEW content element / active nav items active IN ALL NAV MENUS, and show it
                   $($chosen_menu + " a").each(function() {
               	         
               	     if ( $(this).attr('href') == $area_file + $curr_content_id ) {
                                  
                         $(this).addClass('active');
                             
                               // Saves current page (if BROWSER closed BUT TAB still open),
                               // ONLY IF NO START PAGE IS EXPLICITLY SET!
                               if ( get_url_param('start_page') == null ) {
                               
                               // MAIN submit form (also stores and updates chosen charts / news feeds / etc)
                               $("#coin_amnts").attr('action', $area_file + $curr_content_id);
                               
                               // Page URL
                               window.location = $area_file + $curr_content_id; 
                               
                               }
                                  
                         }
                             
                   });
     
                   
              // Show NEW content element
              $($curr_content_id).addClass('active');
              $($curr_content_id).show();
                   
     	    // Prevent the anchor's default click action
     	    e.preventDefault();
     	    
              // Scroll left, if we are wider than the page (for UX)
              scroll_start();
     	    
     	    // Do any textarea autoresizes, now that this content is showing
     	    // (since it may not have been showing on app load)
     	    autoresize_update(); 
              
              
                   // Set the page's title to top of page
                   if ( !is_login_form ) {
                   dynamic_position('.page_title', 'emulate_sticky');
                   }
     	    
     	    
     	         // Make sure admin iframe heights are adjusted
                   if ( is_admin == true ) {
                       admin_iframe_load.forEach(function(iframe) {
                       iframe_height_adjust(iframe);
                       });
                   }
                 
     	    
     	    });
     	    
     	
     	});
     	

}


/////////////////////////////////////////////////////////////


// https://stackoverflow.com/questions/14458819/simplest-way-to-obfuscate-and-deobfuscate-a-string-in-javascript
function privacy_mode(click=false) {
    
    
    // Failsafe (if no PIN cookie, delete toggle cookie)
    if ( get_cookie('priv_sec') == false ) {
    delete_cookie('priv_toggle');
    }
    
    
private_data = document.getElementsByClassName('private_data');


    if ( get_cookie('priv_toggle') == 'on' && click == true ) {
        
        
        if ( get_cookie('priv_sec') == null ) {
        delete_cookie('priv_toggle');
        }
        else {
        

            pw_prompt({
                lm:"Enter your PIN:", 
                callback: function(pin_check) {
                

                    if ( atob( get_cookie('priv_sec') ) == pin_check ) {
                        
                        
                    	// Put configged markets into a multi-dimensional array, calculate number of markets total
                    	Object.keys(private_data).forEach(function(element) {
                    	    
                         //console.log( private_data[element].nodeName );
                    		
                    		
                    		if ( private_data[element].nodeName == "INPUT" || private_data[element].nodeName == "TEXTAREA" ) {
                    		    
                              //console.log( private_data[element].nodeName + ': ' + private_data[element].value );
                            
                              decrypted = CryptoJS.AES.decrypt(private_data[element].value, pin_check);
            	    
            	               private_data[element].value = decrypted.toString(CryptoJS.enc.Utf8);
        
                    		}
                    		else if ( private_data[element].nodeName == "DIV" || private_data[element].nodeName == "SPAN" ) {
        
                              //console.log( private_data[element].nodeName + ': ' + private_data[element].innerHTML );
        
                              decrypted = CryptoJS.AES.decrypt(private_data[element].innerHTML, pin_check);
        
                    	     private_data[element].innerHTML = decrypted.toString(CryptoJS.enc.Utf8);
        
                    		}
                        
                        
                         // Show
                         private_data[element].classList.remove("obfuscated");
                    			
                    	});
                
                
                    //console.log('Privacy Mode: Off');
                    
                    set_cookie('priv_toggle', 'off', 365); 


                        // Any stats are added to document title
                        if ( typeof doc_title_stats !== 'undefined' ) {
                        document.title = doc_title_stats; 
                        }
            		
            		
                    safe_add_remove_class('green', 'pm_link', 'remove');
                    safe_add_remove_class('bitcoin', 'pm_link', 'add');
                    
                    safe_add_remove_class('green', 'pm_link2', 'remove');
                    safe_add_remove_class('bitcoin', 'pm_link2', 'add');
                    
                    safe_add_remove_class('green', 'pm_link3', 'remove');
                    safe_add_remove_class('bitcoin', 'pm_link3', 'add');
                    
                    
                        if ( document.getElementById("pm_link") ) {
                        document.getElementById("pm_link").setAttribute('title', 'Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.');
                        }
                    
                        if ( document.getElementById("pm_link2") ) {
                        document.getElementById("pm_link2").setAttribute('title', 'Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.');
                        }
                    
                        if ( document.getElementById("pm_link3") ) {
                        document.getElementById("pm_link3").setAttribute('title', 'Turn privacy mode ON. This encrypts / hides RENDERED personal portfolio data with the PIN you setup (BUT DOES #NOT# encrypt RAW source code). It ALSO disables opposite-clicking / data submission, and logs out any active admin login.');
                        }
                    
                    
                    safe_add_remove_class('disable_click', 'update_link_1', 'remove');
                    safe_add_remove_class('disable_click', 'update_link_2', 'remove');
                    
                    $('.update_portfolio_link').attr('title', 'Update your portfolio data.');
        
                    safe_add_remove_class('hidden', 'crypto_val', 'remove');
                    safe_add_remove_class('hidden', 'fiat_val', 'remove');
                    safe_add_remove_class('hidden', 'portfolio_gain_loss', 'remove');
                    safe_add_remove_class('hidden', 'balance_stats', 'remove');
                    
                    // https://mottie.github.io/tablesorter/docs/
                    $("#coins_table").find("th:eq(7)").removeClass("no-sort");
                    $("#coins_table").find("th:eq(9)").removeClass("no-sort");
                    $("#coins_table").find("th:eq(10)").removeClass("no-sort");
                    
                    $("#coins_table").find("th:eq(7)").addClass("num-sort");
                    $("#coins_table").find("th:eq(9)").addClass("num-sort");
                    $("#coins_table").find("th:eq(10)").addClass("num-sort");
                    
                    //$("#coins_table").find("th:eq(10)").data('sorter', 'sortprices');
                    
                    reset_tablesorter('off');
                    
                    var lvrg_info = document.querySelectorAll(".lvrg_info");
                        
                        lvrg_info.forEach(function(info, index){
                        info.style.visibility = "visible";
                        });
                        
                        
                    document.oncontextmenu = document.body.oncontextmenu = function() {return true;};
                    
                    autoresize_update();
        
                    $("#pm_link").text('Privacy Mode Is Off');
                    $("#pm_link_icon_div").css("background", "#dd7c0d");
                    $("#pm_link3").text('Privacy Mode Is Off');
                                
                    }
                    else {
                    alert("Wrong PIN, please try again.");
                    return;
                    }
                
                
                }
            });
        
        
        }
    
    
    }
    else {
        
        
        if ( get_cookie('priv_sec') == false && click == true ) {
    

            pw_prompt({
                
                lm:"Create PIN:<br /><span style='font-weight: bold;' class='bitcoin'>(requires / uses cookies)</span>", 
                callback: function(pin) {
                    
                    
                    // If page reload before entering pin (or left blank), or non-numeric, or less than 6 numbers
                    if ( typeof pin == 'undefined' || is_int(pin) == false || pin.length != 6 ) {
                    alert("PIN must be 6 numeric characters, please try again.");
                    return;
                    }
                    else {
                    

                        pw_prompt({
                            
                            lm:"Verify PIN:", 
                            callback: function(pin_check) {
                                
        
                                if ( pin == pin_check ) {
                                set_cookie('priv_sec', btoa(pin), 365);
                                privacy_mode(click);
                                }
                                else {
                                alert("PIN mis-match, please try again.");
                                return;
                                }
    
    
                            }
                            
                        });
                        
                    
                    }


                }
            });
            
            
        }
        
        
        // Check again, that 'priv_sec' cookie set
        if ( get_cookie('priv_sec') != false && click == true || get_cookie('priv_sec') != false && click == false && get_cookie('priv_toggle') == 'on' ) {
                    
        pin = atob( get_cookie('priv_sec') );
        
            
            // Put configged markets into a multi-dimensional array, calculate number of markets total
            Object.keys(private_data).forEach(function(element) {
            	    
            //console.log( private_data[element].nodeName );
            		
            		if ( private_data[element].nodeName == "INPUT" || private_data[element].nodeName == "TEXTAREA" ) {

                    //console.log( private_data[element].nodeName + ': ' + private_data[element].value );
            	    
            	    private_data[element].value = CryptoJS.AES.encrypt(private_data[element].value, pin);

            		}
            		else if ( private_data[element].nodeName == "DIV" || private_data[element].nodeName == "SPAN" ) {

                    //console.log( private_data[element].nodeName + ': ' + private_data[element].innerHTML );

            	    private_data[element].innerHTML = CryptoJS.AES.encrypt(private_data[element].innerHTML, pin);

            		}
            
            
            // Hide 
            private_data[element].classList.add("obfuscated");
            			
            });
        
        
        //console.log('Privacy Mode: On');
        
        set_cookie('priv_toggle', 'on', 365);
        
        document.title = ''; // Blank out document title
            		
        safe_add_remove_class('bitcoin', 'pm_link', 'remove');
        safe_add_remove_class('green', 'pm_link', 'add');
            		
        safe_add_remove_class('bitcoin', 'pm_link2', 'remove');
        safe_add_remove_class('green', 'pm_link2', 'add');
            		
        safe_add_remove_class('bitcoin', 'pm_link3', 'remove');
        safe_add_remove_class('green', 'pm_link3', 'add');
                    
                    
            if ( document.getElementById("pm_link") ) {
            document.getElementById("pm_link").setAttribute('title', 'Turn privacy mode OFF. This reveals your personal portfolio data, using the PIN you setup. It ALSO re-enables opposite-clicking / data submission, and allows admin logins.');
            }
                    
            if ( document.getElementById("pm_link2") ) {
            document.getElementById("pm_link2").setAttribute('title', 'Turn privacy mode OFF. This reveals your personal portfolio data, using the PIN you setup. It ALSO re-enables opposite-clicking / data submission, and allows admin logins.');
            }
                    
            if ( document.getElementById("pm_link3") ) {
            document.getElementById("pm_link3").setAttribute('title', 'Turn privacy mode OFF. This reveals your personal portfolio data, using the PIN you setup. It ALSO re-enables opposite-clicking / data submission, and allows admin logins.');
            }
                    
                    
        safe_add_remove_class('disable_click', 'update_link_1', 'add');
        safe_add_remove_class('disable_click', 'update_link_2', 'add');
                    
        $('.update_portfolio_link').attr('title', 'Disabled in privacy mode.');
        
        safe_add_remove_class('hidden', 'crypto_val', 'add');
        safe_add_remove_class('hidden', 'fiat_val', 'add');
        safe_add_remove_class('hidden', 'portfolio_gain_loss', 'add');
        safe_add_remove_class('hidden', 'balance_stats', 'add');
        
        // https://mottie.github.io/tablesorter/docs/
        $("#coins_table").find("th:eq(7)").addClass("no-sort");
        $("#coins_table").find("th:eq(9)").addClass("no-sort");
        $("#coins_table").find("th:eq(10)").addClass("no-sort");
                    
        $("#coins_table").find("th:eq(7)").removeClass("num-sort");
        $("#coins_table").find("th:eq(9)").removeClass("num-sort");
        $("#coins_table").find("th:eq(10)").removeClass("num-sort");
                    
        //$("#coins_table").find("th:eq(10)").data('sorter', false);
        
        reset_tablesorter('on');
                    
        var lvrg_info = document.querySelectorAll(".lvrg_info");
                        
             lvrg_info.forEach(function(info, index){
             info.style.visibility = "hidden";
             });
             
        document.oncontextmenu = document.body.oncontextmenu = function() {return false;};    
        
        $("#pm_link").text('Privacy Mode Is On');
        $("#pm_link_icon_div").css("background", "#10d602");
        $("#pm_link3").text('Privacy Mode Is On');
                                
        
        // Delete any existing admin auth (login) cookie
        // (we force admin logout when privacy mode is on)
        delete_cookie( 'admin_auth_' + Base64.decode(ct_id) ); 
             
             // If we are LOGGED IN the admin area , AND CURRENTLY IN THE ADMIN AREA, redirect to the user area
             // (as we just forced admin logout, because privacy mode is now enabled)
             if ( is_admin && Base64.decode(gen_csrf_sec_token) != 'none' ) {
             $("#app_loading").show(250, 'linear'); // 0.25 seconds
             $("#app_loading_span").html("Please wait, logging out for privacy mode...").css("color", "#ff4747", "important");
             window.location.href = "index.php";
             }
        
        }
        else {
        
            // Any stats are added to document title
            if ( typeof doc_title_stats !== 'undefined' ) {
            document.title = doc_title_stats; 
            }
        
        }
        
    
    }


}


/////////////////////////////////////////////////////////////





