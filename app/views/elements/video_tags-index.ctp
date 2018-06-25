<?php
	$video_slug = !empty($video_slug) ? $video_slug : '';
	echo $this->requestAction(array('controller' => 'video_tags', 'action' => 'index', 'video_slug' => $video_slug), array('return'));
?>