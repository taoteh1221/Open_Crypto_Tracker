
    <!- footer START -->

            	
            	<div id="api_error_alert"><?=( $_SESSION['get_data_error'] ? $_SESSION['get_data_error'] : '' )?></div>
            	
    <p align='center'><a href='https://github.com/taoteh1221/DFD_Cryptocoin_Values/releases' target='_blank'>Version <?=$version?></a><br />(Github releases link)</p>
    

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
    $total_time = round(($finish - $start), 4);
    echo '<p align="center" style="color: red;"> Page generated in '.$total_time.' seconds. </p>';
    
    ?>
        
            </div>
        </div>
    </div>
     <br /> <br />
</body>
</html>
<!-- /*
 * DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */ -->
<?php
session_destroy();
?>