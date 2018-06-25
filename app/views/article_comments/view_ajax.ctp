<?php
 if(!empty($articleComment)):?>
<li class="list-row clearfix" id="comment-<?php echo $articleComment['ArticleComment']['id']; ?>" class="comment">

 <div class="grid_2 omega alpha">
		<?php
			$articleComment['User']['UserAvatar'] = !empty($articleComment['User']['UserAvatar']) ? $articleComment['User']['UserAvatar'] : array();
			if (!empty($articleComment['User']['username'])):
				echo $this->Html->getUserAvatar($articleComment['User'], 'micro_medium_thumb');
				else:
				echo $this->Html->getUserAvatar($articleComment['User'], 'micro_medium_thumb');
				endif;
		?>
	</div>
    <div class="grid_14 omega alpha">
        <div class="clearfix">
                <h3 class="grid_left">
            		<?php echo $this->Html->link('#', '#comment-'.$articleComment['ArticleComment']['id'], array('class' => 'js-scrollto')); ?>
            		<span class="author">
            			<?php
            				if (!empty($articleComment['User']['username'])):
            					echo $this->Html->link($articleComment['User']['username'], array('controller' => 'users', 'action' => 'view', $articleComment['User']['username']), array('title' => $articleComment['User']['username'], 'escape' => false));
            				    elseif(empty($articleComment['ArticleComment']['user_id'])&& !empty($articleComment['ArticleComment']['name'])):
            				    echo $this->Html->cText($articleComment['ArticleComment']['name']);
            					else:
            					echo __l('(unregistered)');
            					endif;
            			?>
            		</span>
            		<?php echo ' ' .__l('said'); ?>
        		</h3>
        		<p class="meta clearfix posted-date grid_right">
        			<span class="publish"><?php echo __l('Posted'); ?></span>
        			<span class="date"><?php echo $this->Html->cDateTimeHighlight($articleComment['ArticleComment']['created']); ?></span>
        		</p>
            </div>
	       	<p><?php echo $this->Html->cText($this->Html->truncate($articleComment['ArticleComment']['title']));?></p>
			<p><?php echo $this->Html->cText($this->Html->truncate($articleComment['ArticleComment']['comment']));?></p>
           	<?php if ($this->Auth->sessionValid() && $articleComment['User']['id'] == $this->Auth->user('id')) { ?>
							<div class="actions">
								<?php echo $this->Html->link(__l('Delete'), array('controller' => 'event_comments', 'action' => 'delete', $articleComment['ArticleComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
							</div>
						<?php } ?>

	</div>
</li>
<?php endif;?>