<?php /* SVN: $Id: $ */ ?>
<div class="partyPlanners index">

<ul class="filter-list clearfix">
	<li><span class="active round-5"><?php echo $this->Html->link(__l('Contacted') . ': ' . $this->Html->cInt($active, false), array('controller' => 'party_planners', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Contacted') . ': ' . $this->Html->cInt($active, false)));?></span></li>
	<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Not Contacted') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'party_planners', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Not Contacted') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'party_planners', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
</ul>

    <div class="clearfix">
    <div class="grid_left">
        <?php echo $this->element('paging_counter'); ?>
      </div>
      <div class="grid_left">
      <?php
        echo $this->Form->create('PartyPlanner', array('class' => 'normal search-form1 search-form', 'action'=>'index', 'type' => 'get'));
        ?>
        <?php echo $this->Form->input('keyword', array('label' => __l('Search name '))); ?>
          <?php echo $this->Form->submit(__l('Search'));?>
      <?php	echo $this->Form->end(); ?>
  </div>
  </div>

<?php		
echo $this->Form->create('PartyPlanner' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
?>

<table class="list">
    <tr>
        
		<th class="select"><?php echo __l('Select');?></th>
		<th class="actions"><?php echo __l('Actions');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('email');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('venue');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('date');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('city');?></th>
        <th class="dl"><?php echo $this->Paginator->sort('zip_code');?></th>
        <th class="dc"><?php echo $this->Paginator->sort('Contacted?','is_contacted');?></th>
    </tr>
<?php
if (!empty($partyPlanners)):

$i = 0;
foreach ($partyPlanners as $partyPlanner):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($partyPlanner['PartyPlanner']['is_contacted']):
		$status_class = 'js-checkbox-active';
	else:
        $active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
	<tr class="<?php echo $class.$active_class;?>">
		<td class="select"><?php echo $this->Form->input('PartyPlanner.'.$partyPlanner['PartyPlanner']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$partyPlanner['PartyPlanner']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
		<td class="actions">
		     <div class="action-block">
                <span class="action-information-block">
                    <span class="action-left-block">&nbsp;
                    </span>
                        <span class="action-center-block">
                            <span class="action-info">
                                <?php echo __l('Action');?>
                             </span>
                        </span>
                    </span>
                    <div class="action-inner-block">
                    <div class="action-inner-left-block">
                        <ul class="action-link clearfix">
					        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $partyPlanner['PartyPlanner']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
					        <li><?php echo $this->Html->link(__l('View'), array('controller'=>'party_planners','action' => 'view', $partyPlanner['PartyPlanner']['slug']), array('class' => 'view', 'title' => __l('View')));?></li>
						</ul>
					   </div>
						<div class="action-bottom-block"></div>
					  </div>
				 </div>
       
        </td>
		<td class="dc"><?php echo $this->Html->cDateTime($partyPlanner['PartyPlanner']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($partyPlanner['PartyPlanner']['name']), array('controller'=> 'party_planners', 'action'=>'view', $partyPlanner['PartyPlanner']['slug'],'admin'=>true), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['email']);?></td>
		<td class="dl"><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['venue']);?></td>
		<td class="dc"><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['date']);?></td>
		<td class="dl"><?php echo $this->Html->cText($partyPlanner['City']['name']);?></td>
		<td class="dc"><?php echo $this->Html->cText($partyPlanner['PartyPlanner']['zip_code']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($partyPlanner['PartyPlanner']['is_contacted']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="10"><p class="notice"><?php echo __l('No party planners available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
    <div class="clearfix">
        <div class="admin-select-block grid_left">
             <div>
        		<?php echo __l('Select:'); ?>
        		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
                <?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
                <?php echo $this->Html->link(__l('Not Contacted'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Not Contacted'))); ?>
                <?php echo $this->Html->link(__l('Contacted'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Contacted'))); ?>
            </div>
            <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
        </div>
        <div class="grid_right">
            <?php
            if (!empty($partyPlanners)) {
                echo $this->element('paging_links');
            }
            ?>
        </div>
    </div>
   <?php
        echo $this->Form->end();
        ?>

</div>