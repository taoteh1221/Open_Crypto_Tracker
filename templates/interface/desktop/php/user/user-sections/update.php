<?php
/*
 * Copyright 2014-2021 GPLv3, Open Crypto Portfolio Tracker by Mike Kilday: http://DragonFrugal.com
 */



?>

<div class='max_full_width_wrapper'>


<!--  !START! RE-USED INFO BUBBLE DATA  -->
<script>



		var average_paid_notes = '<h5 class="align_center yellow tooltip_title">Calculating Average <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Price Paid Per Token</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="green">Total <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Paid For All Tokens</span> <span class="blue">&#247;</span> <span class="yellow">Total Tokens Purchased</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">The RESULT of the above calculation <i>remains the same even AFTER you sell ANY amount, ONLY if you don\'t buy more between sells</i>. Everytime you buy more <i>after selling some</i>, re-calculate your Average <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Price Paid Per Token with this formula:</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">(<span class="green">Total <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Paid For All Tokens</span> <span class="blue">-</span> <span class="red">Total <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Received From All Sold Tokens</span>) <span class="blue">&#247;</span> <span class="yellow">Total Remaining Tokens Still Held</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><span class="yellow">PRO TIP:</span> <br /> When buying / selling, keep quick and dirty (yet clear) textual records of... <br />a) How much you bought / sold of what<br />b) What you paid / received in <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> value<br />c) What / where you traded <br />d) Backup to USB Stick / NAS / DropBox / GoogleDrive / OneDrive / AmazonBucket <br />e) Now you\'re ready for tax season, to create spreadsheets from this data</p>'
			
			+'<p class="coin_info extra_margins yellow" style="white-space: normal; max-width: 600px;">There is also an <i>open source / free</i> app called <a href="https://rotki.com" target="_blank">Rotki</a> that can help you <i>PRIVATELY</i> track your tax data.</p>'
			
			+'<p> </p>';

	
	
			var leverage_trading_notes = '<h5 class="align_center yellow tooltip_title">Tracking Long / Short Margin Leverage Trades</h5>'
			
			
			+'<p class="coin_info extra_margins red" style="white-space: normal; max-width: 600px;"><b>*Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). Never put more than ~5% of your total investment worth into leverage trades, or you will <u>RISK LOSING EVERYTHING</u>!</b></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Set the "Asset / Pairing @ Exchange" drop-down menus for the asset to any markets you prefer. It doesn\'t matter which ones you choose, as long as the price discovery closely matches the exchange where you are margin trading this asset.</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">Set the "Holdings" field to match your margin leverage deposit (example: buying 1 BTC @ 5x leverage would be 0.2 BTC in the "Holdings" field in this app). You\'ll also need to fill in the "Average Paid (per-token)" field with the average price paid in <?=strtoupper($pt_conf['gen']['btc_prim_curr_pairing'])?> per-token. Finally, set the "Margin Leverage" fields to match your leverage and whether you are long or short. When you are done, click "Save Updated Portfolio".</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">To see your margin leverage stats after updating your portfolio, go to the bottom of the Portfolio page, where you\'ll find a summary section. Hovering over the "I" icon next to the Gain / Loss summary will display any margin leverage stats per-asset. There is also an "I" icon in the far right-side data table column (Subtotal) per-asset, which you can also hover over for margin leverage stats.</p>'
			
			+'<p class="coin_info balloon_notation extra_margins yellow" style="white-space: normal; max-width: 600px;">*Current maximum margin leverage setting of <?=$pt_conf['power']['margin_leverage_max']?>x can be adjusted in the Admin Config POWER USER section.</p>'
			
			+'<p> </p>';

	
	
			var portfolio_data_privacy = '<h5 class="align_center bitcoin tooltip_title">How is my portfolio data stored by this app?</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><i class="bitcoin">TLDR: <u>NOBODY EXCEPT YOU ON YOUR COMPUTER</u> CAN SEE THE INFORMATION YOU ENTER IN THIS APP (<u>NO DATA</u> IS STORED REMOTELY).</i></p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><i class="bitcoin"><u>Your portfolio data is NEVER stored in the app</u></i>, it is ONLY stored on your computer in your web browser (either temporarily in the web browser temporary files cache, or semi-permanently in web browser cookies IF YOU MANUALLY ENABLE COOKIES ON THE SETTINGS PAGE).</p>'
			
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><i class="bitcoin"><u>By default</u></i>, your portfolio data needs to be re-entered to calculate your portfolio value, <i class="bitcoin">every time you close / re-open the app\'s tab in your web browser</i>.</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;"><i class="bitcoin"><u>By default</u></i>, your portfolio data is only saved <i class="bitcoin">temporarily on your computer within your web browser</i> (a default behavior of all modern web browsers), for re-submitting / refreshing / reloading app price data <i class="bitcoin">until you close the app\'s tab in your web browser</i>.</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; max-width: 600px;">If you prefer to store your portfolio data <i class="bitcoin">semi-permanently on your computer within your web browser as cookie data (to save between browser sessions)</i>, <i class="bitcoin"><u>you must manually enable</u></i> "Use cookies to save data" on the Settings page. </p>'
			
			
			+'<p> </p>';

	
	
			var random_tip_disclaimer = '<h5 class="align_center bitcoin tooltip_title">Random Tips Disclaimer</h5>'
			
			
			+'<p class="coin_info extra_margins bitcoin" style="white-space: normal; max-width: 600px;">This "Random Tips" section SHOULD NEVER TAKE THE PLACE OF ADVICE FROM A PROFESSIONAL FINANCIAL ADVISER!</p>'
			
			+'<p class="coin_info extra_margins bitcoin" style="white-space: normal; max-width: 600px;">"Random Tips" are only designed to provide VERY BASIC INSIGHT for people new to cryptocurrency, AND DOES NOT / CANNOT TAKE INTO ACCOUNT UNIQUE SITUATIONS INVESTORS MAY BE IN. ALWAYS CONSULT A FINANCIAL ADVISER IF YOU ARE UNAWARE OF ALL RISKS FOR YOUR PARTICULAR SITUATION!</p>'
			
			
			+'<p> </p>';

	
	
			var spreadsheet_import_export = '<h5 class="align_center yellow tooltip_title">Spreadsheet Import / Export</h5>'
			
			
			+'<p class="coin_info" style="white-space: normal; max-width: 600px;">You can import / export your portfolio as a CSV spreadsheet saved on your computer, for portfolio backup / editing offline:</p>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;"><img src="templates/interface/media/images/auto-preloaded/csv-spreadsheet-example.png" width="590" title="CSV Spreadsheet of Portfolio" /></p>'
			
			+'<p class="coin_info" style="max-width: 600px; white-space: normal;"><span class="yellow">PRO TIP:</span> <br /> To easily display different portfolio setups SEPERATELY in this app (long term holdings / short term holdings / etc), save each of your different portfolio setups to seperate spreadsheet backup files. Then import whichever spreadsheet you wish into this app for portfolio tracking.</p>'
			
			
			+'<p> </p>';
			
			
			
</script>
<!--  !END! RE-USED INFO BUBBLE DATA  -->
				
				
				
				
				<span class='red countdown_notice'></span>
				
				
	<p style='margin-top: 20px;'><a style='font-weight: bold;' class='red clear_both' href='javascript: show_more("disclaimer");' title='Click to show disclaimer.'>Disclaimer!</a></p>
	    
	    
	    
		<div id='disclaimer' style='display: none;' class='align_left clear_both'>
			
	     
						<p class='red' style='font-weight: bold;'>
						
						Assets in the default examples / demo list DO NOT indicate ANY endorsement OR recommendation of these assets (AND removal indicates NO anti-endorsement / anti-recommendation). These crypto-assets <i>are only used as examples for demoing usage of features in this application</i>, <a href='README.txt' target='_blank'>before you install it on your Ubuntu / Raspberry Pi device or website server, and change the list to your favorite assets</a>. 
						
						<br /><br />Consult a financial advisor and / or do <i>your own due diligence, to evaluate investment risk / reward</i> of ANY cryptocurrencies, based on THEIR / YOUR OWN determinations before buying. Even AFTER buying ANY cryptocurrency, ALWAYS CONTINUE to do your due diligence, investigating whether you are engaging in trading within acceptable risk levels for your <i>NET</i> worth. ALWAYS consult a financial advisor, if you are unaware of what risks are present. 
						
						</p>
	
						<div class='bitcoin' style='padding-top: 8px; font-weight: bold;'>
						
						<i><u>Expanded-upon version of above IMPORTANT disclaimer / advisory</u>:</i> 
						
						<ul>
						
							<li class='bitcoin disclaimer'>
								<i>LITERALLY</i> nearly 99.9% of all tokens are either scams, garbage, or dead ends.
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>NEVER</i> invest more than you can afford to lose.
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>NEVER</i> buy an asset because of somebody's opinion of it (only buy based on <i>YOUR</i> opinion of it).
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS <u>buy low</u> AND <u>sell high</u></i>. (NOT the other way around!), *UNLESS* you CAREFULLY decide you've accidentally bought an asset that will probably go nowhere in value long term, relative to other assets you are interested in.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS</i> diversify / balance your portfolio with <i>mostly largest AND oldest marketcaps (which are <i>relatively</i> less volatile) / HIGHEST ON-CHAIN ACTIVITY</i> assets, for you <i>and yours safety and sanity</i>.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as long term chart TA, <i>and any short term chart TA is pure BS to be ignored</i>).
							</li>
							
							 <li class='bitcoin disclaimer'>
								"Fully research" does NOT include *BLINDLY* believing some CEO / founder / influencer sweet talking their own token, telling you how competing systems suck and their system is better, or explaining how them owning over 50% of the total coin supply is not out of greed.
							</li>
							
							<li class='bitcoin disclaimer'>
								ALWAYS have a future plan in place, of what you will buy / sell: 1) Around a certain future date in time. 2) If a certain price target has been met or exceeded. This doesn't need to be "all in" or "all out". For instance, you may want to split your capital gains between 2 tax years within a tight time period, in late December / early the following January, to avoid higher tax brackets.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.
							</li>
						
							<li class='bitcoin disclaimer'>
								<i><u>ALWAYS AVOID</u></i> copycat coins (that copy / mimick already-popular networks), coins with high inflation rates (creating too many new coins infinitely), coins that are NOT on a fully decentralized network (small groups control everything), and coins with very little on-chain transaction activity (indicating low <i>REAL WORLD</i> user adoption).
							</li>
							
							<li class='bitcoin disclaimer'>
								If you insist on buying LONG SHOT (#VERY# high risk) SMALL marketcap or NEWER assets (requiring #A TON# OF DILIGENCE / PATIENCE), *HIGHLY* consider getting #NO MORE THAN# a 'moon bag' worth (#NO MORE THAN# between 1% and 5% of your portfolio PER-ASSET, AND A TOTAL OF #NO MORE THAN# 10% of your portfolio). If it goes down 50% in value and keeps going down, sell it and you don't lose much. If it goes up between 200% and 500% in value (3x to 6x original value) or higher, REBALANCE it to not be more than between 1% and 10% of your portfolio again (by selling a significant portion of it). CAREFULLY TRACK YOUR SUCCESS RATE. If you are no good at picking long shots, stick to the <i>largest AND oldest marketcaps / HIGHEST ON-CHAIN ACTIVITY</i> assets instead.
							</li>
							
							<li class='bitcoin disclaimer'>
								The "grass on the other side looks greener" MORE OFTEN THAN NOT will always be in your head, when you see other assets performing better than the ones you currently are holding. That's why it's SO IMPORTANT TO DO YOUR REASEARCH FULLY! If you KNOW you didn't buy garbage, AND you didn't over-extend your position (you CAN afford to hold it for a few years without selling any), just RELAX AND DON'T BE GREEDY...otherwise you are living for money (not ENJOYING life itself) AND YOU WILL END UP FLIPPING YOURSELF OUT, WHICH IS A CRAPPY WAY TO LIVE!
							</li>
							
							<li class='bitcoin disclaimer'>
								The biggest investment you will EVER make (and NEVER regret) is ALWAYS value your health and happiness more than ALL THE MONEY IN THE WORLD. NEVER let chasing after ANY AMOUNT of wealth take that from you, EVER.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>Hang on tight</i> until you can't stand fully holding anymore / want to or must make a position exit percentage <i><u>OFFICIAL</u></i>. (YOU HAVEN'T "LOST" <i><u>OR</u></i> "MADE" <i><u>ANYTHING</u></i> UNTIL YOU SELL A PERCENTAGE OF IT!)
							</li>
							
							<li class='bitcoin disclaimer'>
								Best of luck, be careful out there in this cryptoland frontier <i>full of dead-end coins, garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues (wolves in sheep's clothing)! 😮
							</li>
						
						</ul>
						
						
						<br /><br /><a href="https://twitter.com/taoteh1221/status/1192997965952094208" target="_blank"><img src='templates/interface/media/images/twitter-1192997965952094208.jpg' width='425' class='image_border' alt='' /></a>
						
						</div>
	
		<br clear='all' />
		
		</div>
		
		
	<p style='margin-top: 20px;'><span style='font-weight: bold;' class='bitcoin'>How is my portfolio data stored by this app?</span> 
	     
		<img id='portfolio_data_privacy' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative; left: -5px;' /> </p>
		
	 <script>
		
			$('#portfolio_data_privacy').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: portfolio_data_privacy,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
	    
	
	<div style='margin-top: 20px; max-width: 1200px;' class='bitcoin random_tip'>
	
		<p>
	
			<b>Random Tip:</b><img id='random_tip_disclaimer' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative; padding: 0px; margin: 0px; vertical-align: middle;' />  <a href='javascript: random_tips();'>Show Another Tip</a>
	
		</p>
	
		<p id='quoteContainer'></p>
	
	</div>
	
	
	<script>
		
			$('#random_tip_disclaimer').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: random_tip_disclaimer,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
			
	
	<div> &nbsp; </div>
	
	<p><a style='font-weight: bold;' href='README.txt' target='_blank'>Editing The Portfolio Assets List, and Enabling Email / Text / Telegram / Alexa / Google Home Price Alerts (installation on Ubuntu, Raspberry Pi, or website)</a></p>
	
				
			
	<div class='align_left clear_both' style='margin-top: 40px; margin-bottom: 15px; white-space: nowrap;'>
	
		
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<button class='force_button_style' onclick='
		$("#coin_amounts").submit();
		'>Save Updated Portfolio</button>
	
		<form style='display: inline;' name='csv_import' id='csv_import' enctype="multipart/form-data" action="<?=$pt_gen->start_page($_GET['start_page'])?>" method="post">
		
	    <input type="hidden" name="csv_check" value="1" />
	    
	    <span id='file_upload'><input style='margin-left: 85px;' name="csv_file" type="file" /></span>
	    
	    <input type="button" onclick='validateForm("csv_import", "csv_file");' value="Import Portfolio From CSV File" />
	    
		</form>
		
		
		<button style='margin-left: 40px;' class='force_button_style' onclick='
		set_target_action("coin_amounts", "_blank", "download.php?csv_export=1");
		document.coin_amounts.submit(); // USE NON-JQUERY METHOD SO "APP LOADING..." DOES #NOT# SHOW
		set_target_action("coin_amounts", "_self", "<?=$pt_gen->start_page($_GET['start_page'])?>");
		'>Export Portfolio To CSV File</button>
		
		
		<a style='margin-left: 40px; text-decoration: none;' class='force_button_style' href="download.php?csv_export=1&example_template=1" target="_blank">Example CSV File</a>
	     
		<img id='spreadsheet_import_export' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: 5px;' /> 
		
	 <script>
		
			$('#spreadsheet_import_export').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: spreadsheet_import_export,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		
		
	</div>
	
		
	
	<div> &nbsp; </div>
		
	<div style='display: inline-block; border: 2px dotted black; padding: 7px; margin-left: 0px; margin-top: 15px; margin-bottom: 15px;'>
	
		<div class='align_center' style='font-weight: bold;'>Watch Only</div>
	
		<div style='margin-left: 6px;'><input type='checkbox' onclick='selectAll(this, "coin_amounts");' /> <b>Select / Unselect All <i><u>Unheld</u> Assets</i></b>	</div>
		
	
	</div>
	
	
	<br class='clear_both' />	
	
	 <?php
	 if ( $csv_import_fail != NULL ) {
	 ?>
	<br />	
	 <div class='red red_dotted' style='font-weight: bold;'><?=$csv_import_fail?></div>
	<br />	
	<br />	
	 <?php
	 }
	 if ( $csv_import_succeed != NULL ) {
	 ?>
	<br />	
	 <div class='green green_dotted' style='font-weight: bold;'><?=$csv_import_succeed?></div>
	<br />	
	<br />	
	 <?php
	 }
	 ?>
	
	
	
	<form id='coin_amounts' name='coin_amounts' action='<?=$pt_gen->start_page($_GET['start_page'])?>' method='post'>
	
		
	<div class='long_list_start list_start_black'> &nbsp; </div>
	
	<?php
	
	if ( is_array($pt_conf['assets']) ) {

	    
	    $zebra_stripe = 'long_list_odd';
	    foreach ( $pt_conf['assets'] as $asset_array_key => $asset_array_val ) {
		
		 $rand_id = rand(10000000,100000000);
	    
	    $field_var_pairing = strtolower($asset_array_key) . '_pairing';
	    $field_var_market = strtolower($asset_array_key) . '_market';
	    $field_var_amount = strtolower($asset_array_key) . '_amount';
	    $field_var_paid = strtolower($asset_array_key) . '_paid';
	    $field_var_leverage = strtolower($asset_array_key) . '_leverage';
	    $field_var_margintype = strtolower($asset_array_key) . '_margintype';
	    $field_var_watchonly = strtolower($asset_array_key) . '_watchonly';
	    $field_var_restore = strtolower($asset_array_key) . '_restore';
	    
	    
	        if ( $_POST['submit_check'] == 1 ) {
	        $asset_pairing_id = $_POST[$field_var_pairing];
	        $asset_market_id = $_POST[$field_var_market];
	        $asset_amount_val = $pt_var->rem_num_format($_POST[$field_var_amount]);
	        $asset_paid_val = $pt_var->rem_num_format($_POST[$field_var_paid]);
	        $asset_leverage_val = $_POST[$field_var_leverage];
	        $asset_margintype_val = $_POST[$field_var_margintype];
	        }
	        elseif ( $run_csv_import == 1 ) {
	        	
	        
	        		foreach( $csv_file_array as $key => $val ) {
	        		
	        			if ( strtoupper($asset_array_key) == strtoupper($key) ) {
	        			
	        			// We already validated / auto-corrected $csv_file_array
	        		 	$asset_amount_val = $val[1];
	       		 	$asset_paid_val = $val[2];
	       		 	$asset_leverage_val = $val[3];
	        			$asset_margintype_val = $val[4];
	        			$asset_market_id = $val[5];
	        		 	$asset_pairing_id = $val[6];
	        			
	       		 	}
	        	
	        		}
	        		
	        
	        }
	        
	
	    
	    	  // Cookies
	        if ( !$run_csv_import && $_COOKIE['coin_pairings'] ) {
	        
	        $all_coin_pairings_cookie_array = explode("#", $_COOKIE['coin_pairings']);
	        
		if ( is_array($all_coin_pairings_cookie_array) ) {
		    
		    foreach ( $all_coin_pairings_cookie_array as $asset_pairings ) {
		        
		    $single_coin_pairings_cookie_array = explode("-", $asset_pairings);
		    
		    $asset_symb = strtoupper(preg_replace("/_pairing/i", "", $single_coin_pairings_cookie_array[0]));  
		    
		        if ( $asset_symb == strtoupper($asset_array_key) ) {
		        $asset_pairing_id = $single_coin_pairings_cookie_array[1];
		        }
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	        
	        
	        if ( !$run_csv_import && $_COOKIE['coin_markets'] ) {
	        
	        $all_coin_markets_cookie_array = explode("#", $_COOKIE['coin_markets']);
	        
		if ( is_array($all_coin_markets_cookie_array) ) {
		    
		    foreach ( $all_coin_markets_cookie_array as $asset_markets ) {
		        
		    $single_coin_markets_cookie_array = explode("-", $asset_markets);
		    
		    $asset_symb = strtoupper(preg_replace("/_market/i", "", $single_coin_markets_cookie_array[0]));  
		    
		        if ( $asset_symb == strtoupper($asset_array_key) ) {
		        $asset_market_id = $single_coin_markets_cookie_array[1];
		        }
		    
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_amounts'] ) {
	        
	        $all_coin_amounts_cookie_array = explode("#", $_COOKIE['coin_amounts']);
	        
		if ( is_array($all_coin_amounts_cookie_array) ) {
		    
		    foreach ( $all_coin_amounts_cookie_array as $asset_amounts ) {
		        
		    $single_coin_amounts_cookie_array = explode("-", $asset_amounts);
		    
		    $asset_symb = strtoupper(preg_replace("/_amount/i", "", $single_coin_amounts_cookie_array[0]));  
		    
		    		// We don't need $pt_var->rem_num_format() for cookie data, because it was already done creating the cookies
					if ( $asset_symb == strtoupper($asset_array_key) ) {
					$asset_amount_val = $pt_var->num_to_str($single_coin_amounts_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_paid'] ) {
	        
	        $all_coin_paid_cookie_array = explode("#", $_COOKIE['coin_paid']);
	        
		if ( is_array($all_coin_paid_cookie_array) ) {
		    
		    foreach ( $all_coin_paid_cookie_array as $asset_paid ) {
		        
		    $single_coin_paid_cookie_array = explode("-", $asset_paid);
		    
		    $asset_symb = strtoupper(preg_replace("/_paid/i", "", $single_coin_paid_cookie_array[0]));  
		    		
		    		// We don't need $pt_var->rem_num_format() for cookie data, because it was already done creating the cookies
					if ( $asset_symb == strtoupper($asset_array_key) ) {
					$asset_paid_val = $pt_var->num_to_str($single_coin_paid_cookie_array[1]);
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_leverage'] ) {
	        
	        $all_coin_leverage_cookie_array = explode("#", $_COOKIE['coin_leverage']);
	        
		if ( is_array($all_coin_leverage_cookie_array) ) {
		    
		    foreach ( $all_coin_leverage_cookie_array as $asset_leverage ) {
		        
		    $single_coin_leverage_cookie_array = explode("-", $asset_leverage);
		    
		    $asset_symb = strtoupper(preg_replace("/_leverage/i", "", $single_coin_leverage_cookie_array[0]));  
		    
					if ( $asset_symb == strtoupper($asset_array_key) ) {
					$asset_leverage_val = $single_coin_leverage_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	
	        if ( !$run_csv_import && $_COOKIE['coin_margintype'] ) {
	        
	        $all_coin_margintype_cookie_array = explode("#", $_COOKIE['coin_margintype']);
	        
		if ( is_array($all_coin_margintype_cookie_array) ) {
		    
		    foreach ( $all_coin_margintype_cookie_array as $asset_margintype ) {
		        
		    $single_coin_margintype_cookie_array = explode("-", $asset_margintype);
		    
		    $asset_symb = strtoupper(preg_replace("/_margintype/i", "", $single_coin_margintype_cookie_array[0]));  
		    
					if ( $asset_symb == strtoupper($asset_array_key) ) {
					$asset_margintype_val = $single_coin_margintype_cookie_array[1];
					}
		    
		    
		    }
		    
		}
	        
	        
	        }
	        
	      
	      
	    
	    	if ( strtoupper($asset_array_key) == 'MISCASSETS' ) {
	    	$asset_amount_dec = 2;
	    	$disable_fields = 'disabled';
	    	}
	    	else {
	    	$asset_amount_dec = 8;
	    	$disable_fields = null;
	    	}
	    
	    
	  	 $asset_amount_val = $pt_var->num_pretty($asset_amount_val, $asset_amount_dec, TRUE); // TRUE = Show even if low value is off the map, just for UX purposes tracking token price only, etc
	    
	    // Set any previously-used additional feilds to default, if 'watch only' now (no amount held)
	    if ( $pt_var->rem_num_format($asset_amount_val) < 0.00000001 ) {
	    $asset_paid_val = 0;
	    $asset_leverage_val = 0;
	    $asset_margintype_val = 'long';
	    
	    }
	    else {
	    $asset_paid_val = ( $pt_var->num_to_str($asset_paid_val) >= $pt_conf['gen']['prim_curr_dec_max_thres'] ? $pt_var->num_pretty($asset_paid_val, 2) : $pt_var->num_pretty($asset_paid_val, $pt_conf['gen']['prim_curr_dec_max']) );
	    }
	    
	    
	  	 
	    	
	    ?>
	    
	    <div class='<?=$zebra_stripe?> long_list_taller' style='white-space: nowrap;'> 
	       
	       
	       <input type='checkbox' value='<?=strtolower($asset_array_key)?>' id='<?=$field_var_watchonly?>' onchange='watch_toggle(this);' <?=( $pt_var->rem_num_format($asset_amount_val) > 0 && $pt_var->rem_num_format($asset_amount_val) <= '0.000000001' ? 'checked' : '' )?> /> &nbsp;
				    
				    
			<b class='blue'><?=$asset_array_val['name']?> (<?=strtoupper($asset_array_key)?>)</b> /  
	       
	       
				    <select class='browser-default custom-select' onchange='
				    
				    $("#<?=$field_var_market?>_lists").children().hide(); 
				    $("#" + this.value + "<?=$asset_array_key?>_pairs").show(); 
				    
				    $("#<?=$field_var_market?>").val( $("#" + this.value + "<?=$asset_array_key?>_pairs option:selected").val() );
				    
				    ' id='<?=$field_var_pairing?>' name='<?=$field_var_pairing?>'>
					<?php
					
					// Get default BITCOIN pairing key for further down in the logic, if no $asset_pairing_id value was set FOR BITCOIN
					if ( strtolower($asset_array_val['name']) == 'bitcoin' ) {
					$sel_pairing = ( isset($asset_pairing_id) ? $asset_pairing_id : $pt_conf['gen']['btc_prim_curr_pairing'] );
					}
					else {
					$sel_pairing = $asset_pairing_id;
					}
					
					
					foreach ( $asset_array_val['pairing'] as $pairing_key => $pairing_id ) {
					 	
					 	// Set pairing key if not set yet (values not yet populated etc)
					 	if ( !isset($sel_pairing) ) {
					 	$sel_pairing = $pairing_key;
					 	}
						
					?>
					<option value='<?=$pairing_key?>' <?=( $sel_pairing == $pairing_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pairing_key))?> </option>
					<?php
					
									foreach ( $asset_array_val['pairing'][$pairing_key] as $market_key => $market_id ) {
									$loop2 = $loop2 + 1;
							
									$html_market_list[$pairing_key] .= "\n<option value='".$loop2."'" . ( 
									isset($asset_market_id) && ($asset_market_id) == $loop2 
									|| !isset($asset_market_id) && strtolower($asset_array_val['name']) == 'bitcoin' && $loop2 == $pt_asset->btc_market($pt_conf['gen']['btc_prim_exchange']) ? ' selected ' : '' ) . ">" . $pt_gen->snake_case_to_name($market_key) . " </option>\n";
								
									}
									$loop2 = NULL;
							
							
					}
					?>
				    </select> 
				    
				    
				     @ <input type='hidden' id='<?=$field_var_market?>' name='<?=$field_var_market?>' value='<?php
				     
				     if ( $_POST[$field_var_market] ) {
				     echo $_POST[$field_var_market];
				     }
				     elseif ( isset($asset_market_id) ) {
				     echo $asset_market_id;
				     }
				     elseif ( !isset($asset_market_id) && strtolower($asset_array_val['name']) == 'bitcoin' ) {
				     echo $pt_asset->btc_market($pt_conf['gen']['btc_prim_exchange']);
				     }
				     else {
						echo '1';
				     }
				     
				     ?>'>
				     
				     
				     <span id='<?=$field_var_market?>_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pairing: <?=$sel_pairing?> -->
				    <?php
				    
				    foreach ( $html_market_list as $key => $val ) {
				    ?>
				    
				    <select class='browser-default custom-select' onchange ='
				    
				    $("#<?=$field_var_market?>").val( this.value );
				    
				    ' id='<?=$key.$asset_array_key?>_pairs' style='display: <?=( $sel_pairing == $key ? 'inline' : 'none' )?>;'><?=$html_market_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $html_market_list = NULL;
				    ?>
				    
				    </span> &nbsp;  &nbsp; 
				    
				    
			
	     			 <b>Holdings:</b> <input type='text' size='11' id='<?=$field_var_amount?>' name='<?=$field_var_amount?>' value='<?=$asset_amount_val?>' onkeyup='
	     
	     $("#<?=strtolower($asset_array_key)?>_restore").val( $("#<?=strtolower($asset_array_key)?>_amount").val() );
	     
	     ' onblur='
	     
	     $("#<?=strtolower($asset_array_key)?>_restore").val( $("#<?=strtolower($asset_array_key)?>_amount").val() );
	     
	     ' <?=( $pt_var->rem_num_format($asset_amount_val) > 0 && $pt_var->rem_num_format($asset_amount_val) <= '0.000000001' ? 'readonly' : '' )?> /> <span class='blue'><?=strtoupper($asset_array_key)?></span>  &nbsp;  &nbsp; 
			    
			
	     <b>Average Paid (per-token):</b> <?=$pt_conf['power']['btc_curr_markets'][$pt_conf['gen']['btc_prim_curr_pairing']]?><input type='text' size='10' id='<?=$field_var_paid?>' name='<?=$field_var_paid?>' value='<?=$asset_paid_val?>' <?=$disable_fields?> /> 
	     
	     
		<img id='average_paid_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
	
			$('#average_paid_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: average_paid_notes,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		   &nbsp;  &nbsp; 
	     
	     
	     <b>Margin Leverage:</b> 
	     
	     <select class='browser-default custom-select' name='<?=$field_var_leverage?>' id='<?=$field_var_leverage?>' onchange='
	     if ( this.value <= 5 ) {
	     var mode = "Sane";
	     }
	     else if ( this.value <= 10 ) {
	     var mode = "Mostly Sane";
	     }
	     else if ( this.value <= 20 ) {
	     var mode = "Half Sane";
	     }
	     else if ( this.value <= 30 ) {
	     var mode = "Insane";
	     }
	     else if ( this.value <= 40 ) {
	     var mode = "Crazy";
	     }
	     else if ( this.value <= 50 ) {
	     var mode = "Batshit Crazy";
	     }
	     else if ( this.value > 50 ) {
	     var mode = "Beyond Batshit Crazy";
	     }
	     alert(" " + this.value + "x (" + mode + " Mode) \n Leverage trading in crypto assets is \n EXTREMELY RISKY. NEVER put more \n than ~5% of your crypto investments \n in leveraged trades EVER, OR YOU \n ###COULD LOSE EVERYTHING###. ");
	     ' <?=$disable_fields?> >
	     <option value='0' <?=( $asset_leverage_val == 0 || $pt_var->rem_num_format($asset_paid_val) < 0.00000001 ? 'selected' : '' )?>> None </option>
	     <?php
	     $leverage_count = 2;
	     while ( $pt_conf['power']['margin_leverage_max'] > 1 && $leverage_count <= $pt_conf['power']['margin_leverage_max'] ) {
	     ?>	     
	     <option value='<?=$leverage_count?>' <?=( $asset_leverage_val == $leverage_count && $pt_var->rem_num_format($asset_paid_val) >= 0.00000001 ? 'selected' : '' )?>> <?=$leverage_count?>x </option>
	     <?php
	     $leverage_count = $leverage_count + 1;
	     }
	     ?>
	     </select> 
	     
	     
	     <select class='browser-default custom-select' name='<?=$field_var_margintype?>' id='<?=$field_var_margintype?>' <?=$disable_fields?> >
	     <option value='long' <?=( $asset_margintype_val == 'long' ? 'selected' : '' )?>> Long </option>
	     <option value='short' <?=( $asset_leverage_val >= 2 && $asset_margintype_val == 'short' ? 'selected' : '' )?>> Short </option>
	     </select> 
	     
	     
		<img id='leverage_trading_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
		
			$('#leverage_trading_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: leverage_trading_notes,
			css: {
					fontSize: ".8rem",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "32767",
					textAlign: "left"
					}
			});
		
		 </script>
		 
	     
	     
	     <input type='hidden' id='<?=$field_var_restore?>' name='<?=$field_var_restore?>' value='<?=( $pt_var->rem_num_format($asset_amount_val) > 0 && $pt_var->rem_num_format($asset_amount_val) <= '0.000000001' ? '' : $asset_amount_val )?>' />
				
				
	    </div>
	    
	    
	    <?php
	    
		 	if ( $zebra_stripe == 'long_list_odd' ) {
		 	$zebra_stripe = 'long_list_even';
		 	}
		 	else {
		 	$zebra_stripe = 'long_list_odd';
		 	}
	    
	    $asset_symb = NULL;
	    
	    $asset_pairing_id = NULL;
	    $asset_market_id = NULL;
	    $asset_amount_val = NULL;
 		 $asset_paid_val = NULL;
	    
	    }
	    
	    
	}
	?>
	
	<div class='long_list_end list_end_black'> &nbsp; </div>
	
	
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	
	<input type='hidden' id='theme_selected' name='theme_selected' value='<?=$theme_selected?>' />
	
	<input type='hidden' id='sort_by' name='sort_by' value='<?=($sorted_by_col)?>|<?=($sorted_by_asc_desc)?>' />
	
	<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( $_COOKIE['coin_amounts'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_notes' name='use_notes' value='<?php echo ( $_COOKIE['notes'] != '' ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?=( $_POST['use_alert_percent'] != '' ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] )?>' />
	
	<input type='hidden' id='show_charts' name='show_charts' value='<?=( $_POST['show_charts'] != '' ? $_POST['show_charts'] : $_COOKIE['show_charts'] )?>' />
	
	<input type='hidden' id='show_crypto_val' name='show_crypto_val' value='<?=( $_POST['show_crypto_val'] != '' ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] )?>' />
	
	<input type='hidden' id='show_secondary_trade_val' name='show_secondary_trade_val' value='<?=( $_POST['show_secondary_trade_val'] != '' ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] )?>' />
	
	<input type='hidden' id='show_feeds' name='show_feeds' value='<?=( $_POST['show_feeds'] != '' ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] )?>' />
	
	<input type='hidden' id='prim_curr_market_standalone' name='prim_curr_market_standalone' value='<?=( $_POST['prim_curr_market_standalone'] != '' ? $_POST['prim_curr_market_standalone'] : $_COOKIE['prim_curr_market_standalone'] )?>' />
			
	<p><input type='submit' value='Save Updated Portfolio' /></p>
	
	</form>
	
	
			    
			    
</div> <!-- full_width_wrapper END -->



