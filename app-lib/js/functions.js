
// Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com


/////////////////////////////////////////////////////////////

function chart_toggle(obj_var) {
  
	var show_charts = $("#show_charts").val();
	
		if ( obj_var.checked == true ) {
		$("#show_charts").val("[" + obj_var.value + "]" + "," + show_charts);
		}
		else {
		$("#show_charts").val( show_charts.replace("[" + obj_var.value + "],", "") );
		}
		
		//console.log("show_charts = " + $("#show_charts").val() );  // DEBUGGING ONLY
	
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
		
	count_down(time, 1);
		
		if ( document.getElementById("set_use_cookies").checked == false ) {
		alert('Using this feature requires cookie data. To use auto-refresh effectively, enable "Use cookie data to save values between sessions" on the "Settings" page. Otherwise you likely will be prompted to manually re-submit form data every auto-refresh.');
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
	    	
	    	document.getElementById("reload_countdown").innerHTML = "(" + round_min + " minutes " + sec + " seconds)";
	    	
	    	}
	    	else {
	    	document.getElementById("reload_countdown").innerHTML = "(" + i + " seconds)";
	    	}

		
		
		i-- || clearInterval(int);  //if i is 0, then stop the interval
	    }, 1000);
	    
	  
	    
	}
	else {
	document.getElementById("reload_countdown").innerHTML = "";
	}
    
}

/////////////////////////////////////////////////////////////

function play_alert(tr_id, alert_type, color) {

    
    $( document ).ready(function() {
      
	if ( color == 'no_cmc' ) {
	window.alert_color = '#e5f1ff'; // Assets with CMC data not set or functioning properly
	}
	else if ( color == 'yellow' && !window.alert_color ) {
	window.alert_color = '#efd362';
	}
	else if ( color == 'green' && !window.alert_color ) {
	window.alert_color = '#7dc67d';
	}
      
      
      $('.tablesorter tr#' + tr_id).css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background", window.alert_color);
      $('.tablesorter tr#' + tr_id).css("background-color", window.alert_color);
      $('.tablesorter tr#' + tr_id + ' td').css("background-color", window.alert_color);
      
      // Zebra stripes
	
	if ( color == 'no_cmc' ) {
	window.alert_color = ''; // Assets with CMC data not set or functioning properly
	}
	else if ( window.alert_color == '#efd362' ) {
	window.alert_color = '#d3bb5b';
	}
	else if ( window.alert_color == '#d3bb5b' ) {
	window.alert_color = '#efd362';
	}
	else if ( window.alert_color == '#7dc67d' ) {
	window.alert_color = '#93ea93';
	}
	else if ( window.alert_color == '#93ea93' ) {
	window.alert_color = '#7dc67d';
	}
      
	
	
	if ( !window.is_alerted && alert_type == 'visual_audio' ) {
	  
	  document.getElementById('audio_alert').play();
	  
	window.is_alerted = 1;
	}


    
    });
    

}

/////////////////////////////////////////////////////////////