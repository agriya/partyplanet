<?php
	echo $this->requestAction(array('controller' => 'photo_albums', 'action' => 'index', 'venue_id' => $venue_id, 'type' => 'list'), array('return'));
?>