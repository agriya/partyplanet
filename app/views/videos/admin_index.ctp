<?php /* SVN: $Id: admin_index.ctp 1010 2009-10-05 10:03:50Z siva_063at09 $ */ ?>
<div class="videos index js-response">

<?php if(empty($this->request->params['named']['venue_video'])&& empty($this->request->params['named']['event_video'])): ?>

<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Approved') . ': ' . $this->Html->cInt($active, false), array('controller' => 'videos', 'action' => 'index', 'filter_id' => ConstMoreAction::Approved), array('title' => __l('Approved') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Disapproved') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'videos', 'action' => 'index', 'filter_id' => ConstMoreAction::Disapproved), array('title' => __l('Disapproved') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'videos', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'videos', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'videos', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>

<div class="clearfix">
	<div class="grid_left">
		<?php echo $this->element('paging_counter');?>
	</div>
	<div class="grid_left">
	<?php echo $this->Form->create('Video' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
		<?php echo $this->Form->input('filter_id',array('empty' => __l('Please Select'))); ?>
		<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
		<?php echo $this->Form->submit(__l('Search'));?>
	   <?php echo $this->Form->end(); ?>
	</div>
	<div class="grid_right">
		<?php echo $this->Html->link(__l('Upload Videos'), array('controller' => 'videos', 'action' => 'add'), array('class' => 'add','title' => __l('Upload Videos'))); ?>
	</div>
</div>

<?php endif; ?>

    <?php echo $this->Form->create('Video' , array('class' => 'normal', 'action' => 'move_to')); ?>
      <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
 <div class="overflow-block">
   <table class="list">
        <tr>
            <th class="select" rowspan="2"><?php echo __l('Select'); ?></th>
            <th rowspan="2"><?php echo __l('Actions'); ?></th>
            <th rowspan="2" class="dl"><?php echo __l('Video'); ?></th>
            <th rowspan="2" class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username'); ?></div></th>
            <th rowspan="2" class="dc"><div><?php echo __l('Event'); ?></div></th>
			<th rowspan="2" class="dc"><div><?php echo __l('Venue'); ?></div></th>
			<th colspan="4" class="dc"><?php echo __l('Count'); ?></th>
			<th rowspan="2" class="dc"><div><?php echo __l('Featured'); ?></div></th>
        </tr>
        <tr>
			<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'video_view_count'); ?></div></th>
			<?php if (Configure::read('Video.is_enable_video_flags')): ?>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Flags'), 'video_flag_count'); ?></div></th>
			<?php endif; ?>
			<?php  if (Configure::read('Video.is_enable_video_comments')): ?>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Comments'), 'video_comment_count'); ?></div></th>
			<?php endif; ?>
			<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
				<th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Favorites'), 'video_favorite_count'); ?></div></th>
			<?php endif; ?>
        </tr>
        <?php
        if (!empty($videos)):
            $i = 0;
            foreach ($videos as $video):
                $class = null;
                $active_class = '';
                if ($i++ % 2 == 0):
                   $class = 'altrow';
                endif;
				if($video['Video']['is_approved']):
					$status_class = 'js-checkbox-active';
				else:
      			    $active_class = ' inactive-record';
					$status_class = 'js-checkbox-inactive';
				endif;
				if($video['Video']['is_featured']):
					$featured_status_class = 'js-checkbox-featured';
				else:
					$featured_status_class = 'js-checkbox-notfeatured';
				endif;
				if($video['Video']['admin_suspend']):
					$status_class.= ' js-checkbox-suspended';
				else:
					$status_class.= ' js-checkbox-unsuspended';
				endif;
				if($video['Video']['is_system_flagged']):
					$status_class.= ' js-checkbox-flagged';
				else:
					$status_class.= ' js-checkbox-unflagged';
				endif;
		?>
        <tr class="<?php echo $class.$active_class;?>">
            <td class="select"><?php echo $this->Form->input('Video.'.$video['Video']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$video['Video']['id'], 'label' => false, 'class' => $status_class . ' ' . $featured_status_class . ' js-checkbox-list')); ?></td>
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
                                <li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'videos', 'action'=>'edit', $video['Video']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $video['Video']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
        						<?php if($video['Video']['is_system_flagged']):?>
        							<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $video['Video']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
        							</li>
        						<?php else:?>
        							<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $video['Video']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
        							</li>
        						<?php endif;?>
        						<?php if($video['Video']['admin_suspend']):?>
        							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $video['Video']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
        							</li>
        						<?php else:?>
        							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $video['Video']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
        							</li>
        						<?php endif;?>
       					 </ul>
        					</div>
        					<div class="action-bottom-block"></div>
        				  </div>
                     </div>


                    </td>
                         <td class="dl">
                        <?php
							$video['Thumbnail']['id'] = (!empty($video['Video']['default_thumbnail_id'])) ? $video['Video']['default_thumbnail_id'] : '';
							echo $this->Html->link($this->Html->showImage('Video', $video['Thumbnail'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($video['Video']['title'], false)), 'title' => $this->Html->cText($video['Video']['title'], false))), array('controller' => 'videos', 'action' => 'view', $video['Video']['slug'], 'admin' => false), array('escape' => false));
						?>
                        <span><?php echo $this->Html->link($this->Html->cText($video['Video']['title']), array('controller' => 'videos', 'action' => 'view', $video['Video']['slug'], 'admin' => false), array('escape' => false));?></span>
                    </td>
                    <td class="dl">
					   <div class="status-block">
							<?php
								if($video['Video']['admin_suspend']):
									echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
								endif;
								if($video['Video']['is_system_flagged']):
									echo '<span class="flagged">'.__l('System Flagged').'</span>';
								endif;
							?>
						</div>
						<?php echo $this->Html->link($this->Html->cText($video['User']['username']), array('controller'=> 'users', 'action' => 'view', $video['User']['username'], 'admin' => false), array('escape' => false));?>
						</td>
					     <td class="dl"><?php
					if($video['Video']['class'] =='Event'){
						echo $this->Html->link($this->Html->cText($video['Event']['title']), array('controller'=> 'events', 'action' => 'view', $video['Event']['slug'], 'admin' => false), array('escape' => false));
					}else{
						echo '--';
					}

					?>
					</td>
				  <td class="dl"><?php
					if($video['Video']['class'] =='Venue'){
						echo $this->Html->link($this->Html->cText($video['Venue']['name'],false), array('controller'=> 'venues', 'action' => 'view', $video['Venue']['name'], 'admin' => false), array('escape' => false));
					}else{
						echo '--';
					}

					?>
				</td>
				<td class="dc"><?php echo $this->Html->cInt($video['Video']['video_view_count'], false);?></td>
				<?php if (Configure::read('Video.is_enable_video_flags')): ?>
					<td class="dc"><?php echo $this->Html->link($this->Html->cInt($video['Video']['video_flag_count'], false), array('controller' => 'video_flags', 'action' => 'index', 'video' => $video['Video']['slug'])); ?></td>
				<?php endif; ?>
				<?php if (Configure::read('Video.is_enable_video_comments')): ?>
					<td class="dc"><?php echo $this->Html->link($this->Html->cInt($video['Video']['video_comment_count'], false), array('controller' => 'video_comments', 'action' => 'index', 'video' => $video['Video']['slug'])); ?></td>
				<?php endif; ?>
				<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
					<td class="dc"><?php echo $this->Html->cInt($video['Video']['video_favorite_count'], false); ?></td>
				<?php  endif; ?>
				    <td class="dc"><?php echo $this->Html->cBool($video['Video']['is_featured']);?></td>
			</tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
            <td colspan="11"><p class="notice"><?php echo __l('No videos available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>

    <?php
    if (!empty($videos)):
        ?>
    <div class="clearfix select-block-bot">
        <div class="admin-select-block grid_left">
            <div class="grid_left admin-select-link">
                <?php echo __l('Select:'); ?>
                <?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
                <?php echo $this->Html->link(__l('Disapproved'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Disapproved'))); ?>
                <?php echo $this->Html->link(__l('Approved'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Approved'))); ?>
                <?php echo $this->Html->link(__l('Featured'), '#', array('class' => 'select js-admin-select-featured', 'title' => __l('Featured'))); ?>
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