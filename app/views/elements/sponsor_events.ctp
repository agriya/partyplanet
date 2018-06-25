<?php
if(!empty($type)){
	$type=$type;
}else{
	$type='sponsor';
}?>
<div class = "js-response">
<?php echo $this->requestAction(array('controller'=>'events','action'=>'index','type'=>$type), array('return')); ?>
</div>