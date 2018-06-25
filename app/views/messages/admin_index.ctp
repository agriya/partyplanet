<div class="messages index js-response js-responses">
<?php //echo $this->element('mail-search');?>
<div class="index_inner">
    <ul class="filter-list clearfix">
    	<li><span class="flagged round-5"><?php echo $this->Html->link(__l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false), array('controller' => 'messages', 'action' => 'index', 'filter_id' => ConstMoreAction::Flagged), array('title' => __l('System Flagged') . ': ' . $this->Html->cInt($system_flagged, false)));?></span></li>
    	<li><span class="suspended round-5"><?php echo $this->Html->link(__l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false), array('controller' => 'messages', 'action' => 'index', 'filter_id' => ConstMoreAction::Suspend), array('title' => __l('Admin Suspended') . ': ' . $this->Html->cInt($suspended, false)));?></span></li>
    	<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($all, false), array('controller' => 'messages', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($all, false)));?></span></li>
    </ul>
    <div class="clearfix">
    	<div class="grid_left">	<?php echo $this->element('paging_counter');?></div>
    	<div class="grid_left">
        <?php
        	echo $this->Form->create('Message' , array('action' => 'admin_index', 'type' => 'post', 'class' => 'normal search-form clearfix ')); //js-ajax-form
        	echo $this->Form->input('filter_id', array('type' =>'hidden'));
        	echo $this->Form->autocomplete('Message.username', array('label' => __l('From'), 'acFieldKey' => 'Message.user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '255'));
        	echo $this->Form->autocomplete('Message.other_username', array('label' => __l('To'), 'acFieldKey' => 'Message.other_user_id', 'acFields' => array('User.username'), 'acSearchFieldNames' => array('User.username'), 'maxlength' => '255'));
        ?>
        	<?php
            	echo $this->Form->submit(__l('Filter'));
        	?>
     
        <?php echo $this->Form->end(); ?>
        </div>
    </div>

<?php echo $this->Form->create('Message' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<table class="list">
<tr>
	<th class="select"><?php echo __l('Select');?></th>
	<th><?php echo __l('Action');?></th>
	<th class="dl"><?php echo __l('Subject'); ?></th>
	<th class="dl"><?php echo __l('From'); ?></th>
	<th class="dl"><?php echo __l('To'); ?></th>
	<th class="dc"><?php echo __l('Date'); ?></th>
</tr>
<?php
if (!empty($messages)) :
$i = 0;
foreach($messages as $message):
   // if empty subject, showing with (no suject) as subject as like in gmail
    if (!$message['MessageContent']['subject']) :
		$message['MessageContent']['subject'] = '(no subject)';
    endif;
	if ($i++ % 2 == 0) :
		$row_class = 'row';
	else :
		$row_class = 'altrow';
    endif;
	
	$message_class = "checkbox-message ";
	
	$is_read_class = "";
	
    if ($message['Message']['is_read']) :
        $message_class .= "js-checkbox-active";
    else :
        $message_class .= "js-checkbox-inactive";
        $is_read_class .= "unread-message-bold";
        $row_class=$row_class.' unread-row';
    endif;
	$row_class='class="'.$row_class.'"';

	$row_three_class='w-three';
	 if (!empty($message['MessageContent']['Attachment'])):
			$row_three_class.=' has-attachment';
	endif;
	
	if($message['MessageContent']['admin_suspend']):
		$message_class.= ' js-checkbox-suspended';
	else:
		$message_class.= ' js-checkbox-unsuspended';
	endif;
	if($message['MessageContent']['is_system_flagged']):
		$message_class.= ' js-checkbox-flagged';
	else:
		$message_class.= ' js-checkbox-unflagged';
	endif;
	
		$view_url=array('controller' => 'messages','action' => 'v',$message['Message']['id'], 'admin' => false);
?>
    <tr <?php echo $row_class;?>>

		<td class="select">
				<?php echo $this->Form->input('Message.'.$message['Message']['id'], array('type' => 'checkbox', 'id' => 'admin_checkbox_'.$message['Message']['id'], 'label' => false, 'class' => $message_class.' js-checkbox-list'));?>
		</td>
		
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
                            <li><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $message['Message']['id'], 'flag' => 'delete'), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                        	<?php if($message['MessageContent']['is_system_flagged']):?>
                				<li>	<?php echo $this->Html->link(__l('Clear flag'), array('conroller'=>'message_contents','action' => 'admin_update_status', $message['MessageContent']['id'], 'flag' => 'deactivate'), array('class' => 'clear-flag js-delete', 'title' => __l('Clear flag')));?>
                				</li>
                			<?php else:?>
                			        	<li><?php echo $this->Html->link(__l('Flag'), array('conroller'=>'message_contents','action' => 'admin_update_status', $message['MessageContent']['id'], 'flag' => 'active'), array('class' => 'flag js-delete', 'title' => __l('Flag')));?>
                				</li>
                			<?php endif;?>
                                <?php if($message['MessageContent']['admin_suspend']):?>
                    				<li><?php echo $this->Html->link(__l('Unsuspend'), array('conroller'=>'message_contents','action' => 'admin_update_status', $message['MessageContent']['id'], 'flag' => 'unsuspend'), array('class' => 'unsuspend js-delete', 'title' => __l('Unsuspend')));?>
                    				</li>
                    			<?php else:?>
                    				<li><?php echo $this->Html->link(__l('Suspend'), array('conroller'=>'message_contents','action' => 'admin_update_status', $message['MessageContent']['id'], 'flag' => 'suspend'), array('class' => 'suspend js-delete', 'title' => __l('Suspend')));?>
                    				</li>
                    			<?php endif;?>
   					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
		</td>
	    <td  class=" <?php echo $row_three_class;?> dl">
         	<div class="status-block">
            	<?php
    			if($message['MessageContent']['admin_suspend']):
    					echo '<span class="suspended">'.__l('Admin Suspended').'</span>';
    				endif;
    				if($message['MessageContent']['is_system_flagged']):
    					echo '<span class="flagged">'.__l('System Flagged').'</span>';
    				endif;
    			?>
			</div>
			<?php echo $this->Html->link($this->Html->truncate($message['MessageContent']['subject'] . ' - ' . substr($message['MessageContent']['message'], 0, Configure::read('messages.content_length'))) ,$view_url);?>
             <?php
               if (!empty($message['Label'])):
					?>
					<ul class="message-label-list">
						<?php foreach($message['Label'] as $label): ?>
							<li>
								<?php echo $this->Html->cText($this->Html->truncate($label['name']), false);?>
							</li>
						<?php
						endforeach;
					?>					
					</ul>
					<?php
                endif;
			?>
        
        </td>
	    <td class="w-two <?php  echo $is_read_class;?> dl">
				<span class="user-name-block c1">
					<?php echo $this->Html->link($this->Html->cText($message['User']['username']), array('controller' => 'users', 'action' => 'view', $message['User']['username'], 'admin' => false), array('title' => $message['User']['username'], 'escape' => false));?>
				</span>
                <div class="clear"></div>
            </td>
			        <td class="w-two <?php  echo $is_read_class;?> dl">
				<span class="user-name-block c1">
					<?php echo $this->Html->link($this->Html->cText($message['OtherUser']['username']), array('controller' => 'users', 'action' => 'view', $message['OtherUser']['username'], 'admin' => false), array('title' => $message['OtherUser']['username'], 'escape' => false));?>
				</span>
                <div class="clear"></div>
            </td>

        <td  class="w-four <?php echo $is_read_class;?> dc"><?php echo $this->Html->cDateTimeHighlight($message['Message']['created']);?></td>
    </tr>
<?php
    endforeach;
else :
?>
<tr>
    <td colspan="8"><p class="notice"><?php echo __l('No messages available') ?></p></td>
</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($messages)):
        ?>
        <div class="clearfix select-block-bot">
            <div class="admin-select-block grid_left">
                <div class="grid_left">
        			<?php echo __l('Select:'); ?>
        			<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
        			<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
        			<?php echo $this->Html->link(__l('Flagged'), '#', array('class' => 'js-admin-select-flagged', 'title' => __l('Flagged'))); ?>
        			<?php echo $this->Html->link(__l('Unflagged'), '#', array('class' => 'js-admin-select-unflagged', 'title' => __l('Unflagged'))); ?>
        			<?php echo $this->Html->link(__l('Suspended'), '#', array('class' => 'js-admin-select-suspended', 'title' => __l('Suspended'))); ?>
                </div>
                <div class="admin-checkbox-button grid_left">
                    <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
                </div>
            </div>
             <div class="js-pagination grid_right">
                <?php echo $this->element('paging_links'); ?>
            </div>
        </div>
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
    </div>

</div>