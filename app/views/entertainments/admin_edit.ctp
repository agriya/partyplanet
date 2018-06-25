<?php /* SVN: $Id: $ */ ?>
<div class="entertainments form">
<div class="form-content-block">
<?php echo $this->Form->create('Entertainment', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Entertainments'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Entertainment');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
		echo $this->Form->input('PartyPlanner',array('div' =>'input select multiple-select'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>