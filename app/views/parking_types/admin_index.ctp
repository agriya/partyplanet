<?php /* SVN: $Id: $ */ ?>
<div class="parkingTypes index">
        <ul class="filter-list clearfix">
            <?php if (empty($this->request->params['named']['username'])):?>
 			<li><span class="active round-5"><?php echo $this->Html->link(__l('Active Parking Types'). ': ' . $this->Html->cInt($active_parking_types, false),array('controller'=>'parking_types','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active Parking Types')));?>
			</span></li>
           	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive Parking Types'). ': ' . $this->Html->cInt($inactive_parking_types, false), array('controller'=>'parking_types','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive Parking Types')));?>
            </span></li>
           	<li><span class="all round-5"><?php echo $this->Html->link(__l('Total Parking Types'). ': ' . $this->Html->cInt($total_parking_types, false), array('controller'=>'parking_types','action'=>'index'), array('title' => __l('Total Parking Types')));?>
    		</span></li>
			<?php endif;?>
        </ul>
        <div class="clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
        </div>
            <div class="grid_left">
            <?php
            	echo $this->Form->create('ParkingType', array('class' => 'normal search-form search-form1', 'action'=>'index', 'type' => 'get'));
            	echo $this->Form->input('keyword', array('label' => __l('Search name '))); ?>
               <?php echo $this->Form->submit(__l('Search')); ?>
        	 <?php echo $this->Form->end();
                ?>
      </div>
        <div class="grid_right">
            <?php echo $this->Html->link(__l('Add parking type'), array('action'=>'add'), array('class' => 'add', 'title' => __l('Add parking type')));?>
        </div>
        </div>
        

<?php		
echo $this->Form->create('ParkingType' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>
<div class="overflow-block">
<table class="list">
    <tr>
        
		<th class="select"><?php echo __l('Select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
        <th><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Active'),'is_active');?></th>
    </tr>
<?php
if (!empty($parkingTypes)):

$i = 0;
foreach ($parkingTypes as $parkingType):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if($parkingType['ParkingType']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr<?php echo $class;?>>
		<td><?php echo $this->Form->input('ParkingType.'.$parkingType['ParkingType']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$parkingType['ParkingType']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $parkingType['ParkingType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $parkingType['ParkingType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
              </div>
        </td>
		<td><?php echo $this->Html->cDateTime($parkingType['ParkingType']['created']);?></td>
		<td><?php echo $this->Html->cText($parkingType['ParkingType']['name']);?></td>
		<td><?php echo $this->Html->cBool($parkingType['ParkingType']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No parking types available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>

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
    if (!empty($parkingTypes)) { ?>
       <div class="grid_right">
            <?php    echo $this->element('paging_links'); ?>
        </div>
    <?php } ?>
<?php
echo $this->Form->end();
?>

</div>