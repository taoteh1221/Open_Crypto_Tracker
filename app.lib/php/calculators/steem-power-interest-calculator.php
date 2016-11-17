<?php


// Trim whitespace
if ( $_POST['steem_submitted'] ) {
$_POST = trim_array($_POST);
}

// Get STEEM value
$steam_market = get_trade_price('poloniex', 'BTC_STEEM');

?>
    
    <style>
    .result {
    margin: 7px; border: 2px solid black; padding: 7px;
    }
    .normal {
    font-weight: normal;
    }
    </style>
    


<p style='font-weight: bold; color: green;'>1 STEEM = <?=$steam_market?> BTC ($<?=number_format( ( $steam_market * get_btc_usd($btc_in_usd) ), 8, '.', ',')?>)</p>

<p>
    <form action='index.php#tab4' method='post'>
        
        <p><b>Total Power Down Period:</b> <?=$steem_powerdown_time?> weeks</p>
	
        <p><b>Total STEEM Power Purchased:</b> <input type='text' name='sp_purchased' value='<?=$_POST['sp_purchased']?>' placeholder="(from Bittrex trading etc)" size='45' /></p>
        
        <p><b>Total STEEM Power Earned:</b> <input type='text' name='sp_earned' value='<?=$_POST['sp_earned']?>' placeholder="(voting and posting)" size='45' /></p>
        
        <p><b>Total of all STEEM Power:</b> <input type='text' name='sp_total' value='<?=$_POST['sp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><b>Your Current STEEM Power Interest Rate:</b> <input type='text' name='interest_speed' value='<?=$_POST['interest_speed']?>' placeholder="(STEEM per minute average)" size='45' /></p>
        
        <p><b style='color: red;'>To easily determine your current STEEM Power interest rate:</b>
        
            <ul>
                <li>Click the refresh / reload button on your browser at your steemit wallet page, and note the STEEM Power amount</li>
                <li>Wait exactly 5 minutes and refresh / reload again, and note the new STEEM Power amount</li>
                <li>Delete value #1 from value #2, and divide by 5</li>
                
            </ul>
        
        </p>
        
        <p><input type='submit' value='Calculate Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='steem_submitted' />
        
    </form>
</p>

<?php
if ( $_POST['steem_submitted'] ) {
?>

<p style='color: red; font-weight: bold;'>Your <i>current</i> STEEM Power interest rate results (USD value / STEEM interest rate <i>may change significantly over long periods of time</i>):</p>

<?php

steempower_time($_POST['interest_speed'], 'minute');
steempower_time($_POST['interest_speed'], 'hour');
steempower_time($_POST['interest_speed'], 'day');
steempower_time($_POST['interest_speed'], 'week');
steempower_time($_POST['interest_speed'], 'month');
steempower_time($_POST['interest_speed'], '2month');
steempower_time($_POST['interest_speed'], '3month');

}
?>