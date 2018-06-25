<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div id="breadcrumb">
<?php $this->Html->addCrumb(__l('Dashboard')); ?>
<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="users index ">
	<h2 class='admin-title'><?php echo __l('Dashboard'); ?></h2>

	<ul class="admin-list">
		<li><?php echo __l('Members: '). $users; ?></li>
		<li><?php echo __l('Events: '). $events; ?></li>
		<li><?php echo __l('Venues: '). $venues; ?></li>
		<li><?php echo __l('Sponsors: '). $event_sponsors; ?></li>
	</ul>
	<div class="js-tabs clearfix">
		<ul class="clearfix menu-tabs">
            <li><?php echo $this->Html->link(__l('Latest events'), '#tabs-1'); ?></li>
            <li ><?php echo $this->Html->link(__l('Latest venues'), '#tabs-2');?></li>
            <li ><?php echo $this->Html->link(__l('Latest event sponsors'), '#tabs-3');?></li>
		</ul>
		<div id="tabs-1">
			<?php echo $this->element('latest_events',array('cache' => array('config' => 'sec'))); ?>
		</div>
		<div id="tabs-2">
			<?php echo $this->element('latest_venues',array('cache' => array('config' => 'sec'))); ?>
		</div>
		<div id="tabs-3">
			<?php echo $this->element('latest_event_sponsors',array('cache' => array('config' => 'sec'))); ?>
		</div>
	</div>
</div>