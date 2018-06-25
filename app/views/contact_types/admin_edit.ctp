<?php /* SVN: $Id: $ */ ?>

<div class="contactTypes form">
<legend class="crumb"><?php echo $this->Html->link(__l('Contact Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Contact Type');?></legend>
<?php echo $this->Form->create('ContactType', array('class' => 'normal'));?>
 	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));?>
		<div class="submit-block clearfix">
            <?php echo $this->Form->submit(__l('Update'));?>
        </div>
         <?php echo $this->Form->end(); ?>

</div>
