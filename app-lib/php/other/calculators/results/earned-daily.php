<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				Current <?=$calculation_form_data[0]?> Value Per Coin: 
				<?php
				echo ( $calculation_form_data[1] == 'btc' ? number_format($btc_market_value, 2) . ' ' . strtoupper($btc_primary_currency_pairing) : number_format($mined_coin_value, 8) . ' BTC (' . $bitcoin_market_currencies[$btc_primary_currency_pairing] . round( $mined_coin_value * $btc_market_value , 8) . ' '.strtoupper($btc_primary_currency_pairing).')' );
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $calculation_form_data[1] != 'btc' ) {
				?>
				Average <?=strtoupper($calculation_form_data[1])?> Earned Daily (block reward only): 
				
				
				<?php

				echo number_format( $daily_average , 8) . ' ' . strtoupper($calculation_form_data[1]);
				
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				Average BTC Value Earned Daily: 
				<?php
				echo number_format( $btc_daily_average_raw, 8 ) . ' BTC (' . $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format( $fiat_daily_average_raw , 2) . ' '.strtoupper($btc_primary_currency_pairing).')';
				?>
				
				<br />
				<br />
				
				<b class='red'>Power Cost Daily: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format($kwh_cost_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<b class='red'>Pool Fee Daily: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format($pool_fee_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<b>Daily Profit: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format( $fiat_daily_average_raw - $kwh_cost_daily - $pool_fee_daily , 2);
				?></b>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $calculation_form_data[1] != 'btc' ) {
				?>
				Average <?=strtoupper($calculation_form_data[1])?> Earned Weekly (block reward only): 
				
				
				<?php

				echo number_format( $daily_average * 7 , 8) . ' ' . strtoupper($calculation_form_data[1]);
				
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				Average BTC Value Earned Weekly: 
				<?php
				echo number_format( $btc_daily_average_raw * 7 , 8) . ' BTC (' . $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format( $fiat_daily_average_raw * 7 , 2) . ' '.strtoupper($btc_primary_currency_pairing).')';
				?>
				
				<br />
				<br />
				
				<b class='red'>Power Cost Weekly: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format($kwh_cost_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b class='red'>Pool Fee Weekly: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format($pool_fee_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b>Weekly Profit: 
				<?php
				echo $bitcoin_market_currencies[$btc_primary_currency_pairing] . number_format( ( $fiat_daily_average_raw - $kwh_cost_daily - $pool_fee_daily ) * 7 , 2);
				?></b>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		</p>
		<!-- Green colored END -->
		
		