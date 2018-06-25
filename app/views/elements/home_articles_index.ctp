<?php
	echo $this->requestAction(array('controller' => 'articles', 'action' => 'index', $type, 'admin' => false), array('return'));
?>