<?php /* SVN: $Id: update.ctp 789 2009-07-24 07:46:32Z siva_063at09 $ */ ?>
<div class="photos form">
	<h2><?php echo __l('Update this uploads'); ?></h2>
	<div class="form-content-block">
	<?php echo $this->Form->create('Photo', array('class' => 'normal', 'action' => 'update')); ?>
		<?php
			if (!empty($this->request->data['Photo']['photo_album_id'])):
				echo $this->Form->input('photo_album_id', array('type' => 'hidden'));
			endif;
		?>
		<ul>
			<?php foreach($photos as $photo): ?>
				<li>
					<?php
						echo $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false)));
                		echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.id');
                		echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.title', array('label' => __l('Title')));
                		echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.description', array('label' => __l('Description')));
						if (Configure::read('photo.is_allow_photo_tag')) :
	                		echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.tag', array('label' => __l('Tags'), 'info' => __l('Comma separated tags')));
						endif;
						if (Configure::read('photo.is_show_adult_photo_option')) :
	                		echo $this->Form->input('Photo.'.$photo['Photo']['id'].'.is_adult_photo');
                        endif;
                    ?>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->end(__l('Update'));?>
        </div>
    </div>
</div>