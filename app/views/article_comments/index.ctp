<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div id="comments" class="articleComments index js-response">
	<h3> <?php echo __l('Comments');?></h3>
<ol class="list comment-list js-index-article-comment-response" start="<?php echo $this->Paginator->counter(array('format' => '%start%')); ?>">
<?php
if (!empty($articleComments)):

$i = 0;
foreach ($articleComments as $articleComment):
	$class = null;
	if ($i++ % 2 == 0) {
	$class = 'altrow';
	}
?>
<li class="list-row comment clearfix <?php echo $class; ?>" id="comment-<?php echo $articleComment['ArticleComment']['id']; ?>" >
       	<div class=" grid_2 omega alpha">
       	 <?php
			echo $this->Html->getUserAvatar($articleComment['User'], 'micro_medium_thumb');
		 //echo $this->Html->link($this->Html->showImage('UserAvatar',$articleComment['User']['UserAvatar'], array('dimension' => 'medium_thumb', 'alt' => sprintf('[Image: %s]', $this->Html->cText($articleComment['User']['username'], false)), 'title' => $this->Html->cText($articleComment['User']['username'], false))), array('controller' => 'users', 'action' => 'view', $articleComment['User']['username']), array('escape' => false));?>
       </div>
     <div class=" grid_14 omega alpha">
         <div class="clearfix">
             <h3 class="grid_left">
             <?php echo $this->Html->link('#', '#comment-'.$articleComment['ArticleComment']['id'], array('class' => 'js-scrollto')); ?>
            <cite><span class="author"><?php echo $this->Html->link($articleComment['User']['username'], array('controller' => 'users', 'action' => 'view', $articleComment['User']['username']), array('title' => $articleComment['User']['username'], 'escape' => false)); ?></span></cite>
            </h3>
            <p class="grid_right">
                <span class="publish"><?php echo __l('Posted'); ?></span>
                <span class="date">
                    <?php echo $this->Html->cDateTimeHighlight($articleComment['ArticleComment']['created']); ?>
                </span>
            </p>
         </div>
          <p><?php echo $this->Html->cText($this->Html->truncate($articleComment['ArticleComment']['title']));?></p>
          <p><?php echo $this->Html->cText($articleComment['ArticleComment']['comment']);?></p>
        <?php if ($this->Auth->sessionValid() && $articleComment['User']['id'] == $this->Auth->user('id')) { ?>
        <div class="actions">
			<?php echo $this->Html->link(__l('Delete'), array('controller' => 'article_comments', 'action' => 'delete', $articleComment['ArticleComment']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
		</div>
		<?php } ?>

        </div>
     </li>

<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Article Comments available');?></p>
	</li>
<?php
endif;
?>
</ol>
<div class="js-pagination">
<?php
if (!empty($articleComments)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>