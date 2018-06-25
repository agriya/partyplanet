<?php /* SVN: $Id: $ */ ?>
<div class="sexualOrientations form form-content-block">
<?php echo $this->Form->create('SexualOrientation', array('class' => 'normal'));?>
<legend><?php echo $this->Html->link(__l('Sexual Orientations'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Sexual Orientation');?></legend>
 <?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
		?>
		<div class="submit-block clearfix">
		<?php
        echo $this->Form->submit(__l('Update'));?>
        </div>
         <?php echo $this->Form->end(); ?>
</div>
