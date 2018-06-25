<?php /* SVN: $Id: $ */ ?>
<div class="foodCaterings form">
<div class="form-content-block">
<?php echo $this->Form->create('FoodCatering', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Food Caterings'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Food Catering');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
		echo $this->Form->input('PartyPlanner',array('div' =>'input select multiple-select'));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>