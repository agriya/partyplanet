<div class="side2-content">
<h3 class="title"><?php echo __l('Latest Events');?></h3>
<div class="latest-block">
<ul class="latest-list">
<?php
if(!empty($events)):
foreach ($events as $event):
?>
<li>
<?php
    		echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'micro_normal_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false));
            ?>
 </li>
 <?php
			endforeach;
			endif;
			?>
</ul>
</div>
</div>