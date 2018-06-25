<?php /* SVN: $Id: $ */ ?>
<div id="breadcrumb">
	<?php echo $this->Html->addCrumb(__l('Forums')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<div class="forumCategories index">
<h2><?php echo __l('Discussion') . ' ';?><span><?php echo __l('Forums');?></span></h2>
<?php echo $this->element('paging_counter');?>
<table class="list forum-list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Title'),'title');?></th>
        <th class="last-post"><?php echo $this->Paginator->sort(__l('Last Post'),'modified');?></th>
        <th><?php echo $this->Paginator->sort(__l('Forums'),'forum_count');?></th>
        <th><?php echo $this->Paginator->sort(__l('Posts'),'forum_post_count');?></th>
        <th><?php echo $this->Paginator->sort(__l('Views'),'forum_view_count');?></th>
    </tr>
<?php
if (!empty($forumCategories)):
$i = 0;
foreach ($forumCategories as $forumCategory):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td>
			<h4><?php echo $this->Html->link($this->Html->cText($forumCategory['ForumCategory']['title'], false), array('controller' => 'forums', 'action' => 'index', $forumCategory['ForumCategory']['slug'], 'admin' => false), array('title' => $forumCategory['ForumCategory']['title']));?></h4>
			<p><?php echo $this->Html->truncate($forumCategory['ForumCategory']['description']);?></p>
        </td>
		<td class="last-post">
        	<?php if($forumCategory['ForumCategory']['forum_count'] && !empty($forumCategory['Forum'])): ?>
                <div>
               		<?php echo sprintf('%s created by ', $this->Html->cDateTime($forumCategory['Forum'][0]['created'])); ?>
                    <?php
							echo $this->Html->getUserAvatar($forumCategory['Forum'][0]['User'], 'micro_thumb');
                          
					?>
            <?php if(!empty($forumCategory['Forum'][0]['User']['username'])):?>
            <?php echo $this->Html->link($this->Html->cText($forumCategory['Forum'][0]['User']['username'],false), array('controller' => 'users', 'action' => 'view', $forumCategory['Forum'][0]['User']['username'],'admin' => false), array('title' => $forumCategory['Forum'][0]['User']['username'])) ; ?>
             <?php endif;?>
                </div>
             <?php else:?>
             		<p><?php echo __l('No Posts Yet'); ?></p>
             <?php endif;?>
        </td>
		<td><?php echo $this->Html->cInt($forumCategory['ForumCategory']['forum_count']);?></td>
		
		<td><?php echo $this->Html->cInt($forumCategory['ForumCategory']['forum_post_count']);?></td>
		<td><?php echo $this->Html->cInt($forumCategory['ForumCategory']['forum_view_count']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9"><p class="notice"><?php echo __l('No Forum Categories available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($forumCategories)) {
    echo $this->element('paging_links');
}
?>
</div>