<?php /* SVN: $Id: admin_edit.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<h2><?php echo sprintf(__l('Edit %s Settings'), $paymentGateway['PaymentGateway']['name']);?></h2>
<div id="breadcrumb">
   <?php $this->Html->addCrumb('Payment Gateways', array('controller' => 'payment_gateways','action' => 'index')); ?>
  <?php $this->Html->addCrumb(__l('Payment Gateway Setting Update')); ?>
  <?php echo $this->Html->getCrumbs(' &raquo; '); ?>
</div>
<?php echo $this->Html->link(__l('Add'), array('controller'=> 'payment_gateway_settings', 'action' => 'add', $paymentGateway['PaymentGateway']['id']), array('class' => 'add'));?>
<?php
if (!empty($paymentGatewaySettings)) {
	echo $this->Form->create('PaymentGatewaySetting', array('action' => 'update', 'class' => 'normal'));
	foreach ($paymentGatewaySettings as $paymentGatewaySetting):
		$name = "PaymentGatewaySetting.{$paymentGatewaySetting['PaymentGatewaySetting']['id']}.key";
		$options = array(
			'type' => $paymentGatewaySetting['PaymentGatewaySetting']['type'],
			'value' => $paymentGatewaySetting['PaymentGatewaySetting']['value'],
			'div' => array('id' => "PaymentGatewaySetting-{$paymentGatewaySetting['PaymentGatewaySetting']['key']}")
		);
		if (!empty($paymentGatewaySetting['PaymentGatewaySetting']['description'])):
			$options['after'] = "<p class=\"setting-desc\">{$paymentGatewaySetting['PaymentGatewaySetting']['description']}</p>";
		endif;
		$options['label'] = Inflector::humanize($paymentGatewaySetting['PaymentGatewaySetting']['key']);
		echo $this->Form->input($name, $options);
	endforeach;
	echo $this->Form->input('payment_gateway_id', array('type' => 'hidden', 'value' => $paymentGatewaySetting['PaymentGatewaySetting']['payment_gateway_id']));
	echo $this->Form->end('Update');
}else{
?>
	<div class="notice"><?php echo __l('Sorry no settings added.');?></div>
<?php
}
?>