<?php /* SVN: $Id: $ */ ?>
<div class="articles index">
<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'articles', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'articles', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'articles', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'articles', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'articles', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
<div class="clearfix">
    <div class="grid_left">
         <?php echo $this->element('paging_counter'); ?>
    </div>
    <div class="grid_left">
        <?php
        	echo $this->Form->create('Article', array('class' => 'normal search-form1 search-form', 'action'=>'index', 'type' => 'get'));
        	echo $this->Form->input('keyword', array('label' => __l('Search name '))); ?>
        <?php  echo $this->Form->submit(__l('Search'));  ?>
         <?php  echo $this->Form->end();  ?>
    </div>
    <div class="grid_right">
        <?php echo $this->Html->link(__l('Add News'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add Article')));?>
    </div>
</div>
<?php		
echo $this->Form->create('Article' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
        
		<th class="select"><?php echo __l('Select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Category'),'article_category_id');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Comments'),'article_comment_count');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Added On'),'created');?></th>
    </tr>
<?php
if (!empty($articles)):

$i = 0;
foreach ($articles as $article):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
	$class = 'altrow';
	}
	if($article['Article']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
    	$active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
	if($article['Article']['admin_suspend']):
		$status_class.= ' js-checkbox-suspended';
	else:
		$status_class.= ' js-checkbox-unsuspended';
	endif;
	if(!empty($article['Article']['is_system_flagged'])):
		$status_class.= ' js-checkbox-flagged';
	else:
		$status_class.= ' js-checkbox-unflagged';
	endif;
?>
	 <tr class="<?php echo $class.$active_class;?>">
		<td class="select"><?php echo $this->Form->input('Article.'.$article['Article']['id'] . '.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$article['Article']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                			<li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $article['Article']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                			<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $article['Article']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                			<?php if($article['Article']['is_system_flagged']):?>
                				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('action' => 'admin_update_stats', $article['Article']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                				</li>
                			<?php else:?>
                				<li>	<?php echo $this->Html->link(__l('Flag'), array('action' => 'admin_update_stats', $article['Article']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                				</li>
                			<?php endif;?>
                			<?php if($article['Article']['admin_suspend']):?>
                				<li><?php echo $this->Html->link(__l('Unsuspend'), array('action' => 'admin_update_stats', $article['Article']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                				</li>
                			<?php else:?>
                				<li><?php echo $this->Html->link(__l('Suspend'), array('action' => 'admin_update_stats', $article['Article']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                				</li>
                			<?php endif;?>
   					  </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		</td>
	  
		<td class="dl">
			<div class="status-block">
				<?php
					if($article['Article']['admin_suspend']):
						echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
					endif;
					if($article['Article']['is_system_flagged']):
						echo '<span class="flagged">'.__l('System Flagged').'</span>';
					endif;
				?>
			</div>
			<?php echo $this->Html->link($this->Html->cText($article['Article']['title'],false), array('controller'=> 'articles', 'action'=>'view', $article['Article']['slug'],'admin'=>false), array('escape' => false));?>
		</td>
		<td class="dl"><?php echo $this->Html->cText($article['ArticleCategory']['name']);?></td>
    	<td class="dc">
    	<?php echo $this->Html->link($this->Html->cInt($article['Article']['article_comment_count']), array('controller'=>'article_comments','action' => 'index','article_comment'=>$article['Article']['slug']), array('title' => $article['Article']['article_comment_count'],'escape'=>false));?>
    	</td>
        <td class="dc">
        <?php echo $this->Html->cDateTimeHighlight($article['Article']['created']);?></td>
        </td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No articles available');?></p></td>
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
    		<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
    		<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
    	</div>

    	<div><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
    	</div>
        <div class="grid_right"><?php
            if (!empty($articles)) {
                echo $this->element('paging_links');
            }
            ?>
        </div>
   </div>
<div class="hide">
    <?php echo $this->Form->submit(); ?>
</div>
<?php echo $this->Form->end(); ?>


</div>
