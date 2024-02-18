<?php
/*
 * Copyright 2014-2024 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */



?>

	
<h2 class='bitcoin page_title'>Update Portfolio</h2>
	            

<div class='full_width_wrapper'>

    
<!--  !START! RE-USED INFO BUBBLE DATA  -->
<script>



		var average_paid_notes = '<h5 class="align_center yellow tooltip_title">Calculating Average <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Price Paid Per Token</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="green">Total <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Paid For All Tokens</span> <span class="blue">&#247;</span> <span class="yellow">Total Tokens Purchased</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">The RESULT of the above calculation <i>remains the same even AFTER you sell ANY amount, ONLY if you don\'t buy more between sells</i>. Everytime you buy more <i>after selling some</i>, re-calculate your Average <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Price Paid Per Token with this formula:</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">(<span class="green">Total <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Paid For All Tokens</span> <span class="blue">-</span> <span class="red">Total <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Received From All Sold Tokens</span>) <span class="blue">&#247;</span> <span class="yellow">Total Remaining Tokens Still Held</span> <span class="blue">=</span> <span class="bitcoin">Average <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> Price Paid Per Token</span></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><span class="bitcoin">PRO TIP:</span> <br /> When buying / selling, keep quick and dirty (yet clear) textual records of... <br />a) How much you bought / sold of what<br />b) What you paid / received in <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> value<br />c) What / where you traded <br />d) Backup to USB Stick / NAS / DropBox / GoogleDrive / OneDrive / AmazonBucket <br />e) Now you\'re ready for tax season, to create spreadsheets from this data</p>'
			
			+'<p class="coin_info extra_margins yellow" style="white-space: normal; ">There is also an <i>open source / free</i> app called <a href="https://rotki.com" target="_blank">Rotki</a> that can help you <i>PRIVATELY</i> track your tax data.</p>'
			
			+'<p> </p>';

	
	
			var lvrg_trading_notes = '<h5 class="align_center yellow tooltip_title">Tracking Long / Short Margin Leverage Trades</h5>'
			
			
			+'<p class="coin_info extra_margins red" style="white-space: normal; "><b>*Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). Never put more than ~5% of your total investment worth into leverage trades, or you will <u>RISK LOSING EVERYTHING</u>!</b></p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">Set the "Asset / Pair @ Exchange" drop-down menus for the asset to any markets you prefer. It doesn\'t matter which ones you choose, as long as the price discovery closely matches the exchange where you are margin trading this asset.</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">Set the "Holdings" field to match your margin leverage deposit (example: buying 1 BTC @ 5x leverage would be 0.2 BTC in the "Holdings" field in this app). You\'ll also need to fill in the "Average Paid (per-token)" field with the average price paid in <?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?> per-token. Finally, set the "Margin Leverage" fields to match your leverage and whether you are long or short. When you are done, click "Save Updated Portfolio".</p>'
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">To see your margin leverage stats after updating your portfolio, go to the bottom of the Portfolio page, where you\'ll find a summary section. Hovering over the "I" icon next to the Gain / Loss summary will display any margin leverage stats per-asset. There is also an "I" icon in the far right-side data table column "(<?=strtoupper($ct['conf']['gen']['bitcoin_primary_currency_pair'])?>)" per-asset, which you can also hover over for margin leverage stats.</p>'
			
			+'<p class="coin_info balloon_notation extra_margins yellow" style="white-space: normal; ">*Current maximum margin leverage setting of <?=$ct['conf']['power']['margin_leverage_maximum']?>x can be adjusted in the Admin Config POWER USER section.</p>'
			
			+'<p> </p>';

	
	
			var portfolio_data_privacy = '<h5 class="align_center bitcoin tooltip_title">How is my data stored by this app?</h5>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin">TLDR: <u>NOBODY EXCEPT YOU ON YOUR COMPUTER</u> CAN SEE THE PORTFOLIO DATA YOU ENTER IN THIS APP (<u>NO PORTFOLIO DATA</u> IS STORED REMOTELY).</i></p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>ADMIN AND PLUGIN CONFIG SETTINGS are the only data stored in the app</u></i>, everything else is stored temporarily or semi-permanently in the web browser on your computer that you use to access the app with (even the "Desktop Edition" is an embedded web browser).</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>TRADING NOTES are kept in LOCAL STORAGE</u></i> within your web browser, which is saved PERMANENTLY between browser sessions. Soon user-selected price chart / news feed options will be kept in local storage too (instead of in cookie data).</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>IMPORTED SPREADSHEETS are DELETED AFTER THE IMPORT HAS COMPLETED</u></i> processing your portfolio data, and nothing related to the imported data remains anywhere other than in your web browser afterwards.</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>YOUR PORTFOLIO DATA is NEVER stored in the app</u></i>, it is ONLY stored on your computer in the web browser used to access it (either temporarily in the web browser temporary files cache, or semi-permanently in web browser cookies IF YOU MANUALLY ENABLE COOKIES ON THE SETTINGS PAGE).</p>'
			
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>By default</u></i>, your portfolio data needs to be re-entered to calculate your portfolio value, <i class="bitcoin">every time you close / re-open the app\'s tab in your web browser</i>.</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; "><i class="bitcoin"><u>By default</u></i>, your portfolio data is only saved <i class="bitcoin">temporarily on your computer within your web browser</i> (a default behavior of all modern web browsers), for re-submitting / refreshing / reloading app price data <i class="bitcoin">until you close the app\'s tab in your web browser</i>.</p>'
			
			
			+'<p class="coin_info extra_margins" style="white-space: normal; ">If you prefer to store your portfolio data <i class="bitcoin">semi-permanently on your computer within your web browser as cookie data (to save between browser sessions)</i>, <i class="bitcoin"><u>you must manually enable</u></i> "Use cookies to save data" on the Settings page. </p>'
			
			
			+'<p> </p>';

	
	
			var random_tip_disclaimer = '<h5 class="align_center bitcoin tooltip_title">Random Tips Disclaimer</h5>'
			
			
			+'<p class="coin_info extra_margins bitcoin" style="white-space: normal; ">This "Random Tips" section SHOULD NEVER TAKE THE PLACE OF ADVICE FROM A PROFESSIONAL FINANCIAL ADVISER!</p>'
			
			+'<p class="coin_info extra_margins bitcoin" style="white-space: normal; ">"Random Tips" are only designed to provide VERY BASIC INSIGHT for people new to cryptocurrency, AND DOES NOT / CANNOT TAKE INTO ACCOUNT UNIQUE SITUATIONS INVESTORS MAY BE IN. ALWAYS CONSULT A FINANCIAL ADVISER IF YOU ARE UNAWARE OF ALL RISKS FOR YOUR PARTICULAR SITUATION!</p>'
			
			
			+'<p> </p>';

	
	
			var spreadsheet_import_export = '<h5 class="align_center yellow tooltip_title">Spreadsheet Import / Export</h5>'
			
			
			+'<p class="coin_info" style="white-space: normal; ">You can import / export your portfolio as a CSV spreadsheet saved on your computer, for portfolio backup / editing offline:</p>'
			
			+'<p class="coin_info" style=" white-space: normal;"><img src="templates/interface/media/images/auto-preloaded/csv-spreadsheet-example.png" width="590" title="CSV spreadsheet of portfolio holdings" /></p>'
			
			+'<p class="coin_info" style=" white-space: normal;"><span class="bitcoin">FORMATTING:</span> <br /> The spreadsheet format is custom, and DOES NOT support importing CSV spreadsheets provided by any particular exchange. The only required entries for CSV spreadsheet import are Ticker Key / Holdings / Market Pair. The other fields are optional, and can be left blank if desired:</p>'
			
			+'<p class="coin_info" style=" white-space: normal;"><img src="templates/interface/media/images/auto-preloaded/csv-spreadsheet-example-minimal.png" width="590" title="MINIMUM requirements to import a CSV spreadsheet" /></p>'
			
			+'<p class="coin_info" style=" white-space: normal;"><span class="bitcoin">PRO TIPS:</span> <br /><br /> To easily display different portfolio setups SEPERATELY in this app (long term holdings / short term holdings / etc), save each of your different portfolio setups to seperate spreadsheet backup files. Then import whichever spreadsheet you wish into this app for portfolio tracking.<br /><br /> To have an asset designated as a stock (not crypto / fiat), it\'s "Ticker Key" must have "STOCK" appended to it like: TICKERSTOCK (all one word). Otherwise it won\'t import correctly.</p>'
			
			
			+'<p> </p>';
			
			
			
</script>
<!--  !END! RE-USED INFO BUBBLE DATA  -->
				
				
				
	<p style='margin-top: 10px;'><a style='font-weight: bold; font-size: 25px;' class='red clear_both' href='javascript: show_more("disclaimer");' title='Click to show disclaimer.'>Disclaimer!</a> &nbsp; 👈 </p>
	    
	    
	    
		<div id='disclaimer' style='display: none;' class='align_left clear_both'>
			
	     
						<p class='red' style='font-weight: bold;'>
						
						Assets in the default examples / demo list DO NOT indicate ANY endorsement OR recommendation of these assets (AND removal indicates NO anti-endorsement / anti-recommendation). These crypto-assets <i>are only used as examples for demoing usage of features in this application</i>, <a href='README.txt' target='_blank'>before you install it on your Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian / Windows 10 device or website server, and change the list to your favorite assets</a>. 
						
						<br /><br /><a href='https://www.google.com/search?q=financial+advisors+near+me' target='_blank'>Consult a financial advisor</a> and / or do <i>your own due diligence, to evaluate investment risk / reward</i> of ANY cryptocurrencies, based on THEIR / YOUR OWN determinations before buying. Even AFTER buying ANY cryptocurrency, ALWAYS CONTINUE to do your due diligence, investigating whether you are engaging in trading within acceptable risk levels for your <i>NET</i> worth.
						
						<br /><br /><i><u>*ALWAYS*</u></i> <a href='https://www.google.com/search?q=financial+advisors+near+me' target='_blank'>CONSULT A FINIANCIAL ADVISOR</a>, IF YOU ARE UNAWARE OF WHAT RISKS ARE PRESENT, *AND* YOU ARE INVESTING *SIGNIFICANT* AMOUNTS OF MONEY! 
						
						</p>
	
						<div class='bitcoin' style='padding-top: 8px; font-weight: bold;'>
						
						<i><u>Expanded-upon version of above IMPORTANT disclaimer / advisory</u>:</i> 
						
						<ul>
						
							<li class='bitcoin disclaimer'>
								<i>NEVER</i> invest more than you can afford to lose.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i><u>ALWAYS AVOID</u></i> <a href='https://twitter.com/hashtag/pumpndump?src=hash' target='_blank'>#pumpndump</a> / <a href='https://twitter.com/hashtag/fomo?src=hash' target='_blank'>#fomo</a> / <a href='https://twitter.com/hashtag/shitcoin?src=hash' target='_blank'>#shxtcoin</a> trading.
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>REMEMBER</i>, the <a href="https://www.google.com/search?q=barbell+portfolio+strategy" target="_blank">Barbell Portfolio Strategy</a> works VERY WELL for MANY investors that use it!
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>LITERALLY</i> nearly 99.9% of all tokens (including NFTs) are either scams, garbage, or dead ends.
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>NEVER</i> buy an asset because of somebody's opinion of it (only buy based on <i>YOUR</i> opinion of it).
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS <u>fully research</u></i> your planned investment beforehand (fundamentals are just as important as long term chart TA, <i>and any short term chart TA is pure BS to be ignored</i>).
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS</i> diversify / balance your portfolio with <i>mostly largest AND oldest marketcaps (which are <i>relatively</i> less volatile) / HIGHEST ON-CHAIN ACTIVITY</i> assets, for you <i>and yours safety and sanity</i>.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>Hang on tight</i> until you can't stand fully holding anymore / want to or must make a position exit percentage <i><u>OFFICIAL</u></i>. (YOU HAVEN'T "LOST" <i><u>OR</u></i> "MADE" <i><u>ANYTHING</u></i> UNTIL YOU SELL A PERCENTAGE OF IT!)
							</li>
						
							<li class='bitcoin disclaimer'>
								<i>DOLLAR-COST-AVERAGE (DCA)</i> into investments weekly OR monthly, <i>NEVER GO "ALL-IN"</i> with 100% of your cash / savings at once! You *WILL NOT* be able to handle the stress <i>if it goes down LONG TERM!</i>
							</li>
							
							<li class='bitcoin disclaimer'>
								Leverage trading is <u>EXTREMELY RISKY</u> (and even more so in crypto markets). <i>NEVER</i> put more than ~5% of your total investment worth into ALL your leverage trades COMBINED, or you will <u>RISK LOSING EVERYTHING</u>!
							</li>
							
							<li class='bitcoin disclaimer'>
								The biggest investment you will EVER make (and NEVER regret) is ALWAYS value your health and happiness more than ALL THE MONEY IN THE WORLD. NEVER let chasing after ANY AMOUNT of wealth take that from you, EVER.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>ALWAYS <u>buy low</u> AND <u>sell high</u></i>. (NOT the other way around!), *UNLESS* you CAREFULLY decide you've accidentally bought an asset that will probably go nowhere in value long term, relative to other assets you are interested in.
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>WATCH OUT FOR</i> <a href='https://www.google.com/search?q=pig+butchering+scams' target='_BLANK'>"Pig Butchering"</a> / <a href='https://www.google.com/search?q=catfishing+scams' target='_BLANK'>"Catfishing"</a> scams, and <i>NEVER</i> tell <i>ANYBODY</i> about your investment portfolio details. You would be surprised at how many people will take advantage of others for money. 😮 <i>KEEP THIS INFORMATION PRIVATE</i>!
							</li>
							
							 <li class='bitcoin disclaimer'>
								"Fully research" does NOT include *BLINDLY* believing some CEO / founder / influencer sweet talking their own token, telling you how competing systems suck and their system is better, or explaining how them owning over 50% of the total coin supply is not out of greed.
							</li>
							
							<li class='bitcoin disclaimer'>
								ALWAYS have a future plan in place, of what you will buy / sell: 1) Around a certain future date in time. 2) If a certain price target has been met or exceeded. This doesn't need to be "all in" or "all out". For instance, you may want to split your capital gains between 2 tax years within a tight time period, in late December / early the following January, to avoid higher tax brackets.
							</li>
							
							<li class='bitcoin disclaimer'>
								Speculating on popular "meme coins" (dog / cat / ape / frog coins, etc) is <u>EXTREMELY RISKY</u>. <i>NEVER</i> put more than ~5% of your total investment worth into ALL your meme coin assets COMBINED (AKA small "moon bags"), or you will <u>RISK LOSING EVERYTHING</u>! They are EXTREMELY VOLITILE becuase they are HEAVILY SPECULATED ON (NOT long term investments for many traders).
							</li>
						
							<li class='bitcoin disclaimer'>
								<i><u>ALWAYS AVOID</u></i> copycat coins (that copy / mimick already-popular networks BUT HAVE NO SIGNIFICANT FEATURE IMPROVEMENTS), coins with high inflation rates (creating too many new coins infinitely), coins that are NOT on a fully decentralized network (small groups control everything), and coins with very little on-chain transaction activity (indicating low <i>REAL WORLD</i> user adoption).
							</li>
							
							<li class='bitcoin disclaimer'>
								<i>FOOD FOR THOUGHT:</i> <i>NEARLY <u>ALL</u></i> crypto tokens that are either 'liquid staking', 'wrapped', 'bridged' (*WITHOUT* 'burn-and-mint' bridging security), or are a 'stable coin', ARE ONLY EQUIVELENT TO '<a href='https://www.google.com/search?q=ious+meaning+in+finance' target='_blank'>IOUs</a>', TO SWAP LATER FOR THE *REAL* UNDERLYING ASSET(S). So THINK TWICE before putting more than a relatively small percentage of your NET worth in these types of tokens, as they are higher risk than holding the underlying asset(s) they are pegged to.
							</li>
							
							<li class='bitcoin disclaimer'>
								The "grass on the other side looks greener" MORE OFTEN THAN NOT will always be in your head, when you see other assets performing better than the ones you currently are holding. That's why it's SO IMPORTANT TO DO YOUR REASEARCH FULLY! If you KNOW you didn't buy garbage, AND you didn't over-extend your position (you CAN afford to hold it for a few years without selling any), just RELAX AND DON'T BE GREEDY...otherwise you are living for money (not ENJOYING life itself) AND YOU WILL END UP FLIPPING YOURSELF OUT, WHICH IS A CRAPPY WAY TO LIVE!
							</li>
							
							<li class='bitcoin disclaimer'>
								If you insist on buying LONG SHOT (#VERY# high risk) SMALL marketcap or NEWER assets (requiring #A TON# OF DILIGENCE / PATIENCE), *HIGHLY* consider getting #NO MORE THAN# a 'moon bag' worth (#NO MORE THAN# between 1% and 5% of your portfolio PER-ASSET, AND A TOTAL OF #NO MORE THAN# 10% of your portfolio). If it goes down 50% in value and keeps going down, sell it and you don't lose much. If it goes up between 200% and 500% in value (3x to 6x original value) or higher, REBALANCE it to not be more than between 1% and 10% of your portfolio again (by selling a significant portion of it). CAREFULLY TRACK YOUR SUCCESS RATE. If you are no good at picking long shots, stick to the <i>largest AND oldest marketcaps / HIGHEST ON-CHAIN ACTIVITY</i> assets instead.
							</li>
							
							<li class='bitcoin disclaimer'>
								Best of luck, be careful out there in this cryptoland frontier <i>full of dead-end coins, garbage coins, scam coins, and greedy <u>glorified</u> (and NOT so glorified) crooks</i> and their silver tongues (wolves in sheep's clothing)! 😮
							</li>
						
						</ul>
						
						
						<br /><a href="https://twitter.com/taoteh1221/status/1538567185232273408" target="_blank"><img src='templates/interface/media/images/twitter-1192997965952094208.jpg' width='425' class='image_border' alt='' style='margin-left: 25px;' /></a>
						
						</div>
	
		<br clear='all' />
		
		</div>
		
		
	<p style='margin-top: 20px;'><span style='font-weight: bold;' class='bitcoin'>How is my data stored by this app?</span> 
	     
		<img class='tooltip_style_control' id='portfolio_data_privacy' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative; left: -5px;' /> </p>
		
	 <script>
		
			$('#portfolio_data_privacy').balloon({
			html: true,
			position: "right",
  			classname: 'balloon-tooltips',
			contents: portfolio_data_privacy,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
	
			<b>Random Tip:</b><img class='tooltip_style_control' id='random_tip_disclaimer' src='templates/interface/media/images/info-orange.png' alt='' width='30' style='position: relative; padding: 0px; margin: 0px; vertical-align: middle;' />  <a href='javascript: random_tips();'>Show Another Tip</a>
	
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
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
	
	<p><a style='font-weight: bold;' href='README.txt' target='_blank'>Editing The Portfolio Assets List, and Enabling Email / Text / Telegram / Alexa Price Alerts (installation on Debian / Ubuntu / DietPi OS / RaspberryPi OS / Armbian / Windows 10, or website)</a></p>
	
				
			
	<div class='align_left clear_both' style='margin-top: 40px; margin-bottom: 15px; white-space: nowrap;'>
	
		
		<!-- Submit button must be OUTSIDE form tags here, or it submits the target form improperly and loses data -->
		<button class='force_button_style' onclick='
		$("#coin_amnts").submit();
		'>Save Updated Portfolio</button>
	
		<form style='display: inline;' name='csv_import' id='csv_import' enctype="multipart/form-data" action="<?=$ct['gen']->start_page($_GET['start_page'])?>" method="post">
		
	    <input type="hidden" name="csv_check" value="1" />
	    
	    <span id='file_upload'><input style='margin-left: 85px;' name="csv_file" type="file" /></span>
	    
	    <input type="button" onclick='validate_form("csv_import", "csv_file");' value="Import Portfolio From CSV File" />
	    
		</form>
		
		
		<button style='margin-left: 40px;' class='force_button_style' onclick='
		// HELP THWART CSRF ATTACKS VIA POST METHOD (IN COMBINATION WITH THE TOKEN HASH), DATA IS SENSITIVE!
		set_target_action("coin_amnts", "_blank", "download.php?token=<?=$ct['gen']->nonce_digest('download')?>&csv_export=1");
		document.coin_amnts.submit(); // USE NON-JQUERY METHOD SO "APP LOADING..." DOES #NOT# SHOW
		set_target_action("coin_amnts", "_self", "<?=$ct['gen']->start_page($_GET['start_page'])?>");
		'>Export Portfolio To CSV File</button>
		
		
		<a style='margin-left: 40px; text-decoration: none;' class='force_button_style' href="download.php?token=<?=$ct['gen']->nonce_digest('download')?>&csv_export=1&example_template=1" target="_blank">Example CSV File</a>
	     
		<img class='tooltip_style_control' id='spreadsheet_import_export' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: 5px;' /> 
		
	 <script>
		
			$('#spreadsheet_import_export').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: spreadsheet_import_export,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
		
	<div id='watch_only'>
	
		<div class='align_center' style='font-weight: bold;'>Watch Only</div>
	
		<div style='margin-left: 6px;'><input type='checkbox' onclick='select_all(this, "coin_amnts");' /> <b>Select / Unselect All <i><u>Unheld</u> Assets</i></b>	</div>
		
	
	</div>
	
	
	<br class='clear_both' />	
	
	 <?php
	 if ( $csv_import_fail_alert != NULL ) {
	 ?>
	<br />	
	 <div class='red red_dotted' style='font-weight: bold;'><?=$csv_import_fail_alert?></div>
	<br />	
	<br />	
	 <?php
	 }
	 ?>
	
	
	
	<form id='coin_amnts' name='coin_amnts' action='<?=$ct['gen']->start_page($_GET['start_page'])?>' method='post'>
	
		
	<div class='long_list_start list_start_black'> &nbsp; </div>
	
	<?php
	
	if ( is_array($ct['conf']['assets']) ) {

	        
	//var_dump($ct['conf']['assets']);
	        
	//var_dump($all_cookies_data_array);
	
	    
	    $zebra_stripe = 'long_list_odd';
	    foreach ( $ct['conf']['assets'] as $asset_array_key => $asset_array_val ) {
		
		$rand_id = rand(10000000,100000000);
	    
	    $field_var_pair = strtolower($asset_array_key) . '_pair';
	    $field_var_mrkt = strtolower($asset_array_key) . '_mrkt';
	    $field_var_amnt = strtolower($asset_array_key) . '_amnt';
	    $field_var_paid = strtolower($asset_array_key) . '_paid';
	    $field_var_lvrg = strtolower($asset_array_key) . '_lvrg';
	    $field_var_mrgntyp = strtolower($asset_array_key) . '_mrgntyp';
	    $field_var_watchonly = strtolower($asset_array_key) . '_watchonly';
	    $field_var_restore = strtolower($asset_array_key) . '_restore';
	    
	    
	        if ( $_POST['submit_check'] == 1 ) {
	        $asset_pair_id = $_POST[$field_var_pair];
	        $asset_mrkt_id = $_POST[$field_var_mrkt];
	        $asset_amnt_val = $ct['var']->rem_num_format($_POST[$field_var_amnt]);
	        $asset_paid_val = $ct['var']->rem_num_format($_POST[$field_var_paid]);
	        $asset_lvrg_val = $_POST[$field_var_lvrg];
	        $asset_mrgntyp_val = $_POST[$field_var_mrgntyp];
	        }
	        elseif ( $post_csv_import ) {
	        	
	        
	        	foreach( $csv_file_array as $key => $val ) {
	        		
	        		if ( strtoupper($asset_array_key) == strtoupper($key) ) {
	        			
	        			// We already validated / auto-corrected $csv_file_array
	        		 	$asset_amnt_val = $val[1];
    	       		 	$asset_paid_val = $val[2];
    	       		 	$asset_lvrg_val = $val[3];
	        			$asset_mrgntyp_val = $val[4];
	        			$asset_mrkt_id = $val[5];
	        		 	$asset_pair_id = $val[6];
	        			
	       		 	}
	        	
	        	}
	        		
	        
	        }
	    	   // Cookies
	        elseif ( $ui_cookies ) {
	
        	   foreach ( $all_cookies_data_array as $key => $unused ) {
        	       
        	   $purchase_price_temp = null;
        	       
        	   $asset_symb = substr($key, 0, strpos($key, "_"));
        	        
        	        if ( strtoupper($asset_array_key) == strtoupper($asset_symb) ) {
        	            
        						
                	// Bundle all required cookie data in this final cookies parsing loop for each coin, and render the coin's data
                	// We don't need $ct['var']->rem_num_format() for cookie data, because it was already done creating the cookies
                	$asset_amnt_val_temp = $ct['var']->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_amnt']);
                					
                					
                		if ( $asset_amnt_val_temp >= $watch_only_flag_val ) {
                		    
                    	$asset_amnt_val = $asset_amnt_val_temp;
                    	$asset_mrkt_id = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrkt'];
                    	$asset_pair_id = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_pair'];
                					       
                		$purchase_price_temp = $ct['var']->num_to_str($all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_paid']);
                					   
    			            // If purchased amount (not just watched), AND cost basis
                    		if (
                    		$purchase_price_temp >= $min_fiat_val_test
                    		&& $asset_amnt_val >= $min_crypto_val_test
                    		) {
                			$asset_paid_val = $purchase_price_temp;
                    		$asset_lvrg_val = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_lvrg'];
                    		$asset_mrgntyp_val = $all_cookies_data_array[$asset_symb.'_data'][$asset_symb.'_mrgntyp'];
                    		}
                    		else {
                		     $asset_paid_val = 0;
                    		$asset_lvrg_val = 0;
                    		$asset_mrgntyp_val = 'long';
                    		}
                					   
                		}
                		else {
                		$asset_paid_val = 0;
                		$asset_lvrg_val = 0;
                		$asset_mrgntyp_val = 'long';
                		}
        					
        	        }
        	        
        	   }
	   
	        }
	        
	    
	    	if ( strtoupper($asset_array_key) == 'MISCASSETS' || strtoupper($asset_array_key) == 'BTCNFTS' || strtoupper($asset_array_key) == 'ETHNFTS' || strtoupper($asset_array_key) == 'SOLNFTS' || strtoupper($asset_array_key) == 'ALTNFTS' ) {
	    	$asset_amnt_dec = 2;
	    	$disable_fields = 'disabled';
	    	}
	    	else {
	    	$asset_amnt_dec = 8;
	    	$disable_fields = null;
	    	}
	    
	    
	  	 $asset_amnt_val = $ct['var']->num_pretty($asset_amnt_val, $asset_amnt_dec, TRUE); // TRUE = Show even if low value is off the map, just for UX purposes tracking token price only, etc
	    
	    
    	    // Set any previously-used additional feilds to default, if 'watch only' now (no amount held)
    	    if ( $ct['var']->rem_num_format($asset_amnt_val) < $min_crypto_val_test ) {
    	    $asset_paid_val = 0;
    	    $asset_lvrg_val = 0;
    	    $asset_mrgntyp_val = 'long';
    	    }
    	    // Otherwise, just add auto-dynamic decimal rounding to unit value cost basis
    	    else {
         $thres_dec = $ct['gen']->thres_dec($asset_paid_val, 'u', 'fiat'); // Units mode
         $asset_paid_val = $ct['var']->num_pretty($asset_paid_val, $thres_dec['max_dec'], false, $thres_dec['min_dec']);
    	    }
	    
	    
	    ?>
	    
	    <div class='<?=$zebra_stripe?> long_list_taller' style='white-space: nowrap;'> 
	       
	       
	       <input type='checkbox' value='<?=strtolower($asset_array_key)?>' id='<?=$field_var_watchonly?>' onchange='watch_toggle(this);' <?=( $ct['var']->rem_num_format($asset_amnt_val) > 0 && $ct['var']->rem_num_format($asset_amnt_val) <= $watch_only_flag_val ? 'checked' : '' )?> /> &nbsp;
				    
				    
			<b class='blue'><?=$asset_array_val['name']?> (<?=strtoupper($asset_array_key)?>)</b> /  
	       
	       
				    <select class='browser-default custom-select' onchange='
				    
				    $("#<?=$field_var_mrkt?>_lists").children().hide(); 
				    $("#" + this.value + "<?=$asset_array_key?>_pairs").show(); 
				    
				    $("#<?=$field_var_mrkt?>").val( $("#" + this.value + "<?=$asset_array_key?>_pairs option:selected").val() );
				    
				    $("#prim_currency_mrkt").val( this.value + "|" + $("#<?=$field_var_mrkt?>").val() );
				    
				    ' id='<?=$field_var_pair?>' name='<?=$field_var_pair?>'>
					<?php
					
					// Get default BITCOIN pair key for further down in the logic, if no $asset_pair_id value was set FOR BITCOIN
					if ( strtolower($asset_array_val['name']) == 'bitcoin' ) {
					$sel_pair = ( isset($asset_pair_id) ? $asset_pair_id : $ct['conf']['gen']['bitcoin_primary_currency_pair'] );
					}
					else {
					$sel_pair = $asset_pair_id;
					}
					
					
					foreach ( $asset_array_val['pair'] as $pair_key => $pair_id ) {
					 	
					 	// Set pair key if not set yet (values not yet populated etc)
					 	if ( !isset($sel_pair) ) {
					 	$sel_pair = $pair_key;
					 	}
						
					?>
					<option value='<?=$pair_key?>' <?=( $sel_pair == $pair_key ? ' selected ' : '' )?>> <?=strtoupper(preg_replace("/_/i", " ", $pair_key))?> </option>
					<?php
					
									foreach ( $asset_array_val['pair'][$pair_key] as $mrkt_key => $mrkt_id ) {
									$loop2 = $loop2 + 1;
							
									$html_mrkt_list[$pair_key] .= "\n<option value='".$loop2."'" . ( 
									isset($asset_mrkt_id) && ($asset_mrkt_id) == $loop2 
									|| !isset($asset_mrkt_id) && strtolower($asset_array_val['name']) == 'bitcoin' && $loop2 == $ct['asset']->btc_mrkt($ct['conf']['gen']['bitcoin_primary_currency_exchange']) ? ' selected ' : '' ) . ">" . $ct['gen']->key_to_name($mrkt_key) . " </option>\n";
								
									}
									$loop2 = NULL;
							
							
					}
					?>
				    </select> 
				    
				    
				     @ <input type='hidden' id='<?=$field_var_mrkt?>' name='<?=$field_var_mrkt?>' value='<?php
				     
				     if ( $_POST[$field_var_mrkt] ) {
				     echo $_POST[$field_var_mrkt];
				     }
				     elseif ( isset($asset_mrkt_id) ) {
				     echo $asset_mrkt_id;
				     }
				     elseif ( !isset($asset_mrkt_id) && strtolower($asset_array_val['name']) == 'bitcoin' ) {
				     echo $ct['asset']->btc_mrkt($ct['conf']['gen']['bitcoin_primary_currency_exchange']);
				     }
				     else {
					echo '1';
				     }
				     
				     ?>'>
				     
				     
				     <span id='<?=$field_var_mrkt?>_lists' style='display: inline;'>
				     <!-- Selected (or first if none selected) pair: <?=$sel_pair?> -->
				    <?php
				    
				    foreach ( $html_mrkt_list as $key => $val ) {
				    ?>
				    
				    <select class='browser-default custom-select' onchange ='
				    
				    $("#<?=$field_var_mrkt?>").val( this.value );
				    
				    $("#prim_currency_mrkt").val( $("#<?=$field_var_pair?>").val() + "|" + this.value );
				    
				    ' id='<?=$key.$asset_array_key?>_pairs' style='display: <?=( $sel_pair == $key ? 'inline' : 'none' )?>;'><?=$html_mrkt_list[$key]?>
				    
				    </select>
				    
				    <?php
				    }
				    $html_mrkt_list = NULL;
				    ?>
				    
				    </span> &nbsp;  &nbsp; 
				    
				    
			
	     			 <b>Holdings:</b> <input class='private_data' type='text' size='11' id='<?=$field_var_amnt?>' name='<?=$field_var_amnt?>' value='<?=$asset_amnt_val?>' onkeyup='
	     
	     $("#<?=strtolower($asset_array_key)?>_restore").val( $("#<?=strtolower($asset_array_key)?>_amnt").val() );
	     
	     ' onblur='
	     
	     $("#<?=strtolower($asset_array_key)?>_restore").val( $("#<?=strtolower($asset_array_key)?>_amnt").val() );
	     
	     ' <?=( $ct['var']->rem_num_format($asset_amnt_val) > 0 && $ct['var']->rem_num_format($asset_amnt_val) <= $watch_only_flag_val ? 'readonly' : '' )?> /> <span class='blue'><?=strtoupper($asset_array_key)?></span>  &nbsp;  &nbsp; 
			    
			
	     <b>Average Paid (per-token):</b> <?=$ct['conf']['power']['bitcoin_currency_markets'][ $ct['conf']['gen']['bitcoin_primary_currency_pair'] ]?><input class='private_data' type='text' size='10' id='<?=$field_var_paid?>' name='<?=$field_var_paid?>' value='<?=$asset_paid_val?>' <?=$disable_fields?> /> 
	     
	     
		<img class='tooltip_style_control' id='average_paid_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
	
			$('#average_paid_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: average_paid_notes,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
	     
	     <select class='browser-default custom-select' name='<?=$field_var_lvrg?>' id='<?=$field_var_lvrg?>' onchange='
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
	     <option value='0' <?=( $asset_lvrg_val == 0 || $ct['var']->rem_num_format($asset_paid_val) < $min_fiat_val_test ? 'selected' : '' )?>> None </option>
	     <?php
	     $lvrg_count = 2;
	     while ( $ct['conf']['power']['margin_leverage_maximum'] > 1 && $lvrg_count <= $ct['conf']['power']['margin_leverage_maximum'] ) {
	     ?>	     
	     <option value='<?=$lvrg_count?>' <?=( $asset_lvrg_val == $lvrg_count && $ct['var']->rem_num_format($asset_paid_val) >= $min_fiat_val_test ? 'selected' : '' )?>> <?=$lvrg_count?>x </option>
	     <?php
	     $lvrg_count = $lvrg_count + 1;
	     }
	     ?>
	     </select> 
	     
	     
	     <select class='browser-default custom-select' name='<?=$field_var_mrgntyp?>' id='<?=$field_var_mrgntyp?>' <?=$disable_fields?> >
	     <option value='long' <?=( $asset_mrgntyp_val == 'long' ? 'selected' : '' )?>> Long </option>
	     <option value='short' <?=( $asset_lvrg_val >= 2 && $asset_mrgntyp_val == 'short' ? 'selected' : '' )?>> Short </option>
	     </select> 
	     
	     
		<img class='tooltip_style_control' id='lvrg_trading_notes_<?=$rand_id?>' src='templates/interface/media/images/info.png' alt='' width='30' style='position: relative; left: -5px;' /> 
	 <script>
		
			$('#lvrg_trading_notes_<?=$rand_id?>').balloon({
			html: true,
			position: "left",
  			classname: 'balloon-tooltips',
			contents: lvrg_trading_notes,
			css: {
					fontSize: "<?=$set_font_size?>em",
					minWidth: "350px",
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
		 
	     
	     
	     <input type='hidden' id='<?=$field_var_restore?>' name='<?=$field_var_restore?>' value='<?=( $ct['var']->rem_num_format($asset_amnt_val) > 0 && $ct['var']->rem_num_format($asset_amnt_val) <= $watch_only_flag_val ? '' : $asset_amnt_val )?>' />
				
				
	    </div>
	    
	    
	    <?php
	    
		 	if ( $zebra_stripe == 'long_list_odd' ) {
		 	$zebra_stripe = 'long_list_even';
		 	}
		 	else {
		 	$zebra_stripe = 'long_list_odd';
		 	}
	    
	    $asset_symb = NULL;
	    $asset_pair_id = NULL;
	    $asset_mrkt_id = NULL;
	    $asset_amnt_val = NULL;
 	    $asset_paid_val = NULL;
	    
	    }
	    
	    
	}
	?>
	
	<div class='long_list_end list_end_black'> &nbsp; </div>
	
	
	<input type='hidden' id='submit_check' name='submit_check' value='1' />
	
	<input type='hidden' id='theme_selected' name='theme_selected' value='<?=$ct['sel_opt']['theme_selected']?>' />
	
	<input type='hidden' id='sort_by' name='sort_by' value='<?=($ct['sel_opt']['sorted_by_col'])?>|<?=($ct['sel_opt']['sorted_asc_desc'])?>' />
	
	<input type='hidden' id='use_cookies' name='use_cookies' value='<?php echo ( isset($_COOKIE['coin_amnts']) ? '1' : ''); ?>' />
	
	<input type='hidden' id='use_alert_percent' name='use_alert_percent' value='<?=( isset($_POST['use_alert_percent']) ? $_POST['use_alert_percent'] : $_COOKIE['alert_percent'] )?>' />
	
	<input type='hidden' id='show_charts' name='show_charts' value='<?=( isset($_POST['show_charts']) ? $_POST['show_charts'] : $_COOKIE['show_charts'] )?>' />
	
	<input type='hidden' id='show_crypto_val' name='show_crypto_val' value='<?=( isset($_POST['show_crypto_val']) ? $_POST['show_crypto_val'] : $_COOKIE['show_crypto_val'] )?>' />
	
	<input type='hidden' id='show_secondary_trade_val' name='show_secondary_trade_val' value='<?=( isset($_POST['show_secondary_trade_val']) ? $_POST['show_secondary_trade_val'] : $_COOKIE['show_secondary_trade_val'] )?>' />
	
	<input type='hidden' id='show_feeds' name='show_feeds' value='<?=( isset($_POST['show_feeds']) ? $_POST['show_feeds'] : $_COOKIE['show_feeds'] )?>' />
	
	<input type='hidden' id='prim_currency_mrkt_standalone' name='prim_currency_mrkt_standalone' value='<?=( isset($_POST['prim_currency_mrkt_standalone']) ? $_POST['prim_currency_mrkt_standalone'] : $_COOKIE['prim_currency_mrkt_standalone'] )?>' />
	
	<input type='hidden' id='prim_currency_mrkt' name='prim_currency_mrkt' value='<?=( isset($_POST['prim_currency_mrkt']) ? $_POST['prim_currency_mrkt'] : $_COOKIE['prim_currency_mrkt'] )?>' />
			
	<p><input type='submit' value='Save Updated Portfolio' /></p>
	
	</form>
	
	
			    
			    
</div> <!-- full_width_wrapper END -->



