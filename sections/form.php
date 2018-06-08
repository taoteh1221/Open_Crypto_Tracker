<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<p style='color: red; font-weight: bold;'>Assets in the default list DO NOT indicate ANY endorsement of these assets (AND removal indicates NO anti-endorsement). These crypto-assets are merely either interesting, historically popular, or (at time off addition) good ROI for cryptocurrency mining hardware. They are only used as <i>examples for demoing feasibility of features</i> in this application, <a href='README.txt' target='_blank'>before you install it on your own PHP-enabled web server and change the list to your favorite assets</a>. Always do your due diligence investigating whether you are engaging in trading within acceptable risk levels for your net worth, and consider consulting a professional if you are unaware of what risks are present.</p>

<p style='color: red; font-weight: bold;'><i><u>Semi-simplified version of above important disclaimer / advisory</u>:</i> <i>NEVER</i> invest more than you can afford to lose, <i>NEVER</i> buy an asset because of somebody's opinion of it, <i>ALWAYS <u>fully research</u></i> your planned investment beforehand, <i>ALWAYS</i> diversify for you <i>(and yours)</i> safety / sanity, <i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading, <i>AND</i> hang on tight till you can't stand fully holding anymore / want to or must make a position exit (percentage) official. Best of luck, be careful out there in this cryptoland frontier <i>full of scams and greedy <u>glorified</u> (and NOT so glorified) crooks</i>! :-o</p>

<p style='font-weight: bold;'><a href='README.txt' target='_blank'>Editing The Coin List On Your Own Server</a></p>

<form name='coin_amounts' action='<?=$_SERVER['PHP_SELF']?>' method='post'>

<?php

if (is_array($coins_array) || is_object($coins_array)) {
    
    foreach ( $coins_array as $coin ) {
    
    $field_var_pairing = strtolower($coin['coin_symbol']) . '_pairing';
    $field_var_market = strtolower($coin['coin_symbol']) . '_market';
    $field_var_amount = strtolower($coin['coin_symbol']) . '_amount';
    
    
        if ( $_POST['submit_check'] == 1 ) {
        $coin_pairing_id = $_POST[$field_var_pairing];
        $coin_market_id = $_POST[$field_var_market];
        $coin_amount_value = $_POST[$field_var_amount];
        }
        

    
        if ( $_COOKIE['coin_pairings'] ) {
        
        $all_coin_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
        
            if (is_array($all_coin_pairings_cookie_array) || is_object($all_coin_pairings_cookie_array)) {
                
                foreach ( $all_coin_pairings_cookie_array as $coin_pairings ) {
                    
                $single_coin_pairings_cookie_array = explode("-", $coin_pairings);
                
                $coin_symbol = strtoupper(preg_replace("/_pairing/i", "", $single_coin_pairings_cookie_array[0]));
                
                    if ( $coin_symbol == strtoupper($coin['coin_symbol']) ) {
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
                
                    if ( $coin_symbol == strtoupper($coin['coin_symbol']) ) {
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
                
        				if ( $coin_symbol == strtoupper($coin['coin_symbol']) ) {
        				$coin_amount_value = $single_coin_amounts_cookie_array[1];
        				}
                
                
                }
                
            }
        
        
        }
        
        
        
    $selected_pairing = ( $coin_pairing_id ? $coin_pairing_id : $coin['default_pairing'] );
    
    ?>
    
    <p>
       
    <?=$coin['coin_name']?> (<?=strtoupper($coin['coin_symbol'])?>) 
    

    
    <select onchange='
    
    $("#<?=$field_var_market?>_lists").children().hide(); 
    $("#" + this.value + "<?=$coin['coin_symbol']?>_pairs").show(); 
    
    $("#<?=$field_var_market?>").val( $("#" + this.value + "<?=$coin['coin_symbol']?>_pairs option:selected").val() );
    
    ' id='<?=$field_var_pairing?>' name='<?=$field_var_pairing?>'>
        <?php
        foreach ( $coin['market_pairing'] as $pairing_key => $pairing_id ) {
         $loop = $loop + 1;
         
         	if ( $coin['coin_symbol'] == 'BTC' ) {
         	?>
         	<option value='btc' selected> USD </option>
         	<?php
         	}
         	else{
        ?>
        <option value='<?=$pairing_key?>' <?=( isset($_POST[$field_var_pairing]) && ($_POST[$field_var_pairing]) == $pairing_key || isset($coin_pairing_id) && ($coin_pairing_id) == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
        <?php
        		}
        
					foreach ( $coin['market_pairing'][$pairing_key] as $market_key => $market_id ) {
         		$loop2 = $loop2 + 1;
         		
        			$html_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( isset($_POST[$field_var_market]) && ($_POST[$field_var_market]) == $loop2 || isset($coin_market_id) && ($coin_market_id) == $loop2 ? ' selected ' : '' ) . ">" . ucwords(preg_replace("/_/i", " ", $market_key)) . " </option>\n";
        			
					}
        			$loop2 = NULL;
        		
        		
        }
        $loop = NULL;
        ?>
    </select> 
     market is <input type='hidden' id='<?=$field_var_market?>' name='<?=$field_var_market?>' value='<?php
     
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
    
    ' id='<?=$key.$coin['coin_symbol']?>_pairs' style='display: <?=( $selected_pairing == $key ? 'inline' : 'none' )?>;'><?=$html_market_list[$key]?></select>
    
    <?php
    }
    $html_market_list = NULL;
    ?>
    
    </span>, and amount is <input type='text' size='40' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$coin_amount_value?>' />
    
    </p>
    
    <?php
    $coin_symbol = NULL;
    $coin_amount_value = NULL;
    }
    
}
?>

<p><input type='submit' value='Update Coin Values Data' /></p>

<input type='hidden' id='submit_check' name='submit_check' value='1' />

<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] ? '1' : ''); ?>' />

<input type='hidden' id='use_notes' name='use_notes' value='<?php echo ( $_COOKIE['notes_reminders'] ? '1' : ''); ?>' />

<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?php echo $_COOKIE['alert_percent']; ?>' />

</form>



