<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

    
			<h4 style='display: inline;'>Portfolio</h4> (<?=$last_trade_cache?> minute cache)
			<?php
			if ( sizeof($alert_percent) > 1 ) {
				
				if ( $alert_percent[3] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $alert_percent[3] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
				$text_mcap_trend = $alert_percent[2];
				
				$text_mcap_trend = ucwords(preg_replace("/hour/i", " hour", $text_mcap_trend));
				
				$text_mcap_trend = ucwords(preg_replace("/day/i", " day", $text_mcap_trend));
				
			?>
			  &nbsp; &nbsp; <span class='<?=( stristr($alert_percent[1], '-') == false ? 'green' : 'orange' )?>' style='font-weight: bold;'><?=$visual_audio_alerts?> alerts (<?=ucfirst($marketcap_site)?> / <?=$alert_percent[1]?>% / <?=$text_mcap_trend?>)</span>
			<?php
			}
			?>  &nbsp; &nbsp; &nbsp; <a href='javascript:location.reload(true);' style='font-weight: bold;' title='Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache setting in config.php is set to <?=$last_trade_cache?> minute(s). A setting of 1 or higher assists in avoiding IP blacklisting by exchanges.'>Refresh</a>
			
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
<th class='border_lt'>#</th>
<th class='border_lt blue' align='right'>Asset</th>
<th class='border_t'>USD Value</th>
<th class='border_lt blue' align='right'>Held</th>
<th class='border_t'>Symbol</th>
<th class='border_lt blue'>Exchange</th>
<th class='border_t'>USD Volume</th>
<th class='border_t' align='right'>Trade Value</th>
<th class='border_t blue'>Market</th>
<th class='border_lt blue'>Holdings Value</th>
<th class='border_lrt blue'>USD Subtotal</th>
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
										$selected_market = ($_POST[strtolower($coin_symbol).'_market'] - 1); // Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$purchase_price = remove_number_format($_POST[strtolower($coin_symbol).'_paid']);
										$leverage_level = $_POST[strtolower($coin_symbol).'_leverage'];
										$selected_margintype = $_POST[strtolower($coin_symbol).'_margintype'];
												
						
								
										ui_coin_data($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $held_amount, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
										
											if ( $held_amount >= 0.00000001 ) {
											$assets_added = 1;
											}
											
											if ( $purchase_price >= 0.00000001 ) {
											$purchase_price_added = 1;
											}
											
											if ( $leverage_level >= 2 ) {
											$leverage_added = 1;
											}
											
											if ( $selected_margintype == 'short' ) {
											$short_added = 1;
											}
										
										
										}
									
									
									
									}
		
		}
	
	}
	elseif ( $run_csv_import == 1 ) {
	
		
		if (is_array($csv_file_array) || is_object($csv_file_array)) {
			
		$btc_market = ( $csv_file_array['BTC'][3] != NULL ? $csv_file_array['BTC'][3] - 1 : 1 );  // If no BTC asset is in imported file, default to 1
									
				foreach( $csv_file_array as $key => $value ) {
								
									$run_csv_import = 1;
	        
	        		
	        			if ( remove_number_format($value[1]) > 0.00000000 ) {  // Show even if decimal is off the map, just for UX purposes tracking token price only
	        			
										$held_amount = remove_number_format($value[1]);
										$coin_symbol = strtoupper($value[0]);
										$selected_pairing = $value[6];
										$selected_market = $value[5] - 1; // Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										$purchase_price = remove_number_format($value[2]);
										$leverage_level = $value[3];
										$selected_margintype = strtolower($value[4]);
												
						
								
										ui_coin_data($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $held_amount, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
										
											if ( $held_amount >= 0.00000001 ) {
											$assets_added = 1;
											}
											
											if ( $purchase_price >= 0.00000001 ) {
											$purchase_price_added = 1;
											}
											
											if ( $leverage_level >= 2 ) {
											$leverage_added = 1;
											}
											
											if ( $selected_margintype == 'short' ) {
											$short_added = 1;
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
			
					foreach ( $all_coin_amounts_cookie_array as $coin_amounts ) {
									
					$single_coin_amount_cookie_array = explode("-", $coin_amounts);
					
					$coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_amount_cookie_array[0]));
				
							if ( $coin_symbol == 'BTC' && !$btc_market ) {
							$btc_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
							}
	
					$all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'] = $single_coin_amount_cookie_array[1];
					
					
					// Bundle all required cookie data in this final cookies parsing loop for each coin, and render the coin's data
					// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					$held_amount = floattostr($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount']);
					$selected_pairing = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_pairing'];
					$selected_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
					$purchase_price = floattostr($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_paid']);
					$leverage_level = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_leverage'];
					$selected_margintype = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_margintype'];
					
			// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
					ui_coin_data($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $held_amount, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $purchase_price, $leverage_level, $selected_margintype);
					
						
						if ( $held_amount >= 0.00000001 ) {
						$assets_added = 1;
						}
						
						if ( $purchase_price >= 0.00000001 ) {
						$purchase_price_added = 1;
						}
											
						if ( $leverage_level >= 2 ) {
						$leverage_added = 1;
						}
											
						if ( $selected_margintype == 'short' ) {
						$short_added = 1;
						}
						
					
	
					}
					
					
		}
		
		
		
	}

?>

</tbody>
</table>


<?php


	// Get exchange name
	$coins_list_numbered = array_values($coins_list['BTC']['market_pairing']['btc']);
	foreach ( $coins_list['BTC']['market_pairing']['btc'] as $key => $value ) {
	$loop = $loop + 1;
	
		if ( $value == $coins_list_numbered[$btc_market] ) {
		$show_exchange = $key;
		}

	}
	$loop = NULL;
	


// Get portfolio summaries


$total_btc_worth_raw = number_format(bitcoin_total(), 8, '.', '');

// Pretty number formatting, while maintaining decimals
$raw_btc_worth_value = remove_number_format($total_btc_worth_raw);
	    
	    
	if ( preg_match("/\./", $raw_btc_worth_value) ) {
	$btc_worth_no_decimal = preg_replace("/\.(.*)/", "", $raw_btc_worth_value);
	$btc_worth_decimal = preg_replace("/(.*)\./", "", $raw_btc_worth_value);
	$check_btc_worth_decimal = '0.' . $btc_worth_decimal;
	}
	else {
	$btc_worth_no_decimal = $raw_btc_worth_value;
	$btc_worth_decimal = NULL;
	$check_btc_worth_decimal = NULL;
	}
	    
	    
$total_btc_worth = number_format($btc_worth_no_decimal, 0, '.', ',') . ( floattostr($check_btc_worth_decimal) > 0.00000000 ? '.' . $btc_worth_decimal : '' );

$total_usd_worth = coin_stats_data('coin_worth_total');

$bitcoin_dominance = ( $_SESSION['btc_worth_array']['BTC'] / $total_btc_worth_raw ) * 100;

$altcoin_dominance = 100 - $bitcoin_dominance;
	
		
?>
<div class="show_coin_values bold_1 green"><!-- Summary START -->
<?php
		
		// Run BEFORE output of BTC / USD portfolio values, to include any margin / leverage summaries in parentheses NEXT TO THEM (NOT in the actual BTC / USD amounts, for UX's sake)
		if ( $purchase_price_added == 1 ) {
			
		$gain_loss_total = coin_stats_data('gain_loss_total');
		
		$parsed_gain_loss_total = preg_replace("/-/", "-$", number_format( $gain_loss_total, 2, '.', ',' ) );
		
		$original_worth = coin_stats_data('coin_paid_total');
		
		$leverage_only_gain_loss = coin_stats_data('gain_loss_only_leverage');
  		
		$total_usd_worth_inc_leverage = $total_usd_worth + $leverage_only_gain_loss;
  		
		$total_usd_worth_if_purchase_price = coin_stats_data('coin_total_worth_if_purchase_price') + $leverage_only_gain_loss;
		
		$gain_loss_text = ( $gain_loss_total >= 0 ? 'gains' : 'losses' );
		
		}
		
	  
	  
	  // Notice that margin leverage is NOT included !!WITHIN!! BTC / USD TOTALS EVER (for UX's sake, too confusing to included in anything other than gain / loss stats)
	  // We only include data in parenthesis NEXT TO THE BTC / USD PORTFOLIO SUMMARIES
	  $leverage_text1 = ( $purchase_price_added == 1 && $leverage_added == 1 && $gain_loss_total != NULL ? ' <span class="red"> &nbsp;(includes adjusted long deposits, <i><u>not</u></i> leverage)</span>' : '' );
	  $leverage_text2 = ( $purchase_price_added == 1 && $leverage_added == 1 && $gain_loss_total != NULL ? ' <span class="red"> &nbsp;(includes adjusted short / long deposits, <i><u>not</u></i> leverage)</span>' : '' );


		// BTC / USD portfolio stats output
		echo '<div class="portfolio_summary"><span class="black">BTC Value:</span> <span class="bitcoin">Ƀ ' . $total_btc_worth . '</span>' . $leverage_text1 . '</div>';
		
		echo '<div class="portfolio_summary"><span class="black">USD Value:</span> $' . number_format($total_usd_worth, 2, '.', ',') . $leverage_text2 . '</div>';
		
		echo ( $purchase_price_added == 1 && $leverage_added == 1 && $gain_loss_total != NULL ? '<div class="portfolio_summary"><span class="black">Leverage Included:</span> $' . number_format($total_usd_worth_inc_leverage, 2, '.', ',') . '</div>' : '' );
	



		// Now that BTC / USD summaries have margin leverage stats NEXT TO THEM (NOT in the actual BTC / USD amounts, for UX's sake), 
		// we move on to the gain / loss stats WHICH ARE THE ONLY STATS UX FEASIBLE ENOUGH TO INCLUDE MARGIN LEVERAGE DATA INCLUDED IN THE ACTUAL VALUES
		if ( $gain_loss_total != NULL ) {
			
			
          // Gain / loss percent
          if ( floattostr($original_worth) >= 0.00000001 && $total_usd_worth_if_purchase_price < $original_worth ) {
          $percent_difference_total = 100 - ( $total_usd_worth_if_purchase_price / ( $original_worth / 100 ) );
          }
          elseif ( floattostr($original_worth) >= 0.00000001 && $total_usd_worth_if_purchase_price >= $original_worth ) {
          $percent_difference_total = ( $total_usd_worth_if_purchase_price / ( $original_worth / 100 ) ) - 100;
          }
          
		
		// Notice that we include margin leverage in gain / loss stats (for UX's sake, too confusing to included in anything other than gain / loss stats)
		$leverage_text2 = ( $leverage_added == 1 ? ', includes leverage' : '' );
		
		
		echo '<div class="portfolio_summary"><span class="black">' . ( $gain_loss_total >= 0 ? 'USD Gain:</span> <span class="green">+$' : 'USD Loss:</span> <span class="red">' ) . $parsed_gain_loss_total . ' (' . ( $gain_loss_total >= 0 ? '+' : '-' ) . number_format($percent_difference_total, 2, '.', ',') . '%' . $leverage_text2 . ')</span>';
		
		?> 
		
		<img id='portfolio_gain_loss' src='ui-templates/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' /> </div>
		
		
	 <script>
	 
		document.title = '<?=( $gain_loss_total >= 0 ? '+$' : '' )?><?=$parsed_gain_loss_total?> (<?=( $gain_loss_total >= 0 ? '+' : '-' )?><?=number_format($percent_difference_total, 2, '.', ',')?>%)  ||  ' + document.title;
	
		
			var gain_loss_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;">Portfolio Gain / Loss Stats:</h5>'
			
			<?php
					
					// Sort descending gains
					$coin_stats_array = $_SESSION['coin_stats_array'];
					$columns_array = array_column($coin_stats_array, 'gain_loss_total');
					array_multisort($columns_array, SORT_DESC, $coin_stats_array);
					
				foreach ( $coin_stats_array as $key => $value ) {
					
						$parsed_gain_loss = preg_replace("/-/", "-$", number_format( $value['gain_loss_total'], 2, '.', ',' ) );
						
						if ( $value['coin_leverage'] >= 2 ) {
						$parsed_total_with_leverage = number_format( ( $value['coin_worth_total'] + $value['gain_loss_only_leverage'] ) , 2, '.', ',' );
						}
						
					
						if ( floattostr($value['coin_paid']) >= 0.00000001 ) {
							
							
				?>
			+'<p class="coin_info"><span class="yellow"><?=$value['coin_symbol']?>:</span> <span class="<?=( $value['gain_loss_total'] >= 0 ? 'green_bright">+$' : 'red">' )?><?=$parsed_gain_loss?> / <?=( $value['gain_loss_total'] >= 0 ? '+' : '' )?><?=number_format($value['gain_loss_percent_total'], 2, '.', ',')?>%</span> <?=( $value['coin_leverage'] >= 2 ? '($' . $parsed_total_with_leverage . ' @ ' . $value['coin_leverage'] . 'x ' . $value['selected_margintype'] . ')' : '' )?></p>'
			
			<?php
						}
							
				}
			 ?>
				
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#portfolio_gain_loss').balloon({
			html: true,
			position: "right",
			contents: gain_loss_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "1px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.95",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
		<?php
		}
		if ( $bitcoin_dominance >= 0 && $altcoin_dominance >= 0 ) {
		echo '<div class="portfolio_summary"><span class="black">Stats:</span> ' . number_format($bitcoin_dominance, 2, '.', ',') . '% Bitcoin / ' . number_format($altcoin_dominance, 2, '.', ',') .'% Altcoin(s)';
		?>
		
		<img id='portfolio_dominance' src='ui-templates/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' /> </div>
	 <script>
	
			var dominance_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;">Portfolio Dominance Stats:</h5>'
			
			<?php
					
					// Sort by most dominant first
					arsort($_SESSION['btc_worth_array']);
				foreach ( $_SESSION['btc_worth_array'] as $key => $value ) {
					$dominance = ( $value / $total_btc_worth_raw ) * 100;
					
						if ( $dominance >= 0.01 ) {
				?>
			+'<p class="coin_info"><span class="yellow"><?=$key?>:</span> <?=number_format($dominance, 2, '.', ',')?>%</p>'
			
			<?php
						}
							
				}
			 ?>
				
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#portfolio_dominance').balloon({
			html: true,
			position: "right",
			contents: dominance_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "1px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.95",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
		<?php
		}
	
	echo '<div class="portfolio_summary"><span class="black">(Bitcoin is trading @ $' .number_format( get_btc_usd($btc_exchange)['last_trade'], 2, '.', ','). ' on '.ucfirst($show_exchange).')</span></div>';

			
		if ( $short_added == 1 ) {
		?>	
		<div class="portfolio_summary" style='margin-top: 15px;'><span class="short">★ Adjusted short trade value(s)</span></div>		
		<?php
		}
		?>
</div><!-- Summary END -->
	<?php	
	// End outputting results
	}
	
	if ( $assets_added ) {
	?>
	
	<style>
	.show_coin_values {
	display: block;
	}
	</style>
	
	<?php
	}
	else {
	?>
	
	<div align='center' style='min-height: 100px;'>
	
		<p><img src='ui-templates/media/images/favicon.png' alt='' border='0' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the Update Assets page).</p>
	</div>
	
	<?php
	}
	
	
	if ( $_COOKIE['notes_reminders'] != '' ) {
	?>
	
	<div style='margin-top: 10px;'>
	
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
                            
                            
                        