<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<?php if(empty($this->request->params['named']['event_view'])) {
if($this->request->params['isAjax']!=1){
 ?>
	<div id="breadcrumb">
		<?php echo $this->Html->addCrumb(__l('Events')); ?>
		<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
	</div>
	<?php } if (isset($this->request->params['named']['type']) and $this->request->params['named']['type'] == 'guest') { ?>
		<h2><?php echo __l('Guest List Results');?></h2>
	<?php }
    if($this->request->params['isAjax']!=1){ ?>
	<div class="clearfix">
		<h2><?php echo __l('Events Listing & Guestlists'); ?></h2>
		<div class="event-link"><?php echo $this->Html->link(__l('Click here'), array('action'=>'add'), array('title' => __l('Click here'))) . ' ' . __l('to add your event to our event calendar');?></div>
		<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='guest') { ?>
			<div class="clearfix">
				<?php
					if(!empty($this->request->params['named']['date']) && $this->request->params['named']['date']=='week') {
						$class = 'active';
					} else {
						$class = 'normal';
					}
				?>
				<ul id="js-calendar-event" class="events-list guest-list clearfix">
					<li class="<?php echo $class ?>">
					
							<?php if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type']=='guest'){ ?>
								<p><?php echo $this->Html->link(__l('All'),  array('controller' => 'events', 'action' => 'index', 'type' => $this->request->params['named']['type']), array('title' => __l('All'), 'escape' => false)); ?></p>
							<?php } else { ?>
								<p><?php echo $this->Html->link(__l('This Week'),  array('controller' => 'events', 'action' => 'week_events', 'type' => $this->request->params['named']['type']), array('title' => __l('All'), 'escape' => false)); ?></p>
							<?php } ?>
					
					</li>
					<?php
						for($i=0; $i<=6; $i++) {
							$viewdate = strtotime("+" . $i . " day");
							$dt = date('D',$viewdate);
							$dn = date('d',$viewdate);
							$class = $params = '';
							if (!empty($this->request->params['named']['time_str'])):
								$params = strtotime(date('Y-m-d', $this->request->params['named']['time_str']));
							endif;
							$params1 = strtotime(date('Y-m-d', $viewdate));
							if (!empty($this->request->params['named']['time_str']) && $params1 == $params) {
								$class.='active';
							} else {
								$class.='normal';
							}
							if($i==0){
                             $class.=' cell-today';
                            }
					?>
						<li class="<?php echo $class; ?>">
						
								<dl class="events-list">
									<dt><?php echo $this->Html->link($this->Html->cText($dt), array('controller' => 'events', 'action' => 'index', 'type' => $this->request->params['named']['type'], 'time_str' => $viewdate), array('title' => sprintf('%s,%s', $dt, $dn), 'escape' => false));?></dt>
								<dd><?php echo $this->Html->link($this->Html->cText($dn), array('controller' => 'events','action' => 'index', 'type' => $this->request->params['named']['type'], 'time_str' => $viewdate), array('title' => sprintf('%s,%s', $dt, $dn), 'escape' => false));?></dd>
								</dl>
						
						</li>
					<?php } 
					?>

					<li class="normal">
						<div class="events-r">
							<p><?php echo $this->Html->link(__l('Upcoming'), array('controller' => 'events', 'action' => 'index','type'=>'guest','view'=>'upcoming'), array('class' => '', 'title'=>__l('Upcoming'),'escape' => false)); ?></p>
						</div>
					</li>
				</ul>
			</div>
		<?php } ?>
	</div>
	<?php }
        if(($featureEventCount ==0 ) && ($nonFeatureEventCount == 0)) { ?>
		<p class="notice"><?php echo __l('No events available'); ?></p>
	<?php } else { ?>
		<?php
			if ($featureEventCount) {
				echo $this->element('event-index', array('event_view' => 'feature','key'=>'feature'));
			}
			if($nonFeatureEventCount){
				echo $this->element('event-index', array('event_view' => 'non-feature','key'=>'non-feature'));
			}
		?>
	<?php } ?>
<?php } else { ?>
	<?php if (!empty($events)): ?>

			<h2>
    			<?php if($this->request->params['named']['event_view'] == 'feature') { ?>
    				<?php echo __l('Featured'); ?> <span> <?php echo __l(' Events'); ?></span>
    			<?php } else if($this->request->params['named']['event_view']=='non-feature') { ?>
    				<?php echo __l('More'); ?>  <span><?php echo __l(' Events'); ?></span>
    			<?php } else { ?>
    				<?php echo __l('Events'); ?>
    			<?php } ?>
            </h2>

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
							<?php
								if (!empty($event['Attachment'])): 
									echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false));
								endif;
								if($event['Event']['is_guest_list']):?>
							<?php echo $this->Html->link(__l('Guest List'), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('class'=>'more-info','title'=>__l('Guest List'),'escape' => false));?>
								<?php else:?>
							<?php echo $this->Html->link(__l('More Info'), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('class'=>'more-info','title'=>__l('More Info'),'escape' => false));?>
								<?php endif;?>
						</div>
						<div class="grid_12 omega ">
						    <h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
							<p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
							<p><?php echo $this->Html->cDate($event['Event']['start_date']).__l(' to ').$this->Html->cDate($event['Event']['end_date']); ?></p>
							<p><span class="time-title"><?php echo __l('Type: '); ?></span><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?></p>
						</div>
					</li>
				<?php endforeach; ?>
			</ol>

	<?php endif; ?>
	<?php
		if (!empty($events)) :
			echo $this->element('paging_links');
		endif;
	?>
<?php } ?>