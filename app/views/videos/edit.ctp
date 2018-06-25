<?php /* SVN: $Id: edit.ctp 980 2009-09-22 12:09:46Z siva_063at09 $ */ ?>
<div class="videos form">

          <div class="form-content-block">
			<?php echo $this->Form->create('Video', array('class' => 'normal')); ?>
            	<fieldset>
            	<?php
            		echo $this->Form->input('id');
            		if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                        echo $this->Form->input('user_id', array('empty' => __l('Select')));
                    endif;
            		echo $this->Form->input('description');
					if (Configure::read('Video.is_enable_video_tags')):
						echo $this->Form->input('tag', array('info' => __l('Comma separated tags. Optional')));
					endif;
					if (Configure::read('Video.is_show_adult_in_video_add')):
						echo $this->Form->input('is_adult_video', array('label' => 'Explicit video?'));
					endif;
					if (Configure::read('Video.is_show_private_in_video_add')):
						echo $this->Form->input('is_private', array('label' => 'Private'));
					endif;
					if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('is_featured', array('label' => 'Featured?'));
						echo $this->Form->input('is_approved', array('label' => 'Approved?'));
						echo $this->Form->input('is_recommend', array('label' => __l('Recommend?')));
					endif;
					if (Configure::read('Video.is_enable_video_comments')):
						echo $this->Form->input('is_allow_to_comment', array('type' => 'select', 'empty' => __l('Please Select'), 'label' => __l('Allow to comment'), 'options' => $privacyTypes));
					endif;
					if (Configure::read('Video.is_enable_video_embed')):
						echo $this->Form->input('is_allow_to_embed', array('type' => 'select', 'empty' => __l('Please Select'), 'label' => __l('Allow to embed'), 'options' => $privacyTypes));
					endif;
					if (Configure::read('Video.is_enable_video_ratings')):
						echo $this->Form->input('is_allow_to_rating', array('type' => 'select', 'empty' => __l('Please Select'), 'label' => __l('Allow to rating'), 'options' => $privacyTypes));
					endif;
					if (Configure::read('Video.is_enable_video_downloads')):
						echo $this->Form->input('is_allow_to_download', array('type' => 'select', 'empty' => __l('Please Select'), 'label' => __l('Allow to download'), 'options' => $privacyTypes));
					endif;
            	?>
            	</fieldset>
            	<div class="submit-block clearfix">
                    <?php echo $this->Form->end(__l('Update'));?>
                </div>
            </div>
     
  
</div>