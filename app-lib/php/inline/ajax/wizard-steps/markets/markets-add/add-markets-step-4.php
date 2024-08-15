<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


$ct['gen']->ajax_wizard_back_button("#update_markets_ajax");
     
?>

<h3 class='bitcoin input_margins'>STEP #4: Review Selected Markets</h3>

<?php
     
foreach ( $_POST as $post_key => $post_data ) {
     ?>
     
     <pre class='rounded'><code class='hide-x-scroll less' style='width: 100%;'>
     
     <?=$post_key?>:
     
     <?=print_r($post_data)?>
     
     </code></pre>
     
     <br /><br /><br />
     
     
     
     <?php
}

// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>