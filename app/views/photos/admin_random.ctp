<?php /* SVN: $Id: admin_random.ctp 16778 2012-01-20 12:37:20Z siva_063at09 $ */ ?>
<?php
	echo $this->Form->create('Photo', array('action' => 'random', 'class' => 'normal', 'enctype' => 'multipart/form-data'));
	echo $this->Form->input('user_id', array('type' => 'hidden', 'value' => $this->Auth->user('id')));
	echo $this->Form->input('title', array('label' => __l('Title')));
	echo $this->Form->input('Attachment.filename', array('type' => 'file','size' => '33', 'label' => 'Upload Photo','class' =>'browse-field'));
?>
<div class="submit-block clearfix">
	<?php echo $this->Form->submit(__l('Upload Photos')); ?>
</div>
<?php echo $this->Form->end(); ?>