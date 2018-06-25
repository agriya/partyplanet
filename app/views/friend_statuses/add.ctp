<?php /* SVN: $Id: $ */ ?>
<div class="friendStatuses form">
<div class="form-content-block">
<?php echo $this->Form->create('FriendStatus', array('class' => 'normal'));?>
	<fieldset>
 		<legend><?php echo $this->Html->link(__l('Friend Statuses'), array('action' => 'index'));?> &raquo; <?php echo __l('Add Friend Status');?></legend>
	<?php
		echo $this->Form->input('name');
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add'));?>
    </div>
</div>
</div>
