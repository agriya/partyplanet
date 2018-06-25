<?php
	if (!empty($type) and !empty($view)) {
		echo $this->requestAction(array('controller' => 'articles', 'action' => 'index', 'type' => $type, 'view' => $view, 'admin' => false, 'limit' => 5), array('return'));
	} elseif(!empty($type)) {
		echo $this->requestAction(array('controller' => 'articles', 'action' => 'index', 'type' => $type, 'admin' => false, 'limit' => 5), array('return'));
	} else {
		echo $this->requestAction(array('controller' => 'articles', 'action' => 'index', 'lst', 'category' => $category, 'admin' => false), array('return'));
	}
?>