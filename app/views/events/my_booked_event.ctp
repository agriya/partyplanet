<?php if (!empty($events)): ?>
	<?php echo $this->element('paging_counter');?>
		<ol class="list feature-list clearfix">
			<?php
				$i = 0;
				foreach ($events as $event):
				$class = null;
				if ($i++ % 2 == 0)
				{
					$class = 'altrow';
				} ?>
				<li class="clearfix <?php echo $class; ?>">
					<div class="grid_4 alpha">
						<?php if (empty($event['Attachment']['filename'])): ?>
							<?php
								$video['Thumbnail']['id'] = (!empty($event['Event']['default_thumbnail_id'])) ? $event['Event']['default_thumbnail_id'] : '';
								echo $this->Html->link($this->Html->showImage('Event', $event['Thumbnail'], array('dimension' => 'home_newest_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($event['Event']['title'], false)), 'title' => $this->Html->cText($event['Event']['title'], false))) , array('controller' => 'events', 'action' => 'view', $event['Event']['slug']) , array('escape' => false));
							?>
						<?php endif; ?>
						<?php
							if (!empty($event['Attachment'])):
								echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)),'escape' => false)), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false));
							endif;
						?>
						<?php echo $this->Html->link(__l('More Info'), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('class'=>'more-info','title'=>__l('More Info'),'escape' => false));?>
					</div>
						<div class="grid_9 omega">
    						<div class="clearfix">
    							<h3 class="event-title-info grid_left"><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Event']['title'],false), 40), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
                                <?php if($event['Event']['admin_suspend'] == 1 or $event['Venue']['admin_suspend'] == 1) { ?>
             						<span class="suspended-block grid_left">
            							<?php echo __l('Suspended');?>
        							</span>
							     <?php }?>
                            </div>
                    		<p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
							<p><?php echo $this->Html->cDateTime($event['Event']['start_date'] . " " .$event['Event']['start_time']); ?></p>
							<p><span class="time-title"><?php echo __l('Type: '); ?></span><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?></p>
						</div>
						<?php if($event['GuestList']['GuestListUser']) { ?>
						<div class=" event-action-block">
							<?php
								echo $this->Html->link(__l('Print Ticket'), array('controller'=> 'events', 'action' => 'print_ticket', $event['GuestList']['GuestListUser'][0]['id']), array('title'=>__l('Print Ticket'), 'class' => 'edit', 'escape' => false));
							?>
						</div>
						<?php } ?>
				
				</li>
			<?php endforeach; ?>
		</ol>

<?php else: ?>
	<ol class="list feature-list clearfix">
		<li>
			<p class="notice"><?php echo __l('No events booked'); ?></p>
		</li>
	</ol>
<?php endif;?>
<?php
	if (!empty($events)) :
		echo $this->element('paging_links');
	endif;
?>