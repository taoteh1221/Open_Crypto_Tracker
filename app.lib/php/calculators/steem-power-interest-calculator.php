<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Trim whitespace
if ( $_POST['steem_submitted'] ) {
$_POST = trim_array($_POST);
}

// Get STEEM value
$steem_market = get_trade_data('binance', 'STEEMBTC')['last_trade'];

?>
    
    <style>
    .result {
    margin: 7px; border: 2px solid black; padding: 7px;
    }
    .normal {
    font-weight: normal;
    }
    </style>
    


<p style='font-weight: bold; color: green;'>1 STEEM = <?=$steem_market?> BTC ($<?php echo number_format( ( $steem_market * get_btc_usd($btc_exchange)['last_trade'] ), 8, '.', ','); ?>)</p>

<p>
    <form action='index.php#calculators' method='post'>
        
        <p><b>Power Down Period:</b> <?=$steem_powerdown_time?> weeks</p>
	
        <p><b>STEEM Power Interest Rate:</b> <?=($steempower_yearly_interest)?> percent annually (see config file for yearly adjustments)</p>
	
        <p><b>STEEM Power Purchased:</b> <input type='text' name='sp_purchased' value='<?=$_POST['sp_purchased']?>' placeholder="(from Bittrex trading etc)" size='45' /></p>
        
        <p><b>STEEM Power Earned:</b> <input type='text' name='sp_earned' value='<?=$_POST['sp_earned']?>' placeholder="(voting, posting, mining)" size='45' /></p>
        
        <p><b>All STEEM Power:</b> <input type='text' name='sp_total' value='<?=$_POST['sp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><input type='submit' value='Calculate Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='steem_submitted' />
        
    </form>
</p>

<?php
if ( $_POST['steem_submitted'] ) {
?>

<p style='color: red; font-weight: bold;'>Your <i>current</i> STEEM Power interest rate results (<i><u>USD values may change significantly over long periods of time</u></i>):</p>

<?php

steempower_time('day');
steempower_time('week');
steempower_time('month');
steempower_time('2month');
steempower_time('3month');
steempower_time('6month');
steempower_time('9month');
steempower_time('12month');


}
?>