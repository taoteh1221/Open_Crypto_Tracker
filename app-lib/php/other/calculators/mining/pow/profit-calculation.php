<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
				
$mined_coin_value = asset_market_data(strtoupper($pow_coin_data['symbol']), $pow_coin_data['exchange_name'], $pow_coin_data['exchange_market'])['last_trade'];
				
$btc_daily_average_raw = ( $pow_coin_data['symbol'] == 'btc' ? $daily_average : $daily_average * $mined_coin_value );

$primary_currency_daily_average_raw = $btc_daily_average_raw * $selected_btc_primary_currency_value;
				
$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
$pool_fee_daily = $primary_currency_daily_average_raw * ( trim($_POST['pool_fee']) / 100 );

?>