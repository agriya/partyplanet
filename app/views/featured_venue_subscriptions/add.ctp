<?php /* SVN: $Id: add.ctp 17422 2012-02-08 07:53:58Z beautlin_108ac10 $ */ ?>
<div class="featuredVenueSubscriptions form">
<?php echo $this->Form->create('FeaturedVenueSubscription', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Featured Venue Subscriptions'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Featured Venue Subscription');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('amount');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active')));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Add'));?>
</div>
