<?php /* SVN: $Id: $ */ ?>
<div class="eventSponsors form">
<div id="breadcrumb" class="crumb">
<legend><?php echo $this->Html->link(__l('Event Sponsors'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Event Sponsor');?></legend>
<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="form-content-block">
<?php echo $this->Form->create('EventSponsor', array('class' => 'normal', 'enctype' => 'multipart/form-data'));?>
	<fieldset>
 	
	<?php
	if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
			echo $this->Form->input('user_id', array( 'label' => __l('Users')));
		endif;
		echo $this->Form->input('name');
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
</div>