<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	
				$btc_daily_average = ( $calculation_form_data[1] == 'btc' ? $daily_average : $daily_average * get_coin_value($calculation_form_data[6], $calculation_form_data[7])['last_trade'] );

				$usd_daily_average = $btc_daily_average * get_btc_usd($btc_exchange)['last_trade'];
				
				$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
				$pool_fee_daily = $usd_daily_average * ( trim($_POST['pool_fee']) / 100 );

?>