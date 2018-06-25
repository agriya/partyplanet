<?php /* SVN: $Id: $ */ ?>
<div class="eventCategories form">
<div class="form-content-block">
<?php echo $this->Form->create('EventCategory', array('class' => 'normal'));?>
	<fieldset>
	<legend><?php echo $this->Html->link(__l('Event Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Event Category');?></legend>
	<?php
		echo $this->Form->input('name', array('label' => __l('Name')));
		echo $this->Form->input('description', array('label' => __l('Description')));
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
</div>