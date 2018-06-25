<?php
	if(!empty($sdate)){
		$datearr = explode('-',$sdate);
		if(count($datearr) == 1){
			$sdate = date('Y-m-d', $sdate);
		}
	}
	$month = (!empty($sdate)) ? date('n', strtotime($sdate)) : date('n');
	$year = (!empty($sdate)) ? date('Y', strtotime($sdate)) : date('Y');
	echo $this->requestAction(array('controller' => 'events', 'action' => 'user_events', $month, $year, 'type' => $type, 'sdate' => $sdate,'time_str'=>$time_str), array('return'));
?>