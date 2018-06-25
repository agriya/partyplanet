<?php /* SVN: $Id: $ */ ?>
<div class="cities form">
<?php echo $this->Form->create('City', array('class' => 'normal'));?>
	<fieldset>
	<legend class="crumb"><?php echo $this->Html->link(__l('Cities'), array('action' => 'index'));?> &raquo; <?php echo __l('Add City');?></legend>
	<?php
		echo $this->Form->input('country_id', array('empty' => 'Select Country'));
		echo $this->Form->input('name');
		echo $this->Form->input('city_code');
		echo $this->Form->input('is_approved',array('label'=>__l('Approved?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
