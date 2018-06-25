<?php /* SVN: $Id: $ */ ?>
<div class="artcleCategories index">
<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'article_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'article_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'article_categories','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
<div class="clearfix">
    <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
    <div class="grid_left form-content-block">
        <?php
        	echo $this->Form->create('ArticleCategory', array('class' => 'normal search-form1 search-form', 'action'=>'index', 'type' => 'get'));
        	echo $this->Form->input('keyword', array('label' => __l('Search name '))); ?>
            <?php echo $this->Form->submit(); ?>
           <?php echo $this->Form->end(); ?>
    </div>
    <div class="grid_right">
          <?php echo $this->Html->link(__l('Add Article Category'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add Article Category')));?>
    </div>
</div>
<?php		
echo $this->Form->create('ArticleCategory' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
        
		<th class="select"> <?php echo __l('Select'); ?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Article Count'),'article_count');?></th>
    </tr>
<?php
if (!empty($articleCategories)):

$i = 0;
foreach ($articleCategories as $articleCategory):
	$class = null;
    $active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($articleCategory['ArticleCategory']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
		<td class="select"><?php echo $this->Form->input('ArticleCategory.'.$articleCategory['ArticleCategory']['id'] . '.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$articleCategory['ArticleCategory']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $articleCategory['ArticleCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $articleCategory['ArticleCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>


		
        
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($articleCategory['ArticleCategory']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($articleCategory['ArticleCategory']['name']);?></td>
		<td class="dc">
		<?php echo $this->Html->link($this->Html->cInt(count($articleCategory['Article'])), array('controller'=>'articles','action' => 'index','category'=>$articleCategory['ArticleCategory']['id']), array('title' => $articleCategory['ArticleCategory']['article_count'],'escape'=>false));?>
		</td>
	 </tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No article categories available');?></p></td>
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
        </div>
        <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
	</div>
<div class="grid_right"><?php
if (!empty($articleCategories)) {
    echo $this->element('paging_links');
}
?></div>
	
</div>
    <div class="hide">
        <?php echo $this->Form->submit(); ?>
    </div>
    <?php echo $this->Form->end(); ?>

</div>

