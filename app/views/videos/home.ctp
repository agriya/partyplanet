<?php 
	echo $this->element('video-index', array('type' => 'recent', 'cache' => array('config' => 'sec')));
	echo $this->element('video-index', array('type' => 'site', 'cache' => array('config' => 'sec')));
	echo $this->element('recent-videos', array('cache' => array('config' => 'sec')));
?>