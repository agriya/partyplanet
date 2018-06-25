<?php /* SVN: $Id: admin_edit.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoComments form">
<h2><?php echo __l('Edit Video Comments');?></h2>
<div class="form-content-block">
<?php echo $this->Form->create('VideoComment', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Video Comments'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Video Comment');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('video_id');
		echo $this->Form->input('user_id');
		echo $this->Form->input('comment');
		echo $this->Form->input('ip');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
