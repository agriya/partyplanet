<?php /* SVN: $Id: $ */ ?>
<div class="maritalStatuses form">
<?php echo $this->Form->create('MaritalStatus', array('class' => 'normal'));?>
<legend class="crumb"><?php echo $this->Html->link(__l('Marital Statuses'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Marital Status');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
		?>
			<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update'));?>
		</div>
		 <?php echo $this->Form->end(); ?>
</div>
