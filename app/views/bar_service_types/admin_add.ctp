<?php /* SVN: $Id: $ */ ?>
<div class="barServiceTypes form">
	<div class="form-content-block">
	<?php echo $this->Form->create('BarServiceType', array('class' => 'normal'));?>
		<fieldset>
		<legend><?php echo $this->Html->link(__l('BarService Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Bar Service Type');?></legend>
		<?php
			echo $this->Form->input('name');
			echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
		?>
		</fieldset>
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Add'));?>
		</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>