<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



class ct_asset {
	
// Class variables / arrays
var $ct_var1;
var $ct_var2;
var $ct_var3;

var $ct_array = array();

    
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function powerdown_prim_curr($data) {
   
   global $ct, $hive_mrkt;
   
   return ( $data * $hive_mrkt * $ct['sel_opt']['sel_btc_prim_currency_val'] );
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function static_usd_price($chosen_mrkt, $mrkt_pair) {
   
   global $ct;
   
     if ( strtolower($chosen_mrkt) == 'presale_usd_value' ) {
     return $ct['opt_conf']['token_presales_usd'][$mrkt_pair];
     }
    
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function bitcoin_total() {
     
   global $ct;
   
     foreach ( $ct['btc_worth_array'] as $key => $val ) {
     $result = ($result + $val);
     }
     
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function stocks_bitcoin_total() {
     
   global $ct;
   
     foreach ( $ct['stocks_btc_worth_array'] as $key => $val ) {
     $result = ($result + $val);
     }
     
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function coin_stats_data($request) {
   
   global $ct;
   
     foreach ( $ct['asset_stats_array'] as $key => $val ) {
     $result = ($result + $val[$request]);
     }
       
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function asset_list_int_api() {
   
   global $ct;
   
   $result = array();
   
     foreach ( $ct['conf']['assets'] as $key => $unused ) {
       
         if ( strtolower($key) != 'miscassets' && strtolower($key) != 'btcnfts' && strtolower($key) != 'ethnfts' && strtolower($key) != 'solnfts' && strtolower($key) != 'altnfts' ) {
         $result[] = strtolower($key);
         }
       
     }
     
   sort($result);
   
   return array('asset_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function conversion_list_int_api() {
   
   global $ct;
   
   $result = array();
   
     foreach ( $ct['conf']['assets']['BTC']['pair'] as $key => $unused ) {
     $result[] = $key;
     }
     
   sort($result);
   
   return array('conversion_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function btc_mrkt($data) {
   
   global $ct;
   
     $pair_loop = 0;
     
     foreach ( $ct['conf']['assets']['BTC']['pair'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] as $mrkt_key => $mrkt_id ) {
       
        // If a numeric id, return the exchange name
        if ( is_int($data) && $pair_loop == $data ) {
        return $mrkt_key;
        }
        // If an exchange name (alphnumeric with possible underscores), return the numeric id (used in UI html forms)
        elseif ( preg_match("/^[A-Za-z0-9_]+$/", $data) && $mrkt_key == $data ) {
        return $pair_loop + 1;
        }
       
     $pair_loop = $pair_loop + 1;
     
     }
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function exchange_list_int_api() {
   
   global $ct;
   
   $result = array();
   
     foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
   
       foreach ( $ct['conf']['assets'][$asset_key]['pair'] as $pair_key => $unused ) {
             
         foreach ( $ct['conf']['assets'][$asset_key]['pair'][$pair_key] as $exchange_key => $unused ) {
             
           if( !in_array(strtolower($exchange_key), $result) && !preg_match("/misc_assets/i", $exchange_key) && !preg_match("/btc_nfts/i", $exchange_key) && !preg_match("/eth_nfts/i", $exchange_key) && !preg_match("/sol_nfts/i", $exchange_key) && !preg_match("/alt_nfts/i", $exchange_key) ) {
           $result[] = strtolower($exchange_key);
           }
         
         }
           
       }
     
     }
   
   sort($result);
   
   return array('exchange_list' => $result);
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function market_list_int_api($exchange) {
   
   global $ct;
   
   $exchange = strtolower($exchange);
   
   $result = array();
   
   
     foreach( $ct['conf']['assets'] as $asset_key => $asset_val ) {
     
       foreach( $asset_val['pair'] as $mrkt_pair_key => $mrkt_pair_val ) {
         
         foreach( $mrkt_pair_val as $exchange_key => $unused ) {
           
           if ( $exchange_key == $exchange ) {
           $result[] = $exchange_key . '-' . strtolower($asset_key) . '-' . $mrkt_pair_key;
           }
           
         }
         
       }
     
     }
     
     
     sort($result);
     
     
     if ( !$exchange ) {
     	
     $ct['gen']->log(
     			'int_api_error',
     			'From ' . $ct['remote_ip'] . ' (Missing parameter: exchange)',
     			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
     			);
     
     return array('error' => 'Missing parameter: [exchange]; ');
     
     }
     
     
     if ( is_array($result) && sizeof($result) < 1 ) {
     	
     $ct['gen']->log(
     			'int_api_error',
     			'From ' . $ct['remote_ip'] . ' (No markets found for exchange: ' . $exchange . ')',
     			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
     			);
     
     return array('error' => 'No markets found for exchange: ' . $exchange);
     
     }
     else {
     
     return array(
             		'market_list' => array($exchange => $result)
             		);
     
     }
     
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function prim_currency_trade_vol($asset_symb, $pair, $last_trade, $vol_in_pair) {
   
   global $ct;
     
     
     // Return negative number, if no volume data detected (so we know when data errors happen)
     if ( is_numeric($vol_in_pair) != true ) {
     return -1;
     }
     // If no pair data, skip calculating trade volume to save on uneeded overhead
     elseif ( !$asset_symb || !$pair || !isset($last_trade) || $last_trade == 0 ) {
     return false;
     }
   
   
     // WE NEED TO SET THIS (ONLY IF NOT SET ALREADY) for $ct['api']->market() calls, 
     // because it is not set as a global THE FIRST RUNTIME CALL TO $ct['api']->market()
     if ( strtoupper($asset_symb) == 'BTC' && !$ct['sel_opt']['sel_btc_prim_currency_val'] ) {
     $temp_btc_prim_currency_val = $last_trade; // Don't overwrite global
     }
     else {
     $temp_btc_prim_currency_val = $ct['sel_opt']['sel_btc_prim_currency_val']; // Don't overwrite global
     }
   
   
     // Get primary currency volume value	
     // Currency volume from Bitcoin's DEFAULT PAIR volume
     if ( $pair == $ct['conf']['currency']['bitcoin_primary_currency_pair'] ) {
     $vol_prim_currency_raw = number_format( $vol_in_pair , 0, '.', '');
     }
     // Currency volume from btc PAIR volume
     elseif ( $pair == 'btc' ) {
     $vol_prim_currency_raw = number_format( $temp_btc_prim_currency_val * $vol_in_pair , 0, '.', '');
     }
     // Currency volume from other PAIR volume
     else { 
     
     $pair_btc_val = $this->pair_btc_val($pair);
   
       if ( $pair_btc_val == null ) {
       	
       $ct['gen']->log(
       			'market_error',
       			'this->pair_btc_val() returned null in ct_asset->prim_currency_trade_vol()',
       			'pair: ' . $pair
       			);
       
       }
     
     $vol_prim_currency_raw = number_format( $temp_btc_prim_currency_val * ( $vol_in_pair * $pair_btc_val ) , 0, '.', '');
     
     }
     
     
   return $vol_prim_currency_raw;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function hivepower_time($time) {
       
   global $ct, $hive_mrkt;
   
   $powertime = null;
   $powertime = null;
   $hive_total = null;
   $prim_currency_total = null;
   
   $decimal_yearly_interest = $ct['conf']['currency']['hivepower_yearly_interest'] / 100;  // Convert APR in config to decimal representation
   
   $speed = ($_POST['hp_total'] * $decimal_yearly_interest) / 525600;  // Interest per minute
   
   
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
       
       
   $powertime_prim_currency = ( $powertime * $hive_mrkt * $ct['sel_opt']['sel_btc_prim_currency_val'] );
       
   $hive_total = ( $powertime + $_POST['hp_total'] );
   $prim_currency_total = ( $hive_total * $hive_mrkt * $ct['sel_opt']['sel_btc_prim_currency_val'] );
       
   $power_purchased = ( $_POST['hp_purchased'] / $hive_total );
   $power_earned = ( $_POST['hp_earned'] / $hive_total );
   $power_interest = 1 - ( $power_purchased + $power_earned );
       
   $powerdown_total = ( $hive_total / $ct['conf']['currency']['hive_powerdown_time'] );
   $powerdown_purchased = ( $powerdown_total * $power_purchased );
   $powerdown_earned = ( $powerdown_total * $power_earned );
   $powerdown_interest = ( $powerdown_total * $power_interest );
       
   ?>
       
   <div class='result'>
       <h2> Interest Per <?=ucfirst($time)?> </h2>
       <ul>
           
           <li><b><?=number_format( $powertime, 3, '.', ',')?> HIVE</b> <i>in interest</i> (after a <?=$time?> time period) = <b><?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=number_format( $powertime_prim_currency, 2, '.', ',')?></b></li>
       
       </ul>
   
     <p><b>A Power Down Weekly Payout <i>Started At This Time</i> Would Be (rounded to nearest cent):</b></p>
           <table border='5' cellpadding='20' cellspacing='20'>
               <tr>
           <th class='normal'> Purchased </th>
           <th class='normal'> Earned </th>
           <th class='normal'> Interest </th>
           <th> Total </th>
               </tr>
                   <tr>
   
                   <td> <?=number_format( $powerdown_purchased, 3, '.', ',')?> HIVE = <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=number_format( $this->powerdown_prim_curr($powerdown_purchased), 2, '.', ',')?> </td>
                   <td> <?=number_format( $powerdown_earned, 3, '.', ',')?> HIVE = <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=number_format( $this->powerdown_prim_curr($powerdown_earned), 2, '.', ',')?> </td>
                   <td> <?=number_format( $powerdown_interest, 3, '.', ',')?> HIVE = <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=number_format( $this->powerdown_prim_curr($powerdown_interest), 2, '.', ',')?> </td>
                   <td> <b><?=number_format( $powerdown_total, 3, '.', ',')?> HIVE</b> = <b><?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=number_format( $this->powerdown_prim_curr($powerdown_total), 2, '.', ',')?></b> </td>
   
                   </tr>
              
           </table>     
           
   </div>
   
   <?php
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mining_calc_form($calc_form_data, $network_measure, $hash_unit='hash') {
   
   global $ct;
   
   ?>
   
           <form name='<?=$calc_form_data['symbol']?>' action='<?=$ct['gen']->start_page('mining')?>' method='post'>
           
           
           <p><b><?=ucfirst($network_measure)?>:</b> 
           <?php
           if ( $hash_unit == 'hash' ) {
           ?>
           
           <input type='text' value='<?=( $_POST['network_measure'] && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? number_format($_POST['network_measure']) : number_format($calc_form_data['difficulty']) )?>' name='network_measure' /> 
           
           <?php
           }
           ?>
           </p>
           
           
           <p><b>Your Hashrate:</b>  
           <input type='text' value='<?=( $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? $_POST['your_hashrate'] : '' )?>' name='your_hashrate' /> 
           
           
           
           <?php
           if ( $hash_unit == 'hash' ) {
           ?>
           <select class='browser-default custom-select' name='hash_level'>
           <option value='1' <?=( $_POST['hash_level'] == '1' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Hs (hashes per second) </option>
           <option value='1000' <?=( $_POST['hash_level'] == '1000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Khs (thousand hashes per second) </option>
           <option value='1000000' <?=( $_POST['hash_level'] == '1000000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Mhs (million hashes per second) </option>
           <option value='1000000000' <?=( $_POST['hash_level'] == '1000000000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ghs (billion hashes per second) </option>
           <option value='1000000000000' <?=( $_POST['hash_level'] == '1000000000000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ths (trillion hashes per second) </option>
           <option value='1000000000000000' <?=( $_POST['hash_level'] == '1000000000000000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Phs (quadrillion hashes per second) </option>
           <option value='1000000000000000000' <?=( $_POST['hash_level'] == '1000000000000000000' && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? 'selected' : '' )?>> Ehs (quintillion hashes per second) </option>
           </select>
           
           <?php
           }
           ?>
           
           
           </p>
           
           
           <p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['block_reward'] && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? $_POST['block_reward'] : $calc_form_data['block_reward'] )?>' name='block_reward' /> (MAY be static from Power User Config, verify manually)</p>
           
           
           <p><b>Watts Used:</b> <input type='text' value='<?=( isset($_POST['watts_used']) && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_used'] : '300' )?>' name='watts_used' /></p>
           
           
           <p><b>kWh Rate (<?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?>/kWh):</b> <input type='text' value='<?=( isset($_POST['watts_rate']) && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? $_POST['watts_rate'] : '0.1000' )?>' name='watts_rate' /></p>
           
           
           <p><b>Pool Fee:</b> <input type='text' value='<?=( isset($_POST['pool_fee']) && $_POST[$calc_form_data['symbol'].'_submitted'] == 1 ? $_POST['pool_fee'] : '1' )?>' size='4' name='pool_fee' />%</p>
               
               
            <input type='hidden' value='1' name='<?=$calc_form_data['symbol']?>_submitted' />
               
            <input type='hidden' value='<?=$calc_form_data['symbol']?>' name='pow_calc' />
           
           <input type='submit' value='Calculate <?=strtoupper($calc_form_data['symbol'])?> Mining Profit' />
     
           </form>
           
   
   <?php
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function mcap_data($symbol, $force_currency=null) {
     
   global $ct;
   
   $symbol = strtolower($symbol);
   
   $data = array();
   
   
     if ( preg_match("/stock/i", $symbol) ) {
     // Do nothing for stocks, as we currently don't support stock stats beyond spot price / volume
     }
     elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coingecko' ) {
     
       
         // Check for currency support, fallback to USD if needed
         if ( $force_currency != null ) {
           
         $app_notice = 'Forcing '.strtoupper($force_currency).' stats.';
         
         $coingecko_api_no_overwrite = $ct['api']->mcap_data_coingecko($force_currency);
           
               // Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
               if ( !isset($coingecko_api_no_overwrite['btc']['market_cap_rank']) ) {
           	$app_notice = 'Coingecko.com API data error, check the app logs for more information.';
           	}
         
         }
         elseif ( !isset($ct['coingecko_api']['btc']['market_cap_rank']) && strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) != 'USD' ) {
           
         $app_notice = 'Coingecko.com does not seem to support '.strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']).' stats,<br />showing USD stats instead.';
         
         $ct['mcap_data_force_usd'] = 1;
         
         $ct['coingecko_api'] = $ct['api']->mcap_data_coingecko('usd');
           
           	// Overwrite previous app notice and unset force usd flag, if this appears to be a data error rather than an unsupported language
           	if ( !isset($ct['coingecko_api']['btc']['market_cap_rank']) ) {
           	$ct['mcap_data_force_usd'] = null;
           	$app_notice = 'Coingecko.com API data error, check the app logs for more information.';
           	}
         
         }
         elseif ( $ct['mcap_data_force_usd'] == 1 ) {
         $app_notice = 'Coingecko.com does not seem to support '.strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']).' stats,<br />showing USD stats instead.';
         }
     
     
     // Marketcap data
     $mcap_data = ( $coingecko_api_no_overwrite ? $coingecko_api_no_overwrite : $ct['coingecko_api'] );
     
       
     $data['rank'] = $mcap_data[$symbol]['market_cap_rank'];
     $data['price'] = $ct['var']->num_to_str($mcap_data[$symbol]['current_price']);
     $data['market_cap'] = round( $ct['var']->rem_num_format($mcap_data[$symbol]['market_cap']) );
     
       	if ( $ct['var']->rem_num_format($mcap_data[$symbol]['total_supply']) > $ct['var']->rem_num_format($mcap_data[$symbol]['circulating_supply']) ) {
       	$data['market_cap_total'] = round( $ct['var']->rem_num_format($mcap_data[$symbol]['current_price']) * $ct['var']->rem_num_format($mcap_data[$symbol]['total_supply']) );
       	}
       
     $data['vol_24h'] = $mcap_data[$symbol]['total_volume'];
     
     $data['percent_change_1h'] = number_format( $mcap_data[$symbol]['price_change_percentage_1h_in_currency'] , 2, ".", ",");
     $data['percent_change_24h'] = number_format( $mcap_data[$symbol]['price_change_percentage_24h_in_currency'] , 2, ".", ",");
     $data['percent_change_7d'] = number_format( $mcap_data[$symbol]['price_change_percentage_7d_in_currency'] , 2, ".", ",");
     
     $data['circulating_supply'] = $mcap_data[$symbol]['circulating_supply'];
     $data['total_supply'] = $mcap_data[$symbol]['total_supply'];
     $data['max_supply'] = null;
     
     $data['last_updated'] = strtotime( $mcap_data[$symbol]['last_updated'] );
     
     $data['app_notice'] = $app_notice;
     
     // Coingecko-only
     $data['percent_change_14d'] = number_format( $mcap_data[$symbol]['price_change_percentage_14d_in_currency'] , 2, ".", ",");
     $data['percent_change_30d'] = number_format( $mcap_data[$symbol]['price_change_percentage_30d_in_currency'] , 2, ".", ",");
     $data['percent_change_60d'] = number_format( $mcap_data[$symbol]['price_change_percentage_60d_in_currency'] , 2, ".", ",");
     $data['percent_change_200d'] = number_format( $mcap_data[$symbol]['price_change_percentage_200d_in_currency'] , 2, ".", ",");
     $data['percent_change_1y'] = number_format( $mcap_data[$symbol]['price_change_percentage_1y_in_currency'] , 2, ".", ",");
     
     }
     elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' ) {
   
     // Don't overwrite global
     $coinmarketcap_prim_currency = strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']);
      
      
         // Covert NATIVE tickers to INTERNATIONAL for coinmarketcap
         if ( $coinmarketcap_prim_currency == 'NIS' ) {
         $coinmarketcap_prim_currency = 'ILS';
         }
         elseif ( $coinmarketcap_prim_currency == 'RMB' ) {
         $coinmarketcap_prim_currency = 'CNY';
         }
     
     
         // Default to USD, if selected primary currency is not supported
         if ( $force_currency != null ) {
         $app_notice .= ' Forcing '.strtoupper($force_currency).' stats. ';
         $coinmarketcap_api_no_overwrite = $ct['api']->mcap_data_coinmarketcap($force_currency);
         }
         elseif ( isset($ct['mcap_data_force_usd']) ) {
         $coinmarketcap_prim_currency = 'USD';
         }
         
         
         if ( isset($ct['cmc_notes']) ) {
         $app_notice .= $ct['cmc_notes'];
         }
       
     
     // Marketcap data
     $mcap_data = ( $coinmarketcap_api_no_overwrite ? $coinmarketcap_api_no_overwrite : $ct['coinmarketcap_api'] );
       
       
     $data['rank'] = $mcap_data[$symbol]['cmc_rank'];
     $data['price'] = $ct['var']->num_to_str($mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['price']);
     $data['market_cap'] = round( $ct['var']->rem_num_format($mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['market_cap']) );
     
         if ( $ct['var']->rem_num_format($mcap_data[$symbol]['total_supply']) > $ct['var']->rem_num_format($mcap_data[$symbol]['circulating_supply']) ) {
         $data['market_cap_total'] = round( $ct['var']->rem_num_format($mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['price']) * $ct['var']->rem_num_format($mcap_data[$symbol]['total_supply']) );
         }
       
     $data['vol_24h'] = $mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['volume_24h'];
     
     $data['percent_change_1h'] = number_format( $mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['percent_change_1h'] , 2, ".", ",");
     $data['percent_change_24h'] = number_format( $mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['percent_change_24h'] , 2, ".", ",");
     $data['percent_change_7d'] = number_format( $mcap_data[$symbol]['quote'][$coinmarketcap_prim_currency]['percent_change_7d'] , 2, ".", ",");
     
     $data['circulating_supply'] = $mcap_data[$symbol]['circulating_supply'];
     $data['total_supply'] = $mcap_data[$symbol]['total_supply'];
     $data['max_supply'] = $mcap_data[$symbol]['max_supply'];
     
     $data['last_updated'] = strtotime( $mcap_data[$symbol]['last_updated'] );
     
     $data['app_notice'] = $app_notice;
     
     }
     
     
   // UX on number values
   $thres_dec = $ct['gen']->thres_dec($data['price'], 'u', 'fiat'); // Units mode
   $data['price'] = $ct['var']->num_pretty($data['price'], $thres_dec['max_dec'], false, $thres_dec['min_dec']);
   
   // Return null if we don't even detect a rank
   return ( $data['rank'] != NULL ? $data : NULL );
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function market_conv_int_api($mrkt_conversion, $all_mrkts_data_array) {
   
   global $ct, $min_crypto_val_test;
   
   $result = array();
   
   // Cleanup
   $mrkt_conversion = strtolower($mrkt_conversion);
   $all_mrkts_data_array = array_map('trim', $all_mrkts_data_array);
   $all_mrkts_data_array = array_map('strtolower', $all_mrkts_data_array);
       
   $possible_dos_attack = 0;
   
   
      // Return error message if there are missing parameters
      if ( $mrkt_conversion != 'market_only' && !$ct['conf']['assets']['BTC']['pair'][$mrkt_conversion] || $all_mrkts_data_array[0] == '' ) {
         
            if ( $mrkt_conversion == '' ) {
            	
            $result['error'] .= 'Missing parameter: [currency_symb|market_only]; ';
            
            $ct['gen']->log(
            			'int_api_error',
            			'From ' . $ct['remote_ip'] . ' (Missing parameter: currency_symb|market_only)',
            			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
            			);
            
            }
            elseif ( $mrkt_conversion != 'market_only' && !$ct['conf']['assets']['BTC']['pair'][$mrkt_conversion] ) {
            	
            $result['error'] .= 'Conversion market does not exist: '.$mrkt_conversion.'; ';
            
            $ct['gen']->log(
            			'int_api_error',
            			'From ' . $ct['remote_ip'] . ' (Conversion market does not exist: '.$mrkt_conversion.')',
            			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
            			);
            
            }
            
            if ( $all_mrkts_data_array[0] == '' ) {
            	
            $result['error'] .= 'Missing parameter: [exchange-asset-pair]; ';
            
            $ct['gen']->log(
            			'int_api_error',
            			'From ' . $ct['remote_ip'] . ' (Missing parameter: exchange-asset-pair)',
            			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
            			);
            
            }
       
      return $result;
      
      }
      
      
      // Return error message if the markets lists is more markets than allowed by $ct['conf']['int_api']['int_api_markets_limit']
      if ( is_array($all_mrkts_data_array) && sizeof($all_mrkts_data_array) > $ct['conf']['int_api']['int_api_markets_limit'] ) {
      	
      $result['error'] = 'Exceeded maximum of ' . $ct['conf']['int_api']['int_api_markets_limit'] . ' markets allowed per request (' . sizeof($all_mrkts_data_array) . ').';
      
      $ct['gen']->log(
      			'int_api_error',
      			'From ' . $ct['remote_ip'] . ' (Exceeded maximum markets allowed per request)',
      			'markets_requested: ' . sizeof($all_mrkts_data_array) . '; uri: ' . $_SERVER['REQUEST_URI'] . ';'
      			);
      
      return $result;
      
      }
   
   
      // Loop through each set of market data
      foreach( $all_mrkts_data_array as $mrkt_data ) {
   
         
           // Stop processing output and return an error message, if this is a possible dos attack
           if ( $possible_dos_attack > 5 ) {
           	
           $result = array(); // reset for no output other than error notice
           
           $result['error'] = 'Too many non-existent markets requested.';
           
           $ct['gen']->log(
           			'int_api_error',
           			'From ' . $ct['remote_ip'] . ' (Too many non-existent markets requested)',
           			'uri: ' . $_SERVER['REQUEST_URI'] . ';'
           			);
           
           return $result;
           
           }
           
       
       
       $mrkt_data_array = explode("-", $mrkt_data); // Market data array
                   
       $exchange = $mrkt_data_array[0];
           
       $asset = $mrkt_data_array[1];
           
       $mrkt_pair = $mrkt_data_array[2];
           
       $pair_id = $ct['conf']['assets'][strtoupper($asset)]['pair'][$mrkt_pair][$exchange];
           
       
       
           // If market exists, get latest data
           if ( isset($pair_id) && $pair_id != '' ) {
           
                 
                 
                 // GET BTC MARKET CONVERSION VALUE #BEFORE ANYTHING ELSE#, OR WE WON'T GET PROPER VOLUME IN CURRENCY ETC
                 // IF NOT SET YET, get bitcoin market data (if we are getting converted fiat currency values)
                 if ( $mrkt_conversion != 'market_only' && !isset($btc_exchange) && !isset($mrkt_conv_btc_val) ) {
                 
                   
                     // If a preferred bitcoin market is set in app config, use it...otherwise use first array key
                     if ( isset($ct['opt_conf']['bitcoin_preferred_currency_markets'][$mrkt_conversion]) ) {
                     $btc_exchange = $ct['opt_conf']['bitcoin_preferred_currency_markets'][$mrkt_conversion];
                 	 }
                 	 else {
                 	 $btc_exchange = key($ct['conf']['assets']['BTC']['pair'][$mrkt_conversion]);
                 	 }
                   
                   
                 $btc_pair_id = $ct['conf']['assets']['BTC']['pair'][$mrkt_conversion][$btc_exchange];
                 
                 $mrkt_conv_btc_val = $ct['api']->market('BTC', $btc_exchange, $btc_pair_id)['last_trade'];
                 
                       
                       // FAILSAFE: If the exchange market is DOES NOT RETURN a value, 
                       // move the internal array pointer one forward, until we've tried all exchanges for this btc pair
                       $switch_exchange = true;
                       while ( !isset($mrkt_conv_btc_val) && $switch_exchange != false || $ct['var']->num_to_str($mrkt_conv_btc_val) < $min_crypto_val_test && $switch_exchange != false ) {
                         
                       $switch_exchange = next($ct['conf']['assets']['BTC']['pair'][$mrkt_conversion]);
                       
                           if ( $switch_exchange != false ) {
                             
                           $btc_exchange = key($ct['conf']['assets']['BTC']['pair'][$mrkt_conversion]);
                           
                           $btc_pair_id = $ct['conf']['assets']['BTC']['pair'][$mrkt_conversion][$btc_exchange];
                 
                           $mrkt_conv_btc_val = $ct['api']->market('BTC', $btc_exchange, $btc_pair_id)['last_trade'];
                       
                           }
                 
                       }
           
                 
                 // OVERWRITE SELECTED BITCOIN CURRENCY MARKET GLOBALS
                 $ct['conf']['currency']['bitcoin_primary_currency_pair'] = $mrkt_conversion;
                 $ct['conf']['currency']['bitcoin_primary_currency_exchange'] = $btc_exchange;
                 
                 // OVERWRITE #GLOBAL# BTC PRIMARY CURRENCY VALUE (so we get correct values for volume in currency etc)
                 $ct['sel_opt']['sel_btc_prim_currency_val'] = $mrkt_conv_btc_val;
                 
                 }
                 
                   
                   
           $asset_mrkt_data = $ct['api']->market(strtoupper($asset), $exchange, $pair_id, $mrkt_pair);
           
           $asset_val_raw = $asset_mrkt_data['last_trade'];
           
           // Cleaned up numbers
           $asset_val_raw = $ct['var']->num_to_str($asset_val_raw);
           
           $pair_vol_raw = $ct['var']->num_to_str($asset_mrkt_data['24hr_pair_vol']);
           
           
           
                 // Rounding numbers formatting
                 if ( array_key_exists($mrkt_pair, $ct['conf']['assets']['BTC']['pair']) ) {
                 $thres_dec = $ct['gen']->thres_dec($asset_val_raw, 'u', 'fiat'); // Units mode
                 $asset_val_raw = round($asset_val_raw, $thres_dec['max_dec']);
                 $vol_pair_rounded = round($pair_vol_raw);
                 }
                 else {
                 $vol_pair_rounded = round($pair_vol_raw, 8);
                 }
                 
           
           // Remove any trailing zeros / scientific formatting from round()
           $asset_val_raw = $ct['var']->num_to_str($asset_val_raw);
           $vol_pair_rounded = $ct['var']->num_to_str($vol_pair_rounded);
           
                 
                 // Get converted fiat currency values if requested
                 if ( $mrkt_conversion != 'market_only' ) {
                 
                     // Value in fiat currency
                       if ( $mrkt_pair == 'btc' ) {
                       $asset_prim_mrkt_worth_raw = $asset_val_raw * $mrkt_conv_btc_val;
                       }
                       else {
                       	
                       $pair_btc_val = $this->pair_btc_val($mrkt_pair);
                       
                           if ( $pair_btc_val == null ) {
                           	
                           $ct['gen']->log(
                           			'market_error',
                           			'this->pair_btc_val() returned null in ct_asset->market_conv_int_api()',
                           			'pair: ' . $mrkt_pair
                           			);
                           
                           }
                           
                       $asset_prim_mrkt_worth_raw = ($asset_val_raw * $pair_btc_val) * $mrkt_conv_btc_val;
                       
                       }
                 
                 // Auto-rounded numbers for fiat currency
                 $thres_dec = $ct['gen']->thres_dec($asset_prim_mrkt_worth_raw, 'u', 'fiat'); // Units mode
                 $asset_prim_mrkt_worth_raw = round($asset_prim_mrkt_worth_raw, $thres_dec['max_dec']);
                 
                 // Remove any trailing zeros / scientific formatting from round()
                 $asset_prim_mrkt_worth_raw = $ct['var']->num_to_str($asset_prim_mrkt_worth_raw);
                 
                 }
           
           
           
                 // Results
                 if ( $mrkt_conversion != $mrkt_pair && $mrkt_conversion != 'market_only' ) {
                 
                 // Flag we are doing a price conversion
                 $price_conversion = 1;
                   
                 $result['market_conversion'][$mrkt_data] = array(
                 
                                                                  'market' => array( 
                                                                    			    $mrkt_pair => array('spot_price' => $asset_val_raw, '24hr_vol' => $vol_pair_rounded)
                                                                    			   ),
                                                                    							
                                                                  'conversion' => array(
                                                                    				   $mrkt_conversion => array('spot_price' => $asset_prim_mrkt_worth_raw, '24hr_vol' => $ct['var']->num_to_str( round($asset_mrkt_data['24hr_prim_currency_vol']) ) )
                                                                    				  )
                                                                    								
                                                                  );
                                                                               
                 }
                 else {
                   
                 $result['market_conversion'][$mrkt_data] = array(
                                                                  'market' => array( 
                                                                    			    $mrkt_pair => array('spot_price' => $asset_val_raw, '24hr_vol' => $vol_pair_rounded) 
                                                                    			   )
                                                                  );
                                                       
                 }
           
           
           
           }
           elseif ( !is_array($mrkt_data_array) || is_array($mrkt_data_array) && sizeof($mrkt_data_array) < 3 ) {
           	
           $result['market_conversion'][$mrkt_data] = array('error' => "Missing all 3 REQUIRED sub-parameters: [exchange-asset-pair]");
           
           $ct['gen']->log(
           		    'int_api_error',
           		    'From ' . $ct['remote_ip'] . ' (Missing all 3 REQUIRED sub-parameters: exchange-asset-pair)',
           		    'uri: ' . $_SERVER['REQUEST_URI'] . ';'
           		    );
           
           $possible_dos_attack = $possible_dos_attack + 1;
           
           }
           elseif ( $pair_id == '' ) {
           	
           $result['market_conversion'][$mrkt_data] = array('error' => "Market does not exist: [" . $exchange . "-" . $asset . "-" . $mrkt_pair . "]");
           
           $ct['gen']->log(
           		    'int_api_error',
           		    'From ' . $ct['remote_ip'] . ' (Market does not exist: ' . $exchange . "-" . $asset . "-" . $mrkt_pair . ')',
           		    'uri: ' . $_SERVER['REQUEST_URI'] . ';'
           		   );
           								
           $possible_dos_attack = $possible_dos_attack + 1;
           
           }
       
       
      }
   
   
      // If we did a price conversion, show market used
      if ( $mrkt_conversion != 'market_only' && $price_conversion == 1 ) {
      
      // Reset internal array pointer
      reset($ct['conf']['assets']['BTC']['pair'][$mrkt_conversion]);
       
      $result['market_conversion_source'] = $btc_exchange . '-btc-' . $mrkt_conversion;
      
      }
   
   
   return $result;
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function pair_btc_val($pair) {
   
   global $ct, $min_crypto_val_test;
   
   $pair = strtolower($pair);
   
   
      // Safeguard / cut down on runtime
      if ( !$pair || $pair == null || trim($pair) == '' ) {
      return null;
      }
      // If BTC
      elseif ( $pair == 'btc' ) {
      return 1;
      }
      // If session value exists
      elseif ( isset($ct['btc_pair_mrkts'][$pair.'_btc']) ) {
      return $ct['btc_pair_mrkts'][$pair.'_btc'];
      }
      // If we need an ALTCOIN/BTC market value
      elseif (
      is_array($ct['conf']['assets'][strtoupper($pair)]['pair']['btc'])
      && sizeof($ct['conf']['assets'][strtoupper($pair)]['pair']['btc']) > 0 
      ) {
        
        
	        // Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
	        if (
	        sizeof($ct['conf']['assets'][strtoupper($pair)]['pair']['btc']) > 1
	        && array_key_exists($pair, $ct['opt_conf']['crypto_pair_preferred_markets'])
	        ) {
	        $mrkt_override = $ct['opt_conf']['crypto_pair_preferred_markets'][$pair];
	        }
	      
	      
	        // Loop until we find a market override / non-excluded pair market
	        foreach ( $ct['conf']['assets'][strtoupper($pair)]['pair']['btc'] as $mrkt_key => $mrkt_val ) {
	            
	            
	              if ( is_array($ct['btc_pair_mrkts_excluded'][$pair]) && in_array($mrkt_key, $ct['btc_pair_mrkts_excluded'][$pair]) ) {
	              $mrkt_blacklisted = true;
	              }
	              else {
	              $mrkt_blacklisted = false;
	              }
	              
	              
	              if ( is_array($ct['btc_pair_mrkts_excluded'][$pair]) && in_array($mrkt_override, $ct['btc_pair_mrkts_excluded'][$pair]) ) {
	              $mrkt_override_blacklisted = true;
	              }
	              else {
	              $mrkt_override_blacklisted = false;
	              }
	              
	              
		         if ( 
		         isset($mrkt_override) && $mrkt_override == $mrkt_key && !$mrkt_blacklisted
		         || isset($mrkt_override) && $mrkt_override != $mrkt_key && $mrkt_override_blacklisted && !$mrkt_blacklisted
		         || !isset($mrkt_override) && !$mrkt_blacklisted
		         ) {
		            
		          $ct['btc_pair_mrkts'][$pair.'_btc'] = $ct['var']->num_to_str( $ct['api']->market(strtoupper($pair), $mrkt_key, $mrkt_val)['last_trade'] );
		          
			            // Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
			            // FUTURE-PROOF FIAT ROUNDING WITH $min_crypto_val_test, IN CASE BITCOIN MOONS HARD
			            if ( stristr($mrkt_key, 'bitmex_') == false && $ct['btc_pair_mrkts'][$pair.'_btc'] >= $min_crypto_val_test ) {
			              
				              // Data debugging telemetry
				              if ( $ct['conf']['power']['debug_mode'] == 'all_telemetry' ) {
				              	
				              $ct['gen']->log(
				              			  'market_debug',
				              			  'this->pair_btc_val() market request succeeded for ' . $pair,
				              			  'exchange: ' . $mrkt_key
				              			 );
				              
				              }
			                
			            return $ct['btc_pair_mrkts'][$pair.'_btc'];
			            
			            }
			            // ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
			            // We only want to loop a fallback for the amount of available markets
			            elseif (
			            is_array($ct['btc_pair_mrkts_excluded'][$pair])
			            && sizeof($ct['btc_pair_mrkts_excluded'][$pair]) >= sizeof($ct['conf']['assets'][strtoupper($pair)]['pair']['btc']) 
			            ) {
			            	
			            $ct['gen']->log(
			            			'market_error',
			            							
			            			'this->pair_btc_val() - market request failure (all '.sizeof($ct['btc_pair_mrkts_excluded'][$pair]).' markets failed) for ' . $pair . ' / btc (' . $mrkt_key . ')',
			            							
			            			$pair . '_mrkts_excluded_count: ' . sizeof($ct['btc_pair_mrkts_excluded'][$pair])
			            		    );
			            
			            return null;
			            
			            }
			            else {
			                 
			            $ct['btc_pair_mrkts'][$pair.'_btc'] = null; // Reset

			            $ct['btc_pair_mrkts_excluded'][$pair][] = $mrkt_key; // Market exclusion list, getting pair data from this exchange IN ANY PAIR, for this runtime only

			            return $this->pair_btc_val($pair);

			            }
		          
		         }
	          
	          
	        }
      
      return null; // If we made it this deep in the logic, no data was found	
      
      }
      // If we need a BITCOIN/CURRENCY market value 
      // RUN AFTER CRYPTO MARKETS...WE HAVE A COUPLE CRYPTOS SUPPORTED HERE, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
      elseif (
      is_array($ct['conf']['assets']['BTC']['pair'][$pair])
      && sizeof($ct['conf']['assets']['BTC']['pair'][$pair]) > 0 
      ) {
      
      
	        // Preferred BITCOIN market(s) for getting a certain currency's value, if in config and more than one market exists
	        if ( 
	        sizeof($ct['conf']['assets']['BTC']['pair'][$pair]) > 1 
	        && array_key_exists($pair, $ct['opt_conf']['bitcoin_preferred_currency_markets']) 
	        ) {
	        $mrkt_override = $ct['opt_conf']['bitcoin_preferred_currency_markets'][$pair];
	        }
	            
	            
	        // Loop until we find a market override / non-excluded pair market
	        foreach ( $ct['conf']['assets']['BTC']['pair'][$pair] as $mrkt_key => $mrkt_val ) {
	            
	            
	              if ( is_array($ct['btc_pair_mrkts_excluded'][$pair]) && in_array($mrkt_key, $ct['btc_pair_mrkts_excluded'][$pair]) ) {
	              $mrkt_blacklisted = true;
	              }
	              else {
	              $mrkt_blacklisted = false;
	              }
	              
	              
	              if ( is_array($ct['btc_pair_mrkts_excluded'][$pair]) && in_array($mrkt_override, $ct['btc_pair_mrkts_excluded'][$pair]) ) {
	              $mrkt_override_blacklisted = true;
	              }
	              else {
	              $mrkt_override_blacklisted = false;
	              }
	              
	              
		         if (
		         isset($mrkt_override) && $mrkt_override == $mrkt_key && !$mrkt_blacklisted
		         || isset($mrkt_override) && $mrkt_override != $mrkt_key && $mrkt_override_blacklisted && !$mrkt_blacklisted
		         || !isset($mrkt_override) && !$mrkt_blacklisted
		         ) {
		                
		          
        		        if ( $ct['api']->market(strtoupper($pair), $mrkt_key, $mrkt_val)['last_trade'] > 0 ) {
        		        $ct['btc_pair_mrkts'][$pair.'_btc'] = $ct['var']->num_to_str( 1 / $ct['api']->market(strtoupper($pair), $mrkt_key, $mrkt_val)['last_trade'] );
        		        }
        		        else {
        		        $ct['btc_pair_mrkts'][$pair.'_btc'] = null;
        		        }
		                
		                
			            // Fallback support IF THIS IS A FUTURES MARKET (we want a normal / current value), OR no data returned
			            // FUTURE-PROOF FIAT ROUNDING WITH $min_crypto_val_test, IN CASE BITCOIN MOONS HARD
			            if ( stristr($mrkt_key, 'bitmex_') == false && $ct['btc_pair_mrkts'][$pair.'_btc'] >= $min_crypto_val_test ) {
			                  
				              // Data debugging telemetry
				              if ( $ct['conf']['power']['debug_mode'] == 'all_telemetry' ) {
				              	
				              $ct['gen']->log(
				              			  'market_debug',
				              			  'ct_asset->pair_btc_val() market request succeeded for ' . $pair,
				              			  'exchange: ' . $mrkt_key
				              			 );
				              
				              }
			                  
			            return $ct['btc_pair_mrkts'][$pair.'_btc'];
			                
			            }
			            // ONLY LOG AN ERROR IF ALL AVAILABLE MARKETS FAIL (AND RETURN NULL)
			            // We only want to loop a fallback for the amount of available markets
			            elseif (
			            is_array($ct['btc_pair_mrkts_excluded'][$pair])
			            && sizeof($ct['btc_pair_mrkts_excluded'][$pair]) >= sizeof($ct['conf']['assets']['BTC']['pair'][$pair])
			            ) {
			            	
			            $ct['gen']->log(
			            			'market_error',
			            			'this->pair_btc_val() - market request failure (all '.sizeof($ct['btc_pair_mrkts_excluded'][$pair]).' markets failed) for btc / ' . $pair . ' (' . $mrkt_key . ')', $pair . '_mrkts_excluded_count: ' . sizeof($ct['btc_pair_mrkts_excluded'][$pair])
			            		    );
			            
			            return null;
			            
			            }
			            else {
			                 
			            $ct['btc_pair_mrkts'][$pair.'_btc'] = null; // Reset	

			            $ct['btc_pair_mrkts_excluded'][$pair][] = $mrkt_key; // Market exclusion list, getting pair data from this exchange IN ANY PAIR, for this runtime only

			            return $this->pair_btc_val($pair);

			            }
		          
		              
		         }
	              
	                
	        }
      
      return null; // If we made it this deep in the logic, no data was found	
             
      }
      else {
	        
	 $ct['gen']->log(
	        			'market_error',
	        			'this->pair_btc_val() - market failure (unknown pair) for ' . $pair
	        			);
	        
      return null;
      
      }
      
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function ui_asset_row($asset_name, $asset_symb, $asset_amnt, $all_pair_mrkts, $sel_pair, $sel_exchange, $purchase_price=null, $lvrg_level, $sel_mrgntyp) {
   
   // Globals
   global $ct, $min_fiat_val_test, $min_crypto_val_test, $watch_only_flag_val;
   
     
      // If asset is no longer configured in app config, return false for UX / runtime speed
      if ( !isset($ct['conf']['assets'][$asset_symb]) ) {
      return false;
      }
    
    
      //  For faster runtimes, minimize runtime usage here to held / watched amount
      if ( $asset_amnt >= $watch_only_flag_val ) {
           
      // CONTINUE
        
           // For watch-only, we always want only zero to show here in the UI (with no decimals)
           if ( $asset_amnt == $watch_only_flag_val ) {
           $asset_amnt = 0;
           }
      
      }
      else {
      return false;
      }
        
        
   $rand_id = rand(10000000,100000000);
       
   $original_mrkt = $sel_exchange;
        
   $sort_order = ( array_search($asset_symb, array_keys($ct['conf']['assets'])) + 1);
      
   $all_pairs = $ct['conf']['assets'][$asset_symb]['pair'];
       
       
      // FLAG SELECTED PAIR IF FIAT EQUIVALENT formatting should be used, AS SUCH
      // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
      if ( array_key_exists($sel_pair, $ct['conf']['assets']['BTC']['pair']) && !array_key_exists($sel_pair, $ct['opt_conf']['crypto_pair']) ) {
      $has_btc_pairing = true;
      }
      
        
      // UI table coloring
      if ( !$ct['td_color_zebra'] || $ct['td_color_zebra'] == '#d6d4d4' ) {
      $ct['td_color_zebra'] = 'white';
      }
      else {
      $ct['td_color_zebra'] = '#d6d4d4';
      }
      
        
      // Consolidate function calls for runtime speed improvement
      // (called here so first runtime with NO SELECTED ASSETS RUNS SIGNIFICANTLY QUICKER)
      if ( $ct['conf']['gen']['primary_marketcap_site'] == 'coingecko' && is_array($ct['coingecko_api']) && sizeof($ct['coingecko_api']) < 1 ) {
      $ct['coingecko_api'] = $ct['api']->mcap_data_coingecko();
      }
      elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' && is_array($ct['coinmarketcap_api']) && sizeof($ct['coinmarketcap_api']) < 1 ) {
      $ct['coinmarketcap_api'] = $ct['api']->mcap_data_coinmarketcap();
      }
        
        
      // Update, get the selected market name
      $loop = 0;
      foreach ( $all_pair_mrkts as $key => $val ) {
        
	      if ( $loop == $sel_exchange ) {
	      $sel_exchange = $key;
	      }
           
      $loop = $loop + 1;
      }
      $loop = null; 
      
      
   $mrkt_id = $all_pair_mrkts[$sel_exchange];
    
   // Get coin values, including non-BTC pairs
   
   // Consolidate function calls for runtime speed improvement
   $asset_mrkt_data = $ct['api']->market($asset_symb, $sel_exchange, $mrkt_id, $sel_pair);
    
   $asset_val_raw = $asset_mrkt_data['last_trade'];
        
   $asset_val_total_raw = $ct['var']->num_to_str($asset_amnt * $asset_val_raw);
    
   // SUPPORTED even for BTC ( $this->pair_btc_val('btc') ALWAYS = 1 ), 
   // since we use this var for secondary trade / holdings values logic further down
   $pair_btc_val = $this->pair_btc_val($sel_pair); 
       
       
      if ( $pair_btc_val == null ) {
       	
      $ct['gen']->log(
       			  'market_error',
       			  'this->pair_btc_val(\''.$sel_pair.'\') returned null in ct_asset->ui_asset_row(), likely from exchange API request failure'
       			 );
       
      }
    
    
   $asset_prim_currency_worth_raw = ($asset_val_total_raw * $pair_btc_val) * $ct['sel_opt']['sel_btc_prim_currency_val'];
        
        
      // BITCOIN (OVERWRITE W/ DIFF LOGIC)
      if ( strtolower($asset_name) == 'bitcoin' ) {
      $btc_trade_eqiv_raw = 1;
      $ct['btc_worth_array'][$asset_symb] = $asset_amnt;
      }
      else {
     
      $btc_trade_eqiv_raw = number_format( ($asset_val_raw * $pair_btc_val) , $ct['conf']['currency']['crypto_decimals_max'], '.', '');
      $btc_trade_eqiv_raw = $ct['var']->num_to_str($btc_trade_eqiv_raw); // Cleanup any trailing zeros
       
      $ct['btc_worth_array'][$asset_symb] = $ct['var']->num_to_str($asset_val_total_raw * $pair_btc_val);
       
          if ( preg_match("/stock/i", $asset_symb) ) {
          $ct['stocks_btc_worth_array'][$asset_symb] = $ct['var']->num_to_str($asset_val_total_raw * $pair_btc_val);
          }
       
      }
     
     
      // Calculate gain / loss if purchase price was populated, AND asset held is populated
      if ( $purchase_price >= $min_fiat_val_test && $asset_amnt >= $min_crypto_val_test ) {
       
      //echo ' ' . $asset_symb . ': ' . $purchase_price . ' => ' . $asset_amnt . ' || ';
       
      $asset_paid_total_raw = ($asset_amnt * $purchase_price);
        
      $gain_loss = $asset_prim_currency_worth_raw - $asset_paid_total_raw;
    
    
          // Convert $gain_loss for shorts with leverage
          if ( $lvrg_level >= 2 && $sel_mrgntyp == 'short' ) {
         
          $prev_gain_loss_val = $gain_loss;
         
              	if ( $prev_gain_loss_val >= 0 ) {
              	$gain_loss = $prev_gain_loss_val - ( $prev_gain_loss_val * 2 );
              	$asset_prim_currency_worth_raw = $asset_prim_currency_worth_raw - ( $prev_gain_loss_val * 2 );
              	}
              	else {
              	$gain_loss = $prev_gain_loss_val + ( abs($prev_gain_loss_val) * 2 );
              	$asset_prim_currency_worth_raw = $asset_prim_currency_worth_raw + ( abs($prev_gain_loss_val) * 2 );
              	}
         
          }
        
        
      // Gain / loss percent (!MUST NOT BE! absolute value)
      $gain_loss_percent = ($asset_prim_currency_worth_raw - $asset_paid_total_raw) / abs($asset_paid_total_raw) * 100;
        
      // Check for any leverage gain / loss
      $only_lvrg_gain_loss = ( $lvrg_level >= 2 ? ($gain_loss * ($lvrg_level - 1) ) : 0 );
        
      $inc_lvrg_gain_loss = ( $lvrg_level >= 2 ? ($gain_loss * $lvrg_level) : $gain_loss );
        
      $inc_lvrg_gain_loss_percent =  ( $lvrg_level >= 2 ? ($gain_loss_percent * $lvrg_level) : $gain_loss_percent );
        
      }
      else {
      $no_purchase_price = 1;
      $purchase_price = null;
      $asset_paid_total_raw = null;
      }
      
     
   $ct['asset_stats_array'][] = array(
                                 'coin_symb' => $asset_symb, 
                                 'coin_lvrg' => $lvrg_level,
                                 'selected_mrgntyp' => $sel_mrgntyp,
                                 'coin_worth_total' => $asset_prim_currency_worth_raw,
                                 'coin_total_worth_if_purchase_price' => ($no_purchase_price == 1 ? null : $asset_prim_currency_worth_raw),
                                 'coin_paid' => $purchase_price,
                                 'coin_paid_total' => $asset_paid_total_raw,
                                 'gain_loss_only_lvrg' => $only_lvrg_gain_loss,
                                 'gain_loss_total' => $inc_lvrg_gain_loss,
                                 'gain_loss_percent_total' => $inc_lvrg_gain_loss_percent,
                                 );
        
        
   // Get trade volume
   $trade_vol = $asset_mrkt_data['24hr_prim_currency_vol'];
    
   // Rendering webpage UI output
   // DON'T USE require_once(), as we are looping here!
   require($ct['base_dir'] . '/templates/interface/php/user/user-elements/portfolio-asset-row.php');

   
   }
   

   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function market_id_parse($exchange_key, $market_id, $known_pairing=false, $known_asset=false) {
   
   global $ct;
   
   
        // We only need this function during market searches
        if ( !$ct['ticker_markets_search'] ) {
        return false;
        }
        
   
   $results = array();
   
   
        if ( $known_pairing && trim($known_pairing) != '' ) {
        $results['pairing'] = $known_pairing;
        }
        // Otherwise, parse pairing results out
        else {
             
              
              // Easy parsing
              if ( $exchange_key == 'bitbns' || $exchange_key == 'unocoin' ) {
              $results['pairing'] = 'inr';
              }
              elseif ( $exchange_key == 'coinspot' ) {
              $results['pairing'] = 'aud';
              }
              // Alphavantage still needs pairing determination for SINGLE-EXCHANGE SEARCHES
              elseif ( $exchange_key == 'alphavantage_stock' ) {
                   
                   
                   if ( stristr($market_id, '.') ) {
                        
                        if ( stristr($market_id, '.TRT') || stristr($market_id, '.TRV') ) {
                        $results['pairing'] = 'cad';
                        }
                        elseif ( stristr($market_id, '.DEX') || stristr($market_id, '.FRK') ) {
                        $results['pairing'] = 'eur';
                        }
                        elseif ( stristr($market_id, '.SHH') || stristr($market_id, '.SHZ') ) {
                        $results['pairing'] = 'rmb';
                        }
                        elseif ( stristr($market_id, '.LON') ) {
                        $results['pairing'] = 'usd'; // usd for some odd reason
                        }
                        elseif ( stristr($market_id, '.SAO') ) {
                        $results['pairing'] = 'brl';
                        }
                        elseif ( stristr($market_id, '.BSE') ) {
                        $results['pairing'] = 'inr';
                        }
                   
                   }
                   else {
                   $results['pairing'] = 'usd';
                   }
                   
              
              }
              // Advanced parsing
              else {
                   
              $cleaned_market_id = $market_id;
             
                       
                   // IF WE NEED SOME REGEX MAGIC TO PARSE THE VALUES WE WANT
                   // https://www.threesl.com/blog/special-characters-regular-expressions-escape/
                   if ( $exchange_key == 'loopring' ) {
                   $cleaned_market_id = preg_replace("/AMM-/i", "", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'bitmex' || $exchange_key == 'luno' ) {
                   $cleaned_market_id = preg_replace("/XBT/i", "BTC", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'aevo' ) {
                   $cleaned_market_id = preg_replace("/-PERP/i", "-USD", $cleaned_market_id);
                   }
                   elseif ( $exchange_key == 'crypto.com' ) {
                   $cleaned_market_id = preg_replace("/-PERP(.*)/i", "", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/USD-(.*)/i", "USD", $cleaned_market_id);
                   }
                   // WTF Kraken, LMFAO :)
                   elseif ( $exchange_key == 'kraken' ) {
                   
                   $cleaned_market_id = preg_replace("/XXBTZ/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XXBT/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XBT/i", "BTC", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XETHZ/i", "ETH", $cleaned_market_id);
                   $cleaned_market_id = preg_replace("/XETH/i", "ETH", $cleaned_market_id);
          
                   }
                   elseif ( $exchange_key == 'bybit' && substr($cleaned_market_id, 0, 4) == '1000' ) {
                   $cleaned_market_id = substr($cleaned_market_id, 4);
                   }
                   elseif (
                   $exchange_key == 'bitfinex' && substr($cleaned_market_id, 0, 1) == 't'
                   || 
                   $exchange_key == 'ethfinex' && substr($cleaned_market_id, 0, 1) == 't'
                   ) {
                   $cleaned_market_id = substr($cleaned_market_id, 1);
                   }
                   

              $parsed_pairing = strtolower($cleaned_market_id);
                  
                  
                  if ( in_array($exchange_key, $ct['dev']['hyphen_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)-/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['reverse_hyphen_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/-(.*)/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['underscore_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)_/i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['forwardlash_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*)\//i", "", $parsed_pairing);
                  }
                  elseif ( in_array($exchange_key, $ct['dev']['colon_delimited_markets']) ) {
                  $parsed_pairing = preg_replace("/(.*):/i", "", $parsed_pairing);
                  }                 
                  
                  
                  // If we haven't registered all pairs yet this runtime, do it now
                  // (we do a RUNTIME memory cache, to optimize / increase runtime speed)
                  if ( sizeof($ct['registered_pairs']) < 1 ) {
                       
                  $temp_array = array();
                       
                  // IF the TICKER was a PARTIAL MATCH, we may NOT have CLEANLY parsed out the pairing yet...
                       
                  // HARD-CODED SPECIFIC / POPULAR pairing support (that we don't bundle with fresh install DEMO data)
                  $other_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['additional_pairings_search']) );
                       
                  // Coingecko pairing support
                  $coingecko_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['coingecko_pairings_search']) );
                       
                  // Upbit pairing support
                  $upbit_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['upbit_pairings_search']) );
                       
                  // jupiter_ag pairing support
                  $jupiter_ag_pairings = array_map( "trim", explode(',', $ct['conf']['currency']['jupiter_ag_pairings_search']) );
                   
                       
                       // Other pairings    
                       foreach ( $other_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // Coingecko pairings    
                       foreach ( $coingecko_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // Upbit pairings    
                       foreach ( $upbit_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                   
                       
                       // jupiter_ag pairings    
                       foreach ( $jupiter_ag_pairings as $pair_val ) {
                       $temp_array[] = $pair_val;
                       }
                       
                       
                       // BTC currency pairings
                       foreach ( $ct['conf']['assets']['BTC']['pair'] as $pairing_key => $unused ) {
                       $temp_array[] = $pairing_key;
                       }
                       
                       
                       // 'crypto_pair' pairings
                       foreach ( $ct['opt_conf']['crypto_pair'] as $pairing_key => $unused ) {
                       $temp_array[] = $pairing_key;
                       }
               
                      
                       // Cleanup
                       if ( sizeof($temp_array) > 0 ) { 
                       
                       // Remove whitespace
                       $temp_array = array_map("trim", $temp_array);
                       
                       // To lowercase
                       $temp_array = array_map("strtolower", $temp_array);
                       
                       // Remove duplicates
                       $temp_array = array_unique($temp_array);
                       
                       // Get usd near top, and btc / eth below eur, before the usort below,
                       // so we are looking for usd / eur pairings as early as possible (in the 3 character range)
                       rsort($temp_array);
                       
                       // Now sort by length, so we are checking for LONGER pairings first (to assure SAFEST parsed results)
                       usort($temp_array, array($ct['gen'], 'usort_length') );
                       
                       $ct['registered_pairs'] = $temp_array; // Set global now, since we finished building / sorting
          
                       }
                  
        
                  gc_collect_cycles(); // Clean memory cache
                  
                  }
                       
                  
                  if ( is_array($ct['registered_pairs']) && sizeof($ct['registered_pairs']) > 0 ) {


                       foreach ( $ct['registered_pairs'] as $val ) {
                            
                             
                             // Leave loop, if match already found
                             if ( $pairing_match ) {
                             break; 
                             }
                             // REVERSE ordered (pairing at FRONT of market id)
                             elseif ( in_array($exchange_key, $ct['dev']['reverse_id_markets']) ) {
                                     
                                 if ( substr($parsed_pairing, 0, strlen($val) ) == $val ) {
                                 $pairing_match = $val;
                                 }
                                     
                             }
                             // COMMON ordered (pairing at END of market id)
                             elseif ( substr($parsed_pairing, -strlen($val) ) == $val ) {
                             $pairing_match = $val;
                             }
                            
                            
                       }
                       
                       
                  $results['pairing'] = $pairing_match;          
                  
                  }
                  else {
                       
                  $ct['gen']->log( 'market_error', '"registered_pairs" array was not populated with any pairings (to search for in market ids)');
                  
                  // Set EVERYTHING to false, except exchange key
                  $results['pairing'] = false;
                  $results['asset'] = false;
                  $results['flagged_market'] = false;
                  $results['exchange'] = $exchange_key;
                  
                  return $results;
        
                  }
                  
                  
              }
              
             
             // Log / return false if no pairing was parsed out
             if (
             !isset($results['pairing'])
             || trim($results['pairing']) == ''
             || strtolower($results['pairing']) == strtolower($market_id)
             ) {
                  
                  
                  if ( $ct['conf']['power']['debug_mode'] == 'setup_wizards_io' ) {
                  $ct['gen']->log( 'other_debug', 'value NOT parsed for "pairing" (value = '.$results['pairing'].'), within market id "'.$market_id.'", during asset market search: "' . $_POST['add_markets_search'] . '" (for exchange API '.$exchange_key.')');
                  }
                  
             
             // Set EVERYTHING to false, except exchange key
             $results['pairing'] = false;
             $results['asset'] = false;
             $results['flagged_market'] = false;
             $results['exchange'] = $exchange_key;
             
             return $results;
             
             }
        
        
        } // END OF PAIRING PARSE ROUTINE
   
   
   // NOW WE CAN SAFELY PARSE OUT THE ASSET...
   
        if ( $known_asset && trim($known_asset) != '' ) {
        $results['asset'] = $known_asset;
        }
        // If we couldn't parse out the pairing, we also can't parse out the asset
        elseif ( trim($results['pairing']) == '' ) {
        $results['asset'] = ''; 
        }
        // We flag stocks in this app with the suffix: STOCK [TICKERSTOCK]
        elseif ( $exchange_key == 'alphavantage_stock' ) {
        $results['asset'] = preg_replace("/\.(.*)/i", "", $market_id) . 'STOCK'; 
        }
        else {
             
        $parsed_asset = ( $cleaned_market_id ? $cleaned_market_id : $market_id );
             
        $results['asset'] = preg_replace("/".$results['pairing']."/i", "", $parsed_asset);
        
        }
        
        
   // Remove 'perp', if in asset name
   $results['asset'] = preg_replace("/perp/i", "", $results['asset']);
           
   // Remove everything NOT alphanumeric,
   // AS IT'S A SHITSHOW SUPPORTING $TICKER etc in this app AT THE DATASET STORAGE LEVEL (should be interface ONLY!)
   $results['asset'] = preg_replace("/[^0-9a-zA-Z]+/i", "", $results['asset']);
        
   // Lowercase / Trim any whitespace off ends
   $results['asset'] = strtolower( trim($results['asset']) );
      
        
   // Remove everything NOT alphanumeric, Lowercase / Trim any whitespace off ends
   $results['pairing'] = preg_replace("/[^0-9a-zA-Z]+/i", "", $results['pairing']);
   $results['pairing'] = strtolower( trim($results['pairing']) );
   
        
        if ( $exchange_key == 'coingecko' ) {
        $exchange_check = $exchange_key . '_' . $results['pairing'];
        }
        else {
        $exchange_check = $exchange_key;
        }
             
             
        // Convert WRAPPED CRYPTO TICKERS to their NATIVE tickers
        // MUST BE AFTER PARSING OUT ASSET!
        if ( $results['pairing'] == 'tbtc' || $results['pairing'] == 'wbtc' ) {
        $results['pairing'] = 'btc';
        }
        elseif ( $results['pairing'] == 'weth' ) {
        $results['pairing'] = 'eth';
        }
        // Convert INTERNATIONAL TICKERS to their NATIVE tickers
        elseif ( $results['pairing'] == 'cny' ) {
        $results['pairing'] = 'rmb';
        }
        elseif ( $results['pairing'] == 'ils' ) {
        $results['pairing'] = 'nis';
        }
   
   
        // If already added
        if ( isset($ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check]) ) {
       
           if ( $ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check] == $market_id ) {
           $results['flagged_market'] = 'already_added_' . $market_id;
           }
           else {
           $results['flagged_market'] = 'replacement_for_' . $ct['conf']['assets'][strtoupper($results['asset'])]['pair'][strtolower($results['pairing'])][$exchange_check];
           }
       
        }
        // If no bitcoin market for pairing (to convert currency value to other currencies)
        elseif ( strtolower($results['asset']) != 'btc' && strtolower($results['pairing']) != 'btc' && !isset($ct['conf']['assets']['BTC']['pair'][strtolower($results['pairing'])]) ) {
        $results['flagged_market'] = 'pairing_not_supported_' . strtolower($results['pairing']);
        }
        else {
        $results['flagged_market'] = false;
        }
        

   $results['exchange'] = $exchange_key;
        
   gc_collect_cycles(); // Clean memory cache

   return $results;
      
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////
   
   
   function charts_price_alerts($asset_data, $exchange, $pair, $mode) {
   
   // Globals
   global $ct, $min_fiat_val_test, $min_crypto_val_test;
      
   $pair = strtolower($pair);
   
   // Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
   $asset = ( stristr($asset_data, "-") == false ? $asset_data : substr( $asset_data, 0, mb_strpos($asset_data, "-", 0, 'utf-8') ) );
   $asset = strtoupper($asset);
        
   $pair_btc_val = $this->pair_btc_val($pair); 
   
      
	 if ( $pair_btc_val == null ) {
	        	
	 $ct['gen']->log(
	        			 'market_error',
	        			 'this->pair_btc_val() returned null in ct_asset->charts_price_alerts() (for ' . $pair . ')'
	        			);
	        
	 }
   
   
      // Fiat or equivalent pair?
      // #FOR CLEAN CODE#, RUN CHECK TO MAKE SURE IT'S NOT A CRYPTO AS WELL...WE HAVE A COUPLE SUPPORTED, BUT WE ONLY WANT DESIGNATED FIAT-EQIV HERE
      if ( array_key_exists($pair, $ct['conf']['assets']['BTC']['pair']) && !array_key_exists($pair, $ct['opt_conf']['crypto_pair']) ) {
      $has_btc_pairing = true;
      $min_vol_val_test = $min_fiat_val_test;
      }
      else {
      $min_vol_val_test = $min_crypto_val_test;
      }
      
      
      // RUN BASIC CHECKS FIRST...
      
      // For UX, scan to remove any old stale price alert entries that are now disabled / disabled GLOBALLY 
      // Return false if there is no charting on this entry (to optimize runtime)
      if ( $mode != 'alert' && $mode != 'both' || $ct['conf']['charts_alerts']['price_alert_threshold'] == 0 ) {
      
          // For UX, if this is an alert that has been enabled previously, then disabled later on, we remove stale data
          // (for correct and up-to-date time / price change percent stats, IN CASE the user RE-ENABLES this alert at a later date)
          if ( file_exists($ct['base_dir'] . '/cache/alerts/fiat_price/'.$asset_data.'.dat') ) {
          unlink($ct['base_dir'] . '/cache/alerts/fiat_price/'.$asset_data.'.dat'); 
          }
      
          // If we are not running charting logic, we can safely return false now 
          if ( $mode != 'chart' && $mode != 'both' ) {
          return false;
          }
          
      }
      
      
      // Skip completely, if it's an alphavantage market, AND the end-user has NOT added an alphavantage API key
      if ( $exchange == 'alphavantage_stock' && trim($ct['conf']['ext_apis']['alphavantage_api_key']) == '' ) {
      return false;
      }      
      
      
      // Return false if we have no minimum bitcoin primary currency value
      if ( isset($ct['default_bitcoin_primary_currency_val']) && $ct['default_bitcoin_primary_currency_val'] >= $min_crypto_val_test ) {
      // Continue
      }
      else {
      	
      $ct['gen']->log(
      			'market_error',
      							
      			'ct_asset->charts_price_alerts() - Minimum Bitcoin '.strtoupper($ct['default_bitcoin_primary_currency_pair']).' value ('.strtoupper($pair).' pair) not met for "' . $asset_data . '"',
      							
      			$asset_data . ': ' . $asset . ' / ' . strtoupper($pair) . ' @ ' . $exchange . ';'
      			);
      			
      return false;
      
      }
      
      
   // IF BASIC CHECKS PASSED, CHECK THE PRIMARY CURRENCY VALUE NEXT...
   
   
   // Get any necessary variables for calculating asset's PRIMARY CURRENCY CONFIG value
   
   // Consolidate function calls for runtime speed improvement
   $asset_mrkt_data = $ct['api']->market($asset, $exchange, $ct['conf']['assets'][$asset]['pair'][$pair][$exchange], $pair);
      
      
      // Get asset PRIMARY CURRENCY CONFIG value
      /////////////////////////////////////////////////////////////////
      // PRIMARY CURRENCY CONFIG CHARTS
      if ( $pair == $ct['default_bitcoin_primary_currency_pair'] ) {
      $asset_prim_currency_val_raw = $asset_mrkt_data['last_trade']; 
      }
      // BTC PAIRS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS
      elseif ( $pair == 'btc' ) {
      $asset_prim_currency_val_raw = number_format( $ct['default_bitcoin_primary_currency_val'] * $asset_mrkt_data['last_trade'] , $ct['conf']['currency']['crypto_decimals_max'], '.', '');
      }
      // OTHER PAIRS CONVERTED TO PRIMARY CURRENCY CONFIG (EQUIV) CHARTS, IF $pair_btc_val IS SET
      elseif ( $pair_btc_val != null ) {
      $asset_prim_currency_val_raw = number_format( $ct['default_bitcoin_primary_currency_val'] * ( $asset_mrkt_data['last_trade'] * $pair_btc_val ) , $ct['conf']['currency']['crypto_decimals_max'], '.', '');
      }
      
      
      // Cleanup the asset value 
      if ( $asset_prim_currency_val_raw >= $min_fiat_val_test ) {
      // Continue
      // Round PRIMARY CURRENCY CONFIG asset price to only keep $ct['conf']['currency']['currency_decimals_max'] decimals maximum 
      $asset_prim_currency_val_raw = round($asset_prim_currency_val_raw, $ct['conf']['currency']['currency_decimals_max']);
      $asset_prim_currency_val_raw = $ct['var']->num_to_str($asset_prim_currency_val_raw); // Cleanup any trailing zeros
      }
      // Return false if we have no minimum asset value
      else {
      	
      $ct['gen']->log(
      
      		    'market_error',
      							
      		    'ct_asset->charts_price_alerts() - Minimum '.strtoupper($ct['default_bitcoin_primary_currency_pair']).' conversion value ('.strtoupper($pair).' pair) not met for "' . $asset_data . '": ' . $asset_prim_currency_val_raw,
      							
      			$asset_data . ': ' . $asset . ' / ' . strtoupper($pair) . ' @ ' . $exchange . '; pair_id: ' . $ct['conf']['assets'][$asset]['pair'][$pair][$exchange] . ';'
      			
      		   );
      
      return false;
      
      }
      
      
   // IF PRIMARY CURRENCY VALUE CHECK PASSED, CONTINUE...
      
   
   /////////////////////////////////////////////////////////////////
     
   
      // If available, we'll use pair volume for a good chart volume UX
      if ( isset($asset_mrkt_data['24hr_pair_vol']) && $asset_mrkt_data['24hr_pair_vol'] > 0 ) {
      $pair_vol_raw = $asset_mrkt_data['24hr_pair_vol']; 
      }
      // Otherwise, convert any set '24hr_usd_vol' to pair volume, IF $pair_btc_val IS SET
      elseif ( $pair_btc_val != null && isset($asset_mrkt_data['24hr_usd_vol']) && $ct['var']->num_to_str($asset_mrkt_data['24hr_usd_vol']) > 0 ) {
           
      $btc_vol_raw = $ct['var']->num_to_str( $asset_mrkt_data['24hr_usd_vol'] * $this->pair_btc_val('usd') );         
           
      $pair_vol_raw = $ct['var']->num_to_str( $btc_vol_raw / $pair_btc_val );
      
      //$debug_array = array('btc_vol_raw' => $btc_vol_raw, 'pair_vol_raw' => $pair_vol_raw); // DEBUGGING ONLY
      
      // DEBUGGING ONLY (UNLOCKED file write)
      //$ct['cache']->other_cached_data('save', $ct['base_dir'] . '/cache/debugging/pair_vol.dat', $debug_array, true, "append", false);

      }
   
     
   // Round PAIR volume to only keep $ct['conf']['charts_alerts']['chart_crypto_volume_decimals'] decimals max (for crypto volume etc), to save on data set / storage size
   $pair_vol_raw = ( isset($pair_vol_raw) ? round($pair_vol_raw, ( $has_btc_pairing ? 0 : $ct['conf']['charts_alerts']['chart_crypto_volume_decimals'] ) ) : null );
   // Remove trailing zeros / scientific number format (on small / large numbers) from any rounding etc above
   $pair_vol_raw = ( $pair_vol_raw != null ? $ct['var']->num_to_str($pair_vol_raw) : null );
   
   
   $vol_prim_currency_raw = $asset_mrkt_data['24hr_prim_currency_vol'];
   
   // Round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts / for prettier numbers UX, and to save on data set / storage size
   $vol_prim_currency_raw = ( isset($vol_prim_currency_raw) ? round($vol_prim_currency_raw) : null );	
   // Cleanup any trailing zeros
   $vol_prim_currency_raw = $ct['var']->num_to_str($vol_prim_currency_raw);
   
      
      if ( $has_btc_pairing && $asset_mrkt_data['last_trade'] >= $min_fiat_val_test ) {
      $asset_pair_val_raw = number_format( $asset_mrkt_data['last_trade'] , $ct['conf']['currency']['currency_decimals_max'], '.', '');
      }
      elseif ( $asset_mrkt_data['last_trade'] >= $min_crypto_val_test ) {
      $asset_pair_val_raw = number_format( $asset_mrkt_data['last_trade'] , $ct['conf']['currency']['crypto_decimals_max'], '.', '');
      }
      // Return false if we have no minimum asset value
      else {
      	
      $ct['gen']->log(
      
      		    'market_error',
      							
      		    'ct_asset->charts_price_alerts() - Minimum '.( $has_btc_pairing ? 'currency' : 'crypto' ).' conversion value ('.strtoupper($pair).' pair) not met for "' . $asset_data . '": ' . $asset_mrkt_data['last_trade'],
      							
      			$asset_data . ': ' . $asset . ' / ' . strtoupper($pair) . ' @ ' . $exchange . '; pair_id: ' . $ct['conf']['assets'][$asset]['pair'][$pair][$exchange] . ';'
      			
      		   );
      
      return false;
      
      }
      
      
   $asset_pair_val_raw = $ct['var']->num_to_str($asset_pair_val_raw); // Cleanup any trailing zeros
      
      
   /////////////////////////////////////////////////////////////////
   
   // WE SET ALERT CACHE CONTENTS AS EARLY AS POSSIBLE, AS IT MAY BE DESIRED #OUTSIDE TRIGGERED ALERTS LOGIC# IN FUTURE LOGIC
   // WE USE PAIR VOLUME FOR VOLUME PERCENTAGE CHANGES, FOR BETTER PERCENT CHANGE ACCURACY THAN FIAT EQUIV
   $alert_cache_contents = $asset_prim_currency_val_raw . '||' . $vol_prim_currency_raw . '||' . $pair_vol_raw;
   

   // ARCHIVAL chart paths   
   $prim_currency_chart_path = $ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.strtolower($ct['default_bitcoin_primary_currency_pair']).'.dat';
   $crypto_secondary_currency_chart_path = $ct['base_dir'] . '/cache/charts/spot_price_24hr_volume/archival/'.$asset.'/'.$asset_data.'_chart_'.$pair.'.dat';
   
   
   /////////////////////////////////////////////////////////////////
   
   
      // Skip storing price chart data, IF API limits have been reached ON APIs REGISTERED IN: $ct['dev']['tracked_throttle_limited_servers']
      // (to save on storage space / using same repetitive CACHED API price data)
      // (ONLY IF THE *ARCHIVAL PRIMARY CURRENCY CHART* HAS BEEN UPDATED WITHIN THE PAST $ct['throttled_api_cache_time'][$api_tld_or_ip] MINUTES,
      // OTHERWISE IT COULD BE NEW PRICE DATA CACHED FROM INTERFACE USAGE ETC ETC, SO WE STILL WANT TO UPDATE CHARTS IN THIS CASE)
      foreach ( $ct['dev']['tracked_throttle_limited_servers'] as $api_tld_or_ip => $api_exchange_id ) {
      
           // Keep initial match check quick, for runtime speed
           if ( $exchange == $api_exchange_id ) {
                
               if ( isset($ct['api_throttle_flag'][$api_tld_or_ip]) && $ct['api_throttle_flag'][$api_tld_or_ip] == true && isset($ct['throttled_api_cache_time'][$api_tld_or_ip]) && $ct['cache']->update_cache($prim_currency_chart_path, $ct['throttled_api_cache_time'][$api_tld_or_ip]) == false ) {
               
               $halt_chart_storage = true;
           
                    if ( $ct['conf']['power']['debug_mode'] == 'api_throttling' ) {
                    
                     $ct['gen']->log(
                         	    'notify_debug',
                         	    'skipping "' . $api_exchange_id . '" price chart storage (for ' . strtoupper($asset_data) . '), to avoid exceeding API limits (' . $ct['throttled_api_cache_time'][$api_tld_or_ip] . ' minute MINIMUM API cache OR archival chart time interval NOT met)'
                         	   );
                    	   
                    }
               		  
               }
           
           }
      
      }
     
   
   /////////////////////////////////////////////////////////////////
      
      
      // Charts (WE DON'T WANT TO STORE DATA WITH A CORRUPT TIMESTAMP)
      // If the charts page is enabled in Admin Config, save latest chart data for assets with price alerts configured on them
      if (
      !$halt_chart_storage && $mode == 'both' && $asset_prim_currency_val_raw >= $min_fiat_val_test && $ct['conf']['charts_alerts']['enable_price_charts'] == 'on'
      || !$halt_chart_storage && $mode == 'chart' && $asset_prim_currency_val_raw >= $min_fiat_val_test && $ct['conf']['charts_alerts']['enable_price_charts'] == 'on'
      ) {
      
      // In case a rare error occured from power outage / corrupt memory / etc, we'll check the timestamp (in a non-resource-intensive way)
      // (#SEEMED# TO BE A REAL ISSUE ON A RASPI ZERO AFTER MULTIPLE POWER OUTAGES [ONE TIMESTAMP HAD PREPENDED CORRUPT DATA])
      $now = time();
      
      
	         if ( $now > 0 ) {
	         // Do nothing
	         }
	         else {
	         	
	         // Return false
	         $ct['gen']->log(
	         			  'system_error', 
	         			  'time() returned a corrupt value (from power outage / corrupt memory / etc), chart updating canceled',
	         			  'chart_type: asset market'
	         			 );
	         
	         return false;
	         
	         }
	         
        
      // PRIMARY CURRENCY CONFIG ARCHIVAL charts (CRYPTO/PRIMARY CURRENCY CONFIG markets, 
      // AND ALSO crypto-to-crypto pairs converted to PRIMARY CURRENCY CONFIG equiv value for PRIMARY CURRENCY CONFIG equiv charts)
      
      
      $prim_currency_chart_data = $now . '||' . $asset_prim_currency_val_raw . '||' . $vol_prim_currency_raw;
      $ct['cache']->save_file($prim_currency_chart_path, $prim_currency_chart_data . "\n", "append", false);  // WITH newline (UNLOCKED file write)
        
        
         // Crypto / secondary currency pair ARCHIVAL charts, volume as pair (for UX)
         if ( $pair != strtolower($ct['default_bitcoin_primary_currency_pair']) ) {
         $crypto_secondary_currency_chart_data = $now . '||' . $asset_pair_val_raw . '||' . $pair_vol_raw;
         $ct['cache']->save_file($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data . "\n", "append", false); // WITH newline (UNLOCKED file write)
         }
        
        
      // Light charts (update time dynamically determined in $ct['cache']->update_light_chart() logic)
      // Wait 0.05 seconds before updating light charts (which reads archival data)
      usleep(50000); // Wait 0.05 seconds
        
        
         foreach ( $ct['light_chart_day_intervals'] as $light_chart_days ) {
           
	           // If we reset light charts, just skip the rest of this update session
	           if ( $fiat_light_chart_result == 'reset' || $crypto_light_chart_result == 'reset' ) {
	           continue;
	           }
	           
         // Primary currency light charts
         $fiat_light_chart_result = $ct['cache']->update_light_chart($prim_currency_chart_path, $prim_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
             
	           // Crypto / secondary currency pair light charts (IF fiat light chart run didn't trigger a light chart reset)
	           if ( $pair != strtolower($ct['default_bitcoin_primary_currency_pair']) && $fiat_light_chart_result != 'reset' ) {
	           $crypto_light_chart_result = $ct['cache']->update_light_chart($crypto_secondary_currency_chart_path, $crypto_secondary_currency_chart_data, $light_chart_days); // WITHOUT newline (var passing)
	           }
         
         }
        
        
      }
      /////////////////////////////////////////////////////////////////
     
     
     
      // Alert checking START
      /////////////////////////////////////////////////////////////////
      if ( $mode == 'alert' && $ct['conf']['charts_alerts']['price_alert_threshold'] > 0 || $mode == 'both' && $ct['conf']['charts_alerts']['price_alert_threshold'] > 0 ) {
          
        
      // Grab any cached price alert data
      $data_file = trim( file_get_contents('cache/alerts/fiat_price/'.$asset_data.'.dat') );
        
      $cached_array = explode("||", $data_file);
      
      // PRIMARY CURRENCY CONFIG token value
      $cached_asset_prim_currency_val = $ct['var']->num_to_str($cached_array[0]);  
      
      // PRIMARY CURRENCY CONFIG volume value (round PRIMARY CURRENCY CONFIG volume to nullify insignificant decimal amounts skewing checks)
      $cached_prim_currency_vol = $ct['var']->num_to_str( round($cached_array[1]) ); 
      
      // Crypto volume value (more accurate percent increase / decrease stats than PRIMARY CURRENCY CONFIG value fluctuations)
      $cached_pair_vol = $ct['var']->num_to_str($cached_array[2]); 
        
        
        
          // Price checks (done early for including with price alert reset logic)
          // If a percent change can be determined
          if ( $cached_asset_prim_currency_val >= $min_fiat_val_test && $asset_prim_currency_val_raw >= $min_fiat_val_test ) {
          
          // PRIMARY CURRENCY CONFIG price percent change (!MUST BE! absolute value)
          $percent_change = abs( ($asset_prim_currency_val_raw - $cached_asset_prim_currency_val) / abs($cached_asset_prim_currency_val) * 100 );
          $percent_change = $ct['var']->num_to_str($percent_change); // Better decimal support
	                  
     	     // UX / UI variables
     	     if ( $asset_prim_currency_val_raw < $cached_asset_prim_currency_val ) {
     	     $change_symb = '-';
     	     $increase_decrease = 'decreased';
     	     }
     	     elseif ( $asset_prim_currency_val_raw >= $cached_asset_prim_currency_val ) {
     	     $change_symb = '+';
     	     $increase_decrease = 'increased';
     	     }
                  
          }
          // Percent change is undefined when the starting / ending value is 0
          else {
          $percent_change = 0;
	     $change_symb = '+';
     	$increase_decrease = 'increased';
          }
	                  
	          
	     // Check whether we should send an alert
          // We disallow alerts where minimum 24 hour trade PRIMARY CURRENCY CONFIG volume IS ABOVE ZERO (as zero can be a 'no vol API' flag), 
          // AND price_alert_minimum_volume config has NOT been met, ONLY if volume API request doesn't fail to retrieve volume data (which is flagged as -1)
          if ( $vol_prim_currency_raw > 0 && $vol_prim_currency_raw < $ct['conf']['charts_alerts']['price_alert_minimum_volume'] ) {
          $send_alert = false;
          }
          // We disallow alerts if they are not activated
          elseif ( $mode != 'both' && $mode != 'alert' ) {
          $send_alert = false;
          }
          // We disallow alerts if $ct['conf']['charts_alerts']['price_alert_block_volume_error'] is ON, and there is
          // a volume retrieval error (flagged as -1) #NOT RELATED# TO LACK OF VOLUME API features (flagged as 0)
          elseif ( $vol_prim_currency_raw == -1 && $ct['conf']['charts_alerts']['price_alert_block_volume_error'] == 'on' ) {
          $send_alert = false;
          }
          // If all passes check, flag to send alert
	     elseif ( $percent_change >= $ct['conf']['charts_alerts']['price_alert_threshold'] ) {
	     $send_alert = true;
	     }  
                  
                    
          ////// If flagged to run alerts //////////// 
          if ( $send_alert ) {
                  
          // Pretty exchange name / percent change for UI / UX (defined early for any price alert reset logic)
          $percent_change_text = number_format($percent_change, 2, '.', ',');
          $exchange_text = $ct['gen']->key_to_name($exchange);  
        
          // Check for a file modified time !!!BEFORE ANY!!! file creation / updating happens (to calculate time elapsed between updates)
          $last_cached_days = ( time() - filemtime('cache/alerts/fiat_price/'.$asset_data.'.dat') ) / 86400;
          $last_cached_days = $ct['var']->num_to_str($last_cached_days); // Better decimal support for whale alerts etc
           
           
               if ( $last_cached_days >= 365 ) {
               $last_cached_time = number_format( ($last_cached_days / 365) , 2, '.', ',') . ' years';
               }
               elseif ( $last_cached_days >= 30 ) {
               $last_cached_time = number_format( ($last_cached_days / 30) , 2, '.', ',') . ' months';
               }
              	elseif ( $last_cached_days >= 7 ) {
               $last_cached_time = number_format( ($last_cached_days / 7) , 2, '.', ',') . ' weeks';
               }
              	elseif ( $last_cached_days >= 1 ) {
               $last_cached_time = number_format($last_cached_days, 2, '.', ',') . ' days';
               }
              	elseif ( $last_cached_days >= (1 / 24) ) {
               $last_cached_time = number_format( ($last_cached_days * 24) , 2, '.', ',') . ' hours';
               }
               else {
               $last_cached_time = number_format( ($last_cached_days * 1440) , 0, '.', ',') . ' minutes';
               }
            
                   
               // Crypto volume checks
               // If a percent change can be determined
               if ( $cached_pair_vol >= $min_vol_val_test && $pair_vol_raw >= $min_vol_val_test ) {
                    
               // Crypto volume percent change (!MUST BE! absolute value)
               $vol_percent_change = abs( ($pair_vol_raw - $cached_pair_vol) / abs($cached_pair_vol) * 100 ); 
               // Better decimal support    
               $vol_percent_change = $ct['var']->num_to_str($vol_percent_change); 
               
                    // UI / UX variables
                    if ( $pair_vol_raw < $cached_pair_vol ) {
                    $vol_change_symb = '-';
                    }
                    elseif ( $pair_vol_raw > $cached_pair_vol ) {
                    $vol_change_symb = '+';
                    }
               
               }
               // Percent change is undefined when the starting / ending value is 0
               else {
               $vol_percent_change = 0;
               $vol_change_symb = '+';
               }    
                  
                  
               // Whale alert (price change average of X or greater over X day(s) or less, with X percent pair volume increase average that is at least a X primary currency volume increase average)
               $whale_alert_thres = array_map( "trim", explode("||", $ct['conf']['charts_alerts']['whale_alert_thresholds']) );
               ////
               ////
               if ( trim($whale_alert_thres[0]) != '' && trim($whale_alert_thres[1]) != '' && trim($whale_alert_thres[2]) != '' && trim($whale_alert_thres[3]) != '' ) {
                  
               $whale_max_days_to_24hr_avg_over = $ct['var']->num_to_str( trim($whale_alert_thres[0]) );
                  
               $whale_min_price_perc_change_24hr_avg = $ct['var']->num_to_str( trim($whale_alert_thres[1]) );
                  
               $whale_min_vol_percent_incr_24hr_avg = $ct['var']->num_to_str( trim($whale_alert_thres[2]) );
                  
               $whale_min_vol_currency_incr_24hr_avg = $ct['var']->num_to_str( trim($whale_alert_thres[3]) );
                  
                  
                    // WE ONLY WANT PRICE CHANGE PERCENT AS AN ABSOLUTE VALUE HERE, ALL OTHER VALUES SHOULD BE ALLOWED TO BE NEGATIVE IF THEY ARE NEGATIVE
                    if ( $last_cached_days <= $whale_max_days_to_24hr_avg_over 
                    && $ct['var']->num_to_str($percent_change / $last_cached_days) >= $whale_min_price_perc_change_24hr_avg 
                    && $ct['var']->num_to_str($vol_change_symb . $vol_percent_change / $last_cached_days) >= $whale_min_vol_percent_incr_24hr_avg 
                    && $ct['var']->num_to_str( ($vol_prim_currency_raw - $cached_prim_currency_vol) / $last_cached_days ) >= $whale_min_vol_currency_incr_24hr_avg ) {
                    $whale_alert = 1;
                    }
                    
                 
               }
                  
                  
               // Sending the alerts (if it's within RESENDING LIMITS)
               if ( $ct['cache']->update_cache('cache/alerts/fiat_price/'.$asset_data.'.dat', ( $ct['conf']['charts_alerts']['price_alert_frequency_maximum'] * 60 ) ) == true ) {
                  
              	// Message formatting for display to end user
              	
               $desc_alert_type = ( $ct['conf']['charts_alerts']['price_alert_fixed_reset'] > 0 ? 'reset' : 'alert' );
               
               
                     // Flag if there is no volume history AT ALL available for this market
                     // (so we can skip the EXTRA volume details in the alerts [for UX])
                     if ( $cached_pair_vol <= 0 && $pair_vol_raw <= 0 ) {
                     $no_volume_history = true;
                     }
                  
                    
                     // IF PRIMARY CURRENCY CONFIG volume was 0 or -1 last alert / reset, for UX sake we let users know
                     if ( $cached_prim_currency_vol == 0 ) {
                     $vol_describe = ' 24 hour ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' volume was ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . $cached_prim_currency_vol . ' last price ' . $desc_alert_type . ', and';
                     $vol_describe_mobile = ', ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' volume was ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . $cached_prim_currency_vol . ' last ' . $desc_alert_type;
                     }
                     // Best we can do feasibly for UX on volume reporting errors
                     elseif ( $cached_prim_currency_vol == -1 ) { // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                     $vol_describe = ' 24 hour ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' volume was NULL last price ' . $desc_alert_type . ', and';
                     $vol_describe_mobile = ', ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' volume was NULL last ' . $desc_alert_type;
                     }
                     else {
                     $vol_describe = ' 24 hour pair volume';
                     $vol_describe_mobile = ' pair volume';
                     }
                  
                  
               // Pretty up textual output to end-user (convert raw numbers to have separators, remove underscores in names, etc)
               // Pretty numbers UX on PRIMARY CURRENCY CONFIG asset value
              	     
               $thres_dec = $ct['gen']->thres_dec($asset_prim_currency_val_raw, 'u', 'fiat'); // Units mode
               
               $asset_prim_currency_text = $ct['var']->num_pretty($asset_prim_currency_val_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
                        
               $vol_prim_currency_text = $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . number_format($vol_prim_currency_raw, 0, '.', ',');
               
               
               // Email / telegram / etc
               $has_volume_data_text = $vol_describe . ' has ' . ( $vol_change_symb == '+' ? 'increased ' : 'decreased ' ) . $vol_change_symb . number_format($vol_percent_change, 2, '.', ',') . '% to a ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' value of ' . $vol_prim_currency_text . '.';
                        
               $vol_change_text = ( $no_volume_history ? '' : $has_volume_data_text );
               
               
               // Mobile text
               $has_volume_data_text_mobile = ' 24hr Volume: ' . $vol_prim_currency_text . ' (' . $vol_change_symb . number_format($vol_percent_change, 2, '.', ',') . '%' . $vol_describe_mobile . ')';
                        
               $vol_change_text_mobile = ( $no_volume_history ? '' : $has_volume_data_text_mobile );
                        
                        
                     // If -1 from exchange API error not reporting any volume data (not even zero)
                     // ONLY PRIMARY CURRENCY CONFIG VOLUME CALCULATION RETURNS -1 ON EXCHANGE VOLUME ERROR
                     if ( $cached_prim_currency_vol == -1 || $vol_prim_currency_raw == -1 ) {
                     $vol_change_text = ' 24 hour volume not detected, due to exchange API error.';
                     $vol_change_text_mobile = ' 24hr Volume: Exchange API Error';
                     }
                    
                    
                     // Format trade volume data
                     
                     // Volume filter skipped message, only if filter is on and error getting trade volume data (otherwise is NULL)
                     if ( $vol_prim_currency_raw == null && $ct['conf']['charts_alerts']['price_alert_minimum_volume'] > 0 || $vol_prim_currency_raw < 1 && $ct['conf']['charts_alerts']['price_alert_minimum_volume'] > 0 ) {
                     $vol_filter_skipped_text = ' (no trade volume detected, so volume filter was skipped)';
                     }
                     
                     
                     // Successfully received > 0 volume data, at or above an enabled volume filter
                     if ( $vol_prim_currency_raw > 0 && $ct['conf']['charts_alerts']['price_alert_minimum_volume'] > 0 && $vol_prim_currency_raw >= $ct['conf']['charts_alerts']['price_alert_minimum_volume'] ) {
                     $email_vol_summary = $vol_change_text . ' (volume filter on)';
                     }
                     // If volume is -1 or greater, without an enabled volume filter (or filter skipped)
                     elseif ( $vol_prim_currency_raw >= -1 ) {
                     $email_vol_summary = $vol_change_text . ( isset($vol_filter_skipped_text) ? $vol_filter_skipped_text : '' ); 
                     }
                     
                     
               // UX on stock symbols in alert messages (especially for alexa speaking alerts)
               $asset_text = preg_replace("/stock/i", " STOCK", $asset);
                        
                        
               // Build the different messages, configure comm methods, and send messages
                        
               $email_msg = ( $whale_alert == 1 ? 'WHALE ALERT: ' : '' ) . 'The ' . $asset_text . ' trade value in the ' . strtoupper($pair) . ' market at the ' . $exchange_text . ' exchange has ' . $increase_decrease . ' ' . $change_symb . $percent_change_text . '% in ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' value to ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . $asset_prim_currency_text . ' over the past ' . $last_cached_time . ' since the last price ' . $desc_alert_type . '.' . $email_vol_summary;
                        
                        
               // Were're just adding a human-readable timestamp to smart home (audio) alerts
               $notifyme_msg = $email_msg . ' Timestamp: ' . $ct['gen']->time_date_format($ct['conf']['gen']['local_time_offset'], 'pretty_time') . '.';
                        
                        
               $text_msg = ( $whale_alert == 1 ? '🐳 ' : '' ) . $asset_text . ' / ' . strtoupper($pair) . ' @ ' . $exchange_text . ' ' . $increase_decrease . ' ' . $change_symb . $percent_change_text . '% in ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' value to ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . $asset_prim_currency_text . ' over ' . $last_cached_time . '.' . $vol_change_text_mobile;
                        
                    
               // Message parameter added for desired comm methods (leave any comm method blank to skip sending via that method)
                        
               // Minimize function calls
               $text_msg = $ct['gen']->detect_unicode($text_msg); 
                        
               $send_params = array(
                  
                                    'notifyme' => $notifyme_msg,
                                    
                                    'telegram' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_msg, // Add emoji here, so it's not sent with alexa alerts
                                    
                                    'text' => array(
                                                    'message' => $text_msg['content'],
                                                    'charset' => $text_msg['charset']
                                                   ),
                                                    
                                    'email' => array(
                                                     'subject' => $asset_text . ' Asset Value '.ucfirst($increase_decrease).' Alert' . ( $whale_alert == 1 ? ' (🐳 WHALE ALERT)' : '' ),
                                                     'message' => ( $whale_alert == 1 ? '🐳 ' : '' ) . $email_msg // Add emoji here, so it's not sent with alexa alerts
                                                    )
                                                       
                                     );
                    
                    
               // Only send to comm channels the user prefers, based off the config setting $ct['conf']['charts_alerts']['price_alert_channels']
               $preferred_comms = $ct['gen']->preferred_comms($ct['conf']['charts_alerts']['price_alert_channels'], $send_params);
                    
               // Send notifications
               @$ct['cache']->queue_notify($preferred_comms);
                        
               // Cache the new lower / higher value + volume data
               $ct['cache']->save_file($ct['base_dir'] . '/cache/alerts/fiat_price/'.$asset_data.'.dat', $alert_cache_contents); 
                  
               }
                  
          
          }
          // If run alerts not triggered, BUT asset price exists, we run any required additional logic
          elseif ( $asset_prim_currency_val_raw >= $min_fiat_val_test ) {
       
       
             	 // Not already run at least once (alert cache file not created yet)
             	 if ( !file_exists('cache/alerts/fiat_price/'.$asset_data.'.dat') ) {
             	 $ct['cache']->save_file($ct['base_dir'] . '/cache/alerts/fiat_price/'.$asset_data.'.dat', $alert_cache_contents); 
             	 }
             	 // Config setting set to ALWAYS reset every X days (and X days threshold has been met)
     	      // With offset, to try keeping daily recurrences at same exact runtime (instead of moving up the runtime daily)
             	 elseif ( 
             	 $ct['conf']['charts_alerts']['price_alert_fixed_reset'] >= 1 
             	 && $ct['cache']->update_cache('cache/alerts/fiat_price/'.$asset_data.'.dat', ( $ct['conf']['charts_alerts']['price_alert_fixed_reset'] * 1440 ) + $ct['dev']['tasks_time_offset'] ) == true
             	 ) {
               
             	 $ct['cache']->save_file($ct['base_dir'] . '/cache/alerts/fiat_price/'.$asset_data.'.dat', $alert_cache_contents); 
             
             	 // Comms data (for one alert message, including data on all resets per runtime)
             	 $ct['price_alert_fixed_reset_array'][strtolower($asset)][$asset_data] = $asset . ' / ' . strtoupper($pair) . ' @ ' . $exchange_text . ' (' . $change_symb . $percent_change_text . '%)';
             
             	 }
    
    
          }
       
       
      ////// Alert checking END //////////////
      }
      
      /////////////////////////////////////////////////////////////////
     
   
   // If we haven't returned false yet because of any issues being detected, return true to indicate all seems ok
   return true;
   
   
   }
   
   
   ////////////////////////////////////////////////////////
   ////////////////////////////////////////////////////////

      
   
} // Class END


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>