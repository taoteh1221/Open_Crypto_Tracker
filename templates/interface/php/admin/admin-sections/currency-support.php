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


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['bitcoin_currency_markets']['is_repeatable']['add_button'] = 'Add Currency (at bottom)';

$ct['admin_render_settings']['bitcoin_currency_markets']['is_repeatable']['is_text'] = true; // SINGLE (NON array)
$ct['admin_render_settings']['bitcoin_currency_markets']['is_repeatable']['text_field_size'] = 15;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['currency']['bitcoin_currency_markets']) > 0 ) {

     foreach ( $ct['conf']['currency']['bitcoin_currency_markets'] as $key => $val ) {
     $ct['admin_render_settings']['bitcoin_currency_markets']['is_subarray'][$key]['is_text'] = true;
     $ct['admin_render_settings']['bitcoin_currency_markets']['is_subarray'][$key]['text_field_size'] = 15;
     }

}
else {
$ct['admin_render_settings']['bitcoin_currency_markets']['is_subarray'][0]['is_text'] = true;
$ct['admin_render_settings']['bitcoin_currency_markets']['is_subarray'][0]['text_field_size'] = 15;
}


$ct['admin_render_settings']['bitcoin_currency_markets']['is_notes'] = 'Add different currencies here (country fiat, stablecoin, or secondary crypto)<br />This format MUST be used:<br />
TICKER = SYMBOL<br /><span class="red">IMPORTANT NOTE: If currencies added here do NOT have a BITCOIN MARKET added, THEY WILL NOT BE USED BY THE APP!</span>';


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
TICKER = EXCHANGE_NAME<br /><span class="red">IMPORTANT NOTE: If coins added here do NOT already have the corresponding EXCHANGES in the "Portfolio Assets => Bitcoin" section, THESE PREFERRED MARKETS CAN *NOT* BE USED BY THE APP!</span>';


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
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'all';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('currency', 'currency', $ct['admin_render_settings']);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	