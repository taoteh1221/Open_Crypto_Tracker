
// Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)


/////////////////////////////////////////////////////////////


var geo_map_init = new Object();

var geo_map_locations = new Object();

var geo_map_clusters = new Object();


/////////////////////////////////////////////////////////////


function map_init(map_key, filter, last_update) {
			
// Zoom = 2, on initial rendering
geo_map_init[map_key] = L.map(map_key).setView([18, 0], 2); 

    
    // Map configs
    L.tileLayer('//{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://openstreetmap.org" target="_BLANK">OpenStreetMap</a> Contributors | <span id="'+map_key+'_alert">Loading, please wait...</span> | Last Update: ' + last_update + ' (UTC)',
    maxZoom: 18,
    }).addTo(geo_map_init[map_key]);

    
    // Load locations via AJAX
    $.getJSON(plugin_assets_path['on-chain-stats'] + '/plug-ajax.php?type=map&mode=geolocation&map_key='+map_key+'&filter=' + filter, function (result) {
    load_geolocation_map(result, map_key);
    });

    
resize_geolocation_maps();
    
}


/////////////////////////////////////////////////////////////


function resize_geolocation_maps() {

// Get array of all targets
var geo_maps = document.querySelectorAll('.geolocation_map');
     
     // Listen on all targets
     geo_maps.forEach(function(single_map) {
          
          const observer = new IntersectionObserver((entries) => {
               
              entries.forEach((entry) => {
                 
                   if (entry.isIntersecting) {
                   
                         // We need to reset the map's size-rendering, as is JUST BECAME VISIBLE (modals, etc etc)
                         if ( typeof geo_map_init[single_map.id] != 'undefined'
                         && typeof geo_map_init[single_map.id].invalidateSize === 'function'
                         ) {
                         console.log("Resizing '"+single_map.id+"' geolocation map...");
                         geo_map_init[single_map.id].invalidateSize();
                         }
                   
                   } else {
                   // Perform actions when the element leaves the viewport
                   }
              
              });

          });
     
     observer.observe(single_map);
  
     });

}


/////////////////////////////////////////////////////////////


function load_geolocation_map(result, map_key) {
          
geo_map_locations[map_key] = [];
     
geo_map_clusters[map_key] = L.markerClusterGroup();

$("#"+map_key+"_alert").html('Loaded 0 map location(s)');
          
     
     // Process locations
     $.each(result, function(loop, sol_node){
          
     var count = loop + 1;
     
         // Pretty numbers
         count = count.toLocaleString(undefined, {
         minimumFractionDigits: 0,
         maximumFractionDigits: 0
         });
     
     $("#"+map_key+"_alert").html('Loaded '+count+' map location(s)');
     
     var is_validator = sol_node.description.match(/validator/i);
     
     var recently_offline_validator = sol_node.description.match(/validator recently offline/i);

     geo_map_locations[map_key][loop] = [sol_node.description, sol_node.latitude, sol_node.longitude, is_validator, recently_offline_validator];

     });
     
     
     // Cluster the locations (to scale well, for large data sets)
     for (var i = 0; i < geo_map_locations[map_key].length; i++) {
     
     var a = geo_map_locations[map_key][i];
     
     var title = a[0];
         
         // IF a RECENTLY OFFLINE validator, use a red marker instead
         if ( a[4] ) {
              var marker = L.marker(new L.LatLng(a[1], a[2]), {
              title: title,
              icon: redIcon
              });
         }
         // IF a validator, use an orange marker instead
         else if ( a[3] ) {
              var marker = L.marker(new L.LatLng(a[1], a[2]), {
              title: title,
              icon: orangeIcon
              });
         }
         else {
              var marker = L.marker(new L.LatLng(a[1], a[2]), {
              title: title
              });
         }
     
         marker.bindPopup(title, {
         maxWidth : 560
         });
     
     geo_map_clusters[map_key].addLayer(marker);
     
     }


// Add the clusters layer to the map
geo_map_init[map_key].addLayer(geo_map_clusters[map_key]);
             
}


/////////////////////////////////////////////////////////////


// Wait until the DOM has loaded before running DOM-related scripting
$(document).ready(function(){ 

resize_geolocation_maps();

});


/////////////////////////////////////////////////////////////


