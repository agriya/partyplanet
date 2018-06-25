<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="photoAlbums index js-response">
<?php if(empty($this->request->params['named']['venue_photo'])&& empty($this->request->params['named']['event_photo'])): ?>
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Approved') . ': ' . $this->Html->cInt($active, false), array('controller' => 'photo_albums', 'action' => 'index', 'filter_id' => ConstMoreAction::Active, 'type' => $this->request->params['named']['type']), array('title' => __l('Approved') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Disapproved') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'photo_albums', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive, 'type' => $this->request->params['named']['type']), array('title' => __l('Disapproved') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'photo_albums', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged, 'type' => $this->request->params['named']['type']), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'photo_albums', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend, 'type' => $this->request->params['named']['type']), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive + $suspended, false), array('controller' => 'photo_albums', 'action' => 'index', 'type' => $this->request->params['named']['type']), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive +$suspended, false)));?></span></li>
</ul>
<?php endif;?>
      <?php
       if(empty($this->request->params['named']['venue_photo'])&& empty($this->request->params['named']['event_photo'])&& empty($this->request->params['named']['venue'])&& empty($this->request->params['named']['event'])&& empty($this->request->params['named']['username'])):?>
	<div class="clearfix">
     <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
    	<div class="grid_left">
        <?php echo $this->Form->create('PhotoAlbum' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
        
        			<?php echo $this->Form->input('keyword', array('label' => 'Keyword')); ?>
    				<?php
    				if(!empty($this->request->params['named']['type'])){
    					echo $this->Form->input('type', array('type'=>'hidden','value' =>$this->request->params['named']['type'] ));
    				}
    				?>
        	
            			<?php echo $this->Form->submit(__l('Search'));?>
              
        <?php echo $this->Form->end(); ?>
        </div>
       <div class="grid_right">
    		<?php
			if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue') {
				echo $this->Html->link(__l('Add venue photo album'), array('controller' => 'photo_albums', 'action' => 'add', 'type' => 'venue'), array('class' => 'add', 'title' => __l('Add venue photo album')));
			} elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event') {
				echo $this->Html->link(__l('Add event photo album'), array('controller' => 'photo_albums', 'action' => 'add', 'type' => 'event'), array('class' => 'add', 'title' => __l('Add event photo album')));
			} else {
				echo $this->Html->link(__l('Add photo album'), array('controller' => 'photo_albums', 'action' => 'add'), array('class' => 'add', 'title' => __l('Add photo album')));
			}
		?>

    	</div>
    </div>
	<?php endif;?>
    <?php echo $this->Form->create('PhotoAlbum' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <div class="overflow-block">
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
			<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue'): ?>
		    <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Venue'), 'Venue.name');?></div></th>
			<?php elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event'): ?>
	        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Event'), 'Event.title');?></div></th>
			<?php endif; ?>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Title'),'title');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Description'),'description');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Photos'),'photo_count');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>
            </tr>
        <?php
          if (!empty($photoAlbums)):
            $i = 0;
            foreach ($photoAlbums as $photoAlbum):
               	$class = null;
				$active_class = '';
                if ($i++ % 2 == 0) :
                   	$class = 'altrow';
                endif;
				if($photoAlbum['PhotoAlbum']['admin_suspend']):
					$suspend_class = ' js-checkbox-suspended';
				else:
					$suspend_class = ' js-checkbox-unsuspended';
				endif;
                 if(!$photoAlbum['PhotoAlbum']['is_active']):
                   	  $active_class = ' inactive-record';
                 endif;
				if($photoAlbum['PhotoAlbum']['is_system_flagged']):
					$flag_class = ' js-checkbox-flagged';
				else:
					$flag_class = ' js-checkbox-unflagged';
				endif;

				$status_class = ' ' . $suspend_class . ' ' . $flag_class;
                ?>
                 <tr class="<?php echo $class.$active_class;?>">
                    <td><?php echo $this->Form->input('PhotoAlbum.'.$photoAlbum['PhotoAlbum']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photoAlbum['PhotoAlbum']['id'], 'label' => false, 'class' => $status_class . ' js-checkbox-list')); ?></td>
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
						<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $photoAlbum['PhotoAlbum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
						<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoAlbum['PhotoAlbum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
						<li><?php echo $this->Html->link(__l('Add More Photos'),array('controller'=>'photos', 'action'=>'add', $photoAlbum['PhotoAlbum']['id'], 'admin' => false),array( 'class'=>'add','title'=>__l('Add More Photos'))); ?></li>
						<?php if($photoAlbum['PhotoAlbum']['is_system_flagged']):?>
							<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $photoAlbum['PhotoAlbum']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
							</li>
						<?php else:?>
							<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $photoAlbum['PhotoAlbum']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
							</li>
						<?php endif;?>
						<?php if($photoAlbum['PhotoAlbum']['admin_suspend']):?>
							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $photoAlbum['PhotoAlbum']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
							</li>
						<?php else:?>
							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $photoAlbum['PhotoAlbum']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
							</li>
						<?php endif;?>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>

				</td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->cText($photoAlbum['User']['username']), array('controller'=> 'users', 'action'=>'view', $photoAlbum['User']['username'], 'admin' => false), array('escape' => false));?>
                              </td>
					<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue'): ?>
					<td class="dl">
							<?php if (!empty($photoAlbum['Venue']['name'])): ?>
				                <?php echo $this->Html->link($this->Html->cText($photoAlbum['Venue']['name'],false), array('controller' => 'venues', 'action' => 'view', $photoAlbum['Venue']['slug'], 'admin' => false));?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
					<?php if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event'): ?>
						<td class="dl">
							<?php if (!empty($photoAlbum['Event']['title'])): ?>
								<?php echo $this->Html->link($this->Html->cText($photoAlbum['Event']['title'],false), array('controller' => 'events', 'action' => 'view', $photoAlbum['Event']['slug'], 'admin' => false));?>
							<?php endif; ?>
						</td>
					<?php endif; ?>
                    <td class="dl">
						<div class="status-block">
							<?php
								if($photoAlbum['PhotoAlbum']['admin_suspend']):
									echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
								endif;
								if($photoAlbum['PhotoAlbum']['is_system_flagged']):
									echo '<span class="flagged">'.__l('System Flagged').'</span>';
								endif;
							?>
						</div>
						<?php echo $this->Html->link($this->Html->cText($photoAlbum['PhotoAlbum']['title'],false), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']));?>
					</td>
                    <td class="dl"><?php echo $this->Html->truncate($photoAlbum['PhotoAlbum']['description']);?></td>
                    <td class="dc"><?php echo $this->Html->link($this->Html->cInt($photoAlbum['PhotoAlbum']['photo_count'], false), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']));?></td>
                    <td class="dl">
                         <?php if(!empty($photoAlbum['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($photoAlbum['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $photoAlbum['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$photoAlbum['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($photoAlbum['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($photoAlbum['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $photoAlbum['Ip']['Country']['name']; ?>">
									<?php echo $photoAlbum['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($photoAlbum['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $photoAlbum['Ip']['City']['name']; ?>    </span>
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
                <td colspan="9"><p class="notice"><?php echo __l('No Photo Albums available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($photoAlbums)) :
        ?>
        <div class="clearfix select-block-bot">
        <div class="admin-select-block grid_left">
            <div class="grid_left admin-select-link">
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
    			<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
    			<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
			</div>
	   		<div class="admin-checkbox-button grid_left">
                <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
            </div>
        </div>
        <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
    </div>
        <div class="hide">
                <?php echo $this->Form->submit('Submit');  ?>
          </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>


</div>