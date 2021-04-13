<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



// Trim whitespace
if ( $_POST['hive_submitted'] ) {
$_POST = $pt_var->clean_array($_POST);
}

// Get HIVE value
$hive_market = $pt_api->market('HIVE', 'bittrex', 'HIVE-BTC')['last_trade'];

?>
    

<div>
    <form action='<?=$pt_gen->start_page('mining')?>' method='post'>
        
        <p><b>Power Down Period:</b> <?=$pt_conf['power']['hive_powerdown_time']?> weeks</p>
	
        <p><b>HIVE Power Interest Rate:</b> <?=($pt_conf['power']['hivepower_yearly_interest'])?> percent annually (see Power User Config for yearly adjustments)</p>
	
        <p><b>HIVE Power Purchased:</b> <input type='text' name='hp_purchased' value='<?=$_POST['hp_purchased']?>' placeholder="(from Bittrex trading etc)" size='45' /></p>
        
        <p><b>HIVE Power Earned:</b> <input type='text' name='hp_earned' value='<?=$_POST['hp_earned']?>' placeholder="(voting, posting, mining)" size='45' /></p>
        
        <p><b>All HIVE Power:</b> <input type='text' name='hp_total' value='<?=$_POST['hp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><input type='submit' value='Calculate HIVE Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='hive_submitted' />
        
    </form>
</div>

<?php
if ( $_POST['hive_submitted'] ) {
?>

<p class='red' style='font-weight: bold;'>Your <i>current</i> HIVE Power interest rate results (<i><u><?=strtoupper($pt_conf['gen']['btc_prim_currency_pairing'])?> values may change significantly over long periods of time</u></i>):</p>

<p class='green' style='font-weight: bold;'>1 HIVE = <?=$pt_var->num_to_str($hive_market)?> BTC (<?=$pt_conf['power']['btc_currency_markets'][ $pt_conf['gen']['btc_prim_currency_pairing'] ]?><?php echo number_format( $pt_var->num_to_str( $hive_market * $sel_btc_prim_currency_val ), 8, '.', ','); ?>)</p>


<?php

$pt_asset->hivepower_time('day');
$pt_asset->hivepower_time('week');
$pt_asset->hivepower_time('month');
$pt_asset->hivepower_time('2month');
$pt_asset->hivepower_time('3month');
$pt_asset->hivepower_time('6month');
$pt_asset->hivepower_time('9month');
$pt_asset->hivepower_time('12month');


}
?>