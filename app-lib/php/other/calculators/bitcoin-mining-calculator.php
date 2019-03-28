<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
			
			// Coin information, to dynamically populate general sections
			$calculation_form_data = array(
											'Bitcoin', // Coin name
											'btc', // Coin symbol
											bitcoin_api('height'), // Block height
											bitcoin_api('difficulty'), // Mining network measure (difficulty or network hashrate)
											'https://blockexplorer.com/api-ref', // Blockchain data API url
											'Blockexplorer API', // Blockchain data API name
											'binance', // Exchange name (lowercase for API logic)
											'BTCTUSD' // Market pair name
											);
			
			
			///////////////////////////////////////////////////////////////////////////
			
			echo '<p><b>Block height:</b> ' . number_format($calculation_form_data[2]) . '</p>';
				
			// Start form submission results
			if ( $_POST[$calculation_form_data[1].'_submitted'] ) {
				    
				include('results/post-data-processing.php'); // Generalized module
				
				
			///////////////////////////////////////////////////////////////////////////
			
				// Difficulty calculation for this coin...MAY BE DIFFERENT PER COIN
				$time = ( trim($_POST['network_measure']) * pow(2, 32) / $miner_hashrate );
				
			///////////////////////////////////////////////////////////////////////////
			
				include('results/time-calculation.php'); // Generalized module
				include('results/profit-calculation.php'); // Generalized module
				include('results/earned-daily.php'); // Generalized module
				
			}
			// End form submission results
				
				mining_calc_form($calculation_form_data, 'difficulty'); // Generalized module, with network measure name parameter (difficulty or nethashrate)
				
			///////////////////////////////////////////////////////////////////////////
			
			?>