<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="venueCategories index">
<h2><?php echo __l('Venue Categories');?></h2>
<?php echo $this->Html->link(__l('Add Venue Categories '), array('controller' => 'venue_categories', 'action' => 'add'),array('class'=>'add'));?>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($venueCategories)):

$i = 0;
foreach ($venueCategories as $venueCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cText($venueCategory['VenueCategory']['name']);?></p>
		<p><?php echo $this->Html->cText($venueCategory['VenueCategory']['description']);?></p>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No venue categories available');?></p>
	</li>
<?php
endif;
?>
</ol>


</div>
