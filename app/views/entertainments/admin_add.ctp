<?php /* SVN: $Id: $ */ ?>
<div class="entertainments form">
<div class="form-content-block">
<?php echo $this->Form->create('Entertainment', array('class' => 'normal'));?>
	<fieldset>
	<legend class="crumb"><?php echo $this->Html->link(__l('Entertainments'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Entertainment');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
		echo $this->Form->input('PartyPlanner',array('div' =>'input select multiple-select'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>
