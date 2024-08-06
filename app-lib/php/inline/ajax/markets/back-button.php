<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


if ( isset($_GET['step']) && $_GET['step'] > 1 ) {
?>
<a style='font-weight: bold;' class='blue input_margins' href='javascript: ct_ajax_load("type=<?=$_GET['type']?>&step=<?=($_GET['step'] - 1)?>", "#update_markets_ajax", "previous step", false, true);' title='Go back to the previous step in this wizard.'>Go Back</a>
<?php
}


?>