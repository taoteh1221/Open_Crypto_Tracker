<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */



// Trim whitespace
if ( $_POST['hive_submitted'] ) {
$_POST = $ct_var->clean_array($_POST);
}

// Get HIVE value
$hive_mrkt = $ct_api->market('HIVE', 'bittrex', 'HIVE-BTC')['last_trade'];

?>
    

<div>
    <form action='<?=$ct_gen->start_page('mining')?>' method='post'>
        
        <p><b>Power Down Period:</b> <?=$ct_conf['power']['hive_powerdown_time']?> weeks</p>
	
        <p><b>HIVE Power Interest Rate:</b> <?=($ct_conf['power']['hivepower_yearly_interest'])?> percent annually (see Power User Config for yearly adjustments)</p>
	
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

<p class='red' style='font-weight: bold;'>Your <i>current</i> HIVE Power interest rate results (<i><u><?=strtoupper($ct_conf['gen']['btc_prim_currency_pair'])?> values may change significantly over long periods of time</u></i>):</p>

<p class='green' style='font-weight: bold;'>1 HIVE = <?=$ct_var->num_to_str($hive_mrkt)?> BTC (<?=$ct_conf['power']['btc_currency_mrkts'][ $ct_conf['gen']['btc_prim_currency_pair'] ]?><?php echo number_format( $ct_var->num_to_str( $hive_mrkt * $sel_opt['sel_btc_prim_currency_val'] ), 8, '.', ','); ?>)</p>


<?php

$ct_asset->hivepower_time('day');
$ct_asset->hivepower_time('week');
$ct_asset->hivepower_time('month');


}
?>