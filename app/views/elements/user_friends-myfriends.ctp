<?php
	echo $this->requestAction(array('controller'=>'user_friends','action'=>'myfriends', $user_id, $status), array('return')); 
?>