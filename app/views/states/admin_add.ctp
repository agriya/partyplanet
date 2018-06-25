<?php /* SVN: $Id: $ */ ?>
<div class="states form">
    <?php echo $this->Form->create('State',  array('class' => 'normal','action'=>'add'));?>
	<legend class="crumb"><?php echo $this->Html->link(__l('States'), array('action' => 'index'));?> &raquo; <?php echo __l('Add State');?></legend>
    <?php
        echo $this->Form->input('country_id',array('empty'=>'Please Select'));
        echo $this->Form->input('name');
    ?>
    <div class="submit-block clearfix">
    <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
   
</div>
