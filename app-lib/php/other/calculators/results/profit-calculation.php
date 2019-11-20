<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	
				$btc_daily_average_raw = ( $calculation_form_data[1] == 'btc' ? $daily_average : $daily_average * get_coin_value(strtoupper($calculation_form_data[1]), $calculation_form_data[6], $calculation_form_data[7])['last_trade'] );

				$usd_daily_average_raw = $btc_daily_average_raw * $btc_usd;
				
				$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
				$pool_fee_daily = $usd_daily_average_raw * ( trim($_POST['pool_fee']) / 100 );

?>