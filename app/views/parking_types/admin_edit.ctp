<?php /* SVN: $Id: $ */ ?>
<div class="parkingTypes form form-content-block">
<?php echo $this->Form->create('ParkingType', array('class' => 'normal'));?>
<legend><?php echo $this->Html->link(__l('Parking Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Parking Type');?></legend>
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
