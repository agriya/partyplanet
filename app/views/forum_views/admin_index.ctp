<?php /* SVN: $Id: $ */ ?>
<div class="forumViews index js-response">
<h2><?php echo __l('Forum Views');?></h2>
<?php if(empty($this->request->params['named']['forum'])):?>
<div class="form-content-block">
    <?php echo $this->Form->create('ForumView', array('type' => 'get', 'class' => 'normal', 'action'=>'index')); ?>
    <div class="filter-section">
		<div>
            <?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
        </div>
		<div>
        <div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Search'));?>
        </div>
		</div>
	</div>
    <?php echo $this->Form->end(); ?>
</div>
<?php endif;?>
    <div class="page-counter-block"><?php echo $this->element('paging_counter');?></div>
    <?php echo $this->Form->create('ForumView' , array('class' => 'normal','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
<div class="overflow-block">    
<table class="list">
    <tr>
        <th><?php echo __l('Select'); ?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Forum'), 'Forum.title');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Viewed On'),'created');?></div></th>
        <th><div class="js-pagination"><?php echo $this->Paginator->sort(__l('IP'), 'ip');?> / <?php echo $this->Paginator->sort(__l('Host'), 'host');?></div></th>
        </tr>
<?php
if (!empty($forumViews)):

$i = 0;
foreach ($forumViews as $forumView):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td><?php echo $this->Form->input('ForumView.'.$forumView['ForumView']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$forumView['ForumView']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?></td>
		<td class="actions"><span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $forumView['ForumView']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td>
            <?php if(!empty($forumView['User'])) :
                    echo $this->Html->link($this->Html->cText($forumView['User']['username'],false), array('controller'=> 'users', 'action'=>'view', $forumView['User']['username'], 'admin' => false), array('escape' => false, 'title' => $this->Html->cText($forumView['User']['username'],false)));
                  else :
                    echo __l('Guest');
                  endif;
            ?>
        </td>
		<td><?php echo $this->Html->link($this->Html->cText($forumView['Forum']['title'],false), array('controller' => 'forums', 'action' => 'view', $forumView['Forum']['id'], 'admin' => false), array('escape' => false, 'title' => $this->Html->cText($forumView['Forum']['title'],false)));?></td>
		<td><?php echo $this->Html->cDateTime($forumView['ForumView']['created']);?></td>
		<td><?php echo $this->Html->link($this->Html->cText($forumView['ForumView']['ip'], false),'http://whois.sc/'.$forumView['ForumView']['ip'],array('target' => 'blank'));?> / <?php echo $this->Html->cText($forumView['ForumView']['host']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="6"><p class="notice"><?php echo __l('No Forum Views available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($forumViews)) : ?>
    <div>
		<?php echo __l('Select:'); ?>
		<?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all', 'title' => __l('All'))); ?>
		<?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none', 'title' => __l('None'))); ?>
	</div>
	<div class="js-pagination">
        <?php echo $this->element('paging_links'); ?>
    </div>
	<div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
    <div class="hide">
    <div class="submit-block clearfix">
	    <?php echo $this->Form->submit('Submit'); ?>
    </div>
    </div>
    <?php
endif;
echo $this->Form->end();
?>
</div>