<?php /* SVN: $Id: $ */ ?>
<div class="forums index js-response">

<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'forums', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'forums', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'forums', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>
    <div class="clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
            <?php echo $this->Form->create('Forum', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>
           		<?php echo $this->Form->input('filter_id',array('empty' => __l('Please Select'))); ?>
                <?php echo $this->Form->input('keyword', array('label' => 'Keyword')); ?>
            	<?php echo $this->Form->submit(__l('Search'));?>
              	<?php echo $this->Form->end(); ?>
    	</div>
        <div class="grid_right add-event">
        	<?php echo $this->Html->link(__l('Add'), array('controller' => 'forums', 'action' => 'add'), array('class' => 'add', 'title'=>__l('Add'))); ?>
        </div>
    </div>
    <?php echo $this->Form->create('Forum' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
    <tr>
        <th rowspan="2" class="select"><?php echo __l('Select'); ?></th>
        <th rowspan="2" class="actions"><?php echo __l('Actions');?></th>
        <th rowspan="2" class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('title');?></div></th>
        <th rowspan="2" class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
        <th rowspan="2" class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Category'), 'ForumCategory.title');?></div></th>
        <th colspan="2" class="dc"><?php echo __l('Count');?> </th>
        <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('Active', 'is_active');?></div></th>
        <th rowspan="2" class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
    </tr>
    <tr>
    <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Views'), 'forum_view_count');?></div></th>
    <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Comments'), 'forum_comment_count');?></div></th>
    </tr>
<?php
if (!empty($forums)):
$i = 0;
foreach ($forums as $forum): 
	$class = null;
	if ($i++ % 2 == 0) :
		$class = ' class="altrow"';
    endif;
    if($forum['Forum']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
        <td class="select"><?php echo $this->Form->input('Forum.'.$forum['Forum']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$forum['Forum']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                        <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $forum['Forum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $forum['Forum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    			 </div>
    		  <div class="action-bottom-block"></div>
    		</div>
           </div>
        </td>
        <td class="dl"><?php echo $this->Html->link($this->Html->cText($forum['Forum']['title'],false), array('controller'=>'forums','action' => 'view', $forum['Forum']['id'],'admin' => false), array('title' => $this->Html->cText($forum['Forum']['title'],false)));?></td>
        <td class="dl">
            <?php echo $this->Html->link($this->Html->cText($forum['User']['username']), array('controller'=> 'users', 'action'=>'view', $forum['User']['username'],'admin' => false), array('escape' => false));?>
        </td>
		
		<td class="dl">
            <?php echo $this->Html->link($this->Html->cText($forum['ForumCategory']['title'],false), array('controller'=> 'forums', 'action'=>'index', $forum['ForumCategory']['slug'],'admin' => false), array('escape' => false));?>
        </td>
        <td class="dc"><?php echo $this->Html->cInt($forum['Forum']['forum_view_count']);?></td>
		<td class="dc"><?php echo $this->Html->link($this->Html->cInt($forum['Forum']['forum_comment_count']), array('controller' => 'forum_comments', 'action' => 'index', 'forum' => $forum['Forum']['id']), array('escape' => false));?></td>
  		<td class="dc"><?php echo $this->Html->cBool($forum['Forum']['is_active']);?></td>
		<td class="dc"><?php echo $this->Html->cDateTime($forum['Forum']['created']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="11"><p class="notice"><?php echo __l('No Forums available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($forums)) : ?>
 <div class="clearfix select-block-bot">
        <div class="admin-select-block grid_left">
            <div>
        		<?php echo __l('Select:'); ?>
        		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
        		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
        		<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'js-admin-select-pending', 'title' => __l('Inactive'))); ?>
        		<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'js-admin-select-approved', 'title' => __l('Active'))); ?>
        	</div>
        	<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
        </div>
    	<div class="js-pagination grid_right">
            <?php echo $this->element('paging_links'); ?>
        </div>
    </div>
    <div class="hide">
 
	    <?php echo $this->Form->submit('Submit'); ?>
 
    </div>
    <?php
endif; ?>
        <?php echo $this->Form->end(); ?>
   
</div>
