<?php /* SVN: $Id: $ */ ?>
<div class="eventComments form clearfix js-add-event-comment-response">
	<h3><?php echo __l('Submit a Review');?></h3>
	<?php
			echo $this->Form->create('EventComment', array('class' => "normal comment-form clearfix js-comment-form {container:'js-add-event-comment-response',responsecontainer: 'js-index-event-comment-response'}"));
			echo $this->Form->input('event_id', array('type' => 'hidden'));?>
			<div class="required">
			<?php
			echo $this->Form->input('title');
			echo $this->Form->input('comment', array('type'=>'textarea','label' => __l('Reviews')));?>
			</div>
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Add'));?>
		</div>
		<?php echo $this->Form->end(); ?>

</div>