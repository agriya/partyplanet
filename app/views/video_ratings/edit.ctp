<?php /* SVN: $Id: edit.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoRatings form">
<div class="form-content-block">
<?php echo $this->Form->create('VideoRating', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Video Ratings'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Video Rating');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('video_id');
		echo $this->Form->input('ip');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
