
    <!- footer START -->

            	
            	<div id="api_error_alert"><?=( $_SESSION['get_data_error'] ? $_SESSION['get_data_error'] . $_SESSION['cmc_error'] : $_SESSION['cmc_error'] )?></div>
            	
    <p align='center'><a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases' target='_blank'>Version <?=$app_version?></a><br />(Github download / releases link)</p>
    

    <p align='center'>Donations support further development...<br /><a id='donate' href='#' onclick='return false;'>(click to show addresses below)</a></p>
    
            	<div style='display: none;' id='donate_div' align='center'>
            	
            	BTC: <br />1FfWHekHPLH7hQcU4d5MBVQ4WekJiA8Mk2
            	<br /><br />XMR: <br /><span class='long_linebreak'>47mWWjuwPFiPD6t2MaWcMEfejtQpMuz9oj5hJq18f7nvagcmoJwxudKHUppaWnTMPaMWshMWUTPAUX623KyEtukbSMdmpqu</span>
            	<br /><br />ETH: <br />0xf3da0858c3cfcc28a75c1232957a7fb190d7e5e9
            	<br /><br />STEEM: <br />taoteh1221
            
            	</div>
     
    <?php
    
    // Calculate page load time
    $time = microtime();
    $time = explode(' ', $time);
    $time = $time[1] + $time[0];
    $finish = $time;
    $total_time = round(($finish - $start), 3);
    echo '<p align="center" style="color: '.( $total_time <= 10 ? 'green' : 'red' ).';"> Page generated in '.$total_time.' seconds. </p>';
    
    ?>
        
            </div>
        </div>
    </div>
     <br /> <br />
</body>
</html>
<!-- /*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->
<?php
//var_dump($_SESSION['debugging_printout']);

session_destroy();
?>