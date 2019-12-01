<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

?>			
			<?php
				
				$minutes = ( $mining_time / 60 );
				
				$hours = ( $minutes / 60 );
				
				$days = ( $hours / 24 );
				
				$months = ( $days / 30 );
				
				$years = ( $days / 365 );
				
				?>
				
				
		<!-- Green colored START -->
		<p class='green'>
			Average 
				<?php
				    if ( $minutes < 60 ) {
				    ?>
				    minutes until block found: 
				    <?php
				    echo round($minutes, 2);
				    }
				
				    elseif ( $hours < 24 ) {
				    ?>
				    hours until block found: 
				    <?php
				    echo round($hours, 2);
				    }
				
				    elseif ( $days < 30 ) {
				    ?>
				    days until block found: 
				    <?php
				    echo round($days, 2);
				    }
				
				    elseif ( $days < 365 ) {
				    ?>
				    months until block found: 
				    <?php
				    echo round($months, 2);
				    }
				    
				    else {
				    ?>
				    years until block found: 
				    <?php
				    echo round($years, 2);
				    }
				
				$calculate_daily = ( 24 / $hours );
				
				$daily_average = $calculate_daily * trim($_POST['block_reward']);
				
				?>
				
				