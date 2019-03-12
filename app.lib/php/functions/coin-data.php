<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


//////////////////////////////////////////////////////////

function powerdown_usd($data) {

global $steem_market, $btc_exchange;

return ( $data * $steem_market * get_btc_usd($btc_exchange)['last_trade'] );

}

//////////////////////////////////////////////////////////

function monero_reward() {
	
global $runtime_mode;

	if ( $runtime_mode != 'ui' ) {
	return false;  // We only use the block reward config file call for UI data, can skip the API request if not running the UI.
	}
	else {
 	return monero_api('last_reward') / 1000000000000;
   }
   
}

//////////////////////////////////////////////////////////

function get_sub_token_price($chosen_market, $market_pairing) {

global $eth_subtokens_ico_values;

 if ( strtolower($chosen_market) == 'eth_subtokens_ico' ) {

  return $eth_subtokens_ico_values[$market_pairing];
  }
 

}

//////////////////////////////////////////////////////////

function bitcoin_total() {

    if (is_array($_SESSION['btc_worth_array']) || is_object($_SESSION['btc_worth_array'])) {
      
  foreach ( $_SESSION['btc_worth_array'] as $coin_value ) {
  
  $total_value = ($coin_value + $total_value);
  
  }
  
    }

return $total_value;
}

//////////////////////////////////////////////////////////

function detect_pairing($pair_name) {

$pair_name = preg_replace("/tusd/i", "0000", $pair_name); // SKIP TRUEUSD
$pair_name = preg_replace("/usdc/i", "0000", $pair_name); // SKIP USD Coin

	if ( !preg_match("/btc/i", $pair_name) && !preg_match("/xxbt/i", $pair_name) ) {
	
		// On rare occasion USDT is represented in pairing names as USD
		if ( preg_match("/usd/i", $pair_name) ) {
		return 'usdt'; // Tether
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

//////////////////////////////////////////////////////////

function volume_usd($pair_name, $volume, $last_trade, $vol_in_pairing=false) {

global $btc_exchange;


$pairing = detect_pairing($pair_name);


	// Get any necessary variables for calculating asset's USD value
	if ( $pair_name == 'bitcoin' && $last_trade != '' ) {
	$btc_usd = $last_trade;
	}
	else {
	$btc_usd = get_btc_usd($btc_exchange)['last_trade'];
	}


   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] || $vol_in_pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
   }
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] || $vol_in_pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
   }
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] || $vol_in_pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
   }
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] || $vol_in_pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
	
    
	// Get asset USD value
	if ( $vol_in_pairing == 'btc' || $pair_name == 'bitcoin' ) { // Volume calculated in Bitcoin
	$volume_usd = number_format( $btc_usd * $volume , 0, '.', ',');
	}
	elseif ( $vol_in_pairing != false ){ 
	$volume_usd = number_format( $btc_usd * ( $_SESSION[$pairing.'_btc'] * $volume ) , 0, '.', ',');
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$volume_usd = number_format( $btc_usd * ( $last_trade * $volume ) , 0, '.', ',');
		}
		else {
		$volume_usd = number_format( $btc_usd * ( ( $_SESSION[$pairing.'_btc'] * $last_trade ) * $volume ) , 0, '.', ',');
		}
	
	}


return $volume_usd;

}

//////////////////////////////////////////////////////////

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

//////////////////////////////////////////////////////////

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

//////////////////////////////////////////////////////////

function mining_calc_form($calculation_form_data, $network_measure) {

global $_POST, $mining_rewards;

?>

				<form name='<?=$calculation_form_data[1]?>' action='index.php#calculators' method='post'>
				
				<p><b><?=ucfirst($network_measure)?>:</b> <input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calculation_form_data[3]) )?>' name='network_measure' /> (uses <a href='<?=$calculation_form_data[4]?>' target='_blank'><?=$calculation_form_data[5]?></a>)</p>
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=( $_POST[$calculation_form_data[1].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' />
				
				<select name='hash_level'>
				<option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (one quintillion hashes per second) </option>
				<option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (one quadrillion hashes per second) </option>
				<option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (one trillion hashes per second) </option>
				<option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (one billion hashes per second) </option>
				<option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (one million hashes per second) </option>
				<option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (one thousand hashes per second) </option>
				<option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calculation_form_data[1].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (one hash per second) </option>
				</select>
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

function asset_alert_check($asset_data, $exchange, $pairing, $alert_mode) {

global $coins_array, $btc_exchange, $btc_usd, $to_email, $to_text, $notifyme_accesscode, $textbelt_apikey, $textlocal_account, $price_alerts_freq, $price_alerts_percent, $price_alerts_refresh;

// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, strpos($asset_data, "-") ) );

//echo $asset_data. ", ".$asset . " " .$alert_mode." check \n"; // DEBUGGING


	// Get any necessary variables for calculating asset's USD value
	if ( $asset == 'BTC' ) {
	$pairing = 'usd'; // Overwrite for Bitcoin only, so alerts properly describe the BTC fiat pairing in this app
	$btc_usd = get_btc_usd($exchange)['last_trade']; // Overwrite global var with selected exchange (rather then default), when asset is Bitcoin
	}

   if ( $pairing == 'xmr' && !$_SESSION['xmr_btc'] ) {
   $_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
   }
   elseif ( $pairing == 'ltc' && !$_SESSION['ltc_btc'] ) {
   $_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
   }
   elseif ( $pairing == 'eth' && !$_SESSION['eth_btc'] ) {
   $_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
   }
   elseif ( $pairing == 'usdt' && !$_SESSION['usdt_btc'] ) {
   $_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
   }
    
	// Get asset USD value
	if ( $asset == 'BTC' ){ 
	$asset_usd = $btc_usd;
	$volume_usd = get_btc_usd($exchange)['24hr_usd_volume'];
	}
	else {
		
		if ( $pairing == 'btc' ) {
		$asset_usd = number_format( $btc_usd * get_coin_value($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] , 8);
		}
		else {
		$pairing_btc_value = $_SESSION[$pairing.'_btc'];
		$asset_usd = number_format( $btc_usd * ( $pairing_btc_value * get_coin_value($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['last_trade'] ) , 8);
		}
		
		$volume_usd = get_coin_value($exchange, $coins_array[$asset]['market_pairing'][$pairing][$exchange])['24hr_usd_volume'];
	
	}

	
	
	// Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
	if ( file_exists('cache/alerts/'.$asset_data.'.dat') ) {
	
   $last_check_days = ( time() - filemtime('cache/alerts/'.$asset_data.'.dat') ) / 86400;
   
   	if ( floatval($last_check_days) >= 365 ) {
   	$last_check_time = number_format( ($last_check_days / 365) , 2, '.', ',') . ' years';
   	}
   	elseif ( floatval($last_check_days) >= 30 ) {
   	$last_check_time = number_format( ($last_check_days / 30) , 2, '.', ',') . ' months';
   	}
   	elseif ( floatval($last_check_days) >= 7 ) {
   	$last_check_time = number_format( ($last_check_days / 7) , 2, '.', ',') . ' weeks';
   	}
   	else {
   	$last_check_time = number_format($last_check_days, 2, '.', ',') . ' days';
   	}
   
	}
	
	
$cached_value = trim( file_get_contents('cache/alerts/'.$asset_data.'.dat') );



	////// If cached value, run alert checking ////////////
	if ( $cached_value != '' ) {
	
  
          if ( $alert_mode == 'decreased' ) {
          $price_alerts_value = $cached_value - ( $cached_value * ($price_alerts_percent / 100) );
          $percent_change = 100 - ( $asset_usd / ( $cached_value / 100 ) );
          $change_symbol = '-';
          
                  if ( floatval($asset_usd) >= 0.00000001 && floatval($asset_usd) <= floatval($price_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
          elseif ( $alert_mode == 'increased' ) {
          $price_alerts_value = $cached_value + ( $cached_value * ($price_alerts_percent / 100) );
          $percent_change = ( $asset_usd / ( $cached_value / 100 ) ) - 100;
          $change_symbol = '+';
          
                  if ( floatval($asset_usd) >= 0.00000001 && floatval($asset_usd) >= floatval($price_alerts_value) ) {
                  $send_alert = 1;
                  }
          
          }
  
  
  // Message formatting
  $cached_value_text = ( $asset == 'BTC' ? number_format($cached_value, 2, '.', ',') : $cached_value );
  $asset_usd_text = ( $asset == 'BTC' ? number_format($asset_usd, 2, '.', ',') : $asset_usd );
  
  $email_message = 'The ' . $asset . ' trade value in the '.strtoupper($pairing).' market at the ' . ucfirst($exchange) . ' exchange has '.$alert_mode.' '.$change_symbol.number_format($percent_change, 2, '.', ',').'% from it\'s previous value of $'.$cached_value_text.', to a current value of $' . $asset_usd_text . ' over the past '.$last_check_time.'. 24 hour trade volume is $' . $volume_usd . '.';
  
  $text_message = $asset . '/'.strtoupper($pairing).' @' . ucfirst($exchange) . ' '.$alert_mode.' '.$change_symbol.number_format($percent_change, 2, '.', ',').'% from $'.$cached_value_text.' to $' . $asset_usd_text . ' in '.$last_check_time.'. 24hr Vol: $' . $volume_usd;
  
  
  // Alert parameter configs for comm methods
  $notifyme_params = array(
                          'notification' => $email_message,
                          'accessCode' => $notifyme_accesscode
                          );
  
  $textbelt_params = array(
                          'phone' => text_number($to_text),
                          'message' => $text_message,
                          'key' => $textbelt_apikey
                          );
  
  $textlocal_params = array(
                           'username' => string_to_array($textlocal_account)[0],
                           'hash' => string_to_array($textlocal_account)[1],
                           'numbers' => text_number($to_text),
                           'message' => $text_message
                           );
  
          
          // Sending the alerts
          if ( update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $price_alerts_freq * 60 ) ) == true && $send_alert == 1 ) {
          
          file_put_contents('cache/alerts/'.$asset_data.'.dat', $asset_usd, LOCK_EX); // Cache the new lower / higher value
          
                  if ( trim($notifyme_accesscode) != '' ) {
                  @api_data('array', $notifyme_params, 0, 'https://api.notifymyecho.com/v1/NotifyMe');
                  }
  
                  if ( trim($textbelt_apikey) != '' && trim($textlocal_account) == '' ) { // Only run if textlocal API isn't being used to avoid double texts
                  @api_data('array', $textbelt_params, 0, 'https://textbelt.com/text', 2);
                  }
  
                  if ( trim($textlocal_account) != '' && trim($textbelt_apikey) == '' ) { // Only run if textbelt API isn't being used to avoid double texts
                  @api_data('array', $textlocal_params, 0, 'https://api.txtlocal.com/send/', 1);
                  }
           
           			// SEND EMAILS LAST, AS EMAIL FAILURE CAN BREAK PHP SCRIPTING AND CAUSE RUNTIME TO STOP (causing text / notifyme alerts to fail too)
          
                  if (  validate_email($to_email) == 'valid' ) {
                  @safe_mail($to_email, $asset . ' Asset Value '.ucfirst($alert_mode).' Alert', $email_message);
                  }
  
                  if ( validate_email( text_email($to_text) ) == 'valid' && trim($textbelt_apikey) != '' && trim($textlocal_account) != '' ) { 
                  // Only use text-to-email if other text services aren't configured
                  @safe_mail( text_email($to_text) , $asset . ' Value Alert', $text_message);
                  }
  
          
          }
  
  
	}
	////// END alert checking //////////////



	// Cache a price value if not already done, OR if config setting set to refresh every X days
	if ( floatval($asset_usd) >= 0.00000001 && update_cache_file('cache/alerts/'.$asset_data.'.dat', ( $price_alerts_refresh * 1440 ) ) == true ) {
	file_put_contents('cache/alerts/'.$asset_data.'.dat', $asset_usd, LOCK_EX); 
	}



}

//////////////////////////////////////////////////////////

function coin_data($coin_name, $trade_symbol, $coin_amount, $market_pairing_array, $selected_pairing, $selected_market, $sort_order) {

global $_POST, $coins_array, $btc_exchange, $marketcap_site, $marketcap_cache, $alert_percent, $marketcap_ranks_max, $api_timeout;


$original_market = $selected_market;

$all_markets = $market_pairing_array;  // All markets for this pairing

$all_pairings = $coins_array[$trade_symbol]['market_pairing'];

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
  
  
  if ( $coin_amount > 0.00000000 ) {
    
    if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
    $_SESSION['td_color'] = 'white';
    }
    else {
    $_SESSION['td_color'] = '#e8e8e8';
    }

    if ( $selected_pairing == 'btc' ) {
    $coin_trade_raw = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['last_trade'] : get_coin_value($selected_market, $market_pairing)['last_trade'] );
    $coin_trade = number_format( $coin_trade_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, ( $coin_name == 'Bitcoin' ? 2 : 8 ), '.', ',');
    $btc_worth = number_format( $coin_trade_total_raw, 8 );  
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', ( $coin_name == 'Bitcoin' ? $coin_amount : $btc_worth ) );
    $pairing_symbol = ( $coin_name == 'Bitcoin' ? 'USD' : 'BTC' );
    }
    else if ( $selected_pairing == 'xmr' ) {
    
    	if ( !$_SESSION['xmr_btc'] ) {
    	$_SESSION['xmr_btc'] = get_coin_value('binance', 'XMRBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['xmr_btc'];
    
    $coin_trade_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'XMR';
    
    }
    else if ( $selected_pairing == 'ltc' ) {
    
    	if ( !$_SESSION['ltc_btc'] ) {
    	$_SESSION['ltc_btc'] = get_coin_value('binance', 'LTCBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['ltc_btc'];
    
    $coin_trade_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'LTC';
    
    }
    else if ( $selected_pairing == 'eth' ) {
    
    	if ( !$_SESSION['eth_btc'] ) {
    	$_SESSION['eth_btc'] = get_coin_value('binance', 'ETHBTC')['last_trade'];
    	}
    
    $pairing_btc_value = $_SESSION['eth_btc'];
     
     if ( $selected_market == 'eth_subtokens_ico' ) {
     
     $coin_trade_raw = get_sub_token_price($selected_market, $market_pairing);
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }
     else {
      
     $coin_trade_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
     $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
     $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
     $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
     $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
     $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
     $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
     $pairing_symbol = 'ETH';
     
     }

    }
    else if ( $selected_pairing == 'usdt' ) {
    
    	if ( !$_SESSION['usdt_btc'] ) {
    	$_SESSION['usdt_btc'] = number_format( ( 1 / get_coin_value('binance', 'BTCUSDT')['last_trade'] ), 8, '.', '');
    	}
    
    $pairing_btc_value = $_SESSION['usdt_btc'];
    
    $coin_trade_raw = get_coin_value($selected_market, $market_pairing)['last_trade'];
    $coin_trade = number_format( $coin_trade_raw, 8, '.', ',');
    $coin_trade_total_raw = ($coin_amount * $coin_trade_raw);
    $coin_trade_total = number_format($coin_trade_total_raw, 8, '.', ',');
    $btc_worth = number_format( ($coin_trade_total_raw * $pairing_btc_value), 8 );  // Convert value to bitcoin
    $_SESSION['btc_worth_array'][] = (string)str_replace(',', '', $btc_worth);  
    $btc_trade_eq = number_format( ($coin_trade * $pairing_btc_value), 8);
    $pairing_symbol = 'USDT';
    
    }
  
  $trade_volume = ( $coin_name == 'Bitcoin' ? get_btc_usd($btc_exchange)['24hr_usd_volume'] : get_coin_value($selected_market, $market_pairing)['24hr_usd_volume'] );
  
  ?>
<tr id='<?=strtolower($trade_symbol)?>_row'>

<td class='data border_lb'><span><?php echo $sort_order; ?></span></td>

<td class='data border_lb' align='right' style='position: relative; padding-right: 32px; <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? 'padding-left: 32px;' : '' )?>'>
 
 <?php
 $mkcap_render_data = trim($coins_array[$trade_symbol]['marketcap-website-slug']);
 $info_icon = ( !marketcap_data($trade_symbol)['rank'] ? 'info-none.png' : 'info.png' );
 
	if ( $mkcap_render_data != '' ) {
 	
 
 		if ( $marketcap_site == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $marketcap_site == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 <?=( $coins_array[$trade_symbol]['ico'] == 'yes' ? "<a title='SEC Website On ICO Guidance And Safety' href='https://www.sec.gov/ICO' target='_blank'><img src='media/images/alert.png' border=0' style='position: absolute; top: 3px; left: 0px; margin: 0px; height: 30px; width: 30px;' /></a> " : "" )?><img id='<?=$mkcap_render_data?>' src='media/images/<?=$info_icon?>' border=0' style='position: absolute; top: 3px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <a title='' href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>/' target='_blank' style='color: blue;'><?php echo $coin_name; ?></a>
 <script>

		<?php
		if ( !marketcap_data($trade_symbol)['rank'] ) {
		?>

	var cmc_content = '<h3 style="color: #e5f1ff;"><?=ucfirst($marketcap_site)?> API may be offline / under heavy load, <br />marketcap range not set high enough (current range is top <?=$marketcap_ranks_max?> marketcaps), <br />or API timeout set too low (current timeout is <?=$api_timeout?> seconds). <br />Configuration adjustments can be made in config.php.</h3>';
	
			<?php
			if ( sizeof($alert_percent) > 1 ) {
			?>
			
			setTimeout(function() {
    		play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        ?> 
    
        var cmc_content = '<h3 class="orange" style="position: relative; top: -3px;"><?=ucfirst($marketcap_site)?>.com Summary For <?=$coin_name?> (<?=$trade_symbol?>):</h3>'
        +'<p><span class="orange">Average Global Market Price:</span> $<?=number_format(marketcap_data($trade_symbol)['price'],8,".",",")?></p>'
        +'<p><span class="orange">Marketcap Ranking:</span> #<?=marketcap_data($trade_symbol)['rank']?></p>'
        +'<p><span class="orange">Marketcap (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['market_cap'],0,".",",")?></p>'
        +'<p><span class="orange">24 Hour Global Volume (USD):</span> $<?=number_format(marketcap_data($trade_symbol)['volume_24h'],0,".",",")?></p>'
        +'<p><span class="orange">1 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_1h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_1h'].'%</span>' )?></p>'
        +'<p><span class="orange">24 Hour Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_24h'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_24h'].'%</span>' )?></p>'
        +'<p><span class="orange">7 Day Change:</span> <?=( stristr(marketcap_data($trade_symbol)['percent_change_7d'], '-') != false ? '<span class="red">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' : '<span class="green">'.marketcap_data($trade_symbol)['percent_change_7d'].'%</span>' )?></p>'
        +'<p><span class="orange">Available Supply:</span> <?=number_format(marketcap_data($trade_symbol)['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            if ( marketcap_data($trade_symbol)['total_supply'] > 0 ) {
            ?>
        +'<p><span class="orange">Total Supply:</span> <?=number_format(marketcap_data($trade_symbol)['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['max_supply'] > 0 ) {
            ?>
        +'<p><span class="orange">Maximum Supply:</span> <?=number_format(marketcap_data($trade_symbol)['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( marketcap_data($trade_symbol)['last_updated'] != '' ) {
            ?>
        +'<p><span class="orange">Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", marketcap_data($trade_symbol)['last_updated'])?></p>'
    
        <?php
            }
            ?>
        +'<p><span class="orange">Cache Time:</span> <?=$marketcap_cache?> minute(s)</p>'
    
        +'<p>*Current config setting only retrieves the top <?=$marketcap_ranks_max?> rankings.</p>';
    
        <?php
        
        }
        ?>
    
        $('#<?=$mkcap_render_data?>').balloon({
        html: true,
        position: "right",
        contents: cmc_content,
        css: {
                fontSize: ".9rem",
                minWidth: ".9rem",
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
  $rand_id = rand(10000000,100000000);
  ?>
  <img id='<?=$rand_id?>' src='media/images/<?=$info_icon?>' border=0' style='position: absolute; top: 4px; right: 0px; margin: 0px; height: 30px; width: 30px;' /> <?=$coin_name?>
 <script>
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  contents: '<h3 style="color: #e5f1ff;">No <?=ucfirst($marketcap_site)?>.com data for <?=$coin_name?> (<?=$trade_symbol?>) has been configured yet.</h3>'
});

		<?php
		if ( sizeof($alert_percent) > 1 ) {
		?>
		
		setTimeout(function() {
    	play_alert("<?=strtolower($trade_symbol)?>_row", "visual", "blue"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
	<?php
	}
 
 $mkcap_render_data = NULL;
 $rand_id = NULL;
 ?>
 
</td>

<td class='data border_b'><span><?php

  if ( $btc_trade_eq ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $btc_trade_eq ), 8, '.', ',');
  }
  elseif ( $coin_name != 'Bitcoin' ) {
  echo ' $'.number_format(( get_btc_usd($btc_exchange)['last_trade'] * $coin_trade ), 8, '.', ',');
  }
  else {
  echo ' $'.number_format(get_btc_usd($btc_exchange)['last_trade'], 2, '.', ',');
  }

?></span></td>

<td class='data border_lb' align='right'><?php echo number_format($coin_amount, 8, '.', ','); ?></td>

<td class='data border_b'><span><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb'>
 
    <select name='change_<?=strtolower($trade_symbol)?>_market' onchange='
    $("#<?=strtolower($trade_symbol)?>_market").val(this.value); document.coin_amounts.submit();
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

<td class='data border_b'><span>$<?php echo ( $trade_volume > 0 ? $trade_volume : 0 ); ?></span></td>

<td class='data border_b' align='right'><span><?php echo $coin_trade; ?></span>

<?php

  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eq > 0.00000000 ? $btc_trade_eq : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>
 
    <select name='change_<?=strtolower($trade_symbol)?>_pairing' onchange='
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
echo ' <span><span class="data">' . $coin_trade_total . '</span> ' . $pairing_symbol . '</span>';
  if ( $selected_pairing != 'btc' ) {
  echo '<div class="btc_worth"><span>(' . $btc_worth . ' BTC)</span></div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $selected_pairing == 'btc' ) {
  $coin_usd_worth = ( $coin_name == 'Bitcoin' ? $coin_trade_total_raw : ($coin_trade_total_raw * get_btc_usd($btc_exchange)['last_trade']) );
  }
  else {
  $coin_usd_worth = ( ($coin_trade_total_raw * $pairing_btc_value) * get_btc_usd($btc_exchange)['last_trade']);
  }
  

echo '$' . number_format($coin_usd_worth, 2, '.', ',');

?></td>

</tr>

<?php
  }

}

//////////////////////////////////////////////////////////


?>