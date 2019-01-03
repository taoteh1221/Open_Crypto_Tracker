<?php
/*
 * Copyright 2014-2019 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

				$_POST['network_measure'] = str_replace("    ", '', $_POST['network_measure']);
				$_POST['network_measure'] = str_replace(" ", '', $_POST['network_measure']);
				$_POST['network_measure'] = str_replace(",", '', $_POST['network_measure']);

				$miner_hashrate = ( trim($_POST['your_hashrate']) * trim($_POST['hash_level']) );

?>