<?php /* SVN: $Id: $ */ ?>
<div class="articleComments form clearfix js-add-article-comment-response">
<div class="form-content-block">
	<h3> <?php echo __l('Add Comment');?></h3>
<?php
echo $this->Form->create('ArticleComment', array('class' => "normal comment-form clearfix js-comment-form {container:'js-add-article-comment-response',responsecontainer: 'js-index-article-comment-response'}"));

?>
	<fieldset>
	<?php
		echo $this->Form->input('article_slug', array('type'=>'hidden'));
		echo $this->Form->input('article_id', array('type'=>'hidden'));
		if(!$this->Auth->sessionValid()):
			echo $this->Form->input('name', array('type'=>'text'));
		else:
			echo $this->Form->input('name', array('type'=>'hidden','value'=>$this->Auth->user('username')));
		endif;

		echo $this->Form->input('title');
		echo $this->Form->input('comment');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>

