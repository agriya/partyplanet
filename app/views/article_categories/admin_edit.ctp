<?php /* SVN: $Id: $ */ ?>
<div class="articleCategories form">
<?php echo $this->Form->create('ArticleCategory', array('class' => 'normal'));?>
<legend class="crumb"><?php echo $this->Html->link(__l('Article Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Article Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));?>
		<div class="submit-block clearfix"> <?php
            echo $this->Form->submit(__l('Update'));?>
        </div>
         <?php echo $this->Form->end(); ?>
</div>
