<?php /* SVN: $Id: admin_index.ctp 17235 2012-02-03 11:51:36Z beautlin_108ac10 $ */ ?>
<div class="venueOwners index">

<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Approved') . ': ' . $this->Html->cInt($active, false), array('controller' => 'venue_owners', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Approved') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Waiting for Approval') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'venue_owners', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Waiting for Approval') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'venue_owners', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>

<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('first_name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('last_name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('email');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('venue_name');?></th>
		<th class="dl"><?php echo $this->Paginator->sort('venue_type_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('city/country_id');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('mobile');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('other_mobile');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('gender_id');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('DOB'), 'dob');?></th>
    </tr>
<?php
if (!empty($venueOwners)):
$i = 0;
foreach ($venueOwners as $venueOwner):
	$class = $active_class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if (!$venueOwner['VenueOwner']['is_created']):
	    $active_class = ' inactive-record';
	endif;
?>
	<tr class="<?php echo $class . $active_class; ?>">
        <td class="actions">

        <div class="action-block">
            <span class="action-information-block">
                <span class="action-left-block">&nbsp;&nbsp;</span>
                    <span class="action-center-block">
                        <span class="action-info">
                            <?php echo __l('Action');?>
                         </span>
                    </span>
                </span>
                <div class="action-inner-block">
                <div class="action-inner-left-block">
                    <ul class="action-link clearfix">
            			<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $venueOwner['VenueOwner']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
            			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venueOwner['VenueOwner']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
            			 <?php if(empty($venueOwner['VenueOwner']['is_created'])):?>
                        <li><?php echo $this->Html->link(__l('Create Venue Owner'), array('controller' => 'users', 'action' => 'add', 'venue_owner' => $venueOwner['VenueOwner']['id']), array('class' => 'add', 'title' => __l('Create Venue Owner'))); ?></li>
                        <?php endif;?>
					 </ul>
					</div>
					<div class="action-bottom-block"></div>
				  </div>
          </div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($venueOwner['VenueOwner']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['VenueOwner']['first_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['VenueOwner']['last_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['VenueOwner']['email']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['VenueOwner']['venue_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['VenueType']['name']);?></td>
							<td class="dl">
                         <?php if(!empty($venueOwner['City']['name'])): ?>
							<p>
							<?php
                            if(!empty($venueOwner['Country']['name'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($venueOwner['Country']['iso_alpha2']); ?>" title ="<?php echo $venueOwner['Country']['name']; ?>">
									<?php echo $venueOwner['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($venueOwner['City'])):
                            ?>
                            <span> 	<?php echo $venueOwner['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
						</td>
		<td class="dc"><?php echo $this->Html->cText($venueOwner['VenueOwner']['mobile']);?></td>
		<td class="dc"><?php echo $this->Html->cText($venueOwner['VenueOwner']['other_mobile']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueOwner['Gender']['name']);?></td>
		<td class="dl"><?php echo $this->Html->cDate($venueOwner['VenueOwner']['dob']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="17"><p class="notice"><?php echo __l('No Venue Owners available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($venueOwners)) {
    echo $this->element('paging_links');
}
?>

</div>