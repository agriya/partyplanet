<?php /* SVN: $Id: admin_edit.ctp 960 2009-09-18 15:58:52Z siva_063at09 $ */ ?>
<div class="targetFileTypes form form-content-block">
<?php echo $this->Form->create('TargetFileType', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Target File Types'), array('action' => 'index'),array('title' => __l('Target File Types')));?> &raquo; <?php echo __l('Edit Target File Type');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('extension');
     	echo $this->Form->input('is_active', array('label' => __l('Active')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Update'));?>
</div>
</div>