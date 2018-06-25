<?php
	$url = Router::url(array('controller' => 'cities', 'action' => 'lst'), true);
	echo $this->Form->input('state_id', array('class' => 'js-dropdown {"url":"'.$url.'", "container":"js-city"}', 'options' => $states, 'empty' => __l('Please Select'), 'type' => 'select'));
?>