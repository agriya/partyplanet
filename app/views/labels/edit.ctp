<?php /* SVN: $Id: $ */ ?>
<?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => 'sec')));?>
<div class="labels form">
<?php echo $this->Form->create('Label', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Labels'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Label');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update'));?>
</div>