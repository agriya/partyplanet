<?php /* SVN: $Id: admin_add.ctp 620 2009-07-14 14:04:22Z boopathi_23ag08 $ */ ?>
<div class="photoFlagCategories form">
<?php echo $this->Form->create('PhotoFlagCategory', array('class' => 'normal'));?>
	<fieldset>
 		<legend class="crumb"><?php echo $this->Html->link(__l('Photo Flag Categories'), array('action' => 'index'),array('title' => __l('Photo Flag Categories')));?> &raquo; <?php echo __l('Add Photo Flag Category');?></legend>
    	<?php echo $this->Form->input('name'); ?>
    	<?php echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
