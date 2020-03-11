<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
			
			// Coin information, to dynamically populate general sections
			$calculation_form_data = array(
											'Ethereum', // Coin name
											'eth', // Coin symbol
											hexdec( etherscan_api('number') ), // Block height
											hexdec( etherscan_api('difficulty') ), // Mining network measure (difficulty or network hashrate)
											'https://etherscan.io/apis/', // Blockchain data API url
											'Etherscan.io API', // Blockchain data API name
											'binance', // Exchange name (lowercase for API logic)
											'ETHBTC' // Market pair name
											);
			
			
			///////////////////////////////////////////////////////////////////////////
			
			echo ( etherscan_api('number') == false ? '<p><a class="red" href="https://etherscan.io/apis/" target="_blank"><b>EtherScan.io (free) API key is required.</b></a></p>' : '' );
			echo '<p><b>Block height:</b> ' . number_format( $calculation_form_data[2] ) . '</p>';
			echo '<p><b>Gas limit:</b> ' . number_format( hexdec( etherscan_api('gasLimit') ) ) . '</p>'; // Custom for this Ethereum mining calculator
				
			// Start form submission results
			if ( $_POST[$calculation_form_data[1].'_submitted'] ) {
				    
				include('results/post-data-processing.php'); // Generalized module
				
				
			///////////////////////////////////////////////////////////////////////////
			
				// Difficulty calculation for this coin...MAY BE DIFFERENT PER COIN
				$mining_time = ( trim($_POST['network_measure']) / $miner_hashrate );
				
			///////////////////////////////////////////////////////////////////////////
			
				include('results/time-calculation.php'); // Generalized module
				include('results/profit-calculation.php'); // Generalized module
				include('results/earned-daily.php'); // Generalized module
				
			}
			// End form submission results
				
				mining_calc_form($calculation_form_data, 'difficulty'); // Generalized module, with network measure name parameter (difficulty or nethashrate)
				
			///////////////////////////////////////////////////////////////////////////
			
			?>