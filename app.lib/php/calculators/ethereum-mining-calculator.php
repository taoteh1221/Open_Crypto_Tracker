<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


				
				echo '<p><b>Block height:</b> ' . number_format(hexdec(etherscan_api('number'))) . '</p>';
				echo '<p><b>Gas limit:</b> ' . number_format(hexdec(etherscan_api('gasLimit'))) . '</p>';
				
				
			if ( $_POST['eth_submitted'] ) {
				    
				$_POST['eth_difficulty'] = str_replace("    ", '', $_POST['eth_difficulty']);
				$_POST['eth_difficulty'] = str_replace(" ", '', $_POST['eth_difficulty']);
				$_POST['eth_difficulty'] = str_replace(",", '', $_POST['eth_difficulty']);
				
				$miner_hashrate = ( trim($_POST['eth_your_hashrate']) * trim($_POST['eth_measure']) );
				
				$time = ( trim($_POST['eth_difficulty']) / $miner_hashrate );
				
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
				
				$eth_daily_average = ( $caculate_daily * ( get_trade_price('poloniex', 'BTC_ETH') * trim($_POST['eth_block_reward']) ) );
				
				$usd_daily_average = number_format( ( round($eth_daily_average, 8) * get_btc_usd($btc_in_usd) ), 2);
				
				$btc_daily_average = number_format(round($eth_daily_average, 8), 8);
				
				$kwh_cost_daily = ( ( trim($_POST['eth_watts_used']) / 1000 ) * 24 ) * trim($_POST['eth_watts_rate']);
				
				
				?>
				<br />
				<br />
				Current Ethereum Value Per Coin: 
				<?php
				echo number_format(get_trade_price('poloniex', 'BTC_ETH'), 8) . ' BTC ($' . round(( round(get_trade_price('poloniex', 'BTC_ETH'), 8) * get_btc_usd($btc_in_usd) ), 8) . ' USD)';
				?>
				<br />
				<br />
				Average ETH Earned Daily (block reward only): 
				<?php
				echo number_format( round(( round($eth_daily_average, 8) / get_trade_price('poloniex', 'BTC_ETH') ), 8) , 8) . ' ETH';
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
				echo '$' . number_format( ( $usd_daily_average - $kwh_cost_daily ), 2);
				?>
				<br />
				<br />
				Weekly Profit: 
				<?php
				echo '$' . number_format( ( ($usd_daily_average - $kwh_cost_daily) * 7 ), 2);
				
				
			}
				?>
				</p>
				<form name='eth' action='index.php#calculators' method='post'>
				    
				    <input type='hidden' value='1' name='eth_submitted' />
				
				<p><b>Difficulty:</b> <input type='text' value='<?=( $_POST['eth_difficulty'] ? number_format($_POST['eth_difficulty']) : number_format(hexdec(etherscan_api('difficulty'))) )?>' name='eth_difficulty' /> (uses <a href='https://etherscan.io/apis/' target='_blank'>etherscan.io/apis</a>)</p>
				
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=$_POST['eth_your_hashrate']?>' name='eth_your_hashrate' />
				
				<select name='eth_measure'>
				<option value='1000000000000' <?=( $_POST['eth_measure'] == '1000000000000' ? 'selected' : '' )?>> Ths </option>
				<option value='1000000000' <?=( $_POST['eth_measure'] == '1000000000' ? 'selected' : '' )?>> Ghs </option>
				<option value='1000000' <?=( $_POST['eth_measure'] == '1000000' ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['eth_measure'] == '1000' ? 'selected' : '' )?>> Khs </option>
				</select>
				</p>
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['eth_block_reward'] ? $_POST['eth_block_reward'] : $mining_rewards['ethereum'] )?>' name='eth_block_reward' /> (static from config.php file, verify current block reward manually)</p>
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( $_POST['eth_watts_used'] ? $_POST['eth_watts_used'] : '300' )?>' name='eth_watts_used' /></p>
				
				<p><b>kWh Rate ($/kWh):</b> <input type='text' value='<?=( $_POST['eth_watts_rate'] ? $_POST['eth_watts_rate'] : '0.10' )?>' name='eth_watts_rate' /></p>
				
				<input type='submit' value='Calculate ETH Mining Profit' />
				
	
				</form>
				