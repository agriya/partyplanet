<?php
	echo $this->requestAction(array('controller' => 'venues', 'action' => 'index', 'type' => 'near', 'venue_id' => $venue_id), array('return'));
?>