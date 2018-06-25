<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userFriends lst">
<div id="breadcrumb">
		<?php
			if($this->Auth->user('user_type_id') != ConstUserTypes::Admin):
				echo $this->Html->addCrumb(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false));
				echo $this->Html->addCrumb(__l('My Friends'));
				echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
			endif;
		?>
	</div>

    <ul class="menu-tabs clearfix">
		<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
		<li><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
		<li class="active"><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
    	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
	</ul>

	<div class="main-content-block friends-block js-corner round-5">
		<h2><?php echo __l('Friends');?></h2>
		<div class="js-tabs clearfix review-tabs-block">
			<ul class="clearfix menu-tabs">
				<li><?php echo $this->Html->link(__l('My Friends'), '#tabs-1'); ?></li>
				<li><?php echo $this->Html->link(__l('Friends Requests'), '#tabs-2'); ?></li>
				<li><?php echo $this->Html->link(__l('Search for new Friends'), array('controller' => 'users', 'action'=>'index'), array('title' => __l('Search for new Friends')));?></li>
			</ul>
			<div class="js-response regular-link">
			</div>
			<span class="clear">&nbsp;</span>
			<div id="tabs-1">
				<?php echo $this->element('user_friends-myfriends', array('status' => ConstUserFriendStatus::Approved, 'user_id' => $this->Auth->user('id'), 'cache' => array('key' => $this->Auth->user('id'), 'config' => 'sec'))); ?>
			</div>
			<div id="tabs-2">
				<div class="js-tabs clearfix">
					<ul class="clearfix friends-request-list">
						<li><?php echo $this->Html->link(__l('Received Friends Requests'), '#received-friends'); ?></li>
						<li><?php echo $this->Html->link(__l('Sent Friends Requests'), '#request-friends'); ?></li>
					</ul>
					<div class="clearfix" id="received-friends">
						<div class="js-responses js-received-approve-friends">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Approved, 'type' => 'received', array('cache' => Configure::read('cache.time.friends_list'))));
							?>
						</div>
						<div class="js-responses">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Pending, 'type' => 'received'), array('cache' => Configure::read('cache.time.friends_list')));
							?>
						</div>
						<div class="js-responses js-received-reject-friends">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Rejected, 'type' => 'received', array('cache' =>		Configure::read('cache.time.friends_list'))));
							?>
						</div>
					</div>
					<div class="clearfix" id="request-friends">
						<div class="js-responses">
							<?php
							echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Approved, 'type' => 'sent', array('cache' => Configure::read('cache.time.friends_list'))));
							?>
						</div>
						<div class="js-responses">
							<?php
							echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Pending, 'type' => 'sent', array('cache' => Configure::read('cache.time.friends_list'))));
							?>
						</div>
						<div class="js-responses js-received-send-friends">
							<?php
								echo $this->element('user_friends-index', array('status' => ConstUserFriendStatus::Rejected, 'type' => 'sent', array('cache' => Configure::read('cache.time.friends_list'))));
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>