<?php /* SVN: $Id: $ */ ?>
<div class="venueTypes form">
<?php echo $this->Form->create('VenueType', array('class' => 'normal'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Venue Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Venue Type');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
		<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Add'));?>
        </div>
        <?php echo $this->Form->end(); ?>
</div>
