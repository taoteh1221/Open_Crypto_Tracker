<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

				$usd_daily_average = ( round($daily_average, 8) * get_btc_usd($btc_exchange)['last_trade'] );
				
				$btc_daily_average = round($daily_average, 8);
				
				$kwh_cost_daily = ( ( trim($_POST['watts_used']) / 1000 ) * 24 ) * trim($_POST['watts_rate']);
				
				$pool_fee_daily = $usd_daily_average * ( trim($_POST['pool_fee']) / 100 );

?>