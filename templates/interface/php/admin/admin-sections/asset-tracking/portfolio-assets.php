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
	
	
     	<b class='blue'>Search for a ticker, a ticker/pair, a CoinGecko "APP ID", OR a CoinGecko "Terminal" DeFi Pool:</b><br /><br />
     	
     	<span class='bitcoin'>(example searches: BTC, SOL/ETH, dogecoin, OR solana||FgTCR1ufcaTZMwZZYhNRhJm2K3HgMA8V8kXtdqyttm19 [pool addresses ARE CASE-SENSITIVE!])</span><br /><br />
     	
     	<input type='text' id='add_markets_search' value='' style='width: 100%;' /> &nbsp; 
     	
     	<button style='margin: 1em;' class='force_button_style' onclick='
     	
     	var add_markets_search = { "add_markets_search": $("#add_markets_search").val() };
     	
     	ct_ajax_load("type=add_markets&step=1", "#add_markets_ajax", "markets search results", add_markets_search, true); // Secured
     	
     	'> Search For Markets To Add </button>

	
	</div>
    
	
<?php
}
?>	