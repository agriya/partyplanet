<?php
	echo $this->requestAction(array('controller' => 'photo_albums', 'action' => 'index','type' => 'latest','sort_by'=>'date'), array('return'));
?>