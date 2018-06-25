<?php /* SVN: $Id: $ */ ?>
<div class="labelsUsers form">
<?php echo $this->Form->create('LabelsUser', array('class' => 'admin-form'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Labels Users'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Labels User');?></legend>
	<?php
		echo $this->Form->input('label_id');
		echo $this->Form->input('user_id');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>
