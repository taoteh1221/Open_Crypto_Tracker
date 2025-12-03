<?php
/*
 * Copyright 2014-2026 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// Bitcoin Node stats START (requires price charts)
if ( $ct['conf']['charts_alerts']['enable_price_charts'] == 'on' ) {
     
// Used digest hash for security / short filename
$disabled_rpc_getnodeaddresses = $ct['base_dir'] . '/cache/other/disabled_rpc_endpoints/' . $ct['sec']->digest($ct['conf']['ext_apis']['bitcoin_rpc_server'], 10) . '-bitcoin-method-getnodeaddresses.dat';

// Bitcoin mining data (5 minute cache)
$bitcoin_mining = $ct['api']->blockchain_rpc('bitcoin', 'getmininginfo', false, 5)['result'];

//var_dump($bitcoin_mining);

// Bitcoin get latest block hash (5 minute cache)
$bitcoin_last_block_hash = $ct['api']->blockchain_rpc('bitcoin', 'getbestblockhash', false, 5)['result'];

//var_dump($bitcoin_last_block_hash);

// Bitcoin get latest block stats (5 minute cache)
$bitcoin_last_block_stats = $ct['api']->blockchain_rpc('bitcoin', 'getblockstats', array($bitcoin_last_block_hash), 5)['result'];

//var_dump($bitcoin_last_block_stats);
    
    
     if ( isset($bitcoin_last_block_stats['txs']) && is_numeric($bitcoin_last_block_stats['txs']) ) {
     $bitcoin_tps = round( ($bitcoin_last_block_stats['txs'] / 600) , 2 );
     }


     // Get nodes info
     if ( file_exists( $ct['plug']->chart_cache('/bitcoin/overwrites/bitcoin_nodes_info.dat', $this_plug) ) ) {
     $bitcoin_nodes_info_file = $ct['plug']->chart_cache('/bitcoin/overwrites/bitcoin_nodes_info.dat', $this_plug);
     $bitcoin_nodes_info = json_decode( trim( file_get_contents( $bitcoin_nodes_info_file ) ) , true);
     }
     
//$possible_nodes = $ct['api']->blockchain_rpc('bitcoin', 'getnodeaddresses', array(0), 480);
     
//$ct['gen']->array_debugging($possible_nodes, true);

?>

  
<fieldset class='subsection_fieldset'>
	<legend class='subsection_legend'> <b class='btc'>Bitcoin Network Statistics</b> </legend>
		    
	
    <?php    
    if ( isset($bitcoin_mining['difficulty']) && is_numeric($bitcoin_mining['difficulty']) ) {
    ?>
    
    <p>
    <b class='yellow'>Difficulty:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['difficulty'], 0)?>
    </p>
    
    <?php
    }
    
    
    if ( isset($bitcoin_mining['blocks']) && is_numeric($bitcoin_mining['blocks']) ) {
    ?>
    
    <p>
    <b class='yellow'>Block Height:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['blocks'], 0)?>
    </p>
    
    <?php
    }
    
    
    if ( is_array($bitcoin_nodes_info) ) {
    ?>
    
    <p>
    <b class='yellow'>Node Count:</b>  <?=$ct['var']->num_pretty( sizeof($bitcoin_nodes_info) , 0)?>
    </p>

    <?php
    }
    
    
    if ( isset($bitcoin_tps) ) {
    ?>
    
    <p>
    <b class='yellow'>TPS:</b> <?=$bitcoin_tps?>
    </p>

    <?php
    }
    ?>

    
    
    <b><a href="javascript: return false;" class="modal_style_control show_more_bitcoin_stats blue" title="View More Bitcoin Network Statistics">View More Bitcoin Network Statistics</a></b>
		
</fieldset>

		

	<!-- START MORE PORTFOLIO STATS MODAL -->
	<div class='' id="show_more_bitcoin_stats">
	
		<h3 class='blue' style='display: inline;'>More Bitcoin Network Statistics</h3>
	
				<span style='z-index: 99999; margin-right: 55px;' class='red countdown_notice_modal'></span>
	
	<br clear='all' />
	
	<br clear='all' />
	
	<br clear='all' />
	

<p style='font-weight: bold;' class='bitcoin'>Difficulty, mempool, and hashrate charts coming soon&trade;</p>

	
    <?php    
    if ( isset($bitcoin_mining['blocks']) && is_numeric($bitcoin_mining['blocks']) ) {
    ?>
    
	
	<h4 class='btc'>Bitcoin Mining Information (cached for 5 minutes):</h4>
	
   <div class='secondary_chart_wrapper sol_epoch_data'>
    

    <p>
    <b class='yellow'>Hashes Per Second:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['networkhashps'], 0)?>
    </p>

    <p>
    <b class='yellow'>Difficulty:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['difficulty'], 0)?>
    </p>
    
    <p>
    <b class='yellow'>Block Height:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['blocks'], 0)?>
    </p>

    <p>
    <b class='yellow'>MemPool Size:</b>  <?=$ct['var']->num_pretty($bitcoin_mining['pooledtx'], 0)?> (queued transactions)
    </p>

    <p>
    <b class='yellow'>Transactions in Last Block:</b>  <?=$ct['var']->num_pretty($bitcoin_last_block_stats['txs'], 0)?> (<?=$bitcoin_tps?> TPS)
    </p>
    
    
   </div>
   
    <?php
    }
    else {
    ?>
    
             	<p style='font-weight: bold; margin: 1em !important;' class='red red_dotted'>
             	
             	Your Bitcoin RPC service MAY have disabled the "getmininginfo" endpoint, which we NEED for mining stats. Consult your Bitcoin RPC Provider on using this endpoint, or try switching to another provider in "Admin Area => APIs => External APIs => Bitcoin RPC Server" (<a href='https://chainstack.com/pricing/' target='_BLANK'>ChainStack</a> has a FREE 'Developer' plan, which supports "getmininginfo"), to enable the additional Bitcoin stats mentioned.<br /><br />
             	
               ALTERNATIVELY, you can <a href='https://magitek.dev/articles/2023-02-22-how-to-setup-an-rpc-api-for-a-blockchain-node/' target='_BLANK'>run your own Bitcoin RPC node</a>, and enter it's RPC address in "Admin Area => APIs => External APIs => Bitcoin RPC Server".<br /><br />
             	
               PRO TIP: You can check the app logs in the "Admin Area => System Monitoring" section, to see any error messages related to what Bitcoin RPC endpoints have been detected as unavailable. MANY "free" crypto RPC providers disable resource-intensive OR important endpoints, to monetize access to them.
    
             	</p>
    
    <?php
    }
    ?>


    
	
	<h4 class='btc' style='margin-top: 2em; margin-bottom: 1em;'>Bitcoin TPS:</h4>
	
    <p>
    
    <?php
    
    $tps_chart_defaults = explode("||", $plug['conf'][$this_plug]['tps_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $tps_chart_defaults[0] >= 400 && $tps_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$tps_chart_defaults[0] = 600;
    	}
    	
    	if ( $tps_chart_defaults[1] >= 7 && $tps_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$tps_chart_defaults[1] = 15;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Time Period: <select class='browser-default custom-select' id='bitcoin_tps_period' name='bitcoin_tps_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	?>
    <option value='<?=$light_chart_days?>' <?=( $light_chart_days == 'all' ? 'selected' : '' )?>> <?=$ct['gen']->light_chart_time_period($light_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
    Custom Start Date: <input type="text" id='bitcoin_tps_date' name='bitcoin_tps_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='bitcoin_tps_chart_height' name='bitcoin_tps_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $tps_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='bitcoin_tps_menu_size' name='bitcoin_tps_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $tps_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Bitcoin TPS Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var bitcoin_tps_chart_width = document.getElementById('bitcoin_tps_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('bitcoin_tps_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#bitcoin_tps_chart div.chart_reload div.chart_reload_msg').html('Loading Bitcoin TPS Chart...');
  
	$('#bitcoin_tps_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('bitcoin_tps_chart', 'complete', function() {
  	
	$('#bitcoin_tps_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#bitcoin_tps_chart').css('height', document.getElementById('bitcoin_tps_chart_height').value + 'px');
	$('#bitcoin_tps_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('bitcoin_tps_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('bitcoin_tps_period').value );
		}
	
	});
	
	var to_timestamp_var = ( document.getElementById('bitcoin_tps_date').value ? document.getElementById('bitcoin_tps_date').value : '1970/1/1' );
	
	date_array = to_timestamp_var.split('/');
	
	date_timestamp = to_timestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('bitcoin_tps_chart', 'resize', {
  width: '100%',
  height: document.getElementById('bitcoin_tps_chart_height').value
  });
  
  // 'load'
  zingchart.exec('bitcoin_tps_chart', 'load', {
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=btc_tps&time_period=' + document.getElementById('bitcoin_tps_period').value + '&start_time=' + date_timestamp + '&chart_width=' + bitcoin_tps_chart_width + '&chart_height=' + document.getElementById('bitcoin_tps_chart_height').value + '&menu_size=' + document.getElementById('bitcoin_tps_menu_size').value,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control tps_charts" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
    </p>
    
  
  <script>
	$('.datepicker').datepicker({
    dateFormat: 'yy/mm/dd',
    defaultDate: -7
	});
  </script>
  
  <style>
	.ui-datepicker .ui-datepicker-header {
		background: #808080;
	}
 </style>
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; display: flex; flex-flow: column wrap; overflow: hidden;' class='secondary_chart_wrapper' id='bitcoin_tps_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Bitcoin TPS Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#bitcoin_tps_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Bitcoin TPS Chart...');
	
  
zingchart.bind('bitcoin_tps_chart', 'load', function() {
$("#bitcoin_tps_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
fix_zingchart_watermarks(); // Make sure watermarks are showing properly
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=btc_tps&time_period=" + document.getElementById('bitcoin_tps_period').value + "&start_time=0&chart_height=<?=$tps_chart_defaults[0]?>&menu_size=<?=$tps_chart_defaults[1]?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('bitcoin_tps_chart', 'complete', function() {
	$("#bitcoin_tps_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
     fix_zingchart_watermarks(); // Make sure watermarks are showing properly
	$('#bitcoin_tps_chart').css('height', '<?=$tps_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'bitcoin_tps_chart',
  	height: '<?=$tps_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('bitcoin_tps_chart', 'label_click', function(e){
		
  	zingchart.exec('bitcoin_tps_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>


    <?php
    // Don't show node count chart / node geoloocation map,
    // IF we detect the "getnodeaddresses" endpoint is disabled by the RPC provider
    if ( file_exists($disabled_rpc_getnodeaddresses) ) {
    ?>
             	<br /><br />
             	
             	<p style='font-weight: bold; margin: 1em !important;' class='red red_dotted'>
             	
             	Your Bitcoin RPC service seems to have disabled the "getnodeaddresses" endpoint, which we NEED for the Node Count chart, and the Node Geolocation map. Consult your Bitcoin RPC Provider on how to enable this endpoint, or try switching to another provider in "Admin Area => APIs => External APIs => Bitcoin RPC Server" (<a href='https://chainstack.com/pricing/' target='_BLANK'>ChainStack</a> has a FREE 'Developer' plan, which supports "getnodeaddresses"), to enable the additional Bitcoin stats mentioned.<br /><br />
             	
               ALTERNATIVELY, you can <a href='https://magitek.dev/articles/2023-02-22-how-to-setup-an-rpc-api-for-a-blockchain-node/' target='_BLANK'>run your own Bitcoin RPC node</a>, and enter it's RPC address in "Admin Area => APIs => External APIs => Bitcoin RPC Server".<br /><br />
             	
               PRO TIP: You can check the app logs in the "Admin Area => System Monitoring" section, to see any error messages related to what Bitcoin RPC endpoints have been detected as unavailable. MANY "free" crypto RPC providers disable resource-intensive OR important endpoints, to monetize access to them.
             	
             	</p>
             	
    <?php
    }
    // Otherwise, show node count chart / node geoloocation map
    else {
    ?>

	
	<h4 class='btc' style='margin-top: 2em; margin-bottom: 1em;'>Bitcoin Node Count:</h4>
    
    <p class='bitcoin'>The number of Bitcoin nodes may vary, due to how long the Bitcoin RPC server (you are getting onchain data from) has been online connecting to other peers, or other factors.</p>
	
    <p>
    
    <?php
    
    $node_count_chart_defaults = explode("||", $plug['conf'][$this_plug]['node_count_chart_defaults']);
    
    	// Fallbacks
    	
    	if ( $node_count_chart_defaults[0] >= 400 && $node_count_chart_defaults[0] <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_count_chart_defaults[0] = 600;
    	}
    	
    	if ( $node_count_chart_defaults[1] >= 7 && $node_count_chart_defaults[1] <= 16 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_count_chart_defaults[1] = 15;
    	}

    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Time Period: <select class='browser-default custom-select' id='bitcoin_node_count_period' name='bitcoin_node_count_period' onchange="
    
		if ( this.value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -this.value );
		}
    
    ">
	<?php
	foreach ($ct['light_chart_day_intervals'] as $light_chart_days) {
	?>
    <option value='<?=$light_chart_days?>' <?=( $light_chart_days == 'all' ? 'selected' : '' )?>> <?=$ct['gen']->light_chart_time_period($light_chart_days, 'long')?> </option>
	<?php
	}
	?>
    </select>  &nbsp;&nbsp; 
    
    
    Custom Start Date: <input type="text" id='bitcoin_node_count_date' name='bitcoin_node_count_date' class="datepicker" value='' placeholder="yyyy/mm/dd (optional)" style='width: 155px; display: inline;' /> 
		
			 &nbsp;&nbsp; 

    
    Chart Height: <select class='browser-default custom-select' id='bitcoin_node_count_chart_height' name='bitcoin_node_count_chart_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_count_chart_defaults[0] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    Menu Size: <select class='browser-default custom-select' id='bitcoin_node_count_menu_size' name='bitcoin_node_count_menu_size'>
    <?php
    $count = 7;
    while ( $count <= 16 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_count_chart_defaults[1] ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 1;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    
    <input type='button' value='Update Bitcoin Node Count Chart' onclick="
  
  new_date = new Date();
  
  timestamp_offset = 60 * new_date.getTimezoneOffset(); // Local time offset (browser data), in seconds
  
  var bitcoin_node_count_chart_width = document.getElementById('bitcoin_node_count_chart').offsetWidth;
  
    
  // Reset any user-adjusted zoom
  zingchart.exec('bitcoin_node_count_chart', 'viewall', {
    graphid: 0
  });
  
  
  $('#bitcoin_node_count_chart div.chart_reload div.chart_reload_msg').html('Loading Bitcoin Node Count Chart...');
  
	$('#bitcoin_node_count_chart div.chart_reload').fadeIn(100); // 0.1 seconds
	
  zingchart.bind('bitcoin_node_count_chart', 'complete', function() {
  	
	$('#bitcoin_node_count_chart div.chart_reload' ).fadeOut(2500); // 2.5 seconds
	$('#bitcoin_node_count_chart').css('height', document.getElementById('bitcoin_node_count_chart_height').value + 'px');
	$('#bitcoin_node_count_chart').css('background', '#f2f2f2');
	
		if ( document.getElementById('bitcoin_node_count_period').value == 'all' ) {
		$('.datepicker').datepicker('option', 'defaultDate', -7 );
		}
		else {
		$('.datepicker').datepicker('option', 'defaultDate', -document.getElementById('bitcoin_node_count_period').value );
		}
	
	});
	
	var to_timestamp_var = ( document.getElementById('bitcoin_node_count_date').value ? document.getElementById('bitcoin_node_count_date').value : '1970/1/1' );
	
	date_array = to_timestamp_var.split('/');
	
	date_timestamp = to_timestamp(date_array[0],date_array[1],date_array[2],0,0,0) + timestamp_offset;
  
  // 'resize' MUST run before 'load'
  zingchart.exec('bitcoin_node_count_chart', 'resize', {
  width: '100%',
  height: document.getElementById('bitcoin_node_count_chart_height').value
  });
  
  // 'load'
  zingchart.exec('bitcoin_node_count_chart', 'load', {
  	dataurl: '<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=btc_nodes&time_period=' + document.getElementById('bitcoin_node_count_period').value + '&start_time=' + date_timestamp + '&chart_width=' + bitcoin_node_count_chart_width + '&chart_height=' + document.getElementById('bitcoin_node_count_chart_height').value + '&menu_size=' + document.getElementById('bitcoin_node_count_menu_size').value,
    cache: {
        data: true
    }
  });
    
    " /> 
    
    &nbsp; <img class="tooltip_style_control node_count_chart_defaults" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    
    </div>
    
    
    </p>
    
  
  <script>
	$('.datepicker').datepicker({
    dateFormat: 'yy/mm/dd',
    defaultDate: -7
	});
  </script>
  
  <style>
	.ui-datepicker .ui-datepicker-header {
		background: #808080;
	}
 </style>
 
 
  	<div style='min-width: 775px; width: 100%; min-height: 1px; display: flex; flex-flow: column wrap; overflow: hidden;' class='secondary_chart_wrapper' id='bitcoin_node_count_chart'>
	
	<span class='chart_loading' style='color: <?=$ct['conf']['charts_alerts']['charts_text']?>;'> &nbsp; Loading Bitcoin Node Count Chart...</span>
	
	<div style='z-index: 99999; margin-top: 7px;' class='chart_reload align_center absolute_centered loading bitcoin'><img class='ajax_loader_image' src="templates/interface/media/images/auto-preloaded/loader.gif" height='17' alt="" style='vertical-align: middle;' /> <div class='chart_reload_msg'></div></div>
		
	</div>
	
	
  <script>

$("#bitcoin_node_count_chart span.chart_loading").html(' &nbsp; <img class="ajax_loader_image" src="templates/interface/media/images/auto-preloaded/loader.gif" height="16" alt="" style="vertical-align: middle;" /> Loading Bitcoin Node Count Chart...');
	
  
zingchart.bind('bitcoin_node_count_chart', 'load', function() {
$("#bitcoin_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
fix_zingchart_watermarks(); // Make sure watermarks are showing properly
});
  

zingchart.TOUCHZOOM = 'pinch'; /* mobile compatibility */

$.get( "<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-ajax.php?type=chart&mode=btc_nodes&time_period=" + document.getElementById('bitcoin_node_count_period').value + "&start_time=0&chart_height=<?=$node_count_chart_defaults[0]?>&menu_size=<?=$node_count_chart_defaults[1]?>", function( json_data ) {
 

	// Mark chart as loaded after it has rendered
	zingchart.bind('bitcoin_node_count_chart', 'complete', function() {
	$("#bitcoin_node_count_chart span.chart_loading").hide(); // Hide "Loading chart X..." after it loads
     fix_zingchart_watermarks(); // Make sure watermarks are showing properly
	$('#bitcoin_node_count_chart').css('height', '<?=$node_count_chart_defaults[0]?>px');
	});

	zingchart.render({
  	id: 'bitcoin_node_count_chart',
  	height: '<?=$node_count_chart_defaults[0]?>',
  	width: "100%",
  	data: json_data
	});

 
});


// Reset user-adjusted zoom
zingchart.bind('bitcoin_node_count_chart', 'label_click', function(e){
		
  	zingchart.exec('bitcoin_node_count_chart', 'viewall', {
   graphid: 0
  	});
		
});

    
  </script>



<!-- BITCOIN NODES GEOLOCATION MAP  -->
    
    <h4 class='btc' style='margin-top: 2em; margin-bottom: 1em;'>Bitcoin Node GeoLocation:</h4>
    
    <p class='bitcoin'>Geolocation is approximate. It may vary from actual physical location, due to internal networking behind the gateway, or other factors.</p>
    
    <p class='bitcoin'>The number of Bitcoin nodes may vary, due to how long the Bitcoin RPC server (you are getting onchain data from) has been online connecting to other peers, or other factors.</p>
    
    
    <?php
    if ( !file_exists( $ct['plug']->event_cache('bitcoin_node_geolocation_cleanup.dat', $this_plug) ) ) {
    ?>
             	
             	<p style='font-weight: bold; margin: 1em !important;' class='red red_dotted'>
             	
             	It may take a few hours or longer to show Bitcoin GeoLocation data, after enabling the On-Chain Stats plugin.
             	
             	</p>
             	
    <?php
    }
    else {
    $bitcoin_node_geolocation_cleanup_timestamp = filemtime( $ct['plug']->event_cache('bitcoin_node_geolocation_cleanup.dat', $this_plug) );
    $bitcoin_node_geolocation_pretty_timestamp = date("F jS, g:ia", $bitcoin_node_geolocation_cleanup_timestamp);
    }
    
    
    $node_geolocation_map_height_default = $plug['conf'][$this_plug]['node_geolocation_map_height_default'];
    
    	// Fallbacks
    	
    	if ( $node_geolocation_map_height_default >= 400 && $node_geolocation_map_height_default <= 900 ) {
		// DO NOTHING    	
    	}
    	else {
    	$node_geolocation_map_height_default = 500;
    	}
    
    ?>
    
    
	<div class='align_left clear_both' style='white-space: nowrap;'>
    
    Chart Height: <select class='browser-default custom-select' id='bitcoin_node_geolocation_map_height' name='bitcoin_node_geolocation_map_height'>
    <?php
    $count = 400;
    while ( $count <= 900 ) {
    ?>
    <option value='<?=$count?>' <?=( $count == $node_geolocation_map_height_default ? 'selected' : '' )?>> <?=$count?> </option>
    <?php
    $count = $count + 100;
    }
    ?>
    </select>  &nbsp;&nbsp; 
    
    Filter By: <select class='browser-default custom-select' id='bitcoin_results_filter_type' name='bitcoin_results_filter_type'>
    <option value='country'> Country </option>
    <option value='city'> City </option>
    <option value='time_zone'> Time Zone </option>
    <option value='isp'> ISP </option>
    <option value='node_services_running'> Node Services Running </option>
    </select>  &nbsp;&nbsp; 
    
    <input type='text' size='20' name='bitcoin_results_filter' id='bitcoin_results_filter' placeholder="(optional)" />
    
    <input type='button' value='Update Bitcoin GeoLocation Map' onclick="
    
    // Remove old rendering
    geo_map_init['bitcoin_map'].remove();

    // Adjust map height
	$('#bitcoin_map').css('height', document.getElementById('bitcoin_node_geolocation_map_height').value + 'px');
    
    // Reload map
    map_init(
             'bitcoin_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=bitcoin_map&results_filter=' + document.getElementById('bitcoin_results_filter').value+'&results_filter_type=' + document.getElementById('bitcoin_results_filter_type').value,
             '<?=$bitcoin_node_geolocation_pretty_timestamp?>'
             );

    " /> 
    
    &nbsp; <img class="tooltip_style_control geolocation_filter" src="templates/interface/media/images/info.png" alt="" width="30" style="position: relative; left: -5px;" />
    
    <div id="bitcoin_map" class="secondary_chart_wrapper geolocation_map leaflet_zindex_fix" style="width: 100%; height: <?=$node_geolocation_map_height_default?>px; margin-top: 1.5em !important;"></div>

	
  </div>
  
  
    <script>
    
    // Render map
    map_init(
             'bitcoin_map',
             plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key=bitcoin_map&results_filter=' + document.getElementById('bitcoin_results_filter').value+'&results_filter_type=' + document.getElementById('bitcoin_results_filter_type').value,
             '<?=$bitcoin_node_geolocation_pretty_timestamp?>'
             );
		
    </script>
    
    
    
    <?php
    }
    ?>

    
  <p> &nbsp; </p>
	
	</div>
	<!-- END MORE BITCOIN STATS MODAL -->
	
	<script>
	
	modal_windows.push('.show_more_bitcoin_stats'); // Add to modal window tracking (for closing all dynaimically on app reloads) 
	
	$('.show_more_bitcoin_stats').modaal({
	fullscreen: true,
	content_source: '#show_more_bitcoin_stats',
         after_open: function() {
             
             // IF we need to run logic, AFTER OPENING THE MODAL
             setTimeout(function(){
             // Logic goes here
             }, 500);

         }
	});
	
	</script>
	
<?php
}
// Bitcoin Node stats END
?>
	
