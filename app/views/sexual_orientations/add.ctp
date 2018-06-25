<?php /* SVN: $Id: $ */ ?>
<div class="sexualOrientations form form-content-block">
<?php echo $this->Form->create('SexualOrientation', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Sexual Orientations'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Sexual Orientation');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Add'));?>
</div>
</div>
