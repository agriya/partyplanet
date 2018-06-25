<div class="messages contacts">
<div class="main-content-block js-corner round-5">
<h2><?php echo __l('Contacts'); ?></h2>
<div class="form-blocks js-corner round-5">
<?php echo $this->Form->create('UserFriend', array('controller' => 'user_friends' , 'action' => 'contacts' , 'class' => 'normal'));?>
<?php echo $this->Form->input('search'); ?>
<?php echo $this->Form->submit(__l('Search'));?>
<?php echo $this->Form->end();?>
</div>
<div class=" message-list">
<ol class="list">
<?php if (!empty($contacts)) { ?>
<?php
$i = 0;
$class = null;
if ($i++ % 2 == 0) {
	$class = ' class="altrow"';
}
foreach($contacts as $contact) { ?>
<li class="list-row clearfix">
	<div class="avatar"><?php 
		echo $this->Html->getUserAvatar($contact['FriendUser'], 'medium_thumb');?>

	<div class="data"><?php echo $this->Html->link($this->Html->cText($contact['FriendUser']['username'], false), array('controller' => 'users', 'action' => 'view', $contact['FriendUser']['username'])); ?></div>
</li>
<?php }
} else { ?>
    <li class="notice"><p class="notice"><?php echo __l('No user contacts available.');?></p></li>
<?php } ?>
</ol>
</div>
</div>
</div>

