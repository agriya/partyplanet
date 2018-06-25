<?php
$this->request->params['named']['event_view']=$event_view;
 $url = array_merge(array(
        'controller' => $this->request->params['controller'],
        'action' => $this->request->params['action'],
    ) , $this->request->params['pass'], $this->request->params['named']);

echo $this->requestAction($url, array('return'));
?>