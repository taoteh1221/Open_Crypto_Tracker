<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
	


// BTC
$ct['conf']['power']['mining_calculators']['pow']['btc']['height'] = $ct['api']->bitcoin('height');
$ct['conf']['power']['mining_calculators']['pow']['btc']['difficulty'] = $ct['api']->bitcoin('difficulty');


/* // ETH (NO LONGER USED, BUT LEAVE AS EXAMPLE FOR FUTURE POW CALCS)
$ct['conf']['power']['mining_calculators']['pow']['eth']['height'] = hexdec( $ct['api']->etherscan('number') );      
$ct['conf']['power']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $ct['api']->etherscan('difficulty') );
$ct['conf']['power']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $ct['api']->etherscan('gasLimit') ) ) . '</p>' . ( $ct['api']->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );
*/

	
	// If a mining calculator is being used this runtime, include mining time formula calculations for that chain
	if ( isset($_POST['pow_calc']) ) {
				    
	$_POST['network_measure'] = $ct['var']->rem_num_format($_POST['network_measure']);
				    
	$_POST['your_hashrate'] = $ct['var']->rem_num_format($_POST['your_hashrate']);
		
	$miner_hashrate = trim($_POST['your_hashrate']) * trim($_POST['hash_level']);
	
	
		// Mining time formulas can be different per network, unless they copy Bitcoin's formula
		// BTC
		if ( $_POST['pow_calc'] == 'btc' ) {
		// https://en.bitcoin.it/wiki/Difficulty (How soon might I expect to generate a block?)
		$ct['conf']['power']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		
		
		/* // ETH (NO LONGER USED, BUT LEAVE AS EXAMPLE FOR FUTURE POW CALCS)
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$ct['conf']['power']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
		*/
		
	
	}
	

?>