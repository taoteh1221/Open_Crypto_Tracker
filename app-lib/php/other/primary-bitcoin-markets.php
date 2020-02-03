<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



//////////////////////////////////////////////////////////////////
// PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

// MUST be called FIRST at runtime by the default bitcoin market, to set this var for reuse later in runtime
$selected_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']];
$btc_primary_currency_value = asset_market_data('BTC', $app_config['btc_primary_exchange'], $selected_pairing_id)['last_trade'];

$default_btc_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange];
$default_btc_primary_currency_value = asset_market_data('BTC', $default_btc_primary_exchange, $default_btc_pairing_id)['last_trade'];


// Log any Bitcoin market errors
if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']] ) {
app_logging('config_error', 'init.php btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $app_config['btc_primary_currency_pairing'] . ';' );
}
elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']] ) {
app_logging('config_error', 'init.php btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $app_config['btc_primary_exchange'] . ';' );
}

if ( !isset($btc_primary_currency_value) || $btc_primary_currency_value == 0 ) {
app_logging('other_error', 'init.php Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $app_config['btc_primary_currency_pairing'] . '; exchange: ' . $app_config['btc_primary_exchange'] . '; pairing_id: ' . $selected_pairing_id . '; value: ' . $btc_primary_currency_value );
}


// Log any charts/alerts Bitcoin market errors
if ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing] ) {
app_logging('config_error', 'init.php Charts / alerts btc_primary_currency_pairing variable not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . ';' );
}
elseif ( !$app_config['portfolio_assets']['BTC']['market_pairing'][$default_btc_primary_currency_pairing][$default_btc_primary_exchange] ) {
app_logging('config_error', 'init.php Charts / alerts btc_primary_exchange variable not properly set', 'btc_primary_exchange: ' . $default_btc_primary_exchange . ';' );
}

if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
app_logging('other_error', 'init.php Charts / alerts Bitcoin primary currency market value not properly set', 'btc_primary_currency_pairing: ' . $default_btc_primary_currency_pairing . '; exchange: ' . $default_btc_primary_exchange . '; pairing_id: ' . $default_btc_pairing_id . '; value: ' . $default_btc_primary_currency_value );
}

//////////////////////////////////////////////////////////////////
// END PRIMARY BITCOIN MARKET SETTINGS
//////////////////////////////////////////////////////////////////

  
 
 ?>