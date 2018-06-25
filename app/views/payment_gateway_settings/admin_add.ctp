<?php /* SVN: $Id: admin_add.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="paymentGatewaySettings form">
<h2><?php echo __l('Add Payment Gateway Settings');?></h2>
<div id="breadcrumb">
   <?php $this->Html->addCrumb('Payment Gateways', array('controller' => 'payment_gateways','action' => 'index')); ?>
  <?php $this->Html->addCrumb(__l('Add Payment Gateway Setting')); ?>
  <?php echo $this->Html->getCrumbs(' &raquo; '); ?>
</div>
<?php echo $this->Form->create('PaymentGatewaySetting', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('payment_gateway_id');
		echo $this->Form->input('key');
		echo $this->Form->input('type', array('type' => 'select', 'options' => array('text' => 'text', 'textarea' => 'textarea', 'checkbox' => 'checkbox', 'radio' => 'radio', 'password' => 'password')));
		echo $this->Form->input('value');
		echo $this->Form->input('description');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>