<?php 
	echo $this->requestAction(array('controller'=>'venues', 'action'=>'search_keyword', 'keyword'=> $keyword), array('return')); 
?>