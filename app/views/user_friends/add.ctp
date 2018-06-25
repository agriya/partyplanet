<?php /* SVN: $Id: $ */ ?>
<div class="userFriends">
<?php
	echo $this->Html->link(__l('Remove Friend'), array('controller' => 'user_friends', 'action' => 'remove', $username), array('class' => 'delete','title' => __l('Remove Friend')));
?>
</div>
