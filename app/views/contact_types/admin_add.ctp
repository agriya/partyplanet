<?php /* SVN: $Id: $ */ ?>
<div class="contactTypes form">
<div class="form-content-block">
<?php echo $this->Form->create('ContactType', array('class' => 'normal'));?>
	<fieldset>
	<legend class="crumb"><?php echo $this->Html->link(__l('Contact Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Contact Type');?></legend>
 	<?php
		echo $this->Form->input('name');
			echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
</div>