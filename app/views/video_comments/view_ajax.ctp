<?php if(!empty($videoComment)):?>
<li class="comment clearfix" id="comment-<?php echo $videoComment['VideoComment']['id']?>">
		<div class="grid_2 omega alpha">
		<?php 	echo $this->Html->getUserAvatar($videoComment['User'], 'micro_medium_thumb');?>
        </div>
       	<div class="grid_14 omega alpha">
        <div class="clearfix">
        <h3 class="grid_left">
        <?php echo $this->Html->link('#', '#comment-' . $videoComment['VideoComment']['id']);?>
		<cite>
            <span class="author">
                <?php echo $this->Html->link($this->Html->cText($videoComment['User']['username']), array('controller' => 'users', 'action' => 'view', $videoComment['User']['username']), array('title' => $videoComment['User']['username'], 'escape' => false));?>
            </span>
        </cite>
        <?php echo __l('said');?>
        </h3>
		<p class="meta posted-date grid_right"><?php echo __l('posted');?> <?php echo $this->Html->cDateTimeHighlight($videoComment['VideoComment']['created']); ?></p>
        </div>
        <?php if ($videoComment['Video']['user_id'] == $this->Auth->user('id')) { ?>
		
    			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'video_comments', 'action' => 'delete', $videoComment['VideoComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
    
		<?php } ?>
	
			<p><?php echo $this->Html->cText($videoComment['VideoComment']['comment']);?></p>
	
    	
	</div>
</li>
<?php endif;?>