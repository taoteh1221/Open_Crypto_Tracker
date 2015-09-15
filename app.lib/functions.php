<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 
//////////////////////////////////////////////////////////
 function cryptsy_market_api($markets_ids) {

  $cryptsy_server = 'pubapi.cryptsy.com';  // https://www.cryptsy.com/pages/publicapi
  $json_string = 'http://'.$cryptsy_server.'/api.php?method=singlemarketdata&marketid=' . $markets_ids;
  $jsondata = @file_get_contents($json_string);
   
   
  if ( !$jsondata ) {
  $cryptsy_server = 'pubapi1.cryptsy.com';  // https://www.cryptsy.com/pages/publicapi
  $json_string = 'http://'.$cryptsy_server.'/api.php?method=singlemarketdata&marketid=' . $markets_ids;
  $jsondata = @file_get_contents($json_string);
  }
  
  

  if ( !$jsondata ) {
  $cryptsy_server = 'pubapi2.cryptsy.com';  // https://www.cryptsy.com/pages/publicapi
  $json_string = 'http://'.$cryptsy_server.'/api.php?method=singlemarketdata&marketid=' . $markets_ids;
  $jsondata = @file_get_contents($json_string);
  }
  
  return $jsondata;

}
//////////////////////////////////////////////////////////
 
 
 
//////////////////////////////////////////////////////////
function get_btc_usd($btc_in_usd) {

  if ( !$_SESSION['btc_usd'] ) {

  
    if ( strtolower($btc_in_usd) == 'coinbase' ) {
  
    
  
    $json_string = 'https://coinbase.com/api/v1/prices/spot_rate?currency=USD';
    
    $jsondata = @file_get_contents($json_string);
    
    $data = json_decode($jsondata, TRUE);
    
    $_SESSION['btc_usd'] = $data['amount'];
    
    return $_SESSION['btc_usd'];
  
    }
  

  
  }
  else {
    
  return $_SESSION['btc_usd'];
  
  }
  

}
//////////////////////////////////////////////////////////


//////////////////////////////////////////////////////////
function get_trade_price($markets, $markets_ids) {
  

  if ( strtolower($markets) == 'cryptsy' ) {
  
  $jsondata = cryptsy_market_api($markets_ids);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($data);
  
	if (is_array($data) || is_object($data)) {
	 
	  foreach ($data as $coin_markets) {
	   
	    if (is_array($coin_markets) || is_object($coin_markets)) {
	     
		foreach($coin_markets as $markets) {
		 
		  if (is_array($markets) || is_object($markets)) {
		   
			foreach($markets as $attributes) {
	    
		    return $attributes["lasttradeprice"];
	    
		    /*
		     if (is_array($attributes["recenttrades"]) || is_object($attributes["recenttrades"])) {
			  foreach($attributes["recenttrades"] as $recenttrade) { 
			    //echo "<pre>";
			    //print_r($recenttrade);
			    //echo "</pre>";
			    echo "VALUES('{quantity: " . $recenttrade['quantity'] ."}', 'price: {" . $recenttrade['price'] . "}')";
			  }
		      }
	    
		    */
	    
			}
			
		  }
		  
		}
		
	    }
	    
	  }
	  
	}
  
  
  }


  if ( strtolower($markets) == 'bittrex' ) {
  
  $json_string = 'https://bittrex.com/api/v1.1/public/getticker?market=' . $markets_ids;
  
  $jsondata = @file_get_contents($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
	
	    foreach ($data as $key => $value) {
	      
	      //print_r($key);
	      
	      if ( $key == 'result' ) {
	       
	      return $data[$key]["Last"];
	       
	       
	      }
	    
    
	    }
	    
      }
  
  }

  if ( strtolower($markets) == 'poloniex' ) {


     if ( !$_SESSION['poloniex_markets'] ) {
     
     $json_string = 'https://poloniex.com/public?command=returnTicker';
     
     $jsondata = @file_get_contents($json_string);
     
     $data = json_decode($jsondata, TRUE);
     
     $_SESSION['poloniex_markets'] = $data;
   
     }
     else {
       
     $data = $_SESSION['poloniex_markets'];
     
     }
  
  
  
  //print_r($data);
      if (is_array($data) || is_object($data)) {
	
	    foreach ($data as $key => $value) {
	      
	      //print_r($key);
	      
	      if ( $key == $markets_ids ) {
	       
	      return $data[$key]["last"];
	       
	       
	      }
	    
    
	    }
	    
      }
  
  
  }


  if ( strtolower($markets) == 'kraken' ) {
  
  $json_string = 'https://api.kraken.com/0/public/Ticker?pair=' . $markets_ids;
  
  $jsondata = @file_get_contents($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  //print_r($json_string);print_r($data);
  
      if (is_array($data) || is_object($data)) {
	
	    foreach ($data as $key => $value) {
	      
	      //print_r($key);
	      
	      if ( $key == 'result' ) {
		
	      //print_r($data[$key]);
	      
		foreach ($data[$key] as $key2 => $value2) {
		  
		  //print_r($data[$key][$key2]);
		  
		  if ( $key2 == $markets_ids ) {
		   
		  return $data[$key][$key2]["c"][0];;
		   
		   
		  }
		
	
		}
	    
	      }
    
	    }
	    
      }
  
  
  }



  
}
//////////////////////////////////////////////////////////
 

//////////////////////////////////////////////////////////
function strip_price_formatting($price) {

$price = preg_replace("/ /", "", $price); // Space
$price = preg_replace("/,/", "", $price); // Comma
$price = preg_replace("/	/", "", $price); // Tab

return $price;

}
//////////////////////////////////////////////////////////





//////////////////////////////////////////////////////////
function coin_data($coin_name, $trade_symbol, $coin_amount, $markets, $markets_ids, $trade_pairing, $sort_order) {

global $_POST, $coins_array, $btc_in_usd;

$orig_markets = $markets;  // Save this for dynamic HTML form

$all_markets = $coins_array[$trade_symbol]['markets'];  // Get all markets for this coin

  // Update, get the selected market name
    // Only support for multiple markets per coin with BTC trade pairing
  if ( sizeof($all_markets) > 1 && $trade_pairing == 'btc' ) {
  $markets = $all_markets[$markets];
  }
  elseif ( $trade_pairing != 'btc' ) { // Only supporting Cryptsy non-BTC markets at this time
  $markets = 'cryptsy';
  }
  else {
  $markets = $all_markets[0];
  }




$markets_ids = $markets_ids[$markets];
  
  //var_dump($markets);
  //var_dump($markets_ids);
  
  
	if ( $coin_amount > 0.00000000 ) {
		
	  if ( !$_SESSION['td_color'] || $_SESSION['td_color'] == '#e8e8e8' ) {
	  $_SESSION['td_color'] = 'white';
	  }
	  else {
	  $_SESSION['td_color'] = '#e8e8e8';
	  }

	  if ( $trade_pairing == 'btc' ) {
	  $coin_to_trade = number_format( ( $markets_ids == 'btc' ? get_btc_usd($btc_in_usd) : get_trade_price($markets, $markets_ids) ), ( $markets_ids == 'btc' ? 2 : 8 ), '.', ',');
	  $coin_to_trade_worth = ($coin_amount * $coin_to_trade);
	  $coin_to_trade_worth2 = number_format($coin_to_trade_worth, ( $markets_ids == 'btc' ? 2 : 8 ), '.', ',');
	  $btc_worth = number_format( $coin_to_trade_worth, 8 );  
	  $_SESSION['btc_worth_array'][] = ( $markets_ids == 'btc' ? $coin_amount : $btc_worth );
	  $trade_pairing_description = ( $markets_ids == 'btc' ? 'US Dollar' : 'Bitcoin' );
	  $trade_pairing_symbol = ( $markets_ids == 'btc' ? 'USD' : 'BTC' );
	  }
	  else if ( $trade_pairing == 'ltc' ) {
	    
	  $coin_to_btc = get_trade_price($markets, 3);
	  
	  $coin_to_trade = number_format( get_trade_price($markets, $markets_ids), 8, '.', ',');
	  $coin_to_trade_worth = ($coin_amount * $coin_to_trade);
	  $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
	  $btc_worth = number_format( ($coin_to_trade_worth * $coin_to_btc), 8 );  // Convert ltc value to bitcoin
	  $_SESSION['btc_worth_array'][] = $btc_worth;  
	  $btc_trade_eq = number_format( ($coin_to_trade * $coin_to_btc), 8);
	  $trade_pairing_description = 'Litecoin';
	  $trade_pairing_symbol = 'LTC';
	  
	  //echo $ltc_to_btc . ' ' . $coin_to_trade . ' | | | ';
	  }
	  else if ( $trade_pairing == 'xrp' ) {
	    
	  $coin_to_btc = get_trade_price($markets, 454);
	  
	  $coin_to_trade = number_format( ( $markets_ids == 'xrp' ? $coin_to_btc : get_trade_price($markets, $markets_ids) ), 8, '.', ',');
	  $coin_to_trade_worth = ($coin_amount * $coin_to_trade);
	  $coin_to_trade_worth2 = number_format($coin_to_trade_worth, 8, '.', ',');
	  $btc_worth = number_format( ( $markets_ids == 'xrp' ? $coin_to_trade_worth : ($coin_to_trade_worth * $coin_to_btc) ), 8 );  // Convert xrp value to bitcoin
	  $_SESSION['btc_worth_array'][] = $btc_worth;
	  $btc_trade_eq = number_format( ( $markets_ids == 'xrp' ? $coin_to_btc : ($coin_to_trade * $coin_to_btc)), 8);
	  $trade_pairing_description = ( $markets_ids == 'xrp' ? 'Bitcoin' : 'Ripple' );
	  $trade_pairing_symbol = ( $markets_ids == 'xrp' ? 'BTC' : 'XRP' );
	  
	  //echo $xrp_to_btc . ' ' . $coin_to_trade_worth2;
	  }
	
	
	?>
<tr>

<td class='data border_lb'><span><?php echo $sort_order; ?></span></td>

<td class='data border_lb'>
<?php

  if ( $markets_ids == 'btc' ) {
  echo 'Coinbase';
  }
  else {
    
    // Only support for multiple markets per coin with BTC trade pairing
    if ( sizeof($all_markets) > 1 && $trade_pairing == 'btc' ) {
    ?>
    <select name='change_<?=strtolower($trade_symbol)?>_market' onchange='
    document.coin_amounts.<?=strtolower($trade_symbol)?>_market.value = this.value; document.coin_amounts.submit();
    '>
        <?php
        foreach ( $all_markets as $market_key => $market_name ) {
         // Avoid possible null equivelent issue by upping post value +1 in case zero
        ?>
        <option value='<?=($market_key + 1)?>' <?=( $orig_markets == $market_key ? ' selected ' : '' )?>> <?=ucfirst($market_name)?> </option>
        <?php
        }
        ?>
    </select>
    <?php
    }
    else {
    echo ucfirst($markets);
    }
    
  
  }

?></td>

<td class='data border_lb' align='right'><a href='http://coinmarketcap.com/currencies/<?php echo strtolower($coin_name); ?>/' target='_blank'><?php echo $coin_name; ?></a></td>

<td class='data border_b'><span><?php

  if ( $btc_trade_eq ) {
  echo ' ($'.number_format(( get_btc_usd($btc_in_usd) * $btc_trade_eq ), 8, '.', ',').' USD)';
  }
  elseif ( $markets_ids != 'btc' ) {
  echo ' ($'.number_format(( get_btc_usd($btc_in_usd) * $coin_to_trade ), 8, '.', ',').' USD)';
  }
  else {
  echo ' ($'.get_btc_usd($btc_in_usd).' USD)';
  }

?></span></td>

<td class='data border_lb' align='right'><?php echo number_format($coin_amount, 8, '.', ','); ?></td>

<td class='data border_b'><span><?php echo $trade_symbol; ?></span></td>

<td class='data border_lb' align='right'><?php echo $coin_to_trade; ?>

<?php

  if ( $trade_pairing != 'btc' && $markets_ids != 'xrp' ) {
  echo '<div class="btc_worth">(' . ( $btc_trade_eq > 0.00000000 ? $btc_trade_eq : '0.00000000' ) . ' Bitcoin)</div>';
  }
  
?>


</td>

<td class='data border_b'> <span>(<?=$trade_pairing_description?>)</span></span></td>

<td class='data border_lb'><?php
echo $coin_to_trade_worth2 . ' <span>' . $trade_pairing_symbol . '</span>';
  if ( $trade_pairing != 'btc' && $markets_ids != 'xrp' ) {
  echo '<div class="btc_worth">(' . $btc_worth . ' BTC)</div>';
  }

?></td>

<td class='data border_lrb'><?php

  if ( $trade_pairing == 'btc' ) {
  $coin_usd_worth = ( $markets_ids == 'btc' ? $coin_to_trade_worth2 : ($coin_to_trade_worth * get_btc_usd($btc_in_usd)) );
  }
  else if ( $trade_pairing == 'ltc' ) {
  $coin_usd_worth = ( ($coin_to_trade_worth * $coin_to_btc) * get_btc_usd($btc_in_usd));
  }
  else if ( $trade_pairing == 'xrp' ) {
  $coin_usd_worth = ( $markets_ids == 'xrp' ? ( $coin_to_trade_worth * get_btc_usd($btc_in_usd)) : ( ($coin_to_trade_worth * $coin_to_btc) * get_btc_usd($btc_in_usd)) );
  }
	

echo '$' . number_format($coin_usd_worth, 2, '.', ',');

?></td>

</tr>

<?php
	}

}
//////////////////////////////////////////////////////////




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



//////////////////////////////////////////////////////////
function eth_difficulty() {


  $json_string = 'https://www.etherchain.org/api/basic_stats';
  
  $jsondata = @get_data($json_string);
  
  $data = json_decode($jsondata, TRUE);
  
  var_dump($jsondata);
  
      if (is_array($data) || is_object($data)) {
	
	    foreach ($data as $key => $value) {
	      
	      //print_r($key);
	      
	      if ( $key == 'data' ) {
	       
	      return $data[$key]["difficulty"]["difficulty"];
	       
	       
	      }
	    
    
	    }
	    
      }
  
 
}
//////////////////////////////////////////////////////////

//////////////////////////////////////////////////////////
function get_data($url) {

$ch = curl_init();
$timeout = 5;

curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
curl_setopt($ch, CURLOPT_HEADER, TRUE);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
curl_setopt($ch, CURLOPT_USERAGENT,         'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_8_2) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/27.0.1453.116 Safari/537.36');


curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0); 
curl_setopt($ch, CURLOPT_TIMEOUT,        10);

$data = curl_exec($ch);


//var_dump($data);

curl_close($ch);
return $data;

}
//////////////////////////////////////////////////////////

?>
