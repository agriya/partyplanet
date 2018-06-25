<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userFriends user-friends-block index">
<h3><?php echo $this->pageTitle;?></h3>
<?php echo $this->element('paging_counter');?>
<ol class="user-list friends-list clearfix" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($userFriends)) {
foreach ($userFriends as $userFriend) {
?>
	<li id="friend-<?php echo $userFriend['UserFriend']['id']; ?>" class="list-row clearfix ">
<?php
		if ($type == 'received') {
?>

		<?php	echo $this->Html->getUserAvatar($userFriend['User'], 'normalhigh_thumb');?>

        	<p class="meta-row author">
		        <cite><span title="<?php echo $userFriend['User']['username'];?>"><?php echo $this->Html->link($this->Html->cText($userFriend['User']['username']), array('controller'=> 'users', 'action' => 'view', $userFriend['User']['username']), array('escape' => false));?></span></cite>
		    </p>

<?php
	if ($status == ConstUserFriendStatus::Approved) {
		echo $this->Html->link(__l('Reject'), array('action'=>'reject', $userFriend['User']['username']), array('class' => 'reject js-friend js-friend-delete {container:"js-received-reject-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Pending) {
		echo $this->Html->link(__l('Accept'), array('action'=>'accept', $userFriend['User']['username'], 'received'), array('class' => 'accept js-friend js-friend-delete {container:"js-received-approve-friends"}', 'title' => __l('Accept')));
		echo $this->Html->link(__l('Reject'), array('action'=>'reject', $userFriend['User']['username'], 'received'), array('class' => 'reject js-friend js-friend-delete {container:"js-received-reject-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Rejected) {
		echo $this->Html->link(__l('Remove'), array('action'=>'remove', $userFriend['User']['username'], 'received'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
?>

	
<?php
		}
		else {
?>

		  <?php
			echo $this->Html->getUserAvatar($userFriend['FriendUser']['id'], 'normalhigh_thumb');
			//echo $this->Html->link($this->Html->showImage('UserAvatar', $userFriend['FriendUser']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($userFriend['FriendUser']['username'], false)), 'title' => $this->Html->cText($userFriend['FriendUser']['username'], false))), array('controller' => 'users', 'action' => 'view', $userFriend['FriendUser']['username']), array('escape' => false));?>

        	<p class="meta-row friends-list-author">
		        <cite><span title="<?php echo $userFriend['FriendUser']['username'];?>"><?php echo $this->Html->link($this->Html->cText($userFriend['FriendUser']['username']), array('controller'=> 'users', 'action' => 'view', $userFriend['FriendUser']['username']), array('escape' => false));?></span></cite>
		    </p>
	<?php
	if ($status == ConstUserFriendStatus::Approved) {
		echo $this->Html->link(__l('Reject'), array('action'=>'reject', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'reject js-friend js-friend-delete {container:"js-received-send-friends"}', 'title' => __l('Reject')));
	}
	if ($status == ConstUserFriendStatus::Pending) {
		echo $this->Html->link(__l('Remove'), array('action'=>'remove', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
	if ($status == ConstUserFriendStatus::Rejected) {
		echo $this->Html->link(__l('Remove'), array('action'=>'remove', $userFriend['FriendUser']['username'], 'sent'), array('class' => 'remove js-friend js-friend-delete {container:"js-remove-friends"}', 'title' => __l('Remove')));
	}
?>


<?php
		}
?>
	</li>
<?php
    }
  }
else {
?>
<li class="no-record">
		<p class="notice">
			<?php
			if ($status == ConstUserFriendStatus::Approved) {
				echo __l('No approved friends available');
			}
			else if ($status == ConstUserFriendStatus::Rejected) {
				echo __l('No rejected friends available');
			}
			else if ($status == ConstUserFriendStatus::Pending) {
				echo __l('No pending friends available');
			}
			?>
		</p>
	</li>
<?php
 	}
?>
</ol>

<?php
if (!empty($userFriends)) {
    echo $this->element('paging_links');
}
?>
</div>