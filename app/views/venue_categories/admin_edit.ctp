<?php /* SVN: $Id: $ */ ?>
<div class="venueCategories form">
<?php echo $this->Form->create('VenueCategory', array('class' => 'normal'));?>
<legend class="crumb"><?php echo $this->Html->link(__l('Venue Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Venue Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('label' => 'Active?'));
	?>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Update'));?>
    </div>
     <?php echo $this->Form->end(); ?>

</div>