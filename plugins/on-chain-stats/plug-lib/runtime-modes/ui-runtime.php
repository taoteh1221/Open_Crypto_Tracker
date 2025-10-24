<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */

?>

<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/leaflet.css" />
    
<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/MarkerCluster.css" />

<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/MarkerCluster.Default.css" />

<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-style.css" type="text/css" />

<script>

plugin_assets_path['<?=$this_plug?>'] = '<?=$ct['plug']->plug_dir(true)?>/plug-assets';

</script>
    
<script src="<?=$ct['plug']->plug_dir(true)?>/plug-assets/plug-init.js"></script>

<script src="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/leaflet.js"></script>

<script src="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/leaflet-color-markers.js"></script>	
	
<script src="<?=$ct['plug']->plug_dir(true)?>/plug-assets/leaflet/leaflet.markercluster.js"></script>
	
<?php
require($ct['plug']->plug_dir() . '/plug-lib/runtime-modes/ui/solana/ui-solana-nodes.php');
?>


    <script>
    

var node_count_chart_defaults_content = '<h5 class="yellow tooltip_title">Settings For Node Count Charts</h5>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">The "Custom Start Date" is OPTIONAL, for choosing a custom date in time for the Node Count chart to begin. The Custom Start Date can only go back in time as far back as you have data stored for, as this feature only starts storing node count data once your app server background task starts saving chart data for the first time. IF you have saved chart backups (in the Backup / Restore admin area), you can restore archived chart data on new installations.</p>'
			
			+'<p class="coin_info extra_margins" style=" white-space: normal;">Adjust the chart height and menu size, depending on your preferences. The defaults for these two settings can be changed in "Admin Area => Plugins => On-Chain Stats => Node Count Chart Defaults".</p>';
		
		
		
			$('.node_count_chart_defaults').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: node_count_chart_defaults_content,
			css: balloon_css()
			});
		
		
var geolocation_filter_content = '<h5 class="yellow tooltip_title">Settings For GeoLocation Maps</h5>'

			+'<p class="coin_info extra_margins" style=" white-space: normal;">You can filter what nodes will show on the map. Choose between validator nodes, RPC nodes, recently offline validator nodes (that have NOT voted this epoch), or all nodes.</p>'
			
			+'<p class="coin_info extra_margins" style=" white-space: normal;">Adjust the chart height, depending on your preferences. The default can be changed in "Admin Area => Plugins => On-Chain Stats => Node GeoLocation Map Height Default".</p>';
		
		
		
			$('.geolocation_filter').balloon({
			html: true,
			position: "bottom",
  			classname: 'balloon-tooltips',
			contents: geolocation_filter_content,
			css: balloon_css()
			});
			
		
    </script>
  