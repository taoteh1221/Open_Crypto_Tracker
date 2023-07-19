
    <p class='align_center'><a title='Click to show / hide donation addresses.' href='javascript: show_more("donate");'>Donations Support Development</a></p>
    
            	<div style='display: none;' id='donate' class='align_center'>
            	
            	<span class='bitcoin' style='font-weight: bold;'>Open Crypto Tracker <i>WILL ALWAYS REMAIN 100% FREE / OPEN SOURCE SOFTWARE</i> (<a href='https://en.wikipedia.org/wiki/Free_and_open-source_software' target='_blank'>FOSS</a>),<br />so please consider donating any amount <i>large or small</i> to help support time spent on this project...</span>
            	
            	<br /><br /><b>Bitcoin:</b> <br /><span class='underline_pointer' id='btc_donate' title='Click to show / hide address copying details.'>3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW</span>
            	
            	<br /><br /><b>Ethereum:</b> <br /><span class='underline_pointer' id='eth_donate' title='Click to show / hide address copying details.'>0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8</span>
            	
            	<br /><br /><b>Solana:</b> <br /><span class='underline_pointer' id='sol_donate' title='Click to show / hide address copying details.'>GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU</span>
            	
            	<br /><br /><b>Github:</b> <br /><a href='https://github.com/sponsors/taoteh1221' target='_blank'>https://github.com/sponsors/taoteh1221</a>
            	
            	<br /><br /><b>PayPal:</b> <br /><a href='https://www.paypal.me/dragonfrugal' target='_blank'>https://www.paypal.me/dragonfrugal</a>
            	
            	<br /><br /><b>Patreon:</b> <br /><a href='https://www.patreon.com/dragonfrugal' target='_blank'>https://www.patreon.com/dragonfrugal</a>
            	
	 <script>
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var btc_donate_content = '<h5 class="align_center yellow tooltip_title">Bitcoin (BTC) Donation Address</h5>'
			
			+'<p id="copy_btc_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_btc_address\', \'copy_btc_address_alert\')">3Nw6cvSgnLEFmQ1V4e8RSBG23G7pDjF3hW</p>'
			
			+'<p id="copy_btc_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/btc-donations.png" width="200" title="Bitcoin (BTC) Donation Address" /></p>'
			
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
			css: {
					fontSize: "<?=$default_font_size?>em",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "999",
					textAlign: "left"
					}
					
			 	});
			 	
            btc_shown = !btc_shown;
            
			
			
			// Open / close via target element
          }).hideBalloon();
	
	
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var eth_donate_content = '<h5 class="align_center yellow tooltip_title">Ethereum (ETH) Donation Address</h5>'
			
			+'<p id="copy_eth_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_eth_address\', \'copy_eth_address_alert\')">0x644343e8D0A4cF33eee3E54fE5d5B8BFD0285EF8</p>'
			
			+'<p id="copy_eth_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/eth-donations.png" width="200" title="Ethereum (ETH) Donation Address" /></p>'
			
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
			css: {
					fontSize: "<?=$default_font_size?>em",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "999",
					textAlign: "left"
					}
					
			 	});
			 	
            eth_shown = !eth_shown;
            
          }).hideBalloon();
	
	
			
			 // Info ballon only opens / closes when clicked (for a different UX on certain elements)
	
			var sol_donate_content = '<h5 class="align_center yellow tooltip_title">Solana (SOL) Donation Address</h5>'
			
			+'<p id="copy_sol_address" class="coin_info align_center pointer" style="white-space: nowrap;" onclick="copy_text(\'copy_sol_address\', \'copy_sol_address_alert\')">GvX4AU4V9atTBof9dT9oBnLPmPiz3mhoXBdqcxyRuQnU</p>'
			
			+'<p id="copy_sol_address_alert" class="coin_info align_center bitcoin">(click address above, to copy to clipboard)</p>'
			
			+'<p class="coin_info align_center"><b>QR Code For Phones:</b></p>'
			
			+'<p class="coin_info align_center"><img src="templates/interface/media/images/auto-preloaded/sol-donations.png" width="200" title="Solana (SOL) Donation Address" /></p>'
			
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
			css: {
					fontSize: "<?=$default_font_size?>em",
					minWidth: "450px",
					padding: ".3rem .7rem",
					border: "2px solid rgba(212, 212, 212, .4)",
					borderRadius: "6px",
					boxShadow: "3px 3px 6px #555",
					color: "#eee",
					backgroundColor: "#111",
					opacity: "0.99",
					zIndex: "999",
					textAlign: "left"
					}
					
			 	});
			 	
            sol_shown = !sol_shown;
            
          }).hideBalloon();
	
	
	
	
		 </script>
		 
            	
            	<br /><br />
            	
            	</div>