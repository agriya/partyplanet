<?php /* SVN: $Id: $ */ ?>
<div class="forumCategories form">
<?php echo $this->Form->create('ForumCategory', array('class' => 'normal'));?>
  <div class="crumb">
	<?php $this->Html->addCrumb(__l('Forum Category'), array('controller' => 'forum_categories', 'action' => 'index')); ?>
	<?php $this->Html->addCrumb(__l('Edit Forum Category')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; '); ?>
</div>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));?>
		<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Update')); ?>
        </div>
         <?php echo $this->Form->end(); ?>
</div>
