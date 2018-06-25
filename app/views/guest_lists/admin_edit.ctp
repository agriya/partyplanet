<?php /* SVN: $Id: $ */ ?>
<div class="guestLists form">
<div class="form-content-block">
<?php echo $this->Form->create('GuestList', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Guest Lists'), array('action' => 'index'));?> &raquo; <?php echo __l('Edit Guest List');?></legend>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('name');
		echo $this->Form->input('details');
		echo $this->Form->input('guest_limit');
		echo $this->Form->input('event_id');
		echo $this->Form->input('maximum_guest_limit');
		echo $this->Form->input('maximum_guest_of_guest');
		echo $this->Form->input('website_close_time');
		echo $this->Form->input('guest_close_time');
		echo $this->Form->input('fax');
		echo $this->Form->input('email');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>
