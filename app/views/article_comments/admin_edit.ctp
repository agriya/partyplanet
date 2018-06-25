<?php /* SVN: $Id: $ */ ?>
<div class="articleComments form">
<?php echo $this->Form->create('ArticleComment', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('comment');
    	echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>