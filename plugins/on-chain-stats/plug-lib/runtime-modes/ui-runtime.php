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