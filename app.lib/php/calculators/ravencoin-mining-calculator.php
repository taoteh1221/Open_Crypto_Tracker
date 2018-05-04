<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
			
			// Coin information, to dynamically populate general sections
			$calculation_form_data = array(
											'Ravencoin', // Coin name
											'rvn', // Coin symbol
											ravencoin_api('height'), // Block height
											ravencoin_api('difficulty'), // Mining difficulty
											'https://rvn.hash4.life/api', // Blockchain data API url
											'rvn.hash4.life API', // Blockchain data API name
											'cryptofresh', // Exchange name (lowercase for API logic)
											'BRIDGE.RVN' // Market pair name
											);
			
			
			///////////////////////////////////////////////////////////////////////////
			
			echo '<p><b>Block height:</b> ' . number_format($calculation_form_data[2]) . '</p>';
				
			// Start form submission results
			if ( $_POST[$calculation_form_data[1].'_submitted'] ) {
				    
				include('results/post.data.processing.php'); // Generalized module
				
				
			///////////////////////////////////////////////////////////////////////////
			
				// Difficulty calculation for this coin...MAY BE DIFFERENT PER COIN
				$time = ( trim($_POST['difficulty']) * pow(2, 32) / $miner_hashrate );
				
			///////////////////////////////////////////////////////////////////////////
			
				include('results/time.calculation.php'); // Generalized module
				include('results/profit.calculation.php'); // Generalized module
				include('results/earned.daily.php'); // Generalized module
				
			}
			// End form submission results
				
				mining_calc_form($calculation_form_data); // Generalized module
				
			///////////////////////////////////////////////////////////////////////////
			
			?>