<?php /* SVN: $Id: admin_edit.ctp 7749 2009-12-01 09:29:27Z kanagavel_113at09 $ */ ?>
<div class="videoCategories form">
<?php echo $this->Form->create('VideoCategory', array('class' => 'normal')); ?>
<legend class="crumb"><?php echo $this->Html->link(__l('Video Categories'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Video Category');?></legend>
	<?php
        echo $this->Form->input('id');
        echo $this->Form->input('name');
        echo $this->Form->input('is_active',array('label'=>__l('Active?')));?>
        <div class="submit-block clearfix">
            <?php echo $this->Form->end(__l('Update')); ?>
        </div>
</div>
