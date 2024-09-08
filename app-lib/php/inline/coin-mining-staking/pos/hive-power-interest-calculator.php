<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



// Trim whitespace
if ( $_POST['hive_submitted'] ) {
$_POST = $ct['var']->clean_array($_POST);
}

// Get HIVE value
$hive_mrkt = $ct['api']->market('HIVE', 'binance', 'HIVEBTC')['last_trade'];

?>
    

<div>
    <form action='<?=$ct['gen']->start_page('mining')?>' method='post'>
        
        <p><b>Power Down Period:</b> <?=$ct['conf']['currency']['hive_powerdown_time']?> weeks</p>
	
        <p><b>HIVE Power Interest Rate:</b> <?=($ct['conf']['currency']['hivepower_yearly_interest'])?> percent annually (see Admin Area's "Currency Support" Config for adjustments)</p>
	
        <p><b>HIVE Power Purchased:</b> <input type='text' name='hp_purchased' value='<?=$_POST['hp_purchased']?>' placeholder="(from trading etc)" size='45' /></p>
        
        <p><b>HIVE Power Earned:</b> <input type='text' name='hp_earned' value='<?=$_POST['hp_earned']?>' placeholder="(voting, posting, mining)" size='45' /></p>
        
        <p><b>All HIVE Power:</b> <input type='text' name='hp_total' value='<?=$_POST['hp_total']?>' placeholder="(including interest)" size='45' /></p>
        
        <p><input type='submit' value='Calculate HIVE Interest / Power Down Weekly Payout Amounts Over Time' /></p>
        
				    <input type='hidden' value='1' name='hive_submitted' />
        
    </form>
</div>

<?php
if ( $_POST['hive_submitted'] ) {
?>

<p class='red' style='font-weight: bold;'>Your <i>current</i> HIVE Power interest rate results (<i><u><?=strtoupper($ct['conf']['currency']['bitcoin_primary_currency_pair'])?> values may change significantly over long periods of time</u></i>):</p>

<?php
$hive_val_raw = number_format( ( $hive_mrkt * $ct['sel_opt']['sel_btc_prim_currency_val'] ) , $ct['conf']['currency']['crypto_decimals_max'], '.', ',');
$hive_val_raw = $ct['var']->num_to_str($hive_val_raw); // Cleanup any trailing zeros
?>


<p class='green' style='font-weight: bold;'>1 HIVE = <?=$ct['var']->num_to_str($hive_mrkt)?> BTC (<?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?><?php echo $hive_val_raw; ?>)</p>


<?php

$ct['asset']->hivepower_time('day');
$ct['asset']->hivepower_time('week');
$ct['asset']->hivepower_time('month');


}
?>