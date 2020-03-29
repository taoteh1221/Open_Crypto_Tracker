
// Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com



/////////////////////////////////////////////////////////////


function ajax_placeholder(px_size, message='none'){

div_min_height = px_size + 10;

	if ( message != 'none' ) {
	font_size = px_size - 1;
	return '<div style="white-space: nowrap; min-height: ' + div_min_height + 'px;"><span class="align_center" style="min-width: ' + px_size + 'px;"><img src="templates/interface/media/images/loader.gif" height="' + px_size + '" alt="" style="position: relative; top: -5px;" /> <span style="font-size: ' + font_size + 'px;"> ' + message + '</span></div>';
	}
	else {
	return '<div style="min-height: ' + div_min_height + 'px;"><span class="align_center" style="min-width: ' + px_size + 'px;"><img src="templates/interface/media/images/loader.gif" height="' + px_size + '" alt="" /></span></div>';
	}
	

}


/////////////////////////////////////////////////////////////


function set_target_action(obj_id, set_target, set_action) {
	
document.getElementById(obj_id).target = set_target;
document.getElementById(obj_id).action = set_action;
	
}

	
/////////////////////////////////////////////////////////////

function app_reloading_placeholder() {

$("#body_loading_span").html("Reloading App...");

// Transition effects

$("#body_wrapper").hide(250, 'linear'); // 0.25 seconds

$("#body_loading").show(250, 'linear'); // 0.25 seconds

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


function validateForm(form_id, feild) {
	
  var x = document.forms[form_id][feild].value;
  if (x == "") {
    alert(feild + " must be populated.");
    return false;
  }
  else {
  $("#" + form_id).submit();
  }
  
}


/////////////////////////////////////////////////////////////


function charts_loading_check(charts_loaded) {
	
	//console.log('loaded charts = ' + window.charts_loaded.length + ', all charts = ' + window.charts_num);

	if ( window.charts_loaded.length >= window.charts_num ) {
	$("#loading_subsections").hide(250); // 0.25 seconds
	}
	else {
	$("#loading_subsections_span").html("Loading Charts...");
	$("#loading_subsections").show(250); // 0.25 seconds
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

/*

	USAGE

fake_sleep(500).then(() => {
  //do stuff
});

const doSomething = async () => {
  await fake_sleep(2000)
  //do stuff
};

doSomething();

*/

var fake_sleep = (milliseconds) => {
  return new Promise(resolve => setTimeout(resolve, milliseconds))
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
	var zebra_odd = ( theme == 'light' ? '#efd362' : '#705d29' );
	var zebra_even = ( theme == 'light' ? '#d3bb5b' : '#564a1e' );
	}
	else if ( color == 'green' ) {
	var zebra_odd = ( theme == 'light' ? '#7dc67d' : '#3d603d' );
	var zebra_even = ( theme == 'light' ? '#93ea93' : '#2d492d' );
	}
      
 if ( color != 'no_cmc' ) {
 	
	if ( !window.alert_color ) {
	window.alert_color = zebra_odd;
	}
      
      
      $('.tablesorter tr#' + tr_id).css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id).css("background-color", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background-color", window.alert_color);
      
      
	// Zebra stripes
	if ( window.alert_color == zebra_odd ) {
	window.alert_color = zebra_even;
	}
	else if ( window.alert_color == zebra_even ) {
	window.alert_color = zebra_odd;
	}
      
	
	// Audio, if chosen in settings
	if ( !window.is_alerted && alert_type == 'visual_audio' ) {
		
	audio_alert = document.getElementById('audio_alert');
	  
		if ( audio_alert.canPlayType('audio/mpeg') ) {
  		audio_alert.setAttribute('src','templates/interface/media/audio/Smoke-Alarm-SoundBible-1551222038.mp3');
		}
		else if ( audio_alert.canPlayType('audio/ogg') ) {
  		audio_alert.setAttribute('src','templates/interface/media/audio/Smoke-Alarm-SoundBible-1551222038.ogg');
		}
		
	  audio_alert.play();
	  
	window.is_alerted = 1;
	}
	
	
 }

    
    });
    

}


/////////////////////////////////////////////////////////////




