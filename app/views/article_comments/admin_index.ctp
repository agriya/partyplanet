<?php /* SVN: $Id: $ */ ?>
<div class="articleComments index js-response">
<?php if(empty($this->request->params['named']['article_comment'])):?>
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'article_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Approved), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'article_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Disapproved), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'article_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'article_comments', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'article_comments', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
<?php endif;?>
   <?php echo $this->element('paging_counter');?>
        <?php echo $this->Form->create('ArticleComment'  , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="overflow-block">
<table class="list">
    <tr>
        <th class="select"><?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('user_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('article_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('comment');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('IP'), 'Ip.ip');?></th>
        
    </tr>
<?php
if (!empty($articleComments)):

$i = 0;
foreach ($articleComments as $articleComment):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
	$class = 'altrow';
	}
	if($articleComment['ArticleComment']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;

	if($articleComment['ArticleComment']['admin_suspend']):
		$status_class.=  ' js-checkbox-suspended';
	else:
		$status_class.=  ' js-checkbox-unsuspended';
	endif;
	if($articleComment['ArticleComment']['is_system_flagged']):
		$status_class.= ' js-checkbox-flagged';
	else:
		$status_class.= ' js-checkbox-unflagged';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
       <td class="select"><?php echo $this->Form->input('ArticleComment.'.$articleComment['ArticleComment' ]['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$articleComment['ArticleComment' ]['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                		   <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $articleComment['ArticleComment']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $articleComment['ArticleComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                			<?php if($articleComment['ArticleComment']['is_system_flagged']):?>
                				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $articleComment['ArticleComment']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                				</li>
                			<?php else:?>
                				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $articleComment['ArticleComment']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                				</li>
                			<?php endif;?>
                			<?php if($articleComment['ArticleComment']['admin_suspend']):?>
                				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $articleComment['ArticleComment']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                				</li>
                			<?php else:?>
                				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $articleComment['ArticleComment']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                				</li>
                			<?php endif;?>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		</td>
		<td class="dc"><?php echo $this->Html->cDateTime($articleComment['ArticleComment']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($articleComment['User']['username']), array('controller'=> 'users', 'action'=>'view','admin' => false, $articleComment['User']['username']), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($articleComment['Article']['title'],false), array('controller'=> 'articles', 'action'=>'view', 'admin' => false, $articleComment['Article']['slug']), array('escape' => false));?></td>
		<td class="dl">
        <div class="status-block">
        	<?php
					if($articleComment['ArticleComment']['admin_suspend']):
						echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
					endif;
					if($articleComment['ArticleComment']['is_system_flagged']):
						echo '<span class="flagged">'.__l('System Flagged').'</span>';
					endif;
				?>
        </div>
			<div class="js-desc-to-trucate {len:'90'} truncate-info"><?php echo $this->Html->cText($articleComment['ArticleComment']['comment']);?></div>
		</td>
        <td class="dl">
                         <?php if(!empty($articleComment['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($articleComment['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $articleComment['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$articleComment['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($articleComment['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($articleComment['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $articleComment['Ip']['Country']['name']; ?>">
									<?php echo $articleComment['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($articleComment['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $articleComment['Ip']['City']['name']; ?>    </span>
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
		<td colspan="7"><p class="notice"><?php echo __l('No News Comments available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($articleComments)) {
    ?>
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
    <div class="js-pagination grif_right">
        <?php echo $this->element('paging_links'); ?>
    </div>
  </div>
    <div class="hide">
        <div class="submit-block clearfix">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
    </div>

        <?php
}
    echo $this->Form->end();
    ?>

</div>
