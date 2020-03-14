<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='max_full_width_wrapper'>


<!--  !START! RE-USED INFO BUBBLE DATA  -->
<script>



		var average_paid_notes = '<h5 align="center" class="yellow" style="position: relative; white-space: nowrap;">Calculating Average <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Price Paid Per Token</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="green_bright">Total <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Paid For All Tokens</span> <span class="blue">&#247;</span> <span class="yellow">Total Tokens Purchased</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">The RESULT of the above calculation <i>remains the same even AFTER you sell ANY amount, ONLY if you don\'t buy more between sells</i>. Everytime you buy more <i>after selling some</i>, re-calculate your Average <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Price Paid Per Token with this formula:</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="green_bright">Total <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Paid For All Tokens</span> <span class="blue">-</span> <span class="red_bright">Total <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Received From All Sold Tokens</span> <span class="blue">&#247;</span> <span class="yellow">Total Remaining Tokens Still Held</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($app_config['btc_primary_currency_pairing'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="yellow">PRO TIP:</span> <br /> When buying / selling, keep quick and dirty (yet clear) textual records of... <br />a) How much you bought / sold of what<br />b) What you paid / received in <?=strtoupper($app_config['btc_primary_currency_pairing'])?> value<br />c) What / where you traded <br />d) Backup to USB Stick / NAS / DropBox / GoogleDrive / OneDrive / AmazonBucket <br />e) Now you\'re ready for tax season, to create spreadsheets from this data <br /><span class="yellow">There is also an <i>open source / free</i> app called <a href="https://rotki.com" target="_blank">Rotki</a> that can help you <i>PRIVATELY</i> track your tax data.</span></p>'
			
			+'<p class="coin_info"><span class="yellow"> </span></p>';

	
	
			var leverage_trading_notes = '<h5 align="center" class="yellow" style="position: relative; white-space: nowrap;">Tracking Long / Short Margin Leverage Trades</h5>'
			
			
			+'<p class="coin_info extra_margins red" style="white-space: normal; max-width: 600px; font-size: 13px;"><b>*Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). Never put more than ~5% of your total investment worth into leverage trades, or you will <u>RISK LOSING EVERYTHING</u>!</b></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Set the "Asset / Pairing @ Exchange" drop-down menus for the asset to any markets you prefer. It doesn\'t matter which ones you choose, as long as the price discovery closely matches the exchange where you are margin trading this asset.</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Set the "Holdings" field to match your margin leverage deposit (example: buying 1 BTC @ 5x leverage would be 0.2 BTC in the "Holdings" field in this app). You\'ll also need to fill in the "Average Paid (per-token)" field with the average price paid in <?=strtoupper($app_config['btc_primary_currency_pairing'])?> per-token. Finally, set the "Margin Leverage" fields to match your leverage and whether you are long or short. When you are done, click "Save Updated Assets".</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">To see your margin leverage stats after updating your portfolio, go to the bottom of the Portfolio page, where you\'ll find a stats section. Hovering over the "I" icon next to those summary stats will display additional stats per-asset. There is also an "I" icon in the far right table column (Subtotal) per-asset, which you can hover over for margin leverage stats too.</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="yellow">*Current maximum margin leverage setting of <?=$app_config['margin_leverage_max']?>x can be adjusted in config.php.</span></p>'
			
			+'<p class="coin_info"><span class="yellow"> </span></p>';
			
			
			
</script>
<!--  !END! RE-USED INFO BUBBLE DATA  -->
				
				
	
				<h4 style='display: inline;'>Update Assets</h4>
				
				
				<span id='reload_countdown2' class='red countdown_notice'></span>
				
				
	<p style='margin-top: 10px;'><a style='font-weight: bold;' class='show red' id='disclaimer' href='#show_disclaimer' title='Click to show disclaimer.' onclick='return false;'>Disclaimer!</a></p>
	    
	    
	    
		<div style='display: none;' class='align_left show_disclaimer'>
			
	     
						<p class='red' style='font-weight: bold;'>
						
						Assets in the default examples / demo list DO NOT indicate ANY endorsement of these assets (AND removal indicates NO anti-endorsement). These crypto-assets are merely either interesting, historically popular, or (at time off addition) good ROI or returns for cryptocurrency mining hardware / staking / community grants. They are only used as <i>examples for demoing feasibility of features</i> in this application, <a href='README.txt' target='_blank'>before you install it on your Raspberry Pi or website server, and change the list to your favorite assets</a>. 
						
						<br /><br />Always do your due diligence investigating whether you are engaging in trading within acceptable risk levels for your <i>NET</i> worth, and consider consulting a professional if you are unaware of what risks are present.
						
						</p>
	
						<p class='red' style='font-weight: bold;'>
						
						<i><u>Semi-simplified version of above IMPORTANT disclaimer / advisory</u>:</i> 
						
						<ul>
						
							<li class='red'>
								<i>NEVER</i> invest more than you can afford to lose
							</li>
						
							<li class='red'>
								<i>NEVER</i> buy an asset because of somebody's opinion of it (only buy based on <i>YOUR</i> opinion of it)
							</li>
							
							<li class='red'>
								<i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as long term chart TA, <i>and any short term chart TA is pure BS to be ignored</i>)
							</li>
							
							<li class='red'>
								<i>ALWAYS</i> diversify and balance your portfolio with <i>mostly oldest / largest marketcap</i> assets (which are <i>relatively</i> less volatile) for you <i>and yours safety and sanity</i>
							</li>
							
							<li class='red'>
								<i>ALWAYS <u>buy low</u> AND <u>sell high</u></i> (NOT the other way around)
							</li>
							
							<li class='red'>
								<i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading
							</li>
							
							<li class='red'>
								<i>Hang on tight</i> until you can't stand fully holding anymore / want to or must make a position exit (percentage) official
							</li>
						
						</ul>
						
						<span class='red'>Best of luck, be careful out there in this cryptoland frontier <i>full of garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues (wolves in sheep's clothing)! 😮</span>

						
						<br /><br /><a href="https://twitter.com/taoteh1221/status/1192997965952094208" target="_blank"><img src='templates/interface/media/images/twitter-1192997965952094208.jpg' width='425' class='image_border' alt='' /></a>
						
						</p>
	
		
		</div>
			
	
	
	<br class='clear_both' />
	<a style='font-weight: bold;' href='README.txt' target='_blank'>Editing The Portfolio Assets List, and Enabling Email / Text / Alexa / Google Home Exchange Price Alerts (installation on a Raspberry Pi or website)</a>
	<br class='clear_both' />
	
			
	<div class='align_left' style='margin-top: 30px; margin-bottom: 15px;'>
	
		
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<button class='force_button_style' onclick='
		document.coin_amounts.submit();
		'>Save Updated Assets</button>
	
		<form style='display: inline;' name='csv_import' id='csv_import' enctype="multipart/form-data" action="<?=start_page($_GET['start_page'])?>" method="post">
		
	    <input type="hidden" name="csv_check" value="1" />
	    
	    <span id='file_upload'><input style='margin-left: 75px;' name="csv_file" type="file" /></span>
	    
	    <input type="button" onclick='validateForm("csv_import", "csv_file");' value="Import Portfolio From CSV File" />
	    
		</form>
		
		<button style='margin-left: 75px;' class='force_button_style' onclick='
		set_target_action("coin_amounts", "_blank", "download.php");
		document.coin_amounts.submit();
		set_target_action("coin_amounts", "_self", "<?=start_page($_GET['start_page'])?>");
		'>Export Portfolio To CSV File</button>
		
	</div>
	
		
		
	<div style='display: inline-block; border: 2px dotted black; padding: 7px; margin-left: 0px; margin-top: 15px; margin-bottom: 15px;'>
	
		<div class='align_center' style='font-weight: bold;'>Watch Only</div>
	
		<div style='margin-left: 6px;'><input type='checkbox' onclick='selectAll(this, "coin_amounts");' /> Select / Unselect All <i><u>Unheld</u> Assets</i>	</div>
		
	
	</div>
	
	
	<br class='clear_both' />	
	
	 <?php
	 if ( $csv_import_fail != NULL ) {
	 ?>
	<br />	
	 <div class='red red_dotted' style='font-weight: bold;'><?=$csv_import_fail?></div>
	<br />	
	<br />	
	 <?php
	 }
	 if ( $csv_import_succeed != NULL ) {
	 ?>
	<br />	
	 <div class='green green_dotted' style='font-weight: bold;'><?=$csv_import_succeed?></div>
	<br />	
	<br />	
	 <?php
	 }
	 ?>
	
	
	
	<form id='coin_amounts' name='coin_amounts' action='<?=start_page($_GET['start_page'])?>' method='post'>
	
	
	<?php
	
	if (is_array($app_config['portfolio_assets']) || is_object($app_config['portfolio_assets'])) {

	    
	    $zebra_stripe = 'long_list_odd';
	    foreach ( $app_config['portfolio_assets'] as $coin_array_key => $coin_array_value ) {
		
		 $rand_id = rand(10000000,100000000);
	    
	    $field_var_pairing = strtolower($coin_array_key) . '_pairing';
	    $field_var_market = strtolower($coin_array_key) . '_market';
	    $field_var_amount = strtolower($coin_array_key) . '_amount';
	    $field_var_paid = strtolower($coin_array_key) . '_paid';
	    $field_var_leverage = strtolower($coin_array_key) . '_leverage';
	    $field_var_margintype = strtolower($coin_array_key) . '_margintype';
	    $field_var_watchonly = strtolower($coin_array_key) . '_watchonly';
	    $field_var_restore = strtolower($coin_array_key) . '_restore';
	    
	    
	        if ( $_POST['submit_check'] == 1 ) {
	        $coin_pairing_id = $_POST[$field_var_pairing];
	        $coin_market_id = $_POST[$field_var_market];
	        $asset_amount_value = remove_number_format($_POST[$field_var_amount]);
	        $coin_paid_value = remove_number_format($_POST[$field_var_paid]);
	        $coin_leverage_value = $_POST[$field_var_leverage];
	        $coin_margintype_value = $_POST[$field_var_margintype];
	        }
	        elseif ( $run_csv_import == 1 ) {
	        	
	        
	        		foreach( $csv_file_array as $key => $value ) {
	        		
	        			if ( strtoupper($coin_array_key) == strtoupper($key) ) {
	        			
	        			$value[5] = ( whole_int($value[5]) != false ? $value[5] : 1 ); // If market ID input is corrupt, default to 1
	        			$value[3] = ( whole_int($value[3]) != false ? $value[3] : 0 ); // If leverage amount input is corrupt, default to 0
	        			
	        		 	$coin_pairing_id = strtolower($value[6]);
	        			$coin_market_id = $value[5];
	        		 	$asset_amount_value = remove_number_format($value[1]);
	       		 	$coin_paid_value = remove_number_format($value[2]);
	       		 	$coin_leverage_value = $value[3];
	        			$coin_margintype_value = strtolower($value[4]);
	        			
	       		 	}
	        	
	        		}
	        		
	        
	        }
	        
	
	    
	    	  // Cookies
	        if ( !$run_csv_import && $_COOKIE['coin_pairings'] ) {
	        
	        $all_coin_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
	        
		if (is_array($all_coin_pairings_cookie_array) || is_object($all_coin_pairings_cookie_array)) {
		    
		    foreach ( $all_coin_pairings_cookie_array as $coin_pairings ) {
		        
		    $single_coin_pairings_cookie_array = explode("-", $coin_pairings);
		    
		    $coin_symbol = strtoupper(preg_replace("/_pairing/i", "", $single_coin_pairings_cookie_array[0]));  
		    
		        if ( $coin_symbol == strtoupper($coin_array_key) ) {
		        $coin_pairing_id = $single_coin_pairings_cookie_array[1];
		        }
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	        
	        
	        if ( !$run_csv_import && $_COOKIE['coin_markets'] ) {
	        
	        $all_coin_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
	        
		if (is_array($all_coin_markets_cookie_array) || is_object($all_coin_markets_cookie_array)) {
		    
		    foreach ( $all_coin_markets_cookie_array as $coin_markets ) {
		        
		    $single_coin_markets_cookie_array = explode("-", $coin_markets);
		    
		    $coin_symbol = strtoupper(preg_replace("/_market/i", "", $single_coin_markets_cookie_array[0]));  
		    
		        if ( $coin_symbol == strtoupper($coin_array_key) ) {
		        $coin_market_id = $single_coin_markets_cookie_array[1];
		        }
		    
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_amounts'] ) {
	        
	        $all_coin_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	        
		if (is_array($all_coin_amounts_cookie_array) || is_object($all_coin_amounts_cookie_array)) {
		    
		    foreach ( $all_coin_amounts_cookie_array as $asset_amounts ) {
		        
		    $single_coin_amounts_cookie_array = explode("-", $asset_amounts);
		    
		    $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_amounts_cookie_array[0]));  
		    
		    		// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$asset_amount_value = number_to_string($single_coin_amounts_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_paid'] ) {
	        
	        $all_coin_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	        
		if (is_array($all_coin_paid_cookie_array) || is_object($all_coin_paid_cookie_array)) {
		    
		    foreach ( $all_coin_paid_cookie_array as $coin_paid ) {
		        
		    $single_coin_paid_cookie_array = explode("-", $coin_paid);
		    
		    $coin_symbol = strtoupper(preg_replace("/_paid/i", "", $single_coin_paid_cookie_array[0]));  
		    		
		    		// We don't need remove_number_format() for cookie data, because it was already done creating the cookies
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_paid_value = number_to_string($single_coin_paid_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_leverage'] ) {
	        
	        $all_coin_leverage_cookie_array = explode("#", $_COOKIE['coin_leverage']);
	        
		if (is_array($all_coin_leverage_cookie_array) || is_object($all_coin_leverage_cookie_array)) {
		    
		    foreach ( $all_coin_leverage_cookie_array as $coin_leverage ) {
		        
		    $single_coin_leverage_cookie_array = explode("-", $coin_leverage);
		    
		    $coin_symbol = strtoupper(preg_replace("/_leverage/i", "", $single_coin_leverage_cookie_array[0]));  
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_leverage_value = $single_coin_leverage_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_margintype'] ) {
	        
	        $all_coin_margintype_cookie_array = explode("#", $_COOKIE['coin_margintype']);
	        
		if (is_array($all_coin_margintype_cookie_array) || is_object($all_coin_margintype_cookie_array)) {
		    
		    foreach ( $all_coin_margintype_cookie_array as $coin_margintype ) {
		        
		    $single_coin_margintype_cookie_array = explode("-", $coin_margintype);
		    
		    $coin_symbol = strtoupper(preg_replace("/_margintype/i", "", $single_coin_margintype_cookie_array[0]));  
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_margintype_value = $single_coin_margintype_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	      
	      
	    
	    	if ( strtoupper($coin_array_key) == 'MISCASSETS' ) {
	    	$asset_amount_decimals = 2;
	    	}
	    	else {
	    	$asset_amount_decimals = 8;
	    	}
	    
	    
	  	 $asset_amount_value = pretty_numbers($asset_amount_value, $asset_amount_decimals, TRUE); // TRUE = Show even if low value is off the map, just for UX purposes tracking token price only, etc
	    
	    
	    $coin_paid_value = ( number_to_string($coin_paid_value) >= $app_config['primary_currency_decimals_max_threshold'] ? pretty_numbers($coin_paid_value, 2) : pretty_numbers($coin_paid_value, $app_config['primary_currency_decimals_max']) );
	  	 
	    	
	    ?>
	    
	    <div class='<?=$zebra_stripe?> long_list_taller' style='white-space: nowrap;'> 
	       
	       
	       <input type='checkbox' value='<?=strtolower($coin_array_key)?>' id='<?=$field_var_watchonly?>' onchange='watch_toggle(this);' <?=( remove_number_format($asset_amount_value) > 0 && remove_number_format($asset_amount_value) <= '0.000000001' ? 'checked' : '' )?> /> &nbsp;
				    
				    
			<b class='blue'><?=$coin_array_value['coin_name']?> (<?=strtoupper($coin_array_key)?>)</b> /  
	       
	       
				    <select onchange='
				    
				    $("#<?=$field_var_market?>_lists").children().hide(); 
				    $("#" + this.value + "<?=$coin_array_key?>_pairs").show(); 
				    
				    $("#<?=$field_var_market?>").val( $("#" + this.value + "<?=$coin_array_key?>_pairs option:selected").val() );
				    
				    ' id='<?=$field_var_pairing?>' name='<?=$field_var_pairing?>'>
					<?php
					
					// Get default BITCOIN pairing key for further down in the logic, if no $coin_pairing_id value was set FOR BITCOIN
					if ( strtolower($coin_array_value['coin_name']) == 'bitcoin' ) {
					$selected_pairing = ( isset($coin_pairing_id) ? $coin_pairing_id : $app_config['btc_primary_currency_pairing'] );
					}
					else {
					$selected_pairing = $coin_pairing_id;
					}
					
					
					foreach ( $coin_array_value['market_pairing'] as $pairing_key => $pairing_id ) {
					 	
					 	// Set pairing key if not set yet (values not yet populated etc)
					 	if ( !isset($selected_pairing) ) {
					 	$selected_pairing = $pairing_key;
					 	}
						
					?>
					<option value='<?=$pairing_key?>' <?=( $selected_pairing == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
					
									foreach ( $coin_array_value['market_pairing'][$pairing_key] as $market_key => $market_id ) {
									$loop2 = $loop2 + 1;
							
									$html_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( 
									isset($coin_market_id) && ($coin_market_id) == $loop2 
									|| !isset($coin_market_id) && strtolower($coin_array_value['coin_name']) == 'bitcoin' && $loop2 == btc_market($app_config['btc_primary_exchange']) ? ' selected ' : '' ) . ">" . snake_case_to_name($market_key) . " </option>\n";
								
									}
									$loop2 = NULL;
							
							
					}
					?>
				    </select> 
				    
				    
				     @ <input type='hidden' id='<?=$field_var_market?>' name='<?=$field_var_market?>' value='<?php
				     
				     if ( $_POST[$field_var_market] ) {
				     echo $_POST[$field_var_market];
				     }
				     elseif ( isset($coin_market_id) ) {
				     echo $coin_market_id;
				     }
				     elseif ( !isset($coin_market_id) && strtolower($coin_array_value['coin_name']) == 'bitcoin' ) {
				     echo btc_market($app_config['btc_primary_exchange']);
				     }
				     else {
						echo '1';
				     }
				     
				     ?>'>
				     
				     
				     <span id='<?=$field_var_market?>_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pairing: <?=$selected_pairing?> -->
				    <?php
				    
				    foreach ( $html_market_list as $key => $value ) {
				    ?>
				    
				    <select onchange ='
				    
				    $("#<?=$field_var_market?>").val( this.value );
				    
				    ' id='<?=$key.$coin_array_key?>_pairs' style='display: <?=( $selected_pairing == $key ? 'inline' : 'none' )?>;'><?=$html_market_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $html_market_list = NULL;
				    ?>
				    
				    </span> &nbsp;  &nbsp; 
				    
				    
			
	     			 <b>Holdings:</b> <input type='text' size='11' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$asset_amount_value?>' onkeyup='
	     
	     $("#<?=strtolower($coin_array_key)?>_restore").val( $("#<?=strtolower($coin_array_key)?>_amount").val() );
	     
	     ' onblur='
	     
	     $("#<?=strtolower($coin_array_key)?>_restore").val( $("#<?=strtolower($coin_array_key)?>_amount").val() );
	     
	     ' <?=( remove_number_format($asset_amount_value) > 0 && remove_number_format($asset_amount_value) <= '0.000000001' ? 'readonly' : '' )?> /> <span class='blue'><?=strtoupper($coin_array_key)?></span>  &nbsp;  &nbsp; 
			    
			
	     <b>Average Paid (per-token):</b> <?=$app_config['bitcoin_currency_markets'][$app_config['btc_primary_currency_pairing']]?><input type='text' size='10' id='<?=$field_var_paid?>' name='<?=$field_var_paid?>' value='<?=$coin_paid_value?>' /> 
	     
	     
		<img id='average_paid_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
	
			$('#average_paid_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
			contents: average_paid_notes,
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
		   &nbsp;  &nbsp; 
	     
	     
	     <b>Margin Leverage:</b> 
	     
	     <select name='<?=$field_var_leverage?>' id='<?=$field_var_leverage?>' onchange='
	     if ( this.value <= 5 ) {
	     var mode = "Sane";
	     }
	     else if ( this.value <= 10 ) {
	     var mode = "Mostly Sane";
	     }
	     else if ( this.value <= 20 ) {
	     var mode = "Half Sane";
	     }
	     else if ( this.value <= 30 ) {
	     var mode = "Insane";
	     }
	     else if ( this.value <= 40 ) {
	     var mode = "Crazy";
	     }
	     else if ( this.value <= 50 ) {
	     var mode = "Batshit Crazy";
	     }
	     else if ( this.value > 50 ) {
	     var mode = "Beyond Batshit Crazy";
	     }
	     alert(" " + this.value + "x (" + mode + " Mode) \n Leverage trading in crypto assets is \n EXTREMELY RISKY. NEVER put more \n than ~5% of your crypto investments \n in leveraged trades EVER, OR YOU \n ###COULD LOSE EVERYTHING###. ");
	     '>
	     <option value='0' <?=( $coin_leverage_value == 0 ? 'selected' : '' )?>> None </option>
	     <?php
	     $leverage_count = 2;
	     while ( $app_config['margin_leverage_max'] > 1 && $leverage_count <= $app_config['margin_leverage_max'] ) {
	     ?>	     
	     <option value='<?=$leverage_count?>' <?=( $coin_leverage_value == $leverage_count ? 'selected' : '' )?>> <?=$leverage_count?>x </option>
	     <?php
	     $leverage_count = $leverage_count + 1;
	     }
	     ?>
	     </select> 
	     
	     
	     <select name='<?=$field_var_margintype?>' id='<?=$field_var_margintype?>'>
	     <option value='long' <?=( $coin_margintype_value == 'long' ? 'selected' : '' )?>> Long </option>
	     <option value='short' <?=( $coin_leverage_value >= 2 && $coin_margintype_value == 'short' ? 'selected' : '' )?>> Short </option>
	     </select> 
	     
	     
		<img id='leverage_trading_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
		
			$('#leverage_trading_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
			contents: leverage_trading_notes,
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
		 
	     
	     
	     <input type='hidden' id='<?=$field_var_restore?>' name='<?=$field_var_restore?>' value='<?=( remove_number_format($asset_amount_value) > 0 && remove_number_format($asset_amount_value) <= '0.000000001' ? '' : $asset_amount_value )?>' />
				
				
	    </div>
	    
	    
	    <?php
	    
		 	if ( $zebra_stripe == 'long_list_odd' ) {
		 	$zebra_stripe = 'long_list_even';
		 	}
		 	else {
		 	$zebra_stripe = 'long_list_odd';
		 	}
	    
	    $coin_symbol = NULL;
	    
	    $coin_pairing_id = NULL;
	    $coin_market_id = NULL;
	    $asset_amount_value = NULL;
 		 $coin_paid_value = NULL;
	    
	    }
	    
	    
	}
	?>
	
	<div class='long_list_end'> &nbsp; </div>
	
	
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	
	<input type='hidden' id='theme_selected' name='theme_selected' value='<?=$theme_selected?>' />
	
	<input type='hidden' id='sort_by' name='sort_by' value='<?=($sorted_by_col)?>|<?=($sorted_by_asc_desc)?>' />
	
	<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_notes' name='use_notes' value='<?php echo ( $_COOKIE['notes_reminders'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?=( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] )?>' />
	
	<input type='hidden' id='show_charts' name='show_charts' value='<?=( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] )?>' />
	
	<input type='hidden' id='primary_currency_market_standalone' name='primary_currency_market_standalone' value='<?=( $_POST['primary_currency_market_standalone'] != '' ? $_POST['primary_currency_market_standalone'] : $_COOKIE['primary_currency_market_standalone'] )?>' />
			
	<p><input type='submit' value='Save Updated Assets' /></p>
	
	</form>
	
	
			    
			    
</div> <!-- full_width_wrapper END -->



