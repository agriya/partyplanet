<?php /* SVN: $Id: $ */ ?>
<div class="userComments form">
<div class="crumb-block">
	<?php echo $this->Html->addCrumb($username, array('controller' => 'users', 'action' => 'view', $username)); ?>
	<?php echo $this->Html->addCrumb(__l('Edit Comment')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="form-content-block">
<?php echo $this->Form->create('UserComment', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) { 
			echo $this->Form->input('user_id');
			echo $this->Form->input('comment_user_id', array('type' => 'hidden'));
		} else {
			echo $this->Form->input('user_id', array('type' => 'hidden'));
			echo $this->Form->input('comment_user_id', array('type' => 'hidden'));
		}
		echo $this->Form->input('comment');
		if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) { 
			echo $this->Form->input('is_active',array('label'=>__l('Active')));
			echo $this->Form->input('ip');
		}
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>