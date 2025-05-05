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


// Render config settings for this section...


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['bitcoin_primary_currency_pair']['is_confirm'] = 'This will PERMANENTLY DELETE any ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' *CONVERSION* price charts (' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' *BASE-PAIRED [MARKET]* price charts WILL *NOT* BE DELETED). Do you still wish to select a new primary currency pair?';

foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $unused ) {
$ct['admin_render_settings']['bitcoin_primary_currency_pair']['is_select'][] = $pair_key;
}

$ct['admin_render_settings']['bitcoin_primary_currency_pair']['is_notes'] = 'MUST BE AVAILABLE ON THE CHOSEN "Bitcoin Primary Currency Exchange" BELOW.';


////////////////////////////////////////////////////////////////////////////////////////////////


// List all exchanges that have a Bitcoin market
foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $unused ) {
			
	foreach ( $ct['conf']['assets']['BTC']['pair'][$pair_key] as $exchange_key => $unused ) {
					
		// Detects better with side space included
		if ( stristr($supported_btc_exchange_scan, ' ' . $exchange_key . ' ') == false && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
          $ct['admin_render_settings']['bitcoin_primary_currency_exchange']['is_select'][] = $exchange_key;
		$supported_btc_exchange_scan .= ' ' . $exchange_key . ' /';
		}
				
	}
	
sort($ct['admin_render_settings']['bitcoin_primary_currency_exchange']['is_select']);
				
}


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['currency_decimals_max']['is_range'] = true;

$ct['admin_render_settings']['currency_decimals_max']['range_min'] = 5;

$ct['admin_render_settings']['currency_decimals_max']['range_max'] = 10;

$ct['admin_render_settings']['currency_decimals_max']['range_step'] = 1;

$ct['admin_render_settings']['currency_decimals_max']['is_notes'] = 'Sets the minimum-allowed CURRENCY value, adjust with care!';


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['crypto_decimals_max']['is_range'] = true;

$ct['admin_render_settings']['crypto_decimals_max']['range_min'] = 10;

$ct['admin_render_settings']['crypto_decimals_max']['range_max'] = 15;

$ct['admin_render_settings']['crypto_decimals_max']['range_step'] = 1;

$ct['admin_render_settings']['crypto_decimals_max']['is_notes'] = 'Sets the minimum-allowed CRYPTO value, adjust with care!';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['price_rounding_percent']['is_select'] = array(
                                                                    'one',
                                                                    'tenth',
                                                                    'hundredth',
                                                                    'thousandth',
                                                                   );


$ct['admin_render_settings']['price_rounding_percent']['is_notes'] = 'Example: one = 100, tenth = 100.9, hundredth = 100.09, thousandth = 100.009';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['price_rounding_fixed_decimals']['is_radio'] = array(
                                                                         'off',
                                                                         'on',
                                                                        );


$ct['admin_render_settings']['price_rounding_fixed_decimals']['is_notes'] = '(whether to keep trailing decimal zeros, or remove them)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['coingecko_pairings_search']['is_text'] = true;

$ct['admin_render_settings']['coingecko_pairings_search']['text_field_size'] = 40;

$ct['admin_render_settings']['coingecko_pairings_search']['is_notes'] = 'CoinGecko market pairings searched for, when adding new assets / coins (comma-separated)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['jupiter_ag_pairings_search']['is_text'] = true;

$ct['admin_render_settings']['jupiter_ag_pairings_search']['text_field_size'] = 40;

$ct['admin_render_settings']['jupiter_ag_pairings_search']['is_notes'] = 'Jupiter Aggregator market pairing tokens searched for, when adding new assets / coins (comma-separated, CASE-SENSITIVE)<br /><span class="red">NOTES: We have a HARD CAP OF 100 asset search results maximum PER-PAIRING (to avoid search timeouts [taking too long]), AND each pairing token MUST be VERIFIED (for your SAFETY!).</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['upbit_pairings_search']['is_text'] = true;

$ct['admin_render_settings']['upbit_pairings_search']['text_field_size'] = 40;

$ct['admin_render_settings']['upbit_pairings_search']['is_notes'] = 'Upbit market pairings searched for, when adding new assets / coins (comma-separated)';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['additional_pairings_search']['is_text'] = true;

$ct['admin_render_settings']['additional_pairings_search']['text_field_size'] = 40;

$ct['admin_render_settings']['additional_pairings_search']['is_notes'] = 'OTHER (upcoming / semi-popular) market pairings searched for, when adding new assets / coins (comma-separated)<br /><span class="red">BE CAREFUL, AND ONLY ADD FIAT / STABLECOINS / ***MAJOR*** BLUECHIPS HERE, OR YOU RISK MESSING UP "ADD MARKETS" SEARCH RESULTS!</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['token_presales_usd']['is_repeatable']['add_button'] = 'Add Presale Token (at bottom)';

$ct['admin_render_settings']['token_presales_usd']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['token_presales_usd']['is_repeatable']['text_field_size'] = 20;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['token_presales_usd']) > 0 ) {

     foreach ( $ct['conf']['currency']['token_presales_usd'] as $key => $val ) {
     $ct['admin_render_settings']['token_presales_usd']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['token_presales_usd']['is_subarray'][$key]['text_field_size'] = 20;
     }

}
else {
$ct['admin_render_settings']['token_presales_usd']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['token_presales_usd']['is_subarray'][0]['text_field_size'] = 20;
}


$ct['admin_render_settings']['token_presales_usd']['is_notes'] = 'Static values in USD for token presales, like during crowdsale / VC funding periods etc (before exchange listings)<br />This format MUST be used:<br />
TICKER = 1.23<br /><span class="red">RAW NUMBERS ONLY (NO THOUSANDTHS FORMATTING)</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['conversion_currency_symbols']['is_repeatable']['add_button'] = 'Add Currency (at bottom)';

$ct['admin_render_settings']['conversion_currency_symbols']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['conversion_currency_symbols']['is_repeatable']['text_field_size'] = 15;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['conversion_currency_symbols']) > 0 ) {

     foreach ( $ct['conf']['currency']['conversion_currency_symbols'] as $key => $val ) {
     $ct['admin_render_settings']['conversion_currency_symbols']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['conversion_currency_symbols']['is_subarray'][$key]['text_field_size'] = 15;
     }

}
else {
$ct['admin_render_settings']['conversion_currency_symbols']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['conversion_currency_symbols']['is_subarray'][0]['text_field_size'] = 15;
}


$ct['admin_render_settings']['conversion_currency_symbols']['is_notes'] = 'Add different currency\'s CORRESPONDING SYMBOLS here (country fiat, stablecoin, or secondary crypto)<br /><br />This format MUST be used:<br />
TICKER = SYMBOL<br /><span class="red"><br />IMPORTANT NOTES:<br />Use NATIVE tickers instead of INTERNATIONAL (RMB, not CNY...NIS, not ILS, etc etc), as automation during addition of new markets in the app defaults to this.<br /><br />If currency symbols added here do NOT have a BITCOIN MARKET added, THEY WILL NOT BE USED (WILL BE SAFELY IGNORED) BY THE APP! This setting MERELY ADDS CURRENCY SYMBOLS for currencies ALREADY ADDED TO THE BITCOIN MARKETS.</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_repeatable']['add_button'] = 'Add Preferred Bitcoin Market (at bottom)';

$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_repeatable']['text_field_size'] = 20;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['bitcoin_preferred_currency_markets']) > 0 ) {

     foreach ( $ct['conf']['currency']['bitcoin_preferred_currency_markets'] as $key => $val ) {
     $ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_subarray'][$key]['text_field_size'] = 20;
     }

}
else {
$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_subarray'][0]['text_field_size'] = 20;
}


$ct['admin_render_settings']['bitcoin_preferred_currency_markets']['is_notes'] = 'Set which Bitcoin markets you PREFER for each currency<br />This format MUST be used:<br />
TICKER = EXCHANGE_NAME<br /><span class="red">IMPORTANT NOTE: If coins added here do NOT already have the corresponding EXCHANGES in the "Portfolio Assets => Bitcoin" section, THESE PREFERRED MARKETS WILL SAFELY *NOT* BE USED BY THE APP!</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['crypto_pair']['is_repeatable']['add_button'] = 'Add Crypto (at bottom)';

$ct['admin_render_settings']['crypto_pair']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['crypto_pair']['is_repeatable']['text_field_size'] = 20;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['crypto_pair']) > 0 ) {

     foreach ( $ct['conf']['currency']['crypto_pair'] as $key => $val ) {
     $ct['admin_render_settings']['crypto_pair']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['crypto_pair']['is_subarray'][$key]['text_field_size'] = 20;
     }

}
else {
$ct['admin_render_settings']['crypto_pair']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['crypto_pair']['is_subarray'][0]['text_field_size'] = 20;
}


$ct['admin_render_settings']['crypto_pair']['is_notes'] = 'Auto-activate support for ALTCOIN PAIRED MARKETS (like COIN/sol or COIN/eth, etc...markets where the base pair is an altcoin)<br />This format MUST be used:<br />
TICKER = SYMBOL<br /><span class="red">IMPORTANT NOTE: If coins added here do NOT already have BITCOIN-PAIRED MARKETS in their "Portfolio Assets" section, THESE CRYPTO PAIRS CAN *NOT* BE USED BY THE APP!</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_repeatable']['add_button'] = 'Add Preferred Crypto Market (at bottom)';

$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_repeatable']['text_field_size'] = 20;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['crypto_pair_preferred_markets']) > 0 ) {

     foreach ( $ct['conf']['currency']['crypto_pair_preferred_markets'] as $key => $val ) {
     $ct['admin_render_settings']['crypto_pair_preferred_markets']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['crypto_pair_preferred_markets']['is_subarray'][$key]['text_field_size'] = 20;
     }

}
else {
$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_subarray'][0]['text_field_size'] = 20;
}


$ct['admin_render_settings']['crypto_pair_preferred_markets']['is_notes'] = 'Preferred ALTCOIN PAIRED MARKETS market(s) for getting a certain crypto\'s value<br />This format MUST be used:<br />
TICKER = EXCHANGE_NAME<br /><span class="red">IMPORTANT NOTE: If coins added here do NOT already have the corresponding EXCHANGES *WITHIN BITCOIN-PAIRED MARKETS* in their "Portfolio Assets" section, THESE PREFERRED MARKETS CAN *NOT* BE USED BY THE APP!</span>';


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// (SHOULD BE COMMA-SEPARATED [NO SPACES] FOR MULTIPLE VALUES)
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('currency', 'currency_support', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	