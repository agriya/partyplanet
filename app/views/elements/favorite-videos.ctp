<?php
	echo $this->requestAction(array('controller'=>'videos','action'=>'index','favorite'=>$username), array('return'));
?>