<?php /* SVN: $Id: $ */ ?>
<div class="userFriends index">
<h2><?php echo __l('User Friends');?></h2>
<?php echo $this->Form->create('UserFriend', array('type' => 'get', 'class' => 'normal', 'action'=>'index')); ?>
	<div class="filter-section">
		<div>
			<?php echo $this->Form->input('user_id',array('empty' => __l('Please Select'))); ?>
			<?php echo $this->Form->input('friend_user_id',array('empty' => __l('Please Select'))); ?>
            <?php echo $this->Form->input('filter_id',array('empty' => __l('Please Select'))); ?>
            <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        </div>
		<div>
			<?php echo $this->Form->submit(__l('Search'));?>
		</div>
	</div>
<?php echo $this->Form->end(); ?>
<?php 
	echo $this->Form->create('UserFriend' , array('class' => 'normal','action' => 'update'));
	echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
	
?>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
    	<th><?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('user_id');?></th>
        <th><?php echo $this->Paginator->sort('friend_user_id');?></th>
        <th><?php echo __l('Friends Status');?></th>
        <th><?php echo __l('Request');?></th>
    </tr>
<?php
if (!empty($userFriends)):

$i = 0;
foreach ($userFriends as $userFriend):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
    	<td><?php echo $this->Form->input('UserFriend.'.$userFriend['UserFriend']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userFriend['UserFriend']['id'], 'label' => false, 'class' => ' js-checkbox-list')); ?></td>
		<td class="actions"><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userFriend['UserFriend']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td><?php echo $this->Html->link($this->Html->cText($userFriend['User']['username']), array('controller'=> 'users', 'action'=>'view', $userFriend['User']['username'], 'admin'=>false), array('escape' => false));?></td>
		<td><?php echo $this->Html->link($this->Html->cText($userFriend['FriendUser']['username']), array('controller'=> 'users', 'action'=>'view', $userFriend['FriendUser']['username'], 'admin'=>false), array('escape' => false));?></td>
		<td><?php echo $this->Html->cText($userFriend['FriendStatus']['name']);?></td>
		<td><?php echo $this->Html->cBool($userFriend['UserFriend']['is_requested']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="8"><p class="notice"><?php echo __l('No User Friends available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<?php
if (!empty($userFriends)):
?>
	<div>
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
	</div>
	<div class="js-pagination">
        <?php echo $this->element('paging_links'); ?>
    </div>
	<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
    <div class="hide">
	    <?php echo $this->Form->submit('Submit'); ?>
    </div>
<?php
endif;
echo $this->Form->end();
?>
</div>
