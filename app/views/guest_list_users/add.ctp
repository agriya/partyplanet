<?php /* SVN: $Id: $ */ ?>
<div class="guestListUsers form">
<h2><?php echo __l('Sign up for this guestlist'); ?></h2>
	<?php echo $this->Form->create('GuestListUser', array('class' => 'normal'));?>
	<h3><?php echo $this->Html->cText($guestList['GuestList']['name']); ?></h3>
	<h$><?php echo __l('Details'); ?>:</h4>
	<?php echo $this->Html->cText($guestList['GuestList']['details']); ?>

	<?php
		$guest_booking_closed_date = $guestList['GuestList']['website_close_date'] . " " . $guestList['GuestList']['website_close_time'];
		if(strtotime(_formatDate('Y-m-d H:i:s', strtotime($guest_booking_closed_date))) > strtotime(date("Y-m-d H:i:s"))) {
			if($guestList['Event']['ticket_fee'] > 0) {
				echo $this->Form->input('rsvp_response_id', array('type'=>'hidden', 'value'=> 1));
			} else {
				echo $this->Form->input('rsvp_response_id', array('type'=>'radio', 'legend' => __l('Are you going?'), 'class' => 'js-guest'));
			}
			echo $this->Form->input('user_id', array('type'=>'hidden'));
			echo $this->Form->input('guest_list_id', array('type'=>'hidden'));
	?>
	<div>
		<fieldset>
	<?php
		$partycounts =array('1'=> $this->Auth->user('username'));
		for($i=1; $i<=$guestList['GuestList']['maximum_guest_limit']; $i++){
			$partycounts[] = $this->Auth->user('username').'+'.$i;
		}
		echo $this->Form->input('in_party_count', array('type'=>'select','options' => $partycounts,'label' => __l('How many?')));
		$start_date = _formatDate('Y-m-d', strtotime($guestList['Event']['start_date']." ".date("H:i:s"))) > date('Y-m-d') ? _formatDate('Y-m-d', strtotime($guestList['Event']['start_date']." ".date("H:i:s"))) : date('Y-m-d');
		$event_dates = array();
		for($i=$start_date; $start_date <= _formatDate('Y-m-d', strtotime($guestList['Event']['end_date'] . " " . date("H:i:s")));){
			$date = $start_date;
			$event_dates[$date] = $date;
			$start_date = date('Y-m-d', strtotime($start_date . ' + 1 day'));
		}
	?>
		</fieldset>
	</div>
	<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Get on guestlist')); ?>
    </div>
<?php
	}
else{
	?>
	<div>
	<?php
	echo __l('List closed at').' '. $this->Html->cTime($guestList['GuestList']['website_close_time']);
	?>
	</div>
	<?php
}
	echo $this->Form->end();?>
</div>
