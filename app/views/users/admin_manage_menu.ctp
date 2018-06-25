<div>
	<h3><?php echo __l('Edit Scripts');?></h3>
	<div class="form-content-block">
	<?php echo $this->Form->create('User', array('class' => 'normal','action' => 'manage_menu'));?>
	<?php echo $this->Form->input('script', array('type' => 'textarea','class' => 'js-editor', 'value' => $file_content)); ?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->submit(__l('Update'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
</div>