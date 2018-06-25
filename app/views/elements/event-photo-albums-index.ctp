<?php
	echo $this->requestAction(array('controller' => 'photo_albums', 'action' => 'index', 'event_id' => $event_id, 'type' => 'list'), array('return'));
?>