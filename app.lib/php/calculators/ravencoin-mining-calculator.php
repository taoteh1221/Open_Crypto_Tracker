<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


				
				echo '<p><b>Block height:</b> ' . number_format(ravencoin_api('height')) . '</p>';
				
				
			if ( $_POST['rvn_submitted'] ) {
				    
				$_POST['rvn_difficulty'] = str_replace("    ", '', $_POST['rvn_difficulty']);
				$_POST['rvn_difficulty'] = str_replace(" ", '', $_POST['rvn_difficulty']);
				$_POST['rvn_difficulty'] = str_replace(",", '', $_POST['rvn_difficulty']);

				$miner_hashrate = ( trim($_POST['rvn_your_hashrate']) * trim($_POST['rvn_measure']) );
				
				$time = ( trim($_POST['rvn_difficulty']) * pow(2, 32) / $miner_hashrate );
				
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
				
				$rvn_daily_average = ( $caculate_daily * ( get_trade_price('cryptofresh', 'BRIDGE.RVN') * trim($_POST['rvn_block_reward']) ) );
				
				$usd_daily_average = number_format( ( round($rvn_daily_average, 8) * get_btc_usd($btc_in_usd) ), 2);
				
				$btc_daily_average = number_format(round($rvn_daily_average, 8), 8);
				
				$kwh_cost_daily = ( ( trim($_POST['rvn_watts_used']) / 1000 ) * 24 ) * trim($_POST['rvn_watts_rate']);
				
				
				?>
				<br />
				<br />
				Current Vertcoin Value Per Coin: 
				<?php
				echo number_format(get_trade_price('cryptofresh', 'BRIDGE.RVN'), 8) . ' BTC ($' . round(( round(get_trade_price('cryptofresh', 'BRIDGE.RVN'), 8) * get_btc_usd($btc_in_usd) ), 8) . ' USD)';
				?>
				<br />
				<br />
				Average RVN Earned Daily (block reward only): 
				<?php
				echo number_format( round(( round($rvn_daily_average, 8) / get_trade_price('cryptofresh', 'BRIDGE.RVN') ), 8) , 8) . ' RVN';
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
				<form name='rvn' action='index.php#calculators' method='post'>
				    
				    <input type='hidden' value='1' name='rvn_submitted' />
				
				<p><b>Difficulty:</b> <input type='text' value='<?=( $_POST['rvn_difficulty'] ? number_format($_POST['rvn_difficulty']) : number_format(ravencoin_api('difficulty')) )?>' name='rvn_difficulty' /> (uses <a href='https://rvn.hash4.life/api' target='_blank'>rvn.hash4.life API</a>)</p>
				
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=$_POST['rvn_your_hashrate']?>' name='rvn_your_hashrate' />
				
				<select name='rvn_measure'>
				<option value='1000000000000' <?=( $_POST['rvn_measure'] == '1000000000000' ? 'selected' : '' )?>> Ths </option>
				<option value='1000000000' <?=( $_POST['rvn_measure'] == '1000000000' ? 'selected' : '' )?>> Ghs </option>
				<option value='1000000' <?=( $_POST['rvn_measure'] == '1000000' ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['rvn_measure'] == '1000' ? 'selected' : '' )?>> Khs </option>
				</select>
				</p>
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['rvn_block_reward'] ? $_POST['rvn_block_reward'] : $mining_rewards['ravencoin'] )?>' name='rvn_block_reward' /> (static from config.php file, verify current block reward manually)</p>
				
				<p><b>Watts Used:</b> <input type='text' value='<?=( $_POST['rvn_watts_used'] ? $_POST['rvn_watts_used'] : '300' )?>' name='rvn_watts_used' /></p>
				
				<p><b>kWh Rate ($/kWh):</b> <input type='text' value='<?=( $_POST['rvn_watts_rate'] ? $_POST['rvn_watts_rate'] : '0.10' )?>' name='rvn_watts_rate' /></p>
				
				<input type='submit' value='Calculate RVN Mining Profit' />
				
	
				</form>
				