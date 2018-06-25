<?php /* SVN: $Id: add.ctp 789 2009-07-24 07:46:32Z siva_063at09 $ */ ?>
<div class="photos js-responses">

	<div>
		<h2><?php echo __l('Upload Photos');?></h2>
	<div class="crumb">
		<?php
			$this->Html->addCrumb(__l('Galleries'), array('controller' => 'photo_albums', 'action' => 'index', 'admin' => false));
			if (!empty($photoAlbum['Venue']['name'])):
				$this->Html->addCrumb($this->Html->cText($photoAlbum['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $photoAlbum['Venue']['slug'], 'admin' => false));
			endif;
			if (!empty($photoAlbum['Event']['title'])):
				$this->Html->addCrumb($this->Html->cText($photoAlbum['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $photoAlbum['Event']['slug'], 'admin' => false));
			endif;
			if (!empty($photoAlbum['PhotoAlbum']['title'])):
				$this->Html->addCrumb($this->Html->cText($photoAlbum['PhotoAlbum']['title'], false), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug'], 'admin' => false));
			endif;
			$this->Html->addCrumb(__l('Upload Photos'));
			if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
            echo $this->Html->getCrumbs(' &raquo; ');
  	        else:
	        echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
 	        endif; ?>
 	</div>
		<div class="event-link"><?php echo sprintf(__l('You are currently using %s (%s%%) of your %s %s'), $used_size, $size_percentage, Configure::read('photo.allowed_photos_size'), Configure::read('photo.allowed_photos_size_unit')); ?></div>
		<div>
		<div class="form-content-block">
			<?php 
				echo $this->Form->create('Photo', array('class' => 'normal js-upload-form', 'enctype' => 'multipart/form-data'));
				echo $this->Form->input('user_id', array('type' => 'hidden'));
				echo $this->Form->input('photo_album_id', array('type' => 'hidden'));
				echo $this->Form->uploader('Attachment.filename', array('type'=>'file', 'uController' => 'photos', 'uRedirectURL' => array('controller' => 'photos', 'action' => 'update'), 'uId' => 'photoID', 'uQueuefilesize' => $remaining_allowed_size,  'uFilesize' => higher_to_bytes(Configure::read('photo.file.allowedSize'), Configure::read('photo.file.allowedSizeUnits')), 'uFilecount' => Configure::read('photo.maximum_photos_per_upload'), 'uFiletype' => Configure::read('photo.file.allowedExt')));
		 ?>
			<div class="js-validation-part">
				<?php
					if (Configure::read('photo.is_allow_photo_tag')):
						echo $this->Form->input('tag', array('label' => __l('Tags'), 'info' => __l('Comma separated tags. Optional')));
					endif;
					if (Configure::read('photo.is_show_adult_photo_option')):
						echo $this->Form->input('is_adult_photo');
					endif;
					 if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
					echo $this->Form->input('is_hotties', array('label' =>__l('Hotties?')));
				endif;?>
				<?php if (!empty($photoAlbum['PhotoAlbum']['photo_count'])): ?>
					<div class="submit-block clearfix">
						<?php echo $this->Form->submit(__l('Upload More Photos')); ?>
						<div class="cancel-block clearfix">
							<?php echo $this->Html->link(__l('Finish or Cancel'), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug'], 'admin' => false)); ?>
						</div>
					</div>
				<?php else: ?>
					<div class="submit-block clearfix">
						<?php echo $this->Form->submit(__l('Upload Photos')); ?>
					</div>
				<?php endif; ?>
			</div>
			<?php echo $this->Form->end(); ?>
			</div>
		</div>
	</div>
</div>