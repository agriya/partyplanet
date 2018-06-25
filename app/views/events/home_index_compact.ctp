<?php if(!empty($events)){?>
  <ol class="slider clearfix jcarousel-skin-tango" id="mycarousel">
		<?php			$i = 0;
			foreach ($events as $event):
				$class = null;
				if ($i++ % 2 == 0) :
					$class = ' class="altrow"';
				endif;
	?>
		<li class="clearfix <?php echo $class; ?>">
			<div class="slider-top"></div>
			<div class="slider-in">
				<?php echo $this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'small_big_thumb','title'=>$this->Html->cText($event['Event']['title'],false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'],false)))); ?>
				<h3><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Event']['title'],false),40), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
				<p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller' => 'venues', 'action' => 'view', $event['Venue']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Venue']['name'],false),'escape' => false));?></p>
				<div class="slider-info clearfix">
					<p><?php echo $this->Html->cDateTime($event['Event']['start_date']); ?></p>
					<?php echo __l('Type: ') ;?>
				<?php echo  $this->Html->link($this->Html->truncate($this->Html->cText($event['EventCategory']['name']),25), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?>
				
				</div>
			</div>
			<div class="slider-bot"></div>
        </li>
	<?php }?>
      </ol>
<?php } ?>