<?php /* SVN: $Id: $ */ ?>
<div class="eventTypes form">
<div class="form-content-block">
<?php echo $this->Form->create('EventType', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('is_active',array('label'=>__l('Active')));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>