<?php if(!empty($venues)) { ?>
	<?php
		echo $this->Form->input('Event.venue_id', array('legend' => false, 'before' => '<span class="label-content">Venue</span>', 'options' => $venues, 'type' => 'radio',  'class' => 'js-selected-venue'));
	?>
<?php } else { ?>
	<div class="label-content venue">
		* No venues for current city <?php echo $city_name; ?>. So please add venue
	</div>
<?php } ?>