<?php /* SVN: $Id: order.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="payments order form-content-block">
	<?php if($class_name == 'event') { ?>
		<h2><?php echo $name . " "; ?><span><?php echo __l('Event'); ?></span></h2>
	<?php } else { ?>
		<h2><?php echo $name . " "; ?><span><?php echo __l('Venue'); ?></span></h2>
	<?php } ?>
    <?php echo $this->Form->create('Payment', array('action' => 'order/'.$slug.'/'.$class_name, 'class' => 'normal')); ?>
	<?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
	<?php if($class_name == 'venue') { ?>	
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Premium Services'); ?></legend>
			<h3><?php echo __l('Have Your Venue Go Live Immediately!'); ?><h3>
			<?php if($this->request->data['Venue']['is_featured'] == 1) { ?>
				<span><?php echo __l('Already you have promoted your venue to display in featured box until') . ' ' . $this->Html->cDateTimeHighlight($this->request->data['Venue']['featured_end_date']) . '. ' . __l('Still if you want to extend your promote featued date, please select below option and pay.'); ?></span>
			<?php } ?>
			<span><?php echo __l('Please select either one or both of the options below: '); ?></span>
			<h3><?php echo __l('Featured Venue (Prominent placement throughout ').Configure::read('site.name').__l(' Website)'); ?></h3>
			<?php echo $this->Form->input('Venue.is_featured', array( 'label' => __l('Check to make featured for:'))) . ' ' . $this->Form->input('Venue.featured_venue_subscription_id', array( 'label' => __l(''))); ?>
			<?php if($this->request->data['Venue']['is_venue_enhanced_page'] == 0) { ?>
				<h3><?php echo __l('Enhanced Venue Page (More features, looks better)'); ?></h3>
				<?php echo $this->Form->input('Venue.is_venue_enhanced_page', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_venue_enhanced_amount') . ')' )); ?>
			<?php } ?>
			<?php if($this->request->data['Venue']['is_bump_up'] == 0) { ?>
				<h3><?php echo __l('Generic Listing (without any options chosen)'); ?></h3>
				<?php echo $this->Form->input('Venue.is_bump_up', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_venue_bumpup_amount') . ')' )); ?>
			<?php } ?>
		</fieldset>
	<?php } else { ?>
		<fieldset class="group-block round-5">
			<legend class="round-5"><?php echo __l('Premium Services'); ?></legend>
			<?php if($this->request->data['Event']['is_featured'] == 0) { ?>
				<h3><?php echo __l('FEATURED STATUS'). ' ('. Configure::read('site.currency') . Configure::read('site.is_event_fetured_amount') . ' ' .  __l('one time fee'). ')'; ?></h3>
				<?php echo $this->Form->input('Event.is_featured', array( 'label' => __l('Check here to make this event FEATURED'))); ?>
			<?php } ?>
			<?php if($this->request->data['Event']['is_bump_up'] == 0) { ?>
				<h3><?php echo __l('Generic Listing (without any options chosen)'); ?></h3>
				<?php echo $this->Form->input('Event.is_bump_up', array( 'label' => __l('check to upgrade') . ' (' . __l('one time fee of') . ' ' . Configure::read('site.currency') . Configure::read('site.is_event_bumpup_amount') . ')' )); ?>
			<?php } ?>
		</fieldset>
	<?php } ?>
	<fieldset class="group-block round-5">
	<legend class="round-5"><?php echo __l('Payment Type');?></legend>
	<?php echo $this->Form->input('payment_type_id', array('legend' => false, 'type' => 'radio', 'options' => $gateway_options['paymentTypes'], 'class' => 'js-payment-type'));?>
	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Pay')); ?>
	</div>
	</fieldset>
    <?php echo $this->Form->end(); ?>
</div>