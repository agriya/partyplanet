<?php
    echo $this->requestAction(array('controller' => 'events', 'action' => 'index', 'event_slug' => $event, 'type' => 'samevenue', 'limit' => 3), array('return'));
?>