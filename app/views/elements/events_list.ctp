<?php 
	echo $this->requestAction(array('controller' => 'events', 'action' => 'index', 'venue_id' => $venue_id, 'type' => $type, 'limit' => 3), array('return'));
?>