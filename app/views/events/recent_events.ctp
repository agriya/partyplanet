<h2> <?php echo $event_title;?></h2>
<ol class="list feature-list">
<?php
if (!empty($events)):
?>

<?php
$i = 0;
foreach ($events as $event):
	$class = null;
	if ($i++ % 2 == 0) :
		$class = ' class="altrow"';
	endif;
?>
		<li class="clearfix <?php echo $class; ?>">
            	<div class="grid_4 omega alpha">
            		<?php
            		echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'micro_normal_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title']),'escape' => false), null, array('inline' => false));
                    ?>
                </div>
    			<div class="grid_12 omega alpha">
    				<h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?>
    	               	</h3>
                    <?php if(isset($event_title) and $event_title == 'Today\'s Recession Busters'): ?>
                    	<p>
                            <?php echo __l('By').': '. $this->Html->link($this->Html->cText($event['User']['username']), array('controller'=> 'users', 'action' => 'view',$event['User']['username']), array('title'=>$event['User']['username'],'escape' => false));?>
                		</p>
        		      <?php endif; ?>
    	  			    <p><?php echo __l('From').': '.$this->Html->cDateTime($event['Event']['start_date']);?></p>
          			    <p><?php echo __l('To').': '. $this->Html->cDateTime($event['Event']['end_date']);?></p>
    	           		<p>
                			<span><?php echo __l('Venue').': '.$this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view',$event['Venue']['slug']), array('title'=>$this->Html->cText($event['Venue']['name']),'escape' => false)); ?>
                			<?php echo $this->Html->cText($event['Venue']['City']['name'].$event['Venue']['Country']['name']);?><span>
                		</p>
        		</div>
       
       



	</li>
<?php
    endforeach;
else:
?>
	<li class="clearfix">
		<p class="notice"><?php echo __l('No Recent Events available');?></p>
	</li>
<?php
endif;
?>
</ol>


