<?php
echo $this->requestAction(array('controller'=>'user_friends', 'action'=>'index', $status, $type), array('return', 'key' => $this->Auth->user('id')));
?>