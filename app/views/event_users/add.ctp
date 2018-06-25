<?php /* SVN: $Id: $ */ ?>
<div class="eventUsers form">
<div class="form-content-block">
<?php echo $this->Form->create('EventUser', array('class' => 'normal'));?>
	<fieldset>
 		<?php
		//echo $this->Form->input('event_id', array('type'=>'hidden', 'value'=>$event_id));
		echo $this->Form->input('event_slug', array('type'=>'hidden', 'value'=>$event['Event']['slug']));
		echo $this->Form->input('event_id', array('type'=>'hidden', 'value'=>$event['Event']['id']));
	?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add me'));?>
    </div>
</div>
</div>
