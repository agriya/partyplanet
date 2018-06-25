<?php /* SVN: $Id: $ */ ?>
<div class="venueComments form clearfix js-add-venue-comment-response">
		<?php
			echo $this->Form->create('VenueComment', array('class' => "normal comment-form js-comment-form {container:'js-add-venue-comment-response',responsecontainer:'js-index-venue-comment-response'}"));
			echo $this->Form->input('venue_slug', array('type' => 'hidden'));
			echo $this->Form->input('venue_id', array('type' => 'hidden'));
			if (!$this->Auth->sessionValid()):
				echo $this->Form->input('name', array('type' => 'text'));
			else:
				echo $this->Form->input('name', array('type' => 'hidden', 'value' => $this->Auth->user('username')));
			endif;?>
			<div class="required">
			<?php
			echo $this->Form->input('title');
			echo $this->Form->input('comment', array('type'=>'textarea','label' => __l('Reviews')));?>
			</div>
			<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Submit')); ?>
		</div>
		<?php echo $this->Form->end(); ?>

</div>