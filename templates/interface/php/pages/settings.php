<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='force_1200px_wrapper'>

			
			<h4 style='display: inline;'>Settings</h4>
				
				<span id='reload_countdown3' class='red countdown_notice'></span>
			
			
			
			<?php
			if ( $price_alert_type_text != '' ) {
          ?>
          	<p class='settings_sections'><b><?=$price_alert_type_text?> price alerts</b> are <i>enabled</i> in the configuration file (upon <?=$app_config['asset_price_alerts_percent']?>% or more <?=strtoupper($default_btc_primary_currency_pairing)?> price change<?=( $app_config['asset_price_alerts_freq'] > 0 ? ' / max every ' . $app_config['asset_price_alerts_freq'] . ' minutes per-alert' : '' )?><?=( $app_config['asset_price_alerts_min_volume'] > 0 ? ' / ' . $app_config['bitcoin_market_currencies'][$default_btc_primary_currency_pairing] . number_format($app_config['asset_price_alerts_min_volume'], 0, '.', ',') . ' minumum volume filter enabled' : '' )?><?=( $app_config['asset_price_alerts_refresh'] > 0 ? ' / comparison price auto-refreshed after ' . $app_config['asset_price_alerts_refresh'] . ' days' : '' )?>). 
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( $price_change_config_alert != '' ? '<br />' . $price_change_config_alert : '' )?>
          		
          		<?php
          		if ( preg_match("/text/i", $price_alert_type_text) && $app_config['smtp_login'] == '' && $app_config['smtp_server'] == '' && $app_config['textbelt_apikey'] == '' && $app_config['textlocal_account'] == '' ) {
          		?>
          		<br />
          		<span class='bitcoin'>Email-to-mobile-text service gateways *MAY* work more reliably (not filter out your messages) <i>if you enable SMTP email sending</i>.</span>
          		<?php
          		}
          		?>
          	
          	</p>  
                        
			<?php
			}
			if ( sizeof($app_config['proxy_list']) > 0 ) {
			?>
          <p class='settings_sections'><b><?=( trim($app_config['proxy_login']) != '' ? 'Password-based' : 'IP whitelisting' )?> proxy mode</b> is <i>enabled</i> in the configuration file for API connections (<?=sizeof($app_config['proxy_list'])?> proxies randomly used<?=( $app_config['proxy_alerts'] != 'none' ? ' / proxy alerts enabled for ' . $app_config['proxy_alerts'] . ' alert method(s), every ' . $app_config['proxy_alerts_freq'] . ' hours max per-proxy at ' . $app_config['proxy_alerts_runtime'] . ' runtimes / ' .$app_config['proxy_checkup_ok']. ' sending proxy alerts on proxy checks that tested OK after acting up' : '' )?>). 
          	
          		<?=( $proxy_config_alert != '' ? '<br />' . $proxy_config_alert : '' )?>
          	
          	</p>      
          <?php
          }
			if ( $app_config['mail_logs'] > 0 && trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Emailing logs</b> is <i>enabled</i> in the configuration file (sent out every <?=$app_config['mail_logs']?> days, log file(s) purged every <?=$app_config['purge_logs']?> days).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work RELIABLY.</i> 
          	
          		<?=( $logs_config_alert != '' ? '<br />' . $logs_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $app_config['charts_page'] == 'on' && $app_config['charts_backup_freq'] > 0 && trim($app_config['from_email']) != '' && trim($app_config['to_email']) != '' ) {
          ?>
          	<p class='settings_sections'><b>Chart Backups</b> are <i>enabled</i> in the configuration file (run every <?=$app_config['charts_backup_freq']?> days, purged after <?=$app_config['delete_old_backups']?> days old).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( $backuparchive_config_alert != '' ? '<br />' . $backuparchive_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $app_config['smtp_login'] != '' && $app_config['smtp_server'] != '' ) {
          ?>
          	<p class='settings_sections'><b>SMTP email sending</b> (by account login) is <i>enabled</i> in the configuration file.
          	
          		<?=( $smtp_config_alert != '' ? '<br />' . $smtp_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( $app_config['debug_mode'] != 'off' ) {
          ?>
          	<p class='settings_sections'><b>Debug Mode</b> is <i>enabled</i> in the configuration file.
          	
          		<br /><span class='bitcoin'>Debug Mode: <?=$app_config['debug_mode']?></span>
          	
          	</p>  
                        
			<?php
			}
			?>
			
			
			    <p class='settings_sections'><b>Theme:</b> <select onchange='
			    $("#theme_selected").val(this.value);
			    '>
				<option value='dark' <?=( $theme_selected == 'dark' ? ' selected ' : '' )?>> Dark </option>
				<option value='light' <?=( $theme_selected == 'light' ? ' selected ' : '' )?>> Light </option>
			    </select></p>
			    
			    
			
			<?php
			if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {
			    
			    ?>
			    <p class='settings_sections'><b>Primary Currency Market:</b> 
			    

					<select onchange='
					
					 btc_primary_currency = this.value;
					 primary_currency_market = $("#" + btc_primary_currency + "btc_currency_pairs").val();
					 currency_selected_market = $("#" + btc_primary_currency + "BTC_pairs option:selected").val();
					 currency_selected_market_standalone = $("#" + btc_primary_currency + "btc_currency_pairs option:selected").val();
					 currency_exchanges_list = document.getElementById(btc_primary_currency + "BTC_pairs");
					
				    
				    exchange_name_ui = currency_exchanges_list.options[currency_exchanges_list.selectedIndex].text;
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    if ( window.limited_apis.includes(exchange_name) == true ) {
				    alert("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating multiple / different asset price requests into one single call per session).\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange market, try a different exchange market for your preferred primary currency, and the issue should go away.");
				    }
				    
				    $("#btc_primary_currency").val( btc_primary_currency );
				    
				    $("#primary_currency_market_id_lists").children().hide(); 
				    $("#" + btc_primary_currency + "btc_currency_pairs").show(); 
				    $("#primary_currency_market_id").val( currency_selected_market_standalone );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update assets" tab, mirroring of settings
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
					
					$exchange_field_id = btc_market($app_config['btc_primary_exchange']);
					
					foreach (  $app_config['portfolio_assets']['BTC']['market_pairing'] as $pairing_key => $pairing_id ) {
					?>
					<option value='<?=$pairing_key?>' <?=( isset($app_config['btc_primary_currency_pairing']) && $app_config['btc_primary_currency_pairing'] == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
					
									
									foreach ( $app_config['portfolio_assets']['BTC']['market_pairing'][$pairing_key] as $market_key => $market_id ) {
									$loop2 = $loop2 + 1;
									
									$btc_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( $exchange_field_id == $loop2 ? ' selected ' : '' ) . ">" . name_rendering($market_key) . "</option>\n";
									}
									$loop2 = NULL;
							
					}
					?>
				    </select> 
				    
				    
				     @ 
				    
				    <input type='hidden' id='btc_primary_currency' name='btc_primary_currency' value='<?=$app_config['btc_primary_currency_pairing']?>' />
				     
				    <input type='hidden' id='primary_currency_market_id' name='primary_currency_market_id' value='<?=$exchange_field_id?>' />
				     
				     
				     <span id='primary_currency_market_id_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pairing: <?=$app_config['btc_primary_currency_pairing']?> -->
				     <!-- primary_currency_market_standalone[1]: <?=$primary_currency_market_standalone[1]?> -->
				     <!-- primary_currency_market_standalone[0]: <?=$primary_currency_market_standalone[0]?> -->
				     <!-- btc_primary_exchange: <?=$app_config['btc_primary_exchange']?> -->
				    <?php
				    
				    foreach ( $btc_market_list as $key => $value ) {
				    ?>
				    
				    <select onchange ='
				    
				    exchange_name_ui = this.options[this.selectedIndex].text;
				    
				    exchange_name = exchange_name_ui.toLowerCase();
				    
				    if ( window.limited_apis.includes(exchange_name) == true ) {
				    alert("The " + exchange_name_ui + " exchange API is less reliable than some others (by NOT consolidating multiple / different asset price requests into one single call per session).\n\nIf you experience issues with primary currency values NOT displaying in this app when using the " + exchange_name_ui + " exchange market, try a different exchange market for your preferred primary currency, and the issue should go away.");
				    }
				    
				    btc_primary_currency = $("#btc_primary_currency").val();
					 primary_currency_market = this.value;
					 
				    $("#primary_currency_market_id").val( primary_currency_market );
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update assets" tab, mirroring of settings
				    if ( document.getElementById("standalone_primary_currency_enabled").checked == false ) {
				    $("#btc_market").val( primary_currency_market ); // Set hidden field var
				    $("#" + btc_primary_currency + "BTC_pairs").val( primary_currency_market ); // Set selected drop down choice
				    }
				    else {
				    $("#primary_currency_market_standalone").val( btc_primary_currency + "|" + primary_currency_market );
				    }
				    
				    ' id='<?=$key?>btc_currency_pairs' style='display: <?=( $app_config['btc_primary_currency_pairing'] == $key ? 'inline' : 'none' )?>;'><?=$btc_market_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $btc_market_list = NULL;
				    ?>
				    
				    </span> <img id='currency_info' src='templates/interface/media/images/info.png' alt='' width='30' border='0' style='position: relative; left: -5px;' /> <input type='checkbox' id='standalone_primary_currency_enabled' name='standalone_primary_currency_enabled' value='1' onchange='
				    
				    btc_primary_currency = $("#btc_primary_currency").val();
				    primary_currency_market = $("#primary_currency_market_id").val();
				    
				    /////////////////////////////////////////////////////////
				    
				    // "Update assets" tab, mirroring of settings
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
				    
				    
				    ' <?=( sizeof($primary_currency_market_standalone) == 2 ? 'checked' : '' )?> /> Stand-Alone Mode (<i>WON'T automatically change</i> Bitcoin market on "Update Assets" page)
 <script>
	
			var currency_content = '<h5 align="center" class="yellow" style="position: relative; white-space: nowrap;">Currency Market Setting:</h5>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">The Currency Market setting allows you to change your default primary currency for the portfolio interface (the charts / price alerts currency market <i>must be changed separately in config.php</i>).</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Additionally, if you check off "Stand-Alone Mode", your chosen Bitcoin market on the "Update Assets" page <i>will NOT be automatically changed to match your chosen Currency Market on the "Settings" page</i>. This is useful if you\'d like to browse through different Bitcoin markets, BUT don\'t want your default primary currency to change in the app.</p>'
			
			+'<p class="coin_info"><span class="yellow"> </span></p>';
		
		
			$('#currency_info').balloon({
			html: true,
			position: "right",
			contents: currency_content,
			css: {
					fontSize: ".8rem",
					minWidth: ".8rem",
					padding: ".3rem .7rem",
					border: "1px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.95",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 


			    </p>
			    <?php
			
			}
			
			?>
			
			
			    <p class='settings_sections'><b>Sort Table Data By Column:</b> <select id='sorted_by_col' onchange='
			    $("#sort_by").val( this.value + "|" + $("#sorted_by_asc_desc").val() );
			    '>
				<option value='0' <?=( $sorted_by_col == 0 ? ' selected ' : '' )?>> # </option>
				<option value='1' <?=( $sorted_by_col == 1 ? ' selected ' : '' )?>> Asset </option>
				<option value='2' <?=( $sorted_by_col == 2 ? ' selected ' : '' )?>> Per-Token (<?=strtoupper($app_config['btc_primary_currency_pairing'])?>) </option>
				<option value='3' <?=( $sorted_by_col == 3 ? ' selected ' : '' )?>> Holdings </option>
				<option value='4' <?=( $sorted_by_col == 4 ? ' selected ' : '' )?>> Symbol </option>
				<option value='5' <?=( $sorted_by_col == 5 ? ' selected ' : '' )?>> Exchange </option>
				<option value='6' <?=( $sorted_by_col == 6 ? ' selected ' : '' )?>> Trade Volume </option>
				<option value='7' <?=( $sorted_by_col == 7 ? ' selected ' : '' )?>> Trade Value </option>
				<option value='8' <?=( $sorted_by_col == 8 ? ' selected ' : '' )?>> Market </option>
				<option value='9' <?=( $sorted_by_col == 9 ? ' selected ' : '' )?>> Holdings Value </option>
				<option value='10' <?=( $sorted_by_col == 10 ? ' selected ' : '' )?>> Subtotal (<?=strtoupper($app_config['btc_primary_currency_pairing'])?>) </option>
			    </select> 
			     <select id='sorted_by_asc_desc' onchange='
			    $("#sort_by").val( $("#sorted_by_col").val() + "|" + this.value );
			    '>
				<option value='0' <?=( $sorted_by_asc_desc == 0 ? ' selected ' : '' )?>> Ascending </option>
				<option value='1' <?=( $sorted_by_asc_desc == 1 ? ' selected ' : '' )?>> Decending </option>
			    </select></p>
			    
			    
			<p class='settings_sections'>
				
				<b>Visual or Audio Alerts For Price Changes:</b>
			     
			    <select name='alert_percent' id='alert_percent' onchange='
			    if ( this.value == "yes" ) {
			    document.getElementById("alert_source").style.display = "inline";
			    document.getElementById("percent_change_amount").style.display = "inline";
			    document.getElementById("percent_change_time").style.display = "inline";
			    document.getElementById("percent_change_alert_type").style.display = "inline";
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("alert_source").style.display = "none";
			    document.getElementById("percent_change_amount").style.display = "none";
			    document.getElementById("percent_change_time").style.display = "none";
			    document.getElementById("percent_change_alert_type").style.display = "none";
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='no' <?=( !$alert_percent ? ' selected ' : '' )?>> No </option>
			    <option value='yes' <?=( sizeof($alert_percent) > 1 ? ' selected ' : '' )?>> Yes </option>
			    </select>
			     
			     
			    <select name='alert_source' id='alert_source' onchange='
			    if ( document.getElementById("alert_percent").value == "yes" ) {
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='coingecko' <?=( $alert_percent[0] == 'coingecko' ? ' selected ' : '' )?>> Coingecko.com </option>
			    <option value='coinmarketcap' <?=( $alert_percent[0] == 'coinmarketcap' ? ' selected ' : '' )?>> Coinmarketcap.com </option>
			    </select>  
			    
			    <select name='percent_change_amount' id='percent_change_amount' onchange='
			    if ( document.getElementById("alert_percent").value == "yes" ) {
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='-50' <?=( $alert_percent[1] == '-50' ? ' selected ' : '' )?>> -50% </option>
			    <option value='-45' <?=( $alert_percent[1] == '-45' ? ' selected ' : '' )?>> -45% </option>
			    <option value='-40' <?=( $alert_percent[1] == '-40' ? ' selected ' : '' )?>> -40% </option>
			    <option value='-35' <?=( $alert_percent[1] == '-35' ? ' selected ' : '' )?>> -35% </option>
			    <option value='-30' <?=( $alert_percent[1] == '-30' ? ' selected ' : '' )?>> -30% </option>
			    <option value='-25' <?=( $alert_percent[1] == '-25' ? ' selected ' : '' )?>> -25% </option>
			    <option value='-20' <?=( $alert_percent[1] == '-20' ? ' selected ' : '' )?>> -20% </option>
			    <option value='-15' <?=( $alert_percent[1] == '-15' ? ' selected ' : '' )?>> -15% </option>
			    <option value='-10' <?=( $alert_percent[1] == '-10' ? ' selected ' : '' )?>> -10% </option>
			    <option value='-5' <?=( $alert_percent[1] == '-5' ? ' selected ' : '' )?>> -5% </option>
			    <option value='5' <?=( !$alert_percent[1] || $alert_percent[1] == 5 ? ' selected ' : '' )?>> +5% </option>
			    <option value='10' <?=( $alert_percent[1] == 10 ? ' selected ' : '' )?>> +10% </option>
			    <option value='15' <?=( $alert_percent[1] == 15 ? ' selected ' : '' )?>> +15% </option>
			    <option value='20' <?=( $alert_percent[1] == 20 ? ' selected ' : '' )?>> +20% </option>
			    <option value='25' <?=( $alert_percent[1] == 25 ? ' selected ' : '' )?>> +25% </option>
			    <option value='30' <?=( $alert_percent[1] == 30 ? ' selected ' : '' )?>> +30% </option>
			    <option value='35' <?=( $alert_percent[1] == 35 ? ' selected ' : '' )?>> +35% </option>
			    <option value='40' <?=( $alert_percent[1] == 40 ? ' selected ' : '' )?>> +40% </option>
			    <option value='45' <?=( $alert_percent[1] == 45 ? ' selected ' : '' )?>> +45% </option>
			    <option value='50' <?=( $alert_percent[1] == 50 ? ' selected ' : '' )?>> +50% </option>
			    </select> 
			     
			    <select name='percent_change_time' id='percent_change_time' onchange='
			    if ( document.getElementById("alert_percent").value == "yes" ) {
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='1hour' <?=( $alert_percent[2] == '1hour' ? ' selected ' : '' )?>> 1 Hour </option>
			    <option value='24hour' <?=( $alert_percent[2] == '24hour' ? ' selected ' : '' )?>> 24 Hour </option>
			    <option value='7day' <?=( $alert_percent[2] == '7day' ? ' selected ' : '' )?>> 7 Day </option>
			    </select>  
			     
			    <select name='percent_change_alert_type' id='percent_change_alert_type' onchange='
			    if ( document.getElementById("alert_percent").value == "yes" ) {
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    }
			    else {
			    document.getElementById("use_alert_percent").value = "";
			    }
			    '>
			    <option value='visual_only' <?=( $alert_percent[3] == 'visual_only' ? ' selected ' : '' )?>> Visual Only </option>
			    <option value='visual_audio' <?=( $alert_percent[3] == 'visual_audio' ? ' selected ' : '' )?>> Visual and Audio </option>
			    </select>
			
			</p>
			
			<?php
			if ( sizeof($alert_percent) > 1 ) {
			?>
			
			<style>
			#alert_source, #percent_change_amount, #percent_change_time, #percent_change_alert_type {
			display: inline;
			}
			</style>
			
			<?php
			}
			?>

			
                        <p class='settings_sections'>
                        <b>Use cookie data to save values between sessions:</b> <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
                        if ( this.checked == true ) {
								document.getElementById("use_cookies").value = "1";
                        }
                        else {
								document.getElementById("use_cookies").value = "";
								document.getElementById("use_notes").value = "";
								document.getElementById("set_use_notes").checked = false;
                        }
                        ' <?php echo ( $_COOKIE['coin_amounts'] != '' ? 'checked' : ''); ?> /> <span class='red'>(un-checking this box <i>deletes ALL previously-saved cookie data <u>permanently</u></i>)</span>
                        </p>
			
			
                        <p class='settings_sections'>
                        <b>Enable trading notes (requires cookie data):</b> <input type='checkbox' name='set_use_notes' id='set_use_notes' value='1' onchange='
                        if ( this.checked == true ) {
								document.getElementById("set_use_cookies").checked = true;
								document.getElementById("use_cookies").value = "1";
								document.getElementById("use_notes").value = "1";
                        }
                        else {
								document.getElementById("use_notes").value = "";
                        }
                        ' <?php echo ( $_COOKIE['notes_reminders'] != '' ? 'checked' : ''); ?> />
                        </p>
			
			
                        <p class='settings_sections'><input type='button' value='Save Updated Settings' onclick='
                        document.coin_amounts.submit();
                        ' /></p>
                        
                        
                        
                        
		    
		    
</div> <!-- force_1200px_wrapper END -->



                        