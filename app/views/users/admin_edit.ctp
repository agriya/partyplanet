<?php /* SVN: $Id: $ */ ?>
<div class="users form">
<div class="form-content-block">
<?php echo $this->Form->create('User', array('class' => 'normal'));?>
	<fieldset>
 	
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('email');
		echo $this->Form->input('password');
		echo $this->Form->input('username');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Update'));?>
    </div>
</div>
</div>