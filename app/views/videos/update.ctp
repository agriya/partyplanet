<?php /* SVN: $Id: update.ctp 990 2009-09-25 15:26:25Z siva_063at09 $ */ ?>
<div class="videos form">
    <h2><?php echo __l('Update this uploads')?></h2>
    <div class="form-content-block">
		<?php echo $this->Form->create('Video', array('class' => 'normal', 'action' => 'update'));?>
			<ol class="list clearfix">
				<?php
					$i = 0;
					foreach($videos as $video):
						$class = null;
						if ($i++%2 == 0):
							$class = 'altrow';
						endif;
						if($i == 1):
				?>
					<li class="<?php echo $class; ?> clearfix">
						<div class="update-right">
							<?php
								echo $this->Form->input('Video.'.$video['Video']['id'].'.id', array());
								echo $this->Form->input('Video.'.$video['Video']['id'].'.title', array('label' => __l('Title')));
								echo $this->Form->input('Video.'.$video['Video']['id'].'.description', array('label' => __l('Description')));
								if (Configure::read('Video.is_enable_video_tags')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.tag', array('label' => __l('Tags'), 'info' => __l('Comma separated tags. Optional')));
								endif;
								if (Configure::read('Video.is_show_adult_in_video_add')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_adult_video', array('label' => 'Is explicit video?'));
								endif;
								if (Configure::read('Video.is_show_private_in_video_add')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_private');
								endif;
								if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_featured', array('label' => 'Featured?'));
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_approved', array('label' => 'Approved?'));
								endif;
								if (Configure::read('Video.is_enable_video_comments')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_allow_to_comment', array('type' => 'select', 'empty' => __l('Please Select'), 'options' => $privacyTypes));
								endif;
								if (Configure::read('Video.is_enable_video_embed')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_allow_to_embed', array('type' => 'select', 'empty' => __l('Please Select'), 'options' => $privacyTypes));
								endif;
								if (Configure::read('Video.is_enable_video_ratings')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_allow_to_rating', array('type' => 'select', 'empty' => __l('Please Select'), 'options' => $privacyTypes));
								endif;
								if (Configure::read('Video.is_enable_video_downloads')):
									echo $this->Form->input('Video.'.$video['Video']['id'].'.is_allow_to_download', array('type' => 'select', 'empty' => __l('Please Select'), 'options' => $privacyTypes));
								endif;
							?>
						</div>
					</li>
				<?php
				endif;
					endforeach;
				?>
			</ol>
			<div class="submit-block clearfix">
				<?php echo $this->Form->submit(__l('Update')); ?>
			</div>
		<?php echo $this->Form->end(); ?>
	</div>
</div>