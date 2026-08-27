<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */
			
echo ( isset($pow_asset_data['height']) ? '<p><b class="blue">Block height:</b> <span class="safe_non_table_num"><span class="num_conv">' . number_format( $pow_asset_data['height'] ) . '</span></span></p>' : '' );
echo ( isset($pow_asset_data['other_network_data']) ? $pow_asset_data['other_network_data'] : '' );
				
				
// Form submission results
if ( $_POST[$pow_asset_data['symbol'].'_submitted'] ) {
?>

<!-- Green colored START -->
<p class='green'>

<?php
include('app-lib/php/inline/coin-mining-staking/pow/time-calculation.php');
include('app-lib/php/inline/coin-mining-staking/pow/profit-calculation.php');
include('app-lib/php/inline/coin-mining-staking/pow/earned-daily.php');
?>

</p>
<!-- Green colored END -->

<?php
}
			
				
// Render mining calc HTML form, with network measure name parameter
$ct['asset']->mining_calc_form($pow_asset_data, $pow_asset_data['measure_semantic']); 
				
				
?>