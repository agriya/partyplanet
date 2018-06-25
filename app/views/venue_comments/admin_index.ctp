<?php /* SVN: $Id: $ */ ?>
<div class="venueComments index">
<?php if(empty($this->request->params['named']['venue_comment'])):?>
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'venue_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Approved), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'venue_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Disapproved), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'venue_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'venue_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'venue_comments', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
<?php endif;?>
<?php echo $this->element('paging_counter');?>
<?php
	echo $this->Form->create('VenueComment' , array('class' => 'normal','action' => 'update'));
	echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<div class="overflow-block">
<table class="list">
    <tr>
        <th class="select"> <?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('user_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('venue_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('title');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('comment');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></th>
    </tr>
<?php
if (!empty($venueComments)):

$i = 0;
foreach ($venueComments as $venueComment):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}

	if($venueComment['VenueComment']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
	if($venueComment['VenueComment']['admin_suspend']):
		$status_class.= ' js-checkbox-suspended';
	else:
		$status_class.= ' js-checkbox-unsuspended';
	endif;
	if($venueComment['VenueComment']['is_system_flagged']):
		$status_class.= ' js-checkbox-flagged';
	else:
		$status_class.= ' js-checkbox-unflagged';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
		 <td class="sno"><?php echo $this->Form->input('VenueComment.'.$venueComment['VenueComment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$venueComment['VenueComment']['id'], 'label' => false, 'class' =>$status_class.' js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venueComment['VenueComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                			<?php if($venueComment['VenueComment']['is_system_flagged']):?>
                				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $venueComment['VenueComment']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                				</li>
                			<?php else:?>
                				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $venueComment['VenueComment']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                				</li>
                			<?php endif;?>
                			<?php if($venueComment['VenueComment']['admin_suspend']):?>
                				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $venueComment['VenueComment']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                				</li>
                			<?php else:?>
                				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $venueComment['VenueComment']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                				</li>
                			<?php endif;?>
   					   </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		</td>
		<td class="dc"><?php echo $this->Html->cDateTime($venueComment['VenueComment']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($venueComment['User']['username']), array('controller'=> 'users', 'action'=>'view', $venueComment['User']['username'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($venueComment['Venue']['name'],false), array('controller'=> 'venues', 'action'=>'view', $venueComment['Venue']['slug'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->cText($venueComment['VenueComment']['title']);?></td>
		<td class="dl">
			<div class="status-block">
				<?php
					if($venueComment['VenueComment']['admin_suspend']):
						echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
					endif;
					if($venueComment['VenueComment']['is_system_flagged']):
						echo '<span class="flagged">'.__l('System Flagged').'</span>';
					endif;
				?>
			</div>
			<div class="js-desc-to-trucate {len:'90'} truncate-info"><?php echo $this->Html->cText($venueComment['VenueComment']['comment']);?></div>
		</td>
		<td class="dl">
                         <?php if(!empty($venueComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($venueComment['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $venueComment['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$venueComment['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($venueComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($venueComment['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $venueComment['Ip']['Country']['name']; ?>">
									<?php echo $venueComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($venueComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $venueComment['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
						</td>
		
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No venue comments available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<div class="clearfix select-block-bot">
	<div class="admin-select-block grid_left">
		<div class="grid_left admin-select-link">
            <?php echo __l('Select:'); ?>
    		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
            <?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Inactive'))); ?>
            <?php echo $this->Html->link(__l('Active'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Active'))); ?>
    		<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
    		<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
		</div>
    	<div class="admin-checkbox-button grid_left">
	       	<?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
	 </div>
    <?php
if (!empty($videoCategories)) { ?>
    <div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?></div>
<?php }
?>
    </div>
	<div class="hide">
		<?php echo $this->Form->submit(); ?>
	</div>
<?php echo $this->Form->end(); ?>

</div>
