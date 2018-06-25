<?php /* SVN: $Id: $ */ ?>
<div class="eventSponsors index">
	<ul class="filter-list clearfix">
		<li><span class="active round-5"><?php echo $this->Html->link(__l('Active'). ': ' . $this->Html->cInt($active_count, false),array('controller'=>'event_sponsors','action'=>'index','filter_id' => ConstMoreAction::Active), array('title' => __l('Active')));?>
		</span></li>
		<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive'). ': ' . $this->Html->cInt($inactive_count, false), array('controller'=>'event_sponsors','action'=>'index','filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive')));?>
		</span></li>
		<li><span class="all round-5"><?php echo $this->Html->link(__l('Total'). ': ' . $this->Html->cInt($total_count, false), array('controller'=>'event_sponsors','action'=>'index'), array('title' => __l('Total')));?>
		</span></li>
	</ul>
<?php
    if (empty($requested)):?>
    <div class="clearfix">
    <div class="grid_left">
        <?php echo $this->element('paging_counter');?>
    </div>
    <div class="grid_left">
        <?php
        echo $this->Form->create('EventSponsor', array('class' => 'normal search-form1 search-form', 'action'=>'index', 'type' => 'get'));
        ?>
             <?php echo $this->Form->input('keyword', array('label' => __l('Search events'))); ?>
        	 <?php echo $this->Form->input('user', array('empty' => __l('Please Select'), 'label' => __l('User'))); ?>
             <?php echo $this->Form->submit(__l('Search'));?>
             <?php	echo $this->Form->end(); ?>
      </div>
	   <div class="grid_right">
  	     	<?php echo $this->Html->link(__l('Add Sponsors'), array('controller' => 'event_sponsors', 'action' => 'add'),array('class'=>'add'));?>
		</div>
    </div>
  <?php endif; ?>
<?php		
echo $this->Form->create('EventSponsor' , array('class' => 'normal','action' => 'update'));
echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
if(!empty($requested)){
  echo $this->Html->link(__l('View more sponsors'), array('controller'=>'event_sponsors','action' => 'index'), array('title' => __l('View more sponsors')));
}
?>
<table class="list">
    <tr>
       <th class="select"><?php echo __l('select');?></th>
       <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo __l('Image');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'created');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('User'),'user_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Name'),'name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Description'),'description');?></th>
    </tr>
<?php
if (!empty($eventSponsors)):
$i = 0;
foreach ($eventSponsors as $eventSponsor):
	$class = null;
	$active_class = '';
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
	if($eventSponsor['EventSponsor']['is_active']):
		$status_class = 'js-checkbox-active';
	else:
    	$active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
	endif;
?>
<tr class="<?php echo $class.$active_class;?>">
	<td class="select"><?php echo $this->Form->input('EventSponsor.'.$eventSponsor['EventSponsor']['id'].'.id', array('type' => 'checkbox', 'id' => "sponsor_admin_checkbox_".$eventSponsor['EventSponsor']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $eventSponsor['EventSponsor']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $eventSponsor['EventSponsor']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                        </ul>
    				</div>
    					<div class="action-bottom-block"></div>
    				  </div>
           </div>
       </td>
		<td>
		<?php 
		 		echo $this->Html->link($this->Html->showImage('EventSponsor', $eventSponsor['Attachment'], array('dimension' => 'big_thumb','title'=>$this->Html->cText($eventSponsor['EventSponsor']['name'], false),'escape'=>false,'alt'=>sprintf('[Image: %s]', $this->Html->cText($eventSponsor['EventSponsor']['name'], false)))), array('controller' => 'events', 'action' => 'index','sponsor'=>$eventSponsor['EventSponsor']['slug']), array('escape'=>false), array('inline' => false));
		?>
		</td>
		<td class="dc"><?php echo $this->Html->cDateTime($eventSponsor['EventSponsor']['created']);?></td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($eventSponsor['User']['username']), array('controller'=> 'users', 'action'=>'view', 'admin' => false, $eventSponsor['User']['username']), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->cText($eventSponsor['EventSponsor']['name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($eventSponsor['EventSponsor']['description']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7"><p class="notice"><?php echo __l('No event sponsors available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (empty($requested)) {?>
<div class="clearfix select-block-bot">
	<div class="admin-select-block grid_left">
     <div class="grid_left">
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
        <?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
        <?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Inactive'))); ?>
        <?php echo $this->Html->link(__l('Active'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Active'))); ?>
    </div>
    <div class="admin-checkbox-button grid_left"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
	</div>

<?php
if (empty($requested)) { ?>
  <div class="grid_right"><?php  echo $this->element('paging_links'); ?> </div>
<?php }?>
</div>


<?php }?>
<div class="hide">
    <?php echo $this->Form->submit(); ?>
</div>
<?php echo $this->Form->end(); ?>

</div>