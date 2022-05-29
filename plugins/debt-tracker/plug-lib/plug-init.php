<?php
/*
 * Copyright 2014-2022 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


?>



<script src="plugins/debt-tracker/plug-assets/jquery.repeatable.js"></script>


    	<div class="container">

    		<div class="page-header">
		    	<h5 class='bitcoin'>Credit / Loan Accounts To Track Monthly and Yearly Interest On...</h5>
		    </div>

			<form class="form-horizontal">

				<fieldset class="accounts_labels">

					<p><input type="button" value="Add An Additional Credit / Loan Account" class="btn btn-default add" align="center"></p>

					<div class="repeatable"></div>
                    
                    <br clear='all' />
                    <br clear='all' />
                    
                    <p><input type='submit' value='Calculate Monthly / Yearly Interest Totals' /></p>

				</fieldset>

			</form>

		</div>

		<script type="text/template" id="accounts_labels">
      <div class="field-group row">
  			<div class="col-lg-6">
  			<label class='blue' for="account_{?}">Account Name</label>
  			<input type="text" class="span6 form-control" name="accounts_labels[{?}][account]" value="{account}" id="account_{?}">
  			</div>
  			<div class="col-lg-2">
  			<label class='blue' for="amount_{?}">Debt Amount</label>
  			<input type="text" class="span2 form-control" name="accounts_labels[{?}][amount]" value="{amount}" id="amount_{?}">
			</div>
  			<div class="col-lg-2">
  			<label class='blue' for="apr_{?}">APR</label>
  			<input type="text" class="span2 form-control" name="accounts_labels[{?}][apr]" value="{apr}" id="apr_{?}">
			</div>
			<div class="col-lg-2">
			<label for="">&nbsp;</label><br>
  			<input type="button" class="btn btn-danger span-2 delete" value="Remove" />
			</div>
  		</div>
		</script>

		<script>
		$(document).ready(function(){ 
			$(".accounts_labels .repeatable").repeatable({
				addTrigger: ".accounts_labels .add",
				deleteTrigger: ".accounts_labels .delete",
				template: "#accounts_labels",
				min: 1,
				max: 15
			});
		});
		</script>

<?php


// DEBUGGING ONLY (checking logging capability)
//$ct_cache->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>