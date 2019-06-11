<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

    
			<h3 style='display: inline;'>Portfolio</h3> (<?=$last_trade_cache?> minute cache)
			<?php
			if ( sizeof($alert_percent) > 1 ) {
				
				if ( $alert_percent[3] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $alert_percent[3] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
			?>
			  &nbsp; &nbsp; <span style='color: <?=( stristr($alert_percent[1], '-') == false ? 'green' : '#ea6b1c' )?>; font-weight: bold;'><?=$visual_audio_alerts?> alerts (<?=ucfirst($marketcap_site)?> / <?=$alert_percent[1]?>% / <?=$alert_percent[2]?>)</span>
			<?php
			}
			?>  &nbsp; &nbsp; &nbsp; <a href='javascript:location.reload(true);' style='font-weight: bold;' title='Refreshing data too frequently may cause API request refusals, especially if request caching settings are too low. It is recommended to use this refresh feature sparingly with lower or disabled cache settings. The current real-time exchange data re-cache setting in config.php is set to <?=$last_trade_cache?> minute(s). A setting of 1 or higher assists in avoiding IP blacklisting by exchanges.'>Refresh</a>
			
			 &nbsp;<select name='select_auto_refresh' id='select_auto_refresh' onchange='auto_reload(this.value);'>
				<option value=''> Manually </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> Every 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> Every 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> Every 15 Minutes </option>
				<option value='1800' <?=( $_COOKIE['coin_reload'] == '1800' ? 'selected' : '' )?>> Every 30 Minutes </option>
			</select> &nbsp;<span id='reload_countdown' style='color: red;'></span>
			
			<p>                        
                            
<?php
// Start outputting results
if ( $_POST['submit_check'] == 1 || $_COOKIE['coin_amounts'] ) {
?>


<table border='0' cellpadding='10' cellspacing='0' id="coins_table" class='show_coin_values'>
 <thead>
    <tr>
<th class='border_lt'>#</th>
<th class='border_lt' align='right' style='color: blue;'>Asset</th>
<th class='border_t'>USD Value</th>
<th class='border_lt' align='right' style='color: blue;'>Holdings</th>
<th class='border_t'>Symbol</th>
<th class='border_lt' style='color: blue;'>Exchange</th>
<th class='border_t'>USD Volume</th>
<th class='border_t' align='right'>Trade Value</th>
<th class='border_t' style='color: blue;'>Market</th>
<th class='border_lt' style='color: blue;'>Holdings Value</th>
<th class='border_lrt' style='color: blue;'>USD Subtotal</th>
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
										coin_data_row($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $value, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $sort_order, $purchase_price);
										
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
					coin_data_row($coins_list[$coin_symbol]['coin_name'], $coin_symbol, $selected_amount, $coins_list[$coin_symbol]['market_pairing'][$selected_pairing], $selected_pairing, $selected_market, $sort_order, $purchase_price);
					
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

echo '<p class="show_coin_values bold_1">Total Bitcoin Value: ' . number_format($total_btc_worth, 8, '.', ',') . '<br />';

$coins_list_numbered = array_values($coins_list['BTC']['market_pairing']['btc']);

	foreach ( $coins_list['BTC']['market_pairing']['btc'] as $key => $value ) {
	$loop = $loop + 1;

		if ( $value == $coins_list_numbered[$btc_market] ) {
		echo 'Total USD Value: $' . number_format($total_usd_worth, 2, '.', ',') . ' (1 Bitcoin is currently worth $' .number_format( get_btc_usd($btc_exchange)['last_trade'], 2, '.', ','). ' at '.ucfirst($key).')';
		}

	}
	$loop = NULL;


	if ( $purchase_price_added == 1 ) {
	$gain_loss_worth = gain_loss_total();
	$parsed_gain_loss_worth = preg_replace("/-/", "-$", number_format( $gain_loss_worth, 2, '.', ',' ) );
	echo '<br /> <span style="color: ' . ( $gain_loss_worth >= 0 ? 'green;">Total USD Gains: +$' : 'red;">Total USD Losses: ' ) . $parsed_gain_loss_worth . '</span>';
	}

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
	<p style='font-weight: bold; color: red; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the Update Assets page).</p>
</div>
<?php
}

if ( $_COOKIE['notes_reminders'] ) {
?>

<form action='<?=start_page($_GET['start_page'])?>' method='post'>
<p>

<b>Trading Notes / Reminders:</b><br />

<textarea data-autoresize name='notes_reminders' id='notes_reminders' style='height: auto; width: 100%;'><?=$_COOKIE['notes_reminders']?></textarea><br />

<input type='hidden' name='update_notes' id='update_notes' value='1' />
<input type='submit' value='Save Updated Notes' />

</p>
</form>

<?php
}
?>

</p>
                            
                            
                        