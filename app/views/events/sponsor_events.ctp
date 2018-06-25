
        <?php if($this->request->params['named']['type'] == 'featured') { ?>
			<h3><?php echo __l('Featured'); ?><span><?php echo __l(' Events'); ?></span></h3> 
		<?php } else { ?>
			<h3><?php echo __l('Sponsored'); ?><span><?php echo __l(' Events'); ?></span></h3> 
		<?php } ?>
		<?php $url = Router::url(array('controller' => 'events','action' => 'index','type'=>$this->request->params['named']['type']) , true); ?>


		<?php
			if(!empty($this->request->params['named']['date']) && $this->request->params['named']['date'] == 'all') {
				$class = "active";
			} else {
				$class = "normal";
			}
		?>
		<ul id="js-calendar-event" class="days-list1 events-list clearfix">
			<li class="normal">
				<?php echo $this->Html->link(__l('All'), 'javascript:void(0)', array('class'=>  "js-calendar-event {'url':'" . $url . "','container':'js-response','date':'all'}", 'title'=>__l('All'),'escape' => false)); ?>
			</li>
			<?php
				$today = time();
				$tody = date('D');
				for($i=0; $i<=4; $i++) {
					$viewdate = strtotime(date("Y-m-d", $today) . " +".$i." day");
					$dt = date('D',$viewdate);
					if ($dt == $tody) {
						$dt = __l('Today');
					}
					if (!empty($this->request->params['named']['time_str']) && $this->request->params['named']['time_str'] == $viewdate) {
						$class="active";
					} else {
						$class = "normal";
					}
			?>
				<li class="normal <?php echo $class; ?>">
					<?php echo $this->Html->link($this->Html->cText($dt), 'javascript:void(0)', array('class'=> "js-calendar-event {'url':'" . $url . "','container':'js-response','time_str':'".$viewdate."'}",'title'=>sprintf('%s',$dt),'escape' => false));?>
				</li>
			<?php } ?>
		</ul>
		<ol class="list feature-list clearfix">
			<?php
				if($events):
					$i=0;
					foreach($events as $event) :
					if($event['Event']['admin_suspend']=='0' && $event['Event']['end_date']>=date('Y-m-d', time())):		

						$class = null;
						if ($i++ % 2 == 0) :
							$class = 'altrow';
						endif;
	?>
		<li class="clearfix <?php echo $class; ?>">
				<div class="grid_3 omega alpha">
					<?php echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'sidebar_thumb', 'title' => $this->Html->cText($event['Event']['title'], false), 'alt' => sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin' => false), array('title' => $this->Html->cText($event['Event']['title'], false), 'escape' => false), null, array('inline' => false)); ?>
				</div>
				<div class="grid_5 omega alpha">
					<h3><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Event']['title'],false),20), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
					<p><?php echo $this->Html->link($this->Html->truncate($this->Html->cText($event['Venue']['name'],false),20), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
					<p><?php echo $this->Html->cDateTime($event['Event']['start_date']).__l(' to ').$this->Html->cDateTime($event['Event']['end_date']); ?></p>
					<p><span class="time-title"><?php echo __l('Type: '); ?></span><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?></p>
 				</div>
			</li>
			<?php endif;?>
			<?php 
					endforeach;
				else:
			?>
			<li>
				<p class="notice"><?php echo __l('No events available'); ?></p>
			</li>
			<?php
				endif;
			?>
		</ol>
		<div class="paging-block clearfix">
			<div class="paging-in js-pagination grid_left">
				<?php
					if (!empty($events)) {
						echo $this->element('paging_links');		    
					}
				?>
			</div>
				<?php if (!empty($events)) { ?>
    			<div class="view-all-event grid_right">
    				<?php echo $this->Html->link(__l('View all events '), array('controller' => 'events', 'action' => 'index'), array('class' => '', 'title'=>__l('View all events '),'escape' => false)); ?>
    			</div>
    		<?php } ?>
		</div>
	

