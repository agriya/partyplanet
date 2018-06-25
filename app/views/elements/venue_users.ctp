<?php
	echo $this->requestAction(array('controller' => 'venue_users', 'action' => 'index', 'venue' => $venue, 'type' => $type, 'limit' => '3'), array('return'));
?>