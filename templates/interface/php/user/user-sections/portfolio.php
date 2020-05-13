<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

    
			<h4 style='display: inline;'>Portfolio</h4> <span class='bitcoin'>(<?=$app_config['power_user']['last_trade_cache_time']?> minute cache)</span>
			<?php
			if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
				
				if ( $alert_percent[4] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $alert_percent[4] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
				$text_mcap_trend = $alert_percent[3];
				
				$text_mcap_trend = ucwords(preg_replace("/hour/i", " hour", $text_mcap_trend));
				
				$text_mcap_trend = ucwords(preg_replace("/day/i", " day", $text_mcap_trend));
				
				if ( $theme_selected == 'dark' ) {
				
					if ( $alert_percent[2] == 'gain' ) {
					$alert_filter = '<span style="color: #7dc67d;">+</span>';
					$alert_filter_css = 'green';
					}
					elseif ( $alert_percent[2] == 'loss' ) {
					$alert_filter = '<span style="color: #efd362;">-</span>';
					$alert_filter_css = 'orange';
					}
					elseif ( $alert_percent[2] == 'both' ) {
					$alert_filter = '<span style="color: #7dc67d;">+</span><span style="color: #efd362;">-</span>';
					$alert_filter_css = 'blue';
					}
				
				}
				elseif ( $theme_selected == 'light' ) {
				
					if ( $alert_percent[2] == 'gain' ) {
					$alert_filter = '<span style="color: #6ead6e;">+</span>';
					$alert_filter_css = 'green';
					}
					elseif ( $alert_percent[2] == 'loss' ) {
					$alert_filter = '<span style="color: #dd7c0d;">-</span>';
					$alert_filter_css = 'orange';
					}
					elseif ( $alert_percent[2] == 'both' ) {
					$alert_filter = '<span style="color: #6ead6e;">+</span><span style="color: #dd7c0d;">-</span>';
					$alert_filter_css = 'blue';
					}
				
				}
				
			?>
			  &nbsp; &nbsp; <span class='<?=$alert_filter_css?>' style='font-weight: bold;'><?=$visual_audio_alerts?> alerts (<?=ucfirst($app_config['general']['primary_marketcap_site'])?> / <?=$alert_filter?><?=$alert_percent[1]?>% / <?=$text_mcap_trend?>)</span>
			<?php
			}
			?>  &nbsp; &nbsp; &nbsp; <a href='javascript:app_reloading_placeholder();location.reload(true);' style='font-weight: bold;' title='Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache setting in config.php is set to <?=$app_config['power_user']['last_trade_cache_time']?> minute(s). A setting of 1 or higher assists in avoiding IP blacklisting by exchanges.'>Refresh</a>
			
			 &nbsp;<select name='select_auto_refresh' id='select_auto_refresh' onchange='auto_reload(this.value);'>
				<option value=''> Manually </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> Every 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> Every 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> Every 15 Minutes </option>
				<option value='1800' <?=( $_COOKIE['coin_reload'] == '1800' ? 'selected' : '' )?>> Every 30 Minutes </option>
			</select> &nbsp;<span id='reload_countdown' class='red'></span>
					
			<p>                        
                            
<?php
// Start outputting results
if ( $_POST['submit_check'] == 1 || !$csv_import_fail && $_POST['csv_check'] == 1 || $_COOKIE['coin_amounts'] ) {
?>


<table border='0' cellpadding='10' cellspacing='0' id="coins_table" class='show_coin_values'>
 <thead>
    <tr>
<th class='border_lt'>Sort</th>
<th class='border_lt blue al_right'><span>Asset</span></th>
<th class='border_t'>Per-Token (<span class='btc_primary_currency_pairing'><?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?></span>)</th>
<th class='border_lt blue al_right'>Holdings</th>
<th class='border_t'>Symbol</th>
<th class='border_lt blue'>Exchange</th>
<th class='border_t'>Trade Volume (<span class='btc_primary_currency_pairing'><?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?></span>)</th>
<th class='border_t al_right'>Trade Value</th>
<th class='border_t blue'>Market</th>
<th class='border_lt blue'>Holdings Value</th>
<th class='border_lrt blue'>Subtotal (<span class='btc_primary_currency_pairing'><?=strtoupper($app_config['general']['btc_primary_currency_pairing'])?></span>)</th>
    </tr>
  </thead>
 <tbody>
 
<?php

	if ( $_POST['submit_check'] == 1 ) {
	
		
		if (is_array($_POST) || is_object($_POST)) {
			
		$btc_market = ($_POST['btc_market'] - 1);
									
									foreach ( $_POST as $key => $value ) {
								
										if ( preg_match("/_amount/i", $key) ) {
										
										$held_amount = remove_number_format($value);
										$coin_symbol = strtoupper(preg_replace("/_amount/i", "", $key));
										$selected_pairing = ($_POST[strtolower($coin_symbol).'_pairing']);
										// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$selected_market = ($_POST[strtolower($coin_symbol).'_market'] - 1); 
										$purchase_price = remove_number_format($_POST[strtolower($coin_symbol).'_paid']);
										$leverage_level = $_POST[strtolower($coin_symbol).'_leverage'];
										$selected_margintype = $_POST[strtolower($coin_symbol).'_margintype'];
												
						
										// Render the row of coin data in the UI
										ui_coin_data_row($app_config['portfolio_assets'][$coin_symbol]['asset_name'], $coin_symbol, $held_amount, $app_config['portfolio_assets'][$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
										
										
										
											if ( $held_amount >= 0.00000001 ) {
												
											$assets_added = 1;
											
											
												if ( $purchase_price >= 0.00000001 ) {
												$purchase_price_added = 1;
												}
												
												if ( $leverage_level >= 2 ) {
												$leverage_added = 1;
												}
												
												if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
												$short_added = 1;
												}
											
											
											}
											elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
											$assets_watched = 1;
											}
										
										
										}
									
									
									
									}
		
		}
	
	}
	elseif ( $run_csv_import == 1 ) {
	
		
		if (is_array($csv_file_array) || is_object($csv_file_array)) {
			
									
				foreach( $csv_file_array as $key => $value ) {
								
									$run_csv_import = 1;
	        
	        		
	        			if ( remove_number_format($value[1]) > 0.00000000 ) {  // Show even if decimal is off the map, just for UX purposes tracking token price only
	        			
	        			$value[5] = ( whole_int( trim($value[5]) ) != false ? trim($value[5]) : 1 ); // If market ID input is corrupt, default to 1
	        			$value[3] = ( whole_int( trim($value[3]) ) != false ? trim($value[3]) : 0 ); // If leverage amount input is corrupt, default to 0
	        			
										$held_amount = remove_number_format( trim($value[1]) );
										$coin_symbol = strtoupper( trim($value[0]) );
										$selected_pairing = strtolower( trim($value[6]) );
										// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$selected_market = ( $value[5] != NULL ? $value[5] - 1 : 1 ); 
										$purchase_price = remove_number_format($value[2]);
										$leverage_level = $value[3];
										$selected_margintype = strtolower( trim($value[4]) );
										
											
											// Check pairing value
											foreach ( $app_config['portfolio_assets'][$coin_symbol]['market_pairing'] as $pairing_key => $unused ) {
					 						$ploop = 0;
					 						
					 							// Use first pairing key from coins config for this asset, if no pairing value was set properly in the spreadsheet
					 							if ( $ploop == 0 ) {
					 								
					 								if ( $selected_pairing == NULL || !$app_config['portfolio_assets'][$coin_symbol]['market_pairing'][$selected_pairing] ) {
					 								$selected_pairing = $pairing_key;
					 								}
					 							
					 							}
											
											$ploop = $ploop + 1;
											}
											
											
											// Check margin type value
											if ( $selected_margintype != 'long' && $selected_margintype != 'short' ) {
											$selected_margintype = 'long';
											}
											
						
						
										// Render the row of coin data in the UI
										ui_coin_data_row($app_config['portfolio_assets'][$coin_symbol]['asset_name'], $coin_symbol, $held_amount, $app_config['portfolio_assets'][$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
										
										
										
											if ( $held_amount >= 0.00000001 ) {
												
											$assets_added = 1;
											
											
												if ( $purchase_price >= 0.00000001 ) {
												$purchase_price_added = 1;
												}
												
												if ( $leverage_level >= 2 ) {
												$leverage_added = 1;
												}
												
												if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
												$short_added = 1;
												}
											
											
											}
											elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
											$assets_watched = 1;
											}
											
										
										
										
	       		 	}
									
									
				}
		
		}
	
	}
	elseif ( $_COOKIE['coin_amounts'] && $_COOKIE['coin_markets'] && $_COOKIE['coin_pairings'] ) {
	
	
		$all_cookies_data_array = array('');
		
	
	$all_coin_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
	
		if (is_array($all_coin_markets_cookie_array) || is_object($all_coin_markets_cookie_array)) {
			
					foreach ( $all_coin_markets_cookie_array as $coin_markets ) {
									
					$single_coin_market_cookie_array = explode("-", $coin_markets);
					
					$coin_symbol = strtoupper(preg_replace("/_market/i", "", $single_coin_market_cookie_array[0]));
					
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] = $single_coin_market_cookie_array[1];
					
					}
					
		}
	
	
	$all_coin_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
	
		if (is_array($all_coin_pairings_cookie_array) || is_object($all_coin_pairings_cookie_array)) {
			
					foreach ( $all_coin_pairings_cookie_array as $coin_pairings ) {
									
					$single_coin_pairing_cookie_array = explode("-", $coin_pairings);
					
					$coin_symbol = strtoupper(preg_replace("/_pairing/i", "", $single_coin_pairing_cookie_array[0]));
					
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_pairing'] = $single_coin_pairing_cookie_array[1];
					
					}
					
		}
	
	
	$all_coin_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	
		if (is_array($all_coin_paid_cookie_array) || is_object($all_coin_paid_cookie_array)) {
			
					foreach ( $all_coin_paid_cookie_array as $coin_paid ) {
									
					$single_coin_paid_cookie_array = explode("-", $coin_paid);
					
					$coin_symbol = strtoupper(preg_replace("/_paid/i", "", $single_coin_paid_cookie_array[0]));
					
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_paid'] = $single_coin_paid_cookie_array[1];
					
					}
					
		}
	
	
	$all_coin_leverage_cookie_array = explode("#", $_COOKIE['coin_leverage']);
	
		if (is_array($all_coin_leverage_cookie_array) || is_object($all_coin_leverage_cookie_array)) {
			
					foreach ( $all_coin_leverage_cookie_array as $coin_leverage ) {
									
					$single_coin_leverage_cookie_array = explode("-", $coin_leverage);
					
					$coin_symbol = strtoupper(preg_replace("/_leverage/i", "", $single_coin_leverage_cookie_array[0]));
					
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_leverage'] = $single_coin_leverage_cookie_array[1];
					
					}
					
		}
	
	
	$all_coin_margintype_cookie_array = explode("#", $_COOKIE['coin_margintype']);
	
		if (is_array($all_coin_margintype_cookie_array) || is_object($all_coin_margintype_cookie_array)) {
			
					foreach ( $all_coin_margintype_cookie_array as $coin_margintype ) {
									
					$single_coin_margintype_cookie_array = explode("-", $coin_margintype);
					
					$coin_symbol = strtoupper(preg_replace("/_margintype/i", "", $single_coin_margintype_cookie_array[0]));
					
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_margintype'] = $single_coin_margintype_cookie_array[1];
					
					}
					
		}
	
	
		
		
	
	$all_coin_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	
		if (is_array($all_coin_amounts_cookie_array) || is_object($all_coin_amounts_cookie_array)) {
			
					foreach ( $all_coin_amounts_cookie_array as $asset_amounts ) {
									
					$single_coin_amount_cookie_array = explode("-", $asset_amounts);
					
					$coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_amount_cookie_array[0]));
				
							if ( $coin_symbol == 'BTC' && !$btc_market ) {
							$btc_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
							}
	
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'] = $single_coin_amount_cookie_array[1];
					
					
					// Bundle all required cookie data in this final cookies parsing loop for each coin, and render the coin's data
					// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					$held_amount = number_to_string($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount']);
					$selected_pairing = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_pairing'];
					// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
					$selected_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
					$purchase_price = number_to_string($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_paid']);
					$leverage_level = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_leverage'];
					$selected_margintype = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_margintype'];
					
					
					// Render the row of coin data in the UI
					ui_coin_data_row($app_config['portfolio_assets'][$coin_symbol]['asset_name'], $coin_symbol, $held_amount, $app_config['portfolio_assets'][$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
					
					
						
						if ( $held_amount >= 0.00000001 ) {
							
						$assets_added = 1;
						
						
							if ( $purchase_price >= 0.00000001 ) {
							$purchase_price_added = 1;
							}
												
							if ( $leverage_level >= 2 ) {
							$leverage_added = 1;
							}
												
							if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
							$short_added = 1;
							}
						
						
						}
						elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
						$assets_watched = 1;
						}
						
						
					
	
					}
					
					
		}
		
		
		
	}

?>

</tbody>
</table>


<?php


// Get portfolio summaries


$total_btc_worth_raw = number_format(bitcoin_total(), 8, '.', '');

// FOR UX-SAKE, WE CUT OFF EXTRA RIGHT SIDE ZERO DECIMALS IF VALUE IS AT LEAST A SATOSHI OR HIGHER (O.00000001),
// #BUT# IF VALUE IS LITERALLY ZERO (WATCH-ONLY, ETC), WE WANT TO SHOW THAT #CLEARLY# TO THE END USER WITH 0.00000000
$total_btc_worth = ( $total_btc_worth_raw >= 0.00000001 ? pretty_numbers($total_btc_worth_raw, 8) : '0.00000000' );

$total_primary_currency_worth = coin_stats_data('coin_worth_total');

$bitcoin_dominance = ( $btc_worth_array['BTC'] / $total_btc_worth_raw ) * 100;

$ethereum_dominance = ( $btc_worth_array['ETH'] / $total_btc_worth_raw ) * 100;

$altcoin_dominance = 100 - $bitcoin_dominance - $ethereum_dominance;
	
		
?>
<br class='clear_both' />
<div class="show_coin_values bold_1 blue"><!-- Summary START -->
<?php
		
		// Run BEFORE output of BTC / PAIRING portfolio values, to include any margin / leverage summaries in parentheses NEXT TO THEM (NOT in the actual BTC / PAIRING amounts, for UX's sake)
		if ( $purchase_price_added == 1 ) {
			
		$gain_loss_total = coin_stats_data('gain_loss_total');
		
		$parsed_gain_loss_total = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $gain_loss_total, 2, '.', ',' ) );
		
		$original_worth = coin_stats_data('coin_paid_total');
		
		$leverage_only_gain_loss = coin_stats_data('gain_loss_only_leverage');
  		
		$total_primary_currency_worth_inc_leverage = $total_primary_currency_worth + $leverage_only_gain_loss;
		
  		// Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  		// We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  		// (plus sign would indicate a gain, NOT 'total worth')
		$parsed_total_primary_currency_worth_inc_leverage = preg_replace("/-/", "", number_format( $total_primary_currency_worth_inc_leverage, 2, '.', ',' ) );
  		
		$total_primary_currency_worth_if_purchase_price = coin_stats_data('coin_total_worth_if_purchase_price') + $leverage_only_gain_loss;
		
		$gain_loss_text = ( $gain_loss_total >= 0 ? 'gains' : 'losses' );
		
		}
		
	  
	  
	  // Notice that margin leverage is NOT included !!WITHIN!! BTC / PAIRING TOTALS EVER (for UX's sake, too confusing to included in anything other than gain / loss stats)
	  // We only include data in parenthesis NEXT TO THE BTC / PAIRING PORTFOLIO SUMMARIES
	  $leverage_text1 = ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? ' <span class="red"> &nbsp;(includes adjusted long deposits, <i><u>not</u></i> leverage)</span>' : '' );
	  $leverage_text2 = ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? ' <span class="red"> &nbsp;(includes adjusted short / long deposits, <i><u>not</u></i> leverage)</span>' : '' );


		// BTC / PAIRING portfolio stats output
		echo '<div class="portfolio_summary"><span class="black">BTC Value:</span> <span class="bitcoin">Ƀ ' . $total_btc_worth . '</span>' . $leverage_text1 . '</div>';
		
		echo '<div class="portfolio_summary"><span class="black">'.strtoupper($app_config['general']['btc_primary_currency_pairing']).' Value:</span> ' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format($total_primary_currency_worth, 2, '.', ',') . $leverage_text2 . '</div>';
		
		echo ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? '<div class="portfolio_summary"><span class="black">Leverage Included: </span>' . ( $total_primary_currency_worth_inc_leverage >= 0 ? '<span class="green">' : '<span class="red">-' ) . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . $parsed_total_primary_currency_worth_inc_leverage . '</span>' . '</div>' : '' );
	



		// Now that BTC / PAIRING summaries have margin leverage stats NEXT TO THEM (NOT in the actual BTC / PAIRING amounts, for UX's sake), 
		// we move on to the gain / loss stats WHERE IT IS FEASIBLE ENOUGH TO INCLUDE !BASIC! MARGIN LEVERAGE DATA SUMMARY (where applicable)
		if ( $purchase_price_added == 1 && is_numeric($gain_loss_total) == TRUE ) {
			
			
     	// Gain / loss percent (!MUST BE! absolute value)
      $percent_difference_total = abs( ($total_primary_currency_worth_if_purchase_price - $original_worth) / abs($original_worth) * 100 );
          
		
		// Notice that we include margin leverage in gain / loss stats (for UX's sake, too confusing to included in anything other than gain / loss stats)
		$leverage_text2 = ( $leverage_added == 1 ? ', includes leverage' : '' );
		
		
		echo '<div class="portfolio_summary"><span class="black">' . ( $gain_loss_total >= 0 ? 'Gain:</span> <span class="green">+' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] : 'Loss:</span> <span class="red">' ) . $parsed_gain_loss_total . ' (' . ( $gain_loss_total >= 0 ? '+' : '-' ) . number_format($percent_difference_total, 2, '.', ',') . '%' . $leverage_text2 . ')</span>';
		
		?> 
		
		<img id='portfolio_gain_loss' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> </div>
		
		
	 <script>
	 
		document.title = '<?=( $gain_loss_total >= 0 ? '+' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] : '' )?><?=$parsed_gain_loss_total?> (<?=( $gain_loss_total >= 0 ? '+' : '-' )?><?=number_format($percent_difference_total, 2, '.', ',')?>%)';
	
		
			var gain_loss_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;">Gain / Loss Stats:</h5>'
			
			<?php
					
					// Sort descending gains
					$columns_array = array_column($coin_stats_array, 'gain_loss_total');
					array_multisort($columns_array, SORT_DESC, $coin_stats_array);
					
				foreach ( $coin_stats_array as $key => $value ) {
					
						$parsed_gain_loss = preg_replace("/-/", "-" . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']], number_format( $value['gain_loss_total'], 2, '.', ',' ) );
						
						if ( $value['coin_leverage'] >= 2 ) {
						$parsed_total_with_leverage = number_format( ( $value['coin_worth_total'] + $value['gain_loss_only_leverage'] ) , 2, '.', ',' );
						}
						
					
						if ( number_to_string($value['coin_paid']) >= 0.00000001 ) {
							
							
				?>
			+'<p class="coin_info"><span class="yellow"><?=$value['coin_symbol']?>:</span> <span class="<?=( $value['gain_loss_total'] >= 0 ? 'green_bright">+' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] : 'red_bright">' )?><?=$parsed_gain_loss?> (<?=( $value['gain_loss_total'] >= 0 ? '+' : '' )?><?=number_format($value['gain_loss_percent_total'], 2, '.', ',')?>%<?=( $value['coin_leverage'] >= 2 ? ', ' . $value['coin_leverage'] . 'x ' . $value['selected_margintype'] : '' )?>)</span></p>'
			
			<?php
						}
							
				}
			 ?>
				
			+'<p class="coin_info balloon_notation"><span class="yellow">*<?=( $leverage_added == 1 ? 'Leverage / ' : '' )?>Gain / Loss stats only include assets where you have set the<br />"Average Paid (per-token)" value on the Update Assets page.</span></p>';
		
		
			$('#portfolio_gain_loss').balloon({
			html: true,
			position: "right",
			contents: gain_loss_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
		<?php
		}
		
		if ( number_to_string($bitcoin_dominance) >= 0.01 || number_to_string($ethereum_dominance) >= 0.01 || number_to_string($altcoin_dominance) >= 0.01 ) {
			
			
			if ( number_to_string($bitcoin_dominance) >= 0.01 ) {
			$bitcoin_dominance_text = number_format($bitcoin_dominance, 2, '.', ',') . '% Bitcoin';
			$seperator_btc = ( number_format($bitcoin_dominance, 2, '.', '') < 100 ? ' / ' : '' );
			}
			
			if ( number_to_string($ethereum_dominance) >= 0.01 ) {
			$ethereum_dominance_text = number_format($ethereum_dominance, 2, '.', ',') . '% Ethereum';
			$seperator_eth = ( number_format($bitcoin_dominance, 2, '.', '') + number_format($ethereum_dominance, 2, '.', '') < 100 ? ' / ' : '' );
			}
			
			if ( number_to_string($altcoin_dominance) >= 0.01 ) {
			$altcoin_dominance_text = number_format($altcoin_dominance, 2, '.', ',') .'% Alt(s)';
			}
			
			
			echo '<div class="portfolio_summary"><span class="black">Balance:</span> ' . $bitcoin_dominance_text . $seperator_btc . $ethereum_dominance_text . $seperator_eth . $altcoin_dominance_text;
			
			
		?>
		
		<img id='balance_stats' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> </div>
		
	 <script>
	
			<?php
					
				// Sort by most dominant first
				arsort($btc_worth_array);
					
				foreach ( $btc_worth_array as $key => $value ) {
					
					$balance_stats = ( $value / $total_btc_worth_raw ) * 100;
					
						if ( $balance_stats >= 0.01 ) {
						$balance_stats_encoded .= '&' . strtolower($key) . '=' . number_format($balance_stats, 2, '.', ',');
						}
							
				}
				
			 ?>
			
		
			$('#balance_stats').balloon({
			html: true,
			position: "right",
			contents: ajax_placeholder(30, 'Loading Data...'),
  			url: 'app-lib/js/chart-js.php?type=balance_stats&leverage_added=<?=$leverage_added?>&short_added=<?=$short_added?><?=$balance_stats_encoded?>',
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
	
		 </script>
		 
		<?php
		}
	
	echo '<div class="portfolio_summary"><span class="black">(Bitcoin is trading @ ' . $app_config['power_user']['bitcoin_currency_markets'][$app_config['general']['btc_primary_currency_pairing']] . number_format( $selected_btc_primary_currency_value, 2, '.', ',') . ' on ' . snake_case_to_name($app_config['general']['btc_primary_exchange']) . ')</span></div>';

			
		if ( $short_added == 1 ) {
		?>	
		<div class="portfolio_summary" style='margin-top: 15px;'><span class="short">★ Adjusted short trade deposit(s) (leverage <u>not</u> included)</span></div>		
		<?php
		}
		?>
		
		
</div><!-- Summary END -->



		
	<!-- System stats (if enabled and logged in) -->
	<div id='system_stats' class='align_left'>
	
	<?php
			// If hardware / software stats are enabled, display the os / hardware / load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / software stats
    		if ( isset($_SESSION['admin_logged_in']) ) {
    ?>
	
		<fieldset><legend> <strong class="bitcoin">System Information</strong> </legend>
    		
    <div id='portfolio_render_system_stats'><?php
    			
    		// System data
    		$system_load = $system_info['system_load'];
    		$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
    		$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
    		$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);
         
			$system_free_space_mb = in_megabytes($system_info['free_partition_space'])['in_megs'];
         
			$portfolio_cache_size_mb = in_megabytes($system_info['portfolio_cache'])['in_megs'];
    		
    		$system_memory_total_mb = in_megabytes($system_info['memory_total'])['in_megs'];
    		
    		$system_memory_free_mb = in_megabytes($system_info['memory_free'])['in_megs'];
    		
  			// Percent difference (!MUST BE! absolute value)
         $memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
         $memory_percent_free = round( 100 - $memory_percent_free, 2);
	
	
    		// Output
    		if ( isset($system_info['operating_system']) ) {
    		echo '<span class="bitcoin"><b>Operating System:</b></span> <br /><span class="blue"> '.$system_info['operating_system'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['model']) || isset($system_info['hardware']) ) {
    			
    			if ( isset($system_info['model']) ) {
    			echo '<span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$system_info['model'].( isset($system_info['hardware']) ? ' ('.$system_info['hardware'].')' : '' ).'</span> <br />';
    			}
    			else {
    			echo '<span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$system_info['hardware'].'</span> <br />';
    			}
    		
    		}
    		
    		if ( isset($system_info['model_name']) ) {
    		echo '<span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$system_info['model_name'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['uptime']) ) {
    		echo '<span class="bitcoin"><b>Uptime:</b></span> <span class="'.( preg_match("/0 days, 0 hours/i", $system_info['uptime']) ? 'red' : 'green' ).'"> '.$system_info['uptime'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['system_load']) ) {
    		echo '<span class="bitcoin"><b>Load:</b></span> <span class="'.( $system_load > 2 ? 'red' : 'green' ).'"> '.$system_info['system_load'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['system_temp']) ) {
    		echo '<span class="bitcoin"><b>Temperature:</b></span> <span class="'.( $system_temp > 79 ? 'red' : 'green' ).'"> '.$system_info['system_temp'].'</span> <br />';
    		}
    		
    		if ( isset($system_info['memory_used_megabytes']) ) {
    		echo '<span class="bitcoin"><b>Used Memory (*not* including buffers / cache):</b></span> <br /><span class="'.( $system_info['memory_used_percent'] > 91 ? 'red' : 'green' ).'"> '.round($system_info['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($system_info['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$system_info['memory_used_percent'].'%)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['free_partition_space']) ) {
    		echo '<span class="bitcoin"><b>Free Disk Space:</b></span> <span class="'.( $system_free_space_mb < 500 ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['portfolio_cache']) ) {
    		echo '<span class="bitcoin"><b>Portfolio Cache Size:</b></span> <span class="'.( $portfolio_cache_size_mb > 10000 ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> <br />';
    		}
    		
    		if ( isset($system_info['software']) ) {
    		echo '<span class="bitcoin"><b>Software:</b></span> <span class="blue"> '.$system_info['software'].'</span> <br />';
    		}
    		
    		
    		?></div>
    		
    		<span class="bitcoin"><b>Other:</b></span>&nbsp; 
    		
    		<b><a href="javascript: return false;" class="show_system_charts blue" title="View System Charts">System Charts</a></b>&nbsp;&nbsp;&nbsp; 
    		

    		<b><a href="javascript: return false;" class="show_visitor_stats blue" title="View Visitor Statistics">Visitor Stats</a></b>&nbsp;&nbsp;&nbsp; 
    		

    		<b><a href="javascript: return false;" class="show_logs blue" title="View Logs">App Logs</a></b>
    		
    		<br /><br />
 
    		
		</fieldset>
		
    		<?php
    		}
			?>


	</div>
<br class='clear_both' />


	<?php	
	// End outputting results
	}
	
	if ( $assets_added || $assets_watched ) {
	?>
	
	<style>
	.show_coin_values, #system_stats {
	display: block;
	}
	</style>
	
	<?php
	}
	else {
	?>
	
	<div class='align_center' style='min-height: 100px;'>
	
		<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the "Update Assets" page).</p>
	</div>
	
	<?php
	}
	
	
	if ( $_COOKIE['notes_reminders'] != '' ) {
	?>
	
	<div style='margin-top: 10px; height: auto;'>
	
		<form action='<?=start_page($_GET['start_page'])?>' method='post'>
	
		<b class='black'>Trading Notes / Reminders:</b><br />
	
		<textarea data-autoresize name='notes_reminders' id='notes_reminders' style='height: auto; width: 100%;'><?=$_COOKIE['notes_reminders']?></textarea><br />
	
		<input type='hidden' name='update_notes' id='update_notes' value='1' />
		<input type='submit' value='Save Updated Notes' />
	
		</form>
		
	</div>
	
	<?php
	}
	?>
   
   
	<?php
		// If hardware / software stats are enabled, display the charts when designated link is clicked (in a modal)
    	if ( isset($_SESSION['admin_logged_in']) ) {
    ?>
	
	<div id="show_system_charts">
	
		
		<h3>System Charts</h3>
	
				<span style='margin-top: 25px; margin-right: 85px;' class='red countdown_notice'></span>
	
	<div id='portfolio_render_system_stats2' style='margin-bottom: 30px;'></div>
	
	<script>
	// Mirror portfolio page system stats summary with javascript (without the wrapper that makes the text small)
	$('#portfolio_render_system_stats2').html( $('#portfolio_render_system_stats').html() );
	</script>
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div class='chart_wrapper' id='system_stats_chart_1'><span class='chart_loading' style='color: <?=$app_config['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=system&key=1");
	});
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div class='chart_wrapper' id='system_stats_chart_2'><span class='chart_loading' style='color: <?=$app_config['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span></div>
	
	<script>
	
	$(document).ready(function() {
    $.getScript("app-lib/js/chart-js.php?type=system&key=2");
	});
	
	</script>
		
	</div>
	
	
	<script>
	$('.show_system_charts').modaal({
		fullscreen: true,
		content_source: '#show_system_charts'
	});
	</script>
	
	
	<div id="show_visitor_stats">
	
		
		<h3>Visitor Stats</h3>
	
				<span style='margin-top: 25px; margin-right: 85px;' class='red countdown_notice'></span>
			
	
	<p> &nbsp; </p>
	
	<p> Coming Soon&trade; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
	
	<p> &nbsp; </p>
		
	</div>
	
	
	<script>
	$('.show_visitor_stats').modaal({
		fullscreen: true,
		content_source: '#show_visitor_stats'
	});
	</script>
	
	
	
	<div id="show_logs">
	
		
		<h3>App Logs</h3>
	
				<span style='margin-top: 25px; margin-right: 85px;' class='red countdown_notice'></span>
			
	
	
		<p>Error / debugging logs will automatically display here, if they exist (primary error log always shows, even if empty). <span class='bitcoin'>All log timestamps are UTC time</span> (Coordinated Universal Time). </p>
		
		<p><span class='bitcoin'>Current UTC time:</span> <span class='utc_timestamp red'></span></p>
		
		<p class='bitcoin'>Log format: </p>
		
	   <!-- Looks good highlighted as: less, yaml  -->
	   <pre class='rounded' style='display: inline-block;<?=( is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>[UTC timestamp] runtime_mode => error_type: error_message; [ (tracing if log verbosity set to verbose) ]</code></pre>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='errors_log_space' value='1' onchange="system_logs('errors_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='errors_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('errors_log', 'errors_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('errors_log');">Refresh</button> 
	        
	        &nbsp; <span id='errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='errors_log'></code></pre>
			  
			  <script>
			  system_logs('errors_log');
			  </script>
		
	    </fieldset>
				
	<?php
	if ( is_readable($base_dir . '/cache/logs/smtp_errors.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_errors_log_space' value='1' onchange="system_logs('smtp_errors_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='smtp_errors_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_errors_log', 'smtp_errors_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_errors_log');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_errors_log'></code></pre>
			  
			  <script>
			  system_logs('smtp_errors_log');
			  </script>
		
	    </fieldset>
	<?php
	}
	if ( $app_config['developer']['debug_mode'] != 'off' || is_readable($base_dir . '/cache/logs/debugging.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Debugging Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='debugging_log_space' value='1' onchange="system_logs('debugging_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='debugging_log_lines' value='100' maxlength="4" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('debugging_log', 'debugging_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('debugging_log');">Refresh</button> 
	        
	        &nbsp; <span id='debugging_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='debugging_log'></code></pre>
			  
			  <script>
			  system_logs('debugging_log');
			  </script>
		
	    </fieldset>
	    
	<?php
	}
	?>
	    
			    
		
	</div>
	
	
	<script>
	$('.show_logs').modaal({
		fullscreen: true,
		content_source: '#show_logs'
	});
	</script>
	
	<?php
		}
    ?>
	
	
	
                            
                        