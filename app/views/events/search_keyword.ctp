<h2><?php echo __l('Events'); ?></h2>
	<div class="js-response">
		<?php echo $this->element('paging_counter');?>
		<ol class="list feature-list clearfix">
			<?php
				if (!empty($events)):
					$i = 0;
					foreach ($events as $event):
						$class = null;
						if ($i++ % 2 == 0)
						{
							$class = 'altrow';
						} ?>
						<li class="clearfix <?php echo $class; ?>">
								<div class="grid_4  alpha">
								<?php
									if (!empty($event['Attachment'])): 
										echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false));
									endif;
									if($event['Event']['is_guest_list']):
										 echo $this->Html->link(__l('Guest List'), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array( 'class'=>'more-info','title'=>__l('Guest List'),'escape' => false));
									else:
										 echo $this->Html->link(__l('More Info'), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('class'=>'more-info','title'=>__l('More Info'),'escape' => false));
									endif;
								?>
							</div>
					       	<div class="grid_12 omega ">
								<h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
								<?php if (!empty($event['Venue'])): ?>
									<p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
								<?php endif; ?>
								<p><?php echo $this->Html->cDateTime($event['Event']['start_date']).__l(' to ').$this->Html->cDateTime($event['Event']['end_date']); ?></p>
								<p><?php echo __l('Type: '); ?><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?></p>
							</div>
						</li>
				<?php endforeach; ?>
			<?php else: ?>
					<li>
						<p class="notice"><?php echo __l('No events available');?></p>
					</li>
			<?php endif; ?>			
		</ol>
		<?php if (!empty($events)) : ?>
			<div class="js-pagination">
				<?php echo $this->element('paging_links'); ?>
			</div>
		<?php endif; ?>
	</div>

	<h2><?php echo __l('Venues'); ?></h2>
	<?php
		echo $this->element('venue-index-search', array('cache' => array('config' => '2sec')));
	?>
