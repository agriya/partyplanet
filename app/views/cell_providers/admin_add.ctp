<?php /* SVN: $Id: $ */ ?>
<div class="cellProviders form">
<div class="form-content-block">
<?php echo $this->Form->create('CellProvider', array('class' => 'normal'));?>
	<fieldset>
	<legend><?php echo $this->Html->link(__l('Cell Providers'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Cell Provider');?></legend>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('is_active', array('type'=>'checkbox','label' =>__l('Active?')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>
