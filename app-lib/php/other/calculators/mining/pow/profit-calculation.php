<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
				
$mined_coin_val = $pt_api->market(strtoupper($pow_coin_data['symbol']), $pow_coin_data['exchange_name'], $pow_coin_data['exchange_market'])['last_trade'];
				
$btc_daily_avg_raw = ( $pow_coin_data['symbol'] == 'btc' ? $daily_avg : $daily_avg * $mined_coin_val );

$prim_curr_daily_avg_raw = $btc_daily_avg_raw * $sel_btc_prim_curr_val;
				
$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
$pool_fee_daily = $prim_curr_daily_avg_raw * ( trim($_POST['pool_fee']) / 100 );

?>