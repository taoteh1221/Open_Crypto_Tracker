<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */


?>

    
			<span class='bitcoin'><b>(<?=$pt_conf['power']['last_trade_cache_time']?> minute cache)</b></span>
			<?php
			if ( sizeof($sel_opt['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
				
				if ( $sel_opt['alert_percent'][4] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $sel_opt['alert_percent'][4] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
				$text_mcap_trend = $sel_opt['alert_percent'][3];
				
				$text_mcap_trend = ucwords(preg_replace("/hour/i", " hour", $text_mcap_trend));
				
				$text_mcap_trend = ucwords(preg_replace("/day/i", " day", $text_mcap_trend));
				
				
				if ( $sel_opt['alert_percent'][2] == 'gain' ) {
				$alert_filter = '<span>+</span>';
				$alert_filter_css = 'green';
				}
				elseif ( $sel_opt['alert_percent'][2] == 'loss' ) {
				$alert_filter = '<span>-</span>';
				$alert_filter_css = 'orange';
				}
				elseif ( $sel_opt['alert_percent'][2] == 'both' ) {
				$alert_filter = '<img src="templates/interface/media/images/plus-minus.png" height="13" alt="" style="position: relative; vertical-align:middle; bottom: 2px;" />';
				$alert_filter_css = 'blue';
				}
				
				
			?>
			  &nbsp; &nbsp; <span class='<?=$alert_filter_css?>' style='font-weight: bold;'><?=$visual_audio_alerts?> alerts (<?=ucfirst($pt_conf['gen']['prim_mcap_site'])?> <?=$text_mcap_trend?> <?=$alert_filter?><?=$sel_opt['alert_percent'][1]?>%)</span>
			<?php
			}
			
			// Warning (minimal, just as link title on the 'refresh' link) if price data caching set too low
			if ( $pt_conf['power']['last_trade_cache_time'] < 4 ) {
			$refresh_link_title = 'Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config GENERAL section is set to '. $pt_conf['power']['last_trade_cache_time'] . ' minute(s). A setting of 4 or higher assists in avoiding temporary IP blocking / throttling by exchanges.';
			}
			else {
			$refresh_link_title = 'The current real-time exchange data re-cache (refresh from live data instead of cached data) setting in the Admin Config GENERAL section is set to '. $pt_conf['power']['last_trade_cache_time'] . ' minute(s).';
			}
			
			?>  &nbsp; &nbsp; &nbsp; <a href='javascript:app_reloading_placeholder();app_reload();' style='font-weight: bold;' title='<?=$refresh_link_title?>'>Refresh</a>
			
			 &nbsp;<select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc).' class='browser-default custom-select' name='select_auto_refresh' id='select_auto_refresh' onchange='
			 window.reload_time = this.value;
			 auto_reload();
			 '>
				<option value='0'> Manually </option>
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


<table border='0' cellpadding='10' cellspacing='0' id="coins_table">
 <thead>
    <tr>
<th class='border_lt'>Rank</th>
<th class='border_lt blue al_right'><span>Asset Name</span></th>
<th class='border_t'>Unit Value</th>
<th class='border_lt blue'>Exchange</th>
<th class='border_t al_right'>Trade Value</th>
<th class='border_t blue'>Market</th>
<th class='border_t'>24 Hour Volume</th>
<th class='border_lt blue al_right'>Holdings</th>
<th class='border_t'>Ticker</th>
<th class='border_t blue'>Holdings Value</th>
<th class='border_rt blue'>Subtotal</th>
    </tr>
  </thead>
 <tbody>
 
<?php

	if ( $_POST['submit_check'] == 1 ) {
	
		
		if ( is_array($_POST) ) {
			
		$btc_market = ($_POST['btc_market'] - 1);
									
									foreach ( $_POST as $key => $val ) {
								
										if ( preg_match("/_amount/i", $key) ) {
										
										$held_amount = $pt_var->rem_num_format($val);
										$asset_symb = strtoupper(preg_replace("/_amount/i", "", $key));
										$sel_pairing = ($_POST[strtolower($asset_symb).'_pairing']);
										// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$sel_market = ($_POST[strtolower($asset_symb).'_market'] - 1); 
										$purchase_price = $pt_var->rem_num_format($_POST[strtolower($asset_symb).'_paid']);
										$leverage_level = $_POST[strtolower($asset_symb).'_leverage'];
										$sel_margintype = $_POST[strtolower($asset_symb).'_margintype'];
												
						
										// Render the row of coin data in the UI
										$pt_asset->ui_asset_row($pt_conf['assets'][$asset_symb]['name'], $asset_symb, $held_amount, $pt_conf['assets'][$asset_symb]['pairing'][$sel_pairing], $sel_pairing, $sel_market, $purchase_price, $leverage_level, $sel_margintype);
										
										
										
											if ( $held_amount >= 0.00000001 ) {
												
											$assets_added = 1;
											
											
												if ( $purchase_price >= 0.00000001 ) {
												$purchase_price_added = 1;
												}
												
												if ( $leverage_level >= 2 ) {
												$leverage_added = 1;
												}
												
												if ( $leverage_level >= 2 && $sel_margintype == 'short' ) {
												$short_added = 1;
												}
											
											
											}
											elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
											$assets_watched = 1;
											}
										
											if ( $held_amount > 0.00000000 ) {
											$asset_tracking[] = $asset_symb; // For only showing chosen assets in chart stats etc
											}
										
										}
									
									
									}
		
		}
	
	}
	elseif ( $run_csv_import == 1 ) {
	
		
		if ( is_array($csv_file_array) ) {
			
									
				foreach( $csv_file_array as $key => $val ) {
								
									$run_csv_import = 1;
	        
	        		
	        			if ( $pt_var->rem_num_format($val[1]) > 0.00000000 ) {  // Show even if decimal is off the map, just for UX purposes tracking token price only
	        			
	        			$val[5] = ( $pt_var->whole_int( trim($val[5]) ) != false ? trim($val[5]) : 1 ); // If market ID input is corrupt, default to 1
	        			$val[3] = ( $pt_var->whole_int( trim($val[3]) ) != false ? trim($val[3]) : 0 ); // If leverage amount input is corrupt, default to 0
	        			
										$held_amount = $pt_var->rem_num_format( trim($val[1]) );
										$asset_symb = strtoupper( trim($val[0]) );
										$sel_pairing = strtolower( trim($val[6]) );
										// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$sel_market = ( $val[5] != NULL ? $val[5] - 1 : 1 ); 
										$purchase_price = $pt_var->rem_num_format($val[2]);
										$leverage_level = $val[3];
										$sel_margintype = strtolower( trim($val[4]) );
										
											
											// Check pairing value
											foreach ( $pt_conf['assets'][$asset_symb]['pairing'] as $pairing_key => $unused ) {
					 						$ploop = 0;
					 						
					 							// Use first pairing key from coins config for this asset, if no pairing value was set properly in the spreadsheet
					 							if ( $ploop == 0 ) {
					 								
					 								if ( $sel_pairing == NULL || !$pt_conf['assets'][$asset_symb]['pairing'][$sel_pairing] ) {
					 								$sel_pairing = $pairing_key;
					 								}
					 							
					 							}
											
											$ploop = $ploop + 1;
											}
											
											
											// Check margin type value
											if ( $sel_margintype != 'long' && $sel_margintype != 'short' ) {
											$sel_margintype = 'long';
											}
											
						
						
										// Render the row of coin data in the UI
										$pt_asset->ui_asset_row($pt_conf['assets'][$asset_symb]['name'], $asset_symb, $held_amount, $pt_conf['assets'][$asset_symb]['pairing'][$sel_pairing], $sel_pairing, $sel_market, $purchase_price, $leverage_level, $sel_margintype);
										
										
										
											if ( $held_amount >= 0.00000001 ) {
												
											$assets_added = 1;
											
											
												if ( $purchase_price >= 0.00000001 ) {
												$purchase_price_added = 1;
												}
												
												if ( $leverage_level >= 2 ) {
												$leverage_added = 1;
												}
												
												if ( $leverage_level >= 2 && $sel_margintype == 'short' ) {
												$short_added = 1;
												}
											
											
											}
											elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
											$assets_watched = 1;
											}
										
											if ( $held_amount > 0.00000000 ) {
											$asset_tracking[] = $asset_symb; // For only showing chosen assets in chart stats etc
											}
											
										
										
										
	       		 	}
									
									
				}
		
		}
	
	}
	elseif ( $_COOKIE['coin_amounts'] && $_COOKIE['coin_markets'] && $_COOKIE['coin_pairings'] ) {
	
	
		$all_cookies_data_array = array('');
		
	
	$all_asset_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
	
		if ( is_array($all_asset_markets_cookie_array) ) {
			
					foreach ( $all_asset_markets_cookie_array as $asset_markets ) {
									
					$single_asset_market_cookie_array = explode("-", $asset_markets);
					
					$asset_symb = strtoupper(preg_replace("/_market/i", "", $single_asset_market_cookie_array[0]));
					
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_market'] = $single_asset_market_cookie_array[1];
					
					}
					
		}
	
	
	$all_asset_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
	
		if ( is_array($all_asset_pairings_cookie_array) ) {
			
					foreach ( $all_asset_pairings_cookie_array as $asset_pairings ) {
									
					$single_asset_pairing_cookie_array = explode("-", $asset_pairings);
					
					$asset_symb = strtoupper(preg_replace("/_pairing/i", "", $single_asset_pairing_cookie_array[0]));
					
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_pairing'] = $single_asset_pairing_cookie_array[1];
					
					}
					
		}
	
	
	$all_asset_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	
		if ( is_array($all_asset_paid_cookie_array) ) {
			
					foreach ( $all_asset_paid_cookie_array as $asset_paid ) {
									
					$single_asset_paid_cookie_array = explode("-", $asset_paid);
					
					$asset_symb = strtoupper(preg_replace("/_paid/i", "", $single_asset_paid_cookie_array[0]));
					
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_paid'] = $single_asset_paid_cookie_array[1];
					
					}
					
		}
	
	
	$all_asset_leverage_cookie_array = explode("#", $_COOKIE['coin_leverage']);
	
		if ( is_array($all_asset_leverage_cookie_array) ) {
			
					foreach ( $all_asset_leverage_cookie_array as $asset_leverage ) {
									
					$single_asset_leverage_cookie_array = explode("-", $asset_leverage);
					
					$asset_symb = strtoupper(preg_replace("/_leverage/i", "", $single_asset_leverage_cookie_array[0]));
					
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_leverage'] = $single_asset_leverage_cookie_array[1];
					
					}
					
		}
	
	
	$all_asset_margintype_cookie_array = explode("#", $_COOKIE['coin_margintype']);
	
		if ( is_array($all_asset_margintype_cookie_array) ) {
			
					foreach ( $all_asset_margintype_cookie_array as $asset_margintype ) {
									
					$single_asset_margintype_cookie_array = explode("-", $asset_margintype);
					
					$asset_symb = strtoupper(preg_replace("/_margintype/i", "", $single_asset_margintype_cookie_array[0]));
					
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_margintype'] = $single_asset_margintype_cookie_array[1];
					
					}
					
		}
	
	
		
		
	
	$all_asset_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	
		if ( is_array($all_asset_amounts_cookie_array) ) {
			
					foreach ( $all_asset_amounts_cookie_array as $asset_amounts ) {
									
					$single_asset_amount_cookie_array = explode("-", $asset_amounts);
					
					$asset_symb = strtoupper(preg_replace("/_amount/i", "", $single_asset_amount_cookie_array[0]));
				
							if ( $asset_symb == 'BTC' && !$btc_market ) {
							$btc_market = ($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_market'] -1);
							}
	
					$all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_amount'] = $single_asset_amount_cookie_array[1];
					
					
					// Bundle all required cookie data in this final cookies parsing loop for each coin, and render the coin's data
					// We don't need $pt_var->rem_num_format() for cookie data, because it was already done creating the cookies
					$held_amount = $pt_var->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_amount']);
					$sel_pairing = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_pairing'];
					// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
					$sel_market = ($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_market'] -1);
					$purchase_price = $pt_var->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_paid']);
					$leverage_level = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_leverage'];
					$sel_margintype = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_margintype'];
					
					
					// Render the row of coin data in the UI
					$pt_asset->ui_asset_row($pt_conf['assets'][$asset_symb]['name'], $asset_symb, $held_amount, $pt_conf['assets'][$asset_symb]['pairing'][$sel_pairing], $sel_pairing, $sel_market, $purchase_price, $leverage_level, $sel_margintype);
					
					
						
						if ( $held_amount >= 0.00000001 ) {
							
						$assets_added = 1;
						
						
							if ( $purchase_price >= 0.00000001 ) {
							$purchase_price_added = 1;
							}
												
							if ( $leverage_level >= 2 ) {
							$leverage_added = 1;
							}
												
							if ( $leverage_level >= 2 && $sel_margintype == 'short' ) {
							$short_added = 1;
							}
						
						
						}
						elseif ( $held_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
						$assets_watched = 1;
						}
										
						
						if ( $held_amount > 0.00000000 ) {
						$asset_tracking[] = $asset_symb; // For only showing chosen assets in chart stats etc
						}
					
	
					}
					
					
		}
		
	}

?>

</tbody>
</table>


<?php


// Get portfolio summaries


$total_btc_worth_raw = number_format($pt_asset->bitcoin_total(), 8, '.', '');

// FOR UX-SAKE, WE CUT OFF EXTRA RIGHT SIDE ZERO DECIMALS IF VALUE IS AT LEAST A SATOSHI OR HIGHER (O.00000001),
// #BUT# IF VALUE IS LITERALLY ZERO (WATCH-ONLY, ETC), WE WANT TO SHOW THAT #CLEARLY# TO THE END USER WITH 0.00000000
$total_btc_worth = ( $total_btc_worth_raw >= 0.00000001 ? $pt_var->num_pretty($total_btc_worth_raw, 8) : '0.00000000' );

$total_prim_currency_worth = $pt_asset->coin_stats_data('coin_worth_total');

$bitcoin_dominance = $pt_var->num_to_str( ( $btc_worth_array['BTC'] / $total_btc_worth_raw ) * 100 );

$ethereum_dominance = $pt_var->num_to_str( ( $btc_worth_array['ETH'] / $total_btc_worth_raw ) * 100 );

$miscassets_dominance = $pt_var->num_to_str( ( $btc_worth_array['MISCASSETS'] / $total_btc_worth_raw ) * 100 );

$altcoin_dominance = ( $total_btc_worth_raw >= 0.00000001 ? $pt_var->num_to_str( 100 - $bitcoin_dominance - $ethereum_dominance - $miscassets_dominance ) : 0.00 );

// Remove any slight decimal over 100 (100.01 etc)
$bitcoin_dominance = $pt_var->max_100($bitcoin_dominance);
$ethereum_dominance = $pt_var->max_100($ethereum_dominance);
$miscassets_dominance = $pt_var->max_100($miscassets_dominance);
$altcoin_dominance = $pt_var->max_100($altcoin_dominance);
	
		
?>
<div class="show_asset_vals bold_1 blue"><!-- Summary START -->
<?php
		
		// Run BEFORE output of BTC / PAIRING portfolio values, to include any margin / leverage summaries in parentheses NEXT TO THEM (NOT in the actual BTC / PAIRING amounts, for UX's sake)
		if ( $purchase_price_added == 1 ) {
		    
		$gain_loss_total = $pt_asset->coin_stats_data('gain_loss_total');
		
		
        $thres_dec = $pt_gen->thres_dec($gain_loss_total, 'u'); // Units mode
		$parsed_gain_loss_total = preg_replace("/-/", "-" . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ], number_format($gain_loss_total, $thres_dec['max_dec'], '.', ',' ) );
		
		
		$original_worth = $pt_asset->coin_stats_data('coin_paid_total');
		
		$leverage_only_gain_loss = $pt_asset->coin_stats_data('gain_loss_only_leverage');
  		
		$total_prim_currency_worth_inc_leverage = $total_prim_currency_worth + $leverage_only_gain_loss;
		
		
        $thres_dec = $pt_gen->thres_dec($total_prim_currency_worth_inc_leverage, 'u'); // Units mode
  		// Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  		// We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  		// (plus sign would indicate a gain, NOT 'total worth')
		$parsed_total_prim_currency_worth_inc_leverage = preg_replace("/-/", "", number_format($total_prim_currency_worth_inc_leverage, $thres_dec['max_dec'], '.', ',' ) );
  		
		$total_prim_currency_worth_if_purchase_price = $pt_asset->coin_stats_data('coin_total_worth_if_purchase_price') + $leverage_only_gain_loss;
		
		$gain_loss_text = ( $gain_loss_total >= 0 ? 'gains' : 'losses' );
		
		}
		
	  
	  
	  // Notice that margin leverage is NOT included !!WITHIN!! BTC / PAIRING TOTALS EVER (for UX's sake, too confusing to included in anything other than gain / loss stats)
	  // We only include data in parenthesis NEXT TO THE BTC / PAIRING PORTFOLIO SUMMARIES
	  $leverage_text1 = ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? ' <p class="coin_info balloon_notation red" style="max-width: 600px; white-space: normal;"> *Includes adjusted long deposits, <i><u>not</u></i> leverage.</p>' : '' );
	  $leverage_text2 = ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? ' <p class="coin_info balloon_notation red" style="max-width: 600px; white-space: normal;"> *Includes adjusted short / long deposits, <i><u>not</u></i> leverage.</p>' : '' );



			// Crypto value(s) of portfolio
			if ( $sel_opt['show_crypto_val'][0] ) {
			?>
			
			<div class="portfolio_summary">
			
			<span class="black">Crypto Value:</span> 
			
			<?php
					
			$scan_crypto_val = array_map( array($pt_var, 'strip_brackets') , $sel_opt['show_crypto_val']); // Strip brackets
				
				// Control the ordering with corrisponding app config array (which is already ordered properly), for UX
				$loop = 0;
				foreach ( $pt_conf['power']['crypto_pairing'] as $key => $val ) {
						
						if ( in_array($key, $scan_crypto_val) ) {
						
						echo ( $loop > 0 ? ' &nbsp;/&nbsp; ' : '' );
					
							if ( $key == 'btc' ) {
							echo '<span class="'.$key.'" title="'.strtoupper($key).'">'.$val.' ' . $total_btc_worth . '</span>';
							}
							else {
							echo '<span class="'.$key.'" title="'.strtoupper($key).'">'.$val.' ' . number_format( ( $total_btc_worth_raw / $pt_asset->pairing_btc_val($key) ) , 4) . '</span>';
							}
				
						$loop = $loop + 1;
						
						}
				
				}
				
				// Delete any stale configs
				if ( $loop < 1 ) {
				?>
				<script>
				$("#show_crypto_val").val('');
				delete_cookie("show_crypto_val");
				</script>
				<?php
				}
				?>
				
			<img id="crypto_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative;" />
			
<script>
		
			var crypto_val_content = '<h5 class="yellow tooltip_title">Crypto Value</h5>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">The value of your ENTIRE portfolio, in the cryptocurrencies you selected in the "Show Crypto Value Of ENTIRE Portfolio In" setting, on the Settings page.</p>'
			
			+'<p class="coin_info bitcoin" style="max-width: 600px; white-space: normal;">If these values are skewed often, it\'s because the market(s) being used to determine the values are trading at different prices compared to the markets you chose in this interface. You can force certain markets to be used for this calculation with the "crypto_pairing_pref_markets" setting, in the Admin Config POWER USER section.</p>'
		
			+'<?=$leverage_text1?>';
		
		
			$('#crypto_val').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: crypto_val_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
			
			</div>
			
			<?php
			}
			?>
			
			
			<div class="portfolio_summary">
			
			<?php
			
		
        $thres_dec = $pt_gen->thres_dec($total_prim_currency_worth, 'u'); // Units mode
		// Fiat value of portfolio
		echo '<span class="black">' . strtoupper($pt_conf['gen']['btc_prim_currency_pairing']) . ' Value:</span> ' . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] . number_format($total_prim_currency_worth, $thres_dec['max_dec'], '.', ',');
		
		?>
		
			<img id="fiat_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" /> 

<script>


var fiat_val_content = '<h5 class="yellow tooltip_title">Primary Currency (<?=strtoupper($pt_conf['gen']['btc_prim_currency_pairing'])?>) Value</h5>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">The value of your ENTIRE portfolio, based off your selected primary currency (<?=strtoupper($pt_conf['gen']['btc_prim_currency_pairing'])?>), in the "Primary Currency Market" setting, on the Settings page.</p>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">Selected Primary Currency Market: <span class="yellow">BTC / <?=strtoupper($pt_conf['gen']['btc_prim_currency_pairing'])?> @ <?=$pt_gen->key_to_name($pt_conf['gen']['btc_prim_exchange'])?> (<?=$pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ]?><?=number_format( $sel_opt['sel_btc_prim_currency_val'], 0, '.', ',')?>)</span></p>'
		
			+'<?=$leverage_text2?>';
		
		
		
			$('#fiat_val').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: fiat_val_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
		
			</div>
		
		<?php
		
		// If using margin leverege anywhere
		echo ( $purchase_price_added == 1 && $leverage_added == 1 && is_numeric($gain_loss_total) == TRUE ? '<div class="portfolio_summary"><span class="black">Leverage Included: </span>' . ( $total_prim_currency_worth_inc_leverage >= 0 ? '<span class="green">' : '<span class="red">-' ) . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] . $parsed_total_prim_currency_worth_inc_leverage . '</span></div>' : '' );
	

		// Now that BTC / PAIRING summaries have margin leverage stats NEXT TO THEM (NOT in the actual BTC / PAIRING amounts, for UX's sake), 
		// we move on to the gain / loss stats WHERE IT IS FEASIBLE ENOUGH TO INCLUDE !BASIC! MARGIN LEVERAGE DATA SUMMARY (where applicable)
		if ( $purchase_price_added == 1 && is_numeric($gain_loss_total) == TRUE ) {
			
			
     	// Gain / loss percent (!MUST BE! absolute value)
        $percent_difference_total = abs( ($total_prim_currency_worth_if_purchase_price - $original_worth) / abs($original_worth) * 100 );
          
		
		// Notice that we include margin leverage in gain / loss stats (for UX's sake, too confusing to included in anything other than gain / loss stats)
		$leverage_text3 = ( $leverage_added == 1 ? '<p class="coin_info balloon_notation red" style="max-width: 600px; white-space: normal;"> *Includes leverage.</p>' : '' );
	
	?>
	
			<div class="portfolio_summary">
	
	<?php
		
        $thres_dec = $pt_gen->thres_dec($percent_difference_total, 'p'); // Percentage mode
		echo '<span class="black">' . ( $gain_loss_total >= 0 ? 'Gain:</span> <span class="green">+' . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] : 'Loss:</span> <span class="red">' ) . $parsed_gain_loss_total . ' (' . ( $gain_loss_total >= 0 ? '+' : '-' ) . number_format($percent_difference_total, $thres_dec['max_dec'], '.', ',') . '%' . ')</span>';
		
		?> 
		
			<img id='portfolio_gain_loss' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
			
	 <script>
	 
		document.title = '<?=( $gain_loss_total >= 0 ? '+' . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] : '' )?><?=$parsed_gain_loss_total?> (<?=( $gain_loss_total >= 0 ? '+' : '-' )?><?=number_format($percent_difference_total, $dec, '.', ',')?>%)';
	
		
			var gain_loss_content = '<h5 class="yellow tooltip_title">Gain / Loss Stats</h5>'
			
			<?php
					
					// Sort descending gains
					$columns_array = array_column($asset_stats_array, 'gain_loss_total');
					array_multisort($columns_array, SORT_DESC, $asset_stats_array);
					
				foreach ( $asset_stats_array as $key => $val ) {
				    
						
						if ( $val['coin_leverage'] >= 2 ) {
						$parsed_total_with_leverage = number_format( ( $val['coin_worth_total'] + $val['gain_loss_only_leverage'] ) , 2, '.', ',' );
						}
						
					
						if ( $pt_var->num_to_str($val['coin_paid']) >= 0.00000001 ) {
							
                        $thres_dec_1 = $pt_gen->thres_dec($val['gain_loss_total'], 'u'); // Units mode
						$parsed_gain_loss = preg_replace("/-/", "-" . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ], number_format( $val['gain_loss_total'], $thres_dec_1['max_dec'], '.', ',' ) );
		
		
                        $thres_dec_2 = $pt_gen->thres_dec($val['gain_loss_percent_total'], 'p'); // Percentage mode
				        ?>
			+'<p class="coin_info"><span class="yellow"><?=$val['coin_symb']?>:</span> <span class="<?=( $val['gain_loss_total'] >= 0 ? 'green">+' . $pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ] : 'red">' )?><?=$parsed_gain_loss?> (<?=( $val['gain_loss_total'] >= 0 ? '+' : '' )?><?=number_format($val['gain_loss_percent_total'], $thres_dec_2['max_dec'], '.', ',')?>%<?=( $val['coin_leverage'] >= 2 ? ', ' . $val['coin_leverage'] . 'x ' . $val['selected_margintype'] : '' )?>)</span></p>'
			
			    <?php
						}
							
				}
			    ?>
		
			+'<?=$leverage_text3?>'
				
			+'<p class="coin_info balloon_notation yellow">*<?=( $leverage_added == 1 ? 'Leverage / ' : '' )?>Gain / Loss stats only include assets where you have set the<br />"Average Paid (per-token)" value on the Update page.</p>';
		
		
			$('#portfolio_gain_loss').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: gain_loss_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
			
			</div>
		 
		<?php
		}
		
		if ( $pt_var->num_to_str($bitcoin_dominance) >= 0.01 || $pt_var->num_to_str($ethereum_dominance) >= 0.01 || $pt_var->num_to_str($miscassets_dominance) >= 0.01 || $pt_var->num_to_str($altcoin_dominance) >= 0.01 ) {

			
			if ( $pt_var->num_to_str($bitcoin_dominance) >= 0.01 ) {
			$bitcoin_dominance_text = number_format($bitcoin_dominance, 2, '.', ',') . '% BTC';
			$seperator_btc = ( $pt_var->num_to_str($bitcoin_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $pt_var->num_to_str($ethereum_dominance) >= 0.01 ) {
			$ethereum_dominance_text = number_format($ethereum_dominance, 2, '.', ',') . '% ETH';
			$seperator_eth = ( $pt_var->num_to_str($bitcoin_dominance) + $pt_var->num_to_str($ethereum_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $pt_var->num_to_str($miscassets_dominance) >= 0.01 ) {
			$miscassets_dominance_text = number_format($miscassets_dominance, 2, '.', ',') . '% <span class="btc_prim_currency_pairing">' . strtoupper($pt_conf['gen']['btc_prim_currency_pairing']) . '</span>';
			$seperator_miscassets = ( $pt_var->num_to_str($bitcoin_dominance) + $pt_var->num_to_str($ethereum_dominance) + $pt_var->num_to_str($miscassets_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $pt_var->num_to_str($altcoin_dominance) >= 0.01 ) {
			$altcoin_dominance_text = number_format($altcoin_dominance, 2, '.', ',') .'% Alt(s)';
			}
		
		?>
		 
		 	<div class="portfolio_summary">
		
		<?php
			
			echo '<span class="black">Balance:</span> ' . $bitcoin_dominance_text . $seperator_btc . $ethereum_dominance_text . $seperator_eth . $miscassets_dominance_text . $seperator_miscassets . $altcoin_dominance_text;
			
			
		?>
		
			<img id='balance_stats' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative;' /> 
		
	 <script>
	
			<?php
					
				// Sort by most dominant first
				arsort($btc_worth_array);
					
				foreach ( $btc_worth_array as $key => $val ) {
					
					if ( $key == 'MISCASSETS' ) {
					$key = 'Misc. ' . strtoupper($pt_conf['gen']['btc_prim_currency_pairing']);
					}
					
					// Remove any slight decimal over 100 (100.01 etc)
					$balance_stats = $pt_var->max_100( ( $val / $total_btc_worth_raw ) * 100 );
					
						if ( $balance_stats >= 0.01 ) {
						$balance_stats_encoded .= '&' . urlencode($key) . '=' . urlencode( number_format($balance_stats, 2, '.', ',') );
						}
							
				}
				
			 ?>
			
		
			$('#balance_stats').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: ajax_placeholder(30, 'center', 'Loading Data...'),
  			url: 'ajax.php?type=chart&mode=asset_balance&leverage_added=<?=$leverage_added?>&short_added=<?=$short_added?><?=$balance_stats_encoded?>',
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "999",
					textAlign: "left"
					}
			});
	
		 </script>
		
			</div>
		
		 
		<?php
		}
		?>
		
	
<!-- START 'view more stats' -->

	<div class="portfolio_summary">

			<b><a href="javascript: return false;" class="show_portfolio_stats blue" title="View More Portfolio Stats">View More Stats</a></b>

	</div>
		

	<!-- START MORE PORTFOLIO STATS MODAL -->
	<div id="show_portfolio_stats">
	
	<?php
	
	foreach ( $asset_tracking as $activated_plot ) {
	$plot_conf .= $activated_plot . '|';
	}
	
	$plot_conf = urlencode( rtrim($plot_conf,'|') );
	
	?>
		
		<h3 style='display: inline;'>More Portfolio Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice'></span>
	
	<br clear='all' />
	
	<br clear='all' />
	
  	<?php
  	// Performance chart START (requires price charts)
	if ( $pt_conf['gen']['asset_charts_toggle'] == 'on' ) {
	?>
	
<fieldset class='subsection_fieldset'>
	<legend class='subsection_legend'> <b>Asset Performance Comparison</b> </legend>
		    
	<p class='bitcoin' style='font-weight: bold;'>The Asset Performance Comparison chart <i>requires price charts to be enabled on the Charts page, and uses the price charts primary currency market</i> (<?=strtoupper($default_btc_prim_currency_pairing)?>) for value comparisons.</p>	
			
    <p>
    
    <?php
    
    $asset_performance_chart_defaults = explode("||", $pt_conf['power']['asset_performance_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $asset_performance_chart_defaults[0] >= 400 && $asset_performance_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_performance_chart_defaults[0] = 600;
    	}
    	
    	if ( $asset_performance_chart_defaults[1] >= 7 && $asset_performance_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_performance_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
	
    
    Time Period: <select class='browser-default custom-select' id='performance_chart_period' name='performance_chart_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -30 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($pt_conf['power']['lite_chart_day_intervals'] as $lite_chart_days) {
	?>
    <option value='<?=$lite_chart_days?>' <?=( $lite_chart_days == 'all' ? 'selected' : '' )?>> <?=$pt_gen->light_chart_time_period($lite_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
    Custom Start Date: <input type="text" id='performance_date' name='performance_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='performance_chart_height' name='performance_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_performance_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='performance_menu_size' name='performance_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_performance_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Asset Performance Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var performance_chart_width = document.getElementById('performance_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('performance_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#performance_chart div.chart_reload div.chart_reload_msg').html('Loading Asset Performance Chart...');
  
	$('#performance_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('performance_chart', 'complete', function() {
  	
	$('#performance_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#performance_chart').css('height', document.getElementById('performance_chart_height').value + 'px');
	$('#performance_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('performance_chart_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -30 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('performance_chart_period').value );
		}
	
	});
	
	var to_timestamp = ( document.getElementById('performance_date').value ? document.getElementById('performance_date').value : '1970/1/1' );
	
	date_array = to_timestamp.split('/');
	
	date_timestamp = toTimestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('performance_chart', 'resize', {
  width: '100%',
  height: document.getElementById('performance_chart_height').value
  });
  
  // 'load'
  zingchart.exec('performance_chart', 'load', {
  	dataurl: 'ajax.php?type=chart&mode=asset_performance&time_period=' + document.getElementById('performance_chart_period').value + '&start_time=' + date_timestamp + '&chart_width=' + performance_chart_width + '&chart_height=' + document.getElementById('performance_chart_height').value + '&menu_size=' + document.getElementById('performance_menu_size').value + '&plot_conf=<?=$plot_conf?>',
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="performance_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
<script>


var performance_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For Asset Performance Comparison Chart</h5>'

			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">Select the Time Period, to get finer grain details for smaller time periods.</p>'
			
			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">The "Custom Start Date" is OPTIONAL, for choosing a custom date in time the asset performance comparisions begin, starting at 0&#37; <?=strtoupper($default_btc_prim_currency_pairing)?> value increase / decrease. The Custom Start Date can only go back in time as far back as you have <?=strtoupper($default_btc_prim_currency_pairing)?> Value price charts (per asset) for the "All" chart, and only as far back as the beginning date of smaller time period charts.</p>'
			
			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in the Admin Config POWER USER section, under \'asset_performance_chart_defaults\'.</p>';
		
		
		
			$('.performance_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: performance_chart_defaults_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
  
    </p>
    
  
  <script>
	$('.datepicker').datepicker({
    dateFormat: 'yy/mm/dd',
    defaultDate: -30
	});
  </script>
  
  <style>
	.ui-datepicker .ui-datepicker-header {
		background: #808080;
	}
 </style>
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; background: #808080; border: 2px solid #918e8e; display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='performance_chart'>
	
	<span class='chart_loading' style='color: <?=$pt_conf['power']['charts_text']?>;'> &nbsp; Loading Asset Performance Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#performance_chart span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Asset Performance Chart...');
	
  
zingchart.bind('performance_chart', 'load', function() {
$("#performance_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=asset_performance&time_period=all&start_time=0&chart_height=<?=$asset_performance_chart_defaults[0]?>&menu_size=<?=$asset_performance_chart_defaults[1]?>&plot_conf=<?=$plot_conf?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('performance_chart', 'complete', function() {
	$("#performance_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#performance_chart').css('height', '<?=$asset_performance_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'performance_chart',
  	height: '<?=$asset_performance_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('performance_chart', 'label_click', function(e){
		
  	zingchart.exec('performance_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>
  
				
</fieldset>

  	<?php
	}
  	// Performance chart END
	?>
	


	
<fieldset class='subsection_fieldset'>
	<legend class='subsection_legend'> <b>Marketcap Comparison</b> </legend>
	
    <p>
    
    <?php
    
    $asset_mcap_chart_defaults = explode("||", $pt_conf['power']['asset_mcap_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $asset_mcap_chart_defaults[0] >= 400 && $asset_mcap_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_mcap_chart_defaults[0] = 600;
    	}
    	
    	if ( $asset_mcap_chart_defaults[1] >= 7 && $asset_mcap_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_mcap_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
    Type: <select class='browser-default custom-select' id='mcap_type' name='mcap_type'>
    <option value='circulating'> Circulating </option>
    <option value='total'> Total </option>
    </select>  &nbsp;&nbsp; 
    
    
    Compare Against: <select class='browser-default custom-select' id='mcap_compare_diff' name='mcap_compare_diff'>
    <option value='none'> Nothing </option>
    <?php
    foreach ( $pt_conf['assets'] as $key => $unused ) {
		
	// Consolidate function calls for runtime speed improvement
	$mcap_data = $pt_asset->mcap_data($key, 'usd'); // For marketcap bar chart, we ALWAYS force using USD
    	
    	if ( $key != 'MISCASSETS' && isset($mcap_data['rank']) ) {
   	?>
    <option value='<?=$key?>'> <?=$key?> </option>
    <?php
    	}
    }
   	?>
    </select>  &nbsp;&nbsp; 
    
    
    Chart Height: <select class='browser-default custom-select' id='marketcap_data_height' name='marketcap_data_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_mcap_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='marketcap_menu_size' name='marketcap_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_mcap_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Marketcap Comparison Chart' onclick="
  
  var marketcap_chart_width = document.getElementById('marketcap_chart').offsetWidth;
  
  // Reset any user-adjusted zoom
  zingchart.exec('marketcap_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#marketcap_chart div.chart_reload div.chart_reload_msg').html('Loading USD Marketcap Comparison Chart...');
  
	$('#marketcap_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('marketcap_chart', 'complete', function() {
  	
	$('#marketcap_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#marketcap_chart').css('height', document.getElementById('marketcap_data_height').value + 'px');
	$('#marketcap_chart').css('background', '#f2f2f2');
	
	});
	
	
  // 'resize' MUST run before 'load'
  zingchart.exec('marketcap_chart', 'resize', {
  width: '100%',
  height: document.getElementById('marketcap_data_height').value
  });
  
  // 'load'
  zingchart.exec('marketcap_chart', 'load', {
  	dataurl: 'ajax.php?type=chart&mode=marketcap_data&mcap_type=' + document.getElementById('mcap_type').value + '&mcap_compare_diff=' + document.getElementById('mcap_compare_diff').value + '&chart_width=' + marketcap_chart_width + '&chart_height=' + document.getElementById('marketcap_data_height').value + '&menu_size=' + document.getElementById('marketcap_menu_size').value + '&marketcap_site=<?=$pt_conf['gen']['prim_mcap_site']?>&plot_conf=<?=$plot_conf?>',
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="marketcap_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
<script>


var marketcap_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For USD Marketcap Comparison Chart</h5>'

			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">Marketcap Type: The \'circulating\' marketcap ONLY includes coins that are IN CIRCULATION (publicly available to transfer / trade), while the \'total\' marketcap includes ALL COINS (even those not mined yet / held by VIPs or Treasuries / etc).</p>'

			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in the Admin Config POWER USER section, under \'asset_mcap_chart_defaults\'.</p>';
		
		
		
			$('.marketcap_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: marketcap_chart_defaults_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
  
    </p>
    
  
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; background: #808080; border: 2px solid #918e8e; display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='marketcap_chart'>
	
	<span class='chart_loading' style='color: <?=$pt_conf['power']['charts_text']?>;'> &nbsp; Loading USD Marketcap Comparison Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#marketcap_chart span.chart_loading").html(' &nbsp; <img src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading USD Marketcap Comparison Chart...');
	
  
zingchart.bind('marketcap_chart', 'load', function() {
$("#marketcap_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=marketcap_data&mcap_type=circulating&mcap_compare_diff=none&chart_height=<?=$asset_mcap_chart_defaults[0]?>&menu_size=<?=$asset_mcap_chart_defaults[1]?>&marketcap_site=<?=$pt_conf['gen']['prim_mcap_site']?>&plot_conf=<?=$plot_conf?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('marketcap_chart', 'complete', function() {
	$("#marketcap_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#marketcap_chart').css('height', '<?=$asset_mcap_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'marketcap_chart',
  	height: '<?=$asset_mcap_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('marketcap_chart', 'label_click', function(e){
		
  	zingchart.exec('marketcap_chart', 'viewall', {
   graphid: 0
  	});
		
});
    
  </script>
  
				
</fieldset>


		
	
  <p> &nbsp; </p>
  
  
  	<?php
	if ( $pt_conf['gen']['asset_charts_toggle'] != 'on' ) {
	?>
	<p class='yellow'>*Some stats are not available with price charts disabled.</p>
	
  <p> &nbsp; </p>
  
  	<?php
	}
	?>
	
	</div>
	<!-- END MORE PORTFOLIO STATS MODAL -->
	
	<script>
	$('.show_portfolio_stats').modaal({
		fullscreen: true,
		content_source: '#show_portfolio_stats'
	});
	</script>
	
<!-- END 'view more stats' -->
		
		
		
		<?php
		if ( $short_added == 1 ) {
		?>	
		<div class="portfolio_summary" style='margin-top: 15px;'>
		<span class="short">★ Adjusted short trade deposit(s) (leverage <u>not</u> included)</span>
		</div>		
		<?php
		}
		?>
		
		
</div><!-- Summary END -->



		
	<!-- Admin Config - Quick Links (if we are admin logged in) -->
	<div id='admin_conf_quick_links' class='align_left'>
	
	<?php
			// If hardware / software stats are enabled, display the os / hardware / load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / software stats
    		if ( $pt_gen->admin_logged_in() ) {
    ?>
	
		<fieldset><legend> <strong class="bitcoin">Admin Config - Quick Links</strong> </legend>
    		
    		
    		<b><a id="system_stats_quick_link" href="javascript: return false;" class="show_system_stats blue" title="View System Statistics">System Stats</a></b><img id='system_stats_quick_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    		

    		<b><a href="javascript: return false;" class="show_access_stats blue" title="View Access Statistics">Access Stats</a></b>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
    		

    		<b><a href="javascript: return false;" class="show_logs blue" title="View App Logs">App Logs</a></b>
 
    		
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
	.show_asset_vals, #admin_conf_quick_links, #coins_table {
	display: block;
	}
	</style>
	
	<?php
	}
	else {
	?>
	
	<div class='align_center' style='min-height: 100px;'>
	
		<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the "Update" page).</p>
	</div>
	
	<?php
	}
	
	
	if ( $_COOKIE['notes'] != '' ) {
	?>
	
	<script>
	// WE CANNOT USE getCookie("notes") BECAUSE PHP COOKIE AUTO ENCODING / DECODING IS
	// #NOT# COMPATIBLE WITH JAVASCRIPT COOKIE ENCODING / DECODING!! (SIGH)
	var check_notes = `<?=$_COOKIE['notes']?>`; // Backtick encapsulation lets us include linebreaks in a js variable
	</script>
	
	<div style='margin-top: 10px; height: auto;'>
	
	
	<form id='download_notes' name='download_notes' method='post' target='_blank' action=''>
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	</form>
	
		<form action='<?=$pt_gen->start_page($_GET['start_page'])?>' method='post'>
	
		<b class='black'>&nbsp;Trading Notes (<a href='javascript: return false;' target='_blank' onclick='
		
		if ( check_notes != document.getElementById("notes").value ) {
		alert("You have changed your notes since you last saved them. \n\nPlease save your new notes before downloading them.");
		console.log( getCookie("notes") );
		return false;
		}
		else {
		// HELP THWART CSRF ATTACKS VIA POST METHOD (IN COMBINATION WITH THE TOKEN HASH), DATA IS SENSITIVE!
		set_target_action("download_notes", "_blank", "download.php?token=<?=$pt_gen->nonce_digest('download')?>&notes=1");
		document.download_notes.submit(); // USE NON-JQUERY METHOD SO "APP LOADING..." DOES #NOT# SHOW
		}
		
		' title='Download your trading notes to your computer.'>download</a>):</b><br />
	
		<textarea data-autoresize name='notes' id='notes' style='height: auto; width: 100%;'><?=$_COOKIE['notes']?></textarea>
		<br />
	
		<input type='hidden' name='update_notes' id='update_notes' value='1' />
		<input type='submit' value='Save Updated Notes' />
	
		</form>
		
	</div>
	
	<?php
	}
	?>
   
   
	<?php
		// If hardware / software stats are enabled, display the charts when designated link is clicked (in a modal)
    	if ( $pt_gen->admin_logged_in() ) {
    ?>
	
	<div id="show_system_stats">
	
		
		<h3 style='display: inline;'>System Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	<div id='portfolio_render_system_stats' style='margin-bottom: 30px;'>
	
	<?php
    			
    		// System data
    		$system_load = $system_info['system_load'];
    		$system_load = preg_replace("/ \(15 min avg\)(.*)/i", "", $system_load);
    		$system_load = preg_replace("/(.*)\(5 min avg\) /i", "", $system_load); // Use 15 minute average
    		
    		$system_temp = preg_replace("/° Celsius/i", "", $system_info['system_temp']);
         
			$system_free_space_mb = $pt_gen->in_megabytes($system_info['free_partition_space'])['in_megs'];
         
			$portfolio_cache_size_mb = $pt_gen->in_megabytes($system_info['portfolio_cache'])['in_megs'];
    		
    		$system_memory_total_mb = $pt_gen->in_megabytes($system_info['memory_total'])['in_megs'];
    		
    		$system_memory_free_mb = $pt_gen->in_megabytes($system_info['memory_free'])['in_megs'];
    		
  			// Percent difference (!MUST BE! absolute value)
         $memory_percent_free = abs( ($system_memory_free_mb - $system_memory_total_mb) / abs($system_memory_total_mb) * 100 );
         $memory_percent_free = round( 100 - $memory_percent_free, 2);
    		
    		$system_load_redline = ( $system_info['cpu_threads'] > 1 ? ($system_info['cpu_threads'] * 2) : 2 );
         
         
         if ( substr($system_info['uptime'], 0, 6) == '0 days' ) {
         $system_alerts['uptime'] = 'Low uptime';
         }
	
         if ( $system_load > $system_load_redline ) {
         $system_alerts['system_load'] = 'High CPU load';
         }
	
         if ( $system_temp > 79 ) {
         $system_alerts['system_temp'] = 'High temperature';
         }
	
         if ( $system_info['memory_used_percent'] > 91 ) {
         $system_alerts['memory_used_megabytes'] = 'High memory usage';
         }
	
         if ( $system_free_space_mb < 500 ) {
         $system_alerts['free_partition_space'] = 'High disk storage usage';
         }
	
         if ( $portfolio_cache_size_mb > 10000 ) {
         $system_alerts['portfolio_cache'] = 'High app cache disk storage usage';
         }
         
         
         // Red UI nav, with info bubble too
         if ( sizeof($system_alerts) > 0 ) {
         ?>
         <script>
         
         document.getElementById('system_stats_quick_link').classList.add("red");
         document.getElementById('system_stats_quick_link_info').style.display = 'inline';

			var system_stats_quick_link_info_content = '<h5 class="red tooltip_title">System Stats Alerts</h5>'
			
			<?php
			foreach ( $system_alerts as $alert_key => $alert_val ) {
			?>
			+'<p class="coin_info extra_margins" style="max-width: 600px; white-space: normal;"><span class="red"><?=$pt_gen->key_to_name($alert_key)?>:</span> <?=$alert_val?></p>'
			<?php
			}
			?>
			
			+'';
		
		
			$('#system_stats_quick_link_info').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: system_stats_quick_link_info_content,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
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
	
	
    		// Output
    		if ( isset($system_info['operating_system']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Operating System:</b></span> <span class="blue"> '.$system_info['operating_system'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['model']) || isset($system_info['hardware']) ) {
    			
    			if ( isset($system_info['model']) ) {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$system_info['model'].( isset($system_info['hardware']) ? ' ('.$system_info['hardware'].')' : '' ).'</span> </div>';
    			}
    			else {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$system_info['hardware'].'</span> </div>';
    			}
    		
    		}
    		
    		if ( isset($system_info['model_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$system_info['model_name'].'</span> ' . ( $system_info['cpu_threads'] > 0 ? '(' . $system_info['cpu_threads'] . ' threads)' : '' ) . ' </div>';
    		}
    		
    		if ( isset($system_info['uptime']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Uptime:</b></span> <span class="'.( isset($system_alerts['uptime']) ? 'red' : 'green' ).'"> '.$system_info['uptime'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_load']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Load:</b></span> <span class="'.( isset($system_alerts['system_load']) ? 'red' : 'green' ).'"> '.$system_info['system_load'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['system_temp']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Temperature:</b></span> <span class="'.( isset($system_alerts['system_temp']) ? 'red' : 'green' ).'"> '.$system_info['system_temp'].'</span> </div>';
    		}
    		
    		if ( isset($system_info['memory_used_megabytes']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Used Memory (*not* including buffers / cache):</b></span> <span class="'.( isset($system_alerts['memory_used_megabytes']) ? 'red' : 'green' ).'"> '.round($system_info['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($system_info['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$system_info['memory_used_percent'].'%)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['free_partition_space']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Free Disk Space:</b></span> <span class="'.( isset($system_alerts['free_partition_space']) ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['portfolio_cache']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Portfolio Cache Size:</b></span> <span class="'.( isset($system_alerts['portfolio_cache']) ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> </div>';
    		}
    		
    		if ( isset($system_info['software']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Software:</b></span> <span class="blue"> '.$system_info['software'].'</span> </div>';
    		}
    		
    		
   ?>
    		
   </div>
    		
    		
   <ul>
	
	<li class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>See Admin Config POWER USER section, to adjust vertical axis scales.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>System load is always (roughly) MULTIPLIED by the number of threads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Cron Runtime Seconds DOES NOT INCLUDE plugin runtime (for stability of CORE runtime, in case <i>custom</i> plugins are buggy).</li>	
   
   </ul>
   
	
	<?php
	$all_chart_rebuild_min_max = explode(',', $pt_conf['dev']['all_chart_rebuild_min_max']);
	?>
	
	<p class='sys_stats red' style='font-weight: bold;'>*The most recent days in the 'ALL' chart MAY ALWAYS show a spike on the cron runtime seconds (ON SLOWER MACHINES, from re-building the 'ALL' chart every <?=$all_chart_rebuild_min_max[0]?> to <?=$all_chart_rebuild_min_max[1]?> hours), until the 'ALL' chart re-builds slowly average out only showing their own runtime data for older days.</p>		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$pt_conf['power']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 1;
	include('templates/interface/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='system_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$pt_conf['power']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 2;
	include('templates/interface/desktop/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
		
	</div>
	
	
	<script>
	$('.show_system_stats').modaal({
		fullscreen: true,
		content_source: '#show_system_stats'
	});
	</script>
	
	
	<div id="show_access_stats">
	
		
		<h3 style='display: inline;'>Access Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice'></span>
	
	<br clear='all' />
	<br clear='all' />
			
	
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
	$('.show_access_stats').modaal({
		fullscreen: true,
		content_source: '#show_access_stats'
	});
	</script>
	
	
	
	<div id="show_logs">
	
		
		<h3 style='display: inline;'>App Logs</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice'></span>
	
	<br clear='all' />
	<br clear='all' />
			
    		
   <ul>
	
	<li class='bitcoin' style='font-weight: bold;'>Error / debugging logs will automatically display here, if they exist (primary error log always shows, even if empty).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>All log timestamps are UTC time (Coordinated Universal Time).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Current UTC time: <span class='utc_timestamp red'></span></li>	
   
   </ul>
	
		
		<p class='red' style='font-weight: bold;'>*Log format: </p>
		
	   <!-- Looks good highlighted as: less, yaml  -->
	   <pre class='rounded' style='display: inline-block;<?=( $pt_gen->is_msie() == false ? ' padding-top: 1em !important;' : '' )?>'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>[UTC timestamp] runtime_mode => error_type: error_msg; [ (tracing if log verbosity set to verbose) ]</code></pre>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='error_log_space' value='1' onchange="system_logs('error_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='error_log_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('error_log', 'error_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('error_log');">Refresh</button> 
	        
	        &nbsp; <span id='error_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='error_log'></code></pre>
			  
			  <script>
			  system_logs('error_log');
			  </script>
		
	    </fieldset>
				
	<?php
	if ( $pt_conf['dev']['debug'] != 'off' || is_readable($base_dir . '/cache/logs/debug.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> Debugging Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='debug_log_space' value='1' onchange="system_logs('debug_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='debug_log_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('debug_log', 'debug_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('debug_log');">Refresh</button> 
	        
	        &nbsp; <span id='debug_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='debug_log'></code></pre>
			  
			  <script>
			  system_logs('debug_log');
			  </script>
		
	    </fieldset>
	    
	<?php
	}
	if ( is_readable($base_dir . '/cache/logs/smtp_error.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_error_log_space' value='1' onchange="system_logs('smtp_error_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='smtp_error_log_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_error_log', 'smtp_error_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_error_log');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_error_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_error_log'></code></pre>
			  
			  <script>
			  system_logs('smtp_error_log');
			  </script>
		
	    </fieldset>
	<?php
	}
	if ( is_readable($base_dir . '/cache/logs/smtp_debug.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Debugging Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_debug_log_space' value='1' onchange="system_logs('smtp_debug_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='smtp_debug_log_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_debug_log', 'smtp_debug_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_debug_log');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_debug_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_debug_log'></code></pre>
			  
			  <script>
			  system_logs('smtp_debug_log');
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
	
	
	
                            
                        