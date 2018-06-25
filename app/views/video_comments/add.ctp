<?php /* SVN: $Id: add.ctp 1577 2009-08-12 21:17:54Z siva_063at09 $ */ ?>
	<h3><?php echo __l('Add Your Comments');?></h3>
		<?php echo $this->Form->create('VideoComment', array('class' => "normal clearfix js-comment-form {container:'js-add-video-comment-response',responsecontainer:'js-index-video-comment-response'}"));?>
				<fieldset>
					<div class="form-inner-block videos-view-form form-upload-block clearfix">
						<?php
							echo $this->Form->input('video_id', array('type' => 'hidden'));
							if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
								echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
							else:
								echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
							endif;
							echo $this->Form->input('comment');
						?>
					</div>
				</fieldset>
				<div class="submit-block clearfix">
					<?php echo $this->Form->submit(__l('Post Comment'));?>
				</div>
			<?php echo $this->Form->end();?>

