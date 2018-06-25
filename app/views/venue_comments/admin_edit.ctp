<?php /* SVN: $Id: $ */ ?>
<div class="venueComments form">
<div class="form-content-block">
<?php echo $this->Form->create('VenueComment', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Venue Comments'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Venue Comment');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('comment');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
