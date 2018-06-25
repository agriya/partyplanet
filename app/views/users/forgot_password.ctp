<h2 class="title venue-title"><?php echo __l('Forgot your password?');?></h2>
<div class="info-details">
	<?php echo __l('Enter your Email, and we will send you instructions for resetting your password.'); ?>
</div>
<div class="form-content-block">
<?php
	echo $this->Form->create('User', array('action' => 'forgot_password', 'class' => 'normal'));
	echo $this->Form->input('email', array('type' => 'text')); ?>
	<div class="submit-block clearfix">
    	<?php echo $this->Form->end(__l('Send')); ?>
    </div>
</div>