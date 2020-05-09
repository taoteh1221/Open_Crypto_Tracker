
// Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


/////////////////////////////////////////////////////////////


function force_2_digits(num) {
return ("0" + num).slice(-2);
}


/////////////////////////////////////////////////////////////


function refreshImage(imgElement, imgURL) {   
     
// create a new timestamp, to force-refresh image
var timestamp = new Date().getTime();        
var el = document.getElementById(imgElement);        
var queryString = "?t=" + timestamp;           
el.src = imgURL + queryString;    
 
}    


/////////////////////////////////////////////////////////////


function update_alert_percent() {

	if ( document.getElementById("alert_percent").value == "yes" ) {
	document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_filter").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
	}
	else {
	document.getElementById("use_alert_percent").value = "";
	}

}


/////////////////////////////////////////////////////////////


function ajax_placeholder(px_size, message){

	if ( message ) {
	var img_height = px_size - 2;
	return '<div class="align_center" style="white-space: nowrap; font-size: ' + px_size + 'px;"><img src="templates/interface/media/images/loader.gif" height="' + img_height + '" alt="" style="position: relative; vertical-align:middle;" /> ' + message + ' </div>';
	}
	else {
	var img_height = px_size;
	return '<div class="align_center"><img src="templates/interface/media/images/loader.gif" height="' + img_height + '" alt="" /></div>';
	}
	

}


/////////////////////////////////////////////////////////////


function set_target_action(obj_id, set_target, set_action) {
	
document.getElementById(obj_id).target = set_target;
document.getElementById(obj_id).action = set_action;
	
}

	
/////////////////////////////////////////////////////////////

function app_reloading_placeholder() {

$("#app_loading_span").html("Reloading App...");

// Transition effects

$("#content_wrapper").hide(250, 'linear'); // 0.25 seconds

$("#app_loading").show(250, 'linear'); // 0.25 seconds

}
	

/////////////////////////////////////////////////////////////


function delete_cookie( name ) {
  document.cookie = name + '=; expires=Thu, 01 Jan 1970 00:00:01 GMT;';
}


/////////////////////////////////////////////////////////////


function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}


/////////////////////////////////////////////////////////////


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


/////////////////////////////////////////////////////////////


var sort_extraction = function(node) {

// Sort with the .app_sort_filter CSS class as the primary sorter
var sort_target = $(node).find(".app_sort_filter").text();

// Remove any commas from number sorting
return sort_target.replace(/,/g, '');

}


/////////////////////////////////////////////////////////////


function validateForm(form_id, field) {
	
  var x = document.forms[form_id][field].value;
  if (x == "") {
    alert(field + " must be populated.");
    return false;
  }
  else {
  $("#" + form_id).submit();
  }
  
}


/////////////////////////////////////////////////////////////


function charts_loading_check(charts_loaded) {
	
	//console.log('loaded charts = ' + window.charts_loaded.length + ', all charts = ' + window.charts_num);

	if ( charts_loaded.length >= window.charts_num ) {
	$("#loading_subsections").hide(250); // 0.25 seconds
	return 'done';
	}
	else {
	$("#loading_subsections_span").html("Loading Charts...");
	$("#loading_subsections").show(250); // 0.25 seconds
	return 'active';
	}

}


/////////////////////////////////////////////////////////////


function chart_toggle(obj_var) {
  
	var show_charts = $("#show_charts").val();
	
		if ( obj_var.checked == true ) {
		$("#show_charts").val("[" + obj_var.value + "]" + "," + show_charts);
		}
		else {
		$("#show_charts").val( show_charts.replace("[" + obj_var.value + "],", "") );
		}
	
}
	
	
/////////////////////////////////////////////////////////////


function render_names(name) {
	
render = name.charAt(0).toUpperCase() + name.slice(1);

render = render.replace(/btc/gi, "BTC");
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

return render;

}


/////////////////////////////////////////////////////////////


function selectAll(toggle, form_name) {
	
    var checkbox, i=0;
    while ( checkbox=document.getElementById(form_name).elements[i++] ) {
    	
        if ( checkbox.type == "checkbox" ) {
            
            if ( form_name == 'activate_charts' && checkbox.checked != toggle.checked ) {
        		checkbox.checked = toggle.checked;
            chart_toggle(checkbox);
            }
            
            else if ( form_name == 'coin_amounts' && checkbox.checked != toggle.checked ) {
        		checkbox.checked = toggle.checked;
            watch_toggle(checkbox);
            }
            
        }
        
    }
     
}


/////////////////////////////////////////////////////////////


function copy_text(elm_id, alert_id) {
    
var range = document.createRange();
range.selectNode(document.getElementById(elm_id));
window.getSelection().addRange(range);
document.execCommand("copy");
document.getElementById(alert_id).innerHTML = 'Text copied to clipboard.';
  
}


/////////////////////////////////////////////////////////////


function watch_toggle(obj_var) {
	
		if ( obj_var.checked == true ) {
			
			var num_value = $("#"+obj_var.value+"_amount").val();
			num_value = num_value.replace(/,/g, '');
			
			if ( num_value >= 0.00000001 ) {
			obj_var.checked = false;
			}
			else {
			$("#"+obj_var.value+"_amount").val("0.000000001");
			$("#"+obj_var.value+"_amount").attr("readonly", "readonly");
			}
		
		}
		else {
			
			if ( num_value < 0.00000001 ) {
			$("#"+obj_var.value+"_amount").val("");
			}
			
		$("#"+obj_var.value+"_amount").removeAttr("readonly");
		$("#"+obj_var.value+"_amount").val( $("#"+obj_var.value+"_restore").val() );
		}
	
}


/////////////////////////////////////////////////////////////


function is_msie() {

// MSIE 10 AND UNDER
var ua = window.navigator.userAgent;
var msie = ua.indexOf('MSIE');

	if (msie > 0) {
   return true;
   }

// MSIE 11
var ua = window.navigator.userAgent;
var trident = ua.indexOf('Trident');

	if (trident > 0) {
   return true;
   }

// If we get this far, return false
return false;
	
}


/////////////////////////////////////////////////////////////


function system_logs(elm_id) {

$('#' + elm_id + '_alert').text('Refreshing, please wait...');
        	
var log_area = $('#' + elm_id);
      
// Blank out existing logs that are showing
log_area.text('');
    
var log_file = elm_id.replace(/_log/ig, '.log');
        	
var log_lines = $('#' + elm_id + '_lines').val();


var not_whole_num = (log_lines - Math.floor(log_lines)) !== 0;

	if ( not_whole_num ) {
	set_lines = 100;
	$('#' + elm_id + '_lines').val(set_lines);
	}
	else {
	set_lines = log_lines;
	}
    	  	
   // Get log data
	$.getJSON("logs.php?logfile=" + log_file + '&lines=' + set_lines, function( data ) {
      
      
   	var data_length = data.length;
   	var loop = 0;
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
					
					// MSIE doesn't like highlightjs
					if ( is_msie() == false ) {
   				log_area.each(function(i, e) {hljs.highlightBlock(e)}); // Re-initialize highlighting text
					}
   			
				$('#' + elm_id + '_alert').text('');
				}, 4000);
	
    		}
    		
      });
              
	});     
    		
	
}


/////////////////////////////////////////////////////////////


function check_pass(doc_id_alert, doc_id_pass1, doc_id_pass2) {
	
var pass1 = document.getElementById(doc_id_pass1);
var pass2 = document.getElementById(doc_id_pass2);
var message = document.getElementById(doc_id_alert);


var regex_has_space = /\s/;
var regex_has_number = /\d+/;
var regex_has_lowercase = /[a-z]/;
var regex_has_uppercase = /[A-Z]/;
var regex_has_symbol = /[!@#$%^&*]/;
    
var goodColor = "#10d602";
var badColor = "#ff4747";
    
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
   else if ( !pass1.value.match(regex_has_symbol) ) {
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


function alphanumeric(doc_id_alert, elm_id, ui_name) { 

var regex_is_lowercase_alphanumeric = /^[0-9a-z]+$/;
var regex_starts_letter = /^[a-z]/;

var var1 = document.getElementById(elm_id);
var message = document.getElementById(doc_id_alert);
    
var goodColor = "#10d602";
var badColor = "#ff4747";

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


function satoshi_value(sat_increase) {

var to_trade_amount = Number(document.getElementById("to_trade_amount").value);

var sat_target = Number(document.getElementById("sat_target").value);

	if ( sat_increase == 'refresh' ) {
	var num_total = (sat_target).toFixed(8);
	}
	else {
	sat_increase = Number(sat_increase);
	
	var num_total = (sat_increase + sat_target).toFixed(8);
	
	document.getElementById("sat_target").value = num_total;
	}


var target_primary_currency = ( num_total * btc_primary_currency_value );

var target_total_primary_currency = ( (to_trade_amount * num_total) * btc_primary_currency_value );


document.getElementById("target_primary_currency").innerHTML = target_primary_currency.toLocaleString(undefined, {
  minimumFractionDigits: 8,
  maximumFractionDigits: 8
});

document.getElementById("target_btc").innerHTML = num_total;

document.getElementById("target_total_primary_currency").innerHTML = target_total_primary_currency.toLocaleString(undefined, {
  minimumFractionDigits: 2,
  maximumFractionDigits: 2
});

document.getElementById("target_total_btc").innerHTML = (to_trade_amount * num_total).toFixed(8);

}


/////////////////////////////////////////////////////////////


function auto_reload(time) {
	

	if ( document.getElementById("set_use_cookies") ) {
		
		if ( window.reload_function ) {
		clearInterval(window.reload_function);
		}
		
		if ( time >= 1 ) {
			
			
			if ( document.getElementById("set_use_cookies").checked == false ) {
				
			var use_cookies = confirm(' You must enable "Use cookies to save data between browser sessions" on the "Settings" page before using this auto-refresh feature. \n \n Click OK below to enable "Use cookies to save data between browser sessions" automatically NOW, or click CANCEL to NOT enable cookie data storage for this app.');
			
				if ( use_cookies == true ) {
					
				setCookie("coin_reload", time, 365);
				
				$("#use_cookies").val(1);
				
				document.getElementById("reload_countdown").innerHTML = "(reloading app, please wait...)";
				
					setTimeout(function () {
						$("#coin_amounts").submit();
					}, 2000);
				
				}
				else{
				$("#select_auto_refresh").val('');
				return false;
				}
			
			}
			else {
			setCookie("coin_reload", time, 365);
			count_down(time, 1);
			}
		
		
		window.reload_function = setInterval(function() {
				
						app_reloading_placeholder();
					location.reload(true);
					
					}, (time * 1000));
			
		
		
		}
		else {
		count_down(time, 0);
		setCookie("coin_reload", '', 365);
		}
	
	
	}


}


/////////////////////////////////////////////////////////////


function count_down(i, toggle) {
	
	if ( window.reload_countdown ) {
	clearInterval(window.reload_countdown);
	}

	if ( toggle == 1 ) {
		
			
	    window.reload_countdown = setInterval(function () {
	    	
				    	
	    	
	    	if ( i >= 60 ) {
	    	
	    	var round_min = Math.floor(i/60);
	    	
	    	var sec = ( i - (round_min*60) );
	    	
	    	$("#reload_countdown").html("(" + round_min + " minutes " + sec + " seconds)"); // Portfolio page
	    	$("span.countdown_notice").html("(app reload in " + round_min + " minutes " + sec + " seconds)"); // Secondary pages
	    		
	    		
	    	}
	    	else {
	    		
	    	$("#reload_countdown").html("(" + i + " seconds)"); // Portfolio page
	    	$("span.countdown_notice").html("(app reload in " + i + " seconds)"); // Secondary pages
	    		
	    	
	    	}

		
		
		i-- || clearInterval(int);  //if i is 0, then stop the interval
	    }, 1000);
	    
	  
	    
	}
	else {
	$("#reload_countdown").html(""); // Portfolio page
	$("span.countdown_notice").html(""); // Secondary pages
	}
    
}


/////////////////////////////////////////////////////////////


function row_alert(tr_id, alert_type, color, theme) {


    $( document ).ready(function() {
    	
      
			if ( color == 'yellow' ) {
			var zebra_odd_loss = ( theme == 'light' ? '#d3bb5b' : '#705d29' );
			var zebra_even_loss = ( theme == 'light' ? '#efd362' : '#564a1e' );
			}
			
			if ( color == 'green' ) {
			var zebra_odd_gain = ( theme == 'light' ? '#7dc67d' : '#3d603d' );
			var zebra_even_gain = ( theme == 'light' ? '#93ea93' : '#2d492d' );
			}
					
		 
			if ( color != 'no_cmc' ) {
			
				if ( !window.alert_color_loss ) {
				window.alert_color_loss = zebra_odd_loss;
				}
				
				
				if ( !window.alert_color_gain ) {
				window.alert_color_gain = zebra_odd_gain;
				}
					
				
				if ( color == 'yellow' ) {
				
				$('.tablesorter tr#' + tr_id).css("background", window.alert_color_loss);
				$('.tablesorter tr#' + tr_id + ' td').css("background", window.alert_color_loss);
				$('.tablesorter tr#' + tr_id).css("background-color", window.alert_color_loss);
				$('.tablesorter tr#' + tr_id + ' td').css("background-color", window.alert_color_loss);
				
				}
				
				
				if ( color == 'green' ) {
				
				$('.tablesorter tr#' + tr_id).css("background", window.alert_color_gain);
				$('.tablesorter tr#' + tr_id + ' td').css("background", window.alert_color_gain);
				$('.tablesorter tr#' + tr_id).css("background-color", window.alert_color_gain);
				$('.tablesorter tr#' + tr_id + ' td').css("background-color", window.alert_color_gain);
				
				}
					
					
				// Zebra stripes
				if ( window.alert_color_loss == zebra_odd_loss ) {
				window.alert_color_loss = zebra_even_loss;
				}
				else if ( window.alert_color_loss == zebra_even_loss ) {
				window.alert_color_loss = zebra_odd_loss;
				}
				
				
				if ( window.alert_color_gain == zebra_odd_gain ) {
				window.alert_color_gain = zebra_even_gain;
				}
				else if ( window.alert_color_gain == zebra_even_gain ) {
				window.alert_color_gain = zebra_odd_gain;
				}
					
			
				// Audio, if chosen in settings
				if ( !window.is_alerted && alert_type == 'visual_audio' ) {
				play_audio_alert();
				window.is_alerted = 1;
				}
			
			
			}

    
    });
    

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
				
	
	// If charts are still loading, wait until they are finished
   if ( charts_loading_check(window.charts_loaded) == 'active' ) {
   setTimeout(play_audio_alert, 700); //wait 700 millisecnds then recheck
   return;
   }
   else {
	audio_alert.autoplay = true;
	audio_alert.play();
   }
    				


}



