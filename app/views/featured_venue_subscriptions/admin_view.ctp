<?php /* SVN: $Id: admin_view.ctp 15308 2011-12-22 05:53:12Z jayashree_028ac09 $ */ ?>
<div class="featuredVenueSubscriptions view">
<h2><?php echo __l('Featured Venue Subscription');?></h2>
	<dl class="list"><?php $i = 0; $class = ' class="altrow"';?>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($featuredVenueSubscription['FeaturedVenueSubscription']['id']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($featuredVenueSubscription['FeaturedVenueSubscription']['created']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($featuredVenueSubscription['FeaturedVenueSubscription']['modified']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Name');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($featuredVenueSubscription['FeaturedVenueSubscription']['name']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Amount');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($featuredVenueSubscription['FeaturedVenueSubscription']['amount']);?></dd>
		<dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Active');?></dt>
			<dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cBool($featuredVenueSubscription['FeaturedVenueSubscription']['is_active']);?></dd>
	</dl>
</div>

