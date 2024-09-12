<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( $ct['admin_area_sec_level'] == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor. You can change the security level in the "Security" section.
	
	</p>

<?php
}
else {


// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////

     
$ct['admin_render_settings']['alerts_frequency_maximum']['is_range'] = true;

$ct['admin_render_settings']['alerts_frequency_maximum']['range_ui_meta_data'] .= 'zero_is_unlimited;';

$ct['admin_render_settings']['alerts_frequency_maximum']['range_min'] = 0;

$ct['admin_render_settings']['alerts_frequency_maximum']['range_max'] = 72;

$ct['admin_render_settings']['alerts_frequency_maximum']['range_step'] = 1;

$ct['admin_render_settings']['alerts_frequency_maximum']['range_ui_prefix'] = 'Every ';

$ct['admin_render_settings']['alerts_frequency_maximum']['range_ui_suffix'] = ' Hours';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['privacy_mode']['is_radio'] = array(
                                                           'off',
                                                           'on',
                                                          );


$ct['admin_render_settings']['privacy_mode']['is_notes'] = 'In Privacy Mode, the current asset balance is converted to it\'s ' . strtoupper($ct['default_bitcoin_primary_currency_pair']) . ' value.';


////////////////////////////////////////////////////////////////////////////////////////////////


$ct['admin_render_settings']['tracking']['is_notes'] = 'Track address balance changes, on popular blockchains.<br /><br /><span style="font-weight: bold;" class="yellow">Solana SPL tokens MUST:</span><br /><span class="light_sea_green"><b>1)</b> Have EITHER a Jupiter Aggregator OR CoinGecko Terminal Solana market in it\'s asset config, to AUTOMATICALLY be designated as such / included in the SPL ASSETS LIST below (as a selection option, for the "Asset" setting)<br /><br /><b>2)</b> Have EITHER a Bitcoin (BTC) OR Solana (SOL) market in it\'s asset config, for "Privacy Mode" to work properly<br /><br /><b>3)</b> Use your wallet\'s TOKEN account / address, that\'s specifically associated with this asset (NOT your primary wallet address, as Solana SPL tokens DERIVE *secondary addresses* for each token in your wallet)</span>';


$sol_subtokens = array();

// Get all the solana subtokens in asset config, WHICH HAVE A SOL PAIRING WITH JUPITER AGGREGATOR IN IT
// (for privacy mode / primary currency value)
foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
     
   foreach ( $ct['conf']['assets'][$asset_key]['pair'] as $pair_key => $pair_val ) {
      
      // PHP7.4 NEEDS === HERE INSTEAD OF ==
      if (
      $pair_key === 'sol' && isset($ct['conf']['assets'][$asset_key]['pair'][$pair_key]['jupiter_ag'])
      || $pair_key === 'usd' && isset($ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal'])
      && preg_match("/sol\|\|/i", $ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal']) 
      || $pair_key === 'usd' && isset($ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal'])
      && preg_match("/solana\|\|/i", $ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal']) 
      ) { 
      $sol_subtokens[] = strtolower($asset_key);
      }
   
   }

}


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['tracking']['is_repeatable']['add_button'] = 'Add Address To Track (at bottom)';


$ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset'] = array(
                                                                                  'btc',
                                                                                  'eth',
                                                                                  'sol',
                                                                                 );
          
foreach ( $sol_subtokens as $val ) {
$ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset'][] = 'sol||' . $val;
}


// Sort alphabetically
sort($ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset']);


$ct['admin_render_settings']['tracking']['is_repeatable']['is_text']['label'] = true;
$ct['admin_render_settings']['tracking']['is_repeatable']['is_text']['address'] = true;
$ct['admin_render_settings']['tracking']['is_repeatable']['text_field_size'] = 50;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['plug_conf'][$this_plug]['tracking']) > 0 ) {

$ct['usort_alpha'] = 'label';
$usort_tracking_results = usort($ct['conf']['plug_conf'][$this_plug]['tracking'], array($ct['gen'], 'usort_alpha') );


     if ( !$usort_tracking_results ) {
     $ct['gen']->log('other_error', 'plugin "' . $this_plug . '" tracking addresses failed to sort alphabetically');
     }


     foreach ( $ct['conf']['plug_conf'][$this_plug]['tracking'] as $key => $val ) {
              
              
          foreach ( $val as $tracked_key => $tracked_val ) {
          
          
               if ( $tracked_key === 'asset' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                    
               $ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_select'][$tracked_key] = array(
                                                                                                           'btc',
                                                                                                           'eth',
                                                                                                           'sol',
                                                                                                          );
               
               
                    foreach ( $sol_subtokens as $val ) {
                    $ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_select'][$tracked_key][] = 'sol||' . $val;
                    }
               
               
               // Sort alphabetically
               sort($ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_select'][$tracked_key]);
               
               }
               else {                                               
               $ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_text'][$tracked_key] = true;
               $ct['admin_render_settings']['tracking']['has_subarray'][$key]['text_field_size'] = 50;
               }
               
     
          }
          
                                                                           
     }


}
else {

$ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset'] = array(
                                                                                    'btc',
                                                                                    'eth',
                                                                                    'sol',
                                                                                   );
               
               
     foreach ( $sol_subtokens as $val ) {
     $ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset'][] = 'sol||' . $val;
     }
               
               
// Sort alphabetically
sort($ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset']);


$ct['admin_render_settings']['tracking']['has_subarray'][0]['is_text']['label'] = true;
$ct['admin_render_settings']['tracking']['has_subarray'][0]['is_text']['address'] = true;
$ct['admin_render_settings']['tracking']['has_subarray'][0]['text_field_size'] = 50;
               
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
$ct['admin_render_settings']['is_refresh_admin'] = 'none';


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	