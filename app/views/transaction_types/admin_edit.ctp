<?php /* SVN: $Id: admin_edit.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="transactionTypes form">
<?php echo $this->Form->create('TransactionType', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Transaction Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Transaction Type');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('message');
		echo $this->Form->input('transaction_variables');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update'));?>
</div>
