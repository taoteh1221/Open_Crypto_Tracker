<?php
/*
 * Copyright 2014-2018 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */
	

				$_POST['difficulty'] = str_replace("    ", '', $_POST['difficulty']);
				$_POST['difficulty'] = str_replace(" ", '', $_POST['difficulty']);
				$_POST['difficulty'] = str_replace(",", '', $_POST['difficulty']);

				$miner_hashrate = ( trim($_POST['your_hashrate']) * trim($_POST['measure']) );

?>