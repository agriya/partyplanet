<li class="list-row comment clearfix" id="comment-<?php echo $userComment['UserComment']['id']; ?>" >
	<div class="grid_2 omega alpha">
		<?php 
			echo $this->Html->getUserAvatar($userComment['User'], 'micro_medium_thumb');
			//echo $this->Html->link($this->Html->showImage('UserAvatar',$userComment['User']['UserAvatar'], array('dimension' => 'micro_medium_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($userComment['User']['username'], false)), 'title' => $this->Html->cText($userComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $userComment['User']['username']), array('escape' => false));?>
	</div>
	<div class=" grid_14 omega alpha">
	<div class="clearfix">
        <h3 class="grid_left">
		<?php echo $this->Html->link('#', '#comment-'.$userComment['UserComment']['id'], array('class' => 'js-scrollto')); ?>
          <?php echo $this->Html->link($userComment['User']['username'], array('controller' => 'users', 'action' => 'view', $userComment['User']['username']), array('title' => $userComment['User']['username'], 'escape' => false)); ?> said
        </h3>
        <p class="meta grid_right posted-date">
			<span class="publish"><?php echo __l('Posted'); ?></span>
			<span class="date">
				<?php echo $this->Html->cDateTimeHighlight($userComment['UserComment']['created']); ?>
			</span>
		</p>
		</div>
		<?php echo $this->Html->cText($this->Html->truncate($userComment['UserComment']['comment']));?>
		<?php if ($userComment['User']['id'] == $this->Auth->user('id')) { ?>
		<div class="actions">
			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'user_comments', 'action' => 'delete', $userComment['UserComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
		</div>
		<?php } ?>

	</div>
</li>