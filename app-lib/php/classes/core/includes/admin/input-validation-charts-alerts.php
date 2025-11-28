<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

     	     
$update_config_error_seperator = '<br /> ';
		
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
$parsed_asset = ( stristr($val_config[0], "-") == false ? $val_config[0] : substr( $val_config[0], 0, mb_strpos($val_config[0], "-", 0, 'utf-8') ) );
$parsed_asset = strtoupper($parsed_asset);
 
$exchange = $val_config[1];

$pair = $val_config[2];

$mode = $val_config[3];
     
$mrkt_id = $ct['conf']['assets'][$parsed_asset]['pair'][$pair][$exchange];
    
$mrkt_val = $ct['var']->num_to_str( $ct['api']->market($parsed_asset, $exchange, $mrkt_id)['last_trade'] );
     	
     	     
      if ( sizeof($_POST['charts_alerts']['tracked_markets']) == 1 && trim($val) == '' ) {
      // Do nothing (it's just the BLANK admin interface placeholder, TO ASSURE THE ARRAY IS NEVER EXCLUDED from the CACHED config during updating via interface)
      }
      elseif ( $ct['var']->begins_with_in_array($_POST['charts_alerts']['tracked_markets'], $val_config[0] . '||')['count'] > 1 ) {
      $ct['update_config_error'] .= $update_config_error_seperator . 'Charts / Alerts KEY was USED TWICE (DUPLICATE): "'.$val_config[0].'" (no duplicate keys allowed)';
      }
      elseif ( !isset($mrkt_val) || isset($mrkt_val) && !is_numeric($mrkt_val) || isset($mrkt_val) && $mrkt_val == 0.00000000000000000000 ) {
           
      $ct['update_config_error'] .= $update_config_error_seperator . 'No market data found for ' . $parsed_asset . ' / ' . strtoupper($pair) . ' @ ' . $ct['gen']->key_to_name($exchange) . ' (in submission: "'.$val.'"); Market MAY be down *temporarily* for maintenance, OR permanently removed (please verify on the exchange website)';
      
           
           // UX for stocks
           if (
           $exchange == 'alphavantage_stock'
           && !preg_match("/stock/i", $parsed_asset)
           ) {
           $ct['update_config_error'] .= $update_config_error_seperator . 'You forgot to append "stock" to the end of the ticker (to flag this asset as a stock [NOT crypto], eg: tickerstock)';
           }
      
      
      }
      elseif ( !in_array($mode, $allowed_modes) ) {
      $ct['update_config_error'] .= $update_config_error_seperator . 'Unknown mode (in submission: "'.$val.'")';
      }
     	
     	 
}
        
  
// Make whale alert params are set properly
if ( !$is_whale_alert ) {
$ct['update_config_error'] .= $update_config_error_seperator . 'Whale Alert Thresholds formatting is NOT valid';
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
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Performance Chart Defaults" FORMATTING incorrect (see corresponding setting\'s NOTES section)';
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
$ct['update_config_error'] .= $update_config_error_seperator . '"Asset Marketcap Chart Defaults" FORMATTING incorrect (see corresponding setting\'s NOTES section)';
}
        
        
// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>