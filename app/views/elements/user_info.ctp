<?php
	$view = !empty($view) ? $view : '';
	echo $this->requestAction(array('controller' => 'users', 'action' => 'view', $username, 'type' => 'user_info', 'view' => $view), array('return'));
?>