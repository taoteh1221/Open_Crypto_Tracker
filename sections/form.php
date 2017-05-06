<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

<p style='color: red; font-weight: bold;'>All assets added to the default list DO NOT indicate any endorsement of these assets (and removal indicates no opposition to the removed assets as well). Always do your due diligence investigating whether or not you are engaging in trading within acceptable risk levels for your net worth, and consider consulting a professional if you are unaware of what risks are present.</p>
<p style='color: red; font-weight: bold;'>Simplified version of above disclaimer: NEVER invest more than you can afford to lose, ALWAYS research your planned investment beforehand, AND hang on tight until you can't stand holding any longer. :-)</p>

<p style='font-weight: bold;'><a href='README.txt' target='_blank'>Editing The Coin List</a></p>

<form name='coin_amounts' action='<?=$_SERVER['PHP_SELF']?>' method='post'>

<?php
if (is_array($coins_array) || is_object($coins_array)) {
    
    foreach ( $coins_array as $coin ) {
    
    $field_var_amount = strtolower($coin['coin_symbol']) . '_amount';
    $field_var_market = strtolower($coin['coin_symbol']) . '_market';
    
    
        if ( $_POST['submit_check'] == 1 ) {
        $coin_amount_value = $_POST[$field_var_amount];
        }
        

        if ( $_COOKIE['coin_amounts'] ) {
        
        $all_coins_cookie_array = explode("#", $_COOKIE['coin_amounts']);
        
            if (is_array($all_coins_cookie_array) || is_object($all_coins_cookie_array)) {
                
                foreach ( $all_coins_cookie_array as $coin2 ) {
                    
                $single_coin_cookie_array = explode("-", $coin2);
                
                $coin_symbol = strtoupper(preg_replace("/_amount/i", "", $single_coin_cookie_array[0]));
                
                    if ( $coin_symbol == strtoupper($coin['coin_symbol']) ) {
                    $coin_amount_value = $single_coin_cookie_array[1];
                    }
                
             
                
                }
                
            }
        
        
        }
    
        if ( $_COOKIE['coin_markets'] ) {
        
        $all_coin_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
        
            if (is_array($all_coin_markets_cookie_array) || is_object($all_coin_markets_cookie_array)) {
                
                foreach ( $all_coin_markets_cookie_array as $coin_markets ) {
                    
                $single_coin_market_cookie_array = explode("-", $coin_markets);
                
                $coin_symbol = strtoupper(preg_replace("/_market/i", "", $single_coin_market_cookie_array[0]));
                
                    if ( $coin_symbol == strtoupper($coin['coin_symbol']) ) {
                    $coin_market_id = $single_coin_market_cookie_array[1];
                    }
                
             
                
                }
                
            }
        
        
        }
    
    ?>
    
    <p>
       
    <?=$coin['coin_name']?> (<?=strtoupper($coin['coin_symbol'])?>) 
    <?php
    // Only support for multiple markets per coin with BTC trade pairing
    if ( $coin['trade_pair'] == 'btc' || $coin['trade_pair'] == 'eth' ) {
    ?>
    <?=( $coin['coin_symbol'] != 'BTC' ? strtoupper($coin['trade_pair']) : 'USD' )?> Market is <select id='<?=$field_var_market?>' name='<?=$field_var_market?>'>
        <?php
        foreach ( $coin['market_ids'] as $market_key => $market_id ) {
         $loop = $loop + 1;
        ?>
        <option value='<?=$loop?>' <?=( isset($_POST[$field_var_market]) && ($_POST[$field_var_market]) == $loop || isset($coin_market_id) && ($coin_market_id) == $loop ? ' selected ' : '' )?>> <?=ucwords(preg_replace("/_/i", " ", $market_key))?> </option>
        <?php
        }
        $loop = NULL;
        ?>
    </select>, and 
    <?php
    }
    else {
    ?>
    <?=( $coin['coin_symbol'] != 'BTC' ? strtoupper($coin['trade_pair']) : 'USD' )?> Market is <?=ucwords(preg_replace("/_/i", " ", $coin['market_ids'][0]))?>, and 
    <?php
    }
    ?>
     Amount is <input type='text' size='40' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$coin_amount_value?>' />
    
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

</form>



