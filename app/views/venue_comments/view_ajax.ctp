<li class="list-row clearfix" id="comment-<?php echo $venueComment['VenueComment']['id']; ?>" class="comment">
	<div class="grid_2 omega alpha">
		<?php
			$venueComment['User']['UserAvatar'] = !empty($venueComment['User']['UserAvatar']) ? $venueComment['User']['UserAvatar'] : array();
			if (!empty($venueComment['User']['username'])):
				echo $this->Html->getUserAvatar($venueComment['User'], 'micro_medium_thumb');
				//echo $this->Html->link($this->Html->showImage('UserAvatar', $venueComment['User']['UserAvatar'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($venueComment['User']['username'], false)), 'title' => $this->Html->cText($venueComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $venueComment['User']['username']), array('escape' => false));
			else:
				echo $this->Html->getUserAvatar($venueComment['User'], 'micro_medium_thumb');
				//echo $this->Html->showImage('UserAvatar', $venueComment['User']['UserAvatar'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', __l('Unregistered')), 'title' => __l('(unregistered)')));
			endif;
		?>
	</div>
    <div class="grid_14 omega alpha">
    <div class="clearfix">
        <h3 class="grid_left">
    		<?php echo $this->Html->link('#', '#comment-'.$venueComment['VenueComment']['id'], array('class' => 'js-scrollto')); ?>
    		<cite><span class="author">
    			<?php
    				if (!empty($venueComment['User']['username'])):
    					echo $this->Html->link($venueComment['User']['username'], array('controller' => 'users', 'action' => 'view', $venueComment['User']['username']), array('title' => $venueComment['User']['username'], 'escape' => false));
    				else:
    					echo __l('(unregistered)');
    				endif;
    			?>
    		</span></cite>
    		<?php '' . __l('said') . ' '; ?>
		</h3>
		<p class="meta clearfix posted-date grid_right">
			<span class="publish"><?php echo __l('Posted'); ?></span>
			<span class="date"><?php echo $this->Html->cDateTimeHighlight($venueComment['VenueComment']['created']); ?></span>
		</p>
		</div>
	
			<?php echo $this->Html->cText($this->Html->truncate($venueComment['VenueComment']['title']));?>
			<?php echo $this->Html->cText($this->Html->truncate($venueComment['VenueComment']['comment']));?>
	
		<?php if ($this->Auth->sessionValid() && $venueComment['User']['id'] == $this->Auth->user('id')): ?>
			<div class="actions">
				<?php echo $this->Html->link(__l('Delete'), array('controller' => 'venue_comments', 'action' => 'delete', $venueComment['VenueComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
			</div>
		<?php endif; ?>
	</div>
</li>