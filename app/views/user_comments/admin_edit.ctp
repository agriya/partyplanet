<?php /* SVN: $Id: $ */ ?>
<div class="userComments form">
<?php echo $this->Form->create('UserComment', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('User Comments'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit User Comment');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('comment');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active')));
		echo $this->Form->input('ip');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>

</div>
