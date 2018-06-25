<?php /* SVN: $Id: user_events.ctp 4444 2010-07-24 06:37:21Z chandhra_130at10 $ */ ?>
<h3>
	<?php
		if($this->Auth->sessionValid()):
			if(!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'guest' ):
				echo __l('Guest List Calendar');
			else:
				echo __l('My Calendar');
			endif;
		else:
			echo __l('Event Calendar');
		endif;
	?>
</h3>
<div class="clearfix">
	<?php
		$month_names = array(
			1 => 'january',
			2 => 'february',
			3 => 'march',
			4 => 'april',
			5 => 'may',
			6 => 'june',
			7 => 'july',
			8 => 'august',
			9 => 'september',
			10 => 'october',
			11 => 'november',
			12 => 'december'
		);
		$data = array();
		foreach($events as $event) {
			$data[$event[0]['date']]['content'] = $event[0]['date'];
		}
		if(!empty($this->request->params['named']['time_str'])){
         $data['search_time_str']=$this->request->params['named']['time_str'];
        }
		if (empty($user_id)) :
			$user_id = '';
		endif;
		echo $this->Calendar->month($year, $month_names[$month], $data, null, $user_id, $type);
	?>
</div>