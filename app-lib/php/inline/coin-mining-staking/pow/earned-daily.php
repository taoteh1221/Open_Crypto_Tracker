<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				<b>Current <?=$pow_asset_data['name']?> Value Per Coin:</b> 
				
				<?php
				
				$val_per_unit = round( ($mined_asset_val * $ct['sel_opt']['sel_btc_prim_currency_val']) , $ct['conf']['gen']['currency_decimals_max']);
				
                    $thres_dec = $ct['gen']->thres_dec($val_per_unit, 'u', 'fiat'); // Units mode
                    $val_per_unit_pretty = $ct['var']->num_pretty($val_per_unit, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
                    
                    $btc_unit_val = number_format($mined_asset_val, $ct['conf']['gen']['crypto_decimals_max']);
				
                    $btc_unit_val = $ct['var']->num_to_str($btc_unit_val); // Cleanup any trailing zeros
				
				echo ( $pow_asset_data['symbol'] == 'btc' ? number_format($ct['sel_opt']['sel_btc_prim_currency_val'], 2) . ' ' . strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']) : $btc_unit_val . ' BTC (' . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $val_per_unit_pretty . ' '.strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']).')' );
				
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_asset_data['symbol'] != 'btc' ) {
				?>
				<b>Average <?=strtoupper($pow_asset_data['symbol'])?> Earned Daily (block reward only):</b> 
				
				
				<?php
				$mined_daily_avg = number_format($daily_avg , $ct['conf']['gen']['crypto_decimals_max']);
                    $mined_daily_avg = $ct['var']->num_to_str($mined_daily_avg); // Cleanup any trailing zeros
				echo $mined_daily_avg . ' ' . strtoupper($pow_asset_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b>Average BTC Value Earned Daily:</b> 
				
				<?php
				$prim_currency_daily_avg_raw = $ct['var']->num_to_str($prim_currency_daily_avg_raw); // Handle small / large numbers
				
                    $thres_dec = $ct['gen']->thres_dec($prim_currency_daily_avg_raw, 'u', 'fiat'); // Units mode
                    $prim_currency_daily_avg_pretty = $ct['var']->num_pretty($prim_currency_daily_avg_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
				
				$btc_mined_daily_avg = number_format($btc_daily_avg_raw, $ct['conf']['gen']['crypto_decimals_max']);
                    $btc_mined_daily_avg = $ct['var']->num_to_str($btc_mined_daily_avg); // Cleanup any trailing zeros
                    
				echo $btc_mined_daily_avg . ' BTC (' . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . $prim_currency_daily_avg_pretty . ' '.strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b>Power Cost Daily:</b> 
				
				<?php
				echo $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format($kwh_cost_daily, 2);
				?>
				
				</span> 
				
				<br />
				<br />
				
				<span class='red'><b>Pool Fee Daily:</b> 
				
				<?php
				echo $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format($pool_fee_daily, 2);
				?>
				
				</span> 
				
				<br />
				<br />
				
				<?php
				
				$mining_daily_profit = $ct['var']->num_to_str($prim_currency_daily_avg_raw - $kwh_cost_daily - $pool_fee_daily); // Better decimal support
				
				if ( $mining_daily_profit >= 0 ) {
				$mining_daily_profit_span = 'green';
				}
				else {
				$mining_daily_profit_span = 'red';
				}
				
				?>
				
				<b><span class="<?=$mining_daily_profit_span?>">Daily Profit:</span></b> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format($mining_daily_profit, 2) . '</span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_asset_data['symbol'] != 'btc' ) {
				?>
				<b>Average <?=strtoupper($pow_asset_data['symbol'])?> Earned Weekly (block reward only):</b> 
				
				
				<?php
				$mined_weekly_avg = number_format( ($daily_avg * 7) , $ct['conf']['gen']['crypto_decimals_max']);
                    $mined_weekly_avg = $ct['var']->num_to_str($mined_weekly_avg); // Cleanup any trailing zeros
                    
				echo $mined_weekly_avg . ' ' . strtoupper($pow_asset_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b>Average BTC Value Earned Weekly:</b> 
				
				<?php
				$btc_mined_weekly_avg = number_format( ($btc_daily_avg_raw * 7) , $ct['conf']['gen']['crypto_decimals_max']);
                    $btc_mined_weekly_avg = $ct['var']->num_to_str($btc_mined_weekly_avg); // Cleanup any trailing zeros
                    
				echo $btc_mined_weekly_avg . ' BTC (' . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format( $prim_currency_daily_avg_raw * 7 , 2) . ' '.strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b>Power Cost Weekly:</b> 
				
				<?php
				echo $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format($kwh_cost_daily * 7, 2);
				?>
				
				</span>
				
				<br />
				<br />
				
				<span class='red'><b>Pool Fee Weekly:</b> 
				
				<?php
				echo $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format($pool_fee_daily * 7, 2);
				?>
				
				</span>
				
				<br />
				<br />
				
				<b><span class="<?=$mining_daily_profit_span?>">Weekly Profit:</span></b> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ] . number_format( ($mining_daily_profit * 7) , 2) . '</span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		