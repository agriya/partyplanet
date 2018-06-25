<?php /* SVN: $Id: $ */ ?>
<?php echo $this->element('js_tiny_mce_setting');?>
<div class="articles form">
<?php echo $this->Form->create('Article', array('class' => 'normal', 'enctype' => 'multipart/form-data'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('News'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit News');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('title');
		echo $this->Form->input('article_category_id');
		echo $this->Form->input('description',array('class' => 'js-editor', 'label' => __l('Description')));
		echo $this->Form->input('Attachment.filename', array('type' => 'file', 'label' => __l('Article image')));
		echo $this->Form->input('tag', array('label' => __l('Tags'), 'info' => __l('Comma separated tags. Optional')));
		if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
			echo $this->Form->input('is_active', array( 'label' => __l('Active?')));
		endif;
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Update'));?>
    </div>
     <?php echo $this->Form->end(); ?>
</div>
