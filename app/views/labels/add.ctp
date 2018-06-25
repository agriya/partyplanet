<?php /* SVN: $Id: $ */ ?>
<div class="message-label">
<?php echo $this->element('message_message-left_sidebar', array('cache' => array('config' => 'sec')));?>
<div class="labels form">
    <h2  class="message-title-info"><?php echo __l('Create Label'); ?></h2>
    <div class="form-blocks js-corner round-5">
        <?php
            echo $this->Form->create('Label', array('class' => 'normal js-form'));
            echo $this->Form->input('name');
        ?>
		<div class="clearfix submit-block">
		<?php
		echo $this->Form->end(__l('Add')); ?></div>
		<?php
		echo $this->Form->end(); ?>
    </div>
	</div>
</div>