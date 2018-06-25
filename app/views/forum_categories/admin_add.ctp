<?php /* SVN: $Id: $ */ ?>
<div class="crumb">
	<?php $this->Html->addCrumb(__l('Forum Category'), array('controller' => 'forum_categories', 'action' => 'index')); ?>
	<?php $this->Html->addCrumb(__l('Add Forum Category')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; '); ?>
</div>
<div class="forumCategories form">
<?php echo $this->Form->create('ForumCategory', array('class' => 'normal'));?>
	<fieldset>
 	
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('description');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>