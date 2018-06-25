<?php /* SVN: $Id: $ */ ?>
<div class="ethnicities form">
<div class="form-content-block">
<?php echo $this->Form->create('Ethnicity', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Ethnicities'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Ethnicity');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Update'));?>
    </div>
     <?php echo $this->Form->end(); ?>
</div>
</div>
