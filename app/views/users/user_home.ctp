<ol class="members-list clearfix">
	<?php
		if (!empty($users)):
			$i = 0;
			foreach ($users as $user):
	?>
	<li class="grid_left"><?php echo $this->Html->getUserAvatar($user['User'], 'normalhigh_thumb'); ?></li>
	<?php
			endforeach;
		else:
	?>
	<li><p class="notice"><?php echo __l('No users available');?></p></li>
	<?php
		endif;
	?>
</ol>
<?php if (!empty($users)): ?>
	<div class="add-block"> <?php echo $this->Html->link(__l('view more profile') ,array('controller' => 'users', 'action' => 'index'), array('escape' => false)); ?></div>
<?php endif; ?>