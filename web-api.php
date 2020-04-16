<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


// Runtime mode
$runtime_mode = 'api';


// Load app config / etc
require("config.php");


// Set a max execution time, TO AVOID RUNAWAY PROCESSES FREEZING THE SERVER
if ( $app_config['debug_mode'] != 'off' ) {
ini_set('max_execution_time', 350);
}
else {
ini_set('max_execution_time', $app_config['api_max_execution_time']);
}


// API security check (key request var must match our stored API key, or we abort runtime)
// POST DATA #ONLY#, FOR HIGH SECURITY OF API KEY TRANSMISSION
//if ( $_POST['api_key'] != $api_key ) {
//echo "Incorrect API key. " . $_POST['api_key'];
//exit;
//}

$result = array();
$data_set_array = explode('/', $_GET['data_set']); // Data request array
$all_markets_data_array = explode(",", $data_set_array[1]); // Market data array


// Loop through each set of market data
foreach( $all_markets_data_array as $market_data ) {

$market_data_array = explode("-", $market_data); // Market data array


    if ( sizeof($market_data_array) == 3 ) {
    
    
    $primary_currency = strtolower($data_set_array[0]);
    
    $asset = strtoupper($market_data_array[1]);
    
    $selected_pairing = strtolower($market_data_array[2]);
    
    $exchange = strtolower($market_data_array[0]);
    
    $pairing_id = $app_config['portfolio_assets'][$asset]['market_pairing'][$selected_pairing][$exchange];
    
    $asset_market_data = asset_market_data($asset, $exchange, $pairing_id, $selected_pairing);
    
    
    // Bitcoin market data (auto-pick first array value)
    $btc_exchange = key($app_config['portfolio_assets']['BTC']['market_pairing'][$primary_currency]);
    $btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$primary_currency][$btc_exchange];
    $primary_currency_btc_value = asset_market_data('BTC', $btc_exchange, $btc_pairing_id)['last_trade'];
    
    
         // BTC PAIRINGS
        if ( $selected_pairing == 'btc' ) {
        $coin_value_raw = $asset_market_data['last_trade'];
        $btc_trade_eqiv = number_format($coin_value_raw, 8);
         $coin_primary_currency_worth_raw = $coin_value_raw * $primary_currency_btc_value;
        }
        // ETH ICOS
        elseif ( $selected_pairing == 'eth' && $selected_exchange == 'eth_subtokens_ico' ) {
        $pairing_btc_value = pairing_market_value($selected_pairing);
            if ( $pairing_btc_value == null ) {
            app_logging('other_error', 'pairing_market_value() returned null in web-api', 'pairing: ' . $selected_pairing);
            }
        $coin_value_raw = get_sub_token_price($selected_exchange, $market_pairing);
        $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
         $coin_primary_currency_worth_raw = ($coin_value_raw * $pairing_btc_value) * $primary_currency_btc_value;
        }
        // OTHER PAIRINGS
        else {
        $pairing_btc_value = pairing_market_value($selected_pairing);
            if ( $pairing_btc_value == null ) {
            app_logging('other_error', 'pairing_market_value() returned null in web-api', 'pairing: ' . $selected_pairing);
            }
        $coin_value_raw = $asset_market_data['last_trade'];
        $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
         $coin_primary_currency_worth_raw = ($coin_value_raw * $pairing_btc_value) * $primary_currency_btc_value;
         }
    
    
    
	 // If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
	 $volume_pairing_raw = ( number_to_string($asset_market_data['24hr_pairing_volume']) > 0 ? $asset_market_data['24hr_pairing_volume'] : ($coin_value_raw * $asset_market_data['24hr_asset_volume']) );
    
    
    // Pretty numbers
    $coin_primary_currency_worth_raw = ( number_to_string($coin_primary_currency_worth_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? round($coin_primary_currency_worth_raw, 2) : round($coin_primary_currency_worth_raw, $app_config['primary_currency_decimals_max']) );
    
    $coin_value_raw = number_to_string($coin_value_raw);
    
    if ( array_key_exists($selected_pairing, $app_config['bitcoin_currency_markets']) ) {
    $coin_value_raw = ( number_to_string($coin_value_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? round($coin_value_raw, 2) : round($coin_value_raw, $app_config['primary_currency_decimals_max']) );
    $volume_pairing_rounded = round($volume_pairing_raw);
    }
    else {
    $volume_pairing_rounded = round($volume_pairing_raw, 3);
    }
    
    
    $result[strtolower($market_data)] = array(
                                        $selected_pairing => array('spot_price' => $coin_value_raw, '24hr_volume' => $volume_pairing_rounded),
                                        $primary_currency => array('spot_price' => $coin_primary_currency_worth_raw, '24hr_volume' => round($asset_market_data['24hr_primary_currency_volume']) )
                                       );
    
    
    }
    else {
    $result[strtolower($market_data)] = array('error' => 'Missing parameters');
    }



}


// Return in json format
if ( isset($result) ) {
echo json_encode($result, JSON_PRETTY_PRINT);
}



?>


