<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				<b class='blue'>Current <?=$pow_asset_data['name']?> Value Per Coin:</b> 
				
				<?php
				
				$val_per_unit = round( ($mined_asset_val * $ct['sel_opt']['sel_btc_prim_currency_val']) , $ct['conf']['currency']['currency_decimals_max']);
				
                    $thres_dec = $ct['gen']->thres_dec($val_per_unit, 'u', 'fiat'); // Units mode
                    $val_per_unit_pretty = $ct['var']->num_pretty($val_per_unit, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
                    
                    $btc_unit_val = number_format($mined_asset_val, $ct['conf']['currency']['crypto_decimals_max']);
				
                    $btc_unit_val = $ct['var']->num_to_str($btc_unit_val); // Cleanup any trailing zeros
				
				echo ( $pow_asset_data['symbol'] == 'btc' ? '<span class="safe_non_table_num"><span class="num_conv">' . number_format($ct['sel_opt']['sel_btc_prim_currency_val'], 2) . '</span></span> ' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) : '<span class="safe_non_table_num"><span class="num_conv">' . $btc_unit_val . '</span></span> BTC (' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . $val_per_unit_pretty . '</span></span> '.strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']).')' );
				
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_asset_data['symbol'] != 'btc' ) {
				?>
				<b class='blue'>Average <?=strtoupper($pow_asset_data['symbol'])?> Earned Daily (block reward only):</b> 
				
				
                    <span class="safe_non_table_num"><span class="num_conv">
				<?php
				$mined_daily_avg = number_format($daily_avg , $ct['conf']['currency']['crypto_decimals_max']);
                    $mined_daily_avg = $ct['var']->num_to_str($mined_daily_avg); // Cleanup any trailing zeros
				echo $mined_daily_avg . '</span></span> ' . strtoupper($pow_asset_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b class='blue'>Average BTC Value Earned Daily:</b> 
				
                    <span class="safe_non_table_num"><span class="num_conv">
				<?php
				$prim_currency_daily_avg_raw = $ct['var']->num_to_str($prim_currency_daily_avg_raw); // Handle small / large numbers
				
                    $thres_dec = $ct['gen']->thres_dec($prim_currency_daily_avg_raw, 'u', 'fiat'); // Units mode
                    $prim_currency_daily_avg_pretty = $ct['var']->num_pretty($prim_currency_daily_avg_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
				
				$btc_mined_daily_avg = number_format($btc_daily_avg_raw, $ct['conf']['currency']['crypto_decimals_max']);
                    $btc_mined_daily_avg = $ct['var']->num_to_str($btc_mined_daily_avg); // Cleanup any trailing zeros
                    
				echo $btc_mined_daily_avg . '</span></span> BTC (' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . $prim_currency_daily_avg_pretty . '</span></span> ' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b class='blue'>Power Cost Daily:</b> 
				
				<?php
				echo $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format($kwh_cost_daily, 2) . '</span></span>';
				?>
				
				</span> 
				
				<br />
				<br />
				
				<span class='red'><b class='blue'>Pool Fee Daily:</b> 
				
				<?php
				echo $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format($pool_fee_daily, 2) . '</span></span>';
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
				
				<span class="<?=$mining_daily_profit_span?>"><b class='blue'>Daily Profit:</b></span> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format($mining_daily_profit, 2) . '</span></span></span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $pow_asset_data['symbol'] != 'btc' ) {
				?>
				<b class='blue'>Average <?=strtoupper($pow_asset_data['symbol'])?> Earned Weekly (block reward only):</b> 
				
				
                    <span class="safe_non_table_num"><span class="num_conv">
				<?php
				$mined_weekly_avg = number_format( ($daily_avg * 7) , $ct['conf']['currency']['crypto_decimals_max']);
                    $mined_weekly_avg = $ct['var']->num_to_str($mined_weekly_avg); // Cleanup any trailing zeros
                    
				echo $mined_weekly_avg . '</span></span> ' . strtoupper($pow_asset_data['symbol']);
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				<b class='blue'>Average BTC Value Earned Weekly:</b> 
				
                    <span class="safe_non_table_num"><span class="num_conv">
				<?php
				$btc_mined_weekly_avg = number_format( ($btc_daily_avg_raw * 7) , $ct['conf']['currency']['crypto_decimals_max']);
                    $btc_mined_weekly_avg = $ct['var']->num_to_str($btc_mined_weekly_avg); // Cleanup any trailing zeros
                    
				echo $btc_mined_weekly_avg . '</span></span> BTC (' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format( $prim_currency_daily_avg_raw * 7 , 2) . '</span></span> '.strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']).')';
				?>
				
				<br />
				<br />
				
				<span class='red'><b class='blue'>Power Cost Weekly:</b> 
				
				<?php
				echo $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format($kwh_cost_daily * 7, 2) . '</span></span>';
				?>
				
				</span>
				
				<br />
				<br />
				
				<span class='red'><b class='blue'>Pool Fee Weekly:</b> 
				
				<?php
				echo $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format($pool_fee_daily * 7, 2) . '</span></span>';
				?>
				
				</span>
				
				<br />
				<br />
				
				<span class="<?=$mining_daily_profit_span?>"><b class='blue'>Weekly Profit:</b></span> 
				
				<?php
				echo '<span class="'.$mining_daily_profit_span.'">' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '<span class="safe_non_table_num"><span class="num_conv">' . number_format( ($mining_daily_profit * 7) , 2) . '</span></span></span>';
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		