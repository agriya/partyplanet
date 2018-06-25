<?php /* SVN: $Id: $ */ ?>
<div class="venueSponsors form">
<?php echo $this->Form->create('VenueSponsor', array('class' => 'normal','enctype' => 'multipart/form-data'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Venue Sponsors'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Venue Sponsor');?></legend>

	<?php
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('email');
		echo $this->Form->input('phone');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
		echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Sponsor Photo', true)));
	?>
	</fieldset>
		<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Add'));?>
        </div>
        <?php echo $this->Form->end(); ?>
</div>
