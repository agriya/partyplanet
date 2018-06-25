<?php /* SVN: $Id: $ */ ?>
<div class="eventSponsors form">
    <?php echo $this->Form->create('EventSponsor', array('class' => 'normal','enctype' => 'multipart/form-data'));?>
	<legend><?php echo $this->Html->link(__l('Event Sponsors'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Event Sponsor');?></legend>
<?		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('description');
        echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Sponsor Photo', true)));
		echo $this->Form->input('is_active', array('label' => 'Active?'));
        ?>
		<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Update')); ?>
        </div>
        <?php echo $this->Form->end(); ?>
 
</div>