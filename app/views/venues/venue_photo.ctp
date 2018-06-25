<div class="side2-content">
<h3 class="title"><?php echo __l('Latest Venues');?></h3>
<div class="latest-block">
 <ul class="latest-list">
<?php
if(!empty($venues)):
foreach ($venues as $venue):
?>
<li>
<?php
    		echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'micro_normal_thumb','title'=>$this->Html->cText($venue['Venue']['name'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)))), array('controller' => 'venue', 'action' => 'view',   $venue['Venue']['slug'],'admin'=>false), array('title'=>$this->Html->cText($venue['Venue']['name'], false),'escape' => false), null, array('inline' => false));
            ?>
 </li>
 <?php
			endforeach;
			endif;
			?>
</ul>
</div>
</div>
