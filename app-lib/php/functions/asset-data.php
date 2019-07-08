<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
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


function powerdown_usd($data) {

global $steem_market, $btc_exchange;

return ( $data * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );

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

global $eth_subtokens_ico_values;

 if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {

  return $eth_subtokens_ico_values[$market_pairing];
  }
 

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function bitcoin_total() {

    if (is_array($_SESSION['btc_worth_array']) || is_object($_SESSION['btc_worth_array'])) {
      
  foreach ( $_SESSION['btc_worth_array'] as $key => $value ) {
  
  $total_value = ($value + $total_value);
  
  }
  
    }

return $total_value;
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function coin_stats_data($request) {


	if (is_array($_SESSION['coin_stats_array']) || is_object($_SESSION['coin_stats_array'])) {
      
      
  		foreach ( $_SESSION['coin_stats_array'] as $key => $value ) {
  		
  		$results = ($results + $value[$request]);
  		
		}
  
  
	}


return $results;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function detect_pairing($pair_name) {

	if ( !preg_match("/btc/i", $pair_name) && !preg_match("/xbt/i", $pair_name) ) {
	
		// On rare occasion USDT is represented in pairing names as USD
		if ( preg_match("/usd/i", $pair_name) && !preg_match("/tusd/i", $pair_name) && !preg_match("/usdc/i", $pair_name)
		|| preg_match("/usdt/i", $pair_name) ) {
		return 'usdt'; // Tether
		}
		elseif ( preg_match("/tusd/i", $pair_name) && !preg_match("/usdt/i", $pair_name) ) {
		return 'tusd'; // TrueUSD
		}
		elseif ( preg_match("/usdc/i", $pair_name) && !preg_match("/usdt/i", $pair_name) ) {
		return 'usdc'; // USDC
		}
		elseif ( preg_match("/eth/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'eth';
		}
		elseif ( preg_match("/ltc/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'ltc';
		}
		elseif ( preg_match("/xmr/i", $pair_name) && !preg_match("/usd/i", $pair_name) ) {
		return 'xmr';
		}
		else {
		return false;
		}
	
	}
	else {
	return 'btc';
	}

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function marketcap_data($symbol) {
	
global $marketcap_site, $alert_percent;

$data = array();


	if ( $marketcap_site == 'coinmarketcap' ) { 
		
	$data['rank'] = coinmarketcap_api($symbol)['rank'];
	$data['price'] = coinmarketcap_api($symbol)['quotes']['USD']['price'];
	$data['market_cap'] = coinmarketcap_api($symbol)['quotes']['USD']['market_cap'];
	$data['volume_24h'] = coinmarketcap_api($symbol)['quotes']['USD']['volume_24h'];
	$data['percent_change_1h'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_1h'];
	$data['percent_change_24h'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_24h'];
	$data['percent_change_7d'] = coinmarketcap_api($symbol)['quotes']['USD']['percent_change_7d'];
	$data['circulating_supply'] = coinmarketcap_api($symbol)['circulating_supply'];
	$data['total_supply'] = coinmarketcap_api($symbol)['total_supply'];
	$data['max_supply'] = coinmarketcap_api($symbol)['max_supply'];
	$data['last_updated'] = coinmarketcap_api($symbol)['last_updated'];
	
	}
	elseif ( $marketcap_site == 'coingecko' ) {
		
	$data['rank'] = coingecko_api($symbol)['market_data']['market_cap_rank'];
	$data['price'] = coingecko_api($symbol)['market_data']['current_price']['usd'];
	$data['market_cap'] = coingecko_api($symbol)['market_data']['market_cap']['usd'];
	$data['volume_24h'] = coingecko_api($symbol)['market_data']['total_volume']['usd'];
	
	$data['percent_change_1h'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_1h_in_currency']['usd'] , 2, ".", ",");
	$data['percent_change_24h'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_24h'] , 2, ".", ",");
	$data['percent_change_7d'] = number_format( coingecko_api($symbol)['market_data']['price_change_percentage_7d'] , 2, ".", ",");
	
	$data['circulating_supply'] = coingecko_api($symbol)['market_data']['circulating_supply'];
	$data['total_supply'] = coingecko_api($symbol)['market_data']['total_supply'];
	$data['max_supply'] = NULL;
	
	$data['last_updated'] = strtotime( coingecko_api($symbol)['last_updated'] );
	
	}


return $data;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function trade_volume($pair_name, $volume, $last_trade, $vol_in_pairing=false, $volume_value='usd') {  // Default volume in USD

global $btc_exchange;


$pairing = detect_pairing($pair_name);


	// Get any necessary variables for calculating asset's USD value
	if ( $pair_name == 'bitcoin' && $last_trade != '' ) {
	$btc_usd = $last_trade;
	}
	else {
	$btc_usd = get_btc_usd($btc_exchange)['last_trade'];
	}

	// XMR
   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] || $vol_in_pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
   }
   // LTC
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] || $vol_in_pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
   }
   // ETH
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] || $vol_in_pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
   }
   // TETHER
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] || $vol_in_pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
   // TRUE USD
   elseif ( $pairing == 'tusd' && !$_SESSION['tusd_btc'] || $vol_in_pairing == 'tusd' && !$_SESSION['tusd_btc'] ) {
   $_SESSION['tusd_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCTUSD')['last_trade'] ), 8, '.', '');
   }
   // USDC
   elseif ( $pairing == 'usdc' && !$_SESSION['usdc_btc'] || $vol_in_pairing == 'usdc' && !$_SESSION['usdc_btc'] ) {
   $_SESSION['usdc_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDC')['last_trade'] ), 8, '.', '');
   }
	
    
	// Get asset USD value
	if ( $vol_in_pairing == 'btc' || $pair_name == 'bitcoin' ) { // Volume calculated in Bitcoin
	$volume_usd_raw = number_format( $btc_usd * $volume , 0, '.', '');
	}
	elseif ( $vol_in_pairing != false ){ 
	$volume_usd_raw = number_format( $btc_usd * ( $_SESSION[$pairing.'_btc'] * $volume ) , 0, '.', '');
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$volume_usd_raw = number_format( $btc_usd * ( $last_trade * $volume ) , 0, '.', '');
		}
		else {
		$volume_usd_raw = number_format( $btc_usd * ( ( $_SESSION[$pairing.'_btc'] * $last_trade ) * $volume ) , 0, '.', '');
		}
	
	}


	// Return negative number, if no data detected
	if ( $volume_value == 'usd' && is_numeric($volume) == true && $last_trade != '' || is_numeric($volume) == true && $vol_in_pairing != false ) {
	return $volume_usd_raw;
	}
	elseif ( $volume_value == 'pairing' && is_numeric($volume) == true && $last_trade != '' || is_numeric($volume) == true && $vol_in_pairing != false ) {
	return $volume;
	}
	else {
	return -1;
	}
	 

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function steempower_time($time) {
    
global $_POST, $steem_market, $btc_exchange, $steem_powerdown_time, $steempower_yearly_interest;

$powertime = NULL;
$powertime = NULL;
$steem_total = NULL;
$usd_total = NULL;

$decimal_yearly_interest = $steempower_yearly_interest / 100;  // Convert APR in config to decimal representation

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
    
    $powertime_usd = ( $powertime * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );
    
    $steem_total = ( $powertime + $_POST['sp_total'] );
    $usd_total = ( $steem_total * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );
    
    $power_purchased = ( $_POST['sp_purchased'] / $steem_total );
    $power_earned = ( $_POST['sp_earned'] / $steem_total );
    $power_interest = 1 - ( $power_purchased + $power_earned );
    
    $powerdown_total = ( $steem_total / $steem_powerdown_time );
    $powerdown_purchased = ( $powerdown_total * $power_purchased );
    $powerdown_earned = ( $powerdown_total * $power_earned );
    $powerdown_interest = ( $powerdown_total * $power_interest );
    
    ?>
    
<div class='result'>
    <h2> Interest Per <?=ucfirst($time)?> </h2>
    <ul>
        
        <li><b><?=number_format( $powertime, 3, '.', ',')?> STEEM</b> <i>in interest</i>, after a <?=$time?> time period = <b>$<?=number_format( $powertime_usd, 2, '.', ',')?></b></li>
        
        <li><b><?=number_format( $steem_total, 3, '.', ',')?> STEEM</b> <i>in total</i>, including original vested amount = <b>$<?=number_format( $usd_total, 2, '.', ',')?></b></li>
    
    </ul>

        <table border='1' cellpadding='10' cellspacing='0'>
  <caption><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></caption>
         <thead>
            <tr>
        <th class='normal'> Purchased </th>
        <th class='normal'> Earned </th>
        <th class='normal'> Interest </th>
        <th> Total </th>
            </tr>
          </thead>
         <tbody>
                <tr>

                <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_purchased), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_earned), 2, '.', ',')?> </td>
                <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> STEEM = $<?=number_format( powerdown_usd($powerdown_interest), 2, '.', ',')?> </td>
                <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> STEEM</b> = <b>$<?=number_format( powerdown_usd($powerdown_total), 2, '.', ',')?></b> </td>

                </tr>
           
        </tbody>
        </table>     
        
</div>

    <?php
    
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function mining_calc_form($calculation_form_data, $network_measure, $hash_unit='hash') {

global $_POST, $mining_rewards;

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
				
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['block_reward'] : $mining_rewards[$calculation_form_data[1]] )?>' name='block_reward' /> (may be static from config.php file, verify current block reward manually)</p>
				
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
				
				
				<p><b>kWh Rate ($/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
				
				
				<p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
				    
				    
			   <input type='hidden' value='1' name='<?=$calculation_form_data[1]?>_submitted' />
				
				<input type='submit' value='Calculate <?=strtoupper($calculation_form_data[1])?> Mining Profit' />
	
				</form>
				

<?php
  
}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function asset_charts_and_alerts($asset_data, $exchange, $pairing, $mode) {

global $runtime_mode, $base_dir, $local_time_offset, $block_volume_error, $coins_list, $btc_exchange, $charts_page, $asset_price_alerts_freq, $asset_price_alerts_percent, $asset_price_alerts_minvolume, $asset_price_alerts_refresh;

// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, strpos($asset_data, "-") ) );
$asset = strtoupper($asset);


	// Get any necessary variables for calculating asset's USD value
	if ( $asset == 'BTC' ) {
	$pairing = 'usd'; // Overwrite for Bitcoin only, so alerts properly describe the BTC fiat pairing in this app
	$btc_usd = get_btc_usd($exchange)['last_trade']; // Overwrite global var with selected exchange (rather then default), when asset is Bitcoin
	}
	else {
	$btc_usd = get_btc_usd($btc_exchange)['last_trade'];
	}
	
	

	// XMR
   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
   }
   // LTC
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
   }
   // ETH
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
   }
   // TETHER
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
   // TRUE USD
   elseif ( $pairing == 'tusd' && !$_SESSION['tusd_btc'] ) {
   $_SESSION['tusd_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCTUSD')['last_trade'] ), 8, '.', '');
   }
   // USDC
   elseif ( $pairing == 'usdc' && !$_SESSION['usdc_btc'] ) {
   $_SESSION['usdc_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDC')['last_trade'] ), 8, '.', '');
   }
    
	// Get asset USD value
	if ( $asset == 'BTC' ){ 
	$asset_usd_raw = $btc_usd;
	$volume_pairing_raw = get_btc_usd($exchange)['24hr_volume']; // For chart values based off pairing data (not USD equiv)
	$volume_usd_raw = get_btc_usd($exchange)['24hr_usd_volume'];
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$pairing_value_raw = number_format( get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] , 8, '.', '');
		$asset_usd_raw = number_format( $btc_usd * get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] , 8, '.', '');
		}
		else {
		$pairing_value_raw = number_format( get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] , 8, '.', '');
		
		$pairing_btc_value = $_SESSION[$pairing.'_btc'];
		$asset_usd_raw = number_format( $btc_usd * ( $pairing_btc_value * get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] ) , 8, '.', '');
		}
		
		$volume_pairing_raw = get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['24hr_volume']; // For chart values based off pairing data (not USD equiv)
		$volume_usd_raw = get_coin_value($exchange, $coins_list[$asset]['market_pairing'][$pairing][$exchange])['24hr_usd_volume'];
	
	}



	// Round for pretty numbers
	$volume_pairing_raw = round($volume_pairing_raw);
	$volume_usd_raw = round($volume_usd_raw);	
	
	$new_file_contents = $asset_usd_raw . '||' . $volume_usd_raw . '||' . $volume_pairing_raw;
	
	
	
	// Make sure we have basic values, otherwise return false
	if ( $btc_usd == NULL ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | error: No Bitcoin USD value set | charts_alerts_data: ' . $asset_data . ' (' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ") <br /> \n";
	$set_return = 1;
	}
	
	if ( floattostr($asset_usd_raw) == NULL ) {
	$_SESSION['other_error'] .= date('Y-m-d H:i:s') . ' UTC | runtime mode: ' . $runtime_mode . ' | error: No asset value set | charts_alerts_data: ' . $asset_data . ' (' . $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange . ") <br /> \n";
	$set_return = 1;
	}
	
	if ( $set_return == 1 ) {
	return FALSE;
	}
	
	
	
	// Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
	if ( file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	
   $last_check_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
   
   	if ( floattostr($last_check_days) >= 365 ) {
   	$last_check_time = number_format( ($last_check_days / 365) , 2, '.', ',') . ' years';
   	}
   	elseif ( floattostr($last_check_days) >= 30 ) {
   	$last_check_time = number_format( ($last_check_days / 30) , 2, '.', ',') . ' months';
   	}
   	elseif ( floattostr($last_check_days) >= 7 ) {
   	$last_check_time = number_format( ($last_check_days / 7) , 2, '.', ',') . ' weeks';
   	}
   	else {
   	$last_check_time = number_format($last_check_days, 2, '.', ',') . ' days';
   	}
   
	}
	

$data_file = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );

$cached_array = explode("||", $data_file);


	// Make sure numbers are cleanly pulled from cache file
	foreach ( $cached_array as $key => $value ) {
	$cached_array[$key] = remove_number_format($value);
	}


	// Backwards compatibility
	if ( $cached_array[0] == NULL ) {
	$cached_value = $data_file;
	$cached_volume_value = NULL;
	}
	else {
	$cached_value = $cached_array[0];  // USD value
	$cached_volume_value = $cached_array[2]; // Crypto volume value (more accurate percent increase / decrease stats than USD volume)
	}



	////// If cached value, run alert checking ////////////
	if ( $cached_value != '' ) {
	
	
	
  			 // Price checks
          if ( floattostr($cached_value) >= 0.00000001 && floattostr($asset_usd_raw) < floattostr($cached_value) ) {
          $asset_price_alerts_value = $cached_value - ( $cached_value * ($asset_price_alerts_percent / 100) );
          $percent_change = 100 - ( $asset_usd_raw / ( $cached_value / 100 ) );
          $change_symbol = '-';
          $increase_decrease = 'decreased';
          
                  if ( floattostr($asset_usd_raw) >= 0.00000001 && floattostr($asset_usd_raw) <= floattostr($asset_price_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
          elseif (  floattostr($cached_value) >= 0.00000001 && floattostr($asset_usd_raw) >= floattostr($cached_value) ) {
          $asset_price_alerts_value = $cached_value + ( $cached_value * ($asset_price_alerts_percent / 100) );
          $percent_change = ( $asset_usd_raw / ( $cached_value / 100 ) ) - 100;
          $change_symbol = '+';
          $increase_decrease = 'increased';
          
                  if ( floattostr($asset_usd_raw) >= 0.00000001 && floattostr($asset_usd_raw) >= floattostr($asset_price_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
          
          
          
          // Crypto volume checks
          if ( floattostr($cached_volume_value) > 0 && $volume_pairing_raw < $cached_volume_value ) {
          $volume_percent_change = 100 - ( $volume_pairing_raw / ( $cached_volume_value / 100 ) );
          $volume_change_symbol = '-';
          }
          elseif ( floattostr($cached_volume_value) > 0 && $volume_pairing_raw >= $cached_volume_value ) {
          $volume_percent_change = ( $volume_pairing_raw / ( $cached_volume_value / 100 ) ) - 100;
          $volume_change_symbol = '+';
          }
          elseif ( floattostr($cached_volume_value) == 0 && $volume_pairing_raw >= $cached_volume_value ) {
          $volume_percent_change = $asset_usd_raw; // Use USD value for percent up, for UX sake, if volume is up from zero
          $volume_change_symbol = '+';
          }
          
          
          
          // We disallow alerts where minimum 24 hour trade USD volume has NOT been met, ONLY if an API request doesn't fail to retrieve volume data
          if ( $volume_usd_raw > 0 && $volume_usd_raw < $asset_price_alerts_minvolume ) {
          $send_alert = NULL;
          }
  
  
          // We disallow alerts if they are not activated
          if ( $mode != 'both' && $mode != 'alert' ) {
          $send_alert = NULL;
          }
  
  
          // We disallow alerts if $block_volume_error is on, and there is a volume retrieval error
          if ( $volume_pairing_raw == -1 && $block_volume_error == 'on' ) {
          $send_alert = NULL;
          }
          
          
          
          // Sending the alerts
          if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', $asset_price_alerts_freq) == true && $send_alert == 1 ) {
          
          
  				// Message formatting for display to end user
          	
          	$desc_alert_type = ( $asset_price_alerts_refresh > 0 ? 'refresh' : 'alert' );
          	
          	// IF base volume was zero last alert / refresh, for UX sake we use USD volume instead of base volume (for percent up, so it's not up 70,000% for altcoins lol)
          	if ( floattostr($cached_volume_value) == 0 ) {
          	$volume_describe = 'USD volume was $0 last price ' . $desc_alert_type . ', and ';
          	$volume_describe_mobile = 'USD vol up from $0 last ' . $desc_alert_type;
          	}
          	else {
          	$volume_describe = 'base volume ';
          	$volume_describe_mobile = 'base vol';
          	}
          
          
          	// Pretty up textual output to end-user (convert raw numbers to have separators, remove underscores in names, etc)
  				$exchange_text = ucwords(preg_replace("/_/i", " ", $exchange));
  				
  				$asset_usd_text = ( $asset == 'BTC' ? number_format($asset_usd_raw, 2, '.', ',') : number_format($asset_usd_raw, 8, '.', ',') );
  				
  				$percent_change_text = number_format($percent_change, 2, '.', ',');
  				
  				$volume_usd_text = '$' . number_format($volume_usd_raw, 0, '.', ',');
  				
  				$volume_change_text = 'has ' . ( $volume_change_symbol == '+' ? 'increased ' : 'decreased ' ) . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% to a dollar value of';
  				
  				$volume_change_text_mobile = '(' . $volume_change_symbol . number_format($volume_percent_change, 2, '.', ',') . '% ' . $volume_describe_mobile . ')';
  				
  				
  				
  				// Backwards compatibility (from before volume was stored), and also if -1 from exchange API error not reporting any volume data (not even zero)
  				if ( $cached_volume_value == NULL || $cached_volume_value == -1 || $volume_pairing_raw == NULL || $volume_pairing_raw == -1 ) {
  				$volume_change_text = NULL;
  				$volume_change_text_mobile = NULL;
  				}
          	
          	
          	// Format trade volume data
          	
          	// Minimum volume filter skipped message, only if filter enabled and error getting trade volume data (otherwise is NULL)
          	if ( $volume_usd_raw == NULL && $asset_price_alerts_minvolume > 0 || $volume_usd_raw < 1 && $asset_price_alerts_minvolume > 0 ) {
          	$volume_filter_skipped_text = ', so enabled minimum volume filter was skipped';
          	}
          	else {
          	$volume_filter_skipped_text = NULL;
          	}
          	
          	
          	// Successfully received > 0 volume data, at or above an enabled minimum volume filter
  				if ( $volume_usd_raw > 0 && $asset_price_alerts_minvolume > 0 && $volume_usd_raw >= $asset_price_alerts_minvolume ) {
          	$email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_usd_text . ' (minimum volume filter set at $' . number_format($asset_price_alerts_minvolume, 0, '.', ',') . ').';
          	}
          	// NULL if not setup to get volume, negative number returned if no data received from API, therefore skipping any enabled volume filter
  				elseif ( $volume_pairing_raw == -1 ) { 
          	$email_volume_summary = 'No data received for 24 hour volume' . $volume_filter_skipped_text . '.';
          	$volume_usd_text = 'No data';
          	}
          	// If volume is zero or greater in successfully received volume data, without an enabled volume filter (or filter skipped)
          	// IF exchange dollar value price goes up/down and triggers alert, 
          	// BUT current reported volume is zero (temporary error on exchange side etc, NOT on our app's side),
          	// inform end-user of this probable volume discrepancy being detected.
          	elseif ( $volume_usd_raw >= 0 ) {
          	$email_volume_summary = '24 hour ' . $volume_describe . $volume_change_text . ' ' . $volume_usd_text . ( $volume_usd_raw == 0 ? ' (probable volume discrepancy detected' . $volume_filter_skipped_text . ')' : '' ) . '.'; 
          	}
  				
  				
  				
  				// Build the different messages, configure comm methods, and send messages
  				
  				$email_message = 'The ' . $asset . ' trade value in the ' . strtoupper($pairing) . ' market at the ' . $exchange_text . ' exchange has ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in dollar value to $' . $asset_usd_text . ' over the past ' . $last_check_time . ' since the last price ' . $desc_alert_type . '. ' . $email_volume_summary;
  				
  				$text_message = $asset . ' / ' . strtoupper($pairing) . ' @ ' . $exchange_text . ' ' . $increase_decrease . ' ' . $change_symbol . $percent_change_text . '% in dollar value to $' . $asset_usd_text . ' over ' . $last_check_time . '. 24hr USD Vol: ' . $volume_usd_text . ' ' . $volume_change_text_mobile;
  				
  				$notifyme_message = $email_message . ' Timestamp is ' . time_date_format($local_time_offset, 'pretty_time') . '.';
  				
  				// Cache the new lower / higher value + volume data
          	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $new_file_contents); 
          	
  				// Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
          	$send_params = array(
          								'text' => $text_message,
          								'notifyme' => $notifyme_message,
          								'email' => array(
          														'subject' => $asset . ' Asset Value '.ucfirst($increase_decrease).' Alert',
          														'message' => $email_message
          														)
          								);
          	
          	// Send notifications
          	@send_notifications($send_params);
  
          
          }
          
  
  
	}
	////// END alert checking //////////////

 

	// Cache a price value / volumes if not already done, OR if config setting set to refresh every X days
	if ( $mode == 'both' && floattostr($asset_usd_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat')
	|| $mode == 'alert' && floattostr($asset_usd_raw) >= 0.00000001 && !file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $new_file_contents); 
	}
	elseif ( $mode == 'both' && $send_alert != 1 && $asset_price_alerts_refresh >= 1 && floattostr($asset_usd_raw) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $asset_price_alerts_refresh * 1440 ) ) == true
	|| $mode == 'alert' && $send_alert != 1 && $asset_price_alerts_refresh >= 1 && floattostr($asset_usd_raw) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $asset_price_alerts_refresh * 1440 ) ) == true ) {
	store_file_contents($base_dir . '/cache/alerts/'.$asset_data.'.dat', $new_file_contents); 
	}



	// If the charts page is enabled in config.php, save latest chart data for assets with price alerts configured on them
	if ( $mode == 'both' && floattostr($asset_usd_raw) >= 0.00000001 && $charts_page == 'on'
	|| $mode == 'chart' && floattostr($asset_usd_raw) >= 0.00000001 && $charts_page == 'on' ) { 
	
		// Only store chart data if the exchange reported volume data too, even if zero (we don't want to store anything if data errors happened from questionable exchanges)
		if ( floattostr($pairing_value_raw) >= 0.0000000 ) {
		store_file_contents($base_dir . '/cache/charts/'.$asset.'/'.$asset_data.'_chart_usd.dat', time() . '||' . $asset_usd_raw . '||' . $volume_usd_raw . "\n", "append"); 
		store_file_contents($base_dir . '/cache/charts/'.$asset.'/'.$asset_data.'_chart_'.$pairing.'.dat', time() . '||' . $pairing_value_raw . '||' . $volume_pairing_raw . "\n", "append"); 
		}
	
	}


// If we haven't returned FALSE yet because of any issues being detected, return TRUE to indicate all seems ok
return TRUE;

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


function ui_coin_data($coin_name, $trade_symbol, $coin_amount, $market_pairing_array, $selected_pairing, $selected_market, $purchase_price=NULL, $leverage_level, $selected_margintype) {

global $_POST, $coins_list, $btc_exchange, $marketcap_site, $marketcap_cache, $alert_percent, $marketcap_ranks_max, $api_timeout;

$rand_id = rand(10000000,100000000);
  
$sort_order = ( array_search($trade_symbol, array_keys($coins_list)) + 1);

$original_market = $selected_market;

$all_markets = $market_pairing_array;  // All markets for this pairing

$all_pairings = $coins_list[$trade_symbol]['market_pairing'];

  // Update, get the selected market name
  
  $loop = 0;
   foreach ( $all_markets as $key => $value ) {
   
    if ( $loop == $selected_market || $key == "eth_subtokens_ico" ) {
    $selected_market = $key;
     
     if ( $coin_name == 'Bitcoin' ) {
     $_SESSION['btc_in_usd'] = $key;
     }
     
    }
   
   $loop = $loop + 1;
   }
  $loop = NULL; 


if ( $_SESSION['btc_in_usd'] ) {
$btc_exchange = $_SESSION['btc_in_usd'];
}


$market_pairing = $all_markets[$selected_market];
  
  
  if ( $coin_amount > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
    
    
    // UI table coloring
    if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
    $_SESSION['td_color'] = 'white';
    }
    else {
    $_SESSION['td_color'] = '#e8e8e8';
    }

	
	 // Get coin values, including non-BTC pairings
    if ( $selected_pairing == 'btc' ) {
    $coin_value_raw = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['last_trade'] : get_coin_value($selected_market, $market_pairing)['last_trade'] );
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = ( $coin_name == 'Bitcoin' ? $coin_amount : $coin_value_total_raw );
    $pairing_symbol = ( $coin_name == 'Bitcoin' ? 'USD' : 'BTC' );
    }
    // XMR
    else if ( $selected_pairing == 'xmr' ) {
    
    	if ( !$_SESSION['xmr_btc'] ) {
    	$_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['xmr_btc'];
    
    $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $pairing_symbol = 'XMR';
    
    }
    // LTC
    else if ( $selected_pairing == 'ltc' ) {
    
    	if ( !$_SESSION['ltc_btc'] ) {
    	$_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['ltc_btc'];
    
    $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $pairing_symbol = 'LTC';
    
    }
    // ETH
    else if ( $selected_pairing == 'eth' ) {
    
    	if ( !$_SESSION['eth_btc'] ) {
    	$_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['eth_btc'];
     
     if ( $selected_market == 'eth_subtokens_ico' ) {
     
     $coin_value_raw = get_sub_token_price($selected_market, $market_pairing);
     $coin_value_total_raw = ($coin_amount * $coin_value_raw);
     $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
     $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
     $coin_value_total_raw = ($coin_amount * $coin_value_raw);
     $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
     $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }

    }
    // TETHER
    else if ( $selected_pairing == 'usdt' ) {
    
    	if ( !$_SESSION['usdt_btc'] ) {
    	$_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
    	}
    
    $pairing_btc_value = $_SESSION['usdt_btc'];
    
    $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $pairing_symbol = 'USDT';
    
    }
    // TRUE USD
    else if ( $selected_pairing == 'tusd' ) {
    
    	if ( !$_SESSION['tusd_btc'] ) {
    	$_SESSION['tusd_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCTUSD')['last_trade'] ), 8, '.', '');
    	}
    
    $pairing_btc_value = $_SESSION['tusd_btc'];
    
    $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $pairing_symbol = 'TUSD';
    
    }
    // USDC
    else if ( $selected_pairing == 'usdc' ) {
    
    	if ( !$_SESSION['usdc_btc'] ) {
    	$_SESSION['usdc_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDC')['last_trade'] ), 8, '.', '');
    	}
    
    $pairing_btc_value = $_SESSION['usdc_btc'];
    
    $coin_value_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_value_total_raw = ($coin_amount * $coin_value_raw);
    $_SESSION['btc_worth_array'][$trade_symbol] = floattostr($coin_value_total_raw * $pairing_btc_value);  
    $btc_trade_eqiv = number_format( ($coin_value_raw * $pairing_btc_value), 8);
    $pairing_symbol = 'USDC';
    
    }
  
  
  	 if ( $selected_pairing == 'btc' ) {
  	 $coin_usd_worth_raw = ( $coin_name == 'Bitcoin' ? $coin_value_total_raw : ($coin_value_total_raw * get_btc_usd($btc_exchange)['last_trade']) );
  	 }
  	 else {
  	 $coin_usd_worth_raw = ( ($coin_value_total_raw * $pairing_btc_value) * get_btc_usd($btc_exchange)['last_trade']);
  	 }
  
  
	 
  	 // Calculate gain / loss if purchase price was populated
	 if ( $purchase_price >= 0.00000001 ) {
	 	
	 $coin_paid_total_raw = ($coin_amount * $purchase_price);
	 
	 $gain_loss = $coin_usd_worth_raw - $coin_paid_total_raw;
	 	 
	 	 
	 	// Convert $gain_loss for shorts
		if ( $selected_margintype == 'short' ) {
  		
 		$prev_gain_loss_val = $gain_loss;
 			
 			if ( $prev_gain_loss_val >= 0 ) {
 	 		$gain_loss = $prev_gain_loss_val - ( $prev_gain_loss_val * 2 );
 	 		$coin_usd_worth_raw = $coin_usd_worth_raw - ( $prev_gain_loss_val * 2 );
 		 	}
 	 		else {
 		 	$gain_loss = $prev_gain_loss_val + ( abs($prev_gain_loss_val) * 2 );
 			$coin_usd_worth_raw = $coin_usd_worth_raw + ( abs($prev_gain_loss_val) * 2 );
 	 		}

 	 	}
	 
	 
	 $gain_loss_percent = ( ($coin_usd_worth_raw / $coin_paid_total_raw) - 1 ) * 100;
	 
	 // Check for leverage
	 $only_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * ($leverage_level - 1) ) : 0 );
	 
	 $inc_leverage_gain_loss = ( $leverage_level >= 2 ? ($gain_loss * $leverage_level) : $gain_loss );
	 
	 $inc_leverage_gain_loss_percent =  ( $leverage_level >= 2 ? ($gain_loss_percent * $leverage_level) : $gain_loss_percent );
	 
    
	 }
	 else {
	 $no_purchase_price = 1;
	 }
	  
	 
	 
    $_SESSION['coin_stats_array'][] = array(
    													'coin_symbol' => $trade_symbol, 
    													'coin_leverage' => $leverage_level,
    													'selected_margintype' => $selected_margintype,
    													'coin_worth_total' => $coin_usd_worth_raw,
    													'coin_total_worth_if_purchase_price' => ($no_purchase_price == 1 ? NULL : $coin_usd_worth_raw),
    													'coin_paid' => $purchase_price,
    													'coin_paid_total' => $coin_paid_total_raw,
    													'gain_loss_only_leverage' => $only_leverage_gain_loss,
    													'gain_loss_total' => $inc_leverage_gain_loss,
    													'gain_loss_percent_total' => $inc_leverage_gain_loss_percent,
    													);
    										


  // Get trade volume
  $trade_volume = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['24hr_usd_volume'] : get_coin_value($selected_market, $market_pairing)['24hr_usd_volume'] );
  
  
  ?>
<tr id='<?=strtolower($trade_symbol)?>_row'>

<td class='data border_lb'><span class='app_sort_filter'><?php echo $sort_order; ?></span></td>

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; white-space: nowrap;'>
 
 <?php
 $mkcap_render_data = trim($coins_list[$trade_symbol]['marketcap_website_slug']);
 $info_icon = ( !marketcap_data($trade_symbol)['rank'] ? 'info-none.png' : 'info.png' );
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $marketcap_site == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $marketcap_site == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 <img id='<?=$mkcap_render_data?>' src='ui-templates/media/images/<?=$info_icon?>' alt='' border='0' style='position: absolute; top: 2px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' class='blue app_sort_filter'><?php echo $coin_name; ?></a>
 <script>

		<?php
		if ( !marketcap_data($trade_symbol)['rank'] ) {
		?>

	var cmc_content = '<h5 style="color: #e5f1ff;"><?=ucfirst($marketcap_site)?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$marketcap_ranks_max?> marketcaps), <br />or API timeout set too low (current timeout is <?=$api_timeout?> seconds). <br />Configuration adjustments can be made in config.php.</h5>';
	
			<?php
			if ( sizeof($alert_percent) > 1 ) {
			?>
			
			setTimeout(function() {
    		play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "no_cmc"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        ?> 
    
        var cmc_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=ucfirst($marketcap_site)?>.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h5>'
        +'<p class="coin_info"><span class="yellow">Average Global Market Price:</span> $<?=number_format(marketcap_data($trade_symbol)['price'],8,".",",")?></p>'
        +'<p class="coin_info"><span class="yellow">Marketcap Ranking:</span> #<?=marketcap_data($trade_symbol)['rank']?></p>'
        +'<p class="coin_info"><span class="yellow">Marketcap (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['market_cap'],0,".",",")?></p>'
        +'<p class="coin_info"><span class="yellow">24 Hour Global Volume (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['volume_24h'],0,".",",")?></p>'
        +'<p class="coin_info"><span class="yellow">1 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_1h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' : '<span class="green_bright">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' )?></p>'
        +'<p class="coin_info"><span class="yellow">24 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_24h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' : '<span class="green_bright">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' )?></p>'
        +'<p class="coin_info"><span class="yellow">7 Day Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_7d'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' : '<span class="green_bright">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' )?></p>'
        +'<p class="coin_info"><span class="yellow">Available Supply:</span> <?=number_format(marketcap_data($trade_symbol)['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            if ( marketcap_data($trade_symbol)['total_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Total Supply:</span> <?=number_format(marketcap_data($trade_symbol)['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['max_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Maximum Supply:</span> <?=number_format(marketcap_data($trade_symbol)['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['last_updated'] != '' ) {
            ?>
        +'<p class="coin_info"><span class="yellow">Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", marketcap_data($trade_symbol)['last_updated'])?></p>'
    
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="yellow">Cache Time:</span> <?=$marketcap_cache?> minute(s)</p>'
    
        +'<p class="coin_info">*Current config setting only retrieves the top <?=$marketcap_ranks_max?> rankings.</p>';
    
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
            $percent_change = marketcap_data($trade_symbol)['percent_change_1h'];
            }
            elseif ( $alert_percent[2] == '24hour' ) {
            $percent_change = marketcap_data($trade_symbol)['percent_change_24h'];
            }
            elseif ( $alert_percent[2] == '7day' ) {
            $percent_change = marketcap_data($trade_symbol)['percent_change_7d'];
            }
         
          //echo 'console.log("' . $percent_change_alert . '|' . $percent_change . '");';
          
         
            if ( stristr($percent_change_alert, '-') != false && $percent_change_alert >= $percent_change && is_numeric($percent_change) ) {
            ?>
         
            setTimeout(function() {
               play_alert("<?=strtolower($trade_symbol)?>_row", "<?=$percent_alert_type?>", "yellow");
            }, 1000);
            
            <?php
            }
            elseif ( stristr($percent_change_alert, '-') == false && $percent_change_alert <= $percent_change && is_numeric($percent_change) ) {
            ?>
            
            setTimeout(function() {
               play_alert("<?=strtolower($trade_symbol)?>_row", "<?=$percent_alert_type?>", "green");
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
  <img id='<?=$rand_id?>' src='ui-templates/media/images/<?=$info_icon?>' alt='' border='0' style='position: absolute; top: 2px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <?=$coin_name?>
 <script>
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: '<h5 style="color: #e5f1ff;">No <?=ucfirst($marketcap_site)?>.com data for <?=$coin_name?> (<?=$trade_symbol?>) has been configured yet.</h5>'
});

		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "no_cmc"); // Assets with marketcap data not set or functioning properly
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

<span class='app_sort_filter'>

<?php

  if ( $btc_trade_eqiv ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $btc_trade_eqiv ), 8, '.', ',');
  }
  elseif ( $coin_name != 'Bitcoin' ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $coin_value_raw ), 8, '.', ',');
  }
  else {
  echo ' $'.number_format(get_btc_usd($btc_exchange)['last_trade'], 2, '.', ',');
  }

?>

</span>

</td>

<td class='data border_lb' align='right'>


<?php

	if ( strtoupper($trade_symbol) == 'USD' ) {
	$coin_amount_decimals = 2;
	}
	else {
	$coin_amount_decimals = 8;
	}
	
$pretty_coin_amount = pretty_numbers($coin_amount, $coin_amount_decimals);

echo "<span class='app_sort_filter blue'>" . ( $pretty_coin_amount != NULL ? $pretty_coin_amount : 0 ) . "</span>";

?>


</td>

<td class='data border_b'><span class='app_sort_filter'><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb'>
 
    <select class='app_sort_filter' name='change_<?=strtolower($trade_symbol)?>_market' onchange='
    $("#<?=strtolower($trade_symbol)?>_market").val(this.value);
    document.coin_amounts.submit();
    '>
        <?php
        foreach ( $all_markets as $market_key => $market_name ) {
         $loop = $loop + 1;
        ?>
        <option value='<?=($loop)?>' <?=( $original_market == ($loop -1) ? ' selected ' : '' )?>> <?=ucwords(preg_replace("/_/i", " ", $market_key))?> </option>
        <?php
        }
        $loop = NULL;
        ?>
    </select>

</td>

<td class='data border_b'><span class='app_sort_filter'>

<?php 

  // NULL if not setup to get volume, negative number returned if no data received from API
  if ( $trade_volume == NULL || $trade_volume == -1 ) {
  echo '$0';
  }
  elseif ( $trade_volume >= 0 ) {
  echo '$' . number_format($trade_volume, 0, '.', ',');
  }

?>

</span></td>

<td class='data border_b' align='right'><span class='app_sort_filter'><?php echo number_format($coin_value_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ','); ?></span>

<?php

  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eqiv > 0.00000000 ? $btc_trade_eqiv : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>
 
    <select class='app_sort_filter' name='change_<?=strtolower($trade_symbol)?>_pairing' onchange='
    $("#<?=strtolower($trade_symbol)?>_pairing").val(this.value); 
    $("#<?=strtolower($trade_symbol)?>_market").val(1); // Just reset to first listed market for this pairing
    document.coin_amounts.submit();
    '>
        <?php
        
		  if ( $coin_name == 'Bitcoin' ) {
		  ?>
		  <option value='btc'> USD </option>
		  <?php
		  }
        
        else {
        	
        $loop = 0;

	        foreach ( $all_pairings as $pairing_key => $pairing_name ) {
	         $loop = $loop + 1;
	        ?>
	        <option value='<?=$pairing_key?>' <?=( strtolower($pairing_symbol) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper($pairing_key)?> </option>
	        <?php
	        }
        
        $loop = NULL;
        
        }
        ?>
    </select>

</span></td>

<td class='data border_lb'><?php

echo ' <span><span class="data app_sort_filter">' . number_format($coin_value_total_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',') . '</span> ' . $pairing_symbol . '</span>';

  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . number_format( $coin_value_total_raw * $pairing_btc_value , 8 ) . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb' style='white-space: nowrap;'><?php


echo '<span class="' . ( $selected_margintype == 'short' ? 'short">★ $' : 'blue">$' ) . '<span class="app_sort_filter" style="color: inherit;">' . number_format($coin_usd_worth_raw, 2, '.', ',') . '</span></span>';

  if ( $purchase_price >= 0.00000001 && $leverage_level >= 2 ) {
  
  echo ' <span class="extra_data blue">@ ' . $leverage_level . 'x ' . $selected_margintype . '</span>';

  $parsed_gain_loss = preg_replace("/-/", "-$", number_format( $gain_loss, 2, '.', ',' ) );
  
  $parsed_inc_leverage_gain_loss = preg_replace("/-/", "-$", number_format( $inc_leverage_gain_loss, 2, '.', ',' ) );
  
  $parsed_only_leverage_gain_loss = preg_replace("/-/", "-$", number_format($only_leverage_gain_loss, 2, '.', ',' ) );
  
  
  // Pretty format, but no need to parse out anything here
  $pretty_leverage_gain_loss_percent = number_format( $inc_leverage_gain_loss_percent, 2, '.', ',' );
  $pretty_coin_worth_inc_leverage = number_format( ($coin_usd_worth_raw + $only_leverage_gain_loss) , 2, '.', ',' );
  
  
  		// Formatting
  		$gain_loss_span_color = ( $gain_loss >= 0 ? 'green_bright' : 'red' );
  		$gain_loss_usd = ( $gain_loss >= 0 ? '+$' : '' );
  		
		?> 
		<img id='<?=$rand_id?>_leverage' src='ui-templates/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' />
	 <script>
	
			var leverage_content = '<h5 class="yellow" style="position: relative; white-space: nowrap;"><?=$leverage_level?>x Leverage <?=ucfirst($selected_margintype)?> For <?=$coin_name?> (<?=$trade_symbol?>):</h5>'
			
			+'<p class="coin_info"><span class="yellow">Deposit (1x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_usd?><?=$parsed_gain_loss?></span> (USD Subtotal)</p>'
			
			+'<p class="coin_info"><span class="yellow">Loan (<?=($leverage_level - 1)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_usd?><?=$parsed_only_leverage_gain_loss?></span></p>'
			
			+'<p class="coin_info"><span class="yellow">Total (<?=($leverage_level)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_usd?><?=$parsed_inc_leverage_gain_loss?> / <?=( $gain_loss >= 0 ? '+' : '' )?><?=$pretty_leverage_gain_loss_percent?>%</span> ($<?=$pretty_coin_worth_inc_leverage?> @ <?=$leverage_level?>x <?=$selected_margintype?>)</p>'
			
				
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

?></td>

</tr>

<?php
  }

}


////////////////////////////////////////////////////////
////////////////////////////////////////////////////////


?>