<?php /* SVN: $Id: $ */ ?>
<div class="forumComments form js-add-forum-comment-response">
<div class="forumComments-add-block js-corner round-5">
<h2><?php echo __l('Add Forum Comments');?></h2>
<div class="form-content-block">
<?php echo $this->Form->create('ForumComment', array('class' => "normal js-comment-form {container:'js-add-forum-comment-response',responsecontainer:'js-index-forum-comment-response'}"));?>
	<?php
		echo $this->Form->input('forum_id', array('type' => 'hidden'));
        echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
		echo $this->Form->input('comment');
	?>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>
</div>