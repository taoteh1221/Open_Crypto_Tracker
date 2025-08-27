
    
            	<div style='display: none; margin: 3em; margin-top: 1em;' id='donate' class='align_center'>
    
               <h4>Please show your appreciation for my crypto apps, IF you enjoy using them. Leaving a review on the <a href="https://sourceforge.net/u/taoteh1221/profile/" target="_BLANK">SourceForge project pages</a>, or buying me a coffee / beer means WAY MORE to me than large donations. It's about letting me know you appreciate them / find them useful, NOT about me making money. Think of it as a PRIVATE app usage survey anon! :) All crypto addresses are bot-monitored (for balance changes) on active / well-secured / backed-up HD wallets...  </h4>
            	
            
            	<div style='padding: 15px; min-width: 100px;'>
            	
            	NOTES FOR BLACKHATS: 
            	
            	P̵̫̊h̴̪̑ì̶̯s̵̫̀h̸̠̆i̶̔͜n̸̞͒g̶̳̏ ̸̺͐a̴͎̓n̷̜̕d̴̻͆ ̵̙̕b̶͓͝ř̵̗u̵̼̔t̷͖͐e̴̢͑ ̵̦͊f̸̱̃ö̶̦́r̷͉͌c̴̍͜ê̸͈ ̶̬̍l̴̙̾ō̸̗g̵̫̿ḯ̴̱ṅ̸̖ ̴̘́/̴̣̅ ̸̳̈d̶̡̃ẹ̶̈c̸̲͂r̶̰̋ỹ̵̨p̶̥͂t̷͍̎i̶̮̕o̸̝̎n̸̟͑ ̴͎͑/̶̹̑ ̶͉̎O̵̦̿T̵̜̄H̶̗̓Ę̶͗R̸̪̋ ̸̦̾ȃ̷̰t̸̪̂ṯ̸̐ä̶͈́c̸̫̈k̶͈̍s̴̳̀ ̸͇̎w̸̢͝i̶͕̍l̵̦͗l̵̗̽ ̷̱̀Ň̴͍Ō̶͓T̶̙́ ̸̺̆w̶̖̓o̸̠͝r̴̪̃k̵̞͠ ̴̪̎o̴͎̽n̸͎͘ ̸̗͘m̴̖͗ẽ̸̠,̴̨̆ ̴͙̇G̵̬̿Ĭ̴͍V̶͉̇E̸̳̐ ̴̯̾U̸̺͂P̶̩̀ ̴̨͌A̵͋͜L̴̤̎R̷̖͘Ē̸͕Â̸͍D̸̨̒Ÿ̶͜!̶͖͛
            	
            	
            	</div>
            	
            	<span class='bitcoin' style='font-weight: bold;'>Open Crypto Tracker <i>WILL ALWAYS REMAIN 100% FREE / OPEN SOURCE SOFTWARE</i> (<a href='https://en.wikipedia.org/wiki/Free_and_open-source_software' target='_blank'>FOSS</a>),<br />so please let me know you appreciate my time spent, and buy me a coffee or beer! :)</span>
            	
            	<br /><br /><b class='btc'>Bitcoin:</b> <br /><span class='underline_pointer' id='btc_donate' title='Click to show / hide address copying details.'>3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW</span>
            	
            	<br /><br /><b class='eth'>Ethereum:</b> <br /><span class='underline_pointer' id='eth_donate' title='Click to show / hide address copying details.'>0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8</span>
            	
            	<br /><br /><b class='sol'>Solana:</b> <br /><span class='underline_pointer' id='sol_donate' title='Click to show / hide address copying details.'>GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU</span>
            	
            	<br /><br /><b class='blue'>Github:</b> <br /><a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a>
            	
            	<br /><br /><b class='blue'>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            	
            	<br /><br /><b class='blue'>Patreon:</b> <br /><a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a>
            	
            	<br /><br /><b class='blue'>Venmo:</b> <br /><a href='https://account.venmo.com/u/taoteh1221' target='_blank'>https://account.venmo.com/u/taoteh1221</a>
            	
            	<br /><br /><b class='blue'>Cash App:</b> <br /><a href='https://cash.app/$taoteh1221' target='_blank'>https://cash.app/$taoteh1221</a>
            	
	 <script>
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var btc_donate_content = '<h5 class="align_center yellow tooltip_title">Bitcoin (BTC) Donation Address</h5>'
			
			+'<p id="copy_btc_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_btc_address\', \'copy_btc_address_alert\')">3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW</p>'
			
			+'<p id="copy_btc_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/btc-donations.png" width="400" title="Bitcoin (BTC) Donation Address" /></p>'
			
			+'<p> </p>';
			
			
			// If the target of the click doesn't have the 'leave_open' class (clicking elsewhere on page)
			$(document).click(function(e) {
				
				if ( btc_shown ) {
			
    		 	var btc_container = $("#btc_donate");
    		 	
    		 	// Add 'leave_open' class to parent / all child elements reursively
    		 	add_css_class_recursively( $(".btc_click_to_open") , 'btc_leave_open');

    		 		if ( !btc_container.is(e.target) && btc_container.has(e.target).length === 0 && $(e.target).hasClass('btc_leave_open') == false ) {
        	 		btc_container.hideBalloon();
        	 		btc_shown = false;
    		 		}
    		 		
        	 	}
    		 	
			});
			
			
			// Open / close via target element
			 var btc_shown = false;
			 
          $("#btc_donate").on("click", function(e) {
          	
            btc_shown ? $(this).hideBalloon() : $(this).showBalloon({
            	
			html: true,
			position: "top",
  			classname: 'btc_click_to_open',
			contents: btc_donate_content,
			css: balloon_css("left", "999")
					
			 	});
			 	
            btc_shown = !btc_shown;
            
			
			
			// Open / close via target element
          }).hideBalloon();
	
	
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var eth_donate_content = '<h5 class="align_center yellow tooltip_title">Ethereum (ETH) Donation Address</h5>'
			
			+'<p id="copy_eth_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_eth_address\', \'copy_eth_address_alert\')">0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8</p>'
			
			+'<p id="copy_eth_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/eth-donations.png" width="400" title="Ethereum (ETH) Donation Address" /></p>'
			
			+'<p> </p>';
			
			
			// If the target of the click doesn't have the 'leave_open' class (clicking elsewhere on page)
			$(document).click(function(e) {
				
				if ( eth_shown ) {
			
    		 	var eth_container = $("#eth_donate");
    		 	
    		 	// Add 'leave_open' class to parent / all child elements reursively
    		 	add_css_class_recursively( $(".eth_click_to_open") , 'eth_leave_open');

    		 		if ( !eth_container.is(e.target) && eth_container.has(e.target).length === 0 && $(e.target).hasClass('eth_leave_open') == false ) {
        	 		eth_container.hideBalloon();
        	 		eth_shown = false;
    		 		}
    		 		
        	 	}
    		 	
			});
			
			
			// Open / close via target element
			 var eth_shown = false;
			 
          $("#eth_donate").on("click", function(e) {
          	
            eth_shown ? $(this).hideBalloon() : $(this).showBalloon({
            	
			html: true,
			position: "top",
  			classname: 'eth_click_to_open',
			contents: eth_donate_content,
			css: balloon_css("left", "999")
					
			 	});
			 	
            eth_shown = !eth_shown;
            
          }).hideBalloon();
	
	
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var sol_donate_content = '<h5 class="align_center yellow tooltip_title">Solana (SOL) Donation Address</h5>'
			
			+'<p id="copy_sol_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_sol_address\', \'copy_sol_address_alert\')">GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU</p>'
			
			+'<p id="copy_sol_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/sol-donations.png" width="400" title="Solana (SOL) Donation Address" /></p>'
			
			+'<p> </p>';
			
			
			// If the target of the click doesn't have the 'leave_open' class (clicking elsewhere on page)
			$(document).click(function(e) {
				
				if ( sol_shown ) {
			
    		 	var sol_container = $("#sol_donate");
    		 	
    		 	// Add 'leave_open' class to parent / all child elements reursively
    		 	add_css_class_recursively( $(".sol_click_to_open") , 'sol_leave_open');

    		 		if ( !sol_container.is(e.target) && sol_container.has(e.target).length === 0 && $(e.target).hasClass('sol_leave_open') == false ) {
        	 		sol_container.hideBalloon();
        	 		sol_shown = false;
    		 		}
    		 		
        	 	}
    		 	
			});
			
			
			// Open / close via target element
			 var sol_shown = false;
			 
          $("#sol_donate").on("click", function(e) {
          	
            sol_shown ? $(this).hideBalloon() : $(this).showBalloon({
            	
			html: true,
			position: "top",
  			classname: 'sol_click_to_open',
			contents: sol_donate_content,
			css: balloon_css("left", "999")
					
			 	});
			 	
            sol_shown = !sol_shown;
            
          }).hideBalloon();
	
	
	
	
		 </script>
		 
            	
            	<br /><br />
            	
            	</div>