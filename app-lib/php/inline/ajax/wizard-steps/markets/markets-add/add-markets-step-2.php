<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");

   /*
If the 'add asset market' search result does NOT return a PAIRING VALUE, WE LOG THIS AS AN ERROR IN $ct['api']->market_id_parse() WITH DETAILS, AND ****DO NOT DISPLAY IT**** AS A RESULT TO THE ****END USER INTERFACE****. We DO NOT want to COMPLETELY block it from the 'under the hood' results array output, BECAUSE WE NEED TO KNOW FROM ERROR DETECTION / LOGS WHAT WE NEED TO PATCH / FIX IN $ct['api']->market_id_parse(), TO PROPERLY PARSE THE PAIRING FOR THIS PARTICULAR SEARCH / FUNCTION CALL.
   */
   
?>

          <h3 class='bitcoin input_margins'>STEP #2: Search Available Asset Markets</h3>
          
	     <?php
	     if ( $no_results ) {
	     ?>
	     <p class='red red_dotted input_margins' style='font-weight: bold; padding: 12px;'>
	     
	     NO RESULTS FOUND, PLEASE TRY A DIFFERENT SEARCH.<br /><br />
	     
	     NOTE: ANY EXCHANGE MARKETS **THAT ALREADY EXIST IN THIS APP** ARE NEVER DISPLAYED IN SEARCH RESULTS HERE.
	     
	     </p>
	     <?php
	     }
	     ?>

	     <ul>
	     
          	<li class='blue'>
          	
          	<b>SIMILAR SEARCH RESULTS ARE INCLUDED <span class='red'>(bonk/usd search results will also include babybonk/usdt, etc etc)</span>.</b>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search ALL EXCHANGES <span class='red'>(that have MULTIPLE markets search capability)</span>, for a TICKER, or TICKER / PAIRING.</b><br />
          	<span class='bitcoin'>(example[s]: BTC, ETH, SOL, BONK, WEN, BTC/USD, ETH/EUR, ETH/BTC, SOL/GBP, SOL/ETH)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search a SPECIFIC EXCHANGE, for a SPECIFIC MARKET ID <span class='red'>(MARKET ID MUST BE AN **EXACT** MATCH)</span>.</b><br />
          	<span class='bitcoin'>(example[s]: XBTUSD at Bitmex, tBTCUSD at Bitfinex, ETHDAI at Binance, SOL-USD at Coinbase)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search CoinGecko.com for an "APP ID", or "APP ID" / PAIRING <span class='red'>("APP ID" is found on the asset's CoinGecko page)</span>.</b><br />
          	<span class='blue'>(adding AT LEAST ONE CoinGecko market automatically adds a link to the asset's CoinGecko page)</span><br />
          	<span class='bitcoin'>(example[s]: bitcoin, ethereum, solana, bitcoin/usd, ethereum/eur, solana/hkd)</span>
          	
          	</li>
	     
          	<li class='blue'>
          	
          	<b>Search "CoinGecko.com Terminal" for a DeFi Pool.</b><br />
          	<span class='bitcoin'>(example[s]: ethereum||0xb7ecb2aa52aa64a717180e030241bc75cd946726, solana||FgTCR1ufcaTZMwZZYhNRhJm2K3HgMA8V8kXtdqyttm19 <span class='red'>[pool addresses ARE CASE-SENSITIVE!]</span>)</span>
          	
          	</li>
     	
     	</ul>
	
     	
     	<p class='input_margins'><select id='add_markets_search_exchange' name='add_markets_search_exchange'>
     	
     	<option value='all_exchanges'> ALL Exchanges </option>
     	
     	<option value='presale_usd_value' <?=( isset($_POST['add_markets_search_exchange']) && $_POST['add_markets_search_exchange'] == 'presale_usd_value' ? 'selected' : '' )?> > Token Presales (in 'Currency Support' section) </option>
     	
     	<?php
     	foreach ( $ct['api']->exchange_apis as $exchange_key => $unused ) {
     	?>
     	<option value='<?=$exchange_key?>' <?=( isset($_POST['add_markets_search_exchange']) && $_POST['add_markets_search_exchange'] == $exchange_key ? 'selected' : '' )?> > <?=$ct['gen']->key_to_name($exchange_key)?> </option>
     	<?php
     	}
     	?>
     	
     	</select></p>
     	
     	
     	<?php
     	// IF free alphavantage tier, show an OPTIONAL checkbox to EXCLUDE
     	// searching alphavantage, to avoid going over free tier limits on live data retrieval
     	if ( $ct['conf']['ext_apis']['alphavantage_per_minute_limit'] <= 5 ) {
     	?>
     	
     	<p class='input_margins'>
     	
     	<input type='checkbox' id='skip_alphavantage_search' name='skip_alphavantage_search' value='yes' <?=( !isset($_POST['skip_alphavantage_search']) || isset($_POST['skip_alphavantage_search']) && $_POST['skip_alphavantage_search'] == 'yes' ? 'checked' : '' )?> /> 
     	
     	<span class='bitcoin'><b><i><u>WHEN SEARCHING 'ALL Exchanges'</u></i></b>, SKIP Using Up Alphavantage.co Stock Price DAILY Live Requests For Data</span> 
	     
		<img class='tooltip_style_control' id='skip_alphavantage_info' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative; left: -5px;' /><br />
		
		</p>
     	
     	<p class='input_margins red_dotted'>
     	
		<b class='red'>NOTE ABOUT ***STOCK MARKET*** ASSETS: ALREADY-ADDED ***AND*** SEARCH-RESULT ASSET MARKETS THAT ARE ***STOCK MARKET*** ASSETS ARE GIVEN A SUFFIX "STOCK" APPENDED TO THE STOCK TICKER VALUE, ***TO FLAG THE ASSET AS A STOCK WITHIN THIS APP*** (EG: IBM = IBMSTOCK).</b>
		
		</p>
		
		
	 <script>
		
			var skip_alphavantage_info = '<h5 class="align_center bitcoin tooltip_title">SKIP Alphavantage During "ALL Exchanges" Search</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">DAILY live data requests are VERY LIMITED for the FREE tier of Alphavantage.co\'s Stock Market Prices API. IF you know you are NOT searching for a STOCK MARKET asset, leave this box checked, TO AVOID USING UP your Alphavantage DAILY limits.</p>'
			
			
			+'<p> </p>';

	
			$('#skip_alphavantage_info').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: skip_alphavantage_info,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
     	<?php
     	}
     	

$all_exchanges_count = 0;
$all_exchanges_search_count = 0;

foreach ( $ct['api']->exchange_apis as $check_exchange ) {
     
$all_exchanges_count = $all_exchanges_count + 1;

     if ( $check_exchange['all_markets_support'] || $check_exchange['search_endpoint'] ) {
     $all_exchanges_search_count = $all_exchanges_search_count + 1;
     }

}
     	
     	?>
     	
     	
     	<p class='input_margins' style='width: calc(100% - 2em);'><input type='text' id='add_markets_search' name='add_markets_search' value='<?=$_POST['add_markets_search']?>' style='width: 100%;' /> </p>
     	
     	<button class='force_button_style input_margins' onclick='
     	
     	
     	    if ( $("#skip_alphavantage_search").length && $("#skip_alphavantage_search").is(":checked") ) {
     	    var skip_alphavantage_search = "yes";
     	    var exchange_count = ( Number("<?=$all_exchanges_search_count?>") - 1 );
     	    }
     	    else {
     	    var skip_alphavantage_search = "no";
     	    var exchange_count = Number("<?=$all_exchanges_search_count?>");
     	    }
     	    
     	    
     	    if ( $("#add_markets_search_exchange").val() == "all_exchanges" ) {
     	    var search_desc = exchange_count + " exchanges (of <?=$all_exchanges_count?>), please wait";
     	    }
     	    else {
     	    var search_desc = $("#add_markets_search_exchange").val();
     	    }
     	    
     	
     	var add_markets_search = {
     	                          "add_markets_search": $("#add_markets_search").val(),
     	                          "add_markets_search_exchange": $("#add_markets_search_exchange").val(),
     	                          "skip_alphavantage_search": skip_alphavantage_search,
     	                          };
     	
     	ct_ajax_load("type=add_markets&step=3", "#update_markets_ajax", "results from " + search_desc, add_markets_search, true); // Secured
     	
     	'> Search For Markets To Add </button>



	