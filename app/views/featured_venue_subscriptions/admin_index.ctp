<?php /* SVN: $Id: admin_index.ctp 15308 2011-12-22 05:53:12Z jayashree_028ac09 $ */ ?>
<div class="featuredVenueSubscriptions index">
<h2><?php echo $this->pageTitle;?></h2>
<div class="clearfix add-block">
<span class="add-event" id="add-event">
	<?php echo $this->Html->link(__l('Add Venue Subscription'), array('controller' => 'featured_venue_subscriptions', 'action' => 'add'), array('class' => 'add', 'title' => __l('Add Venue Subscription'))); ?>
</span>
</div>
<?php echo $this->element('paging_counter');?>
<ul class="active-links-block clearfix">
              <?php if (empty($this->request->params['named']['username'])):?>
 			<li><?php echo $this->Html->link(__l('Active Featured Venue Subscriptions:'), array('controller'=>'featured_venue_subscriptions','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active Featured Venue Subscriptions')));?>
			  <?php echo $this->Html->cInt($active_featured_venue_subscription); ?></li>

            	<li><?php echo $this->Html->link(__l('Inactive Featured Venue Subscriptions:'), array('controller'=>'featured_venue_subscriptions','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive Featured Venue Subscriptions')));?>
			  <?php echo $this->Html->cInt($inactive_featured_venue_subscription); ?></li>

           		<li><?php echo $this->Html->link(__l('Total Featured Venue Subscriptions:'), array('controller'=>'featured_venue_subscriptions','action'=>'index'), array('title' => __l('Total Featured Venue Subscriptions')));?>
			  <?php echo $this->Html->cInt($total_featured_venue_subscription); ?></li>
			  <?php endif;?>
</ul>
<div class="form-content-block">
<div class="overflow-block">
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort(__l('No of Days'), 'name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Amount').'('.Configure::read('site.currency').')','amount');?></th>
        <th><?php echo $this->Paginator->sort('is_active');?></th>
    </tr>
<?php
if (!empty($featuredVenueSubscriptions)):

$i = 0;
foreach ($featuredVenueSubscriptions as $featuredVenueSubscription):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions">
			<span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $featuredVenueSubscription['FeaturedVenueSubscription']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> 
			<span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $featuredVenueSubscription['FeaturedVenueSubscription']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
		</td>
		<td><?php echo $this->Html->cDateTime($featuredVenueSubscription['FeaturedVenueSubscription']['created']);?></td>
		<td><?php echo $this->Html->cText($featuredVenueSubscription['FeaturedVenueSubscription']['name']);?></td>
		<td><?php echo $this->Html->cCurrency($featuredVenueSubscription['FeaturedVenueSubscription']['amount']);?></td>
		<td><?php echo $this->Html->cBool($featuredVenueSubscription['FeaturedVenueSubscription']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7"><p class="notice"><?php echo __l('No Featured Venue Subscriptions available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>

<?php
if (!empty($featuredVenueSubscriptions)) {
    echo $this->element('paging_links');
}
?>
</div>
