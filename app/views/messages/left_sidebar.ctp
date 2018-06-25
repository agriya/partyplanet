
<div class="clearfix">
    <ul class="add-venue-list compose-menu grid_right omega clearfix">
        <li class="<?php echo (((isset($settings)) and ($settings == 'settings')) ? 'active' : 'inactive'); ?>">
         <?php echo $this->Html->link(__l('Message Settings') , array('controller' => 'messages', 'action' => 'settings'), array('title'=>__l('Message Settings'))); ?>
         </li>
        <li class="<?php echo (((isset($compose)) and ($compose == 'compose')) ? 'active' : 'inactive'); ?>">
        <?php echo $this->Html->link(__l('Compose Message') , array('controller' => 'messages', 'action' => 'compose') , array('title'=>__l('Compose Message'))); ?>
        </li>
    </ul>
</div>
	<div class="clearfix filter-block">
		<ul class="inbox-menu">
        <?php if ($inbox == 0): ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'inbox')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Inbox') , array('controller' => 'messages', 'action' => 'inbox'), array('title'=>__l('Inbox'))); ?>
			</li>
        <?php else: ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'inbox')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Inbox') .' (' . $inbox . ')' , array('controller' => 'messages', 'action' => 'inbox'), array('title'=>__l('Inbox'))); ?>
			</li>
        <?php endif; ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'sent')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Sent Mail') , array('controller' => 'messages', 'action' => 'sentmail'), array('title'=>__l('Sent Mail'))); ?>
			</li>
        <?php if ($draft == 0) :  ?>
			<li class="starred <?php echo (isset($folder_type) and $folder_type == 'all' and isset($is_starred) and $is_starred == 1) ? 'active' : 'inactive'; ?>">
				<?php echo $this->Html->link(__l('Starred').' (' . $stared . ')' , array('controller' => 'messages', 'action' => 'starred'), array('title'=>__l('Starred'))); ?><em class="starred"></em>
			</li>
        <?php else : ?>
			<li class="starred <?php echo (isset($folder_type) and $folder_type == 'all' and isset($is_starred) and $is_starred == 1) ? 'active' : 'inactive'; ?>">
				<?php echo $this->Html->link(__l('Starred') . ' (' . $stared . ')' , array('controller' => 'messages', 'action' => 'starred'), array('title'=>__l('Starred'))); ?><em class="starred"></em>
			</li>
        <?php endif; ?>
        <?php if ($draft == 0) : ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'draft')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Drafts') , array('controller' => 'messages', 'action' => 'drafts'), array('title'=>__l('Drafts'))); ?>
			</li>
        <?php else : ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'draft')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Drafts ') . ' (' . $draft . ')' , array('controller' => 'messages', 'action' => 'drafts'), array('title'=>__l('Drafts'))); ?>
			</li>
        <?php endif; ?>
			<li class="<?php echo (isset($folder_type) and $folder_type == 'all' and isset($is_starred) and $is_starred == 0) ? 'active' : 'inactive'; ?>">
				<?php echo $this->Html->link(__l('All Mail') , array('controller' => 'messages', 'action' => 'all'), array('title'=>__l('All Mail'))); ?>
			</li>
        <?php if ($spam == 0) : ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'spam')) ? 'active' : 'inactive'); ?>">
				 <?php echo $this->Html->link(__l('Spam') , array('controller' => 'messages', 'action' => 'spam'), array('title'=>__l('Spam'))); ?>
			</li>
        <?php else : ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'spam')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Spam (' . $spam . ')') , array('controller' => 'messages', 'action' => 'spam'), array('title'=>__l('Spam'))); ?>
			</li>
        <?php endif; ?>
			<li class="<?php echo (((isset($folder_type)) and ($folder_type == 'trash')) ? 'active' : 'inactive'); ?>">
				<?php echo $this->Html->link(__l('Trash') , array('controller' => 'messages', 'action' => 'trash'), array('title'=>__l('Trash'))); ?>
			</li>
		</ul>
	</div>
<?php
echo $this->element('message_labels_users-lst', array(
    'cache' => array('config' => 'sec', 'key' =>$this->Auth->User('username') )
));
?>
