<?php
/*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>

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
    if ( sizeof($coin['markets']) > 1 && $coin['trade_pair'] == 'btc' ) {
    ?>
    <?=strtoupper($coin['trade_pair'])?> Market is <select id='<?=$field_var_market?>' name='<?=$field_var_market?>'>
        <?php
        foreach ( $coin['markets'] as $market_key => $market_name ) {
         // Avoid possible null equivelent issue by upping post value +1 in case zero
        ?>
        <option value='<?=($market_key + 1)?>' <?=( isset($_POST[$field_var_market]) && ($_POST[$field_var_market] - 1) == $market_key || isset($coin_market_id) && ($coin_market_id - 1) == $market_key ? ' selected ' : '' )?>> <?=ucfirst($market_name)?> </option>
        <?php
        }
        ?>
    </select>, and 
    <?php
    }
    elseif ( $coin['coin_symbol'] == 'BTC' ) { // Coinbase USD market for Bitcoin trading
    ?>
    USD Market is Coinbase, and 
    <?php
    }
    else {
    ?>
    <?=strtoupper($coin['trade_pair'])?> Market is <?=ucfirst($coin['markets'][0])?>, and 
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

<input type='hidden' id='submit_check' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] ? '1' : ''); ?>' />

</form>



