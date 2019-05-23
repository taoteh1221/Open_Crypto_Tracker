<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>
			<?php
			if ( $price_alert_type_text != '' ) {
          ?>
          	<p class='settings_sections'><?=$price_alert_type_text?> exchange price alerts are <i>enabled</i> in the configuration file (upon <?=$exchange_price_alerts_percent?>% or more USD price change<?=( $exchange_price_alerts_freq > 0 ? ' / every ' . $exchange_price_alerts_freq . ' minutes max per-alert' : '' )?><?=( $exchange_price_alerts_minvolume > 0 ? ' / $' . number_format($exchange_price_alerts_minvolume, 0, '.', ',') . ' minumum volume filter enabled' : '' )?><?=( $exchange_price_alerts_refresh > 0 ? ' / comparison price auto-refreshed after ' . $exchange_price_alerts_refresh . ' days' : '' )?>). 
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work AT ALL.</i> 
          	
          		<?=( $price_change_config_alert != '' ? '<br />' . $price_change_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			if ( sizeof($proxy_list) > 0 ) {
			?>
          <p class='settings_sections'><?=( trim($proxy_login) != '' ? 'Password-based' : 'IP whitelisting' )?> proxy mode is <i>enabled</i> in the configuration file for API connections (<?=sizeof($proxy_list)?> proxies randomly used<?=( $proxy_alerts != 'none' ? ' / proxy alerts enabled for ' . $proxy_alerts . ' alert method(s), every ' . $proxy_alerts_freq . ' hours max per-proxy at ' . $proxy_alerts_runtime . ' runtimes / ' .$proxy_checkup_ok. ' sending proxy alerts on proxy checks that tested OK after acting up' : '' )?>). 
          	
          		<?=( $proxy_config_alert != '' ? '<br />' . $proxy_config_alert : '' )?>
          	
          	</p>      
          <?php
          }
			if ( $mail_error_logs == 'daily' && trim($from_email) != '' && trim($to_email) != ''
			|| $mail_error_logs == 'weekly' && trim($from_email) != '' && trim($to_email) != '' ) {
          ?>
          	<p class='settings_sections'>Emailing error logs is <i>enabled</i> in the configuration file (sent out <?=$mail_error_logs?>, purged every <?=$purge_error_logs?> days).
          	
          	<br /><i>Enable <a href='README.txt' target='_blank'>a cron job on your web server</a>, or this feature will not work RELIABLY.</i> 
          	
          		<?=( $errorlogs_config_alert != '' ? '<br />' . $errorlogs_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			// To be safe, don't use trim() on certain strings with arbitrary non-alphanumeric characters here
			if ( $smtp_login != '' && $smtp_server != '' ) {
          ?>
          	<p class='settings_sections'>SMTP email sending (by account login) is <i>enabled</i> in the configuration file.
          	
          		<?=( $smtp_config_alert != '' ? '<br />' . $smtp_config_alert : '' )?>
          	
          	</p>  
                        
			<?php
			}
			
			if (is_array($coins_list) || is_object($coins_list)) {
			    
			    ?>
			    <p class='settings_sections'>Default Bitcoin Market: <select onchange='
			    $("#btc_market").val(this.value);
			    '>
				<?php
				foreach ( $coins_list['BTC']['market_pairing']['btc'] as $market_key => $market_name ) {
				$loop = $loop + 1;
				?>
				<option value='<?=$loop?>' <?=( isset($_POST['btc_market']) && ($_POST['btc_market']) == $loop || isset($btc_market) && $btc_market == ($loop - 1) ? ' selected ' : '' )?>> <?=ucfirst($market_key)?> </option>
				<?php
				}
				$loop = NULL;
				?>
			    </select>
			    </p>
			    <?php
			
			}
			
			?>
			
			    <p class='settings_sections'>Sort Table Data By Column: <select id='sorted_by_col' onchange='
			    $("#sort_by").val( this.value + "|" + $("#sorted_by_asc_desc").val() );
			    '>
				<option value='0' <?=( $sorted_by_col == 0 ? ' selected ' : '' )?>> # </option>
				<option value='1' <?=( $sorted_by_col == 1 ? ' selected ' : '' )?>> Asset </option>
				<option value='2' <?=( $sorted_by_col == 2 ? ' selected ' : '' )?>> USD Value </option>
				<option value='3' <?=( $sorted_by_col == 3 ? ' selected ' : '' )?>> Holdings </option>
				<option value='4' <?=( $sorted_by_col == 4 ? ' selected ' : '' )?>> Symbol </option>
				<option value='5' <?=( $sorted_by_col == 5 ? ' selected ' : '' )?>> Exchange </option>
				<option value='6' <?=( $sorted_by_col == 6 ? ' selected ' : '' )?>> USD Volume </option>
				<option value='7' <?=( $sorted_by_col == 7 ? ' selected ' : '' )?>> Trade Value </option>
				<option value='8' <?=( $sorted_by_col == 8 ? ' selected ' : '' )?>> Market </option>
				<option value='9' <?=( $sorted_by_col == 9 ? ' selected ' : '' )?>> Holdings Value </option>
				<option value='10' <?=( $sorted_by_col == 10 ? ' selected ' : '' )?>> USD Subtotal </option>
			    </select> 
			     <select id='sorted_by_asc_desc' onchange='
			    $("#sort_by").val( $("#sorted_by_col").val() + "|" + this.value );
			    '>
				<option value='0' <?=( $sorted_by_asc_desc == 0 ? ' selected ' : '' )?>> Ascending </option>
				<option value='1' <?=( $sorted_by_asc_desc == 1 ? ' selected ' : '' )?>> Decending </option>
			    </select></p>
			    
			    
			<p class='settings_sections'>
				
				Visual or Audio Alerts For Price Changes:
			     
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
			    <option value='coinmarketcap' <?=( $alert_percent[0] == 'coinmarketcap' ? ' selected ' : '' )?>> Coinmarketcap.com </option>
			    <option value='coingecko' <?=( $alert_percent[0] == 'coingecko' ? ' selected ' : '' )?>> Coingecko.com </option>
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
			    <option value='5' <?=( !$alert_percent[1] || $alert_percent[1] == 5 ? ' selected ' : '' )?>> 5% </option>
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
                        Use cookie data to save values between sessions <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
                        if ( this.checked == true ) {
								document.getElementById("use_cookies").value = "1";
                        }
                        else {
								document.getElementById("set_use_notes").checked = false;
                        }
                        ' <?php echo ( $_COOKIE['coin_amounts'] && $_POST['submit_check'] != 1 || $_POST['use_cookies'] == 1 && $_POST['submit_check'] == 1 ? ' checked="checked"' : ''); ?> /> <span style='color: red;'>(un-checking this box <i>deletes ALL previously-saved cookie data <u>permanently</u></i>)</span>
                        </p>
			
			
                        <p class='settings_sections'>
                        Enable trading notes (requires cookie data) <input type='checkbox' name='set_use_notes' id='set_use_notes' value='1' onchange='
                        if ( this.checked == true ) {
								document.getElementById("set_use_cookies").checked = true;
								document.getElementById("use_cookies").value = "1";
         
									if ( document.getElementById("notes_reminders") ) {
									var prev_notes = document.getElementById("notes_reminders").value;								
									}         
									else {
									var prev_notes = " "; // Initialized with some whitespace when blank
									}
         
        						setCookie("notes_reminders", prev_notes, 365);
								document.getElementById("use_notes").value = "1";
                        }
                        ' <?php echo ( $_COOKIE['notes_reminders'] && $_POST['submit_check'] != 1 || $_POST['use_notes'] == 1 && $_POST['submit_check'] == 1 ? ' checked="checked"' : ''); ?> />
                        </p>
			
			
                        <p class='settings_sections'><input type='button' value='Save Updated Program Settings' onclick='
                        if ( document.getElementById("set_use_notes").checked != true ) {
								delete_cookie("notes_reminders");
								document.getElementById("use_notes").value = "";
                        }
                        if ( document.getElementById("set_use_cookies").checked != true ) {
								delete_cookie("coin_amounts");
								delete_cookie("coin_markets");
								delete_cookie("coin_reload");
								delete_cookie("alert_percent");
								delete_cookie("sort_by");
								document.getElementById("use_cookies").value = "";
								
								delete_cookie("notes_reminders");
								document.getElementById("use_notes").value = "";
                        }
                        document.coin_amounts.submit();
                        ' /></p>
                        
                        
                        
                        
                        