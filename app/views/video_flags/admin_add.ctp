<?php /* SVN: $Id: admin_add.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoFlags form">
  <h2><?php echo __l('Add Video Flags');?></h2>
<div class="form-content-block">
<?php echo $this->Form->create('VideoFlag', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Video Flags'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Video Flag');?></legend>
	<?php
		echo $this->Form->input('user_id');
		echo $this->Form->input('video_id');
		echo $this->Form->input('video_flag_category_id');
		echo $this->Form->input('message');
		echo $this->Form->input('ip');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
</div>
