<div class="message-section message-index-block clearfix">
<div id="breadcrumb">
		<?php
			if($this->Auth->user('user_type_id') != ConstUserTypes::Admin):
			 echo $this->Html->addCrumb(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false));
			 echo $this->Html->addCrumb(__l('Inbox'));
				echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
			endif;
		?>
	</div>
<ul class="menu-tabs clearfix">
	<li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
	<li class="active"><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
	<li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
</ul>
<h2>
<?php
    if ($is_starred == 1) :
    	$folder_type = 'Starred';
    endif;
?>
<?php echo __l('My ') . ' ' .  ucfirst($folder_type) .  ' ' .__l(' Messages');?>
</h2>
<?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => '2sec'))); ?>
<div class="messages index">
<?php //echo $this->element('mail-search');?>
<?php echo $this->Form->create('Message', array('action' => 'move_to', 'class' => 'normal')); ?>
<?php
$refresh_folder_type = $folder_type;
if ($folder_type == 'draft') $refresh_folder_type = 'drafts';
if ($folder_type == 'sent') $refresh_folder_type = 'sentmail';
echo $this->Form->hidden('folder_type', array('value' => $folder_type, 'name' => 'data[Message][folder_type]'));
echo $this->Form->hidden('is_starred', array('value' => $is_starred, 'name' => 'data[Message][is_starred]'));
echo $this->Form->hidden('label_slug', array('value' => $label_slug, 'name' => 'data[Message][label_slug]'));
?>
<div class="message-block clearfix">
<div class="message-block-left grid_left" >
<?php

	echo $this->Form->input('more_action_1', array('type' => 'select',
	        'options' => $mail_options,
	        'label' => false,
	        'class' => 'js-apply-message-action'
	        ));

?>
</div>
<div class="message-block-right grid_right">
<?php
	echo $this->Form->submit(__l('Archive'), array('name' => 'data[Message][Archive]'));
	if ($folder_type == 'spam'){
		echo $this->Form->submit(__l('Notspam'), array('name' => 'data[Message][NotSpam]'));
	}else{
		echo $this->Form->submit(__l('Spam'), array('name' => 'data[Message][ReportSpam]'));
	}
	echo $this->Form->submit(__l('Delete'), array('name' => 'data[Message][Delete]'));
?>
</div>
</div>
<div class="inbox-option">
<?php echo __l('Select:'); ?>
<?php echo $this->Html->link(__l('All,'), '#', array('class' => 'js-select-all', 'title' => __l('All'))); ?>
<?php echo $this->Html->link(__l('None,'), '#', array('class' => 'js-select-none', 'title' => __l('None'))); ?>
<?php echo $this->Html->link(__l('Read,'), '#', array('class' => 'js-select-read', 'title' => __l('Read'))); ?>
<?php echo $this->Html->link(__l('Unread,'), '#', array('class' => 'js-select-unread', 'title' => __l('Unread'))); ?>
<?php echo $this->Html->link(__l('Starred,'), '#', array('class' => 'js-select-starred', 'title' => __l('Starred'))); ?>
<?php echo $this->Html->link(__l('Unstarred'), '#', array('class' => 'js-select-unstarred', 'title' => __l('Unstarred'))); ?>
</div>
<table class="list">
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
	$is_starred_class = "star";
    if ($message['Message']['is_read']) :
        $message_class .= " checkbox-read ";
    else :
        $message_class .= " checkbox-unread ";
        $is_read_class .= "unread-message-bold";
        $row_class=$row_class.' unread-row';
    endif;
    if ($message['Message']['is_starred']):
        $message_class .= " checkbox-starred ";
        $is_starred_class = "star-select";
    else:
        $message_class .= " checkbox-unstarred ";
    endif;
	$row_class='class="'.$row_class.'"';

	$row_three_class='w-three';
	 if (!empty($message['MessageContent']['Attachment'])):
			$row_three_class.=' has-attachment';
	endif;
if ($folder_type == 'draft'):
	$view_url=array('controller' => 'messages','action' => 'compose',$message['Message']['id'],'draft');
else:
	$view_url=array('controller' => 'messages','action' => 'v',$message['Message']['id']);
endif;

?>
    <tr <?php echo $row_class;?>>
        <td class="w-one">
				<?php echo $this->Form->input("Message.Id." . $message['Message']['id'], array('type' => 'checkbox', 'id' => "Message_" . $message['Message']['id'], 'label' => false, 'class' => $message_class));?>
		</td>
		<td class="w-two <?php  echo $is_read_class;?>">
				<span  class="<?php echo $is_starred_class;?>">
						<?php echo $this->Html->link(__l('Star') , array('controller' => 'messages', 'action' => 'star', $message['Message']['id'], $is_starred_class));?>
				</span>
				<span class="user-name-block c1">
                    <?php
                    if ($message['Message']['is_sender'] == 1) :
                        echo $this->Html->link(__l('To: ') . $this->Html->cText($this->Html->truncate($message['OtherUser']['username'], 7), false) , $view_url);
                    elseif ($message['Message']['is_sender'] == 2) :
                        echo $this->Html->link(__l('Me   : ') , $view_url);
                    else:
                        echo $this->Html->link($this->Html->cText($this->Html->truncate($message['OtherUser']['username'], 7), false), $view_url);
                    endif;
                    ?>
				</span>
                <div class="clear"></div>
            </td>
        <td  class=" <?php echo $row_three_class;?>">
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
			<?php 
				echo $this->Html->link($this->Html->truncate($message['MessageContent']['subject'] . ' - ' . substr($message['MessageContent']['message'], 0, Configure::read('messages.content_length'))) ,$view_url);?>
        </td>
        <td  class="w-four <?php echo $is_read_class;?>"><?php echo $this->Html->cDateTimeHighlight($message['Message']['created']);?></td>
    </tr>
<?php
    endforeach;
else :
?>
<tr>
    <td><p class="notice"><?php echo __l('No') ?> <?php echo ($folder_type=='all')?'':$folder_type; ?> <?php echo __l('messages available') ?></p></td>
</tr>
<?php
endif;
?>
</table>
<div class="inbox-option">
<?php echo __l('Select:'); ?>
<?php echo $this->Html->link(__l('All,'), '#', array('class' => 'js-select-all', 'title' => __l('All'))); ?>
<?php echo $this->Html->link(__l('None,'), '#', array('class' => 'js-select-none', 'title' => __l('None'))); ?>
<?php echo $this->Html->link(__l('Read,'), '#', array('class' => 'js-select-read', 'title' => __l('Read'))); ?>
<?php echo $this->Html->link(__l('Unread,'), '#', array('class' => 'js-select-unread', 'title' => __l('Unread'))); ?>
<?php echo $this->Html->link(__l('Starred,'), '#', array('class' => 'js-select-starred', 'title' => __l('Starred'))); ?>
<?php echo $this->Html->link(__l('Unstarred'), '#', array('class' => 'js-select-unstarred', 'title' => __l('Unstarred'))); ?>

</div>
<?php
if (!empty($messages)) :
    echo $this->element('paging_links');
endif;
?>
<div class="message-block clearfix">
<div class="message-block-left grid_left ">
<?php
echo $this->Form->input('more_action_2', array('type' => 'select',
    'options' => $mail_options,
    'label' => false,
    'class' => 'js-apply-message-action'
    ));
?>
</div>
<div class="message-block-right grid_right ">
<?php
echo $this->Form->submit(__l('Archive'), array('name' => 'data[Message][Archive]'));
if ($folder_type == 'spam'){
	echo $this->Form->submit(__l('Notspam'), array('name' => 'data[Message][NotSpam]'));
}else{
	echo $this->Form->submit(__l('Spam'), array('name' => 'data[Message][ReportSpam]'));
}
echo $this->Form->submit(__l('Delete'), array('name' => 'data[Message][Delete]'));
?>
</div>
</div>
<div class="refersh-block">
<?php
if (!empty($label_slug) && $label_slug != 'null') :
    echo $this->Html->link(__l('Refresh') , array('controller' => 'messages',
            'action' => 'label',
            $label_slug
            ),array('class' => 'refresh', 'title' => __l('Refresh')));
 elseif (!empty($is_starred)) :
    echo $this->Html->link(__l('Refresh') , array('controller' => 'messages',
            'action' => 'starred'
            ),array('class' => 'refresh', 'title' => __l('Refresh')));
 else:
    echo $this->Html->link(__l('Refresh') , array('controller' => 'messages',
            'action' => $refresh_folder_type
            ),array('class' => 'refresh', 'title' => __l('Refresh')));
endif;
?>
</div>

<?php echo $this->Form->end();?>
</div>
</div>