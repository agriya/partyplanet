<?php /* SVN: $Id: $ */ ?>
<div class="events index js-response">

<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="canceled round-5"><?php echo $this->Html->link(__l('Canceled') . ': ' . $this->Html->cInt($canceled, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Cancel), array('title' => __l('Canceled') . ': ' . $this->Html->cInt($canceled, false)));?></span></li>
	<li><span class="featured round-5"><?php echo $this->Html->link(__l('Featured') . ': ' . $this->Html->cInt($featured, false), array('controller' => 'events', 'action' => 'index', 'filter_id' => ConstMoreAction::Featured), array('title' => __l('Canceled') . ': ' . $this->Html->cInt($featured, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive + $suspended, false), array('controller' => 'events', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive + $suspended, false)));?></span></li>
</ul>

    <div class="clearfix">
     <div class="grid_left"><?php echo $this->element('paging_counter'); ?></div>
    <?php if(empty($this->request->params['named']['username'])):?>
        <div class="grid_left">
            <?php echo $this->Form->create('Event', array('class' => 'normal search-form clearfix', 'action'=>'index', 'type' => 'get'));
            ?>
                 <?php echo $this->Form->input('keyword', array('label' => __l('Keyword'))); ?>
                 <?php echo $this->Form->input('type', array('label'=>__l('Type'), 'empty' => __l('All'), 'options' => array('past' => __l('Past'), 'upcoming' => __l('Upcoming'))));?>
            	 <?php echo $this->Form->input('user', array( 'empty' => __l('All'), 'label' => __l('User'))); ?>
            
                    <?php echo $this->Form->submit(__l('Search'));?>
          
              <?php	echo $this->Form->end(); ?>
        </div>
    <?php endif;?>
    <?php if(empty($this->request->params['named']['username'])):?>
     <div class="grid_right">
       			<?php echo $this->Html->link(__l('Add Event'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add Event')));?>
    </div>
    <?php endif;?>
    </div>
<?php
echo $this->Form->create('Event' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));	?>
<table class="list" id="js-expand-table">
    <tr class="js-even">
      <th class="select"><?php echo __l('Select');?></th>
	    <th class="dl"><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
 	    <th class="dl"><?php echo $this->Paginator->sort(__l('Venue'),'venue_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('User'),'user_id');?></th>
	    <th class="dc date-time-block"><div class="js-pagination"><?php echo __l('Date'); ?><div><?php echo $this->Paginator->sort(__l('Start'), 'Event.start_date'); ?><?php echo '/'.$this->Paginator->sort(__l('End'), 'Event.end_date'); ?></div></div></th>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
		<th class="dl"><?php echo $this->Paginator->sort(__l('Revenue (' . Configure::read('site.currency') . ')'),'revenue');?></th>
		<th class="dl"><?php echo $this->Paginator->sort(__l('Site Revenue (' . Configure::read('site.currency') . ')'), 'site_revenue');?></th>
		<?php } ?>
        </tr>
<?php
if (!empty($events)):
$i = 0;
foreach ($events as $event):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
	$class = 'altrow';
	}
	if($event['Event']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
    	$active_class = ' inactive-record';
    	$status_class = 'js-checkbox-inactive';
	endif;
	if($event['Event']['is_feature']):
		$feature_class='js-checkbox-featured';
	else:
		$feature_class='js-checkbox-non-featured'; 
	endif;

	if($event['Event']['admin_suspend']):
		$status_class.= ' js-checkbox-suspended';
	else:
		$status_class.= ' js-checkbox-unsuspended';
	endif;
	if($event['Event']['is_system_flagged']):
		$status_class.= ' js-checkbox-flagged';
	else:
		$status_class.= ' js-checkbox-unflagged';
	endif;
	if($event['Event']['is_cancel']):
		$status_class.= ' js-checkbox-cancelled';
	else:
		$status_class.= ' js-checkbox-uncancelled';
	endif;
	$status_class=$status_class.' '.$feature_class;
	
?>
<tr class="<?php echo $class.$active_class;?> expand-row js-odd">
		<td class="<?php echo $class;?> select"><div class="arrow"></div>
        <?php echo $this->Form->input('Event.'.$event['Event']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_event".$event['Event']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?>
        </td>
         <td>
           <div class="status-block">
		<?php 
				if(!empty($event['Event']['admin_suspend'])):
				echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
				endif;
				if($event['Event']['is_system_flagged']):
					echo '<span class="flagged">'.__l('System Flagged').'</span>';
				endif;
				if($event['Event']['is_feature']):
					echo '<span class="featured">'.__l('Featured').'</span>';
				endif;
				if($event['Event']['is_cancel']):
					echo '<span class="canceled">'.__l('Canceled').'</span>';
				endif;

		?>
		</div>
		<?php echo $this->Html->link($this->Html->cText($event['Event']['title'],false), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin' => false), array('escape' => false));?>
        </td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action'=>'view', $event['Venue']['slug'],'admin'=>false), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($event['User']['username']), array('controller'=> 'users', 'action'=>'view', 'admin' => false, $event['User']['username']), array('escape' => false));?></td>
		<td class="dc date-time-block">
    	  <div class="clearfix">
              <div class="event-info">
               <?php
					$event_progress_precentage = 0;
					if(strtotime($event['Event']['start_date']) < strtotime(date('Y-m-d H:i:s'))) {
						if($event['Event']['end_date'] !== null) {
							$days_till_now = (strtotime(date("Y-m-d")) - strtotime(date($event['Event']['start_date']))) / (60 * 60 * 24);
							$total_days = (strtotime(date($event['Event']['end_date'])) - strtotime(date($event['Event']['start_date']))) / (60 * 60 * 24);
							$event_progress_precentage = 0;
							if ($total_days > 0) {
								$event_progress_precentage = round((($days_till_now/$total_days) * 100));
							}
							if($event_progress_precentage > 100)
							{
								$event_progress_precentage = 100;
							}
						} else {
							$event_progress_precentage = 100;
						}
					}
				?>

                <p class="progress-bar round-5">
                   <span class="round-5 <?php echo ($event['Event']['end_date'] === null)? ' any-time-deal-progress': 'progress-status '; ?>" style="width:<?php echo $event_progress_precentage; ?>%" title="<?php echo ($event['Event']['end_date'] === null)? __l('Any Time Deal'): $event_progress_precentage.'%'; ?>">&nbsp;</span>
                </p>
                <p class="progress-value clearfix"><span class="progress-from"><?php echo $this->Html->cDateTimeHighlight($event['Event']['start_date']);?></span><span class="progress-to"><?php echo (!is_null($event['Event']['end_date']))? $this->Html->cDateTimeHighlight($event['Event']['end_date']): ' - ';?></span></p>
               </div>
        </div>
        </td>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
        <td class="dr"><?php echo $this->Html->cFloat($event['Event']['revenue']); ?></td>
		<td class="dr site-amount"><?php echo $this->Html->cFloat($event['Event']['site_revenue']); ?></td>
		<?php } ?>
		</tr>
        <tr class="hide">
		  <td colspan="7" class="action-block">
		     <div class="action-info-block clearfix">
                <div class="action-left-block">
                	<h3> <?php echo __l('Action'); ?> </h3>
                	<ul class="action-link clearfix">
                    <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $event['Event']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
        			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $event['Event']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        			<?php if($event['Event']['is_system_flagged']):?>
        				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $event['Event']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
        				</li>
        			<?php else:?>
        				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $event['Event']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
        				</li>
        			<?php endif;?>
        			<?php if($event['Event']['admin_suspend']):?>
        				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $event['Event']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
        				</li>
        			<?php else:?>
        				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $event['Event']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
        				</li>
        			<?php endif;?>
   					 </ul>
                </div>
                <div class="action-right-block deal-action-right-block clearfix">
                 <div class="clearfix">
                                   	  <div class="action-right action-right1">
                                       <h3><?php echo __l('General'); ?></h3>
                                       <dl class="clearfix">
        								   <dt><?php echo __l('Venue'); ?></dt><dd><?php echo $this->Html->link($this->Html->cText($event['Venue']['name']), array('controller'=> 'venues', 'action'=>'view', $event['Venue']['slug'],'admin'=>false), array('escape' => false));?></dd>
        								   <dt><?php echo __l('Event Category'); ?></dt><dd><?php echo $this->Html->cText($event['EventCategory']['name']);?></dd>
        								   <dt class="clearfix"><?php echo __l('Age Requirements'); ?></dt><dd><?php echo $this->Html->cText($event['AgeRequirment']['name']); ?></dd>
        								</dl>
                                     </div>
                                      <?php if(!empty($event['Event']['is_guest_list'])):?>
                                     <div class="action-right">
                                       <h3><?php echo __l('Guestlist'); ?></h3>
                                         <dl class="clearfix">
        								  <dt><?php echo __l('Total Signups'); ?></dt><dd><?php echo !empty($event['GuestList']['guest_limit'])?$this->Html->cInt($event['GuestList']['guest_limit']):'-'; ?></dd>
        								  <dt><?php echo __l('Email'); ?></dt><dd><?php echo !empty($event['GuestList']['email'])?$this->Html->cText($event['GuestList']['email']):'-'; ?></dd>
        								  <dt><?php echo __l('Fax'); ?></dt><dd><?php echo !empty($event['GuestList']['fax'])?$this->Html->cText($event['GuestList']['fax']):'-'; ?></dd>
        								</dl>
                                    </div>
                                    <?php endif;?>
                  </div>
                </div>
                <div class="action-right action-right-block action-right4">
                   <div class="venues-img-block">
                <?php echo $this->Html->link($this->Html->showImage('Event', $event['Attachment'], array('dimension' => 'admin_listing_thumb','title'=>$this->Html->cText($event['Event']['title'], false),'escape'=>false, 'alt'=>sprintf('[Image: %s]', $this->Html->cText($event['Event']['title'], false)))), array('controller' => 'events', 'action' => 'view',$event['Event']['slug'],'admin'=>false), array('escape'=>false), false);?>
                </div>
                <dl class="clearfix">
                     <dt><?php echo __l('Added On'); ?></dt><dd><?php echo $this->Html->cDateTime($event['Event']['created']);?></dd>
                     <dt><?php echo __l('Attenders'); ?></dt><dd><?php echo $this->Html->cText($event['GuestList']['guest_list_user_count']); ?></dd>
                     <dt><?php echo __l('Reviews'); ?></dt><dd><?php echo $this->Html->cText($event['Event']['event_comment_count']); ?></dd>
                     <dt><?php echo __l('Photos'); ?></dt><dd><?php echo $this->Html->cText($event['Event']['photo_count']); ?></dd>
                     <dt><?php echo __l('Videos'); ?></dt><dd><?php echo $this->Html->cText($event['Event']['video_count']); ?></dd>
                     <?php if(!empty($event['Event']['ticket_fee'])){ ?>
                     <dt><?php echo __l('Event Fee'); ?></dt><dd><?php echo $this->Html->siteCurrencyFormat($event['Event']['ticket_fee']); ?></dd>
                     <?php } ?>
                     <dt><?php echo __l('IP');?></dt><dd><?php echo $this->Html->cText($event['Ip']['ip']);?></dd>
                </dl>
                </div>
             </div>
		    </td>
		</tr>
<?php
    endforeach;
else:
?>
	<tr class="js-odd">
		<td colspan="5"><p class="notice"><?php echo __l('No events available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($events)):
?> <div class="clearfix select-block-bot">
	<div class="admin-select-block grid_left">
	<div>
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
        <?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
        <?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Inactive'))); ?>
        <?php echo $this->Html->link(__l('Active'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Active'))); ?>
        <?php echo $this->Html->link(__l('Featured'), '#', array('class' => 'select js-admin-select-featured', 'title' => __l('Featured'))); ?>
        <?php echo $this->Html->link(__l('Non Featured'), '#', array('class' => 'select js-admin-select-non-featured', 'title' => __l('Non Featured'))); ?>
		<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
		<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
		<?php echo $this->Html->link(__l('Canceled'), '#', array('class' => 'js-select-cancelled', 'title' => __l('Canceled'))); ?>
		</div>
		<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
	</div>
<?php
if (!empty($events)) : ?>
   <div class="js-pagination grid_right"><?php echo $this->element('paging_links');?> </div>
<?php endif;
?>
</div>
	<?php
endif; ?>
    <div class="hide">
        <?php echo $this->Form->submit(); ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
