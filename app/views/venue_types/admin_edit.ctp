<?php /* SVN: $Id: $ */ ?>
<div class="venueTypes form">
<?php echo $this->Form->create('VenueType', array('class' => 'normal'));?>
<legend class="crumb"><?php echo $this->Html->link(__l('Venue Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Venue Type');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	?>
		<div class="submit-block clearfix">
     <?php echo $this->Form->submit(__l('Update'));?>
       </div>
 <?php echo $this->Form->end(); ?>
</div>
