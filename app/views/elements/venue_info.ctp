<?php
	echo $this->requestAction(array('controller' => 'events', 'action' => 'view', $event, 'type' => 'venue'), array('return'));
?>