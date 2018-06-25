<?php /* SVN: $Id: edit.ctp 946 2009-09-17 08:59:39Z boopathi_026ac09 $ */ ?>
<div class="photos form">
<?php if($this->request->data['Photo']['is_random']): ?>
    
            <?php echo $this->Form->create('Photo', array('class' => 'normal'));
           		echo $this->Form->input('id');
				echo $this->Form->input('Attachment.id', array('type' => 'hidden'));
				echo $this->Form->input('title', array('label' => __l('Title')));
			?>
			<div class="submit-block clearfix">
				<?php echo $this->Form->submit(__l('Update')); ?>
			</div>
			<?php echo $this->Form->end(); ?>
	

<?php else: ?>

    
      <div class="new-album">
              <?php echo $this->Html->link($this->Html->showImage('Photo', $this->request->data['Attachment'], array('dimension' => 'medium_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($this->request->data['Photo']['title'], false)), 'title' => $this->Html->cText($this->request->data['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'view', $this->request->data['Photo']['slug'], 'admin' => false), array('escape' => false));?>
          </div>
            <div class="form-content-block">
            <?php echo $this->Form->create('Photo', array('class' => 'normal'));?>
            	<fieldset>
            	<?php
            		echo $this->Form->input('id');
            		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
                        echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
                    endif;
                     if(Configure::read('photo.is_allow_photo_album')): ?>
                	<?php	echo $this->Form->input('photo_album_id', array('label' => __l('Album'), 'empty' => __l('Please Select'))); ?>
                      <div class="new-album"> <?php  echo $this->Html->link(__l('Create new album'), array('controller' => 'photo_albums', 'action' => 'add'),array('title' => __l('Create new album'))); ?></div>
                    <?php endif;
            		echo $this->Form->input('title');
            		echo $this->Form->input('description');
            		if (Configure::read('photo.is_allow_photo_tag')):
               		echo $this->Form->input('tag', array('info' => __l('Comma separated tags. Optional')));
               		endif;
            		if(Configure::read('photo.is_show_adult_photo_option')):
                		echo $this->Form->input('is_adult_photo');
                    endif;
                    if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
	       			echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	   	       			echo $this->Form->input('is_hotties', array('label' =>__l('Hotties?')));
    				endif;
            	?>
            	</fieldset>
            	<div class="submit-block clearfix">
                    <?php echo $this->Form->submit(__l('Update'));?>
                </div>
                 <?php echo $this->Form->end(); ?>
            </div>
  
<?php endif; ?>
</div>

