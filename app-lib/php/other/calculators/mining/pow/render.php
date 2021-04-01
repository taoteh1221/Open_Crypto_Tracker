<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */
			
echo ( isset($pow_coin_data['height']) ? '<p><b>Block height:</b> ' . number_format( $pow_coin_data['height'] ) . '</p>' : '' );
echo ( isset($pow_coin_data['other_network_data']) ? $pow_coin_data['other_network_data'] : '' );
				
				
// Form submission results
if ( $_POST[$pow_coin_data['symbol'].'_submitted'] ) {
?>

<!-- Green colored START -->
<p class='green'>

<?php
include('app-lib/php/other/calculators/mining/pow/time-calculation.php');
include('app-lib/php/other/calculators/mining/pow/profit-calculation.php');
include('app-lib/php/other/calculators/mining/pow/earned-daily.php');
?>

</p>
<!-- Green colored END -->

<?php
}
			
				
// Render mining calc HTML form, with network measure name parameter
$ocpt_asset->mining_calc_form($pow_coin_data, $pow_coin_data['measure_semantic']); 
				
				
?>