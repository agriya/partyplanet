<?php if(!empty($eventComment)):?>
<li class="list-row clearfix" id="comment-<?php echo $eventComment['EventComment']['id']; ?>" class="comment">
	<div class="grid_2 omega alpha">
		<?php
			$eventComment['User']['UserAvatar'] = !empty($eventComment['User']['UserAvatar']) ? $eventComment['User']['UserAvatar'] : array();
			if (!empty($eventComment['User']['username'])):
				echo $this->Html->getUserAvatar($eventComment['User'], 'micro_medium_thumb');
				//echo $this->Html->link($this->Html->showImage('UserAvatar', $eventComment['User']['UserAvatar'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($eventComment['User']['username'], false)), 'title' => $this->Html->cText($eventComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $eventComment['User']['username']), array('escape' => false));
			else:
				echo $this->Html->getUserAvatar($eventComment['User'], 'micro_medium_thumb');
				//echo $this->Html->showImage('UserAvatar', $eventComment['User']['UserAvatar'], array('dimension' => 'big_thumb', 'alt' => sprintf('[Image: %s]', __l('Unregistered')), 'title' => __l('(unregistered)')));
			endif;
		?>
	</div>
    <div class="grid_14 omega alpha">
        <div class="clearfix">
                <h3 class="grid_left">
            		<?php echo $this->Html->link('#', '#comment-'.$eventComment['EventComment']['id'], array('class' => 'js-scrollto')); ?>
            		<span class="author">
            			<?php
            				if (!empty($eventComment['User']['username'])):
            					echo $this->Html->link($eventComment['User']['username'], array('controller' => 'users', 'action' => 'view', $eventComment['User']['username']), array('title' => $eventComment['User']['username'], 'escape' => false));
            				    elseif(empty($eventComment['EventComment']['user_id'])&& !empty($eventComment['EventComment']['name'])):
            				    echo $this->Html->cText($eventComment['EventComment']['name']);
            					else:
            					echo __l('(unregistered)');
            					endif;
            			?>
            		</span>
            		<?php echo ' ' .__l('said'); ?>
        		</h3>
        		<p class="meta clearfix posted-date grid_right">
        			<span class="publish"><?php echo __l('Posted'); ?></span>
        			<span class="date"><?php echo $this->Html->cDateTimeHighlight($eventComment['EventComment']['created']); ?></span>
        		</p>
            </div>
	       	<p><?php echo $this->Html->cText($this->Html->truncate($eventComment['EventComment']['title']));?></p>
			<p><?php echo $this->Html->cText($this->Html->truncate($eventComment['EventComment']['comment']));?></p>
           	<?php if ($this->Auth->sessionValid() && $eventComment['User']['id'] == $this->Auth->user('id')) { ?>
							<div class="actions">
								<?php echo $this->Html->link(__l('Delete'), array('controller' => 'event_comments', 'action' => 'delete', $eventComment['EventComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
							</div>
						<?php } ?>

	</div>
</li>
<?php endif;?>