<div class="userOpenids form main-content-block round-5 js-corner">
<h2><?php echo __l('Add New OpenID'); ?></h2>
<div class="form-content-block">
<?php echo $this->Form->create('UserOpenid', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('openid', array('id' => "openid_identifier", 'class' => 'bg-openid-input', 'label' => __l('OpenID')));
	?>
	</fieldset>
		<div class="submit-block clearfix">
<?php echo $this->Form->end(__l('Add'));?>
</div>
</div>
</div>
<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/widget/button/1"></script>