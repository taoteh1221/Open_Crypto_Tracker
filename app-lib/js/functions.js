
// Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com



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
  document.forms[form_id].submit();
  }
  
}

/////////////////////////////////////////////////////////////

function set_target_action(obj_id, set_target, set_action) {
	
document.getElementById(obj_id).target = set_target;
document.getElementById(obj_id).action = set_action;
	
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


var target_usd = ( num_total * btc_usd_value );

var target_total_usd = ( (to_trade_amount * num_total) * btc_usd_value );


document.getElementById("target_usd").innerHTML = target_usd.toLocaleString(undefined, {
  minimumFractionDigits: 8,
  maximumFractionDigits: 8
});

document.getElementById("target_btc").innerHTML = num_total;

document.getElementById("target_total_usd").innerHTML = target_total_usd.toLocaleString(undefined, {
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
			
		var use_cookies = confirm(' You must enable "Use cookie data to save values between sessions" on the "Settings" page before using this auto-refresh feature. \n \n Click OK below to enable "Use cookie data to save values between sessions" automatically NOW, or click CANCEL to NOT enable cookie data storage for this app.');
		
			if ( use_cookies == true ) {
				
			setCookie("coin_reload", time, 365);
			
			$("#use_cookies").val(1);
			
			document.getElementById("reload_countdown").innerHTML = "(reloading settings, please wait...)";
			
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
	    	
	    		if ( document.getElementById("reload_countdown") ) {
	    		document.getElementById("reload_countdown").innerHTML = "(" + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown2") ) {
	    		document.getElementById("reload_countdown2").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown3") ) {
	    		document.getElementById("reload_countdown3").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown4") ) {
	    		document.getElementById("reload_countdown4").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown5") ) {
	    		document.getElementById("reload_countdown5").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown6") ) {
	    		document.getElementById("reload_countdown6").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown7") ) {
	    		document.getElementById("reload_countdown7").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown8") ) {
	    		document.getElementById("reload_countdown8").innerHTML = "(page reload in " + round_min + " minutes " + sec + " seconds)";
	    		}
	    		
	    		
	    		
	    	}
	    	else {
	    		
	    		if ( document.getElementById("reload_countdown") ) {
	    		document.getElementById("reload_countdown").innerHTML = "(" + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown2") ) {
	    		document.getElementById("reload_countdown2").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown3") ) {
	    		document.getElementById("reload_countdown3").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown4") ) {
	    		document.getElementById("reload_countdown4").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown5") ) {
	    		document.getElementById("reload_countdown5").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown6") ) {
	    		document.getElementById("reload_countdown6").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown7") ) {
	    		document.getElementById("reload_countdown7").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		if ( document.getElementById("reload_countdown8") ) {
	    		document.getElementById("reload_countdown8").innerHTML = "(page reload in " + i + " seconds)";
	    		}
	    		
	    		
	    	
	    	}

		
		
		i-- || clearInterval(int);  //if i is 0, then stop the interval
	    }, 1000);
	    
	  
	    
	}
	else {
	document.getElementById("reload_countdown").innerHTML = "";
	document.getElementById("reload_countdown2").innerHTML = "";
	document.getElementById("reload_countdown3").innerHTML = "";
	document.getElementById("reload_countdown4").innerHTML = "";
	document.getElementById("reload_countdown5").innerHTML = "";
	document.getElementById("reload_countdown6").innerHTML = "";
	document.getElementById("reload_countdown7").innerHTML = "";
	document.getElementById("reload_countdown8").innerHTML = "";
	}
    
}

/////////////////////////////////////////////////////////////

function play_alert(tr_id, alert_type, color, theme) {


    
    $( document ).ready(function() {
      
	if ( color == 'yellow' ) {
	var zebra_odd = ( theme == 'light' ? '#efd362' : '#d4bb58' );
	var zebra_even = ( theme == 'light' ? '#d3bb5b' : '#b7a24d' );
	}
	else if ( color == 'green' ) {
	var zebra_odd = ( theme == 'light' ? '#7dc67d' : '#6ba76b' );
	var zebra_even = ( theme == 'light' ? '#93ea93' : '#5a8c5a' );
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
	  
	  document.getElementById('audio_alert').play();
	  
	window.is_alerted = 1;
	}
	
	
 }

    
    });
    

}

/////////////////////////////////////////////////////////////