<?php
	echo $this->requestAction(array('controller' => 'users', 'action' => 'index', 'type' => 'home', 'limit' => '8', 'admin' => false), array('return'));
?>