<?php /* SVN: $Id: $ */ ?>
<div class="bodyTypes form">
<div class="form-content-block">
<?php echo $this->Form->create('BodyType', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Body Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Body Type');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
