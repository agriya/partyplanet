<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<ol class="list feature-list clearfix" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>" >
	<?php
		if (!empty($events)):
			$i = 0;
			foreach ($events as $event):
				$class = null;
				if ($i++ % 2 == 0) :
					$class = 'altrow';
				endif;
	?>
		<li class="clearfix <?php echo $class; ?>">
			<div class="grid_4 omega alpha">
				<?php echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'featured_event_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('escape' => false)); ?>
			</div>
			<div class="grid_11 omega alpha">
				<h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
				<p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
				<p><?php echo $this->Html->cDateTime($event['Event']['start_date']).__l(' to ').$this->Html->cDateTime($event['Event']['end_date']); ?></p>
				<p><?php echo __l('Type: '); ?><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?></p>
			</div>
		</li>
	<?php
			endforeach;
			if (!empty($filter) && $filter != 'recent'):
				$link = array('controller'=> 'events', 'action' => 'index', 'type' => $filter);
				if ($filter == 'similar') :
					$link['category'] = $event['EventCategory']['slug'];
				endif;
	?>
		<li class="more-link clearfix">
			<?php echo $this->Html->link(__l('More'), $link, array('class' => 'more', 'title' => __l('View More'), 'escape' => false)); ?>
		</li>
	<?php
			endif;
		else:
	?>
		<li>
			<p class="notice">
				<?php
					if (!empty($this->request->params['named']['user'])):
						echo sprintf('%s %s',$this->request->params['named']['user'],__l('is currently not attending any events'));
					else:
						echo __l('No events available');
					endif;
				?>
			</p>
		</li>
	<?php
		endif;
	?>
</ol>
