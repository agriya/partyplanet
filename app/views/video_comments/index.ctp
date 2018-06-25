<?php /* SVN: $Id: index.ctp 1577 2009-08-12 21:17:54Z siva_063at09 $ */ ?>
<div class="comment-section">
<div class="videoComments index">
<h3><?php echo __l('Comments');?></h3>
<div class="record-info"><?php echo $this->element('paging_counter');?></div>
<ol class="list clearfix comment-list comments js-index-video-comment-response" start="<?php echo $this->Paginator->counter(array('format' => '%start%'));?>">
<?php
if (!empty($videoComments)):
$i = 0;
foreach ($videoComments as $videoComment):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = 'altrow';
	}
?>
	<li class="list-row clearfix <?php echo $class;?> comment" id="comment-<?php echo $videoComment['VideoComment']['id']?>">
    	<div class="grid_2 omega alpha">
			<?php 
				echo $this->Html->getUserAvatar($videoComment['User'], 'micro_medium_thumb');
				//echo $this->Html->link($this->Html->showImage('UserAvatar', $videoComment['User']['UserAvatar'], array('dimension' => 'micro_medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($videoComment['User']['username'], false)), 'title' => $this->Html->cText($videoComment['User']['username'], false))), array('controller' => 'user', 'action' => 'view',$videoComment['User']['username'], 'admin' => false), null, array('inline' => false)); ?>
		</div>
		<div class="grid_14 omega alpha">
    		<div class="clearfix">
    	       	<h3 class="grid_left">
                	<?php echo $this->Html->link('#', '#comment-' . $videoComment['VideoComment']['id'], array('class' => 'js-scrollto'));?>
                    <cite>
                      <span class="author"><?php echo $this->Html->link($this->Html->cText($videoComment['User']['username']), array('controller' => 'users', 'action' => 'view', $videoComment['User']['username']), array('title' => $videoComment['User']['username'], 'escape' => false));?> </span>
                   </cite>
                     <?php echo __l('said');?>
                </h3>
                <p class="meta posted-date grid_right"><?php echo __l('posted');?> <?php echo $this->Html->cDateTimeHighlight($videoComment['VideoComment' ]['created']);?></p>
            </div>
    		<?php echo nl2br($this->Html->cText($videoComment['VideoComment' ]['comment']));?>
		  	<?php if ($videoComment['Video']['user_id'] == $this->Auth->user('id')) { ?>
			<div class="actions">
    			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'video_comments', 'action' => 'delete', $videoComment['VideoComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
    		</div>
		<?php } ?>
        </div>
	</li>
<?php
    endforeach;
else:
?>
	<li class="notice-block">
		<p class="notice"><?php echo __l('No comments available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($videoComments)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>

