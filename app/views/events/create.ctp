<div class="crumb-block">
<?php $this->Html->addCrumb(__l('Events'), array('controller' => 'events', 'action' => 'index')); ?>
<?php $this->Html->addCrumb(__l('Create Event')); ?>
<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
	<h2 class="title"><?php echo __l('Create Event');?></h2>
	<div id="js-seearh-form">
		<ul class="step-list clearfix">
			<li class="active">
				<span class="step">1</span>
				<span class="step-text">Choose Venue</span>
			</li>
			<li>
				<span class="step">2</span>
				<span class="step-text">Enter Venue Details</span>
			</li>
			<li>
				<span class="step">3</span>
				<span class="step-text">Enter Event Details</span>
			</li>
			<li>
				<span class="step">4</span>
				<span class="step-text1">Finished</span>
			</li>
		</ul>
	</div>
	<span class="info-new"><?php echo  __l('Pick the venue where your event is being held, from the available list. If the venue you are looking for is not included, go down to "Add it now", and add your venue.');?></span>
	
	<?
	echo $this->requestAction(array('controller'=>'venues','action'=>'search'), array('return'));
	?>
	<h3 class="show-add-venue-form"><span><?php echo __l('Still can\'t find a place?').' '; ?></span> <span class="js-show-add-venue-form show-add-venue-form" title="<?php echo __l('Add it now');?>"> <?php echo __l('Add it now');?></span> </h3>
	<div class="js-venue-add-div" style="display:none">
		<ul class="step-list clearfix">
			<li>
				<span class="step">1</span>
				<span class="step-text">Choose Venue</span>
			</li>
			<li class="active">
				<span class="step">2</span>
				<span class="step-text">Enter Venue Details</span>
			</li>
			<li>
				<span class="step">3</span>
				<span class="step-text">Enter Event Details</span>
			</li>
			<li>
				<span class="step">4</span>
				<span class="step-text1">Finished</span>
			</li>
		</ul>
		<?php echo  $this->requestAction(array('controller'=>'venues','action'=>'add','event'), array('return')); ?>
	</div>

