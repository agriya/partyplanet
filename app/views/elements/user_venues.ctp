<?php
	echo $this->requestAction(array('controller' => 'venues', 'action' => 'index', 'type' => 'user', 'list' => $list), array('return'));
?>