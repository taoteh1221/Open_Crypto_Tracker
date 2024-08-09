<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->parse_pairing() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->parse_pairing(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
?>

	     <?php
	     if ( $no_results ) {
	     ?>
	     <p class='red input_margins' style='font-weight: bold;'>NO RESULTS FOUND, PLEASE TRY A DIFFERENT SEARCH.</p>
	     <?php
	     }
	     ?>

          <h3 class='bitcoin input_margins'>STEP #2: Search Available Asset Markets</h3>
          

	     <ul>
	     
          	<li class='blue'>
          	
          	<b>Search ALL EXCHANGES (that have MULTIPLE markets search capability), for a TICKER or TICKER / PAIRING.</b><br />
          	<span class='bitcoin'>(example[s]: BTC, ETH, SOL, BONK, WEN, BTC/USD, ETH/EUR, ETH/BTC, SOL/GBP, SOL/ETH)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search a SPECIFIC EXCHANGE, for a SPECIFIC MARKET ID (BOTH MUST be an EXACT MATCH).</b><br />
          	<span class='bitcoin'>(example[s]: XBTUSD at Bitmex, tBTCUSD at Bitfinex, ETHDAI at Binance, SOL-USD at Coinbase)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search CoinGecko.com for an "APP ID" (found on the coin's CoinGecko page).</b><br />
          	<span class='bitcoin'>(example[s]: bitcoin, ethereum, solana, dogecoin, render-token)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search "CoinGecko.com Terminal" for a DeFi Pool.</b><br />
          	<span class='bitcoin'>(example[s]: ethereum||0xb7ecb2aa52aa64a717180e030241bc75cd946726, solana||FgTCR1ufcaTZMwZZYhNRhJm2K3HgMA8V8kXtdqyttm19 [pool addresses ARE CASE-SENSITIVE!])</span>
          	
          	</li>
     	
     	</ul>
	
     	
     	<select class='input_margins' id='add_markets_search_exchange'>
     	
     	<option value='all_exchanges'> ALL Exchanges </option>
     	
     	<option value='presale_usd_value'> Token Presales (in 'Currency Support' section) </option>
     	
     	<?php
     	foreach ( $ct['api']->exchange_apis as $exchange_key => $unused ) {
     	?>
     	<option value='<?=$exchange_key?>'> <?=$ct['gen']->key_to_name($exchange_key)?> </option>
     	<?php
     	}
     	?>
     	
     	</select><br />
     	
     	
     	<input class='input_margins' type='text' id='add_markets_search' name='add_markets_search' value='<?=$_POST['add_markets_search']?>' style='width: calc(100% - 2em);' /> &nbsp; 
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	var add_markets_search = {
     	                          "add_markets_search": $("#add_markets_search").val(),
     	                          "add_markets_search_exchange": $("#add_markets_search_exchange").val(),
     	                          };
     	
     	ct_ajax_load("type=add_markets&step=3", "#update_markets_ajax", "market search results", add_markets_search, true); // Secured
     	
     	'> Search For Markets To Add </button>

	