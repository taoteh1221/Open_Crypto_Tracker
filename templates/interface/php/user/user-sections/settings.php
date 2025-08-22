<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


?>

	
<h2 class='bitcoin page_title'>User Settings</h2>
	            

<div class='full_width_wrapper'>
			
	
	<p style='margin-top: 0.5em; margin-bottom: 2em;'>
	
	<?=$ct['gen']->start_page_html('settings')?>
			
			&nbsp; &nbsp; <span class='blue' style='font-weight: bold;'>App Reload:</span> <select title='Auto-Refresh MAY NOT WORK properly on mobile devices (phone / laptop / tablet / etc), or inactive tabs.' class='browser-default custom-select select_auto_refresh' name='select_auto_refresh' onchange='
			 auto_reload(this);
			 '>
				<option value='0'> Manually </option>
				<option value='300'> 5 Minutes </option>
				<option value='600'> 10 Minutes </option>
				<option value='900'> 15 Minutes </option>
				<option value='1800'> 30 Minutes </option>
			</select> 
			
			&nbsp; <span class='reload_notice red'></span>		
		
		
	   </p>		
			
			
			<?php
			if ( isset($price_alert_type_text) && $price_alert_type_text != '' && $ct['conf']['charts_alerts']['price_alert_threshold'] > 0 ) {
          ?>
          	<p class='settings_sections'><b><?=$price_alert_type_text?> price alerts</b> are <i>enabled</i> in the configuration file (upon <?=$ct['conf']['charts_alerts']['price_alert_threshold']?>% or more <?=strtoupper($ct['default_bitcoin_primary_currency_pair'])?> price change<?=( $ct['conf']['charts_alerts']['price_alert_frequency_maximum'] > 0 ? ' / max every ' . $ct['conf']['charts_alerts']['price_alert_frequency_maximum'] . ' hours per-alert' : '' )?><?=( $ct['conf']['charts_alerts']['price_alert_minimum_volume'] > 0 ? ' / ' . $ct['opt_conf']['conversion_currency_symbols'][ $ct['default_bitcoin_primary_currency_pair'] ] . number_format($ct['conf']['charts_alerts']['price_alert_minimum_volume'], 0, '.', ',') . ' minumum volume filter enabled' : '' )?><?=( $ct['conf']['charts_alerts']['price_alert_fixed_reset'] > 0 ? ' / comparison price fixed-reset after ' . $ct['conf']['charts_alerts']['price_alert_fixed_reset'] . ' days' : '' )?>). 
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job / scheduled task on your app server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( isset($price_change_conf_alert) && $price_change_conf_alert != '' ? '<br />' . $price_change_conf_alert : '' )?>
          		
          		<?php
          		if ( preg_match("/text/i", $price_alert_type_text) && $ct['conf']['comms']['smtp_login'] == '' && $ct['conf']['comms']['smtp_server'] == '' && $ct['conf']['ext_apis']['textbelt_api_key'] == '' && $ct['conf']['ext_apis']['textlocal_api_key'] == '' ) {
          		?>
          		<br />
          		<span class='bitcoin'>Email-to-mobile-text service gateways *MAY* work more reliably (not filter out your messages) <i>if you enable SMTP email sending</i>.</span>
          		<?php
          		}
          		?>
          	
          	</p>  
                        
			<?php
			}
			if ( $ct['activate_proxies'] == 'on' && is_array($ct['conf']['proxy']['proxy_list']) && sizeof($ct['conf']['proxy']['proxy_list']) > 0 ) {
			?>
          <p class='settings_sections'><b><?=( trim($ct['conf']['proxy']['proxy_login']) != '' ? 'Password-based' : 'IP-athenticated' )?> proxy mode</b> is <i>enabled</i> in the configuration file for API connections (<?=sizeof($ct['conf']['proxy']['proxy_list'])?> proxies randomly used<?=( $ct['conf']['proxy']['proxy_alert_channels'] != 'off' ? ' / proxy alerts enabled for ' . $ct['conf']['proxy']['proxy_alert_channels'] . ' alert method(s), every ' . $ct['conf']['proxy']['proxy_alert_frequency_maximum'] . ' hours max per-proxy at ' . $ct['conf']['proxy']['proxy_alert_runtime'] . ' runtimes / ' .$ct['conf']['proxy']['proxy_alert_checkup_ok']. ' sending proxy alerts on proxy checks that tested OK after acting up' : '' )?>). 
          	
          		<?=( isset($proxy_conf_alert) && $proxy_conf_alert != '' ? '<br />' . $proxy_conf_alert : '' )?>
          	
          	</p>      
          <?php
          }
			if ( $ct['conf']['comms']['logs_email'] > 0 && trim($ct['conf']['comms']['from_email']) != '' && trim($ct['conf']['comms']['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Emailing logs</b> is <i>enabled</i> in the configuration file (sent out every <?=$ct['conf']['comms']['logs_email']?> days, log files purged every <?=$ct['conf']['power']['logs_purge']?> days).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job / scheduled task on your app server</a>, or this feature will not work RELIABLY.</i> 
          	
          		<?=( isset($logs_conf_alert) && $logs_conf_alert != '' ? '<br />' . $logs_conf_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' && $ct['conf']['power']['backup_archive_frequency'] > 0 && trim($ct['conf']['comms']['from_email']) != '' && trim($ct['conf']['comms']['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Chart Backups</b> are <i>enabled</i> in the configuration file (run every <?=$ct['conf']['power']['backup_archive_frequency']?> days, purged after <?=$ct['conf']['power']['backup_archive_delete_old']?> days old).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job / scheduled task on your app server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( isset($backuparchive_conf_alert) && $backuparchive_conf_alert != '' ? '<br />' . $backuparchive_conf_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $ct['conf']['comms']['smtp_login'] != '' && $ct['conf']['comms']['smtp_server'] != '' ) {
          ?>
          	<p class='settings_sections'><b>SMTP email sending</b> (by account login) is <i>enabled</i> in the configuration file.
          	
          		<?=( isset($smtp_conf_alert) && $smtp_conf_alert != '' ? '<br />' . $smtp_conf_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $ct['conf']['power']['debug_mode'] != 'off' ) {
          ?>
          	<p class='settings_sections'><b>Debug Mode</b> is <i>enabled</i> in the configuration file.
          	
          		<br /><span class='bitcoin'>Debug Mode: <?=$ct['conf']['power']['debug_mode']?></span>
          	
          	</p>  
                        
			<?php
			}
			?>
			
			
			<p class='settings_sections'>
			    
			    <b>Theme:</b> 
			    
			    <select class='browser-default custom-select' onchange='
			    $("#theme_selected").val(this.value);
                   red_save_button();
			    '>
				<option value='dark' <?=( $ct['sel_opt']['theme_selected'] == 'dark' ? ' selected ' : '' )?>> Dark </option>
				<option value='light' <?=( $ct['sel_opt']['theme_selected'] == 'light' ? ' selected ' : '' )?>> Light </option>
			    </select>
			    
			</p>
			
			
			
			<p class='settings_sections'>
			    
			    <b>Sort Portfolio Data By:</b> 
			    
			    <select class='browser-default custom-select' id='sorted_by_col' onchange='
			    localStorage.setItem(folio_sorting_storage,  this.value + "|" + $("#sorted_direction").val() );
                   local_storage_saved_notice("Portfolio Sort Order");
			    '>
				<option value='0'> Rank </option>
				<option value='1'> Asset Name </option>
				<option value='2'> Unit Value </option>
				<option value='3'> Trade Value </option>
				<option value='4'> Market </option>
				<option value='5'> Exchange </option>
				<option value='6'> 24hr Volume </option>
				<option value='7'> Holdings </option>
				<option value='8'> Ticker </option>
				<option value='9'> Holdings Value </option>
				<option value='10'> (in <?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?>) </option>
			    </select> 
			    
			     <select class='browser-default custom-select' id='sorted_direction' onchange='
			    localStorage.setItem(folio_sorting_storage,  $("#sorted_by_col").val() + "|" + this.value );
                   local_storage_saved_notice("Portfolio Sort Order");
			    '>
				<option value='0'> Ascending </option>
				<option value='1'> Decending </option>
			    </select>
			    
			</p>
			
			
			<div id='sorting_alert' class='bitcoin_dotted bitcoin'></div>
				    
		 <script>
		 
		 $('#sorted_by_col').val(sorted_by_col);
		 $('#sorted_direction').val(sorted_direction);
		 

				    if ( localStorage.getItem(priv_toggle_storage) == 'on' ) {
				    $("#sorting_alert").html("Sorting by the Holdings / Holdings Value / Subtotal columns is currently disabled, as Privacy Mode is turned ON.");
				    $("#sorting_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#sorting_alert").html("");
				    $("#sorting_alert").hide(250, "linear"); // 0.25 seconds
				    }
		
		 </script>
			    
			    
			
			<?php
			if ( is_array($ct['conf']['assets']) ) {
			    
			    ?>
			    
			<p class='settings_sections'>
			    
			    <b>Primary Currency Market:</b> 
			    

					BTC / <select class='browser-default custom-select' onchange='
					
					 btc_prim_currency = this.value;
					 prim_currency_mrkt = $("#" + btc_prim_currency + "btc_currency_pairs").val();
					 currency_selected_mrkt = $("#" + btc_prim_currency + "BTC_pairs option:selected").val();
					 currency_selected_mrkt_standalone = $("#" + btc_prim_currency + "btc_currency_pairs option:selected").val();
					
				    
				    exchange_name_ui = $("#" + btc_prim_currency + "btc_currency_pairs option:selected").text();
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    exchange_name = exchange_name.replace(/coingecko.com/gi, "coingecko");
				    
				    exchange_name_check = exchange_name.replace(" ", "_");
				    
				    if ( limited_apis.indexOf(exchange_name_check) != -1 ) { // MSIE-compatible
				    $("#prim_currency_mrkts_alert").html("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating enough API requests into one single call per session). It is recommended to use a different marketplace IF FEASIBLE, as there MAY be occasional API data retrieval issues with some BTC / " + btc_prim_currency.toUpperCase() + " marketplaces like " + exchange_name_ui + ".<br /><br />If you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " marketplace (or your other crypto apps get " + exchange_name_ui + " data requests refused), try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#prim_currency_mrkts_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#prim_currency_mrkts_alert").html("");
				    $("#prim_currency_mrkts_alert").hide(250, "linear"); // 0.25 seconds
				    }
				    
				    
				    $("#btc_prim_currency").val( btc_prim_currency );
				    
				    $("#prim_currency_mrkt_id_lists").children().hide(); 
				    $("#" + btc_prim_currency + "btc_currency_pairs").show(); 
				    $("#prim_currency_mrkt_id").val( currency_selected_mrkt_standalone );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( document.getElementById("standalone_prim_currency_enabled").checked == false ) {
				    
				    $("#btc_pair").val( btc_prim_currency );
				    
				    $("#btc_mrkt_lists").children().hide(); 
				    $("#" + btc_prim_currency + "BTC_pairs").show(); 
				    $("#btc_mrkt").val( currency_selected_mrkt );
				    
				    $("#btc_mrkt").val( prim_currency_mrkt ); // Set hidden field var
				    $("#" + btc_prim_currency + "BTC_pairs").val( prim_currency_mrkt ); // Set selected drop down choice
				    
				    $("#prim_currency_mrkt").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    
				    }
				    else {
				    $("#prim_currency_mrkt_standalone").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    }
				    
                        red_save_button();
				    
				    '>
					
					<?php
					
					$exchange_field_id = $ct['asset']->btc_mrkt($ct['conf']['currency']['bitcoin_primary_currency_exchange']);
					
					foreach (  $ct['conf']['assets']['BTC']['pair'] as $pair_key => $pair_id ) {
					?>
					<option value='<?=$pair_key?>' <?=( $ct['conf']['currency']['bitcoin_primary_currency_pair'] == $pair_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pair_key))?> </option>
					<?php
					
									
									foreach ( $ct['conf']['assets']['BTC']['pair'][$pair_key] as $mrkt_key => $mrkt_id ) {
									$loop2 = $loop2 + 1;
									$btc_mrkt_list[$pair_key] .= "\n<option value='".$loop2."'" . ( $exchange_field_id == $loop2 ? ' selected ' : '' ) . ">" . $ct['gen']->key_to_name($mrkt_key) . "</option>\n";
									}
									$loop2 = NULL;
							
					}
					?>
				    </select> 
				    
				    
				     @ 
				    
				    <input type='hidden' id='btc_prim_currency' name='btc_prim_currency' value='<?=$ct['conf']['currency']['bitcoin_primary_currency_pair']?>' />
				     
				    <input type='hidden' id='prim_currency_mrkt_id' name='prim_currency_mrkt_id' value='<?=$exchange_field_id?>' />
				     
				     
				     <span id='prim_currency_mrkt_id_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pair: <?=$ct['conf']['currency']['bitcoin_primary_currency_pair']?> -->
				     <!-- prim_currency_mrkt_standalone[1]: <?=$ct['sel_opt']['prim_currency_mrkt_standalone'][1]?> -->
				     <!-- prim_currency_mrkt_standalone[0]: <?=$ct['sel_opt']['prim_currency_mrkt_standalone'][0]?> -->
				     <!-- bitcoin_primary_currency_exchange: <?=$ct['conf']['currency']['bitcoin_primary_currency_exchange']?> -->
				    <?php
				    
				    foreach ( $btc_mrkt_list as $key => $value ) {
				    ?>
				    
				    <select class='browser-default custom-select' onchange='
				    
				    exchange_name_ui = $(this).find("option:selected").text();
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    exchange_name = exchange_name.replace(/coingecko.com/gi, "coingecko");
				    
				    exchange_name_check = exchange_name.replace(" ", "_");
				    
				    btc_prim_currency = $("#btc_prim_currency").val();
				    
				    prim_currency_mrkt = this.value;
				    
				    if ( limited_apis.indexOf(exchange_name_check) != -1 ) { // MSIE-compatible
				    $("#prim_currency_mrkts_alert").html("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating enough API requests into one single call per session). It is recommended to use a different marketplace IF FEASIBLE, as there MAY be occasional API data retrieval issues with some BTC / " + btc_prim_currency.toUpperCase() + " marketplaces like " + exchange_name_ui + ".<br /><br />If you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " marketplace (or your other crypto apps get " + exchange_name_ui + " data requests refused), try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#prim_currency_mrkts_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#prim_currency_mrkts_alert").html("");
				    $("#prim_currency_mrkts_alert").hide(250, "linear"); // 0.25 seconds
				    }
					 
				    $("#prim_currency_mrkt_id").val( prim_currency_mrkt );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( document.getElementById("standalone_prim_currency_enabled").checked == false ) {
				    $("#btc_mrkt").val( prim_currency_mrkt ); // Set hidden field var
				    $("#" + btc_prim_currency + "BTC_pairs").val( prim_currency_mrkt ); // Set selected drop down choice
				    $("#prim_currency_mrkt").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    }
				    else {
				    $("#prim_currency_mrkt_standalone").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    }
				    
                        red_save_button();
				    
				    ' id='<?=$key?>btc_currency_pairs' style='display: <?=( $ct['conf']['currency']['bitcoin_primary_currency_pair'] == $key ? 'inline' : 'none' )?>;'>
				    
				    <?=$btc_mrkt_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $btc_mrkt_list = NULL;
				    ?>
				    
				    </span> <img class='tooltip_style_control' id='currency_info' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> <input type='checkbox' id='standalone_prim_currency_enabled' name='standalone_prim_currency_enabled' value='1' onchange='
				    
				    btc_prim_currency = $("#btc_prim_currency").val() ? $("#btc_prim_currency").val() : "<?=$ct['conf']['currency']['bitcoin_primary_currency_pair']?>";
				    prim_currency_mrkt = $("#prim_currency_mrkt_id").val() ? $("#prim_currency_mrkt_id").val() : <?=$ct['asset']->btc_mrkt($ct['conf']['currency']['bitcoin_primary_currency_exchange'])?>;
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( this.checked == false ) {
				    
				    $("#btc_mrkt_lists").children().hide(); 
				    $("#" + btc_prim_currency + "BTC_pairs").show(); 
				    $("#btc_mrkt").val( $("#" + btc_prim_currency + "BTC_pairs option:selected").val() );
				    
				    $("#btc_pair").val( btc_prim_currency );
				    	
				    $("#btc_mrkt").val( prim_currency_mrkt ); // Set hidden field var
				    $("#" + btc_prim_currency + "BTC_pairs").val( prim_currency_mrkt ); // Set selected drop down choice
				    
				    $("#prim_currency_mrkt").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    
				    $("#prim_currency_mrkt_standalone").val("");
				    
				    }
				    else {
				    $("#prim_currency_mrkt_standalone").val( btc_prim_currency + "|" + prim_currency_mrkt );
				    }
				    
                        red_save_button();
				    
				    ' <?=( is_array($ct['sel_opt']['prim_currency_mrkt_standalone']) ? 'checked' : '' )?> /> Stand-Alone Mode (<i>WON'T automatically change</i> Bitcoin market on "Update" page)
				    
				    <div id='prim_currency_mrkts_alert' class='bitcoin_dotted bitcoin'></div>
				    
 <script>
	
			var currency_content = '<h5 class="align_center yellow tooltip_title">Primary Currency Market</h5>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">The Primary Currency Market setting allows you to change your default primary currency (conversion) for the portfolio interface (the price charts / price alerts primary currency market <i>must be changed separately in the Admin Config GENERAL section</i>).</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">Additionally, if you check off "Stand-Alone Mode", your chosen Bitcoin market on the "Update" page <i>will NOT be automatically changed to match your chosen Primary Currency Market on this "Settings" page</i>. This is useful if you\'d like to browse through different Bitcoin markets, BUT don\'t want your default primary currency to change in the app.</p>'
			
			+'<p> </p>';
		
		
			$('#currency_info').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: currency_content,
			css: balloon_css()
			});
		
		
			// On page load / reload
			
			var settings_tab_prim_btc_exchange = document.getElementById("<?=$ct['conf']['currency']['bitcoin_primary_currency_pair']?>btc_currency_pairs");

			exchange_name_ui = settings_tab_prim_btc_exchange.options[settings_tab_prim_btc_exchange.selectedIndex].text;

			exchange_name = exchange_name_ui.toLowerCase();
				    
		    exchange_name = exchange_name.replace(/coingecko.com/gi, "coingecko");
				    
			exchange_name_check = exchange_name.replace(" ", "_");

			btc_prim_currency = bitcoin_primary_currency_pair.toLowerCase();
			

				    if ( limited_apis.indexOf(exchange_name_check) != -1 ) { // MSIE-compatible
				    $('#prim_currency_mrkts_alert').html("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating enough API requests into one single call per session). It is recommended to use a different marketplace IF FEASIBLE, as there MAY be occasional API data retrieval issues with some BTC / " + btc_prim_currency.toUpperCase() + " marketplaces like " + exchange_name_ui + ".<br /><br />If you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " marketplace (or your other crypto apps get " + exchange_name_ui + " data requests refused), try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#prim_currency_mrkts_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $('#prim_currency_mrkts_alert').html("");
				    $("#prim_currency_mrkts_alert").hide(250, "linear"); // 0.25 seconds
				    }
		
		
		
		 </script>


			</p>
			    
			<?php
			}
			?>
			    
			    
			    
			<p class='settings_sections'>
				
				<b>Price Change Visual / Audio Alerts:</b>
			     
			    <select class='browser-default custom-select' name='alert_percent' id='alert_percent' onchange='
			    
			    update_alert_percent();
			    
			    if ( this.value == "yes" ) {
			    $("#alert_source").css("display", "inline");
			    $("#percent_change_amnt").css("display", "inline");
			    $("#percent_change_filter").css("display", "inline");
			    $("#percent_change_time").css("display", "inline");
			    $("#percent_change_alert_type").css("display", "inline");
			    }
			    else {
			    $("#alert_source").css("display", "none");
			    $("#percent_change_amnt").css("display", "none");
			    $("#percent_change_filter").css("display", "none");
			    $("#percent_change_time").css("display", "none");
			    $("#percent_change_alert_type").css("display", "none");
			    }
			    
			    '>
			    <option value='no' <?=( !$ct['sel_opt']['alert_percent'] ? ' selected ' : '' )?>> No </option>
			    <option value='yes' <?=( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ? ' selected ' : '' )?>> Yes </option> <!-- Backwards compatibility (dynamic PHP reset, if user data is not the current feature set number of array values) -->
			    </select>
			     
			     
			    <select class='browser-default custom-select' name='alert_source' id='alert_source' onchange='update_alert_percent();'>
			    <option value='coingecko' <?=( $ct['conf']['gen']['primary_marketcap_site'] == 'coingecko' ? ' selected ' : '' )?>> Coingecko.com </option>
			    <option value='coinmarketcap' <?=( $ct['conf']['gen']['primary_marketcap_site'] == 'coinmarketcap' ? ' selected ' : '' )?>> Coinmarketcap.com </option>
			    </select>  
			    
			    
			    <select class='browser-default custom-select' name='percent_change_amnt' id='percent_change_amnt' onchange='update_alert_percent();'>
			    <option value='5' <?=( $ct['sel_opt']['alert_percent'][1] == 5 ? ' selected ' : '' )?>> 5% </option>
			    <option value='10' <?=( $ct['sel_opt']['alert_percent'][1] == 10 ? ' selected ' : '' )?>> 10% </option>
			    <option value='15' <?=( $ct['sel_opt']['alert_percent'][1] == 15 ? ' selected ' : '' )?>> 15% </option>
			    <option value='20' <?=( $ct['sel_opt']['alert_percent'][1] == 20 ? ' selected ' : '' )?>> 20% </option>
			    <option value='25' <?=( $ct['sel_opt']['alert_percent'][1] == 25 ? ' selected ' : '' )?>> 25% </option>
			    <option value='30' <?=( $ct['sel_opt']['alert_percent'][1] == 30 ? ' selected ' : '' )?>> 30% </option>
			    <option value='35' <?=( $ct['sel_opt']['alert_percent'][1] == 35 ? ' selected ' : '' )?>> 35% </option>
			    <option value='40' <?=( $ct['sel_opt']['alert_percent'][1] == 40 ? ' selected ' : '' )?>> 40% </option>
			    <option value='45' <?=( $ct['sel_opt']['alert_percent'][1] == 45 ? ' selected ' : '' )?>> 45% </option>
			    <option value='50' <?=( $ct['sel_opt']['alert_percent'][1] == 50 ? ' selected ' : '' )?>> 50% </option>
			    </select> 
			     
			     
			    <select class='browser-default custom-select' name='percent_change_filter' id='percent_change_filter' onchange='update_alert_percent();'>
			    <option value='both' <?=( $ct['sel_opt']['alert_percent'][2] == 'both' ? ' selected ' : '' )?>> Gain or Loss </option>
			    <option value='gain' <?=( $ct['sel_opt']['alert_percent'][2] == 'gain' ? ' selected ' : '' )?>> Gain </option>
			    <option value='loss' <?=( $ct['sel_opt']['alert_percent'][2] == 'loss' ? ' selected ' : '' )?>> Loss </option>
			    </select>  
			     
			     
			    <select class='browser-default custom-select' name='percent_change_time' id='percent_change_time' onchange='update_alert_percent();'>
			    <option value='1hour' <?=( $ct['sel_opt']['alert_percent'][3] == '1hour' ? ' selected ' : '' )?>> 1 Hour </option>
			    <option value='24hour' <?=( $ct['sel_opt']['alert_percent'][3] == '24hour' ? ' selected ' : '' )?>> 24 Hour </option>
			    <option value='7day' <?=( $ct['sel_opt']['alert_percent'][3] == '7day' ? ' selected ' : '' )?>> 7 Day </option>
			    </select>  
			     
			     
			    <select class='browser-default custom-select' name='percent_change_alert_type' id='percent_change_alert_type' onchange='
			    
			    update_alert_percent();

			    if ( this.value == "visual_audio" ) {
				 $("#percent_change_alert_type_alert").html("For security, some browsers may require occasional interaction to allow media auto-play (clicking on page etc), or changes to per-site auto-play preferences. <br /><br />Chrome users can open chrome://settings/content/sound, enable \"Sites can play sound\", and under \"Allowed to play sound\" add the address: <?=$ct['app_host']?>");
				 $("#percent_change_alert_type_alert").show(250, "linear"); // 0.25 seconds
			    }
			    else {
				 $("#percent_change_alert_type_alert").html("");
				 $("#percent_change_alert_type_alert").hide(250, "linear"); // 0.25 seconds
			    }
			    
			    '>
			    <option value='visual_only' <?=( $ct['sel_opt']['alert_percent'][4] == 'visual_only' ? ' selected ' : '' )?>> Visual Only </option>
			    <option value='visual_audio' <?=( $ct['sel_opt']['alert_percent'][4] == 'visual_audio' ? ' selected ' : '' )?>> Visual and Audio </option>
			    </select>
			
			</p>
			
			
			<div id='percent_change_alert_type_alert' class='bitcoin_dotted bitcoin'></div>
				    
		 <script>

					if ( $("#percent_change_alert_type").val() == "visual_audio" ) {
				    $("#percent_change_alert_type_alert").html("For security, some browsers may require occasional interaction to allow media auto-play (clicking on page etc), or changes to per-site auto-play preferences. <br /><br />Chrome users can open chrome://settings/content/sound, enable \"Sites can play sound\", and under \"Allowed to play sound\" add the address: <?=$ct['app_host']?>");
				    $("#percent_change_alert_type_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#percent_change_alert_type_alert").html("");
				    $("#percent_change_alert_type_alert").hide(250, "linear"); // 0.25 seconds
				    }
		
		 </script>
				   
			    
			    
			<p class='settings_sections'>
				
				<b>Show Crypto Value Of ENTIRE Portfolio In:</b> &nbsp;  
			     
			<?php
			$loop = 0;
			foreach ( $ct['opt_conf']['crypto_pair'] as $key => $unused ) {
			?>
			<?=( $loop > 0 ? ' &nbsp;/&nbsp; ' : '' )?> 
			<input type='checkbox' value='<?=$key?>' onchange='crypto_val_toggle(this);' <?=( in_array("[".$key."]", $ct['sel_opt']['show_crypto_val']) ? 'checked' : '' )?> /> <?=strtoupper($key)?> 
			<?php
			$loop = $loop + 1;
			}
			?> 
			     <img class='tooltip_style_control' id="setting_crypto_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
         </p>
			

<script>


		
			var setting_crypto_val_content = '<h5 class="yellow tooltip_title">Show Crypto Value Of ENTIRE Portfolio In</h5>'
			
			+'<p class="coin_info" style=" white-space: normal;">Shows the value of your ENTIRE portfolio, in cryptocurrencies selected here, at the bottom of the Portfolio page.</p>'
			
			+'<p class="coin_info" style=" white-space: normal;">You can edit this list (except for BTC) with the "crypto_pair" setting, in the Admin Config POWER USER section.</p>'
			
			+'<p class="coin_info red_bright" style=" white-space: normal;">It\'s HIGHLY RECOMMENDED to only add Bluechip / relatively lower risk crypto assets here! Remember, the <a href="https://www.google.com/search?q=barbell+portfolio+strategy" target="_blank">Barbell Portfolio Strategy</a> works VERY WELL for MANY investors that use it!</p>';
			
		
			$('#setting_crypto_val').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: setting_crypto_val_content,
			css: balloon_css()
			});
		


</script>


				   
			    
			    
			<p class='settings_sections'>
				
				<b>Show Secondary Trade / Holdings Values In:</b> &nbsp;  
			
			<select class='browser-default custom-select' onchange='
			
			 document.getElementById("show_secondary_trade_val").value = this.value;
			 
			 red_save_button();
			
			'>
			<option value=''> None </option>
			<?php
			foreach ( $ct['opt_conf']['crypto_pair'] as $key => $unused ) {
			?>
			<option value='<?=$key?>' <?=( $ct['sel_opt']['show_secondary_trade_val'] == $key ? 'selected' : '' )?>> <?=strtoupper($key)?> </option>
			<?php
			}
			?> 
			</select>
			
			     <img class='tooltip_style_control' id="setting_secondary_trade_val" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
			     
         </p>
			

<script>


		
			var setting_secondary_trade_val_content = '<h5 class="yellow tooltip_title">Show Secondary Trade / Holdings Values In</h5>'
			
			+'<p class="coin_info" style=" white-space: normal;">Choose showing secondary trade / holdings values in another asset, see example screenshot below:</p>'
			
			+'<p class="coin_info" style=" white-space: normal;"><img src="templates/interface/media/images/auto-preloaded/secondary-value-example.png" width="590" title="Secondary Trade / Holdings Value" /></p>'
			
			+'<p class="coin_info" style=" white-space: normal;">You can edit this list (except for BTC) with the "crypto_pair" setting, in the Admin Config POWER USER section.</p>';
			
		
			$('#setting_secondary_trade_val').balloon({
			html: true,
			position: "top",
  			classname: 'balloon-tooltips',
			contents: setting_secondary_trade_val_content,
			css: balloon_css()
			});
		


</script>


			
			
         <p class='settings_sections'>
         
         <b>Use cookies to save data:</b> <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
         
         if ( this.checked == true ) {
			document.getElementById("use_cookies").value = "1";
         }
         else {
			document.getElementById("use_cookies").value = "";
         }
         
         red_save_button();
         
         ' <?php echo ( isset($_COOKIE['coin_amnts']) ? 'checked' : ''); ?> /> <span class='bitcoin'>(un-checking this box <i>deletes ALL previously-saved cookie data <u>permanently</u></i>)</span>
         
         </p>
			
                        
                         
			<?php
			if ( is_array($ct['sel_opt']['alert_percent']) && sizeof($ct['sel_opt']['alert_percent']) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
			?>
			
			<style>
			#alert_source, #percent_change_amnt, #percent_change_filter, #percent_change_time, #percent_change_alert_type {
			display: inline;
			}
			</style>
			
			<?php
			}
			?>

                        
		    
		    
</div> <!-- full_width_wrapper END -->



                        