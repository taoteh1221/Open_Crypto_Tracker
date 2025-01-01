<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( isset($_GET['plugin']) ) {
$iframe_id = 'plugins'; // PLURAL HERE, AS THAT'S THE PARENT IFRAME ID SUFFIX FOR THE PLUGINS SUBSECTION
}
elseif ( isset($_GET['parent']) ) {
$iframe_id = $_GET['parent']; // PARENT HERE, AS THAT'S THE PARENT IFRAME ID SUFFIX FOR GENERIC SUBSECTIONS
}
// MUST BE AFTER PLUGIN / PARENT
elseif ( isset($_GET['section']) ) {
$iframe_id = $_GET['section'];
}
// MUST BE LAST, AS WE PREFER ALL ABOVE FIRST
elseif ( isset($_POST['interface_id']) ) {
$iframe_id = $_POST['interface_id'];
}


if ( isset($_GET['refresh']) ) {
$refresh_sections = $_GET['refresh']; 
}
elseif ( isset($_POST['refresh']) ) {
$refresh_sections = $_POST['refresh']; 
}

?>

console.log('admin iframe "<?=$iframe_id?>" loaded.'); // DEBUGGING
    
console.log('parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
  
//console.log('CURRENT URI: <?=$_SERVER['REQUEST_URI']?>');

var <?=$reload_function_name?>_timeout = 0;

    
function <?=$reload_function_name?>() {
    

    if ( is_admin && is_iframe ) {
    
    
    
    // Wait until admin_settings_save_init == true (in init.js), OR 10 SECONDS TIMEOUT
    if ( !parent.admin_settings_save_init && <?=$reload_function_name?>_timeout < 10 ) {
    <?=$reload_function_name?>_timeout = <?=$reload_function_name?>_timeout + 1;
    reload_recheck = setTimeout(<?=$reload_function_name?>, 1000);  // Re-check every 1 seconds (in milliseconds)
    return;
    }
    else if ( <?=$reload_function_name?>_timeout >= 10 ) {
    console.log('Timeout on refresh with <?=$reload_function_name?>()');
    }
	
    
    // RESET ONLY AFTER CONFIRMED AS RUNNING FROM BEING SET!
    parent.admin_settings_save_init = false; 
    console.log('RESET parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
   
    //console.log(parent.admin_interface_check); 
  
    
    // Add any corrupted config sections to blacklist
    for (var hashed_id in parent.admin_interface_check) {
    skip_corrupt_sections.push( 'iframe_' + parent.admin_interface_check[hashed_id]['interface_id'] );
    console.log('corrupt section = ' + 'iframe_' + parent.admin_interface_check[hashed_id]['interface_id'] );
    }
    
    
    <?php
    // If we need to refresh an admin iframe, to show the updated data
    if ( isset($refresh_sections) && trim($refresh_sections) != '' && $refresh_sections != 'none' && $refresh_sections != 'auto' ) {
    
    
   // Flag as config NOT updated if it was halted (so we skip refreshing any other admin sections)
   if ( !$ct['app_upgrade_check'] && !$ct['reset_config'] && !$ct['update_config'] ) {
   
  if ( $ct['check_2fa_error'] != null || $ct['update_config_error'] != null || $admin_general_error != null || $admin_reset_error != null ) {
  $halt_iframe_refreshing = true;
  ?>
  console.log('halt_iframe_refreshing = "<?=$halt_iframe_refreshing?>"');
  <?php
  }
   
   }
   
   
   // 'auto' is the 'refresh' param value we set further down here in footer.php,
   // so we never get stuck in endless loops with refresh=all when refreshing here
   if ( $halt_iframe_refreshing ) {
   ?>
   selected_admin_iframe_ids = new Array(); // SET TO BLANK (no iframe refreshing)
   <?php
   }
   // Refreshing ALL admin sections
   elseif ( $refresh_sections == 'all' ) {
   ?>
   selected_admin_iframe_ids = all_admin_iframe_ids; // ALL admin iframes refreshed
   <?php    
   }
   // Refreshing the passed list of admin sections
   else {
   
   $refresh_admin = explode(',', $refresh_sections);
   $refresh_admin = array_map("trim", $refresh_admin);
   
   foreach ( $refresh_admin as $refresh ) {
   ?>
   selected_admin_iframe_ids.push("<?=$refresh?>"); // SELECTED admin iframes refreshed
   <?php 
   }
   
   }
    
    ?>
    

   // DONT INCLUDE CURRENT PAGE (OR IT WILL *ENDLESS LOOP* RELOAD IT) 
   var excluded_iframe = selected_admin_iframe_ids.indexOf("iframe_<?=$iframe_id?>");
   if ( excluded_iframe > -1 ) {
   selected_admin_iframe_ids.splice(excluded_iframe, 1); // 2nd parameter means remove one item only
   console.log('SKIPPING auto-refresh of current page iframe: "iframe_<?=$iframe_id?>" (array index = ' + excluded_iframe + ')');
   }

   
   selected_admin_iframe_ids.forEach(function(refresh_iframe) {
   
  
  // Skip any corrupt interface config sections
  if ( skip_corrupt_sections.includes(refresh_iframe) ) {
  console.log('SKIPPING CORRUPT CONFIG SECTION IFRAME: ' + refresh_iframe + ' (in "<?=$iframe_id?>")');
  }
  // Skip any about:blank pages
  else if ( parent.document.getElementById(refresh_iframe).contentWindow.location.href == 'about:blank' ) {
  console.log('SKIPPING ABOUT:BLANK IFRAME: ' + refresh_iframe + ' (in "<?=$iframe_id?>")');
  }
  else {
  
  var refresh_url = update_url_param(parent.document.getElementById(refresh_iframe).contentWindow.location.href, 'refresh', 'auto');
  
  console.log('AUTO-REFRESHING (safely avoiding data submissions / runaway loops) CONFIG SECTION IFRAME: ' + refresh_iframe + ' ( ' + refresh_url + ' ) (in "<?=$iframe_id?>")');
  
  // Remove any POST data (so we don't get endless loops under certain conditions)
  parent.document.getElementById(refresh_iframe).contentWindow.location.replace(refresh_url);
  
  
  // Remove any POST data AGAIN, IN A DIFFERENT WAY (JUST TO BE SURE!)
  if ( parent.document.getElementById(refresh_iframe).contentWindow.history.replaceState ) {
  parent.document.getElementById(refresh_iframe).contentWindow.history.replaceState(null, null, refresh_url);
  }

  
  }
  
 
   });
  
    <?php
    }
    ?>
    
    }
    
}

    
    <?php
    // If we need to refresh an admin iframe, to show the updated data
    if ( isset($refresh_sections) && trim($refresh_sections) != '' && $refresh_sections != 'none' && $refresh_sections != 'auto' ) {
    ?>

    console.log('refreshing: <?=$refresh_sections?>');
    
    // Reload all flagged iframes after 3 seconds (to give any newly-revised ct_conf re-cache time to 'settle in')
    setTimeout(<?=$reload_function_name?>, 3000);
    
    <?php
    }
    elseif ( isset($refresh_sections) && $refresh_sections == 'none' ) {
    ?>
    
    console.log('refreshing: <?=$refresh_sections?>');
    
    // RESET, SINCE REFRESH IS SET TO 'none'
    parent.admin_settings_save_init = false; 
    console.log('RESET parent.admin_settings_save_init ("<?=$iframe_id?>") = ' + parent.admin_settings_save_init);
    
    <?php
    }
    ?>