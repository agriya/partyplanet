<?php
	echo $this->requestAction(array('controller' => 'photo_albums', 'action' => 'index','type' => 'last_night','sort_by'=>'date','album_view'=>'hoime_newest'), array('return'));
?>