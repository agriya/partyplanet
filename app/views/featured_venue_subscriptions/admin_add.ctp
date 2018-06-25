<?php /* SVN: $Id: admin_add.ctp 17422 2012-02-08 07:53:58Z beautlin_108ac10 $ */ ?>
<div class="featuredVenueSubscriptions form">
<?php echo $this->Form->create('FeaturedVenueSubscription', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Featured Venue Subscriptions'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Featured Venue Subscription');?></legend>
	<?php
		echo $this->Form->input('name', array('label' => __l('No of Days')));
		echo $this->Form->input('amount', array('label' => __l('Amount').'('.Configure::read('site.currency').')'));
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->submit(__l('Add'));?>
</div>
<?php echo $this->Form->end();?>
</div>
