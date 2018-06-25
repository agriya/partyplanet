<?php
if($this->request->params['action']=='home'):
echo $this->requestAction(array('controller' => 'videos', 'action' => 'index', 'index_simple', 'most' => 'viewed', 'limit' => 3), array('return'));
elseif($this->request->params['action']=='view'):
echo $this->requestAction(array('controller' => 'videos', 'action' => 'index', 'index_simple', 'most' => 'viewed', 'limit' => 3,'tweet'=>'no_tweet'), array('return'));
endif;
?>