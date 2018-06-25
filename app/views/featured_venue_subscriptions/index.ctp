<?php /* SVN: $Id: index.ctp 15308 2011-12-22 05:53:12Z jayashree_028ac09 $ */ ?>
<div class="featuredVenueSubscriptions index">
<h2><?php echo __l('Featured Venue Subscriptions');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($featuredVenueSubscriptions)):

$i = 0;
foreach ($featuredVenueSubscriptions as $featuredVenueSubscription):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($featuredVenueSubscription['FeaturedVenueSubscription']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($featuredVenueSubscription['FeaturedVenueSubscription']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($featuredVenueSubscription['FeaturedVenueSubscription']['modified']);?></p>
		<p><?php echo $this->Html->cText($featuredVenueSubscription['FeaturedVenueSubscription']['name']);?></p>
		<p><?php echo $this->Html->cCurrency($featuredVenueSubscription['FeaturedVenueSubscription']['amount']);?></p>
		<p><?php echo $this->Html->cBool($featuredVenueSubscription['FeaturedVenueSubscription']['is_active']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $featuredVenueSubscription['FeaturedVenueSubscription']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $featuredVenueSubscription['FeaturedVenueSubscription']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Featured Venue Subscriptions available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($featuredVenueSubscriptions)) {
    echo $this->element('paging_links');
}
?>
</div>
