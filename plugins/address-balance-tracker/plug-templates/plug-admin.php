<?php
/*
 * Copyright 2014-2023 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


if ( $admin_area_sec_level == 'high' ) {
?>
	
	<p class='bitcoin bitcoin_dotted'>
	
	YOU ARE IN HIGH SECURITY ADMIN MODE. <br /><br />Editing plugin settings is <i>done manually</i> IN HIGH SECURITY ADMIN MODE, by updating the file plug-conf.php (in this plugin's subdirectory: <?=$ct['base_dir']?>/plugins/<?=$this_plug?>) with a text editor.
	
	</p>

<?php
}
else {


// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['runtime_mode']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_location']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['ui_name']['is_readonly'] = 'Developer setting only';


////////////////////////////////////////////////////////////////////////////////////////////////

$loop = 0;
$loop_max = 72;
while ( $loop <= $loop_max ) {
     
$admin_render_settings['alerts_frequency_maximum']['is_select']['is_assoc'][] = array(
                                                                                      'key' => $loop,
                                                                                      'val' => ( $loop == 0 ? 'Unlimited' : 'Every ' . $loop . ' Hours'),
                                                                                     );
                                                                      
$loop = $loop + 1;
}


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['privacy_mode']['is_radio'] = array(
                                                           'off',
                                                           'on',
                                                          );


$admin_render_settings['privacy_mode']['is_notes'] = 'In Privacy Mode, the current asset balance in converted to it\'s ' . strtoupper($default_bitcoin_primary_currency_pair) . ' value.';


////////////////////////////////////////////////////////////////////////////////////////////////


$admin_render_settings['tracking']['is_notes'] = 'Track address balance changes on popular blockchains.';


$sol_subtokens = array();

// Get all the solana subtokens in asset config, which we can add as selection options
foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {

   // If we can get the primary currency value conversion (in privacy mode) for added assets that are solana SPL tokens,
   // then auto-add them to the asset selection dropdown menu
   if ( 
   array_key_exists('sol', $ct['conf']['assets'][$asset_key]['pair']) && isset($ct['conf']['assets'][$asset_key]['pair']['btc']) 
   || array_key_exists('sol', $ct['conf']['assets'][$asset_key]['pair']) && isset($ct['conf']['assets']['BTC']['pair'][ strtolower($asset_key) ]) 
   ) {
        
        if ( $asset_key != 'MISCASSETS' && $asset_key != 'BTCNFTS' && $asset_key != 'ETHNFTS' && $asset_key != 'SOLNFTS' && $asset_key != 'ALTNFTS' ) {
        $sol_subtokens[] = strtolower($asset_key);
        }
        
   }

}


// EMPTY add / remove (repeatable) fields TEMPLATE rendering

$admin_render_settings['tracking']['is_repeatable']['add_button'] = 'Add Address To Track (at bottom)';


$admin_render_settings['tracking']['is_repeatable']['is_select']['asset'] = array(
                                                                                  'btc',
                                                                                  'eth',
                                                                                  'sol',
                                                                                 );
          
foreach ( $sol_subtokens as $val ) {
$admin_render_settings['tracking']['is_repeatable']['is_select']['asset'][] = 'sol||' . $val;
}


// Sort alphabetically
sort($admin_render_settings['tracking']['is_repeatable']['is_select']['asset']);


$admin_render_settings['tracking']['is_repeatable']['is_text']['label'] = true;
$admin_render_settings['tracking']['is_repeatable']['is_text']['address'] = true;
$admin_render_settings['tracking']['is_repeatable']['text_field_size'] = 50;
               

// FILLED IN setting values

// Alphabetically sort tracked addresses by 'label' key
if ( is_array($ct['conf']['plug_conf'][$this_plug]['tracking']) ) { 
$usort_alpha = 'label';
$usort_tracking_results = usort($ct['conf']['plug_conf'][$this_plug]['tracking'], array($ct['gen'], 'usort_alpha') );
}


if ( !$usort_tracking_results ) {
$ct['gen']->log('other_error', 'plugin "' . $this_plug . '" tracking addresses failed to sort alphabetically');
}


foreach ( $ct['conf']['plug_conf'][$this_plug]['tracking'] as $key => $val ) {
         
     foreach ( $val as $tracked_key => $tracked_val ) {
     
          if ( $tracked_key === 'asset' ) {
               
          $admin_render_settings['tracking']['has_subarray'][$key]['is_select'][$tracked_key] = array(
                                                                                                      'btc',
                                                                                                      'eth',
                                                                                                      'sol',
                                                                                                     );
          
               foreach ( $sol_subtokens as $val ) {
               $admin_render_settings['tracking']['has_subarray'][$key]['is_select'][$tracked_key][] = 'sol||' . $val;
               }
          
          // Sort alphabetically
          sort($admin_render_settings['tracking']['has_subarray'][$key]['is_select'][$tracked_key]);
          
          }
          else {                                               
          $admin_render_settings['tracking']['has_subarray'][$key]['is_text'][$tracked_key] = true;
          $admin_render_settings['tracking']['has_subarray'][$key]['text_field_size'] = 50;
          }

     }
                                                                      
}


////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// (SEE $refresh_admin / $_GET['refresh'] in footer.php, for ALL possible values)
$admin_render_settings['is_refresh_admin'] = 'none';

// $ct['admin']->admin_config_interface($conf_id, $interface_id)
$ct['admin']->admin_config_interface('plug_conf|' . $this_plug, $this_plug, $admin_render_settings);


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>	