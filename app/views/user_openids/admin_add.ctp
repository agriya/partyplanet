<?php /* SVN: $Id: $ */ ?>
<div class="userOpenids form form-content-block">
<?php echo $this->Form->create('UserOpenid', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Openids'), array('action' => 'index'));?> &raquo; <?php echo __l('Add User Openid');?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('openid');
	?>
	</fieldset>
		<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Add'));?>
</div>
</div>
