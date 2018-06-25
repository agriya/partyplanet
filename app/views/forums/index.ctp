<?php /* SVN: $Id: $ */ ?>
<div id="breadcrumb">
	<?php echo $this->Html->addCrumb(__l('Forums'), array('controller' => 'forum_categories', 'action' => 'index')); ?>
	<?php echo $this->Html->addCrumb($this->Html->cText($this->pageTitle, false)); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="forums index js-response">
<h2><?php echo $this->Html->cText($this->pageTitle,false); ?></h2>
<div class="add-block1">
	<?php echo $this->Html->link(__l('Add'), array('controller' => 'forums', 'action' => 'add'), array('class' => 'add','title'=>__l('Add'))); ?>
</div>
<?php echo $this->element('paging_counter');?>
<table class="list forum-list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
        <th><?php echo $this->Paginator->sort(__l('Last Reply'),'modified');?></th>
        <th><?php echo $this->Paginator->sort(__l('Replies'),'forum_comment_count');?></th>
        <th><?php echo $this->Paginator->sort(__l('Views'),'forum_view_count');?></th>
    </tr>
<?php
if (!empty($forums)):

$i = 0;
foreach ($forums as $forum):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>		
		<td><h4>
			<?php echo $this->Html->link($this->Html->cText($forum['Forum']['title'], false), array('controller' => 'forums', 'action' => 'view', $forum['Forum']['id']), array('title' => $this->Html->cText($forum['Forum']['title'],false), 'escape' => false));?></h4>
			<p><?php echo sprintf(__l('Started on %s by '), $this->Html->cDateTime($forum['Forum']['created'], false)); ?>
            <?php
				echo $this->Html->getUserAvatar($forum['User'], 'micro_thumb');
            ?>
			<?php echo $this->Html->link($this->Html->cText($forum['User']['username'], false), array('controller' => 'users', 'action' => 'view', $forum['User']['username']), array('title'=>$this->Html->cText($forum['User']['username'], false), 'escape' => false)); ?></p>
                      
            <?php if($this->Auth->user('id') == $forum['Forum']['user_id']): ?>
                <p class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $forum['Forum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $forum['Forum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span>
                </p>
            <?php endif; ?>
         </td>
		<td>
        	<?php if($forum['Forum']['forum_comment_count'] && !empty($forum['ForumComment'])): ?>
                <div>
                <h4><?php echo __l('by '); echo $this->Html->getUserAvatar($forum['ForumComment'][0]['User'], 'micro_thumb'); echo' '. $this->Html->link($this->Html->cText($forum['ForumComment'][0]['User']['username'],false), array('controller' => 'users', 'action' => 'view', $forum['ForumComment'][0]['User']['username'],'admin' => false), array('title' => $forum['ForumComment'][0]['User']['username'])).' '.__l('on');?></h4>
					<?php
						$splitDateTime = explode(' ', $forum['ForumComment'][0]['created']);
						$title = $splitDateTime[0].'T'.$splitDateTime[1].'Z';
					?>		
					<span class="js-timestamp" title ="<?php echo $title; ?>">
						<?php echo date('Y-m-d H:i:s' , strtotime($forum['ForumComment'][0]['created'] . ' GMT')); ?>
					</span>
                        </div>
             <?php else:?>
             		<p><?php echo __l('No Reply Yet'); ?></p>
             <?php endif;?>
        </td>
		<td><?php echo $this->Html->cInt($forum['Forum']['forum_comment_count']);?></td>
		<td><?php echo $this->Html->cInt($forum['Forum']['forum_view_count']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="4"><p class="notice"><?php echo __l('No Forums available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
<div class="js-pagination">
<?php
if (!empty($forums)) {?>
   <?php echo $this->element('paging_links');?>
   
<?php }
?>
</div>
</div>