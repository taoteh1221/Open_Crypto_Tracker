<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
			
echo ( isset($pow_asset_data['height']) ? '<p><b>Block height:</b> ' . number_format( $pow_asset_data['height'] ) . '</p>' : '' );
echo ( isset($pow_asset_data['other_network_data']) ? $pow_asset_data['other_network_data'] : '' );
				
				
// Form submission results
if ( $_POST[$pow_asset_data['symbol'].'_submitted'] ) {
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
$oct_asset->mining_calc_form($pow_asset_data, $pow_asset_data['measure_semantic']); 
				
				
?>