<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div id="breadcrumb">
	<?php echo $this->Html->addCrumb(__l('GuestList')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Events')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
  </div>
  <div class="clearfix">

<div class="add-block">
<?php	echo $this->Html->link(__l('Add event'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add event')));?>
 </div>
 <?php	echo $this->Html->link(__l('Search by type'), array('controller'=> 'events','action'=>'search','type'=>'type'), array( 'title' => __l('Search by type')));?>
<?php	echo $this->Html->link(__l('Search by location'), array('controller'=> 'events','action'=>'search','type'=>'location'), array( 'title' => __l('Search by location')));?>
<?php	echo $this->Html->link(__l('Guestlist'), array('controller'=> 'events','action'=>'index','type'=>'guest'), array( 'title' => __l('Guestlist')));?>
</div>
<h2><?php echo __l('GuestList Events');	?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list feature-list clearfix" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
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
            <div class="grid_4  alpha">
                <?php
        		echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'home_newest_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',   $event['Event']['slug'],'admin'=>false), array('title'=>$this->Html->cText($event['Event']['title'], false),'escape' => false), null, array('inline' => false));
                ?>
            </div>
           	<div class="grid_12  alpha">
                <h3><?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $event['Event']['slug']), array('title'=>$this->Html->cText($event['Event']['title'],false),'escape' => false));?></h3>
			    <p><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $event['Venue']['slug']), array('title'=>$event['Venue']['name'],'escape' => false));?></p>
			    <p> 	<?php echo __l($event['timing']['description']);	?></p>
		      	<p><?php echo __l('Type').':'; ?><?php echo  $this->Html->link($this->Html->cText($event['EventCategory']['name']), array('controller'=> 'events', 'action' => 'index', 'category'=>$event['EventCategory']['slug']), array('title'=>$event['EventCategory']['name'],'escape' => false));?> </p>
            </div>
    </li>

<?php
    endforeach;
else:
?>
	<li class="clearfix">
		<p class="notice"><?php echo __l('No events available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($events)) :
    echo $this->element('paging_links');
endif;
?>