<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */



// Trim whitespace
if ( $_POST['steem_submitted'] ) {
$_POST = trim_array($_POST);
}

// Get STEEM value
$steem_market = asset_market_data('STEEM', 'binance', 'STEEMBTC')['last_trade'];

?>
    

<div>
    <form action='<?=start_page('mining_calculators')?>' method='post'>
        
        <p><b>Power Down Period:</b> <?=$steem_powerdown_time?> weeks</p>
	
        <p><b>STEEM Power Interest Rate:</b> <?=($steempower_yearly_interest)?> percent annually (see config file for yearly adjustments)</p>
	
        <p><b>STEEM Power Purchased:</b> <input type='text' name='sp_purchased' value='<?=$_POST['sp_purchased']?>' placeholder="(from Bittrex trading etc)" size='45' /></p>
        
        <p><b>STEEM Power Earned:</b> <input type='text' name='sp_earned' value='<?=$_POST['sp_earned']?>' placeholder="(voting, posting, mining)" size='45' /></p>
        
        <p><b>All STEEM Power:</b> <input type='text' name='sp_total' value='<?=$_POST['sp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><input type='submit' value='Calculate STEEM Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='steem_submitted' />
        
    </form>
</div>

<?php
if ( $_POST['steem_submitted'] ) {
?>

<p class='red' style='font-weight: bold;'>Your <i>current</i> STEEM Power interest rate results (<i><u><?=strtoupper($btc_primary_currency_pairing)?> values may change significantly over long periods of time</u></i>):</p>

<p class='green' style='font-weight: bold;'>1 STEEM = <?=$steem_market?> BTC (<?=$bitcoin_market_currencies[$btc_primary_currency_pairing]?><?php echo number_format( ( $steem_market * $btc_market_value ), 8, '.', ','); ?>)</p>


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