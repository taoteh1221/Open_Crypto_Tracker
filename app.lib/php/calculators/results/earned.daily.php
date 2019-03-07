<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>				
				<br />
				<br />
				Current <?=$calculation_form_data[0]?> Value Per Coin: 
				<?php
				echo ( $calculation_form_data[1] == 'btc' ? number_format(get_btc_usd($btc_exchange)['last_trade'], 2) . ' USD' : number_format(get_coin_value($calculation_form_data[6], $calculation_form_data[7])['last_trade'], 8) . ' BTC ($' . round( get_coin_value($calculation_form_data[6], $calculation_form_data[7])['last_trade'] * get_btc_usd($btc_exchange)['last_trade'] , 8) . ' USD)' );
				?>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $calculation_form_data[1] != 'btc' ) {
				?>
				Average <?=strtoupper($calculation_form_data[1])?> Earned Daily (block reward only): 
				
				
				<?php

				echo number_format( $daily_average , 8) . ' ' . strtoupper($calculation_form_data[1]);
				
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				Average BTC Value Earned Daily: 
				<?php
				echo number_format( $btc_daily_average, 8 ) . ' BTC ($' . number_format( $usd_daily_average , 2) . ' USD)';
				?>
				
				<br />
				<br />
				
				<b style='color: red;'>Power Cost Daily: 
				<?php
				echo '$' . number_format($kwh_cost_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<b style='color: red;'>Pool Fee Daily: 
				<?php
				echo '$' . number_format($pool_fee_daily, 2);
				?></b>
				
				<br />
				<br />
				
				<b>Daily Profit: 
				<?php
				echo '$' . number_format( $usd_daily_average - $kwh_cost_daily - $pool_fee_daily , 2);
				?></b>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
				<?php
				if ( $calculation_form_data[1] != 'btc' ) {
				?>
				Average <?=strtoupper($calculation_form_data[1])?> Earned Weekly (block reward only): 
				
				
				<?php

				echo number_format( $daily_average * 7 , 8) . ' ' . strtoupper($calculation_form_data[1]);
				
				?>
				
				<br />
				<br />
				<?php
				}
				?>
				
				Average BTC Value Earned Weekly: 
				<?php
				echo number_format( $btc_daily_average * 7 , 8) . ' BTC ($' . number_format( $usd_daily_average * 7 , 2) . ' USD)';
				?>
				
				<br />
				<br />
				
				<b style='color: red;'>Power Cost Weekly: 
				<?php
				echo '$' . number_format($kwh_cost_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b style='color: red;'>Pool Fee Weekly: 
				<?php
				echo '$' . number_format($pool_fee_daily * 7, 2);
				?></b>
				
				<br />
				<br />
				
				<b>Weekly Profit: 
				<?php
				echo '$' . number_format( ( $usd_daily_average - $kwh_cost_daily - $pool_fee_daily ) * 7 , 2);
				?></b>
				
				<br />
				<br />
				 ###################################################
				<br />
				<br />
				
		</p>
		<!-- Green colored END -->
		
		