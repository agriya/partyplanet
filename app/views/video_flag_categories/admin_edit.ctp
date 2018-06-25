<?php /* SVN: $Id: admin_edit.ctp 615 2009-07-02 08:00:35Z annamalai_40ag08 $ */ ?>
<div class="videoFlagCategories form">

<?php echo $this->Form->create('VideoFlagCategory', array('class' => 'normal'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Video Flag Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Video Flag Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>

</div>