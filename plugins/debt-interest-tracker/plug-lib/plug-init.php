<?php
/*
 * Copyright 2014-2025 GPLv3, Open Crypto Tracker by Mike Kilday: Mike@DragonFrugal.com (leave this copyright / attribution intact in ALL forks / copies!)
 */


// ###########################################################################################
// SEE /DOCUMENTATION-ETC/PLUGINS-README.txt FOR CREATING YOUR OWN CUSTOM PLUGINS
// ###########################################################################################


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:start');


$debt_form_action = $ct['gen']->start_page($plug['conf'][$this_plug]['ui_location']); // Make the page it's on the start page (for results UX)
?>

<link rel="stylesheet" href="<?=$ct['plug']->plug_dir(true)?>/plug-assets/style.css" type="text/css" />
	

    <div class="container">


    		<div class="page-header">
		    	<h5 class='blue'>Credit / Loan Accounts To Track Monthly and Yearly Interest On</h5>
		    </div>


			<form method='post' action='<?=$debt_form_action?>' class="form-horizontal">


				<fieldset class="accounts_labels">


					<p><input type="button" value="Add Credit / Loan Account" class="btn btn-default add" align="center"></p>

                     
					<div class="repeatable">
					
					
                        <?php
                        
                        // If we have post data from submission, repopulate the original submission data (for UX),
                        // AND calculate the interest / save summary to a results array (for output BELOW the form data)
                        if ( is_array($_POST['accounts_labels']) ) {
                        
                        $all_debt = array();
                        
                        
                            $loop=0;
                            foreach ( $_POST['accounts_labels'] as $key => $val ) {
                                
                            // Filter vars
                            $val['account'] = trim($val['account']);
                            $val['amount'] = $ct['var']->strip_formatting($val['amount']);
                            $val['apr'] = $ct['var']->strip_formatting($val['apr']);
                            
                                
                                if ( $val['account'] != '' && is_numeric($val['amount']) && is_numeric($val['apr']) ) {
                                
                                // Get results for this debt account
                                $all_debt[$key] = $plug['class'][$this_plug]->apr_calc($val['account'], $val['amount'], $val['apr']);
                                
                                ?>
                            
                            
                                <div class="field-group row">
                            
                          			<div class="extra_margins col-lg-6">
                          			<label class='blue' for="account_<?=$key?>">Account Name</label>
                          			<input data-track-index='<?=$key?>' type="text" class="span6 form-control" name="accounts_labels[<?=$key?>][account]" value="<?=$val['account']?>" id="account_<?=$key?>">
                          			</div>
                          			
                          			<div class="extra_margins col-lg-2">
                          			<label class='blue' for="amount_<?=$key?>">Debt Amount <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?></label>
                          			<input data-track-index='<?=$key?>' type="text" class="span2 form-control" name="accounts_labels[<?=$key?>][amount]" value="<?=number_format($val['amount'], 2, '.', ',')?>" id="amount_<?=$key?>">
                        			</div>
                    			
                          			<div class="extra_margins col-lg-2">
                          			<label class='blue' for="apr_<?=$key?>">APR %</label>
                          			<input data-track-index='<?=$key?>' type="text" class="span2 form-control" name="accounts_labels[<?=$key?>][apr]" value="<?=$val['apr']?>" id="apr_<?=$key?>">
                        			</div>
                    			
                            		<div class="extra_margins col-lg-2">
                            		<br>
                              		<input data-track-index='<?=$key?>' type="button" class="btn btn-danger span-2 delete" value="Remove" />
                            		</div>
                            		
                    			</div>
                      		
                      		
                                <?php
                                
                                $loop = $loop + 1;
                                }
                                
                            
                            }
                            $loop=null;
                        
                        }
                        ?>
                    
					
					</div>
					
                    
                    <br clear='all' />
                    <br clear='all' />

                    
                    <p><input type='submit' value='Calculate Monthly / Yearly Interest Totals' /></p>


				</fieldset>


			</form>


    <?php

    // Results output
    
    // If post submission results
    if ( is_array($_POST['accounts_labels']) ) { 
    ?>
        
        
        <p style='font-size: 20px !important;' class='bitcoin align_center'>
        Results Summary / Totals...
        </p>
        
        
        <?php
        $debt_yearly_interest_total = 0;

        foreach ( $all_debt as $debt_account ) {
        $debt_yearly_interest_total = $debt_yearly_interest_total + $debt_account['yearly_interest'];
        echo $debt_account['summary'];
        }
        
        $debt_monthly_interest_total = round( ($debt_yearly_interest_total / 12) , 2);

        ?>

        
        <p class='debt_results_total'>
        Total Monthly Interest: <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . number_format($debt_monthly_interest_total, 2, '.', ',')?><br />
        Total Yearly Interest: <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ] . number_format($debt_yearly_interest_total, 2, '.', ',')?>
        </p>
        

    <?php
    }
    ?>
        
        
	</div>
		
		
	<!-- Scripting to run the form manipulations -->
	

	<script type="text/template" id="accounts_labels">
	
      <div class="field-group row">
  			<div class="extra_margins col-lg-6">
  			<label class='blue' for="account_{?}">Account Name</label>
  			<input data-track-index='{?}' type="text" class="span6 form-control" name="accounts_labels[{?}][account]" value="{account}" id="account_{?}">
  			</div>
  			<div class="extra_margins col-lg-2">
  			<label class='blue' for="amount_{?}">Debt Amount <?=$ct['opt_conf']['conversion_currency_symbols'][ $ct['conf']['currency']['bitcoin_primary_currency_pair'] ]?></label>
  			<input data-track-index='{?}' type="text" class="span2 form-control" name="accounts_labels[{?}][amount]" value="{amount}" id="amount_{?}">
			</div>
  			<div class="extra_margins col-lg-2">
  			<label class='blue' for="apr_{?}">APR %</label>
  			<input data-track-index='{?}' type="text" class="span2 form-control" name="accounts_labels[{?}][apr]" value="{apr}" id="apr_{?}">
			</div>
			<div class="extra_margins col-lg-2">
			<br>
  			<input data-track-index='{?}' type="button" class="btn btn-danger span-2 delete" value="Remove" />
			</div>
  		</div>
  		
	</script>


	<script>
	
		$(document).ready(function(){ 
			$(".accounts_labels .repeatable").repeatable({
          		prefix: 'new',
				addTrigger: ".accounts_labels .add",
				deleteTrigger: ".accounts_labels .delete",
				template: "#accounts_labels",
				itemContainer: ".field-group",
				min: 1,
				max: 99
			});
		});
		
	</script>
		

<?php


// DEBUGGING ONLY (checking logging capability)
//$ct['cache']->check_log('plugins/' . $this_plug . '/plug-lib/plug-init.php:end');


// DON'T LEAVE ANY WHITESPACE AFTER THE CLOSING PHP TAG!

?>