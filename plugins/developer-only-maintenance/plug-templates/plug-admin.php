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
?>
	

	<p class='blue blue_dotted'>
	
	PRO TIP: Click "Save Admin Changes" in the menu bar, to refresh this page, after changing app config / related data or logic.
	
	</p>
	
	
	
	
<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> Exchange / Pairing Information </legend>

<pre class='rounded'><code class='hide-x-scroll less' style='width: auto; height: auto;'>
<?php
	
	
$all_exchange_count = 0;
$active_exchange_count = 0;
$active_currency_count = 0;



foreach ( $ct['conf']['assets']['BTC']['pair'] as $key => $unused ) {
	
	// Detects better with side space included
	if ( stristr($supported_prim_currency_list, ' ' . $key . ' ') == false ) {
	$active_currency_count = $active_currency_count + 1;
	$supported_prim_currency_list .= ' ' . $key . ' /';
	}
	

}


$supported_prim_currency_list = ltrim($supported_prim_currency_list);

$pairs_count = $active_currency_count;
$all_supported_pairs_list = $supported_prim_currency_list;


foreach ( $ct['opt_conf']['crypto_pair'] as $key => $unused ) {
	
	// Detects better with side space included
	if ( stristr($all_supported_pairs_list, ' ' . $key . ' ') == false ) {
	$pairs_count = $pairs_count + 1;
	$all_supported_pairs_list .= ' ' . $key . ' /';
	}
	

}


$all_supported_pairs_list = ltrim($all_supported_pairs_list);

// Alphabetical sorting
$supported_prim_currency_list = $ct['var']->list_sort($supported_prim_currency_list, '/', 'sort', true);
$all_supported_pairs_list = $ct['var']->list_sort($all_supported_pairs_list, '/', 'sort', true);


foreach ( $ct['conf']['assets']['BTC']['pair'] as $pair_key => $unused ) {
	
     foreach ( $ct['conf']['assets']['BTC']['pair'][$pair_key] as $exchange_key => $unused ) {
     	
     	// Detects better with side space included
     	if ( !preg_match("/" . $exchange_key . " \//i", $active_btc_exchange_list) && stristr($exchange_key, 'bitmex_') == false ) { // Futures markets not allowed
     	$active_exchange_count = $active_exchange_count + 1;
     	$active_btc_exchange_list .= ' ' . $exchange_key . ' /';
     	}
     	
     
     }

}


$active_btc_exchange_list = ltrim($active_btc_exchange_list);

$active_exchange_count = $active_exchange_count;
$active_exchanges_list = $active_btc_exchange_list;


foreach ( $ct['conf']['assets'] as $asset_key => $unused ) {
	
     foreach ( $ct['conf']['assets'][$asset_key]['pair'] as $pair_key => $unused ) {
     	
     	foreach ( $ct['conf']['assets'][$asset_key]['pair'][$pair_key] as $exchange_key => $unused ) {
     	
               // Detects better with side space included
               if ( !preg_match("/" . $exchange_key . " \//i", $active_exchanges_list) && $exchange_key != 'misc_assets' && $exchange_key != 'btc_nfts' && $exchange_key != 'eth_nfts' && $exchange_key != 'sol_nfts' && $exchange_key != 'alt_nfts' ) {
               $active_exchange_count = $active_exchange_count + 1;
               $active_exchanges_list .= ' ' . $exchange_key . ' /';
               }
     	
     	}
     
     }

}


$active_exchanges_list = ltrim($active_exchanges_list);

// Alphabetical sorting
$active_btc_exchange_list = $ct['var']->list_sort($active_btc_exchange_list, '/', 'sort', true);
$active_exchanges_list = $ct['var']->list_sort($active_exchanges_list, '/', 'sort', true);


//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////




foreach ( $ct['api']->exchange_apis as $all_exchanges_key => $unused ) {

     if ( $all_exchanges_key == 'coingecko' ) {
     
          foreach( $ct['coingecko_currencies'] as $cg_currency ) {
          $all_exchange_count = $all_exchange_count + 1;
          $all_exchanges_list .= ' ' . $all_exchanges_key . '_' . $cg_currency . ' /';
          }
     
     }
     else {
     $all_exchange_count = $all_exchange_count + 1;
     $all_exchanges_list .= ' ' . $all_exchanges_key . ' /';
     }

}


$all_exchanges_list = ltrim($all_exchanges_list);

// Alphabetical sorting
$all_exchanges_list = $ct['var']->list_sort($all_exchanges_list, '/', 'sort', true);

//////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////////////////


echo 'bitcoin ACTIVE (markets in config) information: ' . "\n\n" . ' active_btc_prim_currencies_list['.$active_currency_count.']: ' . $supported_prim_currency_list . '; ' . "\n\n" . 'active_btc_exchanges_list['.$active_exchange_count.']: ' . $active_btc_exchange_list . "\n\n";
          	
echo "\n\n" . 'all ACTIVE (markets in config) information: ' . "\n\n" . ' active_pairs_list['.$pairs_count.']: ' . strtoupper($all_supported_pairs_list) . '; ' . "\n\n" . 'active_exchanges_list['.$active_exchange_count.']: ' . strtolower($active_exchanges_list) . "\n\n";
          	
echo "\n\n" . 'ALL supported exchanges information: ' . "\n\n" . 'all_supported_exchanges_list['.$all_exchange_count.']: ' . strtolower($all_exchanges_list);
     	

?>

</code></pre>

</fieldset>


<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> DEMO Data Checks </legend>

<pre class='rounded'><code class='hide-x-scroll less' style='width: auto; height: auto;'>
<?php
	
	
echo '============== C H A R T S  /  A L E R T S =================' . "\n\n";
	
	
foreach ( $ct['conf']['charts_alerts']['tracked_markets'] as $val ) {

$check_asset_params = array_map( "trim", explode("||", $val) );

// Remove any duplicate asset array key formatting, which allows multiple alerts per asset with different exchanges / trading pairs (keyed like SYMB, SYMB-1, SYMB-2, etc)
$check_asset = ( stristr($check_asset_params[0], "-") == false ? $check_asset_params[0] : substr( $check_asset_params[0], 0, mb_strpos($check_asset_params[0], "-", 0, 'utf-8') ) );
$check_asset = strtoupper($check_asset);

$check_market_id = $ct['conf']['assets'][$check_asset]['pair'][ $check_asset_params[2] ][ $check_asset_params[1] ];

// Consolidate function calls for runtime speed improvement
$charts_test_data = $ct['api']->market($check_asset, $check_asset_params[1], $check_market_id, $check_asset_params[2]);


	if ( isset($charts_test_data['last_trade']) && $ct['var']->num_to_str($charts_test_data['last_trade']) >= $ct['min_crypto_val_test'] ) {
	// DO NOTHING (IS SET / AT LEAST $ct['min_crypto_val_test'] IN VALUE)
	}
	// TEST FAILURE
	else {
	echo 'No chart / alert price data available: (conf_item='.$check_asset_params[0].',last_trade='.$ct['var']->num_to_str($charts_test_data['last_trade']).')' . "\n";
	}
	
	
	if ( isset($charts_test_data['24hr_prim_currency_vol']) && $ct['var']->num_to_str($charts_test_data['24hr_prim_currency_vol']) >= 1 ) {
	// DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
	}
	// TEST FAILURE
	else {
	echo 'No chart / alert trade volume data available: (conf_item='.$check_asset_params[0].',trade_volume='.$ct['var']->num_to_str($charts_test_data['24hr_prim_currency_vol']).')' . "\n";
	}


}
	
	
echo "\n" . '================ M O B I L E  T E X T  G A T E W A Y S ================' . "\n\n";
	
	
foreach ( $ct['conf']['mobile_network']['text_gateways'] as $val ) {
     
$gateway_data = array_map( "trim", explode("||", $val) );
	
$test_result = $ct['gen']->valid_email( 'test@' . $gateway_data[1] );

     if ( $test_result != 'valid' ) {
	echo 'email-to-mobile-text gateway ' . $gateway_data[1] . ' does not appear valid' . "\n";
	}

}
	
	
echo "\n" . '=============== A S S E T  M A R K E T S =============' . "\n\n";
	
	
foreach ( $ct['conf']['assets'] as $asset_key => $asset_val ) {


	foreach ( $asset_val['pair'] as $pair_key => $pair_val ) {
	
	
          foreach ( $pair_val as $key => $val ) {
          
          	if ( $key != 'misc_assets' && $key != 'btc_nfts' && $key != 'eth_nfts' && $key != 'sol_nfts' && $key != 'alt_nfts' ) {
          	
          	// Consolidate function calls for runtime speed improvement
          	$mrkts_test_data = $ct['api']->market( strtoupper($asset_key) , $key, $val, $pair_key);
          
          
                    if ( isset($mrkts_test_data['last_trade']) && $ct['var']->num_to_str($mrkts_test_data['last_trade']) >= $ct['min_crypto_val_test'] ) {
                           	// DO NOTHING (IS SET / AT LEAST $ct['min_crypto_val_test'] IN VALUE)
                    }
                    // TEST FAILURE
                    else {
               	echo 'No market price data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct['gen']->key_to_name($key) . "\n";
               	}
               	
               	
               	if ( isset($mrkts_test_data['24hr_prim_currency_vol']) && $ct['var']->num_to_str($mrkts_test_data['24hr_prim_currency_vol']) >= 1 ) {
                    // DO NOTHING (IS SET / AT LEAST 1 IN VALUE)
                    }
                    // TEST FAILURE
                    else {
               	echo 'No market volume data available for ' . strtoupper($asset_key) . ' / ' . strtoupper($pair_key) . ' @ ' . $ct['gen']->key_to_name($key) . "\n";
               	}
          
          	
          	}
          
          }

	
	}
	

}
	
	
echo "\n" . '=============== E N D  O F  C H E C K S =============';
	
	

?>

</code></pre>

</fieldset>


<!-- chart_bootstrapping START -->

<fieldset class='subsection_fieldset'>

<legend class='subsection_legend'> Price Chart Bootstrapping </legend>

<p>SKIPS system / all light charts, AND any charts for assets NOT in the DEFAULT config. This allows developers to easily make a publicly available download archive, for end-users to bootstrap (import) some price chart data into new installations.</p>

<?php

$backup_files = $ct['gen']->sort_files($ct['base_dir'] . '/cache/secured/backups', 'zip', 'desc');


if ( is_array($backup_files) && sizeof($backup_files) > 0 ) {

$backup_links = array();

     foreach( $backup_files as $back_file ) {
     
          if ( preg_match("/charts-bootstrapping/i", $back_file) ) {
          $backup_links['charts-bootstrapping'][] = $back_file;
          }
     
     }

     
     if ( is_array($backup_links['charts-bootstrapping']) ) {
     $backup_count_max = sizeof($backup_links['charts-bootstrapping']);
     }

}


?>	
               
               <?=$ct['gen']->table_pager_nav('chart_bootstrapping')?>
               
               <table id='chart_bootstrapping' border='0' cellpadding='10' cellspacing='0' class="data_table no_default_sort align_center" style='width: 100% !important;'>
                <thead>
                   <tr>
                    <th class="filter-match" data-placeholder="Filter Results">Chart Bootstrapping Backups</th>
                   </tr>
                 </thead>
                 
                <tbody>
                   
                   <?php
                   
                   if ( isset($backup_count_max) ) {
                        
                      $loop = 0;
                      while ( $loop < $backup_count_max ) {
                        
                   ?>
                   
                   <tr>
                   
                     <td><?=( isset($backup_links['charts-bootstrapping'][$loop]) ? '<a href="download.php?backup='. $backup_links['charts-bootstrapping'][$loop] . '" target="_BLANK">' . $backup_links['charts-bootstrapping'][$loop] . '</a>' : '' )?></td>
                   
                   </tr>
                   
                   <?php
                      
                      $loop = $loop + 1;
                      }
                      
                   }
                   else {
                   ?>
                   
                   <tr>
                   
                     <td class='bitcoin'>No backups yet, please check back later.</td>
                     <td class='bitcoin'></td>
                   
                   </tr>
                   
                   <?php
                   }
                   ?>

                </tbody>
                </table>

	
</fieldset>

<?php

// Render config settings for this plugin...


////////////////////////////////////////////////////////////////////////////////////////////////





////////////////////////////////////////////////////////////////////////////////////////////////


// What OTHER admin pages should be refreshed AFTER this settings update runs
// CAN ALSO BE 'none' OR 'all'...THE SECTION BEING RUN IS AUTO-EXCLUDED,
// (SEE 'all_admin_iframe_ids' [javascript array], for ALL possible values)
// SHOULD BE COMMA-DELIMITED: 'iframe_reset_backup_restore,iframe_apis'
//$ct['admin_render_settings']['is_refresh_admin'] = 'none';


////////////////////////////////////////////////////////////////////////////////////////////////


}
?>