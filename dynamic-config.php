<?php
/*
 * Copyright 2014-2020 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */
	

// Mining calculator configs for different crypto networks (SEMI-AUTOMATICALLY adds mining calculators to the Mining page)
// FOR #DYNAMIC# CHAIN STATS (height / difficuly / rewards / etc), API CALL FUNCTIONS NEED TO BE CUSTOM-WRITTEN FOR ANY #CUSTOM# ASSETS ADDED HERE,
// AND CALLED WITHIN THE 'Update dynamic mining data' SECTION OF THE FILE: /app-lib/php/inline/config/config-auto-adjust.php
// 'mining_time_formula' ALSO NEEDS TO BE DYNAMICALLY ADDED IN THAT SAME SECTION, #OR YOUR CUSTOM CALCULATOR WILL NOT WORK AT ALL#
// ('PLACEHOLDER' values are dynamically populated during app runtime)
$ct['opt_conf']['mining_calculators'] = array(
					
					
			// POW CALCULATORS
			'pow' => array(
					
					
					// BTC
					'btc' => array(
									'name' => 'Bitcoin', // Coin name
									'symbol' => 'btc', // Coin symbol (lowercase)
									'exchange_name' => 'binance', // Exchange name (for price data, lowercase)
									'exchange_mrkt' => 'BTCUSDT', // Market pair name (for price data)
									'measure_semantic' => 'difficulty',  // (difficulty, nethashrate, etc)
									'block_reward' => 'PLACEHOLDER', // Mining block reward (OPTIONAL, can be made dynamic with code, like below)
									// EVERYTHING BELOW #MUST BE DYNAMICALLY# UPDATED (for a clean / non-confusing PRIMARY config)
									'mining_time_formula' => 'PLACEHOLDER', // Mining time formula calculation (REQUIRED)
									'height' => 'PLACEHOLDER', // Block height (OPTIONAL)
									'difficulty' => 'PLACEHOLDER', // Mining network difficulty (OPTIONAL)
									'other_network_data' => '', // Leave blank to skip (OPTIONAL)
									),
					
					
			), // POW END
					
			
); // MINING CALCULATORS END
				
				

// MINING DYNAMIC CONFIGS

// BTC

// Bitcoin mining data (5 minute cache)
$bitcoin_mining = $ct['api']->blockchain_rpc('bitcoin', 'getmininginfo', false, 5)['result'];

// Bitcoin get latest block hash (5 minute cache)
$bitcoin_last_block_hash = $ct['api']->blockchain_rpc('bitcoin', 'getbestblockhash', false, 5)['result'];

// Bitcoin get latest block stats (5 minute cache)
$bitcoin_last_block_stats = $ct['api']->blockchain_rpc('bitcoin', 'getblockstats', array($bitcoin_last_block_hash), 5)['result'];

$ct['opt_conf']['mining_calculators']['pow']['btc']['block_reward'] = $ct['var']->num_to_str($bitcoin_last_block_stats['subsidy'] / 100000000);
$ct['opt_conf']['mining_calculators']['pow']['btc']['height'] = $bitcoin_mining['blocks'];
$ct['opt_conf']['mining_calculators']['pow']['btc']['difficulty'] = $bitcoin_mining['difficulty'];


/* // ETH (NO LONGER USED, BUT LEAVE AS EXAMPLE FOR FUTURE POW CALCS)
$ct['opt_conf']['mining_calculators']['pow']['eth']['height'] = hexdec( $ct['api']->etherscan('number') );      
$ct['opt_conf']['mining_calculators']['pow']['eth']['difficulty'] = hexdec( $ct['api']->etherscan('difficulty') );
$ct['opt_conf']['mining_calculators']['pow']['eth']['other_network_data'] = '<p><b>Gas limit:</b> ' . number_format( hexdec( $ct['api']->etherscan('gasLimit') ) ) . '</p>' . ( $ct['api']->etherscan('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );
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
		$ct['opt_conf']['mining_calculators']['pow']['btc']['mining_time_formula'] = trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate;
		}
		
		
		/* // ETH (NO LONGER USED, BUT LEAVE AS EXAMPLE FOR FUTURE POW CALCS)
		elseif ( $_POST['pow_calc'] == 'eth' ) {
		$ct['opt_conf']['mining_calculators']['pow']['eth']['mining_time_formula'] = trim($_POST['network_measure']) / $miner_hashrate;
		}
		*/
		
	
	}
	

?>