<?php /* SVN: $Id: $ */ ?>
<div class="siteCategories form">
<?php echo $this->Form->create('SiteCategory', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Site Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Site Category');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active')));
	?>
	</fieldset>
<?php echo $this->Form->end(__l('Update'));?>
</div>
