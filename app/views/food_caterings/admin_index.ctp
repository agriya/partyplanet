<?php /* SVN: $Id: $ */ ?>
<div id="breadcrumb">
<?php $this->Html->addCrumb(__l('Food caterings')); ?>
</div>
<div class="foodCaterings index">
<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'food_caterings','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'food_caterings','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'food_caterings','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
<p class="add-event clearfix">
<div class="clearfix grid_right">
<?php echo $this->Html->link(__l('Add food catering'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add food catering')));?>
</div>
</p>
<?php echo $this->element('paging_counter');?>
<?php		
echo $this->Form->create('FoodCatering' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
        
		<th class="select"><?php echo __l('select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('name');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('Active','is_active');?></th>
    </tr>
<?php
if (!empty($foodCaterings)):

$i = 0;
foreach ($foodCaterings as $foodCatering):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($foodCatering['FoodCatering']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
			<td><?php echo $this->Form->input('FoodCatering.'.$foodCatering['FoodCatering']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$foodCatering['FoodCatering']['id'], 'label' => false, 'class' => $status_class)); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $foodCatering['FoodCatering']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $foodCatering['FoodCatering']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
       					 </ul>
        			 </div>
    				<div class="action-bottom-block"></div>
    				  </div>
           </div>


      

        </td>
		<td class="dl"><?php echo $this->Html->cText($foodCatering['FoodCatering']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($foodCatering['FoodCatering']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No food caterings available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($foodCaterings)) {
    echo $this->element('paging_links');
}
?>

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
        <div class="grid_rigt"><?php
if (!empty($foodCaterings)) {
    echo $this->element('paging_links');
}
?></div>
</div>

	       <?php echo $this->Form->end(); ?>
</div>