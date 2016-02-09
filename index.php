<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */

 // Start measuring page load time
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$start = $time;

require("config.php");

require("templates/default/header.php");

?>


		<ul class='tabs'>
			<li><a href='#tab1'>Your Coin Values</a></li>
			<li><a href='#tab2'>Update Coin Amounts</a></li>
			<li><a href='#tab3'>Program Settings</a></li>
			<li><a href='#tab4'>Mining Calculators</a></li>
			<li><a href='#tab5'>Help</a></li>
		</ul>
		<div id='tab1' class='tabdiv'>
			<h3 style='display: inline;'>Your Coin Values</h3> &nbsp; &nbsp; <a href='javascript:location.reload(true);' style='font-weight: bold;'>Reload Values</a> &nbsp; <select name='select_auto_refresh' id='select_auto_refresh' onchange='auto_reload(this.value);'>
				<option value=''> Manually </option>
				<option value='60' <?=( $_COOKIE['coin_reload'] == '60' ? 'selected' : '' )?>> Every Minute </option>
				<option value='120' <?=( $_COOKIE['coin_reload'] == '120' ? 'selected' : '' )?>> Every 2 Minutes </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> Every 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> Every 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> Every 15 Minutes </option>
			</select> &nbsp; <span id='reload_countdown' style='color: red;'></span>
			<p>
                            
                            
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
	       
	       foreach ( $_POST as $key => $value ) {
	      
		  if ( preg_match("/_amount/i", $key) ) {
		  
		  $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $key));
		  
		// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
		  coin_data($coins_array[$coin_symbol]['coin_name'], $coin_symbol, $value, ($_POST[strtolower($coin_symbol).'_market'] - 1), $coins_array[$coin_symbol]['markets_ids'], $coins_array[$coin_symbol]['trade_pair'], $sort_order);
		  
		  
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
	   
	   $market_000 = $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'];
	   
	   $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'] = $single_coin_cookie_array[1];
	   
	   //var_dump($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market']);
	   //var_dump($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount']);
	   
		// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
	   coin_data($coins_array[$coin_symbol]['coin_name'], $coin_symbol, $all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_amount'], ($all_cookies_data_array[$coin_symbol.'_data'][$coin_symbol.'_market'] -1), $coins_array[$coin_symbol]['markets_ids'], $coins_array[$coin_symbol]['trade_pair'], $sort_order);
	   
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
echo 'Total USD Value: $' . $total_usd_worth2 . ' (1 Bitcoin is currently worth $' .get_btc_usd($btc_in_usd). ' at Coinbase)</p>';

// End outputting results
}
?>
                            
                            
                        </p>
		</div>
		<div id='tab2' class='tabdiv'>
			<h3>Update Coin Amounts</h3>
			<p><?php require("form.php"); ?></p>
		</div>
		<div id='tab3' class='tabdiv'>
			<h3>Program Settings</h3>
			
                        <p>
                        Save coin values as cookie data <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
                        if ( this.checked == true ) {
                        document.coin_amounts.use_cookies.value = 1;
                        }
                        else {
                        document.coin_amounts.use_cookies.value = "";
                        }
                        ' <?php echo ( $_COOKIE['coin_amounts'] && $_POST['submit_check'] != 1 || $_POST['use_cookies'] == 1 ? ' checked="checked"' : ''); ?> />
                        </p>
                        <input type='button' value='Update Settings' onclick='document.coin_amounts.submit();' />
                        
		</div>
		<div id='tab4' class='tabdiv'>
			<h3>Mining Calculators</h3>
			
			<fieldset class='calculators'>
				<legend style='color: blue;'> Ethereum Mining Calculator </legend>
		    
				<?php
				
				echo '<p>Block height: ' . number_format(hexdec(etherscan_api('number'))) . '</p>';
				echo '<p>Gas limit: ' . number_format(hexdec(etherscan_api('gasLimit'))) . '</p>';
				
				
				if ( $_POST['eth_submitted'] ) {
				    
				$_POST['eth_difficulty'] = str_replace("    ", '', $_POST['eth_difficulty']);
				$_POST['eth_difficulty'] = str_replace(" ", '', $_POST['eth_difficulty']);
				$_POST['eth_difficulty'] = str_replace(",", '', $_POST['eth_difficulty']);
				    
				$scale = ( trim($_POST['eth_difficulty']) / trim($_POST['eth_measure']) );
				
				$time = ( $scale / trim($_POST['eth_hashrate']) );
				
				$hours = ( $time / 3600 );
				
				$days = ( $hours / 24 );
				
				$months = ( $days / 30 );
				
				$years = ( $days / 365 );
				    
				    //echo '<p>'.$scale;
				    //echo '<p>'.$time;
				?>
				<p style='color: green;'>
				<?php
				    if ( $hours < 24 ) {
				    ?>
				    Hours until block found: 
				    <?php
				    echo round($hours, 2);
				    }
				
				    elseif ( $days < 30 ) {
				    ?>
				    Days until block found: 
				    <?php
				    echo round($days, 2);
				    }
				
				    elseif ( $days < 365 ) {
				    ?>
				    Months until block found: 
				    <?php
				    echo round($months, 2);
				    }
				    
				    else {
				    ?>
				    Years until block found: 
				    <?php
				    echo round($years, 2);
				    }
				
				$caculate_daily = ( 24 / $hours );
				$daily_average = ( $caculate_daily * ( get_trade_price('poloniex', 'BTC_ETH') * 5 ) );
				?>
				<br />
				<br />
				Current Ethereum Value Per Coin: 
				<?php
				echo round(get_trade_price('poloniex', 'BTC_ETH'), 8) . ' BTC ($' . round(( round(get_trade_price('poloniex', 'BTC_ETH'), 8) * get_btc_usd($btc_in_usd) ), 8) . ' USD)';
				?>
				<br />
				<br />
				Average ETH Earned Daily (block reward only): 
				<?php
				echo round(( round($daily_average, 8) / get_trade_price('poloniex', 'BTC_ETH') ), 8) . ' ETH';
				?>
				<br />
				<br />
				Average BTC Value Earned Daily: 
				<?php
				echo round($daily_average, 8) . ' BTC ($' . round(( round($daily_average, 8) * get_btc_usd($btc_in_usd) ), 2) . ' USD)';
				}
				?>
				</p>
				<form name='eth' action='index.php#tab4' method='post'>
				    
				    <input type='hidden' value='1' name='eth_submitted' />
				
				<p>Difficulty: <input type='text' value='<?=( $_POST['eth_difficulty'] ? number_format($_POST['eth_difficulty']) : number_format(hexdec(etherscan_api('difficulty'))) )?>' name='eth_difficulty' /> (uses <a href='https://etherscan.io/apis/' target='_blank'>etherscan.io/apis</a>)</p>
				
				
				<p>Your Hashrate: <input type='text' value='<?=$_POST['eth_hashrate']?>' name='eth_hashrate' />
				
				<select name='eth_measure'>
				<option value='1000000' <?=( $_POST['eth_measure'] == '1000000' ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['eth_measure'] == '1000' ? 'selected' : '' )?>> Khs </option>
				</select>
				</p>
				
				<input type='submit' value='Calculate ETH Mining Profit' />
				
				</form>
				
				
			</fieldset>
			
		</div>
		<div id='tab5' class='tabdiv'>
			<h3>Help</h3>
			<p>If you reconfigure the config file settings, reload / refresh the page before updating any coin values, or the submission form may not be configured properly and may not submit data correctly. Also, you may need to uncheck "Save coin values as cookie data" on the Program Settings page temporarily to clear out old cookie data that may conflict with the new configuration...then you can re-enable cookies again afterwards.</p>
		</div>





<p align='center'>
	
	<a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases' target='_blank'>Version <?=$version?></a>
	
	<br /><br />Donations are welcome to support further development...
	<br /><br />BTC: 1FfWHekHPLH7hQcU4d5MBVQ4WekJiA8Mk2
	<br /><br />ETH: 0xf3da0858c3cfcc28a75c1232957a7fb190d7e5e9

</p>
<?php
require("templates/default/footer.php");

// Calculate page load time
$time = microtime();
$time = explode(' ', $time);
$time = $time[1] + $time[0];
$finish = $time;
$total_time = round(($finish - $start), 4);
echo '<p align="center"> Page generated in '.$total_time.' seconds. </p>';

?>

