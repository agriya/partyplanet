<?php /* SVN: $Id: $ */ ?>
<div class="venueCategories index">
	<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'venue_categories','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'venue_categories','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'venue_categories','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
    <div class="clearfix">
        <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
        <div class="grid_left">
        <?php
        echo $this->Form->create('VenueCategory', array('class' => 'normal search-form', 'action'=>'index', 'type' => 'get'));
        ?>
             <?php echo $this->Form->input('keyword', array('label' => __l('Search name '))); ?>
      
                 <?php echo $this->Form->submit(__l('Search'));?>
         
        <?php echo $this->Form->end(); ?>
        </div>
        <div class="grid_right">
           <?php echo $this->Html->link(__l('Add venue category'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add venue category')));?>
         </div>
    </div>

<?php echo $this->Form->create('VenueCategory' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<table class="list">
    <tr>
        
		<th class="select"><?php echo __l('select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('created'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Active?'),'is_active');?></th>
    </tr>
<?php
if (!empty($venueCategories)):

$i = 0;
foreach ($venueCategories as $venueCategory):
	$class = null;
    $active_class = '';
	if ($i++ % 2 == 0) {
	 $class = 'altrow';
	}
	if($venueCategory['VenueCategory']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
		<td class="select"><?php echo $this->Form->input('VenueCategory.'.$venueCategory['VenueCategory']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$venueCategory['VenueCategory']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                         <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $venueCategory['VenueCategory']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venueCategory['VenueCategory']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					   </ul>
    				</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($venueCategory['VenueCategory']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueCategory['VenueCategory']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($venueCategory['VenueCategory']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No venue categories available');?></p></td>
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
if (!empty($venueCategories)) { ?>
    <div  class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?> </div>
<?php }
?>

</div>
    <?php echo $this->Form->end(); ?>


</div>
