<?php
/*
 * Copyright 2014-2020 GPLv3, DFD Cryptocoin Values by Mike Kilday: http://DragonFrugal.com
 */


?>


<div class='full_width_wrapper'>
	
				<h3 class='align_center'>Logs</h3>
				
	
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Error Logs</strong> </legend>
	        
	        <p>
	        
	        <button class='force_button_style' onclick="system_logs('errors_log');">Refresh</button> 
	        
	        &nbsp; <b>Maximum lines shown:</b> <input type='text' id='errors_log_lines' value='100' maxlength="4" size="5" />
	        
	        &nbsp; <span id='errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <pre><code class='hide-x-scroll bash rounded' style='width: 100%; height: 750px;' id='errors_log'></code></pre>
			  
			  <script>
			  system_logs('errors_log');
			  </script>
		
	    </fieldset>
				
	<?php
	if ( is_readable($base_dir . '/cache/logs/smtp_errors.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>SMTP Error Logs</strong> </legend>
	        
	        <p>
	        
	        <button class='force_button_style' onclick="system_logs('smtp_errors_log');">Refresh</button> 
	        
	        &nbsp; <b>Maximum lines shown:</b> <input type='text' id='smtp_errors_log_lines' value='100' maxlength="4" size="5" />
	        
	        &nbsp; <span id='smtp_errors_log_alert' class='red'></span>
	        
	        </p>
	        
	        <pre><code class='hide-x-scroll bash rounded' style='width: 100%; height: 750px;' id='smtp_errors_log'></code></pre>
			  
			  <script>
			  system_logs('smtp_errors_log');
			  </script>
		
	    </fieldset>
	<?php
	}
	if ( $app_config['developer']['debug_mode'] != 'off' || is_readable($base_dir . '/cache/logs/debugging.log') ) {
	?>
	    <fieldset class='subsection_fieldset'><legend class='subsection_legend'> <strong>Debugging Logs</strong> </legend>
	        
	        <p>
	        
	        <button class='force_button_style' onclick="system_logs('debugging_log');">Refresh</button> 
	        
	        &nbsp; <b>Maximum lines shown:</b> <input type='text' id='debugging_log_lines' value='100' maxlength="4" size="5" />
	        
	        &nbsp; <span id='debugging_log_alert' class='red'></span>
	        
	        </p>
	        
	        <pre><code class='hide-x-scroll bash rounded' style='width: 100%; height: 750px;' id='debugging_log'></code></pre>
			  
			  <script>
			  system_logs('debugging_log');
			  </script>
		
	    </fieldset>
	<?php
	}
	?>
	    
			    
			    
</div> <!-- full_width_wrapper END -->




		    