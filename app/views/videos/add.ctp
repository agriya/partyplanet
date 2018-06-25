<?php /* SVN: $Id: add.ctp 1007 2009-10-04 06:51:54Z siva_063at09 $ */ ?>
<div class="videos clearfix js-responses">
	<div class="side1-block">
		<h2 ><?php echo __l('Upload'); ?><span><?php echo ' ' . __l('Videos'); ?></span></h2>
		<div class="index_inner">
<div class="crumb">
  <?php
			$this->Html->addCrumb(__l('Videos'), array('controller' => 'videos', 'action' => 'index', 'admin' => false));
			if ($this->Auth->user('user_type_id') == ConstUserTypes::User)
			{
			if (!empty($venue['Venue']['name'])):
				$this->Html->addCrumb($this->Html->cText($venue['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'], 'admin' => false));
			elseif (!empty($event['Event']['title'])):
				$this->Html->addCrumb($this->Html->cText($event['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin' => false));
			elseif ($this->Auth->user('username')):
				$this->Html->addCrumb($this->Html->cText($this->Auth->user('username'), false), array('controller' => 'users', 'action' => 'view', $this->Auth->user('username'), 'admin' => false));
			endif;
			}
            $this->Html->addCrumb(__l('Upload Videos'));
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
            echo $this->Html->getCrumbs(' &raquo; ');
            else:
  	        echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
  	        endif;?>
 	</div>
		<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin): ?>
			<div class="event-link"><?php echo sprintf(__l('You are currently using %s (%s%%) of your %s %s'), $used_size, $size_percentage, Configure::read('Video.allowed_videos_size'), Configure::read('Video.allowed_videos_size_unit')); ?></div>
		<?php endif; ?>
	
			<div class="form-content-block">
			<?php
				echo $this->Form->create('Video', array('class' => 'normal js-upload-form', 'enctype' => 'multipart/form-data', 'id' => 'VideoAddForm'));
				echo $this->Form->input('uploaded_via', array('type' => 'hidden'));
				echo $this->Form->input('class', array('type' => 'hidden'));
				echo $this->Form->input('foreign_id', array('type' => 'hidden'));
				if(ConstUploadedVia::File == $uploaded_via):
					echo $this->Form->uploader('Attachment.filename', array('type'=>'file', 'uController' => 'videos', 'uRedirectURL' => array('controller' => 'videos', 'action' => 'update'), 'uId' => 'videoID', 'uQueuefilesize' => $queue_allowed_size,  'uFilesize' => $remaining_allowed_size, 'uFilecount' => Configure::read('Video.maximum_videos_per_upload'), 'uFiletype' => Configure::read('video.file.allowedExt')));
				elseif(ConstUploadedVia::Record == $uploaded_via):
			?>
					<div id="video-recorder">
						<?php
							echo $this->Html->link('Record Video', array('controller' => 'videos', 'action' => 'r', '?' => array('width' => '425', 'height' => '344', 'wmode' => 'transparent', 'allowfullscreen' => 'true', 'name' => 'record_video'), 'admin' => false), array('class' => 'js-flash'));
						?>
					</div>
			<?php
				endif;
			?>
			<div class="js-validation-part">
				<fieldset>
					<?php if(ConstUploadedVia::File == $uploaded_via): ?>
						<div class="notice"><?php echo __l('Set common settings for all the videos; you can update them in the next page.'); ?></div>
					<?php endif; ?>
					<?php
						if (ConstUploadedVia::Embed == $uploaded_via):
							echo $this->Form->input('embed_code');
						endif;
						if (ConstUploadedVia::File != $uploaded_via):
							echo $this->Form->input('title');
						endif;
						if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
							echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
						else:
							echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
						endif;
						echo $this->Form->input('video_category_id', array('type' => 'select', 'empty' => __l('Please Select'), 'label' => __l('Video Category')));
						if (Configure::read('Video.is_enable_video_tags')):
							echo $this->Form->input('tag', array('label' => __l('Tags'), 'help' => __l('Comma separated tags. Optional')));
						endif;
						if (Configure::read('Video.is_show_adult_in_video_add')):
							echo $this->Form->input('is_adult_video', array('label' => __l('Explicit video?')));
						endif;
						if (Configure::read('Video.is_show_private_in_video_add')):
							echo $this->Form->input('is_private', array('label' => __l('Private')));
						endif;
						if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
							echo $this->Form->input('is_featured', array('label' => __l('Featured')));
							echo $this->Form->input('is_approved', array('label' => __l('Approved')));
							echo $this->Form->input('is_recommend', array('label' => __l('Recommend')));
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
    				<?php echo $this->Form->submit(__l('Next')); ?>
                </div>
			</div>
			<?php echo $this->Form->end(); ?>
			</div>
	</div>
	</div>
</div>