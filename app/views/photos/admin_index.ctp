<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="questions index js-response">
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'photos', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'photos', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'photos', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'photos', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="hotties round-5"><?php echo $this->Html->link(__l('Hotties') . ': ' . $this->Html->cInt($hotties, false), array('controller' => 'photos', 'action' => 'index', 'filter_id' => ConstMoreAction::Hotties), array('title' => __l('Hotties') . ': ' . $this->Html->cInt($hotties, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'photos', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
    
   <div class="clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
            <?php echo $this->Form->create('Photo' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
          	<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
            <?php echo $this->Form->submit(__l('Search'));?>
            <?php echo $this->Form->end(); ?>
    	</div>
    	<div class="grid_right">
            <?php //echo $this->Html->link(__l('Add'), array('controller' => 'photos', 'action' => 'add'), array('class' => 'add','title' => __l('Add'))); ?>
    		<?php if(!empty($this->request->params['named']['album']) && ($this->Auth->user('id') == $photoAlbum['PhotoAlbum']['user_id'] || $this->Auth->user('id') == ConstUserTypes::Admin)): ?>
    		<?php echo $this->Html->link(__l('Add More Photos'),array('controller'=>'photos', 'action'=>'add', $photoAlbum['PhotoAlbum']['id'], 'admin' => false),array('class'=> 'add', 'title'=>__l('Add More Photos'))); ?>
    		<?php endif; ?>
        </div>
    </div>
    <?php echo $this->Form->create('Photo' , array('class' => 'normal','action' => 'move_to')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <table class="list">
        <tr>
            <th rowspan="2"><?php echo __l('Select'); ?></th>
            <th rowspan="2"><?php echo __l('Actions'); ?></th>
            <th rowspan="2" class="dl"><?php echo __l('Title'); ?></th>
            <th rowspan="2" class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username'); ?></div></th>
            <?php if (Configure::read('photo.is_allow_photo_album')): ?>
                <th rowspan="2"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Album'), 'PhotoAlbum.name'); ?></div></th>
            <?php endif; ?>
            <th colspan="4" class="dc"><?php echo __l('Count'); ?></th>
            <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Added On'), 'Photo.created'); ?></div></th>
        </tr>
        <tr>
        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'photo_view_count'); ?></div></th>
        <?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
                <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Favorites'), 'photo_favorite_count'); ?></div></th>
            <?php endif; ?>
        <?php if (Configure::read('photo.is_allow_photo_comment')): ?>
                <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Comments'), 'photo_comment_count'); ?></div></th>
            <?php endif; ?>
               <?php if (Configure::read('photo.is_allow_photo_flag')): ?>
                <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Flags'), 'photo_flag_count'); ?></div></th>
            <?php endif; ?>
        </tr>
        <?php
           if (!empty($photos)):
            $i = 0;
            foreach ($photos as $photo):
                 $class = null;
                 $active_class = '';
                if ($i++ % 2 == 0):
                    $class = 'altrow';
                endif;
                if(!$photo['Photo']['is_active']):
                 $active_class = ' inactive-record';
				endif;
				if($photo['Photo']['admin_suspend']):
					$status_class= 'js-checkbox-suspended';
				else:
					$status_class= 'js-checkbox-unsuspended';
				endif;
				if($photo['Photo']['is_system_flagged']):
					$status_class.= ' js-checkbox-flagged';
				else:
					$status_class.= ' js-checkbox-unflagged';
				endif;
				
                ?>
                	<tr class="<?php echo $class.$active_class;?>">
                    <td><?php echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photo['Photo']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                                        <li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'photos', 'action'=>'edit', $photo['Photo']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                                        <li><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $photo['Photo']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                						<?php if($photo['Photo']['is_system_flagged']):?>
                							<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $photo['Photo']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                							</li>
                						<?php else:?>
                							<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $photo['Photo']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                							</li>
                						<?php endif;?>
                						<?php if($photo['Photo']['admin_suspend']):?>
                							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $photo['Photo']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                							</li>
                						<?php else:?>
                							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $photo['Photo']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                							</li>
                						<?php endif;?>
                   					 </ul>
                    			 </div>
                    					<div class="action-bottom-block"></div>
                    				  </div>
                           </div>
                    </td>
                    
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'view', $photo['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                        <span><?php echo $this->Html->link($this->Html->cText($photo['Photo']['title']), array('controller' => 'photos', 'action' => 'view', $photo['Photo']['slug'], 'admin' => false), array('escape' => false));?></span>
                        <span><?php echo $this->Html->truncate($photo['Photo']['description']);?></span>
                    </td>
                    <td class="dl">
						<div class="status-block">
     					 <?php
								if($photo['Photo']['admin_suspend']):
									echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
								endif;
								if($photo['Photo']['is_system_flagged']):
									echo '<span class="flagged">'.__l('System Flagged').'</span>';
								endif;
								if($photo['Photo']['is_hotties']):
									echo '<span class="hotties">'.__l('hotties').'</span>';
								endif;
							
							?>
						</div>
						<?php echo $this->Html->link($this->Html->cText($photo['User']['username']), array('controller'=> 'users', 'action' => 'view', $photo['User']['username'], 'admin' => false), array('escape' => false));?>
								</td>
                    <?php if (Configure::read('photo.is_allow_photo_album')): ?>
                        <td class="dl"><?php echo ($photo['PhotoAlbum']['title']) ? $this->Html->link($this->Html->cText($photo['PhotoAlbum']['title']), array('controller'=> 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug']), array('escape' => false)) : __l(' - '); ?></td>
                    <?php endif; ?>
                       <td class="dc"><?php echo $this->Html->cInt($photo['Photo']['photo_view_count'], false); ?></td>
                     <?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
                        <td class="dc"><?php echo $this->Html->link($this->Html->cInt($photo['Photo']['photo_favorite_count'], false), array('controller' => 'photo_favorites', 'action' => 'index', 'photo' => $photo['Photo']['slug'])); ?></td>
                    <?php endif; ?>
                    <?php if (Configure::read('photo.is_allow_photo_comment')): ?>
                        <td class="dc"><?php echo $this->Html->link($this->Html->cInt($photo['Photo']['photo_comment_count'], false), array('controller' => 'photo_comments', 'action' => 'index', 'photo' => $photo['Photo']['slug'])); ?></td>
                    <?php endif; ?>
                                       <?php if (Configure::read('photo.is_allow_photo_flag')): ?>
                        <td class="dc"><?php echo $this->Html->link($this->Html->cInt($photo['Photo']['photo_flag_count'], false), array('controller' => 'photo_flags', 'action' => 'index', 'photo' => $photo['Photo']['slug'])); ?></td>
                    <?php endif; ?>
                   <td class="dc"><?php echo $this->Html->cDateTime($photo['Photo']['created']);?></td>
				     </tr>
            <?php
            endforeach;
        else:
            ?>
            <tr>
            <td colspan="12"><p class="notice"><?php echo __l('No photos available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    <?php
    if (!empty($photos)):
        ?>
    <div class="clearfix select-block-bot">
    <div class="admin-select-block grid_left">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
			<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
			<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
        </div>
                <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
    </div>
        <div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
</div>
        <div class="hide">
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit('Submit');  ?>
            </div>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>

</div>