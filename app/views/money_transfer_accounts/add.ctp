<?php if(!empty($paymentGateways)): ?>
<div class="moneyTransferAccounts form add js-responses">
	<?php echo $this->Form->create('MoneyTransferAccount', array('action' => 'add', 'class' =>  "normal js-ajax-form {'container':'js-responses'}"));?>
		<fieldset  class="form-block">						
			</h3>
			<?php														
				echo $this->Form->input('payment_gateway_id');
				echo $this->Form->input('account',array('label' => __l('Account')));							
			?>                        																			
		</fieldset>
	  <div class="submit-block clearfix">
		<?php
			echo $this->Form->submit(__l('Add'));
		?>
		</div>
	<?php
		echo $this->Form->end();
	?>		
</div>
<?php endif; ?>