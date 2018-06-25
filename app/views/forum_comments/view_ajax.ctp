<li class="list-row comment clearfix" id="comment-<?php echo $forumComment['ForumComment']['id']?>">
    	<div class="grid_2 omega alpha">
			<?php 
				echo $this->Html->getUserAvatar($forumComment['User'], 'micro_medium_thumb');
				//echo $this->Html->link($this->Html->showImage('UserAvatar', $forumComment['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($forumComment['User']['username'], false)), 'title' => $this->Html->cText($forumComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $forumComment['User']['username']), array('escape' => false));?>
        </div>
    	<div class="grid_14 omega alpha">
    	<div class="clearfix">
            <h3 class="grid_left">
                 <?php echo $this->Html->link('#', '#comment-' . $forumComment['ForumComment']['id'], array('class' => 'js-scrollto'));?>
                <?php echo $this->Html->cText($forumComment['User']['username']); ?>
        		<?php if(!empty($forumComment['User']['UserProfile']['Country']['name'])): ?>
				<?php
					echo $this->Html->image('flags/'.strtolower($forumComment['User']['UserProfile']['Country']['iso_alpha2']).'.gif', array('alt'=> sprintf(__l('[Image: %s]'),$this->Html->cText($forumComment['User']['UserProfile']['Country']['name'])), 'title' => $forumComment['User']['UserProfile']['Country']['name']));
					echo $this->Html->cText($forumComment['User']['UserProfile']['Country']['name']);
				?>
				<?php endif;?>
    		</h3>
    		  <p class="meta posted-date grid_right">
                     <?php echo sprintf(__l('posted %s'), $this->Html->cDateTimeHighlight($forumComment['ForumComment']['created'])); ?>
             </p>
         </div>
       	<?php echo $this->Html->cText($forumComment['ForumComment']['comment']);?>
 	    <?php if ($forumComment['Forum']['user_id'] == $this->Auth->user('id')) : ?>
            <div class="actions">
                <?php echo $this->Html->link(__l('Delete'), array('controller' => 'forum_comments', 'action' => 'delete', $forumComment['ForumComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
            </div>
        <?php endif; ?>
	</div>
</li>