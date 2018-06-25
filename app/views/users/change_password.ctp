<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
    <ul class="menu-tabs password-menu clearfix">
    	<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
    	<li><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
    	<li class="active"><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
    	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
    	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'contacts', 'action' => 'invite', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
    </ul>
<?php } ?>
<?php echo $this->element('user_account'); ?>
<div class="form-content-block">
	<?php if (empty($this->request->params['prefix'])): ?>
		<div id="breadcrumb" class="crumb">
	     <?php echo $this->Html->addCrumb(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false));
			 echo $this->Html->addCrumb(__l('Edit Profile'));
			 echo $this->Html->addCrumb(__l('Change password')); ?>
			<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
		</div>
	<?php endif; ?>
	<?php
		echo $this->Form->create('User', array('action' => 'change_password' ,'class' => 'normal'));
		echo $this->Form->input('old_password', array('type' => 'password','label' => __l('Old password') ,'id' => 'old-password'));
		echo $this->Form->input('passwd', array('type' => 'password','label' => __l('New password') , 'id' => 'new-password'));
		echo $this->Form->input('confirm_password', array('type' => 'password', 'label' => __l('Confirm Password')));
	?>
	<div class="submit-block clearfix">
		<?php echo $this->Form->end(__l('Change password')); ?>
	</div>
</div>