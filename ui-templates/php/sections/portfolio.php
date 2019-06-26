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
				
			?>
			  &nbsp; &nbsp; <span class='<?=( stristr($alert_percent[1], '-') == false ? 'green' : 'orange' )?>' style='font-weight: bold;'><?=$visual_audio_alerts?> alerts (<?=ucfirst($marketcap_site)?> / <?=$alert_percent[1]?>% / <?=$alert_percent[2]?>)</span>
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
if ( $_POST['submit_check'] == 1 || $_COOKIE['coin_amounts'] ) {
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
	
		$sort_order = 1;
		
		if (is_array($_POST) || is_object($_POST)) {
			
		$btc_market = ($_POST['btc_market'] - 1);
									
									foreach ( $_POST as $key => $value ) {
								
										if ( preg_match("/_amount/i", $key) ) {
										
										$coin_symbol = strtoupper(preg_replace("/_amount/i", "", $key));
										$selected_pairing = ($_POST[strtolower($coin_symbol).'_pairing']);
										$selected_market = ($_POST[strtolower($coin_symbol).'_market'] - 1);
										$purchase_price = ($_POST[strtolower($coin_symbol).'_paid']);
												
						
								// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
										ui_coin_data($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $value, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $sort_order, $purchase_price);
										
											if ( floatval($value) >= 0.00000001 ) {
											$assets_added = 1;
											}
											
											if ( floatval($purchase_price) >= 0.00000001 ) {
											$purchase_price_added = 1;
											}
										
										
										}
									
									$sort_order = $sort_order + 1;
									}
		
		}
	
	}
	elseif ( $_COOKIE['coin_amounts'] && $_COOKIE['coin_markets'] && $_COOKIE['coin_pairings'] ) {
	
	
		$sort_order = 1;
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
					$selected_pairing = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_pairing'];
					$selected_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
					$selected_amount = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'];
					$purchase_price = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_paid'];
					
			// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
					ui_coin_data($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $selected_amount, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $sort_order, $purchase_price);
					
						if ( floatval($selected_amount) >= 0.00000001 ) {
						$assets_added = 1;
						}
						
						if ( floatval($purchase_price) >= 0.00000001 ) {
						$purchase_price_added = 1;
						}
						
					
					$sort_order = $sort_order + 1;
	
					}
					
		}
		
		
		
	}

?>

</tbody>
</table>


<?php

$total_btc_worth = bitcoin_total();
$total_usd_worth = ($total_btc_worth * get_btc_usd($btc_exchange)['last_trade']);

$bitcoin_dominance = ( $_SESSION['btc_worth_array']['BTC'] / $total_btc_worth ) * 100;

$altcoin_dominance = 100 - $bitcoin_dominance;

echo '<p class="show_coin_values bold_1 green">';

echo 'BTC Value: Ƀ ' . number_format($total_btc_worth, 8, '.', ',');
	
$coins_list_numbered = array_values($coins_list['BTC']['market_pairing']['btc']);

	foreach ( $coins_list['BTC']['market_pairing']['btc'] as $key => $value ) {
	$loop = $loop + 1;

		if ( $value == $coins_list_numbered[$btc_market] ) {
		$show_exchange = $key;
		}

	}
	$loop = NULL;

	echo '<br />USD Value: $' . number_format($total_usd_worth, 2, '.', ',');

	if ( $purchase_price_added == 1 ) {
		
	$gain_loss_worth = gain_loss_total();
	$parsed_gain_loss_worth = preg_replace("/-/", "-$", number_format( $gain_loss_worth, 2, '.', ',' ) );
	
	$positive_gain_loss_worth = abs($gain_loss_worth); // Needed to calculate original worth with loss
	
		if ( $gain_loss_worth < 0 ) {
		$original_worth = $total_usd_worth + $positive_gain_loss_worth;
		}
		else {
		$original_worth = $total_usd_worth - $gain_loss_worth;
		}
		
	$percent_difference = ( ($total_usd_worth / $original_worth) - 1 ) * 100;
	
	echo '<br /><span class="' . ( $gain_loss_worth >= 0 ? 'green">USD Gain: +$' : 'red">USD Loss: ' ) . $parsed_gain_loss_worth . ' (' . ( $gain_loss_worth >= 0 ? '+' : '' ) . number_format($percent_difference, 2, '.', ',') . '%)</span>';
	}
	?> 
	<img id='portfolio_gain_loss' src='ui-templates/media/images/info.png' width='30' border='0' style='position: relative; left: -5px;' /> 
 <script>

        var gain_loss_content = '<h5 class="yellow" style="position: relative;">Portfolio Gain / Loss Stats:</h5>'
        
        <?php
        		
        		// Sort descending gains
        		$gain_loss_array = $_SESSION['gain_loss_array'];
        		$columns_array = array_column($gain_loss_array, 'gain_loss');
				array_multisort($columns_array, SORT_DESC, $gain_loss_array);
        		
            foreach ( $gain_loss_array as $key => $value ) {
            	
					$parsed_gain_loss = preg_replace("/-/", "-$", number_format( $value['gain_loss'], 2, '.', ',' ) );
	
   				$gain_loss_percent = ( ($value['coin_worth_total'] / $value['coin_paid_total']) - 1 ) * 100;
            	
            	
            		if ( $value['coin_paid'] != NULL ) {
            ?>
        +'<p class="coin_info"><span class="yellow"><?=$value['coin_symbol']?>:</span> <span class="<?=( $value['gain_loss'] >= 0 ? 'green_bright">+$' : 'red">' )?><?=$parsed_gain_loss?> (<?=( $value['gain_loss'] >= 0 ? '+' : '' )?><?=number_format($gain_loss_percent, 2, '.', ',')?>%)</span></p>'
        
        <?php
        				}
        				
            }
         ?>
            
        +'<p class="coin_info"><span class="yellow"> </p>';
    
    
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
	if ( $bitcoin_dominance >= 0 && $altcoin_dominance >= 0 ) {
	echo '<br />Stats: ' . number_format($bitcoin_dominance, 2, '.', ',') . '% Bitcoin / ' . number_format($altcoin_dominance, 2, '.', ',') .'% Altcoin(s)';
	}
	?> 
	<img id='portfolio_dominance' src='ui-templates/media/images/info.png' width='30' border='0' style='position: relative; left: -5px;' /> 
 <script>

        var dominance_content = '<h5 class="yellow" style="position: relative;">Portfolio Dominance Stats:</h5>'
        
        <?php
        		
        		// Sort by most dominant first
        		arsort($_SESSION['btc_worth_array']);
            foreach ( $_SESSION['btc_worth_array'] as $key => $value ) {
            	$dominance = ( $value / $total_btc_worth ) * 100;
            	
            		if ( $dominance >= 0.01 ) {
            ?>
        +'<p class="coin_info"><span class="yellow"><?=$key?>:</span> <?=number_format($dominance, 2, '.', ',')?>%</p>'
        
        <?php
        				}
        				
            }
         ?>
            
        +'<p class="coin_info"><span class="yellow"> </p>';
    
    
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

echo '<br /><span style="color: black;">(Bitcoin is $' .number_format( get_btc_usd($btc_exchange)['last_trade'], 2, '.', ','). ' @ '.ucfirst($show_exchange).')</span>';
	
echo '</p>';

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

	<p><img src='ui-templates/media/images/favicon.png' border='0' /></p>
	<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the Update Assets page).</p>
</div>
<?php
}

if ( $_COOKIE['notes_reminders'] ) {
?>

<form action='<?=start_page($_GET['start_page'])?>' method='post'>

<b>Trading Notes / Reminders:</b><br />

<textarea data-autoresize name='notes_reminders' id='notes_reminders' style='height: auto; width: 100%;'><?=$_COOKIE['notes_reminders']?></textarea><br />

<input type='hidden' name='update_notes' id='update_notes' value='1' />
<input type='submit' value='Save Updated Notes' />

</form>

<?php
}
?>

</p>
                            
                            
                        