<?php
	echo $this->requestAction(array('controller'=>'photos','action'=>'index','type'=>'favorite','favorite'=>$username), array('return'));
?>