<?php /* SVN: $Id: $ */ ?>
<div class="venues index js-response">
    <ul class="filter-list clearfix">
    	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'venues', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
    	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'venues', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
    	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'venues', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
    	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'venues', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
		<li><span class="featured round-5"><?php echo $this->Html->link(__l('Featured') . ': ' . $this->Html->cInt($featured, false), array('controller' => 'venues', 'action' => 'index', 'filter_id' => ConstMoreAction::Featured), array('title' => __l('Canceled') . ': ' . $this->Html->cInt($featured, false)));?></span></li>
    	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'venues', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
    </ul>
	<div class="clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter'); ?>
        </div>
        <?php if(empty($this->request->params['named']['username'])):?>
        <div class="grid_left">
            <?php echo $this->Form->create('Venue', array('class' => 'normal search-form1  search-form', 'action'=>'index', 'type' => 'get'));
            ?>
                 <?php echo $this->Form->input('keyword', array('label' => __l('Venue Name'))); ?>
            	 <?php echo $this->Form->input('user', array('label' => __l('User'),'empty' => __l('All'))); ?>
           
                     <?php echo $this->Form->submit(__l('Search'));?>
          
              <?php	echo $this->Form->end(); ?>
        </div>
        <?php endif;?>
        <?php if(empty($this->request->params['named']['username'])):?>
          <div class="grid_right">
            <?php echo $this->Html->link(__l('Add Venue'), array('controller' => 'venues', 'action' => 'admin_add'),array('class'=>'add', 'title' => __l('Add Venue')));?>
            <?php echo $this->Html->link(__l('Import Venue'), array('controller' => 'venues', 'action' => 'admin_import'),array('class'=>'add', 'title' => __l('Import Venue')));?>
         </div>
         <?php endif;?>
    </div>

<?php
echo $this->Form->create('Venue' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list" id="js-expand-table">
    <tr class="js-even">
        <th class="select"><?php echo __l('Select');?></th>
		<th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Address'),'address');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Venue Owner'),'user_id');?></th>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Revenue (' . Configure::read('site.currency') . ')'),'revenue');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Site Revenue (' . Configure::read('site.currency') . ')'),'site_revenue');?></th>
		<?php } ?>
       </tr>
<?php
if (!empty($venues)):

$i = 0;
foreach ($venues as $venue):
 $class = null;
 $active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($venue['Venue']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
	if($venue['Venue']['admin_suspend']):
		$suspend_class = 'js-checkbox-suspended';
	else:
		$suspend_class = 'js-checkbox-unsuspended';
	endif;

	if($venue['Venue']['is_system_flagged']):
		$flag_class = 'js-checkbox-flagged';
	else:
		$flag_class = 'js-checkbox-unflagged';
	endif;

	if($venue['Venue']['is_feature']):
		$feature_class='js-checkbox-featured';
	else:
		$feature_class='js-checkbox-non-featured';
	endif;

	$status_class = $status_class.' '.$feature_class . ' ' . $suspend_class . ' ' . $flag_class;

?>
	<tr class="<?php echo $class.$active_class;?> expand-row js-odd">
		<td class="<?php echo $class;?> select">
            <div class="arrow"></div><?php echo $this->Form->input('Venue.'.$venue['Venue']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_venue".$venue['Venue']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
	    <td class="dl">
        <div class="status-block">
		<?php
				if($venue['Venue']['admin_suspend']):
					echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
				endif;
				if($venue['Venue']['is_system_flagged']):
					echo '<span class="flagged">'.__l('System Flagged').'</span>';
				endif;
				if($venue['Venue']['is_feature']):
					echo '<span class="featured">'.__l('Featured').'</span>';
				endif;
			?>
			  </div>
        <?php echo $this->Html->link($this->Html->cText($venue['Venue']['name']), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'], 'admin' => false), array('escape' => false));?>
      
        </td>
        <td class="dl">
        <address>
           <span> <?php echo $this->Html->cText($venue['Venue']['address']).',';?></span>
 		   <span> <?php echo ' '.$this->Html->cText($venue['City']['name']).',';?></span>
		   <span><?php echo ' '.$this->Html->cText($venue['Country']['name']).',';?></span>
		   <span><?php echo ' '.$this->Html->cText($venue['Venue']['phone']);?></span>
		</address>
        </td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($venue['User']['username']), array('controller'=> 'users', 'action'=>'view', 'admin' => false, $venue['User']['username']), array('escape' => false));?></td>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
		<td class="dr"><?php echo $this->Html->cFloat($venue['Venue']['revenue']); ?></td>
		<td class="dr site-amount"><?php echo $this->Html->cFloat($venue['Venue']['site_revenue']); ?></td>
		<?php } ?>
  		</tr>
		<tr class="hide">
		     <td colspan="6" class="action-block">
		       <div class="action-info-block clearfix">
                <div class="action-left-block">
                	<h3> <?php echo __l('Action'); ?> </h3>
                	<ul class="action-link clearfix">
                    			<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $venue['Venue']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                    			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venue['Venue']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                    			<?php if($venue['Venue']['is_system_flagged']):?>
                    				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $venue['Venue']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                    				</li>
                    			<?php else:?>
                    				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $venue['Venue']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                    				</li>
                    			<?php endif;?>
                    			<?php if($venue['Venue']['admin_suspend']):?>
                    				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $venue['Venue']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                    				</li>
                    			<?php else:?>
                    				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $venue['Venue']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                    				</li>
                    			<?php endif;?>
   					 </ul>
                </div>
                <div class="action-right-block deal-action-right-block clearfix">
                	  <div class="clearfix">
                                   	  <div class="action-right action-right1">
                                       <h3><?php echo __l('General'); ?></h3>
                                       <dl class="clearfix">
        								   <dt><?php echo __l('Venue Type'); ?></dt><dd><?php echo !empty($venue['VenueType']['name'])?$this->Html->cText($venue['VenueType']['name']):'-'; ?></dd>
        								   <dt><?php echo __l('Email'); ?></dt><dd><?php echo !empty($venue['Venue']['email'])?$this->Html->cText($venue['Venue']['email']):'-'; ?></dd>
        								   <dt><?php echo __l('Phone'); ?></dt><dd><?php echo !empty($venue['Venue']['phone'])?$this->Html->cText($venue['Venue']['phone']):'-'; ?></dd>
        								   <dt><?php echo __l('Website'); ?></dt><dd><?php echo !empty($venue['Venue']['website'])?$this->Html->cText($venue['Venue']['website']):'-'; ?></dd>
                                       </dl>
                                     </div>
                                     <div class="action-right">
                                       <h3><?php echo __l('Business Info'); ?></h3>
                                       <dl class="clearfix">
        								  <dt><?php echo __l('Contact Name'); ?></dt><dd><?php echo !empty($venue['Venue']['contact_name'])?$this->Html->cText($venue['Venue']['contact_name']):'-'; ?></dd>
        								  <dt><?php echo __l('Contact Phone'); ?></dt><dd><?php echo !empty($venue['Venue']['contact_phone'])?$this->Html->cText($venue['Venue']['contact_phone']):'-'; ?></dd>
        								  <dt><?php echo __l('Contact Email'); ?></dt><dd><?php echo !empty($venue['Venue']['contact_email'])?$this->Html->cText($venue['Venue']['contact_email']):'-'; ?></dd>
        								  <dt><?php echo __l('Contact Fax'); ?></dt><dd><?php echo !empty($venue['Venue']['contact_fax'])?$this->Html->cText($venue['Venue']['contact_fax']):'-'; ?></dd>
                                       </dl>
                                    </div>
                        </div>
                </div>
                <div class="action-right action-right-block action-right4">
                      <div class="venues-img-block">
                      <?php echo $this->Html->link($this->Html->showImage('Venue', $venue['Attachment'], array('dimension' => 'admin_listing_thumb','title'=>$this->Html->cText($venue['Venue']['name'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'], false)),'escape'=>false)), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'],'admin'=>false), array('escape' => false),false);?>
                      </div>
                     <dl class="clearfix">
                     <dt><?php echo __l('Added On'); ?></dt><dd><?php echo $this->Html->cDateTime($venue['Venue']['created']);?></dd>
                     <dt><?php echo __l('Events'); ?></dt><dd><?php echo $this->Html->cText($venue['Venue']['event_count']); ?></dd>
                     <dt><?php echo __l('Reviews'); ?></dt><dd><?php echo $this->Html->cText($venue['Venue']['venue_comment_count']); ?></dd>
                     <dt><?php echo __l('Regulars'); ?></dt><dd><?php echo $this->Html->cText($venue['Venue']['venue_user_count']); ?></dd>
                     <dt><?php echo __l('Photos'); ?></dt><dd><?php echo $this->Html->cText($venue['Venue']['photo_count']); ?></dd>
                     <dt><?php echo __l('Videos'); ?></dt><dd><?php echo $this->Html->cText($venue['Venue']['video_count']); ?></dd>
                     <dt><?php echo __l('IP'); ?></dt><dd><?php echo $this->Html->link($this->Html->cText($venue['Ip']['ip'], false),'http://whois.sc/'.$venue['Ip']['ip'], array('target' => 'blank'));?></dd>
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
		<td colspan="4"><p class="notice"><?php echo __l('No venues available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

 <div class="clearfix select-block-bot">
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
    </div>
    <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
	</div>
<?php
if (!empty($venues))  { ?>
    <div class="js-pagination grid_right"> <?php echo $this->element('paging_links'); ?> </div>
<?php } ?>
</div>


<div class="hide">
<?php echo $this->Form->submit();
?>
</div>
    <?php echo $this->Form->end(); ?>
</div>


