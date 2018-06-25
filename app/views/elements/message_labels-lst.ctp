<?php
	$foldertype = isset($folder_type) ? $folder_type : '';
	$label_slug = isset($label_slug) ? $label_slug : '';
	echo $this->requestAction(array('controller' => 'labels', 'action' => 'index', 'view_type' => 'compact', $label_slug), array('return'));
?>