<div class="js-response">
        <h3>
	      <?php if($this->request->params['named']['type'] == 'popular') {?>
				<?php echo __l('Popular'); ?><span><?php echo __l(' Events'); ?></span>
			<?php } else if($this->request->params['named']['type'] == 'samevenue') {?>
				<?php echo __l('More Events at ') . '<span>' . $this->Html->cText($venu_info['Venue']['name']) . ', ' .  $this->Html->cText($venu_info['Venue']['City']['name']) . '</span>'; ?>
			<?php } else { ?>
				<?php echo __l('New Events at ') . '<span>' . $this->Html->cText($venue['Venue']['name']) . ', ' .  $this->Html->cText($venue['City']['name']) . '</span>'; ?>
			<?php } ?>
	      </h3>
	<?php if (!empty($events)): ?>
			<ol class="list feature-list clearfix">
				<?php
                	$i = 0;
					foreach ($events as $event):
						$class = null;
						if ($i++ % 2 == 0) :
							$class = 'altrow';
						endif;
			?>
				<li class="clearfix <?php echo $class; ?>">
						<div class="grid_3 omega alpha">
                             <?php echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'sidebar_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false)); ?>
                        </div>
						<div class="grid_5 omega alpha">
						<h3><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Event']['title'],false),20), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
							<p><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Venue']['name'],false),20), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
								<p>
							<?php if ($this->request->params['named']['type'] == 'popular') { ?>
								<?php echo $this->Html->link($this->Html->cText($event['Event']['event_user_count']), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title' => $event['Event']['event_user_count'], 'escape' => false));?>
								<span><?php echo __l('People Are Going'); ?></span>
							<?php } else { ?> 
								<?php echo $this->Html->cDate($event['Event']['start_date']).__l(' to ').$this->Html->cDate($event['Event']['end_date']); ?>
							<?php } ?></p>
					</div>
				
					</li>
				<?php endforeach; ?>
			</ol>

				<div class="js-pagination">
					<?php echo $this->element('paging_links'); ?>
				</div>
		
		<?php else: ?>
			<ol class="list clearfix">
				<li>
					<?php if (!empty($venue)): ?>
						<p class="notice"><?php echo sprintf(__l('There are currently no events listed for %s %s yet.'), $this->Html->cText($venue['Venue']['name']), $this->Html->cText($venue['City']['name'])); ?></p>
						<?php echo $this->Html->link(__l('Click here'), array('controller' => 'events', 'action' => 'add',$venue['Venue']['slug']), array('title' => __l('Add Event'))) . ' ' . sprintf(__l('if you want to promote or list an event at %s %s'), $this->Html->cText($venue['Venue']['name']), $this->Html->cText($venue['City']['name'])); ?>
					<?php else: ?>
						<p class="notice"><?php echo __l('No events available'); ?></p>
					<?php endif; ?>
				</li>
			</ol>
		<?php endif; ?>

  	   </div>