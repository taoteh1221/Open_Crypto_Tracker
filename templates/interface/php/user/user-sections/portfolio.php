<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$pref_number_format = ( isset($_COOKIE['pref_number_format']) ? $_COOKIE['pref_number_format'] : 'automatic' );

?>

	
<h2 class='bitcoin page_title'>My Portfolio</h2>


<div class='full_width_wrapper align_center'>
			
			
	   <div class='align_left' style='margin-top: 0.5em; margin-bottom: 1em;'>
	
	
	         <div style='display: inline;'><?=$ct['gen']->start_page_html('portfolio')?></div>
			
			&nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>App Reload:</span> <select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc), or inactive tabs.' class='browser-default custom-select' name='select_auto_refresh' id='select_auto_refresh' onchange='
			 reload_time = this.value;
			 auto_reload();
			 '>
				<option value='0'> Manually </option>
				<option value='300' <?=( $_COOKIE['coin_reload'] == '300' ? 'selected' : '' )?>> 5 Minutes </option>
				<option value='600' <?=( $_COOKIE['coin_reload'] == '600' ? 'selected' : '' )?>> 10 Minutes </option>
				<option value='900' <?=( $_COOKIE['coin_reload'] == '900' ? 'selected' : '' )?>> 15 Minutes </option>
				<option value='1800' <?=( $_COOKIE['coin_reload'] == '1800' ? 'selected' : '' )?>> 30 Minutes </option>
			</select> 
			
			&nbsp; <span id='reload_notice' class='red'></span>		
		
		
	   </div>
					
                            
<?php
// Start outputting results
if ( $_POST['submit_check'] == 1 || $post_csv_import || $ui_cookies ) {
?>


<table border='0' cellpadding='0' cellspacing='0' id="coins_table" class="data_table align_center" style='margin-top: 10px !important;'>
 <thead>
    <tr>
         <th class='border_lt border_rt align_left' colspan="11">
    
			
			 &nbsp; <span class='blue' style='font-weight: bold;'>View:</span> <select title='Select which portfolio view format you prefer.' class='browser-default custom-select' name='select_portfolio_view' id='select_portfolio_view' onchange='
			 
			 if ( this.value = "tall" ) {
			 alert("Comin Soon&trade;");
			 $(this).val("wide");   
			 }
			 
			 '>
				<option value='wide'> Wide </option>
				<option value='tall'> Tall </option>
			</select> 
			
         
         &nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>Numbers:</span> <select class='browser-default custom-select narrow_dropdown' id='pref_number_format' name='pref_number_format' onchange="
	
	if ( !get_cookie('pref_number_format') ) {
	number_format_cookie = confirm('This feature REQUIRES using cookie data. This ONLY changes the number format of your portfolio summaries. To change the currency, see the \'User Settings => Primary Currency Market\' setting.');
	}
	else {
	number_format_cookie = true;
	}
			
	if ( number_format_cookie == true ) {
	set_cookie('pref_number_format', this.value, 365);
	$('#coin_amnts').submit();
	}
	else {
     $(this).val(pref_number_format);
     return false;
	}
	
	">
    <option value='automatic'> Automatic </option>	
	<!-- Locales by country: https://www.localeplanet.com/icu/iso3166.html  -->
	<?php
	foreach ( $ct['country_locales'] as $locale_key => $locale_val ) {
	?>
    <option value='<?=$locale_key?>' <?=( $pref_number_format == $locale_key ? 'selected' : '' )?> > <?=$locale_val?> </option>	
     <?php
	}
	?>
    </select> 
    
    
			<?php
			if ( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
				
				if ( $ct['sel_opt']['alert_percent'][4] == 'visual_only' ) {
				$visual_audio_alerts = 'Visual';
				}
				elseif ( $ct['sel_opt']['alert_percent'][4] == 'visual_audio' ) {
				$visual_audio_alerts = 'Visual / Audio';
				}
				
				$text_mcap_trend = $ct['sel_opt']['alert_percent'][3];
				
				$text_mcap_trend = ucwords(preg_replace("/hour/i", " hour", $text_mcap_trend));
				
				$text_mcap_trend = ucwords(preg_replace("/day/i", " day", $text_mcap_trend));
				
				
				if ( $ct['sel_opt']['alert_percent'][2] == 'gain' ) {
				$alert_filter = '<span>+</span>';
				$alert_filter_css = 'green';
				}
				elseif ( $ct['sel_opt']['alert_percent'][2] == 'loss' ) {
				$alert_filter = '<span>-</span>';
				$alert_filter_css = 'orange';
				}
				elseif ( $ct['sel_opt']['alert_percent'][2] == 'both' ) {
				$alert_filter = '<img src="templates/interface/media/images/plus-minus.png" height="13" alt="" style="position: relative; vertical-align:middle; bottom: 2px;" />';
				$alert_filter_css = 'blue';
				}
				
				
			?>
			
			
			&nbsp; &nbsp; <span class='<?=$alert_filter_css?>' style='font-weight: bold;'><?=$visual_audio_alerts?> Alerts: (<?=ucfirst($ct['conf']['gen']['primary_marketcap_site'])?> <?=$text_mcap_trend?> <?=$alert_filter?><?=$ct['sel_opt']['alert_percent'][1]?>%)</span>
			
			<?php
			}
			?>  
         
         
         </th>
    </tr>
    <tr>
<th class='border_lb num-sort'>Rank</th>
<th class='border_lb blue al_right'><span>Asset Name</span></th>
<th class='border_b num-sort'>Unit Value</th>
<th class='border_lb num-sort al_right'>Trade Value</th>
<th class='border_b blue'>Market</th>
<th class='border_b blue'>Exchange</th>
<th class='border_b num-sort'>24hr Volume</th>
<th class='border_lb blue num-sort al_right'>Holdings</th>
<th class='border_b'>Ticker</th>
<th class='border_b blue num-sort'>Holdings Value</th>
<th class='border_rb blue num-sort'>(in <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>)</th>
    </tr>
  </thead>
 <tbody>


<?php

	if ( $_POST['submit_check'] == 1 ) {
	
		
		if ( is_array($_POST) ) {
		    
		    
    	        // (we go by array index number here, rather than 1 or higher for html form values)
    		    if ( $_POST['btc_mrkt'] > 0 ) {
    		    $btc_mrkt = ($_POST['btc_mrkt'] - 1);
    		    }
    		    else {
    		    $btc_mrkt = 0;
    		    }
		
              
              foreach ( $_POST as $key => $val ) {
								
               	if ( preg_match("/_amnt/i", $key) ) {
               	
               	$held_amnt = $ct['var']->rem_num_format($val);
               	$asset_symb = strtoupper(preg_replace("/_amnt/i", "", $key));
               	$sel_pair = ($_POST[strtolower($asset_symb).'_pair']);
               	
               	   // Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
    	                                   // (we go by array index number here, rather than 1 or higher for html form values)
               	   if ( $_POST[strtolower($asset_symb).'_mrkt'] > 0 ) {
               	   $sel_mrkt = ($_POST[strtolower($asset_symb).'_mrkt'] - 1); 
               	   }
               	   else {
               	   $sel_mrkt = 0;
               	   }
               	
               	$purchase_price = $ct['var']->rem_num_format($_POST[strtolower($asset_symb).'_paid']);
               	$lvrg_level = $_POST[strtolower($asset_symb).'_lvrg'];
               	$sel_mrgntyp = $_POST[strtolower($asset_symb).'_mrgntyp'];
               			
						
               	// Render the row of coin data in the UI
               	$ct['asset']->ui_asset_row($ct['conf']['assets'][$asset_symb]['name'], $asset_symb, $held_amnt, $ct['conf']['assets'][$asset_symb]['pair'][$sel_pair], $sel_pair, $sel_mrkt, $purchase_price, $lvrg_level, $sel_mrgntyp);
               	
               	
               	
               		if ( $held_amnt >= $min_crypto_val_test ) {
               			
               		$assets_added = 1;
               		
               		
               			if ( $purchase_price >= $min_fiat_val_test ) {
               			$purchase_price_added = 1;
               			}
               			
               			if ( $lvrg_level >= 2 ) {
               			$lvrg_added = 1;
               			}
               			
               			if ( $lvrg_level >= 2 && $sel_mrgntyp == 'short' ) {
               			$short_added = 1;
               			}
               		
               		
               		}
               		elseif ( $held_amnt > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
               		$assets_watched = 1;
               		}
               	
               		if ( $held_amnt > 0.00000000 ) {
               		$ct['asset_tracking'][] = $asset_symb; // For only showing chosen assets in chart stats etc
               		}
               	
               	}
               
               
              }						
              
		
		}
	
	}
	elseif ( $post_csv_import ) {
	
		
		if ( is_array($csv_file_array) ) {
			
									
				foreach( $csv_file_array as $key => $val ) {
	        
	        		
	        			if ( $ct['var']->rem_num_format($val[1]) > 0.00000000 ) {  // Show even if decimal is off the map, just for UX purposes tracking token price only
	        			
	        			$val[5] = ( $ct['var']->whole_int( trim($val[5]) ) != false ? trim($val[5]) : 1 ); // If market ID input is corrupt, default to 1
	        			$val[3] = ( $ct['var']->whole_int( trim($val[3]) ) != false ? trim($val[3]) : 0 ); // If leverage amount input is corrupt, default to 0
	        			
	        			$held_amnt = $ct['var']->rem_num_format( trim($val[1]) );
	        			$asset_symb = strtoupper( trim($val[0]) );
	        			$sel_pair = strtolower( trim($val[6]) );
	        			// Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
    	                                // (we go by array index number here, rather than 1 or higher for html form values)
	        			$sel_mrkt = ( $val[5] > 0 ? $val[5] - 1 : 0 ); 
	        			$purchase_price = $ct['var']->rem_num_format($val[2]);
	        			$lvrg_level = $val[3];
	        			$sel_mrgntyp = $val[4];
	        			
	        				
	        				// Check pair value
	        				foreach ( $ct['conf']['assets'][$asset_symb]['pair'] as $pair_key => $unused ) {
					 	
					 	$ploop = 0;
					 						
					 			// Use first pair key from coins config for this asset, if no pair value was set properly in the spreadsheet
					 			if ( $ploop == 0 ) {
					 								
					 					if ( $sel_pair == null || !$ct['conf']['assets'][$asset_symb]['pair'][$sel_pair] ) {
					 					$sel_pair = $pair_key;
					 					}
					 							
					 			}
	        				
	        				$ploop = $ploop + 1;
	        				
	        				}
	        				
	        				
	        				// Check margin type value
	        				if ( $sel_mrgntyp != 'long' && $sel_mrgntyp != 'short' ) {
	        				$sel_mrgntyp = 'long';
	        				}
	        				
						
						
	        			// Render the row of coin data in the UI
	        			$ct['asset']->ui_asset_row($ct['conf']['assets'][$asset_symb]['name'], $asset_symb, $held_amnt, $ct['conf']['assets'][$asset_symb]['pair'][$sel_pair], $sel_pair, $sel_mrkt, $purchase_price, $lvrg_level, $sel_mrgntyp);
	        			
	        			
	        			
	        				if ( $held_amnt >= $min_crypto_val_test ) {
	        					
	        				$assets_added = 1;
	        				
	        				
	        					if ( $purchase_price >= $min_fiat_val_test ) {
	        					$purchase_price_added = 1;
	        					}
	        					
	        					if ( $lvrg_level >= 2 ) {
	        					$lvrg_added = 1;
	        					}
	        					
	        					if ( $lvrg_level >= 2 && $sel_mrgntyp == 'short' ) {
	        					$short_added = 1;
	        					}
	        				
	        				
	        				}
	        				elseif ( $held_amnt > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
	        				$assets_watched = 1;
	        				}
	        			
	        				if ( $held_amnt > 0.00000000 ) {
	        				$ct['asset_tracking'][] = $asset_symb; // For only showing chosen assets in chart stats etc
	        				}
	        				
										
	       		 	}
									
									
				}
		
		}
	
	}
	elseif ( $ui_cookies ) {
	
	//var_dump($all_cookies_data_array);
	
	   foreach ( $all_cookies_data_array as $key => $unused ) {
        	       
       $purchase_price_temp = null;
	       
	   $asset_symb = substr($key, 0, strpos($key, "_"));
    		
    	// Bundle all required cookie data in this final cookies parsing loop for each coin, and render the coin's data
    	// We don't need $ct['var']->rem_num_format() for cookie data, because it was already done creating the cookies
    	$held_temp = $ct['var']->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_amnt']);
    					
          if ( $held_temp >= $watch_only_flag_val ) {
                
        	$held_amnt = $held_temp;
        	
        	   // Avoided possible null equivelent issue by upping post value +1 in case zero, so -1 here
    	       // (we go by array index number here, rather than 1 or higher for html form values)
        	   if ( $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] > 0) {
        	   $sel_mrkt = ($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'] -1);
        	   }
        	   else {
        	   $sel_mrkt = 0;
        	   }
        	
        	$sel_pair = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_pair'];
    					       
    		$purchase_price_temp = $ct['var']->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_paid']);
    			
    			// If purchased amount (not just watched), AND cost basis
        		if (
        		$purchase_price_temp >= 0.00000001
        		&& $held_amnt >= $min_crypto_val_test
        		) {
    			$purchase_price = $purchase_price_temp;
        		$lvrg_level = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_lvrg'];
            	$sel_mrgntyp = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrgntyp'];
        		}
        		else {
    			$purchase_price = 0;
        		$lvrg_level = 0;
        		$sel_mrgntyp = 'long';
        		}
    					   
    		}
    		else {
    		$purchase_price = 0;
    		$lvrg_level = 0;
    		$sel_mrgntyp = 'long';
    		}
    					
    	
    	//echo ' ' . $held_amnt . ' => ' . $sel_mrgntyp . ' || ';
    		
    	//echo ' ' . $sel_mrkt . ' => ' .$sel_pair. ' ';
    					
    	// Render the row of coin data in the UI
    	$ct['asset']->ui_asset_row($ct['conf']['assets'][$asset_symb]['name'], $asset_symb, $held_amnt, $ct['conf']['assets'][$asset_symb]['pair'][$sel_pair], $sel_pair, $sel_mrkt, $purchase_price, $lvrg_level, $sel_mrgntyp);
    					
    					
    						
    		if ( $held_amnt >= $min_crypto_val_test ) {
    							
    		$assets_added = 1;
    						
    						
    			if ( $purchase_price >= 0.00000001 ) {
    			$purchase_price_added = 1;
    			}
    												
    			if ( $lvrg_level >= 2 ) {
    			$lvrg_added = 1;
    			}
    												
    			if ( $lvrg_level >= 2 && $sel_mrgntyp == 'short' ) {
    			$short_added = 1;
    		     }
    						
    						
    		}
    		elseif ( $held_amnt > 0.00000000 ) { // Show even if decimal is off the map, just for UX purposes tracking token price only
    		$assets_watched = 1;
    		}
    										
    						
    		if ( $held_amnt > 0.00000000 ) {
    		$ct['asset_tracking'][] = $asset_symb; // For only showing chosen assets in chart stats etc
    		}
    		

       }
				
		
	}

?>

</tbody>
</table>


<?php


// Get portfolio summaries


$total_btc_worth_raw = number_format($ct['asset']->bitcoin_total(), $ct['conf']['currency']['crypto_decimals_max'], '.', '');
$total_btc_worth_raw = $ct['var']->num_to_str($total_btc_worth_raw); // Cleanup any trailing zeros

// FOR UX-SAKE, WE CUT OFF EXTRA RIGHT SIDE ZERO DECIMALS IF VALUE IS AT LEAST A SATOSHI OR HIGHER (O.00000001),
// #BUT# IF VALUE IS LITERALLY ZERO (WATCH-ONLY, ETC), WE WANT TO SHOW THAT #CLEARLY# TO THE END USER WITH 0.00000000
$total_btc_worth = $ct['var']->num_pretty($total_btc_worth_raw, $ct['conf']['currency']['crypto_decimals_max']);

$total_prim_currency_worth = $ct['asset']->coin_stats_data('coin_worth_total');

    if ( $total_btc_worth_raw > 0 ) {
        
    $bitcoin_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['BTC'] / $total_btc_worth_raw ) * 100 );

    $ethereum_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['ETH'] / $total_btc_worth_raw ) * 100 );

    $solana_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['SOL'] / $total_btc_worth_raw ) * 100 );

    $miscassets_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['MISCASSETS'] / $total_btc_worth_raw ) * 100 );

    $btcnfts_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['BTCNFTS'] / $total_btc_worth_raw ) * 100 );

    $ethnfts_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['ETHNFTS'] / $total_btc_worth_raw ) * 100 );

    $solnfts_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['SOLNFTS'] / $total_btc_worth_raw ) * 100 );

    $altnfts_dominance = $ct['var']->num_to_str( ( $ct['btc_worth_array']['ALTNFTS'] / $total_btc_worth_raw ) * 100 );

    $stocks_dominance = $ct['var']->num_to_str( ( number_format($ct['asset']->stocks_bitcoin_total(), $ct['conf']['currency']['crypto_decimals_max'], '.', '') / $total_btc_worth_raw ) * 100 );

    }
    else {
        
    $bitcoin_dominance = 0;

    $ethereum_dominance = 0;

    $solana_dominance = 0;

    $miscassets_dominance = 0;

    $btcnfts_dominance = 0;

    $ethnfts_dominance = 0;

    $solnfts_dominance = 0;

    $altnfts_dominance = 0;

    $stocks_dominance = 0;
    
    }


$altcoin_dominance = ( $total_btc_worth_raw >= $min_crypto_val_test ? $ct['var']->num_to_str( 100 - $bitcoin_dominance - $ethereum_dominance - $solana_dominance - $miscassets_dominance - $btcnfts_dominance - $ethnfts_dominance - $solnfts_dominance - $altnfts_dominance - $stocks_dominance ) : 0.00 );


// Remove any slight decimal over 100 (100.01 etc)
$bitcoin_dominance = $ct['var']->max_100($bitcoin_dominance);
$ethereum_dominance = $ct['var']->max_100($ethereum_dominance);
$solana_dominance = $ct['var']->max_100($solana_dominance);
$miscassets_dominance = $ct['var']->max_100($miscassets_dominance);
$btcnfts_dominance = $ct['var']->max_100($btcnfts_dominance);
$ethnfts_dominance = $ct['var']->max_100($ethnfts_dominance);
$solnfts_dominance = $ct['var']->max_100($solnfts_dominance);
$altnfts_dominance = $ct['var']->max_100($altnfts_dominance);
$stocks_dominance = $ct['var']->max_100($stocks_dominance);
$altcoin_dominance = $ct['var']->max_100($altcoin_dominance);
	
		
?>


<!-- .portfolio_footer START -->
<div class="portfolio_footer">


<!-- Summary START -->
<div class="align_left show_asset_vals bold_1 blue">


<?php
		
		// Run BEFORE output of BTC / PAIR portfolio values, to include any margin / leverage summaries in parentheses NEXT TO THEM (NOT in the actual BTC / PAIR amounts, for UX's sake)
		if ( $purchase_price_added == 1 ) {
		    
		$gain_loss_total = $ct['asset']->coin_stats_data('gain_loss_total');
		
          $thres_dec = $ct['gen']->thres_dec($gain_loss_total, 'u', 'fiat'); // Units mode
          
		$parsed_gain_loss_total = preg_replace("/-/", "-" . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ], number_format($gain_loss_total, $thres_dec['max_dec'], '.', ',' ) );
		
		$original_worth = $ct['asset']->coin_stats_data('coin_paid_total');
		
		$lvrg_only_gain_loss = $ct['asset']->coin_stats_data('gain_loss_only_lvrg');
  		
		$total_prim_currency_worth_inc_lvrg = $total_prim_currency_worth + $lvrg_only_gain_loss;
		
		
          $thres_dec = $ct['gen']->thres_dec($total_prim_currency_worth_inc_lvrg, 'u', 'fiat'); // Units mode
          
  		// Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  		// We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  		// (plus sign would indicate a gain, NOT 'total worth')
		$parsed_total_prim_currency_worth_inc_lvrg = preg_replace("/-/", "", number_format($total_prim_currency_worth_inc_lvrg, $thres_dec['max_dec'], '.', ',' ) );
  		
		$total_prim_currency_worth_if_purchase_price = $ct['asset']->coin_stats_data('coin_total_worth_if_purchase_price') + $lvrg_only_gain_loss;
		
		$gain_loss_text = ( $gain_loss_total >= 0 ? 'gains' : 'losses' );
		
		}
		
	  

	     // Crypto value(s) of portfolio
		if ( $ct['sel_opt']['show_crypto_val'][0] ) {
		?>
			
			<div class="portfolio_summary">
			
			<span class="black private_data">Portfolio Value (Crypto):</span> 
			
			<span class='private_data'>
			<?php
					
			$scan_crypto_val = array_map( array($ct['var'], 'strip_brackets') , $ct['sel_opt']['show_crypto_val']); // Strip brackets
				
				// Control the ordering with corrisponding app config array (which is already ordered properly), for UX
				$loop = 0;
				foreach ( $ct['opt_conf']['crypto_pair'] as $key => $val ) {
						
						if ( in_array($key, $scan_crypto_val) ) {
						
						echo ( $loop > 0 ? ' &nbsp;/&nbsp; ' : '' );
					
							if ( $key == 'btc' ) {
						    $thres_dec = $ct['gen']->thres_dec($total_btc_worth, 'u', 'crypto'); // Units mode
							echo '<span class="'.$key.'" title="'.strtoupper($key).'">'.$val.' <span class="num_conv">' . $ct['var']->num_pretty($total_btc_worth, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . '</span></span>';
							}
							else {
							    
							   if ( $ct['asset']->pair_btc_val($key) > 0 ) {
							   $total_crypto_worth = ( $total_btc_worth_raw / $ct['asset']->pair_btc_val($key) );
						       $thres_dec = $ct['gen']->thres_dec($total_crypto_worth, 'u', 'crypto'); // Units mode
							   echo '<span class="'.$key.'" title="'.strtoupper($key).'">'.$val.' <span class="num_conv">' . $ct['var']->num_pretty($total_crypto_worth, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . '</span></span>';
							   }
							   else {
							   echo '<span class="'.$key.'" title="'.strtoupper($key).'">'.$val.' ' . number_format(0, 4) . '</span>';
							   }
							    
							}
				
						$loop = $loop + 1;
						
						}
				
				}
				?>
				</span>
                
                <?php				
				// Delete any stale configs
				if ( $loop < 1 ) {
				?>
				
				<script>
				$("#show_crypto_val").val('');
				delete_cookie("show_crypto_val");
				</script>
				
				<?php
				}
				?>
				
			<img class='tooltip_style_control' id="crypto_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative;" />
			
<script>
		
			var crypto_val_content = '<h5 class="yellow tooltip_title">Portfolio Value In Crypto</h5>'
			
			+'<p class="coin_info" style=" white-space: normal;">The value of your ENTIRE portfolio, in the cryptocurrencies you selected in the "Show Crypto Value Of ENTIRE Portfolio In" setting, on the Settings page.</p>'
			
			+'<p class="coin_info bitcoin" style=" white-space: normal;">If these values are skewed often, it\'s because the market(s) being used to determine the values are trading at different prices compared to the markets you chose in this interface. You can force certain markets to be used for this calculation with the "crypto_pair_preferred_markets" setting, in the Admin Config POWER USER section.</p>'
			
			+'<p class="coin_info red_bright" style=" white-space: normal;">It\'s HIGHLY RECOMMENDED to only add Bluechip / relatively lower risk crypto assets here! Remember, the <a href="https://www.google.com/search?q=barbell+portfolio+strategy" target="_blank">Barbell Portfolio Strategy</a> works VERY WELL for MANY investors that use it!</p>'
		
			+'<p class="coin_info balloon_notation bitcoin" style=" white-space: normal;"> *Includes any adjusted long (*NOT* short) deposits, BUT <i><u>any leverage is NOT included</u></i>.</p>';
		
		
			$('#crypto_val').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: crypto_val_content,
			css: balloon_css()
			});


</script>
			
			</div>
			
		<?php
		}
		?>
			
			
			<div class="portfolio_summary">
			
			
			<?php
			
		
        $thres_dec = $ct['gen']->thres_dec($total_prim_currency_worth, 'u', 'fiat'); // Units mode
		// Fiat value of portfolio
		echo '<span class="black private_data">Portfolio Value (' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) . '):</span> <span class="private_data">' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . $ct['var']->num_pretty($total_prim_currency_worth, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . '</span>';
		
		?>
		
		
			<img class='tooltip_style_control' id="fiat_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" /> 


<script>


var fiat_val_content = '<h5 class="yellow tooltip_title">Portfolio Value In <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?></h5>'
			
			+'<p class="coin_info" style=" white-space: normal;">The value of your ENTIRE portfolio, based off your selected primary currency (<?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>), in the "Primary Currency Market" setting, on the Settings page.</p>'
			
			+'<p class="coin_info" style=" white-space: normal;">Selected Primary Currency Market: <span class="bitcoin">BTC / <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?> @ <?=$ct['gen']->key_to_name($ct['conf']['currency']['bitcoin_primary_currency_exchange'])?> (<?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><span class="num_conv"><?=number_format( $ct['sel_opt']['sel_btc_prim_currency_val'], 0, '.', ',')?></span>)</span></p>'
		
			+'<p class="coin_info balloon_notation bitcoin" style=" white-space: normal;"> *Includes any adjusted long AND short deposits, BUT <i><u>any leverage is NOT included</u></i>.</p>';
		
		
		
			$('#fiat_val').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: fiat_val_content,
			css: balloon_css()
			});
			
		
		
		


</script>
		
			</div>
		
		
		<?php
		
		// If using margin leverege anywhere
		echo ( $purchase_price_added == 1 && $lvrg_added == 1 && is_numeric($gain_loss_total) == true ? '<div class="portfolio_summary"><span class="black private_data">Leverage Included: </span>' . ( $total_prim_currency_worth_inc_lvrg >= 0 ? '<span class="green private_data">' : '<span class="red private_data">-' ) . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . $parsed_total_prim_currency_worth_inc_lvrg . '</span></div>' : '' );
	

		// Now that BTC / PAIR summaries have margin leverage stats NEXT TO THEM (NOT in the actual BTC / PAIR amounts, for UX's sake), 
		// we move on to the gain / loss stats WHERE IT IS FEASIBLE ENOUGH TO INCLUDE !BASIC! MARGIN LEVERAGE DATA SUMMARY (where applicable)
		if ( $purchase_price_added == 1 && is_numeric($gain_loss_total) == true ) {
			
     	// Gain / loss percent (!MUST BE! absolute value)
          $percent_difference_total = abs( ($total_prim_currency_worth_if_purchase_price - $original_worth) / abs($original_worth) * 100 );
	
	     ?>
	
			<div class="portfolio_summary">
	
	     <?php
		
          $thres_dec = $ct['gen']->thres_dec($percent_difference_total, 'p'); // Percentage mode
          
		echo '<span class="black private_data">' . ( $gain_loss_total >= 0 ? 'Gain:</span> <span id="gain_loss_data" class="green private_data">+' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] : 'Loss:</span> <span id="gain_loss_data" class="red private_data">' ) . $parsed_gain_loss_total . ' (' . ( $gain_loss_total >= 0 ? '+' : '-' ) . '<span class="num_conv">' . number_format($percent_difference_total, $thres_dec['max_dec'], '.', ',') . '</span>%' . ')</span>';
		
	     ?> 
		
		
			<img class='tooltip_style_control' id='portfolio_gain_loss' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
		
			
	 <script>
	 
		var gain_loss_content = '<h5 class="yellow tooltip_title">Gain / Loss Stats</h5>'
			
			
			<?php
					
			     // Sort descending gains
			     $columns_array = array_column($ct['asset_stats_array'], 'gain_loss_total');
				array_multisort($columns_array, SORT_DESC, $ct['asset_stats_array']);
					
				foreach ( $ct['asset_stats_array'] as $key => $val ) {
				    
						
						if ( $val['coin_lvrg'] >= 2 ) {
						$parsed_total_with_lvrg = number_format( ( $val['coin_worth_total'] + $val['gain_loss_only_lvrg'] ) , 2, '.', ',' );
						}
						
					
						if ( $ct['var']->num_to_str($val['coin_paid']) >= $min_fiat_val_test ) {
							
                              $thres_dec_1 = $ct['gen']->thres_dec($val['gain_loss_total'], 'u', 'fiat'); // Units mode
                              
						$parsed_gain_loss = preg_replace("/-/", "", $ct['var']->num_pretty($val['gain_loss_total'], $thres_dec_1['max_dec'], false, $thres_dec_1['min_dec']) );
		
		
                              $thres_dec_2 = $ct['gen']->thres_dec($val['gain_loss_percent_total'], 'p'); // Percentage mode
				          
				          ?>
				          
			+'<p class="coin_info"><span class="bitcoin"><?=$val['coin_symb']?>:</span> <span class="<?=( $val['gain_loss_total'] >= 0 ? 'green">+' : 'red">-' )?><?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><span class="num_conv"><?=$parsed_gain_loss?></span> (<?=( $val['gain_loss_total'] >= 0 ? '+' : '-' )?><span class="num_conv"><?=number_format( abs($val['gain_loss_percent_total']) , $thres_dec_2['max_dec'], '.', ',')?></span>%<?=( $val['coin_lvrg'] >= 2 ? ', ' . $val['coin_lvrg'] . 'x ' . $val['selected_mrgntyp'] : '' )?>)</span></p>'
			
			    <?php
						}
							
				}
			    ?>
		
			+'<p class="coin_info balloon_notation bitcoin" style=" white-space: normal;"> *Includes any leverage.</p>'
				
			+'<p class="coin_info balloon_notation bitcoin">*<?=( $lvrg_added == 1 ? 'Leverage / ' : '' )?>Gain / Loss stats only include assets where you have set the<br />"Average Paid (per-unit)" value on the Update page.</p>';
		
		
			$('#portfolio_gain_loss').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: gain_loss_content,
			css: balloon_css()
			});
		
		 </script>
			
			</div>
		 
		 
		<?php
		}
		
		if ( $ct['var']->num_to_str($bitcoin_dominance) >= 0.01 || $ct['var']->num_to_str($ethereum_dominance) >= 0.01 || $ct['var']->num_to_str($miscassets_dominance) >= 0.01 || $ct['var']->num_to_str($altcoin_dominance) >= 0.01 ) {

			
			if ( $ct['var']->num_to_str($bitcoin_dominance) >= 0.01 ) {
			$bitcoin_dominance_text = '<span class="btc"><span class="num_conv">' . number_format($bitcoin_dominance, 2, '.', ',') . '</span>% BTC</span>';
			$seperator_btc = ( $ct['var']->num_to_str($bitcoin_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($ethereum_dominance) >= 0.01 ) {
			$ethereum_dominance_text = '<span class="eth"><span class="num_conv">' . number_format($ethereum_dominance, 2, '.', ',') . '</span>% ETH</span>';
			$seperator_eth = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($solana_dominance) >= 0.01 ) {
			$solana_dominance_text = '<span class="sol"><span class="num_conv">' . number_format($solana_dominance, 2, '.', ',') . '</span>% SOL</span>';
			$seperator_sol = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($miscassets_dominance) >= 0.01 ) {
			$miscassets_dominance_text = '<span class="num_conv">' . number_format($miscassets_dominance, 2, '.', ',') . '</span>% Misc. Assets (<span class="bitcoin_primary_currency_pair">' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']) . '</span>)';
			$seperator_miscassets = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($btcnfts_dominance) >= 0.01 ) {
			$btcnfts_dominance_text = '<span class="btc"><span class="num_conv">' . number_format($btcnfts_dominance, 2, '.', ',') . '</span>% BTC NFTs</span>';
			$seperator_btcnfts = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) + $ct['var']->num_to_str($btcnfts_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($ethnfts_dominance) >= 0.01 ) {
			$ethnfts_dominance_text = '<span class="eth"><span class="num_conv">' . number_format($ethnfts_dominance, 2, '.', ',') . '</span>% ETH NFTs</span>';
			$seperator_ethnfts = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) + $ct['var']->num_to_str($btcnfts_dominance) + $ct['var']->num_to_str($ethnfts_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($solnfts_dominance) >= 0.01 ) {
			$solnfts_dominance_text = '<span class="sol"><span class="num_conv">' . number_format($solnfts_dominance, 2, '.', ',') . '</span>% SOL NFTs</span>';
			$seperator_solnfts = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) + $ct['var']->num_to_str($btcnfts_dominance) + $ct['var']->num_to_str($ethnfts_dominance) + $ct['var']->num_to_str($solnfts_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($altnfts_dominance) >= 0.01 ) {
			$altnfts_dominance_text = '<span class="alt"><span class="num_conv">' . number_format($altnfts_dominance, 2, '.', ',') . '</span>% ALT NFTs</span>';
			$seperator_altnfts = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) + $ct['var']->num_to_str($btcnfts_dominance) + $ct['var']->num_to_str($ethnfts_dominance) + $ct['var']->num_to_str($solnfts_dominance) + $ct['var']->num_to_str($altnfts_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($stocks_dominance) >= 0.01 ) {
			$stocks_dominance_text = '<span class="num_conv">' . number_format($stocks_dominance, 2, '.', ',') . '</span>% Stocks';
			$seperator_stocks = ( $ct['var']->num_to_str($bitcoin_dominance) + $ct['var']->num_to_str($ethereum_dominance) + $ct['var']->num_to_str($solana_dominance) + $ct['var']->num_to_str($miscassets_dominance) + $ct['var']->num_to_str($btcnfts_dominance) + $ct['var']->num_to_str($ethnfts_dominance) + $ct['var']->num_to_str($solnfts_dominance) + $ct['var']->num_to_str($altnfts_dominance) + $ct['var']->num_to_str($stocks_dominance) <= 99.99 ? ' &nbsp;/&nbsp; ' : '' );
			}
			
			if ( $ct['var']->num_to_str($altcoin_dominance) >= 0.01 ) {
			$altcoin_dominance_text = '<span class="num_conv">' . number_format($altcoin_dominance, 2, '.', ',') .'</span>% Alt(s)';
			}
		
		?>
		 
		 	<div class="portfolio_summary">
		
		<?php
			
			echo '<span class="black private_data">Balance:</span>  <span class="private_data">' . $bitcoin_dominance_text . $seperator_btc . $ethereum_dominance_text . $seperator_eth . $solana_dominance_text . $seperator_sol . $miscassets_dominance_text . $seperator_miscassets . $btcnfts_dominance_text . $seperator_btcnfts . $ethnfts_dominance_text . $seperator_ethnfts . $solnfts_dominance_text . $seperator_solnfts . $altnfts_dominance_text . $seperator_altnfts . $stocks_dominance_text . $seperator_stocks . $altcoin_dominance_text . '</span>';
			
			
		?>
		
			<img class='tooltip_style_control' id='balance_stats' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative;' /> 
		
	 <script>
	
			<?php
					
				// Sort by most dominant first
				arsort($ct['btc_worth_array']);
					
				foreach ( $ct['btc_worth_array'] as $key => $val ) {
					
					if ( $key == 'MISCASSETS' ) {
					$key = 'MISC__' . strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']);
					}
					
			    // Remove any slight decimal over 100 (100.01 etc)
				$balance_stats = $ct['var']->max_100( ( $val / $total_btc_worth_raw ) * 100 );
					
					if ( $balance_stats >= 0.01 ) {
					$balance_stats_encoded .= '&' . urlencode($key) . '=' . urlencode( number_format($balance_stats, 2, '.', ',') );
					}
							
				}
				
			 ?>
			
		
			$('#balance_stats').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: ajax_placeholder(15, 'center', 'Loading Data...'),
  			url: 'ajax.php?type=chart&mode=asset_balance&lvrg_added=<?=$lvrg_added?>&short_added=<?=$short_added?><?=$balance_stats_encoded?>',
			css: balloon_css("left", "999", "750px")
			});
	
		 </script>
		
			</div>
		
		 
		<?php
		}
		?>
		
	
<!-- START 'view more stats' -->

	<div class="portfolio_summary">

			<b><a href="javascript: return false;" class="modal_style_control show_portfolio_stats blue" title="View More Portfolio Stats">View More Stats</a></b>

	</div>
		

	<!-- START MORE PORTFOLIO STATS MODAL -->
	<div class='' id="show_portfolio_stats">
	
	<?php
	
	foreach ( $ct['asset_tracking'] as $activated_plot ) {
	$plot_conf .= $activated_plot . '|';
	}
	
	$plot_conf = urlencode( rtrim($plot_conf,'|') );
	
	?>
		
		<h3 class='blue' style='display: inline;'>More Portfolio Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	
	<br clear='all' />
	
	
	<script>
	// We want ONLY WATCHED ASSETS SHOWN for privacy mode, so nobody easily
	// becomes interested in what we are NOT watching on the update page
	if ( get_cookie('priv_toggle') == 'on' ) {
	zingchart_privacy = '&privacy=on';
	}
	else {
	zingchart_privacy = '&privacy=off';
	}
	</script>
	
	
  	<?php
  	// Performance chart START (requires price charts)
	if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
	?>
	
<fieldset class='subsection_fieldset'>
	<legend class='subsection_legend'> <b>Asset Performance Comparison</b> </legend>
		    
	<p class='bitcoin' style='font-weight: bold;'>The Asset Performance Comparison chart <i>requires price charts to be enabled on the Charts page, and uses the price charts primary currency market</i> (<?=strtoupper($ct['default_bitcoin_primary_currency_pair'])?>) for value comparisons.</p>	
			
    <p>
    
    <?php
    
    $asset_performance_chart_defaults = explode("||", $ct['conf']['charts_alerts']['asset_performance_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $asset_performance_chart_defaults[0] >= 400 && $asset_performance_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_performance_chart_defaults[0] = 600;
    	}
    	
    	if ( $asset_performance_chart_defaults[1] >= 7 && $asset_performance_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_performance_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
	
    
    Time Period: <select class='browser-default custom-select' id='performance_chart_period' name='performance_chart_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -30 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	?>
    <option value='<?=$light_chart_days?>' <?=( $light_chart_days == 'all' ? 'selected' : '' )?>> <?=$ct['gen']->light_chart_time_period($light_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
    Custom Start Date: <input type="text" id='performance_date' name='performance_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='performance_chart_height' name='performance_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_performance_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='performance_menu_size' name='performance_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_performance_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Asset Performance Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var performance_chart_width = document.getElementById('performance_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('performance_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#performance_chart div.chart_reload div.chart_reload_msg').html('Loading Asset Performance Chart...');
  
	$('#performance_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('performance_chart', 'complete', function() {
  	
	$('#performance_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#performance_chart').css('height', document.getElementById('performance_chart_height').value + 'px');
	$('#performance_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('performance_chart_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -30 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('performance_chart_period').value );
		}
	
	});
	
	var to_timestamp_var = ( document.getElementById('performance_date').value ? document.getElementById('performance_date').value : '1970/1/1' );
	
	date_array = to_timestamp_var.split('/');
	
	date_timestamp = to_timestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('performance_chart', 'resize', {
  width: '100%',
  height: document.getElementById('performance_chart_height').value
  });
  
  // 'load'
  zingchart.exec('performance_chart', 'load', {
  	dataurl: 'ajax.php?type=chart&mode=asset_performance&time_period=' + document.getElementById('performance_chart_period').value + '&start_time=' + date_timestamp + '&chart_width=' + performance_chart_width + '&chart_height=' + document.getElementById('performance_chart_height').value + '&menu_size=' + document.getElementById('performance_menu_size').value + '&plot_conf=<?=$plot_conf?>' + zingchart_privacy,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control performance_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
<script>


var performance_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For Asset Performance Comparison Chart</h5>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">Select the Time Period, to get finer grain details for smaller time periods.</p>'
			
			+'<p class="coin_info extra_margins" style=" white-space: normal;">The "Custom Start Date" is OPTIONAL, for choosing a custom date in time the asset performance comparisions begin, starting at 0&#37; <?=strtoupper($ct['default_bitcoin_primary_currency_pair'])?> value increase / decrease. The Custom Start Date can only go back in time as far back as you have <?=strtoupper($ct['default_bitcoin_primary_currency_pair'])?> Value price charts (per asset) for the "All" chart, and only as far back as the beginning date of smaller time period charts.</p>'
			
			+'<p class="coin_info extra_margins" style=" white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in the Admin Config POWER USER section, under \'asset_performance_chart_defaults\'.</p>';
		
		
		
			$('.performance_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: performance_chart_defaults_content,
			css: balloon_css()
			});
			
		
		
		


</script> 
  
    </p>
    
  
  <script>
	$('.datepicker').datepicker({
    dateFormat: 'yy/mm/dd',
    defaultDate: -30
	});
  </script>
  
  <style>
	.ui-datepicker .ui-datepicker-header {
		background: #808080;
	}
 </style>
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; background: #808080; border: 2px solid #918e8e; display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='performance_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Asset Performance Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#performance_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Asset Performance Chart...');
	
  
zingchart.bind('performance_chart', 'load', function() {
$("#performance_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=asset_performance&time_period=all&start_time=0&chart_height=<?=$asset_performance_chart_defaults[0]?>&menu_size=<?=$asset_performance_chart_defaults[1]?>&plot_conf=<?=$plot_conf?>" + zingchart_privacy, function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('performance_chart', 'complete', function() {
	$("#performance_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#performance_chart').css('height', '<?=$asset_performance_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'performance_chart',
  	height: '<?=$asset_performance_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('performance_chart', 'label_click', function(e){
		
  	zingchart.exec('performance_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>
  
				
</fieldset>

  	<?php
	}
  	// Performance chart END
	?>
	


	
<fieldset class='subsection_fieldset'>
	<legend class='subsection_legend'> <b>Marketcap Comparison</b> </legend>
	
    <p>
    
    <?php
    
    $asset_marketcap_chart_defaults = explode("||", $ct['conf']['charts_alerts']['asset_marketcap_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $asset_marketcap_chart_defaults[0] >= 400 && $asset_marketcap_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_marketcap_chart_defaults[0] = 600;
    	}
    	
    	if ( $asset_marketcap_chart_defaults[1] >= 7 && $asset_marketcap_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$asset_marketcap_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
    Type: <select class='browser-default custom-select' id='mcap_type' name='mcap_type'>
    <option value='circulating'> Circulating </option>
    <option value='total'> Total </option>
    </select>  &nbsp;&nbsp; 
    
    
    Compare Against: <select class='browser-default custom-select' id='mcap_compare_diff' name='mcap_compare_diff'>
    <option value='none'> Nothing </option>
    <?php
    foreach ( $ct['conf']['assets'] as $key => $unused ) {
		
	// Consolidate function calls for runtime speed improvement
	$mcap_data = $ct['asset']->mcap_data($key, 'usd'); // For marketcap bar chart, we ALWAYS force using USD
    	
    	if ( $key != 'MISCASSETS' && isset($mcap_data['rank']) ) {
   	?>
    <option value='<?=$key?>'> <?=$key?> </option>
    <?php
    	}
    }
   	?>
    </select>  &nbsp;&nbsp; 
    
    
    Chart Height: <select class='browser-default custom-select' id='marketcap_data_height' name='marketcap_data_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_marketcap_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='marketcap_menu_size' name='marketcap_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $asset_marketcap_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Marketcap Comparison Chart' onclick="
  
  var marketcap_chart_width = document.getElementById('marketcap_chart').offsetWidth;
  
  // Reset any user-adjusted zoom
  zingchart.exec('marketcap_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#marketcap_chart div.chart_reload div.chart_reload_msg').html('Loading USD Marketcap Comparison Chart...');
  
	$('#marketcap_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('marketcap_chart', 'complete', function() {
  	
	$('#marketcap_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#marketcap_chart').css('height', document.getElementById('marketcap_data_height').value + 'px');
	$('#marketcap_chart').css('background', '#f2f2f2');
	
	});
	
	
  // 'resize' MUST run before 'load'
  zingchart.exec('marketcap_chart', 'resize', {
  width: '100%',
  height: document.getElementById('marketcap_data_height').value
  });
  
  // 'load'
  zingchart.exec('marketcap_chart', 'load', {
  	dataurl: 'ajax.php?type=chart&mode=marketcap_data&mcap_type=' + document.getElementById('mcap_type').value + '&mcap_compare_diff=' + document.getElementById('mcap_compare_diff').value + '&chart_width=' + marketcap_chart_width + '&chart_height=' + document.getElementById('marketcap_data_height').value + '&menu_size=' + document.getElementById('marketcap_menu_size').value + '&marketcap_site=<?=$ct['conf']['gen']['primary_marketcap_site']?>&plot_conf=<?=$plot_conf?>' + zingchart_privacy,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control marketcap_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
<script>


var marketcap_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For USD Marketcap Comparison Chart</h5>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">Marketcap Type: The \'circulating\' marketcap ONLY includes coins that are IN CIRCULATION (publicly available to transfer / trade), while the \'total\' marketcap includes ALL COINS (even those not mined yet / held by VIPs or Treasuries / etc).</p>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in the Admin Config POWER USER section, under \'asset_marketcap_chart_defaults\'.</p>';
		
		
		
			$('.marketcap_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: marketcap_chart_defaults_content,
			css: balloon_css()
			});
			
		
		
		


</script> 
  
    </p>
    
  
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; background: #808080; border: 2px solid #918e8e; display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='marketcap_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading USD Marketcap Comparison Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#marketcap_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading USD Marketcap Comparison Chart...');
	
  
zingchart.bind('marketcap_chart', 'load', function() {
$("#marketcap_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "ajax.php?type=chart&mode=marketcap_data&mcap_type=circulating&mcap_compare_diff=none&chart_height=<?=$asset_marketcap_chart_defaults[0]?>&menu_size=<?=$asset_marketcap_chart_defaults[1]?>&marketcap_site=<?=$ct['conf']['gen']['primary_marketcap_site']?>&plot_conf=<?=$plot_conf?>" + zingchart_privacy, function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('marketcap_chart', 'complete', function() {
	$("#marketcap_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
	$('#marketcap_chart').css('height', '<?=$asset_marketcap_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'marketcap_chart',
  	height: '<?=$asset_marketcap_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('marketcap_chart', 'label_click', function(e){
		
  	zingchart.exec('marketcap_chart', 'viewall', {
   graphid: 0
  	});
		
});
    
  </script>
  
				
</fieldset>


	<?php
  	// Run any ui-designated plugins activated in ct_conf
  	// ALWAYS KEEP PLUGIN RUNTIME LOGIC INLINE (NOT ISOLATED WITHIN A FUNCTION), 
  	// SO WE DON'T NEED TO WORRY ABOUT IMPORTING GLOBALS!
  	foreach ( $plug['activated']['ui'] as $plugin_key => $plugin_init ) {
  			
  	$this_plug = $plugin_key;
  		
  		if ( file_exists($plugin_init) && $plug['conf'][$this_plug]['ui_location'] == 'more_stats' ) {
      	?>
          <fieldset class='subsection_fieldset'>
             	<legend class='subsection_legend'> <b><?=$plug['conf'][$this_plug]['ui_name']?></b> </legend>
      	<?php
  		// This plugin's plug-init.php file (runs the plugin)
  		include($plugin_init);
          ?>
          </fieldset>
          <?php
  		}
  		
  	// Reset $this_plug at end of loop
  	unset($this_plug); 
    
  	}
	?>
		    
		    
	
  <p> &nbsp; </p>
  
  
  	<?php
	if ( $ct['conf']['charts_alerts']['enable_price_charts'] != 'on' ) {
	?>
	<p class='yellow'>*Some stats are not available with price charts disabled.</p>
	
  <p> &nbsp; </p>
  
  	<?php
	}
	?>
	
	</div>
	<!-- END MORE PORTFOLIO STATS MODAL -->
	
	<script>
	
	modal_windows.push('.show_portfolio_stats'); // Add to modal window tracking (for closing all dynaimically on app reloads) 
	
	$('.show_portfolio_stats').modaal({
	fullscreen: true,
	content_source: '#show_portfolio_stats'
	});
	
	</script>
	
<!-- END 'view more stats' -->
		
		
		
		<?php
		if ( $short_added == 1 ) {
		?>	
		<div class="portfolio_summary" style='margin-top: 15px;'>
		<span class="short private_data">★ Adjusted short trade deposit(s) (any leverage is <u>NOT</u> included)</span>
		</div>		
		<?php
		}
		?>
		
		
</div>
<!-- Summary END -->



		
	<!-- Admin Config - System monitoring quick Links (if we are admin logged in) -->
	<div id='admin_conf_quick_links' class='align_left private_data'>
	
	<?php
			// If hardware / software stats are enabled, display the os / hardware / load avg / temperature / free partition space / free memory [mb/percent] / portfolio cache size / software stats
    		if ( $ct['gen']->admin_logged_in() ) {
    ?>
	
		<fieldset><legend> <strong class="bitcoin">System Monitoring</strong> </legend>
    		
    		
    		<b><a id="sys_stats_quick_link" href="javascript: return false;" class="modal_style_control show_system_stats blue" title="View System Statistics">System Stats</a></b><img class='tooltip_style_control' id='sys_stats_quick_link_info' src='templates/interface/media/images/info-red.png' alt='' width='30' style='position: relative;' />
    		
    		<div style='min-height: 0.4em;'></div>
    		

    		<b><a href="javascript: return false;" class="modal_style_control show_access_stats blue" title="View Access Statistics">Access Stats</a></b>
    		
    		<div style='min-height: 0.4em;'></div>    		
    		

    		<b><a href="javascript: return false;" class="modal_style_control show_logs blue" title="View App Logs">App Logs</a></b>
 
    		
		</fieldset>
		
    		<?php
    		}
			?>


	</div>
<br class='clear_both' />

</div>
<!-- .portfolio_footer END -->


	<?php	
	// End outputting results
	}
	
	if ( $assets_added || $assets_watched ) {
	?>
	
	<style>
	.show_asset_vals, #admin_conf_quick_links, #coins_table {
	display: block !important;
	width: fit-content !important;
	margin: auto !important;
	}
	</style>
	
	<?php
	}
	else {
	?>
	
	<div class='align_center' style='min-height: 100px;'>
	
		<p><img src='templates/interface/media/images/favicon.png' alt='' class='image_border' /></p>
		<p class='red' style='font-weight: bold; position: relative; margin: 15px;'>No portfolio assets added yet (add them on the "Update" page).</p>
	</div>
	
	<?php
	}
	?>
	
	
	<div class='portfolio_footer' style='margin-top: 10px; height: auto;'>
	
		<b class='black private_data'>&nbsp;Trading Notes (<a href='javascript: return false;' target='_blank' onclick='
		
		if ( localStorage.getItem(notes_storage) != document.getElementById("notes").value ) {
		alert("You have changed your notes since you last saved them. \n\nPlease save your new notes before downloading them.");
		return false;
		}
		else {
		text_to_download(document.getElementById("notes").value,"Trading-Notes.txt")
		}
		
		' title='Download your trading notes to your computer.'>download</a>):</b><br />
	
		<textarea class='private_data' data-autoresize name='notes' id='notes' style='height: auto; width: 100%;'></textarea>
		<br />
	
		<button onclick='
		
		if ( get_cookie("priv_toggle") == "on" ) {
		alert("Submitting data is not allowed in privacy mode.");
		}
		else {
		
		localStorage.setItem(notes_storage, document.getElementById("notes").value);
		
		document.getElementById("notes_save_result").innerHTML = ajax_placeholder(15, "left", "Saving notes...", "inline");
		
		   setTimeout(
             function() {
             document.getElementById("notes_save_result").innerHTML = "";
             }
           , 2500);
           
		}
		
		'>Save Updated Notes</button> &nbsp; <span class='red' id='notes_save_result'></span>
		
	</div>
   
   
	<?php
		// If hardware / software stats are enabled, display the charts when designated link is clicked (in a modal)
    	if ( $ct['gen']->admin_logged_in() ) {
    ?>
	
	<div class='' id="show_system_stats">
	
		
		<h3 class='blue' style='display: inline;'>System Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
	
	<div id='portfolio_render_system_stats' style='margin-bottom: 30px;'>
	
	<?php
    			
         
         // Red UI nav, with info bubble too
         if ( is_array($ct['system_warnings']) && sizeof($ct['system_warnings']) > 0 ) {
         ?>
         <script>
         
                 if ( document.getElementById('sys_stats_quick_link') ) {
                 document.getElementById('sys_stats_quick_link').classList.add("red");
                 document.getElementById('sys_stats_quick_link_info').style.display = 'inline';
                 }

			var sys_stats_quick_link_info_content = '<h5 class="red tooltip_title">System Stats Alerts</h5>'
			
			<?php
			foreach ( $ct['system_warnings'] as $alert_key => $alert_val ) {
			?>
			+'<p class="coin_info" style=" white-space: normal;"><span class="red"><?=$ct['gen']->key_to_name($alert_key)?>:</span> <?=$alert_val?></p>'
			<?php
			}
			?>
			
			+'';
		
		
			$('#sys_stats_quick_link_info').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: sys_stats_quick_link_info_content,
			css: balloon_css()
			});
			
  
         </script>
         <?php
         }
	
	
    		// Output
    		if ( isset($ct['system_info']['operating_system']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Operating System:</b></span> <span class="blue"> '.$ct['system_info']['operating_system'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['distro_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Distro:</b></span> <span class="blue"> '.$ct['system_info']['distro_name'].( isset($ct['system_info']['distro_version']) ? ' ' . $ct['system_info']['distro_version'] : '' ).'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['model']) || isset($ct['system_info']['hardware']) ) {
    			
    			if ( isset($ct['system_info']['model']) ) {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Model:</b></span> <span class="blue"> '.$ct['system_info']['model'].( isset($ct['system_info']['hardware']) ? ' ('.$ct['system_info']['hardware'].')' : '' ).'</span> </div>';
    			}
    			else {
    			echo '<div class="sys_stats"><span class="bitcoin"><b>Hardware:</b></span> <span class="blue"> '.$ct['system_info']['hardware'].'</span> </div>';
    			}
    		
    		}
    		
    		if ( isset($ct['system_info']['model_name']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$ct['system_info']['model_name'].'</span> ' . ( $ct['system_info']['cpu_threads'] > 0 ? '(' . $ct['system_info']['cpu_threads'] . ' threads / cores)' : '' ) . ' </div>';
    		}
    		elseif ( isset($ct['system_info']['cpu_threads']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>CPU:</b></span> <span class="blue"> '.$ct['system_info']['cpu_threads'].' threads / cores</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['software']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Server:</b></span> <span class="blue"> '.$ct['system_info']['software'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['portfolio_cookies']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Server Cookies Size:</b></span> <span class="'.( isset($ct['system_warnings']['portfolio_cookies_size']) ? 'red' : 'green' ).'"> '.$ct['var']->num_pretty( ($ct['system_info']['portfolio_cookies'] / 1000) , 2).' Kilobytes</span> <span class="black">(~'.round( abs( ($ct['system_info']['portfolio_cookies'] / 1000) / abs(8.00) * 100 ) , 2).'% of <i>average</i> server header size limit [8 kilobytes])</span></span> &nbsp;<img class="tooltip_style_control server_header_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" /> </div>';
    		}
    		
    		if ( isset($ct['system_info']['uptime']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Uptime:</b></span> <span class="'.( isset($ct['system_warnings']['uptime']) ? 'red' : 'green' ).'"> '.$ct['system_info']['uptime'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['system_load']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Load:</b></span> <span class="'.( isset($ct['system_warnings']['system_load']) ? 'red' : 'green' ).'"> '.$ct['system_info']['system_load'].'</span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['system_temp']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>System Temperature:</b></span> <span class="'.( isset($ct['system_warnings']['system_temp']) ? 'red' : 'green' ).'"> '.$ct['system_info']['system_temp'].' <span class="black">('.round( ($system_temp * 9 / 5 + 32), 2).'° Fahrenheit)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['memory_used_megabytes']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>USED Memory (*not* including buffers / cache):</b></span> <span class="'.( isset($ct['system_warnings']['memory_used_percent']) ? 'red' : 'green' ).'"> '.round($ct['system_info']['memory_used_megabytes'] / 1000, 4).' Gigabytes <span class="black">('.number_format($ct['system_info']['memory_used_megabytes'], 2, '.', ',').' Megabytes / '.$ct['system_info']['memory_used_percent'].'%)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['free_partition_space']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>FREE Disk Space:</b></span> <span class="'.( isset($ct['system_warnings']['free_partition_space']) ? 'red' : 'green' ).'"> '.round($system_free_space_mb / 1000000, 4).' Terabytes <span class="black">('.number_format($system_free_space_mb / 1000, 2, '.', ',').' Gigabytes)</span></span> </div>';
    		}
    		
    		if ( isset($ct['system_info']['portfolio_cache']) ) {
    		echo '<div class="sys_stats"><span class="bitcoin"><b>Open Crypto Tracker Cache Size:</b></span> <span class="'.( isset($ct['system_warnings']['portfolio_cache_size']) ? 'red' : 'green' ).'"> '.round($portfolio_cache_size_mb / 1000, 4).' Gigabytes <span class="black">('.number_format($portfolio_cache_size_mb, 2, '.', ',').' Megabytes)</span></span> </div>';
    		}
    		
    		
   ?>
    
 
<script>


var server_header_defaults_content = '<h5 class="yellow tooltip_title">Average Server Header Size Limits</h5>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;">Web servers have a pre-set header size limit (which can be adjusted within it\'s own server configuration), which varies depending on the server software you are using.</p>'
            
            			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">IF THIS APP GOES OVER THOSE HEADER SIZE LIMITS, IT WILL CRASH!</span></p>'
            

			+'<p class="coin_info extra_margins" style=" white-space: normal;"><span class="bitcoin">STANDARD SERVER HEADER SIZE LIMITS (IN KILOBYTES)...</span><br />Apache: 8kb<br />NGINX: 4kb - 8kb<br />IIS: 8kb - 16kb<br />Tomcat: 8kb - 48kb</p>';
		
		
			$('.server_header_defaults').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: server_header_defaults_content,
			css: balloon_css()
			});
			
		
		
		


</script> 

    		
   </div>
    		
    		
   <ul>
        
	
	<li class='bitcoin' style='font-weight: bold;'>Charts may take awhile to update with the latest data.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>See Admin Config POWER USER section, to adjust vertical axis scales.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>System load is always (roughly) MULTIPLIED by the number of threads.</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>"CRON Core Runtime Seconds" DOES NOT INCLUDE plugin runtime (for stability of CORE runtime, in case <i>custom</i> plugins are buggy and crash).</li>	
   
   </ul>
   
	
	<?php
	$all_chart_rebuild_min_max = explode(',', $ct['conf']['charts_alerts']['light_chart_all_rebuild_min_max']);
	?>
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "Server Cookies Size" telemetry data above <i>is not tracked in the system charts, because it's ONLY available in the user interface runtime (NOT the cron job runtime)</i>.</p>				
	
	<p class='sys_stats red' style='font-weight: bold;'>*The "CRON Core Runtime Seconds" telemetry data <i>may vary per time period chart</i> (10D / 2W / 1M / 1Y / etc etc), as time period charts are updated during CRON runtimes, and some time period charts (including asset price charts) can take longer to update than others. Additionally, recent "ALL" chart data may show higher CRON runtimes, and average out in older data.</p>		
	
	<div class='red' id='system_charts_error'></div>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_1'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #1 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 1;
	include('templates/interface/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
	
	
	<br/><br/><br/>
	
	
	<div style='display: flex; flex-flow: column wrap; overflow: hidden;' class='chart_wrapper' id='sys_stats_chart_2'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading chart #2 for system data...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
	
	</div>
	
	<script>
	
	<?php
	$chart_mode = 2;
	include('templates/interface/php/admin/admin-elements/system-charts.php');
	?>
	
	</script>
		
	</div>
	
	
	<script>
	
	modal_windows.push('.show_system_stats'); // Add to modal window tracking (for closing all dynamically on app reloads) 
	
	$('.show_system_stats').modaal({
	fullscreen: true,
	content_source: '#show_system_stats'
	});

	</script>
	
	
	<div class='' id="show_access_stats">
	
		
		<h3 class='blue' style='display: inline;'>Access Stats</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
			


  <h3 class='bitcoin'>(<?=$ct['conf']['power']['access_stats_delete_old']?> Day Report)</h3>
    		
   <ul style='margin-top: 25px; font-weight: bold;'>
	
	<li class='bitcoin' style='font-weight: bold;'>You can adjust how long to store access stats for, in the Admin -> Power User section (with the "Access Stats Delete Old" setting).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>The DEFAULT sorting (on INITIAL load) is "Last Visit Time" first, AND "Total Visits" second (both descending).</li>
	
	<li class='bitcoin' style='font-weight: bold;'>Hover your mouse over the browser name, to see the full user agent string.</li>
	
   </ul>		
  
  
   <p>
  
   <button class='load_access_stats_onclick force_button_style' style='margin: 1em;'>Show / Refresh Latest Access Stats</button>
	
   </p>
	
	
     <div id='access_stats_data'></div>
					
		
	</div>
	
	
	<script>
	
	modal_windows.push('.show_access_stats'); // Add to modal window tracking (for closing all dynaimically on app reloads) 
	
	$('.show_access_stats').modaal({
	fullscreen: true,
	content_source: '#show_access_stats'
	});

	</script>
	
	
	
	<div class='' id="show_logs">
	
		
		<h3 class='blue' style='display: inline;'>App Logs</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	<br clear='all' />
			
    		
   <ul>
	
	<li class='bitcoin' style='font-weight: bold;'>Error / debugging logs will automatically display here, if they exist (primary error log always shows, even if empty).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>All log timestamps are UTC time (Coordinated Universal Time).</li>	
	
	<li class='bitcoin' style='font-weight: bold;'>Current UTC time: <span class='utc_timestamp red'></span></li>	
   
   </ul>
	
		
		<p class='red' style='font-weight: bold;'>*Log format: </p>
		
	   <!-- Looks good highlighted as: less, yaml  -->
	   <pre class='rounded' style='display: inline-block; padding-top: 1em !important;'><code class='hide-x-scroll less' style='white-space: nowrap; width: auto; display: inline-block;'>[UTC timestamp] runtime_mode => error_type: error_msg; [ (tracing if log verbosity set to verbose) ]</code></pre>
	
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> App Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='app_log_space' value='1' onchange="system_logs('app_log');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='app_log_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('app_log', 'app_log_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('app_log');">Refresh</button> 
	        
	        &nbsp; <span id='app_log_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='app_log'></code></pre>
			  
			  <script>
			  system_logs('app_log');
			  </script>
		
	    </fieldset>
				
	<?php
	if ( is_readable($ct['base_dir'] . '/cache/logs/smtp_error.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Error Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_error_space' value='1' onchange="system_logs('smtp_error');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='smtp_error_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_error', 'smtp_error_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_error');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_error_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_error'></code></pre>
			  
			  <script>
			  system_logs('smtp_error');
			  </script>
		
	    </fieldset>
	<?php
	}
	if ( is_readable($ct['base_dir'] . '/cache/logs/smtp_debug.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> SMTP Debugging Log </legend>
	        
	        <p>
	        
	        <b>Extra Spacing:</b> <input type='checkbox' id='smtp_debug_space' value='1' onchange="system_logs('smtp_debug');" />
	        
	        &nbsp; <b>Last lines:</b> <input type='text' id='smtp_debug_lines' value='100' maxlength="5" size="4" />
	        
	        &nbsp; <button class='force_button_style' onclick="copy_text('smtp_debug', 'smtp_debug_alert');">Copy To Clipboard</button> 
	        
	        &nbsp; <button class='force_button_style' onclick="system_logs('smtp_debug');">Refresh</button> 
	        
	        &nbsp; <span id='smtp_debug_alert' class='red'></span>
	        
	        </p>
	        
	        <!-- Looks good highlighted as: less, yaml  -->
	        <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%; height: 750px;' id='smtp_debug'></code></pre>
			  
			  <script>
			  system_logs('smtp_debug');
			  </script>
		
	    </fieldset>
	<?php
	}
	?>
	    
			    
		
	</div>
	
	
	<script>
	
	modal_windows.push('.show_logs'); // Add to modal window tracking (for closing all dynaimically on app reloads) 
	
	$('.show_logs').modaal({
	fullscreen: true,
	content_source: '#show_logs'
	});

	</script>
	
	<?php
		}
    ?>
	
				
				
				
</div> <!-- full_width_wrapper END -->




	
	
	
                            
                        