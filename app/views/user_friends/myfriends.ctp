<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="userFriends index">
<ol class="user-list friends-list  clearfix " start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($userFriends)) {
foreach ($userFriends as $userFriend) {
?>
	<li id="friend-<?php echo $userFriend['UserFriend']['id']; ?>" class="friend">
		<?php 
			echo $this->Html->getUserAvatar($userFriend['FriendUser'],'normalhigh_thumb');
			//echo $this->Html->link($this->Html->showImage('UserAvatar', $userFriend['FriendUser']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($userFriend['FriendUser']['username'], false)), 'title' => $this->Html->cText($userFriend['FriendUser']['username'], false))), array('controller' => 'users', 'action' => 'view', $userFriend['FriendUser']['username']), array('escape' => false));?>
        <p>
    	<?php echo $this->Html->link($this->Html->cText($this->Html->truncate($userFriend['FriendUser']['username'], 14)), array('controller' => 'users', 'action' => 'view', $userFriend['FriendUser']['username']), array('escape' => false)); ?>
        </p>
    </li>
<?php
    } ?>

 <?php  }

else {
?>
<li class="no-record">
		<p class="notice"><?php echo __l('No friends available'); ?></p>
</li>

<?php
 	}
?>
</ol>
<?php
if (!empty($userFriends) and $total_friends > 12) {
    echo $this->element('paging_links');
}
?>
</div>