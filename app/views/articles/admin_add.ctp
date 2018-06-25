<?php /* SVN: $Id: $ */ ?>
<?php echo $this->element('js_tiny_mce_setting');?>
<div class="articles form">

<?php echo $this->Form->create('Article', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('title');
		echo $this->Form->input('article_category');
		echo $this->Form->input('description',array('class' => 'js-editor', 'label' => __l('Description')));
		echo $this->Form->input('article_comment_count');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
		echo $this->Form->input('ArticleTag');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>

</div>
</div>
