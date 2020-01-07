<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
				
				$mined_coin_value = asset_market_data(strtoupper($calculation_form_data[1]), $calculation_form_data[6], $calculation_form_data[7])['last_trade'];
				
				$btc_daily_average_raw = ( $calculation_form_data[1] == 'btc' ? $daily_average : $daily_average * $mined_coin_value );

				$fiat_daily_average_raw = $btc_daily_average_raw * $btc_market_value;
				
				$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
				$pool_fee_daily = $fiat_daily_average_raw * ( trim($_POST['pool_fee']) / 100 );

?>