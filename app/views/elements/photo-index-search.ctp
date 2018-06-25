<?php
$$type = !empty($type)? $type :'';
echo $this->requestAction(array('controller'=>'photos','action'=>'index','type'=>$type,'keyword'=>$keyword), array('return')); ?>