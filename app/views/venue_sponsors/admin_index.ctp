<?php /* SVN: $Id: $ */ ?>
<div class="venueSponsors index">
	<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'venue_sponsors','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'venue_sponsors','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'venue_sponsors','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
<?php
    if (empty($requested)):?>
    <div class="clearfix">
        <div class="grid_left"><?php echo $this->element('paging_counter');?></div>
        <div class="grid_left">
            <?php
            echo $this->Form->create('VenueSponsor', array('class' => 'normal search-form1 search-form', 'action'=>'index', 'type' => 'get'));
            ?>
                <?php echo $this->Form->input('filter',array('type'=>'select', 'empty' => __l('All'), 'options'=> $filterActions)); ?>
                <?php echo $this->Form->input('keyword', array('label' => __l('Search venues'))); ?>
                 <?php echo $this->Form->submit(__l('Search'));?>
              <?php	echo $this->Form->end(); ?>
          </div>
           <div class="grid_right">
            	<?php echo $this->Html->link(__l('Add Sponsors'), array('controller' => 'venue_sponsors', 'action' => 'add'),array('class'=>'add'));?>
        	</div>
		</div>
  <?php endif; ?>
<?php		
echo $this->Form->create('VenueSponsor' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
if(!empty($requested)){
  echo $this->Html->link(__l('View more sponsors'), array('controller'=>'venue_sponsors','action' => 'index'), array('title' => __l('View more sponsors')));
}
?>
<table class="list">
    <tr>
       <th class="select"><?php echo __l('Select');?></th>
       <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo __l('Image');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('First Name'),'first_name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Last Name'),'last_name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Email'),'email');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Phone'),'phone');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Description'),'description');?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Active?'),'is_active');?></th>
        </tr>
<?php

if (!empty($venueSponsors)):
$i = 0;
foreach ($venueSponsors as $venueSponsor):
	$class = null;
    $active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($venueSponsor['VenueSponsor']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
		$active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
        <tr class="select <?php echo $class.$active_class;?>">
    		<td><?php echo $this->Form->input('VenueSponsor.'.$venueSponsor['VenueSponsor']['id'].'.id', array('type' => 'checkbox', 'id' => "sponsor_admin_checkbox_".$venueSponsor['VenueSponsor']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                         <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $venueSponsor['VenueSponsor']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                        <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $venueSponsor['VenueSponsor']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
        </td>
		<td>
		<?php 
		 		echo $this->Html->link($this->Html->showImage('VenueSponsor', $venueSponsor['Attachment'], array('dimension' => 'big_thumb','title'=>$this->Html->cText($venueSponsor['VenueSponsor']['first_name'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($venueSponsor['VenueSponsor']['first_name'], false)))), array('controller' => 'venues', 'action' => 'index','sponsor'=>$venueSponsor['VenueSponsor']['slug']), array('escape'=>false), array('inline' => false));
		?>
		</td>
		<td class="dc"><?php echo $this->Html->cDateTime($venueSponsor['VenueSponsor']['created']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueSponsor['VenueSponsor']['first_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueSponsor['VenueSponsor']['last_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueSponsor['VenueSponsor']['email']);?></td>
		<td class="dl"><?php echo $this->Html->cText($venueSponsor['VenueSponsor']['phone']);?></td>
		<td class="dl"><?php echo $this->Html->truncate($this->Html->cText($venueSponsor['VenueSponsor']['description']));?></td>
		<td class="dc"><?php echo $this->Html->cBool($venueSponsor['VenueSponsor']['is_active']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9"><p class="notice"><?php echo __l('No venue sponsors available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<div class="clearfix select-block-bot">
<?php
if (empty($requested)) {?>
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

	
<?php }?>

<?php if (empty($requested)) { ?>

<div class="js-pagination grid_right"><?php echo $this->element('paging_links'); ?></div>

<?php } ?>

</div>

<?php
echo $this->Form->end();
?>
</div>
