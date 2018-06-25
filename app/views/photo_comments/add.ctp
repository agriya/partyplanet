<?php /* SVN: $Id: add.ctp 735 2009-07-21 16:01:02Z siva_063at09 $ */ ?>
<div class="photoComments form js-add-photo-comment-response">
    <div class="form-content-block">
		<h3><?php echo __l('Add Your Comments');?></h3>
		<?php echo $this->Form->create('PhotoComment', array('class' => "normal js-comment-form {container:'js-add-photo-comment-response',responsecontainer:'js-index-photo-comment-response'}")); ?>
			<?php
					echo $this->Form->input('photo_id', array('type' => 'hidden'));
					if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
					else:
						echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
					endif;
					echo $this->Form->input('comment');
				?>
			
			<div class="submit-block clearfix">
				<?php echo $this->Form->submit(__l('Post Comment'));?>
			</div>
		<?php echo $this->Form->end();?>
	</div>
</div>
