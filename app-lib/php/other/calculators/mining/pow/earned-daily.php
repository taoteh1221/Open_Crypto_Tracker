<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				<b>Current <?=$pow_coin_data['name']?> Value Per Coin:</b> 
				
				<?php
				$value_per_coin = round( $mined_coin_value * $selected_btc_primary_currency_value , 8);
				
				$value_per_coin = ( number_to_string($value_per_coin) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($value_per_coin, 2) : round($value_per_coin, $app_config['general']['primary_currency_decimals_max']) );
				
				echo ( $pow_coin_data['symbol'] == 'btc' ? number_format($selected_btc_primary_currency_value, 2) . ' ' . strtoupper($app_config['general']['btc_primary_currency_pairing']) : number_format($mined_coin_value, 8) . ' BTC (' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . $value_per_coin . ' '.strtoupper($app_config['general']['btc_primary_currency_pairing']).')' );
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_coin_data['symbol'] != 'btc' ) {
				?>
				<b>Average <?=strtoupper($pow_coin_data['symbol'])?> Earned Daily (block reward only):</b> 
				
				
				<?php
				echo number_format( $daily_average , 8) . ' ' . strtoupper($pow_coin_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b>Average BTC Value Earned Daily:</b> 
				
				<?php
				$primary_currency_daily_average_raw = ( number_to_string($primary_currency_daily_average_raw) >= $app_config['general']['primary_currency_decimals_max_threshold'] ? round($primary_currency_daily_average_raw, 2) : round($primary_currency_daily_average_raw, $app_config['general']['primary_currency_decimals_max']) );
				
				echo number_format( $btc_daily_average_raw, 8 ) . ' BTC (' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . $primary_currency_daily_average_raw . ' '.strtoupper($app_config['general']['btc_primary_currency_pairing']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b>Power Cost Daily:</b> 
				
				<?php
				echo $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($kwh_cost_daily, 2);
				?>
				
				</span> 
				
				<br />
				<br />
				
				<span class='red'><b>Pool Fee Daily:</b> 
				
				<?php
				echo $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($pool_fee_daily, 2);
				?>
				
				</span> 
				
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
				
				<b><span class="<?=$mining_daily_profit_span?>">Daily Profit:</span></b> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($mining_daily_profit, 2) . '</span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_coin_data['symbol'] != 'btc' ) {
				?>
				<b>Average <?=strtoupper($pow_coin_data['symbol'])?> Earned Weekly (block reward only):</b> 
				
				
				<?php
				echo number_format( $daily_average * 7 , 8) . ' ' . strtoupper($pow_coin_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b>Average BTC Value Earned Weekly:</b> 
				
				<?php
				echo number_format( $btc_daily_average_raw * 7 , 8) . ' BTC (' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format( $primary_currency_daily_average_raw * 7 , 2) . ' '.strtoupper($app_config['general']['btc_primary_currency_pairing']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b>Power Cost Weekly:</b> 
				
				<?php
				echo $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($kwh_cost_daily * 7, 2);
				?>
				
				</span>
				
				<br />
				<br />
				
				<span class='red'><b>Pool Fee Weekly:</b> 
				
				<?php
				echo $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($pool_fee_daily * 7, 2);
				?>
				
				</span>
				
				<br />
				<br />
				
				<b><span class="<?=$mining_daily_profit_span?>">Weekly Profit:</span></b> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format( ($mining_daily_profit * 7) , 2) . '</span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		