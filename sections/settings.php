
			<?php
			
			
			if (is_array($coins_array) || is_object($coins_array)) {
			    
			    ?>
			    <p>Default Bitcoin Market: <select onchange='
			    document.getElementById("btc_market").selectedIndex = (this.value - 1);
			    '>
				<?php
				foreach ( $coins_array['BTC']['market_ids'] as $market_key => $market_name ) {
				$loop = $loop + 1;
				?>
				<option value='<?=$loop?>' <?=( isset($_POST['btc_market']) && ($_POST['btc_market']) == $loop || isset($btc_market) && $btc_market == ($loop - 1) ? ' selected ' : '' )?>> <?=ucfirst($market_key)?> </option>
				<?php
				}
				$loop = NULL;
				?>
			    </select></p>
			    <?php
			
			}
			
			?>
			
                        <p>
                        Save coin values as cookie data <input type='checkbox' name='set_use_cookies' id='set_use_cookies' value='1' onchange='
                        if ( this.checked != true ) {
			delete_cookie("coin_amounts");
			delete_cookie("coin_markets");
			delete_cookie("coin_reload");
			document.getElementById("use_cookies").value = "";
                        }
                        else {
			document.getElementById("use_cookies").value = "1";
                        }
                        ' <?php echo ( $_COOKIE['coin_amounts'] && $_POST['submit_check'] != 1 || $_POST['use_cookies'] == 1 && $_POST['submit_check'] == 1 ? ' checked="checked"' : ''); ?> />
                        </p>
                        <input type='button' value='Update Settings' onclick='console.log("use_cookies = " + document.getElementById("use_cookies").value); document.coin_amounts.submit();' />
                        