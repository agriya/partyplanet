<?php /* SVN: $Id: admin_index.ctp 801 2009-07-25 13:22:35Z boopathi_026ac09 $ */ ?>
<div class="photoComments index js-response">
<?php if(empty($this->request->params['named']['photo'])):?>
<ul class="filter-list clearfix">
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'photo_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'photo_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($all, false), array('controller' => 'photo_comments', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($all, false)));?></span></li>
</ul>
<?php endif;?>
    <div class="clearfix">
       <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
       </div>
        <?php if (!(isset($this->request->params['isAjax']) && $this->request->params['isAjax'] == 1)): ?>
            <div class="form-content-block grid_left">
                <?php echo $this->Form->create('PhotoComment' , array('type' => 'get', 'class' => 'normal search-form','action' => 'index')); ?>
                <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
                <?php echo $this->Form->submit(__l('Search'));?>
                <?php echo $this->Form->end(); ?>
            </div>
        <?php endif; ?>
    </div>
    <?php echo $this->Form->create('PhotoComment' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
    <div class="overflow-block">
	<table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions');?></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('photo_id');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('user_id');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('comment');?></div></th>
	        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></div></th>

        </tr>
        <?php
        if (!empty($photoComments)):
            $i = 0;
            foreach ($photoComments as $photoComment):
                $class = null;
               	if ($i++ % 2 == 0) :
                    $class = 'altrow';
                endif;
				if($photoComment['PhotoComment']['admin_suspend']):
					$status_class= 'js-checkbox-suspended';
				else:
					$status_class= 'js-checkbox-unsuspended';
				endif;
				if($photoComment['PhotoComment']['is_system_flagged']):
					$status_class.= ' js-checkbox-flagged';
				else:
					$status_class.= ' js-checkbox-unflagged';
				endif;
				 ?>
                <tr class="<?php echo $class;?>">
                    <td class="select"><?php echo $this->Form->input('PhotoComment.'.$photoComment['PhotoComment']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$photoComment['PhotoComment']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                						<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $photoComment['PhotoComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                						<?php if($photoComment['PhotoComment']['is_system_flagged']):?>
                							<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $photoComment['PhotoComment']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                							</li>
                						<?php else:?>
                							<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $photoComment['PhotoComment']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                							</li>
                						<?php endif;?>
                						<?php if($photoComment['PhotoComment']['admin_suspend']):?>
                							<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $photoComment['PhotoComment']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                							</li>
                						<?php else:?>
                							<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $photoComment['PhotoComment']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                							</li>
                						<?php endif;?>
               					 </ul>
                					</div>
                					<div class="action-bottom-block"></div>
                				  </div>
                      </div>
					</td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($photoComment['PhotoComment']['created']);?></td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->showImage('Photo', $photoComment['Photo']['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photoComment['Photo']['title'], false)), 'title' => $this->Html->cText($photoComment['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'view', $photoComment['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                        <?php echo $this->Html->link($this->Html->cText($photoComment['Photo']['title']), array('controller'=> 'photos', 'action'=>'view', $photoComment['Photo']['slug'], 'admin' => false), array('escape' => false));?>
                    </td>
                    <td class="dl">
                        <?php echo $this->Html->link($this->Html->cText($photoComment['User']['username']), array('controller'=> 'users', 'action'=>'view', $photoComment['User']['username'],  'admin' => false), array('escape' => false));?>
                               </td>
                    <td class="dl">
						<div class="status-block">
							<?php
								if($photoComment['PhotoComment']['admin_suspend']):
									echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
								endif;
								if($photoComment['PhotoComment']['is_system_flagged']):
									echo '<span class="flagged">'.__l('System Flagged').'</span>';
								endif;
							?>
						</div>
					<div class="js-desc-to-trucate {len:'90'}"><?php echo $this->Html->cText($photoComment['PhotoComment']['comment']);?></div>
					</td>
                    <td class="dl">
                         <?php if(!empty($photoComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($photoComment['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $photoComment['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$photoComment['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($photoComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($photoComment['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $photoComment['Ip']['Country']['name']; ?>">
									<?php echo $photoComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($photoComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $photoComment['Ip']['City']['name']; ?>    </span>
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
                <td colspan="8"><p class="notice"><?php echo __l('No Photo Comments available');?></p></td>
            </tr>
            <?php
        endif;
        ?>
    </table>
    </div>
    <?php
    if (!empty($photoComments)) :
        ?>
    <div class="clearfix select-block-bot">
        <div class="admin-select-block grid_left">
            <div class="grid_left">
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
            <div class="submit-block clearfix">
                <?php echo $this->Form->submit('Submit');  ?>
            </div>
        </div>

        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>
