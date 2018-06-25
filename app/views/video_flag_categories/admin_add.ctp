<?php /* SVN: $Id: admin_add.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoFlagCategories form">
<?php echo $this->Form->create('VideoFlagCategory', array('class' => 'normal'));?>

 		<legend class="crumb"><?php echo $this->Html->link(__l('Video Flag Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Video Flag Category');?></legend>
	<?php
		echo $this->Form->input('name');
	?>

	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
