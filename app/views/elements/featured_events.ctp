<?php
	$view = !empty($view)?$view:'';
	echo $this->requestAction(array('controller' => 'events', 'action' => 'index', 'type' => 'featured', 'view' => $view), array('return'));
?>