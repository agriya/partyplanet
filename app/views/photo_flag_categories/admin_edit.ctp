<?php /* SVN: $Id: admin_edit.ctp 620 2009-07-14 14:04:22Z boopathi_23ag08 $ */ ?>
<div class="photoFlagCategories form">
<?php echo $this->Form->create('PhotoFlagCategory', array('class' => 'normal'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Photo Flag Categories'), array('action' => 'index'),array('title' => __l('Photo Flag Categories')));?> &raquo; <?php echo __l('Edit Photo Flag Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
        echo $this->Form->input('is_active',array('label'=>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>

</div>