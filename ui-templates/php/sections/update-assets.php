<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<div class='force_1200px_wrapper'>
	
				
	
				<h4 style='display: inline;'>Update Assets</h4>
				
	<p style='margin-top: 10px;'><a style='font-weight: bold;' class='show red' id='disclaimer' href='#show_disclaimer' title='Click to show disclaimer.' onclick='return false;'>Disclaimer</a></p>
	    
		<div style='display: none;' class='show_disclaimer' align='left'>
			
	     
						<p class='red' style='font-weight: bold;'>Assets in the default list DO NOT indicate ANY endorsement of these assets (AND removal indicates NO anti-endorsement). These crypto-assets are merely either interesting, historically popular, or (at time off addition) good ROI for cryptocurrency mining hardware. They are only used as <i>examples for demoing feasibility of features</i> in this application, <a href='README.txt' target='_blank'>before you install it on your own PHP-enabled web server and change the list to your favorite assets</a>. Always do your due diligence investigating whether you are engaging in trading within acceptable risk levels for your net worth, and consider consulting a professional if you are unaware of what risks are present.</p>
	
						<p class='red' style='font-weight: bold;'><i><u>Semi-simplified version of above important disclaimer / advisory</u>:</i> <i>NEVER</i> invest more than you can afford to lose, <i>NEVER</i> buy an asset because of somebody's opinion of it, <i>ALWAYS <u>fully research</u></i> your planned investment beforehand, <i>ALWAYS</i> diversify for you <i>(and yours)</i> safety / sanity, <i>ALWAYS <u>buy low</u></i> AND <u>sell high</u></i> (NOT the other way around), <i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading, <i>AND</i> hang on tight till you can't stand fully holding anymore / want to or must make a position exit (percentage) official. Best of luck, be careful out there in this cryptoland frontier <i>full of scams and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues! :-o</p>
	
		
		</div>
			
			
	<p><a style='font-weight: bold;' href='README.txt' target='_blank'>Editing The Coin List, or Enabling Email / Text / Alexa Exchange Price Alerts</a></p>
	
	<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
	<p><button class='force_button_style' onclick='document.coin_amounts.submit();'>Save Updated Assets</button></p>
	
	
	<form id='coin_amounts' name='coin_amounts' action='<?=start_page($_GET['start_page'])?>' method='post'>
	
	
	<?php
	
	if (is_array($coins_list) || is_object($coins_list)) {
	    
	    
	    $zebra_stripe = 'f8f6f6';
	    foreach ( $coins_list as $coin_array_key => $coin_array_value ) {
		
	    
	    $field_var_pairing = strtolower($coin_array_key) . '_pairing';
	    $field_var_market = strtolower($coin_array_key) . '_market';
	    $field_var_amount = strtolower($coin_array_key) . '_amount';
	    $field_var_paid = strtolower($coin_array_key) . '_paid';
	    
	    
	        if ( $_POST['submit_check'] == 1 ) {
	        $coin_pairing_id = $_POST[$field_var_pairing];
	        $coin_market_id = $_POST[$field_var_market];
	        $coin_amount_value = $_POST[$field_var_amount];
	        $coin_paid_value = $_POST[$field_var_paid];
	        }
	        
	
	    
	        if ( $_COOKIE['coin_pairings'] ) {
	        
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
	        
	        
	        
	        if ( $_COOKIE['coin_markets'] ) {
	        
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
	        
	
	        if ( $_COOKIE['coin_amounts'] ) {
	        
	        $all_coin_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	        
		if (is_array($all_coin_amounts_cookie_array) || is_object($all_coin_amounts_cookie_array)) {
		    
		    foreach ( $all_coin_amounts_cookie_array as $coin_amounts ) {
		        
		    $single_coin_amounts_cookie_array = explode("-", $coin_amounts);
		    
		    $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_amounts_cookie_array[0]));
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_amount_value = $single_coin_amounts_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( $_COOKIE['coin_paid'] ) {
	        
	        $all_coin_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	        
		if (is_array($all_coin_paid_cookie_array) || is_object($all_coin_paid_cookie_array)) {
		    
		    foreach ( $all_coin_paid_cookie_array as $coin_paid ) {
		        
		    $single_coin_paid_cookie_array = explode("-", $coin_paid);
		    
		    $coin_symbol = strtoupper(preg_replace("/_paid/i", "", $single_coin_paid_cookie_array[0]));
		    
					if ( $coin_symbol == strtoupper($coin_array_key) ) {
					$coin_paid_value = $single_coin_paid_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	        
	        
	    $selected_pairing = ( $coin_pairing_id ? $coin_pairing_id : 'btc' );
	    
	    
	    ?>
	    
	    <div class='long_list' style='background-color: #<?=$zebra_stripe?>;'>
	       
	       
				    
			<b class='blue'><?=$coin_array_value['coin_name']?> (<?=strtoupper($coin_array_key)?>)</b> /  
	       
				    <select onchange='
				    
				    $("#<?=$field_var_market?>_lists").children().hide(); 
				    $("#" + this.value + "<?=$coin_array_key?>_pairs").show(); 
				    
				    $("#<?=$field_var_market?>").val( $("#" + this.value + "<?=$coin_array_key?>_pairs option:selected").val() );
				    
				    ' id='<?=$field_var_pairing?>' name='<?=$field_var_pairing?>'>
					<?php
					foreach ( $coin_array_value['market_pairing'] as $pairing_key => $pairing_id ) {
					 $loop = $loop + 1;
					 
						if ( $coin_array_key == 'BTC' ) {
						?>
						<option value='btc' selected> USD </option>
						<?php
						}
						else{
					?>
					<option value='<?=$pairing_key?>' <?=( isset($_POST[$field_var_pairing]) && ($_POST[$field_var_pairing]) == $pairing_key || isset($coin_pairing_id) && ($coin_pairing_id) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
							}
					
									foreach ( $coin_array_value['market_pairing'][$pairing_key] as $market_key => $market_id ) {
							$loop2 = $loop2 + 1;
							
								$html_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( isset($_POST[$field_var_market]) && ($_POST[$field_var_market]) == $loop2 || isset($coin_market_id) && ($coin_market_id) == $loop2 ? ' selected ' : '' ) . ">" . ucwords(preg_replace("/_/i", " ", $market_key)) . " </option>\n";
								
									}
								$loop2 = NULL;
							
							
					}
					$loop = NULL;
					?>
				    </select> 
				     Market @ <input type='hidden' id='<?=$field_var_market?>' name='<?=$field_var_market?>' value='<?php
				     
				     if ( $_POST[$field_var_market] ) {
				     echo $_POST[$field_var_market];
				     }
				     elseif ( $coin_market_id ) {
				     echo $coin_market_id;
				     }
				     else {
						echo '1';
				     }
				     
				     ?>'>
				     
				     <span id='<?=$field_var_market?>_lists' style='display: inline;'>
				    <?php
				    
				    foreach ( $html_market_list as $key => $value ) {
				    ?>
				    
				    <select onchange ='
				    
				    $("#<?=$field_var_market?>").val( this.value );
				    
				    ' id='<?=$key.$coin_array_key?>_pairs' style='display: <?=( $selected_pairing == $key ? 'inline' : 'none' )?>;'><?=$html_market_list[$key]?></select>
				    
				    <?php
				    }
				    $html_market_list = NULL;
				    ?>
				    
				    </span>, &nbsp; 
				    
			
	     <b>Amount:</b> <input type='text' size='12' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$coin_amount_value?>' /> <?=strtoupper($coin_array_key)?>, &nbsp; 
			    
			
	     <b>Paid (per-token):</b> $<input type='text' size='6' id='<?=$field_var_paid?>' name='<?=$field_var_paid?>' value='<?=$coin_paid_value?>' />
	     
				
	    </div>
	    
	    <?php
	    
		 	if ( $zebra_stripe == 'f8f6f6' ) {
		 	$zebra_stripe = 'ffffff';
		 	}
		 	else {
		 	$zebra_stripe = 'f8f6f6';
		 	}
	    
	    $coin_symbol = NULL;
	    $coin_amount_value = NULL;
	    
	    }
	    
	    
	}
	?>
	<div class='long_list_end'> &nbsp; </div>
	
	
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	
	<input type='hidden' id='sort_by' name='sort_by' value='<?=($sorted_by_col)?>|<?=($sorted_by_asc_desc)?>' />
	
	<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_notes' name='use_notes' value='<?php echo ( $_COOKIE['notes_reminders'] ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?=( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] )?>' />
	
	<input type='hidden' id='show_charts' name='show_charts' value='<?=( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] )?>' />
			
	<p><input type='submit' value='Save Updated Assets' /></p>
	
	</form>
	
	
			    
			    
</div> <!-- force_1200px_wrapper END -->



