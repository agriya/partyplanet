<li class="comment clearfix" id="comment-<?php echo $photoComment['PhotoComment']['id']?>">
	<div class="grid_2 omega alpha">
		<?php 
			echo $this->Html->getUserAvatar($photoComment['User'], 'micro_medium_thumb');
		//echo $this->Html->link($this->Html->showImage('UserAvatar', $photoComment['User']['UserAvatar'], array('dimension' => 'micro_medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photoComment['User']['username'], false)), 'title' => $this->Html->cText($photoComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $photoComment['User']['username']), array('escape' => false));?>
	</div>
	<div class="grid_14 omega alpha">
        <div class="clearfix">
            <h3 class="grid_left">
            <?php echo $this->Html->link('#', '#comment-' . $photoComment['PhotoComment']['id']);?>
    		<cite>
                <span class="author">
                    <?php echo $this->Html->link($this->Html->cText($photoComment['User']['username']), array('controller' => 'users', 'action' => 'view', $photoComment['User']['username']), array('title' => $photoComment['User']['username'], 'escape' => false));?>
                </span>
            </cite>
            <?php echo __l('said');?>
            </h3>
		  <p class="meta posted-date grid_right"><?php echo sprintf(__l('posted %s'), $this->Html->cDateTimeHighlight($photoComment['PhotoComment']['created'])); ?></p>
        </div>
        <?php if ($photoComment['User']['id'] == $this->Auth->user('id')) : ?>
	
			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'photo_comments', 'action' => 'delete', $photoComment['PhotoComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
	
		<?php endif; ?>
	
			<p><?php echo $this->Html->cText($photoComment['PhotoComment']['comment']);?></p>
	

	</div>
</li>