	<div id="breadcrumb">
		<?php
			if($this->Auth->user('user_type_id') != ConstUserTypes::Admin):
			 echo $this->Html->addCrumb($this->pageTitle);
			 echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
			endif;
		?>
	</div>
<ul class="menu-tabs clearfix">
	<li class="active"><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
</ul>
<div class="form-content-block dashboard-content-block  phots-view-block">
    <h2><?php echo __l('Dashboard');?></h2>
    <h3><?php echo __l('My Events');?></h3>
    <div class="add-block1">
        <?php echo $this->Html->link(__l('Add Event'), array('controller' => 'events', 'action' => 'add', 'admin' => false), array('title' => __l('Add Event'), 'class' => 'add', 'escape' => false));?>
    </div>
    <?php echo $this->element('user_events', array('list' => 'home', 'cache' => array('config' => '2sec', 'key' => $this->Auth->user('id'))));?>
	<h3><?php echo __l('Booked Events');?></h3>
	<?php echo $this->element('booked_events', array('cache' => array('config' => '2sec', 'key' => $this->Auth->user('id'))));?>
    <?php if ($this->Auth->user('user_type_id') != ConstUserTypes::User):?>
    <h3><?php echo __l('My Venues');?></h3>
    <div class="add-block1">
    <?php echo $this->Html->link(__l('Add Venue'), array('controller' => 'venues', 'action' => 'add', 'admin' => false), array('title' => __l('Add Venue'), 'class' => 'add', 'escape' => false));?>
    </div>
    <?php echo $this->element('user_venues', array('list' => 'home', 'cache' => array('config' => '2sec', 'key' => $this->Auth->user('id'))));?>
    <?php endif;?>
</div>