
				<?php
				
				echo '<p><b>Block height:</b> ' . number_format(decred_api('height')) . '</p>';
				
				
				if ( $_POST['dcr_submitted'] ) {
				    
				$_POST['dcr_difficulty'] = str_replace("    ", '', $_POST['dcr_difficulty']);
				$_POST['dcr_difficulty'] = str_replace(" ", '', $_POST['dcr_difficulty']);
				$_POST['dcr_difficulty'] = str_replace(",", '', $_POST['dcr_difficulty']);

				$miner_dcr_hashrate = ( trim($_POST['dcr_your_hashrate']) * trim($_POST['dcr_measure']) );
				
				$time = ( trim($_POST['dcr_difficulty']) * pow(2, 32) / $miner_dcr_hashrate );
				
				$minutes = ( $time / 60 );
				
				$hours = ( $time / 3600 );
				
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
				$daily_average = ( $caculate_daily * ( get_trade_price('poloniex', 'BTC_DCR') * trim($_POST['dcr_block_reward']) ) );
				?>
				<br />
				<br />
				Current Decred Value Per Coin: 
				<?php
				echo round(get_trade_price('poloniex', 'BTC_DCR'), 8) . ' BTC ($' . round(( round(get_trade_price('poloniex', 'BTC_DCR'), 8) * get_btc_usd($btc_in_usd) ), 8) . ' USD)';
				?>
				<br />
				<br />
				Average DCR Earned Daily (block reward only): 
				<?php
				echo number_format( round(( round($daily_average, 8) / get_trade_price('poloniex', 'BTC_DCR') ), 8) , 8) . ' DCR';
				?>
				<br />
				<br />
				Average BTC Value Earned Daily: 
				<?php
				echo number_format(round($daily_average, 8), 8) . ' BTC ($' . round(( round($daily_average, 8) * get_btc_usd($btc_in_usd) ), 2) . ' USD)';
				}
				?>
				</p>
				<form name='dcr' action='index.php#calculators' method='post'>
				    
				    <input type='hidden' value='1' name='dcr_submitted' />
				
				<p><b>Difficulty:</b> <input type='text' value='<?=( $_POST['dcr_difficulty'] ? number_format($_POST['dcr_difficulty']) : number_format(decred_api('difficulty')) )?>' name='dcr_difficulty' /> (uses <a href='https://github.com/decred/dcrdata#json-rest-api' target='_blank'>dcrdata.org API</a>)</p>
				
				
				<p><b>Your Hashrate:</b> <input type='text' value='<?=$_POST['dcr_your_hashrate']?>' name='dcr_your_hashrate' />
				
				<select name='dcr_measure'>
				<option value='1000000' <?=( $_POST['dcr_measure'] == '1000000' ? 'selected' : '' )?>> Mhs </option>
				<option value='1000' <?=( $_POST['dcr_measure'] == '1000' ? 'selected' : '' )?>> Khs </option>
				</select>
				</p>
				
				<p><b>Block Reward:</b> <input type='text' value='<?=( $_POST['dcr_block_reward'] ? $_POST['dcr_block_reward'] : $mining_rewards['decred'] )?>' name='dcr_block_reward' /> (static from config.php file, verify current block reward manually)</p>
				
				<input type='submit' value='Calculate DCR Mining Profit' />
				
	
				</form>
				