<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				Current <?=$calculation_form_data[0]?> Value Per Coin: 
				<?php
				echo ( $calculation_form_data[1] == 'btc' ? number_format($selected_btc_primary_currency_value, 2) . ' ' . strtoupper($app_config['btc_primary_currency_pairing']) : number_format($mined_coin_value, 8) . ' BTC (' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . round( $mined_coin_value * $selected_btc_primary_currency_value , 8) . ' '.strtoupper($app_config['btc_primary_currency_pairing']).')' );
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
				echo number_format( $btc_daily_average_raw, 8 ) . ' BTC (' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format( $primary_currency_daily_average_raw , 2) . ' '.strtoupper($app_config['btc_primary_currency_pairing']).')';
				?>
				
				<br />
				<br />
				
				<b class='red'>Power Cost Daily: 
				<?php
				echo $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format($kwh_cost_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<b class='red'>Pool Fee Daily: 
				<?php
				echo $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format($pool_fee_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<?php
				
				$mining_daily_profit = number_to_string($primary_currency_daily_average_raw - $kwh_cost_daily - $pool_fee_daily); // Better decimal support
				
				if ( $mining_daily_profit >= 0 ) {
				$mining_daily_profit_span = 'green';
				}
				else {
				$mining_daily_profit_span = 'red';
				}
				
				?>
				
				<b><span class="<?=$mining_daily_profit_span?>">Daily Profit:</span> 
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format($mining_daily_profit, 2) . '</span>';
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
				echo number_format( $btc_daily_average_raw * 7 , 8) . ' BTC (' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format( $primary_currency_daily_average_raw * 7 , 2) . ' '.strtoupper($app_config['btc_primary_currency_pairing']).')';
				?>
				
				<br />
				<br />
				
				<b class='red'>Power Cost Weekly: 
				<?php
				echo $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format($kwh_cost_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b class='red'>Pool Fee Weekly: 
				<?php
				echo $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format($pool_fee_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b><span class="<?=$mining_daily_profit_span?>">Weekly Profit:</span> 
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . number_format( ($mining_daily_profit * 7) , 2) . '</span>';
				?></b>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		</p>
		<!-- Green colored END -->
		
		