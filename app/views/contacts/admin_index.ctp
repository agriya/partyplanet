<?php /* SVN: $Id: $ */ ?>
<?php if(!isset($this->request->params['named']['main_filter_id']) && empty($this->request->data)) : ?>
	<div class="js-tabs">
		<ul class="clearfix">
			<li><?php echo $this->Html->link(__l('All'), array('controller' => 'contacts', 'action' => 'index', 'main_filter_id' => 'all'),array('title' => __l('All')));?></li>
			
		<?php foreach($contactTypes As  $key => $contactType) { ?>
			<li><?php echo $this->Html->link($this->Html->cText($contactType, false), array('controller' => 'contacts', 'action' => 'index', 'main_filter_id' => $key),array('title' => $this->Html->cText($contactType, false)));?></li>
		<?php } ?>
	   </ul>       
	</div>
<?php else: ?>
	<div class="contacts index js-response">
    <div class="clearfix">
        <div class="grid_left">
            <?php echo $this->element('paging_counter'); ?>
    	</div>
    	 <div class="grid_left">
    		<?php echo $this->Form->create('Contact', array('class' => 'normal search-form  js-ajax-form', 'action'=>'index', 'type' => 'post'));
    	       ?>
    		<?php echo $this->Form->input('contact_type_id',array('type'=>'hidden', 'value' => $this->request->params['named']['main_filter_id'])); ?>
    		<?php echo $this->Form->input('filter',array('type'=>'select', 'empty' => __l('Please Select'), 'options'=> $filterActions)); ?>
    		<?php echo $this->Form->input('type',array('type'=>'select', 'empty' => __l('Please Select'), 'options'=> $contactTypes)); ?>
     		<?php echo $this->Form->input('keyword'); ?>
        	<?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
    	</div>
	</div>
	<?php		
	echo $this->Form->create('Contact' , array('class' => 'normal','action' => 'update'));
	echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
	?>

	<table class="list">
		<tr>
			<th class="select"><?php echo __l('Select');?></th>
			<th class="actions"><?php echo __l('Actions');?></th>
			<th class="dc"><?php echo $this->Paginator->sort('created');?></th>
			<th class="dl"><?php echo $this->Paginator->sort('contact_type_id');?></th>
			<th class="dl"><?php echo $this->Paginator->sort('first_name');?></th>
			<th class="dl"><?php echo $this->Paginator->sort('last_name');?></th>
			<th class="dl"><?php echo $this->Paginator->sort('email');?></th>
			<th class="dl"><?php echo $this->Paginator->sort('subject');?></th>
		</tr>
	<?php
	if (!empty($contacts)):

	$i = 0;
	foreach ($contacts as $contact):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
			$status_class = 'js-checkbox-active';
	?>
		<tr<?php echo $class;?>>
		<td class="select"><?php echo $this->Form->input('Contact.'.$contact['Contact']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$contact['Contact']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                            	<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $contact['Contact']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
			                 	<li><?php echo $this->Html->link(__l('View'), array('controller' => 'contacts', 'action' => 'view', $contact['Contact']['id']), array('class' => 'view js-view', 'title' => __l('View')));?></li>
    						</ul>
    					   </div>
    						<div class="action-bottom-block"></div>
    					  </div>
				 </div>
			
			</td>
			<td class="dc"><?php echo $this->Html->cDateTimeHighlight($contact['Contact']['created']);?></td>
			<td class="dl"><?php echo $this->Html->cText($contact['ContactType']['name']);?></td>
			<td class="dl"><?php echo $this->Html->cText($contact['Contact']['first_name']);?></td>
			<td class="dl"><?php echo $this->Html->cText($contact['Contact']['last_name']);?></td>
			<td class="dl"><?php echo $this->Html->cText($contact['Contact']['email']);?></td>
			<td class="dc"><?php echo $this->Html->cText($contact['Contact']['subject']);?></td>
		</tr>
	<?php
		endforeach;
	else:
	?>
		<tr>
			<td colspan="13"><p class="notice"><?php echo __l('No Contacts available');?></p></td>
		</tr>
	<?php
	endif;
	?>
	</table>

	<div class="js-pagination">
		<?php
		if (!empty($contacts)) {
			echo $this->element('paging_links');
		}
		?>
	</div>
        <div class="clearfix admin-select-block">
		<div class="grid_left">
			<?php echo __l('Select:'); ?>
			<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
			<?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
		</div>
		<div class="admin-checkbox-button grid_left"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
        </div>
    	<div class=" hide">
    		<?php echo $this->Form->submit(); ?>
    	</div>
	<?php echo $this->Form->end(); ?>
	</div>
<?php endif; ?>