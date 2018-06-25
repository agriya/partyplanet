<?php /* SVN: $Id: admin_add.ctp 7750 2009-12-01 09:29:40Z kanagavel_113at09 $ */ ?>
<div class="videoCategories form ">
<?php
echo $this->Form->create('VideoCategory', array(
    'class' => 'normal',
    'enctype' => 'multipart/form-data'
)); ?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Video Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Video Category');?></legend>
	<?php
    echo $this->Form->input('name');
	echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add')); ?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>

