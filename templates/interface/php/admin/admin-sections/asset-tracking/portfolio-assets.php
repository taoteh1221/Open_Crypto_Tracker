<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>


<?php
if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing most admin config settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file config.php (in this app's main directory: <?=$ct['base_dir']?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {
?>
	
	<div id='add_markets_ajax' style='margin: 1em;'>
	     
	     <ul>
	     
          	<li class='blue'>
          	
          	<b>Search ALL EXCHANGES (that have MULTIPLE markets search capability), for a TICKER or TICKER / PAIRING.</b><br />
          	<span class='bitcoin'>(example[s]: BTC, ETH, SOL, BONK, WEN, BTC/USD, ETH/EUR, ETH/BTC, SOL/GBP, SOL/ETH)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search a SPECIFIC EXCHANGE, for a SPECIFIC MARKET ID (MUST be an EXACT MATCH).</b><br />
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
     	
     	<?php
     	foreach ( $ct['api']->exchange_apis as $exchange_key => $unused ) {
     	?>
     	<option value='<?=$exchange_key?>'> <?=$ct['gen']->key_to_name($exchange_key)?> </option>
     	<?php
     	}
     	?>
     	
     	</select><br />
     	
     	
     	<input class='input_margins' type='text' id='add_markets_search' value='' style='width: calc(100% - 2em);' /> &nbsp; 
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	var add_markets_search = {
     	                          "add_markets_search": $("#add_markets_search").val(),
     	                          "add_markets_search_exchange": $("#add_markets_search_exchange").val(),
     	                          };
     	
     	ct_ajax_load("type=add_markets&step=1", "#add_markets_ajax", "markets search results", add_markets_search, true); // Secured
     	
     	'> Search For Markets To Add </button>

	
	</div>
    
	
<?php
}
?>	