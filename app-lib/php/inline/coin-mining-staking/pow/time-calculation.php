<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
	

$minutes = ( $pow_asset_data['mining_time_formula'] / 60 );
				
$hours = ( $minutes / 60 );
				
$days = ( $hours / 24 );
				
$months = ( $days / 30 );
				
$years = ( $days / 365 );
				

if ( $minutes < 60 ) {
?>
<b class='blue'>Average minutes until block found:</b> 
<?php
echo $ct['var']->num_pretty($minutes, 2);
}
elseif ( $hours < 24 ) {
?>
<b class='blue'>Average hours until block found:</b> 
<?php
echo $ct['var']->num_pretty($hours, 2);
}
elseif ( $days < 30 ) {
?>
<b class='blue'>Average days until block found:</b> 
<?php
echo $ct['var']->num_pretty($days, 2);
}
elseif ( $days < 365 ) {
?>
<b class='blue'>Average months until block found:</b> 
<?php
echo $ct['var']->num_pretty($months, 2);
}
else {
?>
<b class='blue'>Average years until block found:</b> 

<span class="safe_non_table_num"><span class="num_conv"><?php
echo $ct['var']->num_pretty($years, 2);
}
?>
</span></span>

<?php
$thres_dec = $ct['gen']->thres_dec($days, 'u', 'fiat'); // Units mode
$days_pretty = $ct['var']->num_pretty($days, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
?>
<br />
<br />
<b class='bitcoin'>(SOLO MINING *DAILY* ODDS: 1 in <span class="safe_non_table_num"><span class="num_conv"><?=$days_pretty?></span></span> chance of mining a block)</b>
<?php
				
$calculate_daily = ( 24 / $hours );
				
$daily_avg = $calculate_daily * trim($_POST['block_reward']);
				
?>