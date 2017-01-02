
                            
                            
<?php
// Start outputting results
if ( $_POST['submit_check'] == 1 || $_COOKIE['coin_amounts'] ) {
?>


<table border='0' cellpadding='10' cellspacing='0' id="coins_table">
 <thead>
    <tr>
<th class='border_lt'> Sort Order</th>
<th class='border_lt'> Marketplace</th>
<th class='border_lt' align='right'> Coin Name</th>
<th class='border_t'> (USD Value)</th>
<th class='border_lt' align='right'> Coin Amount</th>
<th class='border_t'> Symbol</th>
<th class='border_lt' align='right'> Trade Value</th>
<th class='border_t'> (for)</th>
<th class='border_lt'> Total Trade Value</th>
<th class='border_lrt'> Total USD Value</th>
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
		  $chosen_market = ($_POST[strtolower($coin_symbol).'_market'] - 1);
		  			

		// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
		  coin_data($coins_array[$coin_symbol]['coin_name'], $coin_symbol, $value, $chosen_market, $coins_array[$coin_symbol]['market_ids'], $coins_array[$coin_symbol]['trade_pair'], $sort_order);
		  
		  
		  }
	       
	       $sort_order = $sort_order + 1;
	       }
	
	}

}
elseif ( $_COOKIE['coin_amounts'] && $_COOKIE['coin_markets'] ) {

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


 

$all_coins_cookie_array = explode("#", $_COOKIE['coin_amounts']);

	if (is_array($all_coins_cookie_array) || is_object($all_coins_cookie_array)) {
		
	   foreach ( $all_coins_cookie_array as $coin ) {
	       
	   $single_coin_cookie_array = explode("-", $coin);
	   
	   $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_cookie_array[0]));
	   $chosen_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
			
	     if ( $coin_symbol == 'BTC' && !$btc_market ) {
	     $btc_market = ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1);
	     }


	   $market_000 = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'];
	   
	   $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'] = $single_coin_cookie_array[1];
	   
	   //var_dump($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market']);
	   //var_dump($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount']);
	   
		// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
	   coin_data($coins_array[$coin_symbol]['coin_name'], $coin_symbol, $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'], $chosen_market, $coins_array[$coin_symbol]['market_ids'], $coins_array[$coin_symbol]['trade_pair'], $sort_order);
	   
	   $sort_order = $sort_order + 1;

	   }
	   
	}
	
}
?>
</tbody>
</table>


<?php

$total_btc_worth = bitcoin_total();
$total_btc_worth2 = number_format($total_btc_worth, 8, '.', ',');

$total_usd_worth = ($total_btc_worth * get_btc_usd($btc_in_usd));
$total_usd_worth2 = number_format($total_usd_worth, 2, '.', ',');

echo '<p class="bold_1">Total Bitcoin Value: ' . $total_btc_worth2 . '<br />';

$coins_array_numbered = array_values($coins_array['BTC']['market_ids']);

foreach ( $coins_array['BTC']['market_ids'] as $key => $value ) {
$loop = $loop + 1;

	if ( $value == $coins_array_numbered[$btc_market] ) {
	echo 'Total USD Value: $' . $total_usd_worth2 . ' (1 Bitcoin is currently worth $' .number_format( get_btc_usd($btc_in_usd), 2, '.', ','). ' at '.ucfirst($key).')</p>';
	}

}
$loop = NULL;

// End outputting results
}
?>
                            
                            
                        