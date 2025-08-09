<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
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


$ct['admin_render_settings']['tracking']['is_notes'] = 'Track address balance changes, on popular blockchains.<br /><br /><span style="font-weight: bold;" class="yellow">Solana SPL tokens MUST:</span><br /><span class="light_sea_green"><b>1)</b> Have EITHER a SOL-based Jupiter Aggregator or USD-based CoinGecko Terminal SOLANA market in it\'s asset config, to AUTOMATICALLY be included in the SOLANA SPL ASSETS LISTS below (as a selection option, for the "Asset" setting)<br /><br /><b>2)</b> Use your wallet\'s TOKEN account / address, that\'s specifically associated with your wallet for this asset (NOT your primary wallet address, as Solana SPL tokens DERIVE *secondary addresses* for each token in your wallet)</span>';

$supported_native_tokens = array(
                                 'BTC',
                                 'ETH',
                                 'SOL',
                                 );

$native_tokens = array();

$sol_subtokens = array();

// Get all the solana subtokens in asset config, WHICH HAVE A SOL PAIRING,
// OR USD PAIRING WITH A SOLANA-BASED COIN COINGECKO TERMINAL IN IT
// (for privacy mode / primary currency value)
foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
     
$pair_btc_val = null; // Reset
$is_spl_token = false; // Reset
     
     
   if ( !in_array($asset_key, $ct['dev']['special_assets']) ) {
   
        
        // If it's a supported NATIVE token
        if ( in_array($asset_key, $supported_native_tokens) ) {
        
        $pair_btc_val = $ct['asset']->pair_btc_val( strtolower($asset_key) );

        
            if ( $pair_btc_val != null ) {
            $native_tokens[strtolower($asset_key)] = true; // Overwrites duplicates automatically
            }
            
            
        }

   
        // If it's a Solana SPL token
        foreach ( $ct['conf']['assets'][$asset_key]['pair'] as $pair_key => $pair_val ) {
           
           
           // PHP7.4 NEEDS === HERE INSTEAD OF ==
           if (
           $pair_key === 'sol' && isset($ct['conf']['assets'][$asset_key]['pair'][$pair_key]['jupiter_ag'])
           || $pair_key === 'usd' && isset($ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal'])
           && preg_match("/sol|solana\|\|/i", $ct['conf']['assets'][$asset_key]['pair'][$pair_key]['coingecko_terminal']) 
           ) { 
           $sol_subtokens[strtolower($asset_key)] = true; // Overwrites duplicates automatically
           $is_spl_token = true;
           }

        
        }

   
   }


}


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$ct['admin_render_settings']['tracking']['is_repeatable']['add_button'] = 'Add Address To Track (at bottom)';


foreach ( $native_tokens as $key => $unused ) {
$ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset'][] = $key;
}


foreach ( $sol_subtokens as $key => $unused ) {
$ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset'][] = 'sol||' . $key;
}


// Sort alphabetically
sort($ct['admin_render_settings']['tracking']['is_repeatable']['is_select']['asset']);


$ct['admin_render_settings']['tracking']['is_repeatable']['is_text']['label'] = true;
$ct['admin_render_settings']['tracking']['is_repeatable']['is_text']['crypto_address'] = true;
$ct['admin_render_settings']['tracking']['is_repeatable']['text_field_size'] = 50;
               

// FILLED IN setting values


if ( sizeof($ct['conf']['plug_conf'][$this_plug]['tracking']) > 0 ) {

$ct['sort_by_nested'] = 'root=>label';
$usort_tracking_results = usort($ct['conf']['plug_conf'][$this_plug]['tracking'], array($ct['var'], 'usort_asc') );
$ct['sort_by_nested'] = false; // RESET


     if ( !$usort_tracking_results ) {
     $ct['gen']->log('other_error', 'plugin "' . $this_plug . '" tracking addresses failed to sort alphabetically');
     }


     foreach ( $ct['conf']['plug_conf'][$this_plug]['tracking'] as $key => $val ) {
              
              
          foreach ( $val as $tracked_key => $tracked_val ) {
          
          
               if ( $tracked_key === 'asset' ) { // PHP7.4 NEEDS === HERE INSTEAD OF ==
                    

                    foreach ( $native_tokens as $key2 => $unused ) {
                    $ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_select'][$tracked_key][] = $key2;
                    }
                    
               
                    foreach ( $sol_subtokens as $key2 => $unused ) {
                    $ct['admin_render_settings']['tracking']['has_subarray'][$key]['is_select'][$tracked_key][] = 'sol||' . $key2;
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


     foreach ( $native_tokens as $key => $unused ) {
     $ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset'][] = $key;
     }
     
               
     foreach ( $sol_subtokens as $key => $unused ) {
     $ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset'][] = 'sol||' . $key;
     }
               
               
// Sort alphabetically
sort($ct['admin_render_settings']['tracking']['has_subarray'][0]['is_select']['asset']);


$ct['admin_render_settings']['tracking']['has_subarray'][0]['is_text']['label'] = true;
$ct['admin_render_settings']['tracking']['has_subarray'][0]['is_text']['crypto_address'] = true;
$ct['admin_render_settings']['tracking']['has_subarray'][0]['text_field_size'] = 50;
               
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
$ct['admin_render_settings']['is_refresh_admin'] = 'none';


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	