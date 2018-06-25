<?php
	echo $this->requestAction(array('controller' => 'events', 'action' => 'index', 'type' => 'user', 'list' => $list), array('return'));
?>