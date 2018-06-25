<?php /* SVN: $Id: $ */ ?>
<div class="ageRequirments form">
<?php echo $this->Form->create('AgeRequirment', array('class' => 'normal'));?>
	<fieldset>
	<legend><?php echo $this->Html->link(__l('Age Requirments'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Age Requirment');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
    <div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>

</div>
