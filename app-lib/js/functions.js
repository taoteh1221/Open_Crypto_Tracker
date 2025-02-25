
// Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


/////////////////////////////////////////////////////////////


function balloon_css(text_align="left", z_index="32767", min_width="fit-content") {

return {
					fontSize: set_font_size + "em",
					minWidth: min_width,
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: z_index,
					textAlign: text_align,
					}

}


/////////////////////////////////////////////////////////////


// Javascript OBJECTS are different from javascript ARRAYS (lol),
// so we need a custom function to get the length
function getObjectLength (o) {
  var length = 0;

  for (var i in o) {
    if (Object.prototype.hasOwnProperty.call(o, i)){
      length++;
    }
  }
  return length;
}


/////////////////////////////////////////////////////////////


function same_name_checkboxes_to_radio() {


    $("input[type='checkbox']").each(function(index, value){
         
    var batched_by_name = $('input[name="'+this.name+'"]');
         
        if ( batched_by_name.length > 1 ) {

        var checkboxes = document.getElementsByName(this.name);
        
           for (i = 0; i < checkboxes.length; i++) {
             checkboxes[i].type = 'radio';
           }

        console.log('checkbox to radio for name = ' + this.name);
        
        }
    
       
    });
    

}


/////////////////////////////////////////////////////////////


function merge_objects(orig_object, overwriting_object) {

return combinedSettings = { ...orig_object, ...overwriting_object };

}


/////////////////////////////////////////////////////////////


function checkbox_subarrays_to_ajax(input_name_root) {

var results = {};

var name_override = {};


     $('input[name^="' + input_name_root + '"]').each(function() {
          
          
          if ( this.checked == true ) {
               
          results[this.name] = this.value;
               
          console.log(this.name + ' => ' + this.value);
          
          var selection_name = this.name;
     
          var dataset_id = this.getAttribute("dataset-id");
          
               
               // Get / include the corresponding hidden fields with 'name' / 'mcap_slug' attributes
               $('input[name^="' + input_name_root + '"]').each(function() {
                    
                    
                    if ( this.type == 'hidden' && this.getAttribute("dataset-id") == dataset_id ) {
                         
                         
                         if (
                         !name_override[this.name] && selection_name.search(/coingecko/i) >= 0 && this.name.match(/\[name\]/i) && this.value != ''
                         || !name_override[this.name] && selection_name.search(/coingecko/i) >= 0 && this.name.match(/\[mcap_slug\]/i) && this.value != ''
                         ) {
                         name_override[this.name] = this.value;
                         console.log('coingecko override (dataset_id = '+dataset_id+'): ' + this.name + ' => ' + this.value);
                         }
                         // We still need EMPTY values for correct CONFIG data structure (like 'mcap_slug'),
                         // BUT ONLY IF NOT ALREADY DEFINED IN THE 'name_override' ARRAY!
                         else if ( !name_override[this.name] ) {
                         results[this.name] = this.value;
                         console.log(this.name + ' => ' + this.value);
                         }
                         
                    
                    }
                    
     
               });


          }

     
     });


//console.log(results);

return merge_objects(results, name_override);

}

/////////////////////////////////////////////////////////////


function jstree_json_ajax(url_params, tree_id, csrf_sec_token=false) {
     
jstree_json_data = {}; // RESET GLOBAL VAR
     
$('#' + tree_id).show(250, 'linear'); // 0.25 seconds

     
     // IF secured with the general CSRF security token
     if ( csrf_sec_token ) {
     url_params = url_params + "&gen_nonce=" + Base64.decode(gen_csrf_sec_token);
     }
     
     
     if ( $('#' + tree_id).jstree(true) ) {
     $('#' + tree_id).jstree(true).settings.core.data.url = "ajax.php?" + url_params;
     $('#' + tree_id).jstree(true).refresh();
     }
     else {
     
     
          // https://www.jstree.com/api/
          $('#' + tree_id).on('redraw.jstree', function () {
          
          // Delete button
          $('.jstree_remove_selected').show(250, 'linear'); // 0.25 seconds
          
                   admin_iframe_dom.forEach(function(iframe) {
                   iframe_size_adjust(iframe);
                   });
                                             
          
          }).jstree({
          		
              'core' : {
                        
                        "check_callback" : true, // NEEDED TO DELETE SELECTED ITEMS!
                        "multiple" : true,
                        'data' : {
          			"url" : "ajax.php?" + url_params,
          			"dataType" : "json" // needed only if you do not supply JSON headers
          			
                       }
                       
              },
              "plugins" : [ "themes", "html_data", "checkbox", "sort", "ui" ]
              
          });
     
     
     }


}


/////////////////////////////////////////////////////////////


function jstree_remove(elm_id) {
			
			
     if ( $('#' + elm_id).jstree(true) ) {
     
     var ref = $('#' + elm_id).jstree(true),
     
     all_options = ref.get_json('#', { "flat" : true }),
    
     selected_options = ref.get_selected(),

     all_count = all_options.length,
    
     selected_count = selected_options.length;
          
          
          if ( !selected_options.length ) {
          alert('Please select a market to delete.');
          return false;
          }

          
          // We ALWAYS want to leave AT LEAST ONE ITEM 
          // (as we do 'delete entire asset' in a separate mode)
          if ( all_count > selected_count ) {
          ref.delete_node(selected_options);
          }
          else {
          alert('Please leave at least one market, WHEN USING MARKET DELETION MODE. If you which to delete the ENTIRE ASSET, you can go BACK to STEP 2, and choose: Remove ENTIRE ASSET');
          }
          
     
     // UPDATE GLOBAL VAR 
     // https://www.jstree.com/api/#/?f=get_json([obj,%20options])
     jstree_json_data = ref.get_json(
          
                                         '#',
                                         
                                            {  
                                             no_data : true,
                                             no_state : true,
                                             no_id : true,
                                             no_li_attr : true,
                                             no_a_attr : true
                                            }
                                         
                                   );
                                         
     }
     

}


/////////////////////////////////////////////////////////////


function ucfirst(string) {
    return string.charAt(0).toUpperCase() + string.slice(1);
}


/////////////////////////////////////////////////////////////


function force_2_digits(num) {
return ("0" + num).slice(-2);
}


/////////////////////////////////////////////////////////////


function storage_app_id(var_name) {
return Base64.decode(ct_id) + "_" + var_name;
}
	

/////////////////////////////////////////////////////////////


function delete_cookie(name) {
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
    $("#background_loading_span").html(message).css("color", "#F7931A", "important");
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


var sort_extraction = function(node) {

// Sort with the .app_sort_filter CSS class as the primary sorter
sort_target = $(node).find(".app_sort_filter").text();

// Remove any commas from number sorting
return sort_target.replace(/,/g, '');

}


/////////////////////////////////////////////////////////////


function responsive_menu_override() {

     if ( localStorage.getItem(sidebar_toggle_storage) == "closed" ) {
     $('link[title=responsive-menus]')[0].disabled=true;
     console.log('Overriding responsive menu CSS (user explicitly chose the COMPACT sidebar)...');
     }
     else {
     $('link[title=responsive-menus]')[0].disabled=false;
     }

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

red_save_button();

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
	fix_zingchart_watermarks();
	return 'done';
	}
	else {
	background_loading_notices("Loading Charts...");
	$("#background_loading").show(250); // 0.25 seconds
	return 'active';
	}

}


/////////////////////////////////////////////////////////////


// https://stackoverflow.com/questions/1462138/event-listener-for-when-element-becomes-visible
listen_for_visibility = function(element, callback) {
  var options = {
    root: document.documentElement
  }

  var observer = new IntersectionObserver((entries, observer) => {
    entries.forEach(entry => {
      callback(entry.intersectionRatio > 0);
    });
  }, options);

  observer.observe(element);
}


/////////////////////////////////////////////////////////////


function app_reload_notice(loading_message) {
        
// Transition effects
$("#app_loading").show(250, 'linear'); // 0.25 seconds
$("#app_loading_span").html(loading_message);
            
$("#content_wrapper").hide(250, 'linear'); // 0.25 seconds
            
      // Close any open modal windows
      modal_windows.forEach(function(open_modal) {
      $(open_modal).modaal("close");
      });

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


function select_confirm(id, message, alert_if_specific_unselected=false) {

     var $sel = $('#'+id).on('change', function(){
         
         // If OPTIONAL param NOT set, OR is set and it's same as the CURRENT value
         if ( !alert_if_specific_unselected || alert_if_specific_unselected == $sel.data('currVal') ) {
         var confirmed_change = confirm(message);
         }
          
         if (
         confirmed_change
         || alert_if_specific_unselected && alert_if_specific_unselected != $sel.data('currVal')
         ) {
             // store new value        
             $sel.trigger('update');
         } else {
              // reset
              $sel.val( $sel.data('currVal') );        
         }
         
     }).on('update', function(){
         $(this).data('currVal', $(this).val());
     }).trigger('update');

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


// https://usefulangle.com/post/81/javascript-change-url-parameters
function update_url_param(update_url, param, val) {

var url = new URL(update_url);
var search_params = url.searchParams;

// new value of "param" is set to "val"
search_params.set(param, val);

// change the search property of the main url
url.search = search_params.toString();

// the new url string
var new_url = url.toString();

return new_url;

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


function print_object(obj) {

let string = '';
 
    for (let prop in obj) {
         
        if (typeof obj[prop] == 'string') {
        string += prop + ': ' + obj[prop] + '; \n';
        }
        else {
        string += prop + ': { \n' + print(obj[prop]) + '\n}\n';
        }
        
    }
 
return string;

}


/////////////////////////////////////////////////////////////


function store_scroll_position() {
     
     if ( is_iframe ) {
     return;
     }
     
     
var hash_check = $(location).attr('hash');


     // STORE the current scroll position before the page reload (IF CONDITIONS MET, OTHERWISE RESET)
     // WE ONLY CALL THIS FUNCTION ONCE PER PAGE UNLOAD (body => onbeforeunload)
     if ( !is_admin && typeof hash_check != 'undefined' && hash_check != 'update'  && hash_check != 'settings' && hash_check != 'portfolio' && !isNaN( localStorage.getItem(scroll_position_storage) ) ) {
     localStorage.setItem(scroll_position_storage, window.scrollY);
     }
     else {
     localStorage.setItem(scroll_position_storage, 0);
     }

}


/////////////////////////////////////////////////////////////


function red_save_button(mode=false) {


     if ( is_admin && mode == 'iframe' ) {
     
     parent.unsaved_admin_config = true;
          
     $('#collapsed_sidebar .admin_settings_save img', window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-red.png");
          
     $('#sidebar .admin_settings_save', window.parent.document).removeClass('blue');
     $('#sidebar .admin_settings_save', window.parent.document).addClass('red_bright');
     
     $(".save_notice").show(250, 'linear'); // 0.25 seconds // SHOW SAVE NOTICE AT TOP / BOTTOM OF THIS ADMIN IFRAME PAGE
     
         admin_iframe_dom.forEach(function(iframe) {
         iframe_size_adjust(iframe);
         });                  
     
     }
     else if ( is_admin ) {
     
     unsaved_admin_config = true;
          
     $('#collapsed_sidebar .admin_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-red.png");
          
     $('#sidebar .admin_settings_save').removeClass('blue');
     $('#sidebar .admin_settings_save').addClass('red_bright');
     
     }
     else if ( !is_admin && !mode ) {
     
     unsaved_user_config = true;
          
     $('#collapsed_sidebar .user_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-red.png");
          
     $('#sidebar .user_settings_save').removeClass('blue');
     $('#sidebar .user_settings_save').addClass('red_bright');
     
     }
     

}


/////////////////////////////////////////////////////////////////////////////////////////////////////


function load_google_font() {
	
// Get HTML head element 
head = document.getElementsByTagName('HEAD')[0];  
  
// Create new link Element 
link = document.createElement('link'); 
  
// set the attributes for link element  
link.rel = 'stylesheet';  
      
link.type = 'text/css'; 
      
link.href = '//fonts.googleapis.com/css?family=' + font_name_url_formatting + '&display=swap';  

// Append link element to HTML head 
head.appendChild(link); 

// DEBUGGING
//console.log("Formatted for CSS link: " + font_name_url_formatting);
//console.log("Google font CSS href link set as: " + link.href);

}


/////////////////////////////////////////////////////////////


function set_scroll_position() {
     
     if ( is_iframe ) {
     return;
     }
     
     
var hash_check = $(location).attr('hash');


	// IF ther is a location hash, RETRIEVE any stored scroll position we were at before the page reload
	// (EXCEPT FOR ADMIN AREA, AND A FEW USER AREA PAGES WE *ALWAYS* WANT SCROLLED BACK UP TO THE TOP)
    if ( !is_admin && typeof hash_check != 'undefined' && hash_check != 'update'  && hash_check != 'settings' && hash_check != 'portfolio' && !isNaN( localStorage.getItem(scroll_position_storage) ) ) {
    	     
         	$('html, body').animate({
         	scrollTop: localStorage.getItem(scroll_position_storage)
         	}, 'slow');
    		
    }
    // Reset if we're NOT starting on a page with a location hash we want vertical scroll position saved for
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


function app_reloading_check(form_submission=0, new_location=false) {

        
    // Disable form updating in privacy mode
    if ( get_cookie('priv_toggle') == 'on' && form_submission == 1 ) {
    alert('Submitting data is not allowed in privacy mode.');
    return 'no'; // WE NORMALLY DON'T RETURN DATA HERE BECAUSE WE ARE REFRESHING OR SUBMITTING, SO WE CANNOT USE RETURN FALSE RELIABLY
    }
    // If this is an ADMIN submenu section, AND we are NOT in the admin area,
    // AND no iframe URL has been set yet, don't reload / load new page yet (we want to show the submenu options first)
    else if ( new_location && !is_admin && new_location.split('#')[1] == 'admin_plugins' && iframe_url(admin_iframe_url) == null ) {
    return;
    }
    else {
         
         if ( unsaved_user_config && !form_submit_queued || parent.unsaved_admin_config && !form_submit_queued ) {
         
         var confirm_skip_saving_changes = confirm("You have UN-SAVED setting changes. Are you sure you want to leave this section without saving your changes (using the RED SAVE BUTTON in the menu area)?");
                  
               if ( !confirm_skip_saving_changes ) {
               return false;         
               }
               else if ( unsaved_user_config ) {        
               
               unsaved_user_config = false;

               $('#collapsed_sidebar .user_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
               $('#sidebar .user_settings_save').addClass('blue');
               $('#sidebar .user_settings_save').removeClass('red_bright');
               
               app_reload(form_submission, new_location);
               
               }
               else if ( unsaved_admin_config ) {        
               
               parent.unsaved_admin_config = false;

               $('#collapsed_sidebar .admin_settings_save img', window.parent.document).attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
               $('#sidebar .admin_settings_save', window.parent.document).addClass('blue');
               $('#sidebar .admin_settings_save', window.parent.document).removeClass('red_bright');
               
               app_reload(form_submission, new_location);
               
               }
               
         }
         else {
         app_reload(form_submission, new_location);
         }

         
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

red_save_button();
	
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

red_save_button();
	
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

red_save_button();
	
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
	
	if ( is_admin && is_iframe ) {

           // Wait 1.5 seconds before Initiating
           // (otherwise ELEMENT SIZES / ETC aren't always registered yet for DOM manipulations)
           setTimeout(function(){
                                       
                        // Resize admin iframes after resizing textareas
                        admin_iframe_dom.forEach(function(iframe) {
                        iframe_size_adjust(iframe);
                        });
                                  
           }, 1500);
              
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


function iframe_size_adjust(elm) {

    var extra_width = 2;


    // Now that we've set any required zoom level, adjust the height
    if ( elm.id == 'iframe_security' ) {
    var extra_height = 1500;
    }
    else {
    var extra_height = 150;
    }

    
    // If defined
    if ( typeof elm.contentWindow.document.body != 'undefined' && elm.contentWindow.document.body != null ) {
    $(elm).css( 'min-height' , (elm.contentWindow.document.body.scrollHeight + extra_height) + "px" );
    //$(elm).css( 'min-width' , (elm.contentWindow.document.body.scrollWidth + extra_width) + "px" );
    $(elm).css( 'min-width' , "100%" );
    }
    

}


/////////////////////////////////////////////////////////////


function ajax_placeholder(px_size, align, message=null, display_mode=null){

// Scale properly...Run a multiplier, to slightly increase size
px_size = Math.round( (px_size * set_font_size) * 1.3 );
    
    
     if ( display_mode ) {
     display_mode = 'display: ' + display_mode + '; ';
     }


	if ( message ) {
	img_height = px_size - 2;
	return '<div class="align_' + align + '" style="'+display_mode+'white-space: nowrap; font-size: ' + px_size + 'px;"><img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="' + img_height + '" alt="" style="position: relative; vertical-align:middle;" /> ' + message + ' </div>';
	}
	else {
	img_height = px_size;
	return '<div class="align_' + align + '" style="'+display_mode+'"><img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="' + img_height + '" alt="" /></div>';
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


function paged_tablesort_sizechange() {


     // Dynamically adjust any iframe heights, for any SHOW PER PAGE CHANGES to GENERIC table sorting WITH PAGINATION
     $('div span.left.choose_pp a').on({
        "click":function(e){
             
        console.log('div span.left.choose_pp a CLICKED');
              
              if ( is_admin && is_iframe ) {            
             
              console.log('div span.left.choose_pp a CLICKED IN ADMIN AREA');

                   // Wait 1.5 seconds before Initiating
                   // (otherwise ELEMENT SIZES / ETC aren't always registered yet for DOM manipulations)
                   setTimeout(function(){
                                       
                        // Resize admin iframes after resizing textareas
                        admin_iframe_dom.forEach(function(iframe) {
                        iframe_size_adjust(iframe);
                        });
                                  
                   }, 1500);
              
              }
                                  
         }
     });


}


/////////////////////////////////////////////////////////////


function set_admin_security(obj) {


          if ( !is_iframe ) {
          return false;
          }

          
		if ( obj.value == "normal" || obj.value == "medium" ) {
	     var admin_sec_level_set = confirm("In 'Normal' and 'Medium' admin security modes, editing from the PHP config files will be DISABLED.\n\nAll app configuration editing will need to be done within this admin interface.");
		}
		else {
	     var admin_sec_level_set = confirm("High security admin mode requires you to update your app configuration from the PHP config files (config.php in app main directory / plug-conf.php for each plugin in the plugins subdirectory).\n\nWARNING: IF YOU SWITCH TO HIGH SECURITY MODE, ANY SETTING CHANGES YOU MADE IN A LOWER SECURITY MODE *WILL BE LOST*!");
		}

		
		if ( admin_sec_level_set ) {
		$("#toggle_admin_security").submit(); // Triggers iframe "reloading" sequence
		}
		else {
		$('input[name=opt_admin_sec]:checked').prop('checked',false);
		$('#opt_admin_sec_' + $("#sel_admin_sec").val() ).prop('checked',true);
		}


}


/////////////////////////////////////////////////////////////


function set_admin_2fa(obj=false, submit=false) {


		if ( obj && obj.value == "off" ) {
	     var admin_2fa_set = confirm("Turning off 2FA security IS *NOT* RECOMMENDED. Doing so will GREATLY REDUCE PROTECTION FROM HACKERS.\n\nIF YOU LOSE YOUR AUTHENTICATOR APP ACCOUNT, YOU MUST *MANUALLY* DELETE THE FILE \"cache/vars/admin_area_2fa.dat\" TO FORCE DISABLING 2FA.");
		}
		else if ( obj && obj.value == "on" ) {
	     var admin_2fa_set = confirm("2FA security requires the admin account to enter a SECONDARY \"time-based one-time password\" upon login, which is generated by Google Authenticator / Microsoft Authenticator / Authy / etc. This provides an additional layer of robust security to the admin area, further protecting against unauthorized access and changes.");
		}
		else if ( obj && obj.value == "strict" ) {
	     var admin_2fa_set = confirm("'Strict' 2FA mode not only requires entering your 2FA code upon login, it ALSO requires entering your 2FA code for changing ANYTHING in the admin area.");
		}
		
		
		// Revert if cancelled
		if ( obj && !admin_2fa_set ) {
		$('input[name=opt_admin_2fa]:checked').prop('checked',false);
		$("input[name=opt_admin_2fa][value=" + $("#sel_admin_2fa").val() + "]").prop('checked', true);
		}
		// If turning 2FA on, show next step IF IT'S CURRENTLY OFF
		else if ( obj && admin_2fa_set && obj.value != "off" && Base64.decode(admin_area_2fa) == 'off' ) {
		$("#sel_admin_2fa").val( $('input[name=opt_admin_2fa]:radio:checked').val() );
          $(".show_2fa_verification").show(250, 'linear'); // 0.25 seconds
		}
		// We don't need to show 2FA setup if we are disabling 2FA, OR switching between 'on' / 'strict'
		else if ( !obj && submit == true || obj && admin_2fa_set && obj.value == "off" || obj && admin_2fa_set && Base64.decode(admin_area_2fa) != 'off' ) {
		$("#toggle_admin_2fa").submit(); // Triggers iframe "reloading" sequence
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
			if ( num_val >= min_crypto_val_test || obj_var.value == 'miscassets' || obj_var.value == 'btcnfts' || obj_var.value == 'ethnfts' || obj_var.value == 'solnfts' || obj_var.value == 'altnfts' ) {
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
	
red_save_button();

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


function monitor_iframe_sizes() {


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
                       iframe_size_adjust(entry.target);
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


function resize_password_notes() {

     if ( is_iframe && is_admin && !is_login_form ) {
          
     // Get array of all password fields to target
     password_fields = document.querySelectorAll('.password-container');
     
          // Process all the password fields CSS styling
          // (max-width on any documentation notes bubble, so hide/show password icon positions properly)
          password_fields.forEach(function(pw_wrapper) {
          
          var pw_p = pw_wrapper.getElementsByTagName("p")[0];
          
          var pw_field = pw_p.getElementsByTagName("input")[0];
          
          var measure_pass = pw_p.getElementsByClassName('measure-password-field')[0];
          
               measure_pass.addEventListener("visibilitychange", () => {
                    
                 if (measure_pass.hidden) {
                 // logic here
                 } 
                 else {
                      
                   // Wait 1.5 seconds before Initiating the admin settings password fields notes sizing
                   // (otherwise widths aren't always registered yet for CSS style manipulations)
                   setTimeout(function(){
                   pw_p.style.maxWidth = measure_pass.offsetWidth + 'px';
                   }, 1500);
                   
                 }
                 
               });
    
          });
     
     }

}


/////////////////////////////////////////////////////////////


function load_iframe(id, url=null) {

     
     // Skip if there is an unsaved admin config
     // (we handle UX in init.js [for 3-deep nav])
     if ( is_admin && unsaved_admin_config ) {
     console.log('unsaved_admin_config: true');
     return;
     }
     else if ( !is_admin && unsaved_user_config ) {
     console.log('unsaved_user_config: true');
     return;
     }


var $iframe = $('#' + id);
    
    
    // If the admin iframe exists in the current main page
    if (is_admin && $iframe.length) {
     
    //console.log('admin iframe exists');
         
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
    console.log('admin iframe url set: ' + url);
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
            error: function(response) {
            console.log( "\n\ncron emulation: *AJAX* (*NOT* PHP) ERROR response at " + human_time( new Date().getTime() ) + " (see below)...\n" + print_object(response) + "\n\n" );
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
    
        if ( form_submission == 0 || new_location || form_submit_queued ) {
             
        console.log('is_iframe = ' + is_iframe);
             
        console.log('is_admin = ' + is_admin);
             
        console.log('form_submission = ' + form_submission);
             
        console.log('new_location = ' + new_location);
             
        console.log('form_submit_queued = ' + form_submit_queued);
             
        var loading_message = new_location ? 'Loading...' : 'Reloading...';
        
        
          // Allows auto-refreshing of any admin areas that require it
          if ( is_iframe && is_admin && form_submit_queued ) {
		parent.admin_settings_save_init = true;
		console.log('parent.admin_settings_save_init = ' + parent.admin_settings_save_init);
          }
          else if ( is_admin && form_submit_queued ) {
		admin_settings_save_init = true;
		console.log('admin_settings_save_init = ' + admin_settings_save_init);
          }

            
        app_reload_notice(loading_message);
        
        }
    
    
        // Reload, ONLY IF WE ARE NOT #ALREADY RELOADING# VIA SUBMITTING DATA (so we don't cancel data submission!),
        // OR IF WE ARE LOADING A #NEW# LOCATION
        if ( form_submission == 0 && new_location ) {
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
render = render.replace(/loopring/gi, "LoopRing");
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


function background_tasks_check() {
        
        
     if ( cron_run_check() == 'done' && feeds_loading_check() == 'done' && charts_loading_check() == 'done' ) {
          
     all_tasks_initial_load = false; // Unset initial bg tasks loading flag
		    
	$("#background_loading").hide(250); // 0.25 seconds
         	
     clearTimeout(background_tasks_recheck);
    	
     background_tasks_status = 'done';
		
         	// Run setting scroll position AGAIN if we are on the news / charts page,
         	// as we start out with no scroll height before the news feeds / price charts load
         	// SKIP IF THIS IS JUST THE EMULATED CRON CHECKING EVERY MINUTE (so we don't reset the scroll position every minute)
         	if ( !emulated_cron_task_only && $(location).attr('hash') == '#news' || !emulated_cron_task_only && $(location).attr('hash') == '#charts' ) {
         	set_scroll_position(); 
         	}
		
     }
	else {
	     
	     // If ONLY emulated cron background task is running AFTER initial page load, flag as such
	     // (so we DON'T reset the scroll position every minute)
	     if ( !all_tasks_initial_load && cron_run_check() != 'done' && feeds_loading_check() == 'done' && charts_loading_check() == 'done' ) {
	     emulated_cron_task_only = true;
	     }
		    
	background_tasks_recheck = setTimeout(background_tasks_check, 1000); // Re-check every 1 seconds (in milliseconds)
	
    	background_tasks_status = 'wait';
    
     }
		
    	
//console.log('background_tasks_check: ' + background_tasks_status);

}


/////////////////////////////////////////////////////////////


function custom_range_steps(elm, prev_val) {

// Remove any standard step attribute
elm.removeAttribute('step');

prev_val = Number(prev_val);

elm.value = Number(elm.value);

var datalist = document.getElementById( elm.getAttribute('list') ).options;

var return_array = new Object();

var step_array = [];


  for(i=0; i<datalist.length;i++){
  step_array.push( Number(datalist[i].value) );
  }


return_array['steps_total'] = step_array.length - 1; // Offset because of first position of zero


  if ( prev_val < elm.value ) {
  
       for (custom_steps = 0; custom_steps < step_array.length; custom_steps++) {
       
           if ( step_array[custom_steps - 1] == prev_val && step_array[custom_steps] != prev_val || step_array[custom_steps] >= elm.value ) {
           elm.value = Number(step_array[custom_steps]);
           return_array['prev_val'] = Number(step_array[custom_steps]);
           return_array['current_increment'] = custom_steps;
           break; // We found our target, so stop the loop
           }
         
       }
  
  }
  else if ( prev_val > elm.value ) {
  
       for (custom_steps = step_array.length; custom_steps >= 0; custom_steps--) {
       
           if ( step_array[custom_steps + 1] == prev_val && step_array[custom_steps] != prev_val || step_array[custom_steps] <= elm.value ) {
           elm.value = Number(step_array[custom_steps]);
           return_array['prev_val'] = Number(step_array[custom_steps]);
           return_array['current_increment'] = custom_steps;
           break; // We found our target, so stop the loop
           }
         
       }
  
  }
 
  
return return_array;

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
    prompt.style.setProperty("font-size", set_font_size + 'em', "important");
    prompt.style.width = Math.round(330 * set_font_size) + 'px';
    
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


function dynamic_position(elm, mode=false, compact_sidebar=false) {
     
     if ( typeof $(elm).offset() == 'undefined' ) {
     return;
     }

var docViewTop = $(window).scrollTop();
var docViewBottom = docViewTop + $(window).height();

var elmTop = $(elm).offset().top;
var elmBottom = elmTop + $(elm).height();

var is_showing = ( (elmBottom < docViewBottom) && (elmTop > docViewTop) );

     
     // IF compact sidebar, we tweak things differently
     if ( compact_sidebar == true ) {
     var elmTopParent = get_coords($(elm)[0].parentElement)['top'];
     var elmBottomParent = elmTopParent + $(elm).height();
     }


     // Emulate 'sticky' CSS mode, ONLY IF THE ELEMENT HEIGHT FITS IN THE VIEW PORT +100 px
     // (otherwise we allow scrolling the elements contents to be fully viewable)
     if ( mode == 'emulate_sticky' && ( $(elm).height() + 100 ) < $(window).height() ) {
     $(elm).css("top", Math.round(docViewTop) + "px", "important");
     }
     // If element isn't fully showing on page (and we are NOT emulating sticky), try to make it show as fully as possible
     else if( !is_showing && mode != 'emulate_sticky' ) {
     
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


// Zingchart watermark does NOT always show at hi / low text zoom levels, so we adjust it's positioning,
// (based off it's height) so it ALWAYS shows no matter what zoom level (when using the sidebar zoom feature)
function fix_zingchart_watermarks() {
     
    // Wait 5 seconds for elements to load / render, then update CSS
    setTimeout(function(){
				 
         $('div.chart_wrapper a[title="JavaScript Charts by ZingChart"]').parent().each(function(){
              
         // Element info
              
         var chart_height = $(this).parent().height();
              
         var watermark_link = $(this).children('a[title="JavaScript Charts by ZingChart"]');
              
         var watermark_text_height = watermark_link.css('font-size');
         
         watermark_text_height = Number( watermark_text_height.replace("px", "") );
         
         // Set displays / positions CSS first
               
         $(this).css('display', 'inline-block', "important"); 
               
         watermark_link.css('display', 'inline', "important");
               
         watermark_link.css('position', 'relative', "important");
         
         // Set heights next (WATERMARK LINK FIRST)
               
         watermark_link.css('line-height', watermark_text_height + 'px', "important");
               
         $(this).css('line-height', watermark_text_height + 'px', "important");
               
         $(this).css('height', watermark_text_height + 'px', "important"); 
         
         // Set top / bottom / right last

         var wrapper_top = Math.round(chart_height - watermark_text_height);
               
         $(this).css('top', wrapper_top + 'px', "important");
               
         watermark_link.css('bottom', '8px', "important");
               
         watermark_link.css('right', '6px', "important");
               
         });
    
    }, 5000);
     
}


/////////////////////////////////////////////////////////////


function sorting_portfolio_table() {

	
    // https://mottie.github.io/tablesorter/docs/
	// Table data sorter config
	if ( document.getElementById("coins_table") ) {
	     
	console.log('adding table sorting to PORTFOLIO table (with "coins_table" id)');
	        
	// Set default sort, based on whether privacy mode is on        
	var folio_sort_list = get_cookie('priv_toggle') == 'on' ? [0,0] : [sorted_by_col, sorted_asc_desc];
		
		
    	$("#coins_table").tablesorter({
    			
    			sortList: [ folio_sort_list ],
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


function sorting_generic_tables(paginated=false) {


    // https://mottie.github.io/tablesorter/docs/
	// Table data sorter config
	if ( document.getElementsByClassName('data_table') ) {
	     
	var all_tables = document.getElementsByClassName("data_table");


          Array.prototype.forEach.call(all_tables, function(table) {
	
     	// DEFAULT: sort 1st column, as ascending (0)
     	var dynamic_sort_list = [ [0,0] ];
               
               
               // Custom sorting
               if ( $(table).hasClass( "access_stats" ) ) {
               dynamic_sort_list = [ [0,1] ]; // Sort descending (1)
               }

               
               if ( typeof table.id == 'undefined' || table.id != 'coins_table' ) {
	     
	          console.log('adding table sorting to GENERIC table (with "data_table" class)');
               
               var pager_id = typeof table.id != 'undefined' ? table.id : false;
                   	
                   	if ( paginated ) {
                    paginated_tables( $(table), dynamic_sort_list, table.id );
                    }
                    else {
                    
                        	$(table).tablesorter({
                        	 
         			      sortList: dynamic_sort_list,
                          theme: theme_selected,
                          widgets: ['zebra', 'columns', 'filter']
                        
                        	});
                    
                    }
                    
                   	
               }

               
          });

	
	}


}


/////////////////////////////////////////////////////////////


https://mottie.github.io/tablesorter/beta-testing/example-pager-custom-controls.html
function paginated_tables(element, sort_list, pager_id=false) {

  // initialize custom pager script BEFORE initializing tablesorter/tablesorter pager
  // custom pager looks like this:
  // 1 | 2 … 5 | 6 | 7 … 99 | 100
  //   _       _   _        _     adjacentSpacer
  //       _           _          distanceSpacer
  // _____               ________ ends (2 default)
  //         _________            aroundCurrent (1 default)

  var $table = element,
    $pager = $('.table_pager_' + pager_id);

  $.tablesorter.customPagerControls({
    table          : $table,                   // point at correct table (string or jQuery object)
    pager          : $pager,                   // pager wrapper (string or jQuery object)
    pageSize       : '.left a',                // container for page sizes
    currentPage    : '.right a',               // container for page selectors
    ends           : 10,                        // number of pages to show of either end
    aroundCurrent  : 10,                        // number of pages surrounding the current page
    link           : '<a href="#">{page}</a>', // page element; use {page} to include the page number
    currentClass   : 'current',                // current page class name
    adjacentSpacer : '<span> | </span>',       // spacer for page numbers next to each other
    distanceSpacer : '<span> &#133; <span>',   // spacer for page numbers away from each other (ellipsis = &#133;)
    addKeyboard    : true,                     // use left,right,up,down,pageUp,pageDown,home, or end to change current page
    pageKeyStep    : 5                        // page step to use for pageUp and pageDown
  });

  // initialize tablesorter & pager
  $table.tablesorter({
      // Sort 1st / 2nd columns descending (1)
      sortList: sort_list,
      theme: theme_selected,
      widgets: ['zebra', 'columns', 'filter']
    })
    .tablesorterPager({
      // target the pager markup - see the HTML block below
      container: $pager,
      size: 5,
      output: '<span class="blue">Showing:</span> {startRow} through {endRow} (of {filteredRows} total data rows)'
    });

}


/////////////////////////////////////////////////////////////


function ct_ajax_load(url_params, elm_id, elm_desc, post_data=false, csrf_sec_token=false, sort_tables=false, loading_height=3) {
     
disable_nav_save_buttons = 'AJAX data is loading, please wait until it has finished.';
     
scroll(0,0); // Make sure we are scrolled to top of page


     // Any parent page too
     if ( is_iframe ) {
     window.parent.parent.scrollTo(0,0);
     }


$(elm_id).html("<div style='margin: " + loading_height + "em; line-height: " + (loading_height * 1.07) + "em;'> <strong style='font-size: " + loading_height + "em;' class='bitcoin'>Loading " + elm_desc + "...</strong> &nbsp; &nbsp; <img class='' src='templates/interface/media/images/auto-preloaded/loader.gif' alt='' style='height: " + loading_height + "em; vertical-align: top;' /> </div>");


     // IF we are NOT passing POST data, just populate with dummy data
     // (to avoid halting from possible logic errors)
     if ( !post_data ) {
     post_data = {'no_keys': 'no_values'};
     }
     
     
     // IF secured with the general CSRF security token
     if ( csrf_sec_token ) {
     url_params = url_params + "&gen_nonce=" + Base64.decode(gen_csrf_sec_token);
     }

     
     $(elm_id).show(250, 'linear',function(){ // show after 0.25 seconds
     
     
           $.ajax({
                
                  url: "ajax.php?" + url_params,
                  
                  method: 'POST', // We can ALWAYS use POST, as it covers even GET data
             
                  // POST data (associative) array 
                  data: post_data,
                  
                  // On successful load
                  success: function(data) {
                       
                  disable_nav_save_buttons = false; // Allow nav save buttons to work again
                       
                  $(elm_id).html(data); // Load response into the passed element id
          
     
                       // Resets / inits after 1.25 seconds (to give rendering time to finish)
                       setTimeout(function() {
                            
                       // Highlightjs
                       load_highlightjs();
     
                       scroll(0,0); // Make sure we are scrolled to top of page

                            // Any parent page too
                            if ( is_iframe ) {
                            window.parent.parent.scrollTo(0,0);
                            }
                  
                            if ( sort_tables ) {
                            sorting_generic_tables(true);
                            paged_tablesort_sizechange();
                            }
                            
                            if ( is_admin && is_iframe ) {
                            
                                 // Resize admin iframes after adding repeatable elements
                                 admin_iframe_dom.forEach(function(iframe) {
                                 iframe_size_adjust(iframe);
                                 });
                            
                            }
                             
                       }, 1250);
                  
                  },
                  
                  // On error loading
                  error: function(data) {
                       
                       if ( data.status == 504 && typeof post_data['add_markets_search'] !== 'undefined' && post_data['add_markets_search'].indexOf("/") == -1 ) {
                       var more_info = 'Try narrowing your search, by including a pairing, or lower the search result limit for Jupiter Aggregator in: APIs => External APIs => Jupiter Aggregator Search Results Maximum Per CPU Core, or just TRY AGAIN, NOW THAT THE ASSET SEARCH FEATURE HAS CACHED RECENT EXCHANGE DATA FOR 1 HOUR.';
                       }
                       else if ( data.status == 504 && typeof post_data['add_markets_search'] !== 'undefined' ) {
                       var more_info = 'Try lowering the search result limit for Jupiter Aggregator in: APIs => External APIs => Jupiter Aggregator Search Results Maximum Per CPU Core, or just TRY AGAIN, NOW THAT THE ASSET SEARCH FEATURE HAS RECENTLY CACHED EXCHANGE DATA.';
                       }
                       
                  $(elm_id).html("<div style='margin: " + loading_height + "em; line-height: " + (loading_height * 1.07) + "em;'> <strong style='font-size: " + loading_height + "em;' class='bitcoin'>ERROR loading data...</strong><br /> <strong style='font-size: " + loading_height + "em;' class='red'>" + data.status + ": " + data.statusText + "</strong><br /> <strong style='font-size: " + loading_height + "em;' class='bitcoin'>" + more_info + "</strong> </div>");       
                  
                  }
             
           });
     
     
     });
     

}


/////////////////////////////////////////////////////////////


function system_logs(elm_id) {

$('#' + elm_id + '_alert').text('Refreshing, please wait...');
        	
var log_area = $('#' + elm_id); // Needs to be set as a global var, for the initial page load
      
// Blank out existing logs that are showing
log_area.text('');
    
log_file = elm_id + '.log';
        	
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
	$.getJSON("ajax.php?logs_nonce=" + Base64.decode(logs_csrf_sec_token) + "&type=log&logfile=" + log_file + '&lines=' + set_lines, function( data ) {
      
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
				     
				     if ( typeof log_area[0] !== 'undefined' ) {
				     log_area.scrollTop(log_area[0].scrollHeight);
				     }

                         if ( typeof elm_id !== 'undefined' ) {
                         load_highlightjs(elm_id);
				     $('#' + elm_id + '_alert').text('');
                         }

				}, 4000);
	
    		}
    		
      });
              
	});
	
}


/////////////////////////////////////////////////////////////


function update_heading_tag_sizes(font_size) {
     
var heading_adjust = font_size / 100; // Convert back to em
          
var heading_adjust = heading_adjust.toFixed(3);
     
var tabdiv_side_padding = 2.35 * heading_adjust;
          
var tabdiv_side_padding = tabdiv_side_padding.toFixed(3);

     
     // If heading tag data array not populated yet
     if ( typeof heading_tag_sizes['h1'] == 'undefined' ) {
     
     // Get the default CSS values
     heading_tag_sizes['h1'] = $("h1").css("font-size");
     heading_tag_sizes['h2'] = $("h2").css("font-size");
     heading_tag_sizes['h3'] = $("h3").css("font-size");
     heading_tag_sizes['h4'] = $("h4").css("font-size");
     heading_tag_sizes['h5'] = $("h5").css("font-size");
     heading_tag_sizes['h6'] = $("h6").css("font-size");
     
          // Turn to numeric only, and note CSS unit type
          for (var key in heading_tag_sizes) {
               
               // If a value is set, process it
               if ( typeof heading_tag_sizes[key] != 'undefined' ) {
               
                    if ( heading_tag_sizes[key].search(/rem/i) != -1 ) {
                    heading_tag_sizes[key] = heading_tag_sizes[key].replace("rem", "");
                    heading_css_unit = 'rem';
                    }
                    else if ( heading_tag_sizes[key].search(/em/i) != -1 ) {
                    heading_tag_sizes[key] = heading_tag_sizes[key].replace("em", "");
                    heading_css_unit = 'em';
                    }
                    else if ( heading_tag_sizes[key].search(/vw/i) != -1 ) {
                    heading_tag_sizes[key] = heading_tag_sizes[key].replace("vw", "");
                    heading_css_unit = 'vw';
                    }
                    else if ( heading_tag_sizes[key].search(/vh/i) != -1 ) {
                    heading_tag_sizes[key] = heading_tag_sizes[key].replace("vh", "");
                    heading_css_unit = 'vh';
                    }
                    else if ( heading_tag_sizes[key].search(/px/i) != -1 ) {
                    heading_tag_sizes[key] = heading_tag_sizes[key].replace("px", "");
                    heading_css_unit = 'px';
                    }
                    
               }
               // If no value set, delete this array value
               else {
               delete heading_tag_sizes[key];
               }
               
          }
          
     }
     
     
     // Auto-adjusting header tags (h1 through h6 [if set in CSS, so it's included in the array])
     for (var key in heading_tag_sizes) {
          
          // PX units
          if ( heading_css_unit == 'px' ) {
          var adjust_to = Math.round(heading_tag_sizes[key] * heading_adjust);
          }
          // Everything else (rem / em / vw / vh)
          else {
          var adjust_to = heading_tag_sizes[key] * heading_adjust;
          var adjust_to = adjust_to.toFixed(3);
          }
          
     $(key).css('font-size', adjust_to + heading_css_unit, "important");

     //console.log(key + ', adjust_to = ' + adjust_to + heading_css_unit);

     }
     

// Auto-adjusting tabdiv side padding (to accomodate smaller / larger page title [within an h2 tag])
$('div.tabdiv').css('padding-left', tabdiv_side_padding + 'em', "important");
$('div.tabdiv').css('padding-right', tabdiv_side_padding + 'em', "important");

//console.log('tabdiv_side_padding = ' + tabdiv_side_padding);
//console.log('heading_adjust = ' + heading_adjust);

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
				
				$('#coins_table tr#' + tr_id).css("background", alert_color_loss);
				$('#coins_table tr#' + tr_id + ' td').css("background", alert_color_loss);
				$('#coins_table tr#' + tr_id).css("background-color", alert_color_loss);
				$('#coins_table tr#' + tr_id + ' td').css("background-color", alert_color_loss);
				
				}
				
				
				if ( color == 'green' ) {
				
				$('#coins_table tr#' + tr_id).css("background", alert_color_gain);
				$('#coins_table tr#' + tr_id + ' td').css("background", alert_color_gain);
				$('#coins_table tr#' + tr_id).css("background-color", alert_color_gain);
				$('#coins_table tr#' + tr_id + ' td').css("background-color", alert_color_gain);
				
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


function init_range_sliders() {

// Range input styling / processing
// Modified from Max Globa's code snippet in the article: https://css-tricks.com/value-bubbles-for-range-inputs/
// Thanks Max! :)
     
// Get array of all ranges to target
range_inputs = document.querySelectorAll('.range-wrap');
     
     
     // Process all the ranges
     range_inputs.forEach(function(range_wrap) {
          
     // Get elements inside range wrap
    
     var rangeField = range_wrap.getElementsByClassName('range-field')[0];
     
     var rangeTooltip = range_wrap.getElementsByClassName('range-tooltip')[0];
     
     var rangeMin = range_wrap.getElementsByClassName('range-min')[0];
     
     var rangeMax = range_wrap.getElementsByClassName('range-max')[0];
     
     var rangeValue = range_wrap.getElementsByClassName('range-value')[0];
     
     var rangePrefix = range_wrap.getElementsByClassName('range-ui-prefix')[0];
     
     var rangeSuffix = range_wrap.getElementsByClassName('range-ui-suffix')[0];
     
     var rangeUiMetaData = range_wrap.getElementsByClassName('range-ui-meta-data')[0];
     
     
         // Dynamic interface UX
         if ( (rangeUiMetaData.textContent).includes("zero_is_disabled") ) {
         var metaDataToUi = 'Disabled';
         }
         else if ( (rangeUiMetaData.textContent).includes("zero_is_unlimited") ) {
         var metaDataToUi = 'Unlimited';
         }
         else {
         var metaDataToUi = false;
         }
         
     
     var still_updating = false;
       
     rangeField.style.backgroundColor = 'lightseagreen';
     
     // UI value styling
     rangeValue.style.width = rangeField.offsetWidth + 'px';
     rangeValue.style.left = Math.round( rangeMin.offsetWidth + (5 * set_font_size) ) + 'px';
     
     // INITIAL: Setting of previous value var
     var prev_value = Number(rangeField.value);
     
     // INITIAL: If prefix content is a plus symbol, AND THE VALUE IS NEGATIVE, blank it out
     var rangePrefixContent = Number(rangeField.value) < 0 && rangePrefix.textContent == '+' ? '' : rangePrefix.textContent;
         
     // INITIAL: Pretty numbers, with prefix / suffix added
     var uiValue = rangePrefixContent + ( Number(rangeField.value) ).toLocaleString() + rangeSuffix.textContent;
     
     // INITIAL: Process some different meta data values (if they exist)
     uiValue = Number(rangeField.value) == 0 && metaDataToUi ? ucfirst(metaDataToUi) : uiValue;
     
     rangeValue.innerHTML = `${uiValue}`;
     
     
         // Styling / processing when setting the range value
         // (done when document is loaded / range input value changes)
         var setValue = ()=>{
              
              
              // So we don't do RE-ENTRY AND LOOP SLIGHTLY, just RESET the value and return
              // (for UX...the 'still_updating' flag is reset at end of this function on a timer)
              if ( still_updating ) {
              rangeField.value = prev_value; // RESET HERE KEEPS SLIDER AND TOOLTIP IN-SYNC POSITION-WISE 
              return;
              }
              
         
         // MUST be above conditional logic directly below
         var totalRange = ( Number(rangeField.max) - Number(rangeField.min) );
              
             
             // If flagged as using custom steps (not every step is the same value)
             if ( (rangeUiMetaData.textContent).includes("is_custom_steps") ) {
             
             // MUST BE ABOVE custom_range_steps()
             still_updating = true;
         
             var customRangingResult = custom_range_steps(rangeField, prev_value);
             
             // MUST BE BELOW custom_range_steps()
             var rangeIncrease = ( Number(rangeField.value) - Number(rangeField.min) );
             
             var totalSteps = customRangingResult['steps_total'];
             
             var currentIncrement = customRangingResult['current_increment'];
             
             prev_value = customRangingResult['prev_val'];
             
             }
             // If every step is the same value (regular HTML standards spec)
             else {
             var rangeIncrease = ( Number(rangeField.value) - Number(rangeField.min) );
             var totalSteps = totalRange / rangeField.step;
             var currentIncrement = rangeIncrease / rangeField.step;
             }
         
          
         // Percentage of total range, from LEFT SIDE of wrapper div
         var percentOf = (rangeIncrease / totalRange) * 100; 
         
         // Percentage of total range, AS A DECIMAL (where 1.00 == 100%)
         var percentOfAsDecimal = (percentOf / 100).toFixed(3); 
         
         var rawPosition = (rangeField.offsetWidth * percentOfAsDecimal);
         
         // Take into account the range min UI value showing on the LEFT of the range field,
         // and some extra margin / padding that may not be detected
         var refinedPosition = Math.round( rawPosition + rangeMin.offsetWidth - (10 * set_font_size) );
         
         rangeTooltip.style.left = refinedPosition + 'px';
         
         // If prefix content is a plus symbol, AND THE VALUE IS NEGATIVE, blank it out
         rangePrefixContent = Number(rangeField.value) < 0 && rangePrefix.textContent == '+' ? '' : rangePrefix.textContent;
         
         // Pretty numbers, with prefix / suffix added
         uiValue = rangePrefixContent + ( Number(rangeField.value) ).toLocaleString() + rangeSuffix.textContent;
     
         // Process some different meta data values (if they exist)
         uiValue = Number(rangeField.value) == 0 && metaDataToUi ? ucfirst(metaDataToUi) : uiValue;
              
         rangeTooltip.innerHTML = `<span>${uiValue}</span>`;
       
         rangeValue.innerHTML = `${uiValue}`;
         
         rangeFocus;
         
            	     
             // Wait 0.7 seconds before resetting as not recently updated
             // (so we don't endlessly loop)
		   setTimeout(function(){
		   still_updating = false;
		   }, 700);
				
         
         };
     
         
         // Styling / processing onblur (for UX)
         var rangeFocus = ()=>{
              
         rangeField.focus(); // For touchscreens to focus (to use arrow keys after)
       
         rangeField.style.backgroundColor = '#F7931A';
         
         rangeMin.classList.remove("light_sea_green");
         rangeValue.classList.remove("light_sea_green");
         rangeMax.classList.remove("light_sea_green");
         
         rangeMin.classList.add("bitcoin");
         rangeValue.classList.add("bitcoin");
         rangeMax.classList.add("bitcoin");
         
         };
     
         
         // Styling / processing onblur (for UX)
         var rangeBlur = ()=>{
              
         rangeField.blur(); // For touchscreens to blur
              
         rangeTooltip.innerHTML = ``; // Hide tooltip
       
         rangeField.style.backgroundColor = 'lightseagreen';
         
         rangeMin.classList.remove("bitcoin");
         rangeValue.classList.remove("bitcoin");
         rangeMax.classList.remove("bitcoin");
         
         rangeMin.classList.add("light_sea_green");
         rangeValue.classList.add("light_sea_green");
         rangeMax.classList.add("light_sea_green");
         
         };
         
      
      // Event listeners
      
         // When page becomes visible, center range value UI element below the slider
         listen_for_visibility(rangeField, visible => {
                
                if ( visible ) {
                var rangeField = range_wrap.getElementsByClassName('range-field')[0];
                var rangeMin = range_wrap.getElementsByClassName('range-min')[0];
                // UI value styling
                rangeValue.style.width = rangeField.offsetWidth + 'px';
                rangeValue.style.left = Math.round( rangeMin.offsetWidth + (5 * set_font_size) ) + 'px';
                }
                
         });
      
      document.addEventListener("DOMContentLoaded", setValue);
     
      rangeField.addEventListener('input', setValue);
     
      rangeField.addEventListener('focus', rangeFocus);
     
      rangeField.addEventListener('touchstart', rangeFocus);
     
      rangeField.addEventListener('touchmove', rangeFocus);
     
      rangeField.addEventListener('blur', rangeBlur);
      
      });


}


/////////////////////////////////////////////////////////////


function interface_font_percent(font_val, iframe_elm=false, specific_elm=false, specific_size=false) {
     
update_heading_tag_sizes(font_val);
     
fix_zingchart_watermarks();
     
var font_size = Number(font_val) * 0.01;
var font_size = font_size.toFixed(3);
     
var line_height = font_size * global_line_height_percent; 
var line_height = line_height.toFixed(3);
     
var medium_font_size = font_size * medium_font_size_css_percent;
var medium_font_size = medium_font_size.toFixed(3);
     
var medium_line_height = medium_font_size * global_line_height_percent; 
var medium_line_height = medium_line_height.toFixed(3);
     
var small_font_size = font_size * small_font_size_css_percent;
var small_font_size = small_font_size.toFixed(3);
     
var small_line_height = small_font_size * global_line_height_percent; 
var small_line_height = small_line_height.toFixed(3);
     
var tiny_font_size = font_size * tiny_font_size_css_percent;
var tiny_font_size = tiny_font_size.toFixed(3);
     
var tiny_line_height = tiny_font_size * global_line_height_percent; 
var tiny_line_height = tiny_line_height.toFixed(3);


     if ( specific_elm != false && specific_size != false ) {
          
     var specific_elements = $(specific_elm);
     
          if ( specific_size == 'reg' ) {
          var spec_font_size = font_size;
          var spec_line_height = line_height;
          }
          else if ( specific_size == 'med' ) {
          var spec_font_size = medium_font_size;
          var spec_line_height = medium_line_height;
          }
          else if ( specific_size == 'sm' ) {
          var spec_font_size = small_font_size;
          var spec_line_height = small_line_height;
          }
          else if ( specific_size == 'tny' ) {
          var spec_font_size = tiny_font_size;
          var spec_line_height = tiny_line_height;
          }
     
     }
     else if ( iframe_elm != false ) {
     var info_icon_elements = $(info_icon_size_css_selector, iframe_elm.contentWindow.document);
     var ajax_loading_elements = $(ajax_loading_size_css_selector, iframe_elm.contentWindow.document);
     var password_eye_elements = $(password_eye_size_css_selector, iframe_elm.contentWindow.document);
     var font_elements = $(font_size_css_selector, iframe_elm.contentWindow.document);
     var medium_font_elements = $(medium_font_size_css_selector, iframe_elm.contentWindow.document);
     var small_font_elements = $(small_font_size_css_selector, iframe_elm.contentWindow.document);
     var tiny_font_elements = $(tiny_font_size_css_selector, iframe_elm.contentWindow.document);
     }
     else {
     var info_icon_elements = $(info_icon_size_css_selector);
     var ajax_loading_elements = $(ajax_loading_size_css_selector);
     var password_eye_elements = $(password_eye_size_css_selector);
     var font_elements = $(font_size_css_selector);
     var medium_font_elements = $(medium_font_size_css_selector);
     var small_font_elements = $(small_font_size_css_selector);
     var tiny_font_elements = $(tiny_font_size_css_selector);
     }


     // Specific
     if ( specific_elm != false && specific_size != false ) {
     specific_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + spec_font_size + "em !important; line-height : " + spec_line_height + "em !important;" });
     //specific_elements.attr('style', function(i,s) { return (s || '') + specific_elements.attr('style') + "font-size: " + spec_font_size + "em !important; line-height : " + spec_line_height + "em !important;" });
     }
     // All
     else {
          
          // iframe info icon sizes are wonky for some reason in LINUX PHPDESKTOP (but works fine in modern browsers)
          if ( app_container == 'phpdesktop' && app_platform == 'linux' ) {
          var info_icon_size = font_size * 1.6;
          }
          else {
          var info_icon_size = font_size * 2.0;
          }
          
     var info_icon_size = info_icon_size.toFixed(3);
          
     
     // Run a multiplier, to slightly increase image size
     var ajax_loading_size = font_size * 1.3;
          
     var ajax_loading_size = ajax_loading_size.toFixed(3);
     
     // Run a multiplier, to adjust password eye placement
     var eye_top_right = line_height * 0.22;
          
     var eye_top_right = eye_top_right.toFixed(3);
          
          
     // Info icons
     info_icon_elements.attr('style', function(i,s) { return (s || '') + "height: " + info_icon_size + "em !important; width : auto !important;" });
          
     // ajax loading
     ajax_loading_elements.attr('style', function(i,s) { return (s || '') + "height: " + ajax_loading_size + "em !important; width : auto !important;" });
          
     // password eye
     password_eye_elements.attr('style', function(i,s) { return (s || '') + "position: absolute; cursor: pointer; color: black; top: " + eye_top_right + "em; right: " + eye_top_right + "em; transform: scale(var(--ggs," + font_size + "));" });
          
     // Standard font
     font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + font_size + "em !important; line-height : " + line_height + "em !important;" });
     
     // Medium font
     medium_font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + medium_font_size + "em !important; line-height : " + medium_line_height + "em !important;" });
     
     // Small font
     small_font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + small_font_size + "em !important; line-height : " + small_line_height + "em !important;" });
     
     // Tiny font
     tiny_font_elements.attr('style', function(i,s) { return (s || '') + "font-size: " + tiny_font_size + "em !important; line-height : " + tiny_line_height + "em !important;" });
     
     }

        
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
          setTimeout(function() {
               
              admin_iframe_dom.forEach(function(iframe) {
              iframe_size_adjust(iframe);
              });
              
          }, 3500);
          
     
     }
     
     
     // We don't want to re-set the set font size and it's cookie everytime an iframe is processed,
     // so just set when adjusting the main document
     if ( iframe_elm == false && specific_elm == false && specific_size == false ) {
          
     set_cookie("font_size", font_size, 365); // Update cookie val

     set_font_size = font_size; // Update the global var

     $(".balloon-tooltips").css({ "max-width": Math.round(800 * set_font_size) + 'px' }); // Adjust balloon tooltip max width

     }
     

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
     	
     	var $area_file = $chosen_menu == '.admin-nav' ? 'admin.php' : 'index.php';
     
     	// If the location.hash matches one of the links, use that as the active nav item.
     	// If no match is found, use the first link as the initial active nav item.
     	
     	$active = $($links.filter('[href="' + $area_file + window.location.hash + '"]')[0] || $links[0]);
     	  
     	    
     	    // Show INITIAL nav element (IF NO MATCHING CONTENT FOR URL HASH FOUND)
     	    if ( typeof $curr_content_id == 'undefined' ) {
     	    $active.addClass('active');
     	    }
     	
     
     	$content = $($active[0].hash);
     
         
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
              console.log('No content ID / URL hash match for: ' + $chosen_menu);
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
              
                  
                  // IF user CHANGED admin config settings data via interface,
                  // confirm whether or not they want to skip saving their changes
                  if ( is_admin && unsaved_admin_config ) {
                       
                  var confirm_skip_saving_changes = confirm("You have UN-SAVED setting changes. Are you sure you want to leave this section without saving your changes (using the RED SAVE BUTTON in the menu area)?");
                  
                      if ( !confirm_skip_saving_changes ) {
                      return false;                 
                      }
                      else {        
                      unsaved_admin_config = false;
                      $('#collapsed_sidebar .admin_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
                      $('#sidebar .admin_settings_save').addClass('blue');
                      $('#sidebar .admin_settings_save').removeClass('red_bright');
                      }

                  }
                  else if ( !is_admin && unsaved_user_config ) {
                       
                  var confirm_skip_saving_changes = confirm("You have UN-SAVED setting changes. Are you sure you want to leave this section without saving your changes (using the RED SAVE BUTTON in the menu area)?");
                  
                      if ( !confirm_skip_saving_changes ) {
                      return false;                 
                      }
                      else {        
                      unsaved_user_config = false;
                      $('#collapsed_sidebar .user_settings_save img').attr("src","templates/interface/media/images/auto-preloaded/icons8-save-100-" + theme_selected + ".png");
                      $('#sidebar .user_settings_save').addClass('blue');
                      $('#sidebar .user_settings_save').removeClass('red_bright');
                      }

                  }
                  
                  
              var click_href = $(this).attr('href');   
              
              var scan_href = click_href.split('/').pop();   
              
              
                  if (scan_href.indexOf("#") > 0) {
                  scan_href = scan_href.substring(0, scan_href.indexOf("#"));
                  }
                  
                  
                  // IF we are not ALREADY in the CORRISPONDING user / admin area
                  if ( scan_href != $area_file ) {
                  app_reloading_check(0, click_href);
                  }
                  else {
       
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
               	    // Otherwise, redirect to the clicked link (if no content id was found on the page)
               	    else {
               	    console.log('No content ID / URL hash match for: ' + $chosen_menu + ', redirecting to clicked link');
                        app_reloading_check( 0, $(this).attr('href') );
                        return;
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
                                    
                                    return;
                                    
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
          	         // (even if viewing again, AFTER initial load / view)
                        if ( is_admin == true ) {
                            admin_iframe_dom.forEach(function(iframe) {
                            iframe_size_adjust(iframe);
                            });
                        }
                        
                  
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
        
                    $("#pm_link").text('Privacy Mode: Off');
                    $("#pm_link_icon_div").css("background", "#F7931A");
                    $("#pm_link3").text('Privacy Mode: Off');
                                
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
         
    confirm_privacy_mode = false; // Interface only, set immediately below in interfacing
    
         
        if ( click == true ) {
        var confirm_privacy_mode = confirm("Click OK to continue enabling privacy mode.");
        }
    
        
        // Only continue if clicked and confirmed by user in the interface,
        // OR it's just running automatically during page load
        if ( click == true && !confirm_privacy_mode ) {
        return;
        }
        
        
        if ( get_cookie('priv_sec') == false && click == true ) {
    

            pw_prompt({
                
                lm:"Create 6-Digit PIN:<br /><span style='font-weight: bold;' class='bitcoin'>Requires using cookies</span><br /><span style='font-weight: bold;' class='bitcoin'>(encrypts RENDERING, *NOT* source)</span>", 
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
        
        
        // Check, now that 'priv_sec' cookie set
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
        
        $("#pm_link").text('Privacy Mode: On');
        $("#pm_link_icon_div").css("background", "#10d602");
        $("#pm_link3").text('Privacy Mode: On');
                                
        
        // Delete any existing admin auth (login) cookie
        // (we force admin logout when privacy mode is on)
        delete_cookie( 'admin_auth_' + Base64.decode(ct_id) ); 
             
             // If we are LOGGED IN the admin area , AND CURRENTLY IN THE ADMIN AREA, redirect to the user area
             // (as we just forced admin logout, because privacy mode is now enabled)
             if ( is_admin && Base64.decode(gen_csrf_sec_token) != 'none' ) {
             $("#app_loading").show(250, 'linear'); // 0.25 seconds
             $("#app_loading_span").html("Please wait, logging out for privacy mode...").css("color", "#ff4747", "important");
             $("#content_wrapper").hide(250, 'linear'); // 0.25 seconds
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





