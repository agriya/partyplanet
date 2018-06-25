<?php /* SVN: $Id: $ */ ?>
<div class="eventComments index">
<?php if (empty($this->request->params['named']['event_comment'])): ?>
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'event_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Approved), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'event_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Disapproved), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'event_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'event_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'event_comments', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
<?php endif; ?>
<?php echo $this->element('paging_counter');?>
<?php
	echo $this->Form->create('EventComment' , array('class' => 'normal','action' => 'update'));
	echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<div class="overflow-block">
<table class="list">
    <tr>
	    <th class="sno"> <?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('user_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('event_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('comment');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></th>
    </tr>
<?php
if (!empty($eventComments)):
$i = 0;
foreach ($eventComments as $eventComment):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
	$class = 'altrow';
	}
	if($eventComment['EventComment']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
	if($eventComment['EventComment']['admin_suspend']):
		$status_class.= ' js-checkbox-suspended';
	else:
		$status_class.= ' js-checkbox-unsuspended';
	endif;
	if($eventComment['EventComment']['is_system_flagged']):
		$status_class.= ' js-checkbox-flagged';
	else:
		$status_class.= ' js-checkbox-unflagged';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
				<td class="sno"><?php echo $this->Form->input('EventComment.'.$eventComment['EventComment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$eventComment['EventComment']['id'], 'label' => false, 'class' =>$status_class.' js-checkbox-list')); ?></td>

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
                  			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $eventComment['EventComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                			<?php if($eventComment['EventComment']['is_system_flagged']):?>
                				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $eventComment['EventComment']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                				</li>
                			<?php else:?>
                				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $eventComment['EventComment']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                				</li>
                			<?php endif;?>
                			<?php if($eventComment['EventComment']['admin_suspend']):?>
                				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $eventComment['EventComment']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                				</li>
                			<?php else:?>
                				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $eventComment['EventComment']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                				</li>
                			<?php endif;?>
   					 </ul>
    				</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		</td>
		<td class="dc"><?php echo $this->Html->cDateTime($eventComment['EventComment']['created']);?></td>
		<?php if(!empty($eventComment['User']['username'])):?>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($eventComment['User']['username']), array('controller'=> 'users', 'action'=>'view', $eventComment['User']['username'], 'admin' => false), array('escape' => false));?></td>
		<?php else:?>
		<td><?php echo __l('Guest');?></td>
		<?php endif;?>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($eventComment['Event']['title']), array('controller'=> 'events', 'action'=>'view', $eventComment['Event']['slug'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl">
			<div class="status-block">
				<?php
					if($eventComment['EventComment']['admin_suspend']):
						echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
					endif;
					if($eventComment['EventComment']['is_system_flagged']):
						echo '<span class="flagged">'.__l('System Flagged').'</span>';
					endif;
				?>
			</div>
		<div class="js-desc-to-trucate {len:'90'} truncate-info"><?php echo $this->Html->cText($eventComment['EventComment']['comment']);?></div>
		</td>
		<td class="dl">
                         <?php if(!empty($eventComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($eventComment['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $eventComment['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$eventComment['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($eventComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($eventComment['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $eventComment['Ip']['Country']['name']; ?>">
									<?php echo $eventComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($eventComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $eventComment['Ip']['City']['name']; ?>    </span>
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
		<td colspan="9"><p class="notice"><?php echo __l('No event comments available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<div class="clearfix select-block-bot">
  <div class="admin-select-block grid_left">
        <div class="grid_left">
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
if (!empty($eventComments)) { ?>
   <div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?></div>
<?php } ?>
</div>
	<div class="hide">
		<?php echo $this->Form->submit(); ?>
	</div>

<?php echo $this->Form->end(); ?>
</div>