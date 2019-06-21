<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
			
			// Coin information, to dynamically populate general sections
			$calculation_form_data = array(
											'Grin', // Coin name
											'grin', // Coin symbol
											grin_api('height'), // Block height
											grin_api('target_difficulty'), // Mining network measure (difficulty or network hashrate)
											'https://grinmint.com/', // Blockchain data API url
											'Grinmint.com API', // Blockchain data API name
											'poloniex', // Exchange name (lowercase for API logic)
											'BTC_GRIN' // Market pair name
											);
			
			
			///////////////////////////////////////////////////////////////////////////
			
			echo '<p><b>Block height:</b> ' . number_format($calculation_form_data[2]) . '</p>';
				
			// Start form submission results
			if ( $_POST[$calculation_form_data[1].'_submitted'] ) {
				    
				include('results/post-data-processing.php'); // Generalized module
				
			///////////////////////////////////////////////////////////////////////////
			
				// Difficulty calculation for this coin...MAY BE DIFFERENT PER COIN
				
				// scale = (N-1) * 2^(N-30) for cuckooN cycles
				// https://github.com/mimblewimble/docs/wiki/FAQ
				
				$algo_network_hashrate = round( 42 * ( trim($_POST['network_measure']) / $_POST['cuckoo_cycles'] ) / 60 );
				
				$hashrate_percent = $miner_hashrate / $algo_network_hashrate;
				
				$mining_time = 60 / $hashrate_percent / 100;
							
				
				if ( $_POST['cuckoo_cycles'] < 30 ) {
				//$mining_time = $mining_time * 2;  // Had to do this to match grinmint's calculator? Why?
				}
			
				
			///////////////////////////////////////////////////////////////////////////
			
				include('results/time-calculation.php'); // Generalized module
				include('results/profit-calculation.php'); // Generalized module
				include('results/earned-daily.php'); // Generalized module
				
			}
			// End form submission results
				
				mining_calc_form($calculation_form_data, 'difficulty', 'graph'); // Generalized module, with network measure name parameter (difficulty or nethashrate)
				
			///////////////////////////////////////////////////////////////////////////
			
			?>