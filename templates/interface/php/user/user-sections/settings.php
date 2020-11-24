<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_1200px_wrapper'>

			
			<h4 style='display: inline;'>Settings</h4>
				
				
				<span class='red countdown_notice'></span>
			
			
			
			<?php
			if ( $price_alert_type_text != '' && $app_config['comms']['price_alerts_threshold'] > 0 ) {
          ?>
          	<p class='settings_sections'><b><?=$price_alert_type_text?> price alerts</b> are <i>enabled</i> in the configuration file (upon <?=$app_config['comms']['price_alerts_threshold']?>% or more <?=strtoupper($default_btc_primary_currency_pairing)?> price change<?=( $app_config['comms']['price_alerts_freq_max'] > 0 ? ' / max every ' . $app_config['comms']['price_alerts_freq_max'] . ' hours per-alert' : '' )?><?=( $app_config['comms']['price_alerts_min_volume'] > 0 ? ' / ' . $app_config['power_user']['bitcoin_currency_markets'][$default_btc_primary_currency_pairing] . number_format($app_config['comms']['price_alerts_min_volume'], 0, '.', ',') . ' minumum volume filter enabled' : '' )?><?=( $app_config['charts_alerts']['price_alerts_fixed_reset'] > 0 ? ' / comparison price fixed-reset after ' . $app_config['charts_alerts']['price_alerts_fixed_reset'] . ' days' : '' )?>). 
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( $price_change_config_alert != '' ? '<br />' . $price_change_config_alert : '' )?>
          		
          		<?php
          		if ( preg_match("/text/i", $price_alert_type_text) && $app_config['comms']['smtp_login'] == '' && $app_config['comms']['smtp_server'] == '' && $app_config['comms']['textbelt_apikey'] == '' && $app_config['comms']['textlocal_account'] == '' ) {
          		?>
          		<br />
          		<span class='bitcoin'>Email-to-mobile-text service gateways *MAY* work more reliably (not filter out your messages) <i>if you enable SMTP email sending</i>.</span>
          		<?php
          		}
          		?>
          	
          	</p>  
                        
			<?php
			}
			if ( sizeof($app_config['proxy']['proxy_list']) > 0 ) {
			?>
          <p class='settings_sections'><b><?=( trim($app_config['proxy']['proxy_login']) != '' ? 'Password-based' : 'IP-athenticated' )?> proxy mode</b> is <i>enabled</i> in the configuration file for API connections (<?=sizeof($app_config['proxy']['proxy_list'])?> proxies randomly used<?=( $app_config['comms']['proxy_alerts'] != 'off' ? ' / proxy alerts enabled for ' . $app_config['comms']['proxy_alerts'] . ' alert method(s), every ' . $app_config['comms']['proxy_alerts_freq_max'] . ' hours max per-proxy at ' . $app_config['comms']['proxy_alerts_runtime'] . ' runtimes / ' .$app_config['comms']['proxy_alerts_checkup_ok']. ' sending proxy alerts on proxy checks that tested OK after acting up' : '' )?>). 
          	
          		<?=( $proxy_config_alert != '' ? '<br />' . $proxy_config_alert : '' )?>
          	
          	</p>      
          <?php
          }
			if ( $app_config['power_user']['logs_email'] > 0 && trim($app_config['comms']['from_email']) != '' && trim($app_config['comms']['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Emailing logs</b> is <i>enabled</i> in the configuration file (sent out every <?=$app_config['power_user']['logs_email']?> days, log files purged every <?=$app_config['power_user']['logs_purge']?> days).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work RELIABLY.</i> 
          	
          		<?=( $logs_config_alert != '' ? '<br />' . $logs_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $app_config['general']['asset_charts_toggle'] == 'on' && $app_config['power_user']['charts_backup_freq'] > 0 && trim($app_config['comms']['from_email']) != '' && trim($app_config['comms']['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Chart Backups</b> are <i>enabled</i> in the configuration file (run every <?=$app_config['power_user']['charts_backup_freq']?> days, purged after <?=$app_config['power_user']['backup_archive_delete_old']?> days old).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( $backuparchive_config_alert != '' ? '<br />' . $backuparchive_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $app_config['comms']['smtp_login'] != '' && $app_config['comms']['smtp_server'] != '' ) {
          ?>
          	<p class='settings_sections'><b>SMTP email sending</b> (by account login) is <i>enabled</i> in the configuration file.
          	
          		<?=( $smtp_config_alert != '' ? '<br />' . $smtp_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $app_config['developer']['debug_mode'] != 'off' ) {
          ?>
          	<p class='settings_sections'><b>Debug Mode</b> is <i>enabled</i> in the configuration file.
          	
          		<br /><span class='bitcoin'>Debug Mode: <?=$app_config['developer']['debug_mode']?></span>
          	
          	</p>  
                        
			<?php
			}
			?>
			
			
			<p class='settings_sections'>
			    
			    <b>Theme:</b> 
			    
			    <select class='browser-default custom-select' onchange='
			    $("#theme_selected").val(this.value);
			    '>
				<option value='dark' <?=( $theme_selected == 'dark' ? ' selected ' : '' )?>> Dark </option>
				<option value='light' <?=( $theme_selected == 'light' ? ' selected ' : '' )?>> Light </option>
			    </select>
			    
			</p>
			    
			    
			
			<?php
			if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
			    
			    ?>
			    
			<p class='settings_sections'>
			    
			    <b>Primary Currency Market:</b> 
			    

					BTC / <select class='browser-default custom-select' onchange='
					
					 btc_primary_currency = this.value;
					 primary_currency_market = $("#" + btc_primary_currency + "btc_currency_pairs").val();
					 currency_selected_market = $("#" + btc_primary_currency + "BTC_pairs option:selected").val();
					 currency_selected_market_standalone = $("#" + btc_primary_currency + "btc_currency_pairs option:selected").val();
					
				    
				    exchange_name_ui = $("#" + btc_primary_currency + "btc_currency_pairs option:selected").text();
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    exchange_name_check = exchange_name.replace(" ", "_");
				    
				    if ( window.limited_apis.indexOf(exchange_name) != -1 ) { // MSIE-compatible
				    $("#primary_currency_markets_alert").text("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating multiple / different asset price requests into one single call per session).\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
					 else if ( window.preferred_bitcoin_markets[btc_primary_currency] && window.preferred_bitcoin_markets[btc_primary_currency].length > 0 && window.preferred_bitcoin_markets[btc_primary_currency] != exchange_name_check ) {
				    $("#primary_currency_markets_alert").text("It is recommended to use the " + render_names(window.preferred_bitcoin_markets[btc_primary_currency]) + " marketplace, as there MAY be occasional issues with other BTC / " + btc_primary_currency.toUpperCase() + " marketplaces.\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#primary_currency_markets_alert").text("");
				    $("#primary_currency_markets_alert").hide(250, "linear"); // 0.25 seconds
				    }
				    
				    
				    $("#btc_primary_currency").val( btc_primary_currency );
				    
				    $("#primary_currency_market_id_lists").children().hide(); 
				    $("#" + btc_primary_currency + "btc_currency_pairs").show(); 
				    $("#primary_currency_market_id").val( currency_selected_market_standalone );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( document.getElementById("standalone_primary_currency_enabled").checked == false ) {
				    
				    $("#btc_pairing").val( btc_primary_currency );
				    
				    $("#btc_market_lists").children().hide(); 
				    $("#" + btc_primary_currency + "BTC_pairs").show(); 
				    $("#btc_market").val( currency_selected_market );
				    
				    $("#btc_market").val( primary_currency_market ); // Set hidden field var
				    $("#" + btc_primary_currency + "BTC_pairs").val( primary_currency_market ); // Set selected drop down choice
				    
				    }
				    else {
				    $("#primary_currency_market_standalone").val( btc_primary_currency + "|" + primary_currency_market );
				    }
				    
				    '>
					
					<?php
					
					$exchange_field_id = btc_market($app_config['general']['btc_primary_exchange']);
					
					foreach (  $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $pairing_id ) {
					?>
					<option value='<?=$pairing_key?>' <?=( $app_config['general']['btc_primary_currency_pairing'] == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
					
									
									foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing_key] as $market_key => $market_id ) {
									$loop2 = $loop2 + 1;
									$btc_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( $exchange_field_id == $loop2 ? ' selected ' : '' ) . ">" . snake_case_to_name($market_key) . "</option>\n";
									}
									$loop2 = NULL;
							
					}
					?>
				    </select> 
				    
				    
				     @ 
				    
				    <input type='hidden' id='btc_primary_currency' name='btc_primary_currency' value='<?=$app_config['general']['btc_primary_currency_pairing']?>' />
				     
				    <input type='hidden' id='primary_currency_market_id' name='primary_currency_market_id' value='<?=$exchange_field_id?>' />
				     
				     
				     <span id='primary_currency_market_id_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pairing: <?=$app_config['general']['btc_primary_currency_pairing']?> -->
				     <!-- primary_currency_market_standalone[1]: <?=$primary_currency_market_standalone[1]?> -->
				     <!-- primary_currency_market_standalone[0]: <?=$primary_currency_market_standalone[0]?> -->
				     <!-- btc_primary_exchange: <?=$app_config['general']['btc_primary_exchange']?> -->
				    <?php
				    
				    foreach ( $btc_market_list as $key => $value ) {
				    ?>
				    
				    <select class='browser-default custom-select' onchange='
				    
				    exchange_name_ui = $(this).find("option:selected").text();
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    exchange_name_check = exchange_name.replace(" ", "_");
				    
				    btc_primary_currency = $("#btc_primary_currency").val();
					 primary_currency_market = this.value;
				    
				    if ( window.limited_apis.indexOf(exchange_name) != -1 ) { // MSIE-compatible
				    $("#primary_currency_markets_alert").text("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating multiple / different asset price requests into one single call per session).\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
					 else if ( window.preferred_bitcoin_markets[btc_primary_currency] && window.preferred_bitcoin_markets[btc_primary_currency].length > 0 && window.preferred_bitcoin_markets[btc_primary_currency] != exchange_name_check ) {
				    $("#primary_currency_markets_alert").text("It is recommended to use the " + render_names(window.preferred_bitcoin_markets[btc_primary_currency]) + " marketplace, as there MAY be occasional issues with other BTC / " + btc_primary_currency.toUpperCase() + " marketplaces.\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#primary_currency_markets_alert").text("");
				    $("#primary_currency_markets_alert").hide(250, "linear"); // 0.25 seconds
				    }
					 
				    $("#primary_currency_market_id").val( primary_currency_market );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( document.getElementById("standalone_primary_currency_enabled").checked == false ) {
				    $("#btc_market").val( primary_currency_market ); // Set hidden field var
				    $("#" + btc_primary_currency + "BTC_pairs").val( primary_currency_market ); // Set selected drop down choice
				    }
				    else {
				    $("#primary_currency_market_standalone").val( btc_primary_currency + "|" + primary_currency_market );
				    }
				    
				    ' id='<?=$key?>btc_currency_pairs' style='display: <?=( $app_config['general']['btc_primary_currency_pairing'] == $key ? 'inline' : 'none' )?>;'>
				    
				    <?=$btc_market_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $btc_market_list = NULL;
				    ?>
				    
				    </span> <img id='currency_info' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> <input type='checkbox' id='standalone_primary_currency_enabled' name='standalone_primary_currency_enabled' value='1' onchange='
				    
				    btc_primary_currency = $("#btc_primary_currency").val() ? $("#btc_primary_currency").val() : "<?=$app_config['general']['btc_primary_currency_pairing']?>";
				    primary_currency_market = $("#primary_currency_market_id").val() ? $("#primary_currency_market_id").val() : <?=btc_market($app_config['general']['btc_primary_exchange'])?>;
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update" tab, mirroring of settings
				    if ( this.checked == false ) {
				    
				    $("#btc_market_lists").children().hide(); 
				    $("#" + btc_primary_currency + "BTC_pairs").show(); 
				    $("#btc_market").val( $("#" + btc_primary_currency + "BTC_pairs option:selected").val() );
				    
				    $("#btc_pairing").val( btc_primary_currency );
				    	
				    $("#btc_market").val( primary_currency_market ); // Set hidden field var
				    $("#" + btc_primary_currency + "BTC_pairs").val( primary_currency_market ); // Set selected drop down choice
				    
				    $("#primary_currency_market_standalone").val("");
				    
				    }
				    else {
				    $("#primary_currency_market_standalone").val( btc_primary_currency + "|" + primary_currency_market );
				    }
				    
				    
				    ' <?=( sizeof($primary_currency_market_standalone) == 2 ? 'checked' : '' )?> /> Stand-Alone Mode (<i>WON'T automatically change</i> Bitcoin market on "Update" page)
				    
				    <div id='primary_currency_markets_alert' class='bitcoin_dotted bitcoin'></div>
				    
 <script>
	
			var currency_content = '<h5 align="center" class="yellow tooltip_title">Currency Market Setting</h5>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">The Currency Market setting allows you to change your default primary currency (conversion) for the portfolio interface (the charts / price alerts currency market <i>must be changed separately in config.php</i>).</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Additionally, if you check off "Stand-Alone Mode", your chosen Bitcoin market on the "Update" page <i>will NOT be automatically changed to match your chosen Currency Market on the "Settings" page</i>. This is useful if you\'d like to browse through different Bitcoin markets, BUT don\'t want your default primary currency to change in the app.</p>'
			
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#currency_info').balloon({
			html: true,
			position: "right",
			contents: currency_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		
			// On page load / reload
			
			var settings_tab_primary_btc_exchange = document.getElementById("<?=$app_config['general']['btc_primary_currency_pairing']?>btc_currency_pairs");

			exchange_name_ui = settings_tab_primary_btc_exchange.options[settings_tab_primary_btc_exchange.selectedIndex].text;

			exchange_name = exchange_name_ui.toLowerCase();
				    
			exchange_name_check = exchange_name.replace(" ", "_");

			btc_primary_currency = btc_primary_currency_pairing.toLowerCase();
			

				    if ( window.limited_apis.indexOf(exchange_name) != -1 ) { // MSIE-compatible
				    $('#primary_currency_markets_alert').text("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating multiple / different asset price requests into one single call per session).\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
					 else if ( window.preferred_bitcoin_markets[btc_primary_currency] && window.preferred_bitcoin_markets[btc_primary_currency].length > 0 && window.preferred_bitcoin_markets[btc_primary_currency] != exchange_name_check ) {
				    $("#primary_currency_markets_alert").text("It is recommended to use the " + render_names(window.preferred_bitcoin_markets[btc_primary_currency]) + " marketplace, as there MAY be occasional issues with other BTC / " + btc_primary_currency.toUpperCase() + " marketplaces.\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange marketplace, try a different exchange for your preferred primary currency market, and the issue should go away.");
				    $("#primary_currency_markets_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $('#primary_currency_markets_alert').text("");
				    $("#primary_currency_markets_alert").hide(250, "linear"); // 0.25 seconds
				    }
		
		
		
		 </script>


			</p>
			    
			<?php
			}
			?>
			
			
			
			<p class='settings_sections'>
			    
			    <b>Sort Portfolio Data By:</b> 
			    
			    <select class='browser-default custom-select' id='sorted_by_col' onchange='
			    $("#sort_by").val( this.value + "|" + $("#sorted_by_asc_desc").val() );
			    '>
				<option value='0' <?=( $sorted_by_col == 0 ? ' selected ' : '' )?>> Sort </option>
				<option value='1' <?=( $sorted_by_col == 1 ? ' selected ' : '' )?>> Asset Name </option>
				<option value='2' <?=( $sorted_by_col == 2 ? ' selected ' : '' )?>> Unit Value </option>
				<option value='3' <?=( $sorted_by_col == 3 ? ' selected ' : '' )?>> Exchange </option>
				<option value='4' <?=( $sorted_by_col == 4 ? ' selected ' : '' )?>> Trade Value </option>
				<option value='5' <?=( $sorted_by_col == 5 ? ' selected ' : '' )?>> Market </option>
				<option value='6' <?=( $sorted_by_col == 6 ? ' selected ' : '' )?>> 24 Hour Volume </option>
				<option value='7' <?=( $sorted_by_col == 7 ? ' selected ' : '' )?>> Holdings </option>
				<option value='8' <?=( $sorted_by_col == 8 ? ' selected ' : '' )?>> Ticker </option>
				<option value='9' <?=( $sorted_by_col == 9 ? ' selected ' : '' )?>> Holdings Value </option>
				<option value='10' <?=( $sorted_by_col == 10 ? ' selected ' : '' )?>> Subtotal </option>
			    </select> 
			    
			     <select class='browser-default custom-select' id='sorted_by_asc_desc' onchange='
			    $("#sort_by").val( $("#sorted_by_col").val() + "|" + this.value );
			    '>
				<option value='0' <?=( $sorted_by_asc_desc == 0 ? ' selected ' : '' )?>> Ascending </option>
				<option value='1' <?=( $sorted_by_asc_desc == 1 ? ' selected ' : '' )?>> Decending </option>
			    </select>
			    
			</p>
			    
			    
			    
			<p class='settings_sections'>
				
				<b>Price Change Visual / Audio Alerts:</b>
			     
			    <select class='browser-default custom-select' name='alert_percent' id='alert_percent' onchange='
			    if ( this.value == "yes" ) {
			    document.getElementById("alert_source").style.display = "inline";
			    document.getElementById("percent_change_amount").style.display = "inline";
			    document.getElementById("percent_change_filter").style.display = "inline";
			    document.getElementById("percent_change_time").style.display = "inline";
			    document.getElementById("percent_change_alert_type").style.display = "inline";
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("alert_source").style.display = "none";
			    document.getElementById("percent_change_amount").style.display = "none";
			    document.getElementById("percent_change_filter").style.display = "none";
			    document.getElementById("percent_change_time").style.display = "none";
			    document.getElementById("percent_change_alert_type").style.display = "none";
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='no' <?=( !$alert_percent ? ' selected ' : '' )?>> No </option>
			    <option value='yes' <?=( sizeof($alert_percent) > 4 ? ' selected ' : '' )?>> Yes </option> <!-- Backwards compatibility (dynamic PHP reset, if user data is not the current feature set number of array values) -->
			    </select>
			     
			     
			    <select class='browser-default custom-select' name='alert_source' id='alert_source' onchange='update_alert_percent();'>
			    <option value='coingecko' <?=( $alert_percent[0] == 'coingecko' ? ' selected ' : '' )?>> Coingecko.com </option>
			    <option value='coinmarketcap' <?=( $alert_percent[0] == 'coinmarketcap' ? ' selected ' : '' )?>> Coinmarketcap.com </option>
			    </select>  
			    
			    
			    <select class='browser-default custom-select' name='percent_change_amount' id='percent_change_amount' onchange='update_alert_percent();'>
			    <option value='5' <?=( $alert_percent[1] == 5 ? ' selected ' : '' )?>> 5% </option>
			    <option value='10' <?=( $alert_percent[1] == 10 ? ' selected ' : '' )?>> 10% </option>
			    <option value='15' <?=( $alert_percent[1] == 15 ? ' selected ' : '' )?>> 15% </option>
			    <option value='20' <?=( $alert_percent[1] == 20 ? ' selected ' : '' )?>> 20% </option>
			    <option value='25' <?=( $alert_percent[1] == 25 ? ' selected ' : '' )?>> 25% </option>
			    <option value='30' <?=( $alert_percent[1] == 30 ? ' selected ' : '' )?>> 30% </option>
			    <option value='35' <?=( $alert_percent[1] == 35 ? ' selected ' : '' )?>> 35% </option>
			    <option value='40' <?=( $alert_percent[1] == 40 ? ' selected ' : '' )?>> 40% </option>
			    <option value='45' <?=( $alert_percent[1] == 45 ? ' selected ' : '' )?>> 45% </option>
			    <option value='50' <?=( $alert_percent[1] == 50 ? ' selected ' : '' )?>> 50% </option>
			    </select> 
			     
			     
			    <select class='browser-default custom-select' name='percent_change_filter' id='percent_change_filter' onchange='update_alert_percent();'>
			    <option value='both' <?=( $alert_percent[2] == 'both' ? ' selected ' : '' )?>> Gain or Loss </option>
			    <option value='gain' <?=( $alert_percent[2] == 'gain' ? ' selected ' : '' )?>> Gain </option>
			    <option value='loss' <?=( $alert_percent[2] == 'loss' ? ' selected ' : '' )?>> Loss </option>
			    </select>  
			     
			     
			    <select class='browser-default custom-select' name='percent_change_time' id='percent_change_time' onchange='update_alert_percent();'>
			    <option value='1hour' <?=( $alert_percent[3] == '1hour' ? ' selected ' : '' )?>> 1 Hour </option>
			    <option value='24hour' <?=( $alert_percent[3] == '24hour' ? ' selected ' : '' )?>> 24 Hour </option>
			    <option value='7day' <?=( $alert_percent[3] == '7day' ? ' selected ' : '' )?>> 7 Day </option>
			    </select>  
			     
			     
			    <select class='browser-default custom-select' name='percent_change_alert_type' id='percent_change_alert_type' onchange='
			    update_alert_percent();
			    if ( this.value == "visual_audio" ) {
				 $("#percent_change_alert_type_alert").text("For security, some browsers may require occasional interaction to allow media auto-play (clicking on page etc), or changes to per-site auto-play preferences.");
				 $("#percent_change_alert_type_alert").show(250, "linear"); // 0.25 seconds
			    }
			    else {
				 $("#percent_change_alert_type_alert").text("");
				 $("#percent_change_alert_type_alert").hide(250, "linear"); // 0.25 seconds
			    }
			    '>
			    <option value='visual_only' <?=( $alert_percent[4] == 'visual_only' ? ' selected ' : '' )?>> Visual Only </option>
			    <option value='visual_audio' <?=( $alert_percent[4] == 'visual_audio' ? ' selected ' : '' )?>> Visual and Audio </option>
			    </select>
			
			</p>
			
			
			<div id='percent_change_alert_type_alert' class='bitcoin_dotted bitcoin'></div>
				    
		 <script>

					 if ( $("#percent_change_alert_type").val() == "visual_audio" ) {
				    $("#percent_change_alert_type_alert").text("For security, some browsers may require occasional interaction to allow media auto-play (clicking on page etc), or changes to per-site auto-play preferences.");
				    $("#percent_change_alert_type_alert").show(250, "linear"); // 0.25 seconds
				    }
				    else {
				    $("#percent_change_alert_type_alert").text("");
				    $("#percent_change_alert_type_alert").hide(250, "linear"); // 0.25 seconds
				    }
		
		 </script>
				   
			    
			    
			<p class='settings_sections'>
				
				<b>Show Crypto Value Of ENTIRE Portfolio In:</b> &nbsp;  
			     
			<?php
			$loop = 0;
			foreach ( $app_config['power_user']['crypto_pairing'] as $key => $unused ) {
			?>
			<?=( $loop > 0 ? ' &nbsp;/&nbsp; ' : '' )?> 
			<input type='checkbox' value='<?=$key?>' onchange='crypto_value_toggle(this);' <?=( in_array("[".$key."]", $show_crypto_value) ? 'checked' : '' )?> /> <?=strtoupper($key)?> 
			<?php
			$loop = $loop + 1;
			}
			?> 
			     <img id="setting_crypto_value" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
         </p>
			

<script>


		
			var setting_crypto_value_content = '<h5 class="yellow tooltip_title">Show Crypto Value Of ENTIRE Portfolio In</h5>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">Shows the value of your ENTIRE portfolio, in cryptocurrencies selected here, at the bottom of the portfolio page.</p>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;">You can edit this list (except for BTC) with the "crypto_pairing" setting, in the POWER USER section of the admin configuration.</p>'
		
			
			+'<p class="coin_info yellow" style="max-width: 600px; white-space: normal;">*Consult a financial advisor and / or do <i>your own due diligence, to evaluate investment risk / reward</i> of ANY cryptocurrencies, based on THEIR / YOUR OWN determinations before buying. Even AFTER buying ANY cryptocurrency, ALWAYS CONTINUE to do your due diligence, investigating whether you are engaging in trading within acceptable risk levels for your <i>NET</i> worth. ALWAYS consult a financial advisor, if you are unaware of what risks are present. </p>';
			
		
			$('#setting_crypto_value').balloon({
			html: true,
			position: "right",
			contents: setting_crypto_value_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		


</script>


			
			
         <p class='settings_sections'>
         
         <b>Use cookies to save data:</b> <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
         if ( this.checked == true ) {
			document.getElementById("use_cookies").value = "1";
         }
         else {
			document.getElementById("use_cookies").value = "";
			document.getElementById("use_notes").value = "";
			document.getElementById("set_use_notes").checked = false;
         }
         ' <?php echo ( $_COOKIE['coin_amounts'] != '' ? 'checked' : ''); ?> /> <span class='bitcoin'>(un-checking this box <i>deletes ALL previously-saved cookie data <u>permanently</u></i>)</span>
         
         </p>
			
			
			
         <p class='settings_sections'>
         
         <b>Enable trading notes:</b> <input type='checkbox' name='set_use_notes' id='set_use_notes' value='1' onchange='
         if ( this.checked == true ) {
			document.getElementById("set_use_cookies").checked = true;
			document.getElementById("use_cookies").value = "1";
			document.getElementById("use_notes").value = "1";
         }
         else {
			document.getElementById("use_notes").value = "";
         }
         ' <?php echo ( $_COOKIE['notes_reminders'] != '' ? 'checked' : ''); ?> /> <span class='bitcoin'>(requires cookies)</span>
         
         </p>
			
			
			
          <p class='settings_sections'>
          <input type='button' value='Save Updated Settings' onclick='$("#coin_amounts").submit();' />
          </p>
                        
                        
                         
			<?php
			if ( sizeof($alert_percent) > 4 ) { // Backwards compatibility (reset if user data is not this many array values)
			?>
			
			<style>
			#alert_source, #percent_change_amount, #percent_change_filter, #percent_change_time, #percent_change_alert_type {
			display: inline;
			}
			</style>
			
			<?php
			}
			?>

                        
		    
		    
</div> <!-- max_1200px_wrapper END -->



                        