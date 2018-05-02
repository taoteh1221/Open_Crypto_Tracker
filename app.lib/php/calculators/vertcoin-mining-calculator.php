<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


				
				echo '<p><b>Block height:</b> ' . number_format(vertcoin_api('height')) . '</p>';
				
				
			if ( $_POST['vtc_submitted'] ) {
				    
				$_POST['vtc_difficulty'] = str_replace("    ", '', $_POST['vtc_difficulty']);
				$_POST['vtc_difficulty'] = str_replace(" ", '', $_POST['vtc_difficulty']);
				$_POST['vtc_difficulty'] = str_replace(",", '', $_POST['vtc_difficulty']);

				$miner_hashrate = ( trim($_POST['vtc_your_hashrate']) * trim($_POST['vtc_measure']) );
				
				$time = ( trim($_POST['vtc_difficulty']) * pow(2, 32) / $miner_hashrate );
				
				$minutes = ( $time / 60 );
				
				$hours = ( $minutes / 60 );
				
				$days = ( $hours / 24 );
				
				$months = ( $days / 30 );
				
				$years = ( $days / 365 );
				    
				    //echo '<p>'.$scale;
				    //echo '<p>'.$time;
				?>
				<p style='color: green;'>
				<?php
				    if ( $minutes < 60 ) {
				    ?>
				    Minutes until block found: 
				    <?php
				    echo round($minutes, 2);
				    }
				
				    elseif ( $hours < 24 ) {
				    ?>
				    Hours until block found: 
				    <?php
				    echo round($hours, 2);
				    }
				
				    elseif ( $days < 30 ) {
				    ?>
				    Days until block found: 
				    <?php
				    echo round($days, 2);
				    }
				
				    elseif ( $days < 365 ) {
				    ?>
				    Months until block found: 
				    <?php
				    echo round($months, 2);
				    }
				    
				    else {
				    ?>
				    Years until block found: 
				    <?php
				    echo round($years, 2);
				    }
				
				$caculate_daily = ( 24 / $hours );
				
				$vtc_daily_average = ( $caculate_daily * ( get_trade_price('poloniex', 'BTC_VTC') * trim($_POST['vtc_block_reward']) ) );
				
				$usd_daily_average = number_format( ( round($vtc_daily_average, 8) * get_btc_usd($btc_in_usd) ), 2);
				
				$btc_daily_average = number_format(round($vtc_daily_average, 8), 8);
				
				$kwh_cost_daily = ( ( trim($_POST['vtc_watts_used']) / 1000 ) * 24 ) * trim($_POST['vtc_watts_rate']);
				
				
				?>
				<br />
				<br />
				Current Vertcoin Value Per Coin: 
				<?php
				echo number_format(get_trade_price('poloniex', 'BTC_VTC'), 8) . ' BTC ($' . round(( round(get_trade_price('poloniex', 'BTC_VTC'), 8) * get_btc_usd($btc_in_usd) ), 8) . ' USD)';
				?>
				<br />
				<br />
				Average VTC Earned Daily (block reward only): 
				<?php
				echo number_format( round(( round($vtc_daily_average, 8) / get_trade_price('poloniex', 'BTC_VTC') ), 8) , 8) . ' VTC';
				?>
				<br />
				<br />
				Average BTC Value Earned Daily: 
				<?php
				echo $btc_daily_average . ' BTC ($' . $usd_daily_average . ' USD)';
				?>
				<br />
				<br />
				Power Cost Daily: 
				<?php
				echo '$' . number_format(round($kwh_cost_daily, 2), 2);
				?>
				<br />
				<br />
				Daily Profit: 
				<?php
				echo '$' . number_format( round( ( $usd_daily_average - $kwh_cost_daily ), 2), 2);
				
				
			}
				?>
				</p>
				<form name='vtc' action='index.php#calculators' method='post'>
				    
				    <input type='hidden' value='1' name='vtc_submitted' />
				
				<p><b>Difficulty:</b> <input type='text' value='<?=( $_POST['vtc_difficulty'] ? number_format($_POST['vtc_difficulty']) : number_format(vertcoin_api('difficulty')) )?>' name='vtc_difficulty' /> (uses <a href='http://explorer.vertcoin.info/info' target='_blank'>vertcoin.info API</a>)</p>
				
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=$_POST['vtc_your_hashrate']?>' name='vtc_your_hashrate' />
				
				<select name='vtc_measure'>
				<option value='1000000000000' <?=( $_POST['vtc_measure'] == '1000000000000' ? 'selected' : '' )?>> Ths </option>
				<option value='1000000000' <?=( $_POST['vtc_measure'] == '1000000000' ? 'selected' : '' )?>> Ghs </option>
				<option value='1000000' <?=( $_POST['vtc_measure'] == '1000000' ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['vtc_measure'] == '1000' ? 'selected' : '' )?>> Khs </option>
				</select>
				</p>
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['vtc_block_reward'] ? $_POST['vtc_block_reward'] : $mining_rewards['vertcoin'] )?>' name='vtc_block_reward' /> (static from config.php file, verify current block reward manually)</p>
				
				<p><b>Watts used:</b> <input type='text' value='<?=( $_POST['vtc_watts_used'] ? $_POST['vtc_watts_used'] : '300' )?>' name='vtc_watts_used' /></p>
				
				<p><b>kWh Rate ($/kWh):</b> <input type='text' value='<?=( $_POST['vtc_watts_rate'] ? $_POST['vtc_watts_rate'] : '0.10' )?>' name='vtc_watts_rate' /></p>
				
				<input type='submit' value='Calculate VTC Mining Profit' />
				
	
				</form>
				