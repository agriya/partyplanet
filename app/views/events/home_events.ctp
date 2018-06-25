<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
	<?php
		if (!empty($events)):
			$j = 0;	?>
	    <ol class="list photo-list clearfix">
			 <?php
				foreach ($events as $event):
				    $class = null;
					if ($j++ % 2 == 0) {
						$class = 'altrow';
					}
			?>
            <li class="grid_4 alpha omega">
            <?php
					echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'home_featured_thumb','title'=>$event['Event']['slug'],'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'],false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$event['Event']['slug'],'escape' => false), null, array('inline' => false));
					?>
                  <h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title' => $event['Event']['slug'], 'escape' => false));?> </h3>
                    <p><?php echo $this->Html->cDateTime($event['Event']['created']); ?></p>
                    <p><span><?php echo __l('Photos galleries:');?> </span> <?php echo $this->Html->cInt($event['Event']['photo_album_count']);?></p>
                    </li>

                    <?php
					endforeach;?>
					 </ol>
					<?php
				else:
			?>
			<ol class="list photo-list clearfix">
			<li class="notice-info">
				<p class="notice"><?php echo __l('No events available');?></p>
			</li>
			 </ol>
	<?php
	endif;
	?>

