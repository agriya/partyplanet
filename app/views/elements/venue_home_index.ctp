<?php
if(empty($type)){
$type = "home";
}
echo $this->requestAction(array('controller'=>'venues','action'=>'index','type'=>$type), array('return'));
?>