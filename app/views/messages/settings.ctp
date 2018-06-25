<?php /* SVN: $Id: $ */ ?>
<ul class="menu-tabs clearfix">
	<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
	<li class="active"><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'contacts', 'action' => 'invite', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
</ul>
<?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => 'sec'))); ?>
	<h2><?php echo __l('General Settings'); ?> </h2>
		<?php
			echo $this->Form->create('Message', array('action' => 'settings', 'class' => 'normal clearfix  js-form-settings'));
			echo $this->Form->input('UserProfile.message_page_size', array('label'=>__l('Message Page Size'),'info' => __l('Show conversations per page')));
			echo $this->Form->input('UserProfile.message_signature', array('type' => 'textarea', 'label'=>__l('Message Signature')));
        ?>
        <div class="submit-block">
        <?php
        	echo $this->Form->submit(__l('Update'));
        ?>
        </div>
        <?php
        	echo $this->Form->end();
		?>
