<?php /* SVN: $Id: $ */ ?>
<div class="forumCategories index">
 <ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'forum_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'forum_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'forum_categories','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
 <div class="clearfix">
    <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
    <div class="grid_left">
        <?php echo $this->Form->create('ForumCategory', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>

    			<?php echo $this->Form->input('filter_id',array('empty' => __l('Please Select'))); ?>
                <?php echo $this->Form->input('keyword', array('label' => 'Keyword')); ?>

    			<?php echo $this->Form->submit(__l('Search'));?>

    	<?php echo $this->Form->end(); ?>
    </div>
    <div class="grid_right add-event">
    	<?php echo $this->Html->link(__l('Add'), array('controller' => 'forum_categories', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
    </div>
</div>

<?php echo $this->Form->create('ForumCategory' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
    <tr>
        <th class="select"><?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('created');?></div></th>
        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('title');?></div></th>
        <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort('description');?></div></th>
        <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort('forum_count');?></div></th>
    </tr>
<?php
if (!empty($forumCategories)):

$i = 0;
foreach ($forumCategories as $forumCategory):
	$class = null;
    $active_class = '';
	if ($i++ % 2 == 0) :
			$class = 'altrow';
    endif;
	if($forumCategory['ForumCategory']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
	$active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
        <td class="select"><?php echo $this->Form->input('ForumCategory.'.$forumCategory['ForumCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$forumCategory['ForumCategory']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                        <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $forumCategory['ForumCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $forumCategory['ForumCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    			 </div>
    					<div class="action-bottom-block"></div>
    				  </div>
           </div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($forumCategory['ForumCategory']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($forumCategory['ForumCategory']['title'],false), array('controller' => 'forums', 'action' => 'index', $forumCategory['ForumCategory']['slug'],'admin' => false), array('title' => $forumCategory['ForumCategory']['title']));?></td>
		<td class="dl"><?php echo $this->Html->truncate($forumCategory['ForumCategory']['description']);?></td>
		<td class="dc"><?php echo $this->Html->link($this->Html->cInt((!empty($forumCategory['ForumCategory']['count'])) ? $forumCategory['ForumCategory']['count'] : 0), array('controller' => 'forums', 'action' => 'index', 'category' => $forumCategory['ForumCategory']['id']), array('escape' => false));?></td>
		</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No Forum Categories available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($forumCategories)) : ?>
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
endif;
echo $this->Form->end();
?>
</div>
