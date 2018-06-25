<?php /* SVN: $Id: $ */ ?>
<div class="parkingTypes form form-content-block">
<?php echo $this->Form->create('ParkingType', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Parking Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Parking Type');?></legend>
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
