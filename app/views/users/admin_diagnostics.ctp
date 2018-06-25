<div class="js-response">
<div class="info-details"><?php echo __l('Diagnostics are for developer purpose only.'); ?></div>
	<ul class="setting-links   clearfix">		
			<li class="grid_12 omega alpha">
    			<div class="setting-details-info setting-details-info1 debug-error">
                    <h3><?php echo $this->Html->link(__l('Debug & Error Log'), array('controller' => 'devs', 'action' => 'logs'),array('title' => __l('Debug & Error Log'))); ?></h3>
                    <div><?php echo __l('View debug, error log, used cache memory and used log memory'); ?></div>
                </div>
            </li>
			<li class="grid_12 omega alpha">
				<div class="setting-details-info setting-details-info1 payment-transaction-log">
					<h3><?php echo $this->Html->link(__l('Adaptive Payment Transaction Log'), array('controller' => 'adaptive_transaction_logs', 'action' => 'index'), array('title' => __l('Adaptive Payment Transaction Log'))); ?></h3>
					<p><?php echo __l('View the transaction details done via PayPal Adaptive Payment'); ?></p>
				</div>
			</li>
			<li class="grid_12 omega alpha">
				<div class="setting-details-info setting-details-info1 payment-transaction-log">
					<h3><?php echo $this->Html->link(__l('Adaptive IPN Log'), array('controller' => 'adaptive_ipn_logs', 'action' => 'index'), array('title' => __l('Adaptive IPN Transaction Log'))); ?></h3>
					<p><?php echo __l('View the ipn logs done via PayPal Adaptive Payment'); ?></p>
				</div>
			</li>
			<li class="grid_12 omega alpha">
			<div class="setting-details-info setting-details-info1 mass-payment">
				<h3><?php echo $this->Html->link(__l('Diagnose PayPal'), array('controller' => 'payment_gateways', 'action' => 'paypal_diagnose'),array('title' => __l('Adaptive IPN Log'))); ?></h3>
			<?php echo __l('View configuraion of Paypal'); ?>
			</div>
    </ul>
</div>