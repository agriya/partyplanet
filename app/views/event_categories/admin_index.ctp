<?php /* SVN: $Id: $ */ ?>
<div class="eventCategories index">
   <ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'event_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'event_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'event_categories','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
   <div class="clearfix">
        <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
        <div class="grid_right">
           <?php echo $this->Html->link(__l('Add Event Category'), array('controller' => 'event_categories', 'action' => 'add'),array('title' => __l('Add Event Category'), 'class' => 'add'));?>
        </div>
   </div>
    <?php
echo $this->Form->create('EventCategory' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
       	<th class="select"><?php echo __l('select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
		<th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Description'),'description');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Events'),'event_count');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Active'),'is_active');?></th>
    </tr>
<?php
if (!empty($eventCategories)):

$i = 0;
foreach ($eventCategories as $eventCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
		if($eventCategory['EventCategory']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
		<td class="select"><?php echo $this->Form->input('EventCategory.'.$eventCategory['EventCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$eventCategory['EventCategory']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $eventCategory['EventCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $eventCategory['EventCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					    </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
          
        
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($eventCategory['EventCategory']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($eventCategory['EventCategory']['name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($eventCategory['EventCategory']['description']);?></td>
		<td class="dc"><?php echo $this->Html->link($this->Html->cInt((!empty($eventCategory['EventCategory']['count'])) ? $eventCategory['EventCategory']['count'] : 0), array('controller' => 'events', 'action' => 'index', 'category' => $eventCategory['EventCategory']['slug']), array('escape' => false)); ?></td>
		<td class="dc"><?php echo $this->Html->cBool($eventCategory['EventCategory']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9"><p class="notice"><?php echo __l('No event categories available');?></p></td>
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
	<?php
if (!empty($eventCategories)) { ?>
   <div class="js-pagination grid_right"> <?php echo $this->element('paging_links'); ?></div>
<?php }?>
</div>
    <?php echo $this->Form->end(); ?>
 
</div>
