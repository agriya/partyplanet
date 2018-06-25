<?php /* SVN: $Id: $ */ ?>
<div class="musicTypes form">
<?php echo $this->Form->create('MusicType', array('class' => 'normal'));?>
<legend class="crumb"><?php echo $this->Html->link(__l('Music Types'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Music Type');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active?')));
		?>
        	<div class="submit-block clearfix">
		<?php echo $this->Form->submit(__l('Update'));?>
		</div>
		 <?php echo $this->Form->end(); ?>
</div>
