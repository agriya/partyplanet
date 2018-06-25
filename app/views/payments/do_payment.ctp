<?php /* SVN: $Id: do_payment.ctp 15317 2011-12-23 09:27:38Z jayashree_028ac09 $ */ ?>
<div class="payments do-payment form-content-block">
	<h2><?php echo sprintf(__l('Payment Process'));?></h2>
	<div class="wallet-amount-block">
		<?php echo __l('Amount: ') . Configure::read('site.currency') ?><?php echo $amount; ?>
	</div>
	<?php if($payment_gateway_id == ConstPaymentGateways::MoneyBooker) { ?>
		<h3><?php echo __l('You have selected Moneybookers as your payment gateway...'); ?></h3>
		<?php echo $gateway_options; ?>
	<?php } else if($payment_gateway_id == ConstPaymentGateways::PayPal) { ?>
		<h3><?php echo __l('You are being redirected to the payment page...'); ?></h3>
		<div class="progress"></div>
		<div class="hide">
			<?php echo $this->Gateway->paypal($gateway_options); ?>
		</div>
	<?php 
	}
	?>
</div>