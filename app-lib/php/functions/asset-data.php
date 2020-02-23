<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function cuckoo_scaling_level($num) {

// https://github.com/mimblewimble/docs/wiki/FAQ
// scale = (N-1) * 2^(N-30) for cuckooN cycles

return ($num - 1) * pow(2, ($num - 30) );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function powerdown_primary_currency($data) {

global $steem_market, $app_config,  $btc_primary_currency_value;

return ( $data * $steem_market * $btc_primary_currency_value );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function monero_reward() {
	
global $runtime_mode;

	if ( $runtime_mode != 'ui' ) {
	return false;  // We only use the block reward config file call for UI data, can skip the API request if not running the UI.
	}
	else {
 	return monero_api('last_reward') / 1000000000000;
   }
   
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function get_sub_token_price($chosen_market, $market_pairing) {

global $app_config;

  if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {
  return $app_config['ethereum_subtoken_ico_values'][$market_pairing];
  }
 
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function bitcoin_total() {
	
global $btc_worth_array;

  	foreach ( $btc_worth_array as $key => $value ) {
  	$total_value = ($value + $total_value);
  	}
  
return $total_value;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function coin_stats_data($request) {

global $coin_stats_array;

  	foreach ( $coin_stats_array as $key => $value ) {
  	$results = ($results + $value[$request]);
	}
		
return $results;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function btc_market($input) {

global $app_config;

	$pairing_loop = 0;
	foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']] as $market_key => $market_id ) {
		
		// If a numeric id, return the exchange name
		if ( is_int($input) && $pairing_loop == $input ) {
		return $market_key;
		}
		// If an exchange name (alphnumeric with possible underscores), return the numeric id (used in UI html forms)
		elseif ( preg_match("/^[A-Za-z0-9_]+$/", $input) && $market_key == $input ) {
		return $pairing_loop + 1;
		}
	$pairing_loop = $pairing_loop + 1;
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function marketcap_data($symbol) {
	
global $app_config, $alert_percent, $coinmarketcap_currencies, $cap_data_force_usd, $cmc_notes, $coingecko_api, $coinmarketcap_api;

$symbol = strtolower($symbol);

$data = array();


	if ( $app_config['primary_marketcap_site'] == 'coingecko' ) {
		
	// Don't overwrite global
	$coingecko_primary_currency = strtolower($app_config['btc_primary_currency_pairing']);
	
		
		if ( $coingecko_api[$symbol]['market_data']['current_price'][$coingecko_primary_currency] == '' ) {
		$app_notes = 'Coingecko.com does not support '.strtoupper($coingecko_primary_currency).' stats,<br />showing USD stats instead.';
		$coingecko_primary_currency = 'usd';
		$cap_data_force_usd = 1;
		}
		else {
		$cap_data_force_usd = null;
		}
		
		
	$data['rank'] = $coingecko_api[$symbol]['market_data']['market_cap_rank'];
	$data['price'] = $coingecko_api[$symbol]['market_data']['current_price'][$coingecko_primary_currency];
	$data['market_cap'] = $coingecko_api[$symbol]['market_data']['market_cap'][$coingecko_primary_currency];
	$data['volume_24h'] = $coingecko_api[$symbol]['market_data']['total_volume'][$coingecko_primary_currency];
	
	$data['percent_change_1h'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_1h_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_24h_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_7d_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	
	$data['circulating_supply'] = $coingecko_api[$symbol]['market_data']['circulating_supply'];
	$data['total_supply'] = $coingecko_api[$symbol]['market_data']['total_supply'];
	$data['max_supply'] = null;
	
	$data['last_updated'] = strtotime( $coingecko_api[$symbol]['last_updated'] );
	
	$data['app_notes'] = $app_notes;
	
	// Coingecko-only
	$data['percent_change_14d'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_14d_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_30d'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_30d_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_60d'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_60d_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_200d'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_200d_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	$data['percent_change_1y'] = number_format( $coingecko_api[$symbol]['market_data']['price_change_percentage_1y_in_currency'][$coingecko_primary_currency] , 2, ".", ",");
	
	}
	elseif ( $app_config['primary_marketcap_site'] == 'coinmarketcap' ) {

	// Don't overwrite global
	$coinmarketcap_primary_currency = strtoupper($app_config['btc_primary_currency_pairing']);
	
	
		// Default to USD, if selected primary currency is not supported
		if ( isset($cap_data_force_usd) ) {
		$coinmarketcap_primary_currency = 'USD';
		}
		
		
		if ( isset($cmc_notes) ) {
		$app_notes = $cmc_notes;
		}
		
		
	$data['rank'] = $coinmarketcap_api[$symbol]['cmc_rank'];
	$data['price'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['price'];
	$data['market_cap'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['market_cap'];
	$data['volume_24h'] = $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['volume_24h'];
	
	$data['percent_change_1h'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_1h'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_24h'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( $coinmarketcap_api[$symbol]['quote'][$coinmarketcap_primary_currency]['percent_change_7d'] , 2, ".", ",");
	
	$data['circulating_supply'] = $coinmarketcap_api[$symbol]['circulating_supply'];
	$data['total_supply'] = $coinmarketcap_api[$symbol]['total_supply'];
	$data['max_supply'] = $coinmarketcap_api[$symbol]['max_supply'];
	
	$data['last_updated'] = strtotime( $coinmarketcap_api[$symbol]['last_updated'] );
	
	$data['app_notes'] = $app_notes;
	
	}
 	
	
	// UX on number values
	$data['price'] = ( number_to_string($data['price']) >= $app_config['primary_currency_decimals_max_threshold'] ? pretty_numbers($data['price'], 2) : pretty_numbers($data['price'], $app_config['primary_currency_decimals_max']) );
	

// Return null if we don't even detect a rank
return ( $data['rank'] != NULL ? $data : NULL );

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function trade_volume($asset_symbol, $pairing, $volume, $last_trade, $vol_in_pairing=false) {

global $app_config, $btc_primary_currency_value;
	
	// If no pairing data, skip calculating trade volume to save on uneeded overhead
	if ( !isset($asset_symbol) || $pairing == false || is_numeric($volume) != true && is_numeric($vol_in_pairing) != true || !isset($last_trade) || $last_trade == 0 ) {
	return false;
	}


	// WE NEED TO SET THIS (ONLY IF NOT SET ALREADY) for asset_market_data() calls, 
	// because it is not set as a global THE FIRST RUNTIME CALL TO asset_market_data()
	if ( strtoupper($asset_symbol) == 'BTC' && !$btc_primary_currency_value ) {
	$temp_btc_primary_currency_value = $last_trade; // Don't overwrite global
	}
	else {
	$temp_btc_primary_currency_value = $btc_primary_currency_value; // Don't overwrite global
	}


    
	// Get primary currency volume value
	
	// Currency volume from Bitcoin's DEFAULT PAIRING volume
	if ( $vol_in_pairing != false && $pairing == $app_config['btc_primary_currency_pairing'] ) {
	$volume_primary_currency_raw = number_format( $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from btc PAIRING volume
	elseif ( $vol_in_pairing != false && $pairing == 'btc' ) {
	$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * $vol_in_pairing , 0, '.', '');
	}
	// Currency volume from other PAIRING volume
	elseif ( $vol_in_pairing != false ) { 
	
	$pairing_btc_value = pairing_market_value($pairing);

		if ( $pairing_btc_value == false ) {
		app_logging('other_error', 'pairing_market_value() returned false in trade_volume()', 'pairing: ' . $pairing);
		}
	
	$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * ( $vol_in_pairing * $pairing_btc_value ) , 0, '.', '');
	
	}
	// Currency volume from ASSET volume
	else {
		
		if ( $pairing == $app_config['btc_primary_currency_pairing'] ) { // Volume as DEFAULT BITCOIN currency pairing
		$volume_primary_currency_raw = number_format( $last_trade * $volume , 0, '.', ''); 
		}
		elseif ( $pairing == 'btc' ) {
		$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * ( $last_trade * $volume ) , 0, '.', '');
		}
		else {
			
		$pairing_btc_value = pairing_market_value($pairing);

			if ( $pairing_btc_value == false ) {
			app_logging('other_error', 'pairing_market_value() returned false in trade_volume()', 'pairing: ' . $pairing);
			}
	
		$volume_primary_currency_raw = number_format( $temp_btc_primary_currency_value * ( $last_trade * $volume ) * $pairing_btc_value , 0, '.', '');
		
		}
	
	}
	

	// Return negative number, if no data detected (so we know when data errors happen)
	if ( $last_trade != '' || $vol_in_pairing != false ) {
	return $volume_primary_currency_raw;
	}
	else {
	return -1;
	}
	 

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function pairing_market_value($pairing) {

global $app_config, $btc_pairing_markets, $btc_pairing_markets_blacklist;


	// Kill any ghost calls in dirty code
	if ( trim($pairing) == '' ) {
	return false;
	}

	
	// Safeguard / cut down on runtime
	if ( trim($pairing) == '' || $pairing == 'btc' ) {
	return false;
	}
	// If session value exists
	elseif ( $btc_pairing_markets[$pairing.'_btc'] ) {
	return $btc_pairing_markets[$pairing.'_btc'];
	}
	// If we need an ALTCOIN/BTC market value (RUN BEFORE CURRENCIES FOR BEST MARKET DATA, AS SOME CRYPTOS ARE INCLUDED IN BOTH)
	elseif ( array_key_exists($pairing, $app_config['crypto_to_crypto_pairing']) ) {
		
		// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
		if ( !is_array($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) ) {
		return false;
		}
	
		foreach ( $app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc'] as $market_key => $market_value ) {
					
			// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
			if ( sizeof($app_config['portfolio_assets'][strtoupper($pairing)]['market_pairing']['btc']) > 1 && array_key_exists($pairing, $app_config['preferred_altcoin_markets']) ) {
			$whitelist = $app_config['preferred_altcoin_markets'][$pairing];
			}
					
			if ( isset($whitelist) && $whitelist == $market_key && !array_key_exists($market_key, $btc_pairing_markets_blacklist)
			|| !isset($whitelist) && !array_key_exists($market_key, $btc_pairing_markets_blacklist) ) {
				
   		$btc_pairing_markets[$pairing.'_btc'] = asset_market_data(strtoupper($pairing), $market_key, $market_value)['last_trade'];
   		
   		$result = $btc_pairing_markets[$pairing.'_btc'];
   		
   			// Fallback support, if no data returned
   			if ( !isset($result) || number_to_string($result) < 0.00000001 || !is_numeric($result) ) {
   				
   			$btc_pairing_markets_blacklist[] = $market_key; // Blacklist getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   			
   			app_logging('other_error', 'pairing_market_value() update failure for ' . $pairing, 'blacklisted_exchange: ' . $market_key);
   			
   			return pairing_market_value($pairing);
   			
   			}
   			else {
   				
   				// Data debugging telemetry
					if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' ) {
					app_logging('other_debugging', 'pairing_market_value() update succeeded for ' . $pairing, 'exchange: ' . $market_key);
					}		
   					
   			return $result;
   			
   			}
   		
			}
			
		}
		return false; // If we made it this deep in the logic, no data was found	
	
	}
	// If we need a BITCOIN/CURRENCY market value 
	// RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
	elseif ( array_key_exists($pairing, $app_config['bitcoin_currency_markets']) ) {
	
		foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'] as $pair_key => $pair_unused ) {
		
			if ( $pairing == $pair_key ) {
		
				// Include a basic array check, since we want valid data to avoid an endless loop in our fallback support
				if ( !is_array($app_config['portfolio_assets']['BTC']['market_pairing'][$pair_key]) ) {
				return false;
				}
				
				foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pair_key] as $market_key => $market_value ) {
					
					// Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
					if ( sizeof($app_config['portfolio_assets']['BTC']['market_pairing'][$pair_key]) > 1 && array_key_exists($pairing, $app_config['preferred_bitcoin_markets']) ) {
					$whitelist = $app_config['preferred_bitcoin_markets'][$pairing];
					}
					
					if ( isset($whitelist) && $whitelist == $market_key && !array_key_exists($market_key, $btc_pairing_markets_blacklist)
					|| !isset($whitelist) && !array_key_exists($market_key, $btc_pairing_markets_blacklist) ) {
						
   				$btc_pairing_markets[$pairing.'_btc'] = number_format( (1 /  asset_market_data(strtoupper($pair_key), $market_key, $market_value)['last_trade'] ), 8, '.', '');
   				
   				$result = $btc_pairing_markets[$pairing.'_btc'];
   					
   					// Fallback support, if no data returned
   					if ( !isset($result) || number_to_string($result) < 0.00000001 || !is_numeric($result) ) {
   						
   					$btc_pairing_markets_blacklist[] = $market_key; // Blacklist getting pairing data from this exchange IN ANY PAIRING, for this runtime only
   					
   					app_logging('other_error', 'pairing_market_value() update failure for ' . $pairing, 'blacklisted_exchange: ' . $market_key);
   					
   					return pairing_market_value($pairing);
   					
   					}
   					else {
   						
   						// Data debugging telemetry
							if ( $app_config['debug_mode'] == 'all' || $app_config['debug_mode'] == 'telemetry' ) {
							app_logging('other_debugging', 'pairing_market_value() update succeeded for ' . $pairing, 'exchange: ' . $market_key);
							}
							
   					return $result;
   					
   					}
   		
   				
					}
						
				}
				return false; // If we made it this deep in the logic, no data was found	
   		
   		}
		
		}
	
	}
   else {
   return false;
   }
   
   
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function steempower_time($time) {
    
global $_POST, $steem_market, $app_config, $btc_primary_currency_value;

$powertime = null;
$powertime = null;
$steem_total = null;
$primary_currency_total = null;

$decimal_yearly_interest = $app_config['steempower_yearly_interest'] / 100;  // Convert APR in config to decimal representation

$speed = ($_POST['sp_total'] * $decimal_yearly_interest) / 525600;  // Interest per minute

    if ( $time == 'day' ) {
    $powertime = ($speed * 60 * 24);
    }
    elseif ( $time == 'week' ) {
    $powertime = ($speed * 60 * 24 * 7);
    }
    elseif ( $time == 'month' ) {
    $powertime = ($speed * 60 * 24 * 30);
    }
    elseif ( $time == '2month' ) {
    $powertime = ($speed * 60 * 24 * 60);
    }
    elseif ( $time == '3month' ) {
    $powertime = ($speed * 60 * 24 * 90);
    }
    elseif ( $time == '6month' ) {
    $powertime = ($speed * 60 * 24 * 180);
    }
    elseif ( $time == '9month' ) {
    $powertime = ($speed * 60 * 24 * 270);
    }
    elseif ( $time == '12month' ) {
    $powertime = ($speed * 60 * 24 * 365);
    }
    
    $powertime_primary_currency = ( $powertime * $steem_market * $btc_primary_currency_value );
    
    $steem_total = ( $powertime + $_POST['sp_total'] );
    $primary_currency_total = ( $steem_total * $steem_market * $btc_primary_currency_value );
    
    $power_purchased = ( $_POST['sp_purchased'] / $steem_total );
    $power_earned = ( $_POST['sp_earned'] / $steem_total );
    $power_interest = 1 - ( $power_purchased + $power_earned );
    
    $powerdown_total = ( $steem_total / $app_config['steem_powerdown_time'] );
    $powerdown_purchased = ( $powerdown_total * $power_purchased );
    $powerdown_earned = ( $powerdown_total * $power_earned );
    $powerdown_interest = ( $powerdown_total * $power_interest );
    
    ?>
    
<div class='result'>
    <h2> Interest Per <?=ucfirst($time)?> </h2>
    <ul>
        
        <li><b><?=number_format( $powertime, 3, '.', ',')?> STEEM</b> <i>in interest</i> (after a <?=$time?> time period) = <b><?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( $powertime_primary_currency, 2, '.', ',')?></b></li>
        
        <li><b><?=number_format( $steem_total, 3, '.', ',')?> STEEM</b> <i>in total</i> (including original vested amount) = <b><?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( $primary_currency_total, 2, '.', ',')?></b></li>
    
    </ul>

  <p><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></p>
        <table border='1' cellpadding='10' cellspacing='0'>
            <tr>
        <th class='normal'> Purchased </th>
        <th class='normal'> Earned </th>
        <th class='normal'> Interest </th>
        <th> Total </th>
            </tr>
                <tr>

                <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> STEEM = <?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_purchased), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> STEEM = <?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_earned), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> STEEM = <?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_interest), 2, '.', ',')?> </td>
                <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> STEEM</b> = <b><?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=number_format( powerdown_primary_currency($powerdown_total), 2, '.', ',')?></b> </td>

                </tr>
           
        </table>     
        
</div>

    <?php
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function mining_calc_form($calculation_form_data, $network_measure, $hash_unit='hash') {

global $_POST, $app_config;

?>

				<form name='<?=$calculation_form_data[1]?>' action='<?=start_page('mining_calculators')?>' method='post'>
				
				
				<p><b><?=ucfirst($network_measure)?>:</b> 
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				
				<input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' /> 
				
				(uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)
				
				<?php
				}
				elseif ( $hash_unit == 'graph' ) {
				?>
				
				<input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' id='network_measure_<?=$calculation_form_data[1]?>' />
				
				<select name='cuckoo_cycles'>
				<option value='29' <?=( $_POST['cuckoo_cycles'] == '29' ? 'selected' : '' )?>>Cuckoo 29 </option>
				<option value='30' <?=( $_POST['cuckoo_cycles'] == '30' ? 'selected' : '' )?>>Cuckoo 30 </option>
				<option value='31' <?=( $_POST['cuckoo_cycles'] == '31' ? 'selected' : '' )?>>Cuckoo 31 </option>
				<option value='32' <?=( $_POST['cuckoo_cycles'] == '32' ? 'selected' : '' )?>>Cuckoo 32 </option>
				<option value='33' <?=( $_POST['cuckoo_cycles'] == '33' ? 'selected' : '' )?>>Cuckoo 33 </option>
				<option value='34' <?=( $_POST['cuckoo_cycles'] == '34' ? 'selected' : '' )?>>Cuckoo 34 </option>
				</select> 
				
				(uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)
				
				<?php
				}
				?>
				</p>
				
				
				<p><b>Your Hashrate:</b>  
				<input type='text' value='<?=( $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' /> 
				
				
				
				<?php
				if ( $hash_unit == 'hash' ) {
				?>
				<select name='hash_level'>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (hashes per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (thousand hashes per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (million hashes per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (billion hashes per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (trillion hashes per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (quadrillion hashes per second) </option>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (quintillion hashes per second) </option>
				</select>
				
				<?php
				}
				elseif ( $hash_unit == 'graph' ) {
				?>
				<select name='hash_level'>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Gps (graphs per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Kgps (thousand graphs per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mgps (million graphs per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ggps (billion graphs per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Tgps (trillion graphs per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Pgps (quadrillion graphs per second) </option>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Egps (quintillion graphs per second) </option>
				</select>
				
				<?php
				}
				?>
				
				
				</p>
				
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['block_reward'] : $app_config['mining_rewards'][$calculation_form_data[1]] )?>' name='block_reward' /> (may be static from config.php file, verify current block reward manually)</p>
				
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
				
				
				<p><b>kWh Rate (<?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?>/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
				
				
				<p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
				    
				    
			   <input type='hidden' value='1' name='<?=$calculation_form_data[1]?>_submitted' />
				
				<input type='submit' value='Calculate <?=strtoupper($calculation_form_data[1])?> Mining Profit' />
	
				</form>
				

<?php
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function charts_and_price_alerts($asset_data, $exchange, $pairing, $mode) {


// Globals
global $base_dir, $app_config, $default_btc_primary_exchange, $default_btc_primary_currency_value, $default_btc_primary_currency_pairing;


// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, mb_strpos($asset_data, "-", 0, 'utf-8') ) );
$asset = strtoupper($asset);



// Fiat or equivalent pairing?
// #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
if ( array_key_exists($pairing, $app_config['bitcoin_currency_markets']) && !array_key_exists($pairing, $app_config['crypto_to_crypto_pairing']) ) {
$fiat_eqiv = 1;
}



	// Get any necessary variables for calculating asset's PRIMARY CURRENCY CONFIG value


	// Consolidate function calls for runtime speed improvement
	$asset_market_data = asset_market_data($asset, $exchange, $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange], $pairing);
   
   
	// Get asset PRIMARY CURRENCY CONFIG value
	


	// PRIMARY CURRENCY CONFIG CHARTS
	if ( $pairing == strtolower($default_btc_primary_currency_pairing) ) {
	$asset_primary_currency_value_raw = $asset_market_data['last_trade']; 
	}
	// BTC PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
	elseif ( $pairing == 'btc' ) {
	$asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * $asset_market_data['last_trade'] , 8, '.', '');
	}
	// OTHER PAIRINGS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
	else {
		
	$pairing_btc_value = pairing_market_value($pairing); 
	
		if ( $pairing_btc_value == false ) {
		app_logging('other_error', 'pairing_market_value() returned false in charts_and_price_alerts()', 'pairing: ' . $pairing);
		}
	
	$asset_primary_currency_value_raw = number_format( $default_btc_primary_currency_value * ( $asset_market_data['last_trade'] * $pairing_btc_value ) , 8, '.', '');
	
	}
	
	
	
	$asset_pairing_value_raw = number_format( $asset_market_data['last_trade'] , 8, '.', '');
		
		
	$volume_asset_raw = $asset_market_data['24hr_asset_volume'];  // NEEDED FOR EMULATING PAIRING VOLUME, IF IT'S NOT AVAILABLE
	$volume_pairing_raw = $asset_market_data['24hr_pairing_volume']; // If available, we'll use this for chart volume UX
	$volume_primary_currency_raw = $asset_market_data['24hr_primary_currency_volume'];
	
	
	
	// If no pair volume is available for this market, emulate it within reason with: asset value * asset volume
	$volume_pairing_raw = ( number_to_string($volume_pairing_raw) > 0 ? $volume_pairing_raw : ($asset_pairing_value_raw * $volume_asset_raw) );
	
	
	
	// Make sure we have basic values, otherwise log errors / return false
	
	// Return false if we have no $default_btc_primary_currency_value
	if ( !isset($default_btc_primary_currency_value) || $default_btc_primary_currency_value == 0 ) {
	app_logging('other_error', 'charts_and_price_alerts() - No Bitcoin '.strtoupper($default_btc_primary_currency_pairing).' value set for chart/alert "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ';' );
	$set_return = 1;
	}
	
	
	// Return false if we have no asset value
	if ( number_to_string( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
	// Continue
	}
	else {
	app_logging('other_error', 'charts_and_price_alerts() - No asset '.strtoupper($default_btc_primary_currency_pairing).' value set for chart/alert "' . $asset_data . '"', $asset_data . ': ' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . '; pairing_id: ' . $app_config['portfolio_assets'][$asset]['market_pairing'][$pairing][$exchange] . ';' );
	$set_return = 1;
	}
	
	
	if ( $set_return == 1 ) {
	return false;
	}
	
	
   
	
	
	// Optimizing storage size needed for charts data
	
	// Round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts / for prettier numbers UX, and to save on data set / storage size
	$volume_primary_currency_raw = ( isset($volume_primary_currency_raw) ? round($volume_primary_currency_raw) : null );		
	
	
	// Round PAIRING volume to only keep 3 decimals max (for crypto volume etc), to save on data set / storage size
	$volume_pairing_raw = ( isset($volume_pairing_raw) ? round($volume_pairing_raw, ( $fiat_eqiv == 1 ? 0 : 3 ) ) : null );	
	
	
	// Round PRIMARY CURRENCY CONFIG asset price to only keep $app_config['primary_currency_decimals_max'] decimals maximum 
	// (or only 2 decimals if worth $app_config['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
	$asset_primary_currency_value_raw = ( number_to_string($asset_primary_currency_value_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? round($asset_primary_currency_value_raw, 2) : round($asset_primary_currency_value_raw, $app_config['primary_currency_decimals_max']) );
	
	
	// If fiat equivalent format, round asset price 
	// to only keep $app_config['primary_currency_decimals_max'] decimals maximum 
	// (or only 2 decimals if worth $app_config['primary_currency_decimals_max_threshold'] or more), to save on data set / storage size
   if ( $fiat_eqiv == 1 ) {
   $asset_pairing_value_raw = ( number_to_string($asset_pairing_value_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? round($asset_pairing_value_raw, 2) : round($asset_pairing_value_raw, $app_config['primary_currency_decimals_max']) );
   }


	// Remove any leading / trailing zeros from CRYPTO asset price, to save on data set / storage size
	$asset_pairing_value_raw = number_to_string($asset_pairing_value_raw);


	// Remove any leading / trailing zeros from PAIRING VOLUME, to save on data set / storage size
	$volume_pairing_raw = number_to_string($volume_pairing_raw);
	
	
	// WE USE PAIRING VOLUME FOR VOLUME PERCENTAGE CHANGES, FOR BETTER PERCENT CHANGE ACCURACY THAN FIAT EQUIV
	$alert_cache_contents = $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw . '||' . $volume_pairing_raw;
	
	
	
	
	
	// Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
	
	if ( file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	
   $last_check_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
   
   	if ( number_to_string($last_check_days) >= 365 ) {
   	$last_check_time = number_format( ($last_check_days / 365) , 2, '.', ',') . ' years';
   	}
   	elseif ( number_to_string($last_check_days) >= 30 ) {
   	$last_check_time = number_format( ($last_check_days / 30) , 2, '.', ',') . ' months';
   	}
   	elseif ( number_to_string($last_check_days) >= 7 ) {
   	$last_check_time = number_format( ($last_check_days / 7) , 2, '.', ',') . ' weeks';
   	}
   	else {
   	$last_check_time = number_format($last_check_days, 2, '.', ',') . ' days';
   	}
   
	}
	

$last_check_days = number_to_string($last_check_days); // Better decimal support for whale alerts etc


$data_file = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );

$cached_array = explode("||", $data_file);




	// Make sure numbers are cleanly pulled from cache file
	
	foreach ( $cached_array as $key => $value ) {
	$cached_array[$key] = remove_number_format($value);
	}




	// Backwards compatibility
	
	if ( $cached_array[0] == null ) {
	$cached_asset_primary_currency_value = $data_file;
	$cached_primary_currency_volume = -1;
	$cached_pairing_volume = -1;
	}
	else {
	$cached_asset_primary_currency_value = $cached_array[0];  // PRIMARY CURRENCY CONFIG token value
	$cached_primary_currency_volume = round($cached_array[1]); // PRIMARY CURRENCY CONFIG volume value (round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts skewing checks)
	$cached_pairing_volume = $cached_array[2]; // Crypto volume value (more accurate percent increase / decrease stats than PRIMARY CURRENCY CONFIG value fluctuations)
	}





	////// If cached value and current value exist, run alert checking ////////////
	
	if ( number_to_string( trim($cached_asset_primary_currency_value) ) >= 0.00000001 && number_to_string( trim($asset_primary_currency_value_raw) ) >= 0.00000001 ) {
	
	
	
	
  			 // Price checks
  			 
  			 // PRIMARY CURRENCY CONFIG price percent change (!MUST BE! absolute value)
          $percent_change = abs( ($asset_primary_currency_value_raw - $cached_asset_primary_currency_value) / abs($cached_asset_primary_currency_value) * 100 );
          
          $percent_change = number_to_string($percent_change); // Better decimal support
          
  			 
  			 // Check whether we should send an alert
          if ( number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && $percent_change >= $app_config['price_alerts_threshold'] ) {
          $send_alert = 1;
          }
          
          // UX / UI variables
          if ( number_to_string($asset_primary_currency_value_raw) < number_to_string($cached_asset_primary_currency_value) ) {
          $change_symbol = '-';
          $increase_decrease = 'decreased';
          }
          elseif ( number_to_string($asset_primary_currency_value_raw) >= number_to_string($cached_asset_primary_currency_value) ) {
          $change_symbol = '+';
          $increase_decrease = 'increased';
          }
          
          
          
          
          // Crypto volume checks
          
          // Crypto volume percent change (!MUST BE! absolute value)
          $volume_percent_change = abs( ($volume_pairing_raw - $cached_pairing_volume) / abs($cached_pairing_volume) * 100 );
          
          $volume_percent_change = number_to_string($volume_percent_change); // Better decimal support
          
          // UX adjustments, and UI / UX variables
          if ( $cached_primary_currency_volume <= 0 && $volume_primary_currency_raw <= 0 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
          $volume_percent_change = 0; // Skip calculating percent change if cached / live PRIMARY CURRENCY CONFIG volume are both zero or -1 (exchange API error)
          $volume_change_symbol = '+';
          }
          elseif ( $cached_primary_currency_volume <= 0 && $volume_pairing_raw >= $cached_pairing_volume ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
          $volume_percent_change = $volume_primary_currency_raw; // Use PRIMARY CURRENCY CONFIG volume value for percent up, for UX sake, if volume is up from zero or -1 (exchange API error)
          $volume_change_symbol = '+';
          }
          elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw < $cached_pairing_volume ) {
          $volume_change_symbol = '-';
          }
          elseif ( $cached_primary_currency_volume > 0 && $volume_pairing_raw > $cached_pairing_volume ) {
          $volume_change_symbol = '+';
          }
          
          
          

          // Whale alert (price change average of X or greater over X day(s) or less, with X percent pair volume increase average that is at least a X primary currency volume increase average)
			 $whale_alert_threshold = explode("||", $app_config['price_alerts_whale_alert_threshold']);

          if ( trim($whale_alert_threshold[0]) != '' && trim($whale_alert_threshold[1]) != '' && trim($whale_alert_threshold[2]) != '' && trim($whale_alert_threshold[3]) != '' ) {
          
          $whale_max_days_to_24hr_average_over = number_to_string( trim($whale_alert_threshold[0]) );
          
          $whale_min_price_percent_change_24hr_average = number_to_string( trim($whale_alert_threshold[1]) );
          
          $whale_min_volume_percent_increase_24hr_average = number_to_string( trim($whale_alert_threshold[2]) );
          
          $whale_min_volume_currency_increase_24hr_average = number_to_string( trim($whale_alert_threshold[3]) );
          
          
          	// WE ONLY WANT PRICE CHANGE PERCENT AS AN ABSOLUTE VALUE HERE, ALL OTHER VALUES SHOULD BE ALLOWED TO BE NEGATIVE IF THEY ARE NEGATIVE
   		 	if ( $last_check_days <= $whale_max_days_to_24hr_average_over 
   		 	&& number_to_string($percent_change / $last_check_days) >= $whale_min_price_percent_change_24hr_average 
   		 	&& number_to_string($volume_change_symbol . $volume_percent_change / $last_check_days) >= $whale_min_volume_percent_increase_24hr_average 
   		 	&& number_to_string( ($volume_primary_currency_raw - $cached_primary_currency_volume) / $last_check_days ) >= $whale_min_volume_currency_increase_24hr_average ) {
   		 	$whale_alert = 1;
   		 	}
   		 	
   		 
   		 }
   		 
   		 
          
          // We disallow alerts where minimum 24 hour trade PRIMARY CURRENCY CONFIG volume has NOT been met, ONLY if an API request doesn't fail to retrieve volume data
          if ( $volume_primary_currency_raw >= 0 && $volume_primary_currency_raw < $app_config['price_alerts_min_volume'] ) {
          $send_alert = null;
          }
  
  
  
  
          // We disallow alerts if they are not activated
          if ( $mode != 'both' && $mode != 'alert' ) {
          $send_alert = null;
          }
  
  
          // We disallow alerts if $app_config['price_alerts_block_volume_error'] is on, and there is a volume retrieval error
          // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
          if ( $volume_primary_currency_raw == -1 && $app_config['price_alerts_block_volume_error'] == 'on' ) {
          $send_alert = null;
          }
          
          
          
          
          
          
          // Sending the alerts
          
          if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['price_alerts_freq_max'] * 60 ) ) == true && $send_alert == 1 ) {
          
          
          
          
  				// Message formatting for display to end user
          	
          	$desc_alert_type = ( $app_config['price_alerts_refresh'] > 0 ? 'refresh' : 'alert' );
          	
          	// IF PRIMARY CURRENCY CONFIG volume was zero last alert / refresh, for UX sake 
          	// we use current PRIMARY CURRENCY CONFIG volume instead of current pair volume (for percent up, so it's not up 70,000% for altcoins lol)
          	if ( $cached_primary_currency_volume == 0 ) {
          	$volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was '.$app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing].'0 last price ' . $desc_alert_type . ', and ';
          	$volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from '.$app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing].'0 last ' . $desc_alert_type;
          	}
          	// Best we can do feasibly for UX on volume reporting errors
          	elseif ( $cached_primary_currency_volume == -1 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
          	$volume_describe = strtoupper($default_btc_primary_currency_pairing) . ' volume was NULL last price ' . $desc_alert_type . ', and ';
          	$volume_describe_mobile = strtoupper($default_btc_primary_currency_pairing) . ' volume up from NULL last ' . $desc_alert_type;
          	}
          	else {
          	$volume_describe = 'pair volume ';
          	$volume_describe_mobile = 'pair volume'; // no space
          	}
          
          
          
          
          	// Pretty up textual output to end-user (convert raw numbers to have separators, remove underscores in names, etc)
          	
  				$exchange_text = snake_case_to_name($exchange);
  				
  				// Pretty numbers UX on PRIMARY CURRENCY CONFIG asset value
  				$asset_primary_currency_text = ( number_to_string($asset_primary_currency_value_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? pretty_numbers($asset_primary_currency_value_raw, 2) : pretty_numbers($asset_primary_currency_value_raw, $app_config['primary_currency_decimals_max']) );
  				
  				$percent_change_text = number_format($percent_change, 2, '.', ',');
  				
  				$volume_primary_currency_text = $app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($volume_primary_currency_raw, 0, '.', ',');
  				
  				$volume_change_text = 'has ' . ( $volume_change_symbol == '+' ? 'increased ' : 'decreased ' ) . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% to a ' . strtoupper($default_btc_primary_currency_pairing) . ' value of';
  				
  				$volume_change_text_mobile = '(' . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% ' . $volume_describe_mobile . ')';
  				
  				
  				
  				
  				
  				// If -1 from exchange API error not reporting any volume data (not even zero)
  				// ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
  				if ( $cached_primary_currency_volume == -1 || $volume_primary_currency_raw == -1 ) {
  				$volume_change_text = null;
  				$volume_change_text_mobile = null;
  				}
          	
          	
          	
          	
          	// Format trade volume data
          	
          	// Minimum volume filter skipped message, only if filter enabled and error getting trade volume data (otherwise is NULL)
          	if ( $volume_primary_currency_raw == null && $app_config['price_alerts_min_volume'] > 0 || $volume_primary_currency_raw < 1 && $app_config['price_alerts_min_volume'] > 0 ) {
          	$volume_filter_skipped_text = ', so enabled minimum volume filter was skipped';
          	}
          	else {
          	$volume_filter_skipped_text = null;
          	}
          	
          	
          	
          	// Successfully received > 0 volume data, at or above an enabled minimum volume filter
  				if ( $volume_primary_currency_raw > 0 && $app_config['price_alerts_min_volume'] > 0 && $volume_primary_currency_raw >= $app_config['price_alerts_min_volume'] ) {
          	$email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ' (minimum volume filter set at ' . $app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($app_config['price_alerts_min_volume'], 0, '.', ',') . ').';
          	}
          	// NULL if not setup to get volume, negative number returned if no data received from API, therefore skipping any enabled volume filter
          	// ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
  				elseif ( $volume_primary_currency_raw == -1 ) { 
          	$email_volume_summary = 'No data received for 24 hour volume' . $volume_filter_skipped_text . '.';
          	$volume_primary_currency_text = 'No data';
          	}
          	// If volume is zero or greater in successfully received volume data, without an enabled volume filter (or filter skipped)
          	// IF exchange PRIMARY CURRENCY CONFIG value price goes up/down and triggers alert, 
          	// BUT current reported volume is zero (temporary error on exchange side etc, NOT on our app's side),
          	// inform end-user of this probable volume discrepancy being detected.
          	elseif ( $volume_primary_currency_raw >= 0 ) {
          	$email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_primary_currency_text . ( $volume_primary_currency_raw == 0 ? ' (probable volume discrepancy detected' . $volume_filter_skipped_text . ')' : '' ) . '.'; 
          	}
  				
  				
  				
  				
  				
  				// Build the different messages, configure comm methods, and send messages
				
  				$email_message = ( $whale_alert == 1 ? 'WHALE ALERT: ' : '' ) . 'The ' . $asset . ' trade value in the ' . strtoupper($pairing) . ' market at the ' . $exchange_text . ' exchange has ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over the past ' . $last_check_time . ' since the last price ' . $desc_alert_type . '. ' . $email_volume_summary;
  				
  				// Were're just adding a human-readable timestamp to smart home (audio) alerts
  				$notifyme_message = $email_message . ' Timestamp is ' . time_date_format($app_config['local_time_offset'], 'pretty_time') . '.';
  				
  				$text_message = ( $whale_alert == 1 ? '🐳 ' : '' ) . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in ' . strtoupper($default_btc_primary_currency_pairing) . ' value to ' . $app_config['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . $asset_primary_currency_text . ' over ' . $last_check_time . '. 24 Hour ' . strtoupper($default_btc_primary_currency_pairing) . ' Volume: ' . $volume_primary_currency_text . ' ' . $volume_change_text_mobile;
  				
  				
  				
  				
  				// Cache the new lower / higher value + volume data
          	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
          	
          	
          	
          	
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
  				
  				// Minimize function calls
  				$encoded_text_message = content_data_encoding($text_message);
  				
          	$send_params = array(
          								'notifyme' => $notifyme_message,
          								'telegram' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message, // Add emoji here, so it's not sent with alexa / google home alerts
          								'text' => array(
          														// Unicode support included for text messages (emojis / asian characters / etc )
          														'message' => $encoded_text_message['content_output'],
          														'charset' => $encoded_text_message['charset']
          														),
          								'email' => array(
          														'subject' => $asset . ' Asset Value '.ucfirst($increase_decrease).' Alert' . ( $whale_alert == 1 ? ' (🐳 WHALE ALERT)' : '' ),
          														'message' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_message // Add emoji here, so it's not sent with alexa / google home alerts
          														)
          								);
          	
          	
          	
          	// Send notifications
          	@queue_notifications($send_params);
  
          
     		 
          }
          
          
          
          
  
	}
	////// END alert checking //////////////
	
	
	
	


	// Cache a price alert value / volumes if not already done, OR if config setting set to refresh every X days
	
	if ( $mode == 'both' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat')
	|| $mode == 'alert' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
	}
	elseif ( $mode == 'both' && $send_alert != 1 && $app_config['price_alerts_refresh'] >= 1 && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['price_alerts_refresh'] * 1440 ) ) == true
	|| $mode == 'alert' && $send_alert != 1 && $app_config['price_alerts_refresh'] >= 1 && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $app_config['price_alerts_refresh'] * 1440 ) ) == true ) {
	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $alert_cache_contents); 
	}





	// If the charts page is enabled in config.php, save latest chart data for assets with price alerts configured on them
	
	if ( $mode == 'both' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['charts_page'] == 'on'
	|| $mode == 'chart' && number_to_string($asset_primary_currency_value_raw) >= 0.00000001 && $app_config['charts_page'] == 'on' ) { 
	
		
	// PRIMARY CURRENCY CONFIG charts (CRYPTO/PRIMARY CURRENCY CONFIG markets, 
	// AND ALSO crypto-to-crypto pairings converted to PRIMARY CURRENCY CONFIG equiv value for PRIMARY CURRENCY CONFIG equiv charts)
	store_file_contents($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.strtolower($default_btc_primary_currency_pairing).'.dat', time() . '||' . $asset_primary_currency_value_raw . '||' . $volume_primary_currency_raw . "\n", "append"); 
		
		// Crypto / secondary currency pairing charts, volume as pairing (for UX)
		if ( $pairing != strtolower($default_btc_primary_currency_pairing) ) {
		store_file_contents($base_dir . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.$pairing.'.dat', time() . '||' . $asset_pairing_value_raw . '||' . $volume_pairing_raw . "\n", "append");
		}
			
		
	}
	
	
	


// If we haven't returned false yet because of any issues being detected, return TRUE to indicate all seems ok

return true;


}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function ui_coin_data_row($asset_name, $asset_symbol, $asset_amount, $market_pairing_array, $selected_pairing, $selected_exchange, $purchase_price=NULL, $leverage_level, $selected_margintype) {


// Globals
global $_POST, $btc_worth_array, $coin_stats_array, $td_color_zebra, $cap_data_force_usd, $selected_btc_primary_exchange, $selected_btc_primary_currency_pairing, $theme_selected, $primary_currency_market_standalone, $app_config, $btc_primary_currency_value, $alert_percent;



$rand_id = rand(10000000,100000000);
  
$sort_order = ( array_search($asset_symbol, array_keys($app_config['portfolio_assets'])) + 1);

$original_market = $selected_exchange;

$all_markets = $market_pairing_array;  // All markets for this pairing

$all_pairings = $app_config['portfolio_assets'][$asset_symbol]['market_pairing'];



  // Update, get the selected market name
  
  $loop = 0;
   foreach ( $all_markets as $key => $value ) {
   
    if ( $loop == $selected_exchange || $key == "eth_subtokens_ico" ) {
    $selected_exchange = $key;
     
     if ( sizeof($primary_currency_market_standalone) != 2 && strtolower($asset_name) == 'bitcoin' ) {
     $selected_btc_primary_exchange = $key;
     $selected_btc_primary_currency_pairing = $selected_pairing;
     
     		// Dynamically modify MISCASSETS in $app_config['portfolio_assets']
			// ONLY IF USER HASN'T MESSED UP $app_config['portfolio_assets'], AS WE DON'T WANT TO CANCEL OUT ANY
			// CONFIG CHECKS CREATING ERROR LOG ENTRIES / UI ALERTS INFORMING THEM OF THAT
			if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
     		$app_config['portfolio_assets']['MISCASSETS']['coin_name'] = 'Misc. '.strtoupper($selected_pairing).' Value';
     		}

     ?>
     
     <script>
     window.btc_primary_currency_value = '<?=asset_market_data('BTC', $key, $app_config['portfolio_assets']['BTC']['market_pairing'][$selected_pairing][$key])['last_trade']?>';
     
     window.btc_primary_currency_pairing = '<?=strtoupper($selected_pairing)?>';
     </script>
     
     <?php
     }
     
    }
   
   $loop = $loop + 1;
   }
  $loop = null; 





if ( sizeof($primary_currency_market_standalone) != 2 && isset($selected_btc_primary_exchange) ) {
$app_config['btc_primary_exchange'] = $selected_btc_primary_exchange;
}

if ( sizeof($primary_currency_market_standalone) != 2 && isset($selected_btc_primary_currency_pairing) ) {
$app_config['btc_primary_currency_pairing'] = $selected_btc_primary_currency_pairing;
}




// Overwrite PRIMARY CURRENCY CONFIG / BTC market value, in case user changed preferred market IN THE UI
$selected_pairing_id = $app_config['portfolio_assets']['BTC']['market_pairing'][$app_config['btc_primary_currency_pairing']][$app_config['btc_primary_exchange']];
$btc_primary_currency_value = asset_market_data('BTC', $app_config['btc_primary_exchange'], $selected_pairing_id)['last_trade'];


	// Log any Bitcoin market errors
	if ( !isset($btc_primary_currency_value) || $btc_primary_currency_value == 0 ) {
	app_logging('other_error', 'ui_coin_data_row() Bitcoin primary currency value not properly set', 'exchange: ' . $app_config['btc_primary_exchange'] . '; pairing_id: ' . $selected_pairing_id . '; value: ' . $btc_primary_currency_value );
	}



$market_pairing = $all_markets[$selected_exchange];




  // Start rendering table row, if value set
  if ( $asset_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
    
    
    
    // UI table coloring
    if ( !$td_color_zebra || $td_color_zebra == '#e8e8e8' ) {
    $td_color_zebra = 'white';
    }
    else {
    $td_color_zebra = '#e8e8e8';
    }

	
  
	 // Get coin values, including non-BTC pairings
	 
    $pairing_symbol = strtoupper($selected_pairing);
    
    
    // Consolidate function calls for runtime speed improvement
    $asset_market_data = asset_market_data($asset_symbol, $selected_exchange, $market_pairing, $selected_pairing);
	 
	 
	 // BTC PAIRINGS
    if ( $selected_pairing == 'btc' ) {
    $coin_value_raw = $asset_market_data['last_trade'];
    $btc_trade_eqiv = number_format($coin_value_raw, 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = $coin_value_total_raw *  $btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = $coin_value_total_raw;
    }
    // ETH ICOS
    elseif ( $selected_pairing == 'eth' && $selected_exchange == 'eth_subtokens_ico' ) {
    $pairing_btc_value = pairing_market_value($selected_pairing);
		if ( $pairing_btc_value == false ) {
		app_logging('other_error', 'pairing_market_value() returned false in ui_coin_data_row()', 'pairing: ' . $pairing);
		}
    $coin_value_raw = get_sub_token_price($selected_exchange, $market_pairing);
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = ($coin_value_total_raw * $pairing_btc_value) *  $btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = number_to_string($coin_value_total_raw * $pairing_btc_value);  
    }
    // OTHER PAIRINGS
    else {
    $pairing_btc_value = pairing_market_value($selected_pairing);
		if ( $pairing_btc_value == false ) {
		app_logging('other_error', 'pairing_market_value() returned false in ui_coin_data_row()', 'pairing: ' . $pairing);
		}
    $coin_value_raw = $asset_market_data['last_trade'];
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $coin_value_total_raw = ($asset_amount * $coin_value_raw);
  	 $coin_primary_currency_worth_raw = ($coin_value_total_raw * $pairing_btc_value) *  $btc_primary_currency_value;
    $btc_worth_array[$asset_symbol] = ( strtolower($asset_name) == 'bitcoin' ? $asset_amount : number_to_string($coin_value_total_raw * $pairing_btc_value) );
  	 }
	
  	 
  	 
  	 
    // FLAG SELECTED PAIRING IF FIAT EQUIVALENT formatting should be used, AS SUCH
    // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
    if ( array_key_exists($selected_pairing, $app_config['bitcoin_currency_markets']) && !array_key_exists($selected_pairing, $app_config['crypto_to_crypto_pairing']) ) {
	 $fiat_eqiv = 1;
    }
    
  
	 
	 
  	 // Calculate gain / loss if purchase price was populated
	 if ( $purchase_price >= 0.00000001 ) {
	 	
	 $coin_paid_total_raw = ($asset_amount * $purchase_price);
	 
	 $gain_loss = $coin_primary_currency_worth_raw - $coin_paid_total_raw;
	 	 
	 	 
	 	// Convert $gain_loss for shorts with leverage
		if ( $leverage_level >= 2 && $selected_margintype == 'short' ) {
  		
 		$prev_gain_loss_val = $gain_loss;
 			
 			if ( $prev_gain_loss_val >= 0 ) {
 	 		$gain_loss = $prev_gain_loss_val - ( $prev_gain_loss_val * 2 );
 	 		$coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw - ( $prev_gain_loss_val * 2 );
 		 	}
 	 		else {
 		 	$gain_loss = $prev_gain_loss_val + ( abs($prev_gain_loss_val) * 2 );
 			$coin_primary_currency_worth_raw = $coin_primary_currency_worth_raw + ( abs($prev_gain_loss_val) * 2 );
 	 		}

 	 	}
	 
	 
	 // Gain / loss percent (!MUST NOT BE! absolute value)
	 $gain_loss_percent = ($coin_primary_currency_worth_raw - $coin_paid_total_raw) / abs($coin_paid_total_raw) * 100;
	 
	 // Check for any leverage gain / loss
	 $only_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * ($leverage_level - 1) ) : 0 );
	 
	 $inc_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * $leverage_level) : $gain_loss );
	 
	 $inc_leverage_gain_loss_percent =  ( $leverage_level >= 2 ? ($gain_loss_percent * $leverage_level) : $gain_loss_percent );
	 
    
	 }
	 else {
	 $no_purchase_price = 1;
	 }
	  
	 
	 
	 
	 
    $coin_stats_array[] = array(
    													'coin_symbol' => $asset_symbol, 
    													'coin_leverage' => $leverage_level,
    													'selected_margintype' => $selected_margintype,
    													'coin_worth_total' => $coin_primary_currency_worth_raw,
    													'coin_total_worth_if_purchase_price' => ($no_purchase_price == 1 ? null : $coin_primary_currency_worth_raw),
    													'coin_paid' => $purchase_price,
    													'coin_paid_total' => $coin_paid_total_raw,
    													'gain_loss_only_leverage' => $only_leverage_gain_loss,
    													'gain_loss_total' => $inc_leverage_gain_loss,
    													'gain_loss_percent_total' => $inc_leverage_gain_loss_percent,
    													);
    										




  // Get trade volume
  $trade_volume = $asset_market_data['24hr_primary_currency_volume'];
  
  
  
  // START rendering webpage UI output
  
  ?>


<!-- Coin data row START -->
<tr id='<?=strtolower($asset_symbol)?>_row'>
  


<td class='data border_lb'>

<span class='app_sort_filter'><?php echo $sort_order; ?></span>

</td>



<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; white-space: nowrap;'>
 
 
 <?php
 
 $mkcap_render_data = trim($app_config['portfolio_assets'][$asset_symbol]['marketcap_website_slug']);
 
// Consolidate function calls for runtime speed improvement
 $marketcap_data = marketcap_data($asset_symbol);
 
 $info_icon = ( !$marketcap_data['rank'] ? 'info-none.png' : 'info.png' );
 
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $app_config['primary_marketcap_site'] == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $app_config['primary_marketcap_site'] == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 		
 <img id='<?=$mkcap_render_data?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' border='0' style='position: absolute; top: 2px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' class='blue app_sort_filter'><?php echo $asset_name; ?></a>
 <script>

		<?php
		if ( !$marketcap_data['rank'] ) {
			
			if ( $app_config['primary_marketcap_site'] == 'coinmarketcap' && trim($app_config['coinmarketcapcom_api_key']) == null ) {
			?>

			var cmc_content = '<p class="coin_info"><span class="yellow"><?=ucfirst($app_config['primary_marketcap_site'])?> API key is required. <br />Configuration adjustments can be made in config.php.</span></p>';
	
			<?php
			}
			else {
			?>

			var cmc_content = '<p class="coin_info"><span class="yellow"><?=ucfirst($app_config['primary_marketcap_site'])?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$app_config['marketcap_ranks_max']?> marketcaps), <br />or API timeout set too low (current timeout is <?=$app_config['api_timeout']?> seconds). <br />Configuration adjustments can be made in config.php.</span></p>';
	
			<?php
			}

			if ( sizeof($alert_percent) > 1 ) {
			?>
			
			setTimeout(function() {
    		row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        	
        		if ( isset($cap_data_force_usd) ) {
        		$cmc_primary_currency_symbol = '$';
        		}
        		else {
        		$cmc_primary_currency_symbol = $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']];
        		}
        		
        ?> 
    
        var cmc_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=ucfirst($app_config['primary_marketcap_site'])?>.com Summary For <?=$asset_name?> (<?=$asset_symbol?>):</h5>'
        +'<p class="coin_info"><span class="yellow">Marketcap Ranking:</span> #<?=$marketcap_data['rank']?></p>'
        +'<p class="coin_info"><span class="yellow">Marketcap Value:</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['market_cap'],0,".",",")?></p>'
        +'<p class="coin_info"><span class="yellow">Available Supply:</span> <?=number_format($marketcap_data['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            if ( $marketcap_data['total_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Total Supply:</span> <?=number_format($marketcap_data['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $marketcap_data['max_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Maximum Supply:</span> <?=number_format($marketcap_data['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">Token Value (average):</span> <?=$cmc_primary_currency_symbol?><?=$marketcap_data['price']?></p>'
        <?php
            if ( $marketcap_data['percent_change_1h'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_1h'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_1h'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_1h'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">24 Hour Change:</span> <?=( stristr($marketcap_data['percent_change_24h'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_24h'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_24h'].'%</span>' )?></p>'
        <?php
            if ( $marketcap_data['percent_change_7d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">7 Day Change:</span> <?=( stristr($marketcap_data['percent_change_7d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_7d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_7d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_14d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">14 Day Change:</span> <?=( stristr($marketcap_data['percent_change_14d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_14d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_14d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_30d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">30 Day Change:</span> <?=( stristr($marketcap_data['percent_change_30d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_30d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_30d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_90d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">90 Day Change:</span> <?=( stristr($marketcap_data['percent_change_90d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_90d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_90d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_200d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">200 Day Change:</span> <?=( stristr($marketcap_data['percent_change_200d'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_200d'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_200d'].'%</span>' )?></p>'
        <?php
            }
            if ( $marketcap_data['percent_change_1y'] != null ) {
            ?>
        +'<p class="coin_info"><span class="yellow">1 Year Change:</span> <?=( stristr($marketcap_data['percent_change_1y'], '-') != false ? '<span class="red_bright">'.$marketcap_data['percent_change_1y'].'%</span>' : '<span class="green_bright">+'.$marketcap_data['percent_change_1y'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">24 Hour Volume:</span> <?=$cmc_primary_currency_symbol?><?=number_format($marketcap_data['volume_24h'],0,".",",")?></p>'
        <?php
            if ( $marketcap_data['last_updated'] != '' ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", $marketcap_data['last_updated'])?></p>'
        +'<p class="coin_info"><span class="yellow">App Cache Time:</span> <?=$app_config['marketcap_cache_time']?> minute(s)</p>'
        <?php
            }
            if ( $marketcap_data['app_notes'] != '' ) {
            ?>
        +'<p class="coin_info red_bright">Notes: <?=$marketcap_data['app_notes']?></p>'
        <?php
            }
            ?>
    
        +'<p class="coin_info"><span class="yellow">*Current config setting retrieves the top <?=$app_config['marketcap_ranks_max']?> rankings.</span></p>';
    
        <?php
        
        }
        ?>
    
        $('#<?=$mkcap_render_data?>').balloon({
        html: true,
        position: "right",
        contents: cmc_content,
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
    
    
    <?php
    
    
        if ( sizeof($alert_percent) > 1 ) {
    
        $percent_change_alert = $alert_percent[1];
    
        $percent_alert_type = $alert_percent[3];
    
    
            if ( $alert_percent[2] == '1hour' ) {
            $percent_change = $marketcap_data['percent_change_1h'];
            }
            elseif ( $alert_percent[2] == '24hour' ) {
            $percent_change = $marketcap_data['percent_change_24h'];
            }
            elseif ( $alert_percent[2] == '7day' ) {
            $percent_change = $marketcap_data['percent_change_7d'];
            }
          
         
            if ( stristr($percent_change_alert, '-') != false && $percent_change_alert >= $percent_change && is_numeric($percent_change) ) {
            ?>
         
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "yellow", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
            elseif ( stristr($percent_change_alert, '-') == false && $percent_change_alert <= $percent_change && is_numeric($percent_change) ) {
            ?>
            
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symbol)?>_row", "<?=$percent_alert_type?>", "green", "<?=$theme_selected?>");
            }, 1000);
            
            <?php
            }
        
        
        }
        ?>
     </script>
     
 <?php
	}
	else {
  ?>
  
  <img id='<?=$rand_id?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' border='0' style='position: absolute; top: 2px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <span class='blue app_sort_filter'><?=$asset_name?></span>
 <script>
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: '<p class="coin_info"><span class="yellow">No <?=ucfirst($app_config['primary_marketcap_site'])?>.com data for <?=$asset_name?> (<?=$asset_symbol?>) has been configured yet.</span></p>'
});

		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	row_alert("<?=strtolower($asset_symbol)?>_row", "visual", "no_cmc", "<?=$theme_selected?>"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
 
	<?php
	}
 
 ?>
 
 
</td>



<td class='data border_b'>


<?php
  
  $coin_primary_currency_value = ( $btc_primary_currency_value * $btc_trade_eqiv );

  // UX on FIAT EQUIV number values
  $coin_primary_currency_value = ( number_to_string($coin_primary_currency_value) >= $app_config['primary_currency_decimals_max_threshold'] ? pretty_numbers($coin_primary_currency_value, 2) : pretty_numbers($coin_primary_currency_value, $app_config['primary_currency_decimals_max']) );
	
  echo "<span class='white'>" . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . "</span>" . "<span class='app_sort_filter'>" . $coin_primary_currency_value . "</span>";

?>

</td>



<td class='data border_lb blue' align='right'>

<?php

	if ( strtoupper($asset_symbol) == 'MISCASSETS' ) {
	$asset_amount_decimals = 2;
	}
	else {
	$asset_amount_decimals = 8;
	}
	
$pretty_coin_amount = pretty_numbers($asset_amount, $asset_amount_decimals);

echo "<span class='app_sort_filter blue'>" . ( $pretty_coin_amount != null ? $pretty_coin_amount : 0 ) . "</span>";

?>

</td>



<td class='data border_b'><span class='app_sort_filter'>

<?php echo $asset_symbol; ?></span>

</td>



<td class='data border_lb'>
 
    <select class='app_sort_filter' name='change_<?=strtolower($asset_symbol)?>_market' onchange='
    $("#<?=strtolower($asset_symbol)?>_market").val(this.value);
    document.coin_amounts.submit();
    '>
        <?php
        foreach ( $all_markets as $market_key => $market_name ) {
         $loop = $loop + 1;
        ?>
        <option value='<?=($loop)?>' <?=( $original_market == ($loop -1) ? ' selected ' : '' )?>> <?=snake_case_to_name($market_key)?> </option>
        <?php
        }
        $loop = null;
        ?>
    </select>

</td>



<td class='data border_b'>

<span class='white'><?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?></span><span class='app_sort_filter'><?php 

  // NULL if not setup to get volume, negative number returned if no data received from API
  if ( $trade_volume == NULL || $trade_volume == -1 ) {
  echo '0';
  }
  elseif ( $trade_volume >= 0 ) {
  echo number_format($trade_volume, 0, '.', ',');
  }

?></span>

</td>



<td class='data border_b' align='right'>

<span class='app_sort_filter'>

<?php 

	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_primary_currency_decimals = ( number_to_string($coin_value_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? 2 : $app_config['primary_currency_decimals_max'] );
	}
  
echo ( $fiat_eqiv == 1 ? pretty_numbers($coin_value_raw, $coin_value_primary_currency_decimals) : pretty_numbers($coin_value_raw, 8) ); 

?>

</span>

<?php

  if ( $selected_pairing != 'btc' && strtolower($asset_name) != 'bitcoin' ) {
  echo '<div class="btc_worth">(' . pretty_numbers($btc_trade_eqiv, 8) . ' BTC)</div>';
  }
  
?>

</td>



<td class='data border_b'> 

<span class='app_sort_filter'>
 
    <select name='change_<?=strtolower($asset_symbol)?>_pairing' onchange='
    $("#<?=strtolower($asset_symbol)?>_pairing").val(this.value); 
    $("#<?=strtolower($asset_symbol)?>_market").val(1); // Just reset to first listed market for this pairing
    document.coin_amounts.submit();
    '>
    
    
        <?php
		  
        $loop = 0;

	        foreach ( $all_pairings as $pairing_key => $pairing_name ) {
	         $loop = $loop + 1;
	        ?>
	        <option value='<?=$pairing_key?>' <?=( strtolower($pairing_symbol) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper($pairing_key)?> </option>
	        <?php
	        }
        
        $loop = null;
        
        ?>
        
        
    </select>

</span>

</td>



<td class='data border_lb blue'>

<?php


	// UX on FIAT EQUIV number values
	if ( $fiat_eqiv == 1 ) {
	$coin_value_total_primary_currency_decimals = ( number_to_string($coin_value_total_raw) >= $app_config['primary_currency_decimals_max_threshold'] ? 2 : $app_config['primary_currency_decimals_max'] );
	}
  
$pretty_coin_value_total_raw = ( $fiat_eqiv == 1 ? pretty_numbers($coin_value_total_raw, $coin_value_total_primary_currency_decimals) : pretty_numbers($coin_value_total_raw, 8) ); 


echo ' <span class="blue"><span class="data app_sort_filter blue">' . $pretty_coin_value_total_raw . '</span> ' . $pairing_symbol . '</span>';

  if ( $selected_pairing != 'btc' && strtolower($asset_name) != 'bitcoin' ) {
  echo '<div class="btc_worth"><span>(' . pretty_numbers( $coin_value_total_raw * $pairing_btc_value, 8 ) . ' BTC)</span></div>';
  }

?>

</td>



<td class='data border_lrb blue' style='white-space: nowrap;'>



<?php


echo '<span class="' . ( $purchase_price >= 0.00000001 && $leverage_level >= 2 && $selected_margintype == 'short' ? 'short">★ ' : 'blue">' ) . '<span class="blue">' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] . '</span><span class="app_sort_filter blue">' . number_format($coin_primary_currency_worth_raw, 2, '.', ',') . '</span></span>';

  if ( $purchase_price >= 0.00000001 && $leverage_level >= 2 ) {

  $coin_worth_inc_leverage = $coin_primary_currency_worth_raw + $only_leverage_gain_loss;
  
  echo ' <span class="extra_data">(' . $leverage_level . 'x ' . $selected_margintype . ')</span>';

  // Here we parse out negative symbols
  $parsed_gain_loss = preg_replace("/-/", "-" . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']], number_format( $gain_loss, 2, '.', ',' ) );
  
  $parsed_inc_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']], number_format( $inc_leverage_gain_loss, 2, '.', ',' ) );
  
  $parsed_only_leverage_gain_loss = preg_replace("/-/", "-" . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']], number_format($only_leverage_gain_loss, 2, '.', ',' ) );
  
  // Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  // We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  // (plus sign would indicate a gain, NOT 'total worth')
  $parsed_coin_worth_inc_leverage = preg_replace("/-/", "", number_format($coin_worth_inc_leverage, 2, '.', ',' ) );
  
  
  // Pretty format, but no need to parse out anything here
  $pretty_coin_primary_currency_worth_raw = number_format( ($coin_primary_currency_worth_raw) , 2, '.', ',' );
  $pretty_leverage_gain_loss_percent = number_format( $inc_leverage_gain_loss_percent, 2, '.', ',' );
  
  
  		// Formatting
  		$gain_loss_span_color = ( $gain_loss >= 0 ? 'green_bright' : 'red_bright' );
  		$gain_loss_primary_currency = ( $gain_loss >= 0 ? '+' . $app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']] : '' );
  		
		?> 
		<img id='<?=$rand_id?>_leverage' src='templates/interface/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' />
	 <script>
	
			var leverage_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=$leverage_level?>x <?=ucfirst($selected_margintype)?> For <?=$asset_name?> (<?=$asset_symbol?>):</h5>'
			
			+'<p class="coin_info"><span class="yellow">Deposit (1x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_gain_loss?></span> (<?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=$pretty_coin_primary_currency_worth_raw?>)</p>'
			
			+'<p class="coin_info"><span class="yellow">Margin (<?=($leverage_level - 1)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_only_leverage_gain_loss?></span></p>'
			
			+'<p class="coin_info"><span class="yellow">Total (<?=($leverage_level)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_primary_currency?><?=$parsed_inc_leverage_gain_loss?> / <?=( $gain_loss >= 0 ? '+' : '' )?><?=$pretty_leverage_gain_loss_percent?>%</span> (<?=( $coin_worth_inc_leverage >= 0 ? '' : '-' )?><?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><?=$parsed_coin_worth_inc_leverage?>)</p>'
			
				
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#<?=$rand_id?>_leverage').balloon({
			html: true,
			position: "left",
			contents: leverage_content,
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

?>



</td>


  
</tr>
<!-- Coin data row END -->


<?php
  }
  
  // END of render webpage UI output
  // Stop rendering table row



}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>