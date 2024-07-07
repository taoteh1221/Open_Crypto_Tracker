<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

     	     
$update_config_error_seperator = '<br /> ';
        
$light_chart_day_intervals = array_map( "trim", explode(',', $_POST['charts_alerts']['light_chart_day_intervals']) );
		
$light_chart_all_rebuild_min_max = array_map('trim', explode(',', $_POST['charts_alerts']['light_chart_all_rebuild_min_max']) );
		
$asset_performance_chart_defaults = array_map('trim', explode('||', $_POST['charts_alerts']['asset_performance_chart_defaults']) );
		
$asset_marketcap_chart_defaults = array_map('trim', explode('||', $_POST['charts_alerts']['asset_marketcap_chart_defaults']) );
        
$allowed_modes = array(
                        'chart',
                        'alert',
                        'both',
                        'none',
                       );
        
        
if ( isset($_POST['charts_alerts']['whale_alert_thresholds']) && trim($_POST['charts_alerts']['whale_alert_thresholds']) != '' ) {
     
$is_whale_alert = true;
        
$whale_alert_check = array_map( "trim", explode("||", $_POST['charts_alerts']['whale_alert_thresholds']) );
    
    if ( sizeof($whale_alert_check) == 4 ) {
    
         foreach ( $whale_alert_check as $val ) {
   
             if ( !is_numeric($val) ) {
             $is_whale_alert = false;
             }
   
         }
         
    }
    else {
    $is_whale_alert = false;
    }
  
}


foreach ( $_POST['charts_alerts']['tracked_markets'] as $key => $val ) {

// Auto-correct
$_POST['charts_alerts']['tracked_markets'][$key] = $ct['var']->auto_correct_str($val, 'lower');

$val = $ct['var']->auto_correct_str($val, 'lower');
 
$val_config = array_map( "trim", explode("||", $val) ); // Convert $val into an array
			
// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$chart_asset = ( stristr($val_config[0], "-") == false ? $val_config[0] : substr( $val_config[0], 0, mb_strpos($val_config[0], "-", 0, 'utf-8') ) );
$chart_asset = strtoupper($chart_asset);
 
$exchange = $val_config[1];

$pair = $val_config[2];

$mode = $val_config[3];
     
$mrkt_id = $ct['conf']['assets'][$chart_asset]['pair'][$pair][$exchange];
    
$mrkt_val = $ct['var']->num_to_str( $ct['api']->market($chart_asset, $exchange, $mrkt_id)['last_trade'] );
     	
     	     
      if ( sizeof($_POST['charts_alerts']['tracked_markets']) == 1 && trim($val) == '' ) {
      // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
      }
      elseif ( $ct['var']->begins_with_in_array($_POST['charts_alerts']['tracked_markets'], $val_config[0] . '||')['count'] > 1 ) {
      $ct['update_config_error'] .= $update_config_error_seperator . 'Charts / Alerts KEY was USED TWICE (DUPLICATE): "'.$val_config[0].'" (no duplicate keys allowed)';
      }
      elseif ( !isset($mrkt_val) || isset($mrkt_val) && !is_numeric($mrkt_val) || isset($mrkt_val) && $mrkt_val == 0.00000000000000000000 ) {
      $ct['update_config_error'] .= $update_config_error_seperator . 'No market data found for ' . $chart_asset . ' / ' . strtoupper($pair) . ' @ ' . $ct['gen']->key_to_name($exchange) . ' (in submission: "'.$val.'"); Market MAY be down *temporarily* for maintenance, OR permanently removed (please verify on the exchange website)';
      }
      elseif ( !in_array($mode, $allowed_modes) ) {
      $ct['update_config_error'] .= $update_config_error_seperator . 'Unknown mode (in submission: "'.$val.'")';
      }
     	
     	 
}
        
  
// Make whale alert params are set properly
if ( !$is_whale_alert ) {
$ct['update_config_error'] .= $update_config_error_seperator . 'Whale Alert Thresholds formatting is NOT valid';
}
  
  
// Make sure light chart day intervals is set
if ( isset($_POST['charts_alerts']['light_chart_day_intervals']) && trim($_POST['charts_alerts']['light_chart_day_intervals']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be filled in';
}
else {

     foreach ( $light_chart_day_intervals as $days ) {
     
         if ( $days == 0 || !$ct['var']->whole_int($days) ) {
         $ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart Day Intervals" MUST be whole numbers greater than zero ("'.$days.'" is invalid)';
         }
     
     }
     
}
  
  
// Make sure asset performance chart config is set
if ( isset($_POST['charts_alerts']['asset_performance_chart_defaults']) && trim($_POST['charts_alerts']['asset_performance_chart_defaults']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Performance Chart Defaults" MUST be filled in';
}
else if (
!isset($asset_performance_chart_defaults[0]) || !$ct['var']->whole_int($asset_performance_chart_defaults[0]) || $asset_performance_chart_defaults[0] < 400 || $asset_performance_chart_defaults[0] > 900 
|| !isset($asset_performance_chart_defaults[1]) || !$ct['var']->whole_int($asset_performance_chart_defaults[1]) || $asset_performance_chart_defaults[1] < 7 || $asset_performance_chart_defaults[1] > 16
|| !$ct['var']->whole_int($asset_performance_chart_defaults[0] / 100)
) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Performance Chart Defaults" FORMATTING incorrect (see corrisponding setting\'s NOTES section)';
}
  
  
// Make sure marketcap chart config is set
if ( isset($_POST['charts_alerts']['asset_marketcap_chart_defaults']) && trim($_POST['charts_alerts']['asset_marketcap_chart_defaults']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Marketcap Chart Defaults" MUST be filled in';
}
else if (
!isset($asset_marketcap_chart_defaults[0]) || !$ct['var']->whole_int($asset_marketcap_chart_defaults[0]) || $asset_marketcap_chart_defaults[0] < 400 || $asset_marketcap_chart_defaults[0] > 900 
|| !isset($asset_marketcap_chart_defaults[1]) || !$ct['var']->whole_int($asset_marketcap_chart_defaults[1]) || $asset_marketcap_chart_defaults[1] < 7 || $asset_marketcap_chart_defaults[1] > 16
|| !$ct['var']->whole_int($asset_marketcap_chart_defaults[0] / 100)
) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Marketcap Chart Defaults" FORMATTING incorrect (see corrisponding setting\'s NOTES section)';
}


// Make sure min / max 'all' light chart rebuild time is set properly
if ( isset($_POST['charts_alerts']['light_chart_all_rebuild_min_max']) && trim($_POST['charts_alerts']['light_chart_all_rebuild_min_max']) == '' ) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" MUST be filled in';
}
else if (
!isset($light_chart_all_rebuild_min_max[0]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[0]) || $light_chart_all_rebuild_min_max[0] < 3 || $light_chart_all_rebuild_min_max[0] > 12 
|| !isset($light_chart_all_rebuild_min_max[1]) || !$ct['var']->whole_int($light_chart_all_rebuild_min_max[1]) || $light_chart_all_rebuild_min_max[1] < 3 || $light_chart_all_rebuild_min_max[1] > 12
|| $light_chart_all_rebuild_min_max[0] > $light_chart_all_rebuild_min_max[1]
) {
$ct['update_config_error'] .= $update_config_error_seperator . '"Light Chart All Rebuild Min Max" values MUST be between 3 and 12 (LARGER number last)';
}
        
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>