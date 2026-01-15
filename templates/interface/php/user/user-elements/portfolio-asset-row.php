<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// REMEMBER WE HAVE #GLOBALS# TO WORRY ABOUT ADDING IN $ct['asset']->ui_asset_row(), AS THAT'S WHERE THIS CODE IS RAN!

 
// Consolidate function calls for runtime speed improvement
 $mcap_data = $this->mcap_data($asset_symb);

 //$ct['gen']->array_debugging($mcap_data); // DEBUGGING ONLY
 
 ?>
 

<!-- Coin data row START -->

<tr id='<?=strtolower($asset_symb)?>_row'>
  


<td class='data border_lb'>

<span class='app_sort_filter'>

<?php 

//echo $sort_order;
if ( preg_match("/stock/i", $asset_symb) || $asset_symb == 'MISCASSETS' || $asset_symb == 'BTCNFTS' || $asset_symb == 'ETHNFTS' || $asset_symb == 'SOLNFTS' || $asset_symb == 'ALTNFTS' ) {
echo 'N/A';
}
elseif ( isset($mcap_data['rank']) ) {
echo '#' . $mcap_data['rank'];
}
else {
echo '?';
}

?>

</span>

</td>



<td class='data border_lb' align='right' style='position: relative; white-space: nowrap;'>
 
 
 <?php
 
 $mkcap_render_data = trim($ct['conf']['assets'][$asset_symb]['mcap_slug']);
 
 $info_icon = ( !$mcap_data['rank'] && $asset_symb != 'MISCASSETS' && $asset_symb != 'BTCNFTS' && $asset_symb != 'ETHNFTS' && $asset_symb != 'SOLNFTS' && $asset_symb != 'ALTNFTS' && !preg_match("/stock/i", $asset_symb) ? 'info-red.png' : 'info.png' );
 
     
     // UX, auto-filling marketcap web page slug, FOR STOCKS WITHOUT THE SLUG ALREADY SET
	if ( preg_match("/stock/i", $asset_symb) && $mkcap_render_data == '' ) {
	
	$raw_ticker = preg_replace('/stock/i', '', $asset_symb);
	
	    foreach ( $ct['conf']['assets'][$asset_symb]['pair'] as $stock_pairing_key => $unused ) {
	    
	    $stock_exchange_id = $ct['var']->array_key_first($ct['conf']['assets'][$asset_symb]['pair'][$stock_pairing_key]);
	         
	         // Alphavantage
	         if ( $stock_exchange_id == 'alphavantage_stock' ) {
	         
	         //var_dump($sel_pair);
	         //var_dump($stock_pairing_key);
	         
                   
                   // IF the selected pairing is NOT a match, skip this loop
                   if ( $sel_pair != $stock_pairing_key ) {
                   continue;
                   }
                   // (ONLY IF WE CAN IDENTIFY THE EXCHANGE, BY A DISTINCT MARKET ID)
	              else if ( preg_match('/\.trt/i', $ct['conf']['assets'][$asset_symb]['pair'][$stock_pairing_key][$stock_exchange_id]) ) {
	              $mkcap_render_data = $raw_ticker . ':TSE';
	              }
	              else if ( preg_match('/\.dex/i', $ct['conf']['assets'][$asset_symb]['pair'][$stock_pairing_key][$stock_exchange_id]) ) {
	              $mkcap_render_data = $raw_ticker . ':ETR';
	              }
	              // Otherwise, look for "Exchange" value, in stock overview API data
	              else if ( !preg_match('/\./i', $ct['conf']['assets'][$asset_symb]['pair'][$stock_pairing_key][$stock_exchange_id]) ) {
                    	              
                   // Stock overview
                   $stock_overview = $ct['api']->stock_overview($raw_ticker);
                   
                       if (
                       isset($stock_overview['data']['Exchange']) 
                       && trim($stock_overview['data']['Exchange']) != ''
                       ) {
                       $mkcap_render_data = $raw_ticker . ':' . strtoupper($stock_overview['data']['Exchange']);
                       }
                       // IF no exchange data parsed, skip, but link to google finance "did you mean?" results
                       else {
                       $mkcap_render_data = $raw_ticker;
                       }
	              
	              }
                   // IF no exchange data parsed, skip, but link to google finance "did you mean?" results
                   else {
                   $mkcap_render_data = $raw_ticker;
                   }

	              
	         }
	    
	    }
	
	}
	
	
	if ( isset($mkcap_render_data) && $mkcap_render_data != '' ) {
 	
 
 		if ( preg_match("/stock/i", $asset_symb) ) {
 		$asset_pagebase = 'www.google.com/finance/quote/';
 		}
 		elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' ) {
 		$asset_pagebase = 'coinmarketcap.com/currencies/';
 		}
 		elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coingecko' ) {
 		$asset_pagebase = 'coingecko.com/en/coins/';
 		}
 	
 	
 		?>
 		
 <a href='https://<?=$asset_pagebase?><?=$mkcap_render_data?>' target='_blank' class='blue app_sort_filter' title='View Information Page For <?=$asset_symb?>'><?=$asset_name?></a> <img class='tooltip_style_control' id='<?=preg_replace("/\:/", "", $mkcap_render_data)?>' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
 <script>

		<?php
		if ( !$mcap_data['rank'] ) {
			
			if ( preg_match("/stock/i", $asset_symb) ) {
			?>


			$('#<?=preg_replace("/\:/", "", $mkcap_render_data)?>').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: ajax_placeholder(15, 'center', 'Loading Data...'),
  			url: 'ajax.php?type=assets&mode=stock_overview&ticker=<?=urlencode($asset_symb)?>&pairing=<?=$sel_pair?>&name=<?=urlencode($asset_name)?>',
			css: balloon_css("left", "999"),
			ajaxComplete: function(var1, var2) {
			                                   // var var3 = this.id;
			                                   convert_numbers('.balloon-tooltips p.coin_info', pref_number_format);
			                                   }
			});
			
	
			<?php
			}
			elseif ( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' && trim($ct['conf']['ext_apis']['coinmarketcap_api_key']) == null ) {
			?>

			var cmc_content = '<p class="coin_info"><span class="red"><?=ucfirst($ct['conf']['gen']['primary_marketcap_site'])?> API key is required. <br />Configuration adjustments can be made in the Admin Config GENERAL / EXTERNAL APIS sections.</span></p>';
	
			<?php
			}
			else {
			?>

			var cmc_content = '<p class="coin_info" style="white-space: normal; "><span class="red"><?=ucfirst($ct['conf']['gen']['primary_marketcap_site'])?> API may be offline / under heavy load, OR marketcap range not set high enough (current range is top <?=$ct['conf']['power']['marketcap_ranks_max']?> marketcaps, set in the "Power User" ADMIN area section), OR remote API timeout set too low (current timeout is <?=$ct['conf']['ext_apis']['remote_api_timeout']?> seconds, set in the "External APIs" ADMIN area section).</span></p>'
            
            +'<p class="coin_info bitcoin" style="white-space: normal; ">Please check back in awhile, OR <i>switch to an alternate marketcap data provider</i> in the "General" ADMIN area section, AND / OR in the "User Settings" USER area section.</p>';
	
			<?php
			}

			if ( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
			?>
			
			setTimeout(function() {
    		row_alert("<?=strtolower($asset_symb)?>_row", "visual", "no_cmc", "<?=$ct['sel_opt']['theme_selected']?>"); // Assets with marketcap data not set or functioning properly
			}, 1000);
			
			<?php
			}
		
        }
        else {
        	
        		if ( isset($ct['mcap_data_force_usd']) ) {
        		$mcap_prim_currency_symb = '$';
        		$mcap_prim_currency_ticker = 'USD';
        		}
        		else {
        		$mcap_prim_currency_symb = $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ];
        		$mcap_prim_currency_ticker = strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair']);
        		}
        		
        ?> 
    
        var cmc_content = '<h5 class="yellow tooltip_title"><?=ucfirst($ct['conf']['gen']['primary_marketcap_site'])?>.com Summary For: <?=$asset_name?> (<?=$asset_symb?>)</h5>'
        
        		<?php
            if ( isset($mcap_data['app_notice']) && $mcap_data['app_notice'] != '' ) {
        		?>
        +'<p class="coin_info red">Notice: <br /><?=$mcap_data['app_notice']?></p>'
        		<?php
            }
        		?>
        
        +'<p class="coin_info"><span class="bitcoin">Ranking:</span> #<?=$mcap_data['rank']?></p>'
        +'<p class="coin_info"><span class="bitcoin">Marketcap (circulating):</span> <?=$mcap_prim_currency_symb?><?=number_format($mcap_data['market_cap'],0,".",",")?></p>'
        
        <?php
            if ( $mcap_data['market_cap_total'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">Marketcap (total):</span> <?=$mcap_prim_currency_symb?><?=number_format($mcap_data['market_cap_total'],0,".",",")?></p>'
        <?php
            }
            if ( $mcap_data['circulating_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">Circulating Supply:</span> <?=number_format($mcap_data['circulating_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $mcap_data['total_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">Total Supply:</span> <?=number_format($mcap_data['total_supply'], 0, '.', ',')?></p>'
        <?php
            }
            if ( $mcap_data['max_supply'] > 0 ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">Maximum Supply:</span> <?=number_format($mcap_data['max_supply'], 0, '.', ',')?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="bitcoin">Unit Value (global average):</span> <?=$mcap_prim_currency_symb?><?=$mcap_data['price']?></p>'
        +'<p class="coin_info"><span class="bitcoin">24 Hour Volume (global):</span> <?=$mcap_prim_currency_symb?><?=number_format($mcap_data['vol_24h'],0,".",",")?></p>'
        <?php
            if ( $mcap_data['percent_change_1h'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">1 Hour Change:</span> <?=( stristr($mcap_data['percent_change_1h'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_1h'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_1h'].'%</span>' )?></p>'
        <?php
            }
            ?>
        +'<p class="coin_info"><span class="bitcoin">24 Hour Change:</span> <?=( stristr($mcap_data['percent_change_24h'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_24h'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_24h'].'%</span>' )?></p>'
        <?php
            if ( $mcap_data['percent_change_7d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">7 Day Change:</span> <?=( stristr($mcap_data['percent_change_7d'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_7d'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_7d'].'%</span>' )?></p>'
        <?php
            }
            if ( $mcap_data['percent_change_14d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">14 Day Change:</span> <?=( stristr($mcap_data['percent_change_14d'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_14d'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_14d'].'%</span>' )?></p>'
        <?php
            }
            if ( $mcap_data['percent_change_30d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">30 Day Change:</span> <?=( stristr($mcap_data['percent_change_30d'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_30d'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_30d'].'%</span>' )?></p>'
        <?php
            }
            if ( $mcap_data['percent_change_90d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">90 Day Change:</span> <?=( stristr($mcap_data['percent_change_90d'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_90d'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_90d'].'%</span>' )?></p>'
        <?php
            }
            if ( $mcap_data['percent_change_200d'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">200 Day Change:</span> <?=( stristr($mcap_data['percent_change_200d'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_200d'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_200d'].'%</span>' )?></p>'
        <?php
            }
            if ( $mcap_data['percent_change_1y'] != null ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">1 Year Change:</span> <?=( stristr($mcap_data['percent_change_1y'], '-') != false ? '<span class="num_conv red">'.$mcap_data['percent_change_1y'].'%</span>' : '<span class="num_conv green">+'.$mcap_data['percent_change_1y'].'%</span>' )?></p>'
        <?php
            }
            if ( isset($mcap_data['last_updated']) && $mcap_data['last_updated'] != '' ) {
            ?>
        +'<p class="coin_info"><span class="bitcoin">Data Timestamp (UTC):</span> <?=gmdate("Y-M-d\ \\a\\t g:ia", $mcap_data['last_updated'])?></p>'
        +'<p class="coin_info"><span class="bitcoin">Summary Cache Time:</span> <?=$ct['conf']['power']['marketcap_cache_time']?> minute(s)</p>'
        <?php
            }
            ?>
    
        +'<p class="coin_info balloon_notation bitcoin">*Current config setting retrieves the top <?=$ct['conf']['power']['marketcap_ranks_max']?> rankings.</p>';
    
        <?php
        
        }
        
        
        if ( !preg_match("/stock/i", $asset_symb) ) {
        ?>
    
        $('#<?=preg_replace("/\:/", "", $mkcap_render_data)?>').balloon({
        html: true,
        position: "right",
  	   classname: 'balloon-tooltips',
        contents: cmc_content,
        css: balloon_css()
        });
    
    
        <?php
        }
    
    
        if ( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
        	
        $percent_alert_filter = $ct['sel_opt']['alert_percent'][2]; // gain / loss / both
    
        $percent_change_alert = $ct['var']->num_to_str($ct['sel_opt']['alert_percent'][1]);
    
        $percent_alert_type = $ct['sel_opt']['alert_percent'][4];
    
    
            if ( $ct['sel_opt']['alert_percent'][3] == '1hour' ) {
            $percent_change = $mcap_data['percent_change_1h'];
            }
            elseif ( $ct['sel_opt']['alert_percent'][3] == '24hour' ) {
            $percent_change = $mcap_data['percent_change_24h'];
            }
            elseif ( $ct['sel_opt']['alert_percent'][3] == '7day' ) {
            $percent_change = $mcap_data['percent_change_7d'];
            }
            
            
            $percent_change = $ct['var']->num_to_str($percent_change);
          
         
            if ( $percent_alert_filter != 'gain' && stristr($percent_change, '-') != false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
         
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symb)?>_row", "<?=$percent_alert_type?>", "yellow", "<?=$ct['sel_opt']['theme_selected']?>");
               //console.log('<?=$asset_symb?> set to: yellow');
            }, 1000);
            
            <?php
            }
            elseif ( $percent_alert_filter != 'loss' && stristr($percent_change, '-') == false && abs($percent_change) >= abs($percent_change_alert) && is_numeric($percent_change) ) {
            ?>
            
            setTimeout(function() {
               row_alert("<?=strtolower($asset_symb)?>_row", "<?=$percent_alert_type?>", "green", "<?=$ct['sel_opt']['theme_selected']?>");
               //console.log('<?=$asset_symb?> set to: green');
            }, 1000);
            
            <?php
            }
        
        
        }
        ?>
     </script>
     
 <?php
	}
	else {
		
  ?>
  
  <span class='blue app_sort_filter'><?=$asset_name?></span> <img id='<?=$rand_id?>' class='tooltip_style_control' src='templates/interface/media/images/<?=$info_icon?>' alt='' style='position: relative; vertical-align:middle; height: 30px; width: 30px;' /> 
  
 <script>
 
 			<?php
			if ( $asset_symb == 'MISCASSETS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symb?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Miscellaneous <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?> value can be included in you portfolio stats, by entering it under the "MISCASSETS" asset on the "Update" page.</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">This can be useful for including <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?> Checking / Savings accounts at banks, stable coin holdings, etc.</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>.</span></p>';
	
			<?php
			}
			elseif ( $asset_symb == 'BTCNFTS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symb?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">BTC value of NFTS can be included in you portfolio stats, by entering it under the "BTCNFTS" asset on the "Update" page.</span></p>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">If you are unsure of the value of any of your NFTs, you can use the \'Floor Price\' (if available) for that NFT collection found on NFT marketplace(s).</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>.</span></p>';
	
			<?php
			}
			elseif ( $asset_symb == 'ETHNFTS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symb?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">ETH value of NFTS can be included in you portfolio stats, by entering it under the "ETHNFTS" asset on the "Update" page.</span></p>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">If you are unsure of the value of any of your NFTs, you can use the \'Floor Price\' (if available) for that NFT collection found on NFT marketplace(s).</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>.</span></p>';
	
			<?php
			}
			elseif ( $asset_symb == 'SOLNFTS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symb?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">SOL value of NFTS can be included in you portfolio stats, by entering it under the "SOLNFTS" asset on the "Update" page.</span></p>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">If you are unsure of the value of any of your NFTs, you can use the \'Floor Price\' (if available) for that NFT collection found on NFT marketplace(s).</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>.</span></p>';
	
			<?php
			}
			elseif ( $asset_symb == 'ALTNFTS' ) {
			?>

			var cmc_content = '<h5 class="yellow align_center tooltip_title"><?=$asset_name?> (<?=$asset_symb?>)</h5>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin"><?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?> value of NFTS can be included in you portfolio stats, by entering it under the "ALTNFTS" asset on the "Update" page.</span></p>'
    
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">If you are unsure of the value of any of your NFTs, you can use the \'Floor Price\' (if available) for that NFT collection found on NFT marketplace(s).</span></p>'
        
        +'<p class="coin_info" style="white-space: normal; "><span class="bitcoin">Additionally, you can see it\'s potential market value in another asset by changing the "Market" value on the "Portfolio" page to an asset other than <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>.</span></p>';
	
			<?php
			}
			else {
			?>
			
			var cmc_content = '<p class="coin_info"><span class="red">No <?=ucfirst($ct['conf']['gen']['primary_marketcap_site'])?>.com data for <?=$asset_name?> (<?=$asset_symb?>) has been configured yet.</span></p>';
	
			<?php
			}
			?>
 
 $('#<?=$rand_id?>').balloon({
  html: true,
  position: "right",
  classname: 'balloon-tooltips',
  contents: cmc_content,
  css: balloon_css()
});

		<?php
		if ( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
		?>
		
		setTimeout(function() {
    	row_alert("<?=strtolower($asset_symb)?>_row", "visual", "no_cmc", "<?=$ct['sel_opt']['theme_selected']?>"); // Assets with marketcap data not set or functioning properly
		}, 1000);
		
		<?php
		}
		?>
		
 </script>
 
	<?php
	}
 
 ?>
 
 
</td>




<td class='data border_b'>


<?php
  
  $asset_prim_currency_val = ($ct['sel_opt']['sel_btc_prim_currency_val'] * $btc_trade_eqiv_raw);
              	     
  $thres_dec = $ct['gen']->thres_dec($asset_prim_currency_val, 'u', 'fiat'); // Units mode
  $asset_prim_currency_val = $ct['var']->num_pretty($asset_prim_currency_val, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
	
  echo "<span class='white'>" . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . "</span>" . "<span class='app_sort_filter'>" . $asset_prim_currency_val . "</span>";

?>

</td>



<td class='data border_lb' align='right'>

<span class='app_sort_filter'>

<?php 

$asset_val_raw = $ct['var']->num_to_str($asset_val_raw);

	// FIAT EQUIV
	if ( $has_btc_pairing ) {
     $thres_dec = $ct['gen']->thres_dec($asset_val_raw, 'u', 'fiat'); // Units mode
	}
	else {
     $thres_dec = $ct['gen']->thres_dec($asset_val_raw, 'u', 'crypto'); // Units mode
	}

echo $ct['var']->num_pretty($asset_val_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']);

?>

</span>

<?php

  if ( $ct['sel_opt']['show_secondary_trade_val'] != null && $sel_pair != $ct['sel_opt']['show_secondary_trade_val'] && strtolower($asset_symb) != $ct['sel_opt']['show_secondary_trade_val'] ) {
  
		if ( $ct['sel_opt']['show_secondary_trade_val'] == 'btc' ) {
		$secondary_trade_val_result = $ct['var']->num_to_str($btc_trade_eqiv_raw);
          $thres_dec = $ct['gen']->thres_dec($secondary_trade_val_result, 'u', 'crypto'); // Units mode
		}
		else {
		    
		     if ( $this->pair_btc_val($ct['sel_opt']['show_secondary_trade_val']) > $ct['min_crypto_val_test'] ) {
		     $secondary_trade_val_result = $ct['var']->num_to_str( $btc_trade_eqiv_raw / $this->pair_btc_val($ct['sel_opt']['show_secondary_trade_val']) );
		     }
		     else {
		     $secondary_trade_val_result = 0;
		     }
     		   	
     		// Fiat-eqiv
            	if ( array_key_exists($ct['sel_opt']['show_secondary_trade_val'], $ct['conf']['assets']['BTC']['pair']) ) {
               $thres_dec = $ct['gen']->thres_dec($secondary_trade_val_result, 'u', 'fiat'); // Units mode
         		}
         		// Crypto
         		else {
               $thres_dec = $ct['gen']->thres_dec($secondary_trade_val_result, 'u', 'crypto'); // Units mode
         		}

		}
		
		if ( $secondary_trade_val_result >= $ct['min_crypto_val_test'] ) {
  		echo '<div class="crypto_worth">(' . $ct['var']->num_pretty($secondary_trade_val_result, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . ' '.strtoupper($ct['sel_opt']['show_secondary_trade_val']).')</div>';
		}
  
  }
  
?>

</td>




<td class='data border_b'> 

 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symb)?>_pair' title='Choose which market you want.' onchange='
    $("#<?=strtolower($asset_symb)?>_pair").val(this.value); 
    $("#<?=strtolower($asset_symb)?>_mrkt").val(1); // Just reset to first listed market for this pair
    
    <?php
    // 
    if ( $asset_symb == 'BTC' && !is_array($ct['sel_opt']['prim_currency_mrkt_standalone']) ) {
    ?>
    $("#prim_currency_mrkt").val( this.value + "|1" ); // Just reset to first listed market for this pair
    <?php
    }
    ?>
    
    $("#user_area_settings").submit();
    '>
    
    
        <?php
		  
        $loop = 0;

	        foreach ( $all_pairs as $pair_key => $pair_name ) {
	         $loop = $loop + 1;
	         	if ( $sel_pair == $pair_key ) {
	         	$ui_selected_pair = $pair_key;
	         	}
	        ?>
	        <option value='<?=$pair_key?>' <?=( $sel_pair == $pair_key ? ' selected ' : '' )?>> <?=strtoupper($pair_key)?> </option>
	        <?php
	        }
        
        $loop = null;
        
        ?>
        
        
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_pair?></div>

</td>




<td class='data border_b'>
 
    <select class='browser-default custom-select' name='change_<?=strtolower($asset_symb)?>_mrkt' title='Choose which exchange or defi pool you want.' onchange='
    $("#<?=strtolower($asset_symb)?>_mrkt").val(this.value);
    
    <?php
    // 
    if ( $asset_symb == 'BTC' && !is_array($ct['sel_opt']['prim_currency_mrkt_standalone']) ) {
    ?>
    $("#prim_currency_mrkt").val( $("#<?=strtolower($asset_symb)?>_pair").val() + "|" + this.value );
    <?php
    }
    ?>
    
    $("#user_area_settings").submit();
    '>
        <?php
        $loop = 0;
        foreach ( $all_pair_mrkts as $mrkt_key => $mrkt_name ) {
         $loop = $loop + 1;
         	if ( $original_mrkt == ($loop - 1) ) {
         	$selected_mrkt = $mrkt_key;
         	$ui_selected_mrkt = $ct['gen']->key_to_name($mrkt_key);
         	}
        ?>
        <option value='<?=($loop)?>' <?=( $original_mrkt == ($loop - 1) ? ' selected ' : '' )?>> <?=$ct['gen']->key_to_name($mrkt_key)?> </option>
        <?php
        }
        ?>
    </select>
    
    <div class='app_sort_filter' style='display: none;'><?=$ui_selected_mrkt?></div>

</td>





<td class='data border_b'>

<span class='white'><?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?></span><span class='app_sort_filter'><?php 

  // NULL if not setup to get volume, negative number returned if no data received from API
  if ( $trade_vol == null || $trade_vol == -1 ) {
  echo '0';
  }
  elseif ( $trade_vol >= 0 ) {
  echo number_format($trade_vol, 0, '.', ',');
  }

?></span>

<?php
if ( in_array($selected_mrkt, $ct['dev']['no_trade_volume_api_data']) ) {
?>
 <span class='white'>(N/A)</span> 
<?php
}
?>

</td>




<td class='data border_lb blue' align='right'>

<?php

	
	if ( strtolower($asset_symb) == 'btc' ) {
     $thres_dec = $ct['gen']->thres_dec($asset_amnt, 'u', 'crypto'); // Units mode
	}
	else {
		   	
     	// Fiat-eqiv
          if ( array_key_exists(strtolower($asset_symb), $ct['conf']['assets']['BTC']['pair']) ) {
          $thres_dec = $ct['gen']->thres_dec($asset_amnt, 'u', 'fiat'); // Units mode
         	}
         	// Crypto
         	else {
          $thres_dec = $ct['gen']->thres_dec($asset_amnt, 'u', 'crypto'); // Units mode
         	}
    	
	}
	
$pretty_asset_amnt = $ct['var']->num_pretty($asset_amnt, $thres_dec['max_dec'], false, $thres_dec['min_dec']);

echo "<span class='app_sort_filter blue private_data'>" . ( $pretty_asset_amnt != null ? $pretty_asset_amnt : 0 ) . "</span>";

?>

</td>




<td class='data border_b'><span class='app_sort_filter'>

<?php echo $asset_symb; ?></span>

</td>




<td class='data border_b blue'>

<?php

$asset_val_total_raw = $ct['var']->num_to_str($asset_val_total_raw);

	// UX on FIAT EQUIV
	if ( $has_btc_pairing ) {
    $thres_dec = $ct['gen']->thres_dec($asset_val_total_raw, 'u', 'fiat'); // Units mode
	}
	else {
    $thres_dec = $ct['gen']->thres_dec($asset_val_total_raw, 'u', 'crypto'); // Units mode
	}

$pretty_asset_val_total_raw = $ct['var']->num_pretty($asset_val_total_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']);

echo ' <span class="blue"><span class="data app_sort_filter blue private_data">' . $pretty_asset_val_total_raw . '</span> ' . strtoupper($sel_pair) . '</span>';

  
  if ( $ct['sel_opt']['show_secondary_trade_val'] != null && $sel_pair != $ct['sel_opt']['show_secondary_trade_val'] && strtolower($asset_symb) != $ct['sel_opt']['show_secondary_trade_val'] ) {
  
		if ( $ct['sel_opt']['show_secondary_trade_val'] == 'btc' ) {
		$secondary_holdings_val_result = $ct['var']->num_to_str($asset_val_total_raw * $pair_btc_val);
        $thres_dec = $ct['gen']->thres_dec($secondary_holdings_val_result, 'u', 'crypto'); // Units mode
		}
		else {

            if ( $this->pair_btc_val($ct['sel_opt']['show_secondary_trade_val']) > 0 ) {
		    $secondary_holdings_val_result = $ct['var']->num_to_str( ($asset_val_total_raw * $pair_btc_val) / $this->pair_btc_val($ct['sel_opt']['show_secondary_trade_val']) );
            }
            else {
		    $secondary_holdings_val_result = 0;
            }
		   	
		   	// Fiat-eqiv
       	    if ( array_key_exists($ct['sel_opt']['show_secondary_trade_val'], $ct['conf']['assets']['BTC']['pair']) ) {
            $thres_dec = $ct['gen']->thres_dec($secondary_holdings_val_result, 'u', 'fiat'); // Units mode
    		}
    		// Crypto
    		else {
            $thres_dec = $ct['gen']->thres_dec($secondary_holdings_val_result, 'u', 'crypto'); // Units mode
    		}

		}
		
		if ( $secondary_holdings_val_result >= $ct['min_crypto_val_test'] ) {
  		echo '<div class="crypto_worth private_data">(' . $ct['var']->num_pretty($secondary_holdings_val_result, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . ' '.strtoupper($ct['sel_opt']['show_secondary_trade_val']).')</div>';
  		}
  		
  }
  

?>

</td>




<td class='data border_rb blue' style='white-space: nowrap;'>



<?php

$thres_dec = $ct['gen']->thres_dec($asset_prim_currency_worth_raw, 'u', 'fiat'); // Units mode
echo '<span class="private_data ' . ( $purchase_price >= $ct['min_fiat_val_test'] && $lvrg_level >= 2 && $sel_mrgntyp == 'short' ? 'short">★ ' : 'blue">' ) . '<span class="blue">' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . '</span><span class="app_sort_filter blue">' . $ct['var']->num_pretty($asset_prim_currency_worth_raw, $thres_dec['max_dec'], false, $thres_dec['min_dec']) . '</span></span>';

  if ( $purchase_price >= $ct['min_fiat_val_test'] && $lvrg_level >= 2 ) {

  $asset_worth_inc_lvrg = $asset_prim_currency_worth_raw + $only_lvrg_gain_loss;
  
  echo ' <span class="extra_data private_data">(' . $lvrg_level . 'x ' . $sel_mrgntyp . ')</span>';

  $thres_dec = $ct['gen']->thres_dec($gain_loss, 'u', 'fiat'); // Units mode
  // Here we parse out negative symbols
  $parsed_gain_loss = preg_replace("/-/", "-" . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ], number_format($gain_loss, $thres_dec['max_dec'], '.', ',' ) );
  

  $thres_dec = $ct['gen']->thres_dec($inc_lvrg_gain_loss, 'u', 'fiat'); // Units mode
  $parsed_inc_lvrg_gain_loss = preg_replace("/-/", "-" . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ], number_format($inc_lvrg_gain_loss, $thres_dec['max_dec'], '.', ',' ) );
  
  
  $thres_dec = $ct['gen']->thres_dec($only_lvrg_gain_loss, 'u', 'fiat'); // Units mode
  $parsed_only_lvrg_gain_loss = preg_replace("/-/", "-" . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ], number_format($only_lvrg_gain_loss, $thres_dec['max_dec'], '.', ',' ) );
  
  
  $thres_dec = $ct['gen']->thres_dec($asset_worth_inc_lvrg, 'u', 'fiat'); // Units mode
  // Here we can go negative 'total worth' with the margin leverage (unlike with the margin deposit)
  // We only want a negative sign here in the UI for 'total worth' clarity (if applicable), NEVER a plus sign
  // (plus sign would indicate a gain, NOT 'total worth')
  $parsed_asset_worth_inc_lvrg = preg_replace("/-/", "", number_format($asset_worth_inc_lvrg, $thres_dec['max_dec'], '.', ',' ) );
  
  
  $thres_dec = $ct['gen']->thres_dec($asset_prim_currency_worth_raw, 'u', 'fiat'); // Units mode
  // Pretty format, but no need to parse out anything here
  $pretty_asset_prim_currency_worth_raw = number_format($asset_prim_currency_worth_raw, $thres_dec['max_dec'], '.', ',' );
  
  $thres_dec = $ct['gen']->thres_dec($inc_lvrg_gain_loss_percent, 'p'); // Percentage mode
  $pretty_lvrg_gain_loss_percent = number_format( $inc_lvrg_gain_loss_percent, $thres_dec['max_dec'], '.', ',' );
  
  
  		// Formatting
  		$gain_loss_span_color = ( $gain_loss >= 0 ? 'green' : 'red' );
  		$gain_loss_prim_currency = ( $gain_loss >= 0 ? '+' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] : '' );
  		
		?> 
		<img class='tooltip_style_control lvrg_info' id='<?=$rand_id?>_lvrg' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' />
	 <script>
	
			var lvrg_content = '<h5 class="yellow tooltip_title"><?=$lvrg_level?>x <?=ucfirst($sel_mrgntyp)?> For <?=$asset_name?> (<?=$asset_symb?>)</h5>'
			
			+'<p class="coin_info"><span class="bitcoin">Deposit (1x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_prim_currency?><?=$parsed_gain_loss?></span> (<?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=$pretty_asset_prim_currency_worth_raw?>)</p>'
			
			+'<p class="coin_info"><span class="bitcoin">Margin (<?=($lvrg_level - 1)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_prim_currency?><?=$parsed_only_lvrg_gain_loss?></span></p>'
			
			+'<p class="coin_info"><span class="bitcoin">Total (<?=($lvrg_level)?>x):</span> <span class="<?=$gain_loss_span_color?>"><?=$gain_loss_prim_currency?><?=$parsed_inc_lvrg_gain_loss?> / <?=( $gain_loss >= 0 ? '+' : '' )?><?=$pretty_lvrg_gain_loss_percent?>%</span> (<?=( $asset_worth_inc_lvrg >= 0 ? '' : '-' )?><?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?=$parsed_asset_worth_inc_lvrg?>)</p>'
			
				
			+'<p class="coin_info"><span class="bitcoin"> </span></p>';
		
		
			$('#<?=$rand_id?>_lvrg').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: lvrg_content,
			css: balloon_css()
			});
		
		 </script>
		 
		<?php
  		}

?>


</td>



  
</tr>

<!-- Coin data row END -->



