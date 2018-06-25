<?php
	echo $this->requestAction(array('controller' => 'events', 'action' => 'index', 'type' => $type,'limit'=>'3'), array('return'));
?>