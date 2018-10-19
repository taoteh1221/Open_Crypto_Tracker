<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

			<?php
			
			if (is_array($coins_array) || is_object($coins_array)) {
			    
			    ?>
			    <p>Default Bitcoin Market: <select onchange='
			    document.getElementById("btc_market").selectedIndex = (this.value - 1);
			    '>
				<?php
				foreach ( $coins_array['BTC']['market_pairing']['btc'] as $market_key => $market_name ) {
				$loop = $loop + 1;
				?>
				<option value='<?=$loop?>' <?=( isset($_POST['btc_market']) && ($_POST['btc_market']) == $loop || isset($btc_market) && $btc_market == ($loop - 1) ? ' selected ' : '' )?>> <?=ucfirst($market_key)?> </option>
				<?php
				}
				$loop = NULL;
				?>
			    </select></p>
			    <?php
			
			}
			
			?>

			<p>
				
				Percentage Change Alert (marketcap data):
			     
			    <select name='alert_percent' id='alert_percent' onchange='
			    if ( this.value == "yes" ) {
			    document.getElementById("alert_source").style.display = "inline";
			    document.getElementById("percent_change_amount").style.display = "inline";
			    document.getElementById("percent_change_time").style.display = "inline";
			    document.getElementById("percent_change_alert_type").style.display = "inline";
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    //console.log(document.getElementById("use_alert_percent").value);
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
			    //console.log(document.getElementById("use_alert_percent").value);
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
			    //console.log(document.getElementById("use_alert_percent").value);
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
			    <option value='50' <?=( $alert_percent[1] == 50 ? ' selected ' : '' )?>> 50% or higher </option>
			    </select>
			     
			    <select name='percent_change_time' id='percent_change_time' onchange='
			    if ( document.getElementById("alert_percent").value == "yes" ) {
			    document.getElementById("use_alert_percent").value = document.getElementById("alert_source").value + "|" + document.getElementById("percent_change_amount").value + "|" + document.getElementById("percent_change_time").value + "|" + document.getElementById("percent_change_alert_type").value;
			    //console.log(document.getElementById("use_alert_percent").value);
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
			    //console.log(document.getElementById("use_alert_percent").value);
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

			
                        <p>
                        Use cookie data to save settings between sessions <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
                        if ( this.checked != true ) {
			delete_cookie("coin_amounts");
			delete_cookie("coin_markets");
			delete_cookie("coin_reload");
			delete_cookie("alert_percent");
			document.getElementById("use_cookies").value = "";
			
			delete_cookie("notes_reminders");
			document.getElementById("use_notes").value = "";
			document.getElementById("set_use_notes").checked = false;
			document.getElementById("set_use_notes").disabled = true;
                        }
                        else {
			document.getElementById("use_cookies").value = "1";
			document.getElementById("set_use_notes").disabled = false;
                        }
                        ' <?php echo ( $_COOKIE['coin_amounts'] && $_POST['submit_check'] != 1 || $_POST['use_cookies'] == 1 && $_POST['submit_check'] == 1 ? ' checked="checked"' : ''); ?> />
                        </p>
			
			
                        <p>
                        Enable trading notes (requires cookie data) <input type='checkbox' name='set_use_notes' id='set_use_notes' value='1' onchange='
                        if ( this.checked != true ) {
			delete_cookie("notes_reminders");
			document.getElementById("use_notes").value = "";
                        }
                        else {
         
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
			
			
                        <input type='button' value='Update Settings' onclick='document.coin_amounts.submit();' />
                        