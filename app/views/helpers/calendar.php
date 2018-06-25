<?php
/**
 * Calendar Helper for CakePHP
 *
 * Copyright 2007-2008 John Elliott
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 *
 * @author John Elliott
 * @copyright 2008 John Elliott
 * @link http://www.flipflops.org More Information
 * @license http://www.opensource.org/licenses/mit-license.php The MIT License
 *
 */
class CalendarHelper extends Helper
{
    var $month_list = array(
        'january',
        'february',
        'march',
        'april',
        'may',
        'june',
        'july',
        'august',
        'september',
        'october',
        'november',
        'december'
    );
    var $day_list = array(
        'Mon',
        'Tue',
        'Wed',
        'Thu',
        'Fri',
        'Sat',
        'Sun'
    );
    var $helpers = array(
        'Html',
        'Form'
    );
    /**
     * Perpares a list of GET params that are tacked on to next/prev. links.
     * @retunr string - urlencoded GET params.
     */
    function getParams()
    {
        $params = array();
        foreach($this->params['url'] as $key => $val) if ($key != 'url') $params[] = urlencode($key) . '=' . urlencode($val);
        return (count($params) > 0 ? '?' . join('&', $params) : '');
    }
    /**
     * Generates a Calendar for the specified by the month and year params and populates it with the content of the data array
     *
     * @param $year string
     * @param $month string
     * @param $data array
     * @param $base_url
     * @return string - HTML code to display calendar in view
     *
     */
    function month($year = '', $month = '', $data = '', $base_url = '', $user_id = null, $type)
    {
        $str = '';
        $day = 1;
        $today = 0;
        if ($year == '' || $month == '') { // just use current yeear & month
            $year = date('Y');
            $month = date('m');
        }
        $flag = 0;
        for ($i = 0; $i < 12; $i++) {
            if (strtolower($month) == $this->month_list[$i]) {
                if (intval($year) != 0) {
                    $flag = 1;
                    $month_num = $i+1;
                    break;
                }
            }
        }
        if ($flag == 0) {
            $year = date('Y');
            $month = date('F');
            $month_num = date('m');
        }
        $next_year = $year;
        $prev_year = $year;
        $next_month = intval($month_num) +1;
        $prev_month = intval($month_num) -1;
        if ($next_month == 13) {
            $next_month = 1;
            $next_year = intval($year) +1;
        } else {
            $next_month = $next_month;
        }
        if ($prev_month == 0) {
            $prev_month = 12;
            $prev_year = intval($year) -1;
        } else {
            $prev_month = $prev_month;
        }
        if ($year == date('Y') && strtolower($month) == strtolower(date('F'))) {
            // set the flag that shows todays date but only in the current month - not past or future...
            $today = date('j');
        }
        $days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));
        $first_day_in_month = date('D', mktime(0, 0, 0, $month_num, 1, $year));
        $str.= '<table class="calendar">';
        $str.= '<thead>';
        $str.= '<tr><th class="cell-prev">';
        $str.= $this->Html->link(__l('prev') , array('controller' => 'events', 'action'=>'user_events', $prev_month, $prev_year, $user_id, $type), array('class' => 'js-calendar-prev ui-datepicker-prev ui-corner-all'));
        $str.= '</th><th colspan="5">' . ucfirst($month) . ' ' . $year . '</th><th class="cell-next">';
        $str.= $this->Html->link(__l('next') , array('controller' => 'events', 'action'=>'user_events', $next_month, $next_year, $user_id, $type), array('class' => 'js-calendar-next ui-datepicker-next ui-corner-all'));
        $str.= '</th></tr>';
        $str.= '<tr>';
        for ($i = 0; $i < 7; $i++) {
            $str.= '<th class="cell-header">' . $this->day_list[$i] . '</th>';
        }
        $str.= '</tr>';
        $str.= '</thead>';
        $str.= '<tbody>';
        while ($day <= $days_in_month) {
            $str.= '<tr>';
            for ($i = 0; $i < 7; $i++) {
                $cell = '&nbsp;';
                $onClick = '';
                $class = '';
                $style = '';
                if ($i > 4) {
                    $class = "class ='cell-weekend";
                }
                if ($day == $today) {
					if (!empty($class)) {
						$class .= " cell-today";
					} else {
						$class = "class='cell-today";
					}
                }
                if (isset($data[$day])) {
               
                    if (is_array($data[$day])) {
                        if (isset($data[$day]['onClick'])) {
                            $onClick = ' onClick="' . $data[$day]['onClick'] . '"';
                            $style = ' style="cursor:pointer;"';
                        }
                        if (isset($data[$day]['content'])) $cell = $data[$day]['content'];
                        if (isset($data[$day]['class'])) {
							$class .= $data[$day]['class'];
						} else {
							$highlight_class = 'highlight';
							if (strtotime('now') > strtotime($year . '-' . $month_num . '-' . $day)) {
								$highlight_class = 'past-highlight';
							}
							if(!empty($class)) {
								$class .= ' ' . $highlight_class;
							} else {
								$class = "class = '" . $highlight_class;
							}
						}
                    } else $cell = $data[$day];
                }
				if (!empty($class)) {
					$class .= "'";
				}
				$params_type=!empty($this->params['named']['type'])?$this->params['named']['type']:'';
				$day_link = $this->Html->link($day, array('controller' => 'events', 'action' => 'index', 'type'=>$params_type, 'time_str' => strtotime($year.'-'.date("m", strtotime("01 -".$month.'-'.$year)).'-'.$day)), array());
                  $active_class = '';
                  if(!empty($data['search_time_str']) and strtotime($year.'-'.date("m", strtotime($month)).'-'.$day) == $data['search_time_str']){
                   $active_class = 'active';
                  }
				if (($first_day_in_month == $this->day_list[$i] || $day > 1) && ($day <= $days_in_month)) {
                    $str.= '<td  '. $class .$style . $onClick . ' id="cell-' . $day . '">';
					if ($cell == '&nbsp;') {
						$str.= '<div class="month-cell-number '.$active_class.'">' . $day_link . '</div>';
					} else {
						$str.= '<div class="cell-data '.$active_class.'">' . $day_link . '</div>';
					}
					$str .= '</td>';
                    $day++;
                } else {
                    $str.= '<td  ' . $class . '>&nbsp;</td>';
                }
            }
            $str.= '</tr>';
        }
        $str.= '</tbody>';
        $str.= '</table>';
        return $str;
    }
    /**
     * Generates a Calendar for the week specified by the day, month and year params and populates it with the content of the data array
     *
     * @param $year string
     * @param $month string
     * @param $day string
     * @param $data array[day][hour]
     * @param $base_url
     * @return string - HTML code to display calendar in view
     *
     */
    function week($year = '', $month = '', $day = '', $data = '', $base_url = '', $min_hour = 8, $max_hour = 24)
    {
        $str = '';
        $today = 0;
        if ($year == '' || $month == '') { // just use current yeear & month
            $year = date('Y');
            $month = date('F');
            $day = date('d');
            $month_num = date('m');
        }
        $flag = 0;
        for ($i = 0; $i < 12; $i++) {
            if (strtolower($month) == $this->month_list[$i]) {
                if (intval($year) != 0) {
                    $flag = 1;
                    $month_num = $i+1;
                    break;
                }
            }
        }
        if ($flag == 1) {
            $days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));
            if ($day <= 0 || $day > $days_in_month) $flag = 0;
        }
        if ($flag == 0) {
            $year = date('Y');
            $month = date('F');
            $month_num = date('m');
            $day = date('d');
            $days_in_month = date("t", mktime(0, 0, 0, $month_num, 1, $year));
        }
        $next_year = $year;
        $prev_year = $year;
        $next_month = intval($month_num);
        $prev_month = intval($month_num);
        $next_week = intval($day) +7;
        $prev_week = intval($day) -7;
        if ($next_week > $days_in_month) {
            $next_week = $next_week-$days_in_month;
            $next_month++;
        }
        if ($prev_week <= 0) {
            $prev_month--;
            $prev_week = date('t', mktime(0, 0, 0, $prev_month, $year)) +$prev_week;
        }
        $next_month_num = null;
        if ($next_month == 13) {
            $next_month_num = 1;
            $next_month = 'january';
            $next_year = intval($year) +1;
        } else {
            $next_month_num = $next_month;
            $next_month = $this->month_list[$next_month-1];
        }
        $prev_month_num = null;
        if ($prev_month == 0) {
            $prev_month_num = 12;
            $prev_month = 'december';
            $prev_year = intval($year) -1;
        } else {
            $prev_month_num = $prev_month;
            $prev_month = $this->month_list[$prev_month-1];
        }
        if ($year == date('Y') && strtolower($month) == strtolower(date('F'))) {
            // set the flag that shows todays date but only in the current month - not past or future...
            $today = date('j');
        }
        //count back day until its monday
        while (date('D', mktime(0, 0, 0, $month_num, $day, $year)) != 'Mon') $day--;
        $title = '';
        if ($day+6 > $days_in_month) {
            if ($next_month == 'january') $title = ucfirst($month) . ' ' . $year . ' / ' . ucfirst($next_month) . ' ' . ($year+1);
            else $title = ucfirst($month) . '/' . ucfirst($next_month) . ' ' . $year;
        } else $title = ucfirst($month) . ' ' . $year;
        $str.= '<table class="calendar">';
        $str.= '<thead>';
        $str.= '<tr><th class="cell-prev">';
        $str.= $this->Html->link(__l('prev', true) , $base_url . '/' . $prev_year . '/' . $prev_month . '/' . $prev_week . $this->getParams());
        $str.= '</th><th colspan="5">' . $title . '</th><th class="cell-next">';
        $str.= $this->Html->link(__l('next', true) , $base_url . '/' . $next_year . '/' . $next_month . '/' . $next_week . $this->getParams());
        $str.= '</th></tr>';
        $str.= '<tr>';
        for ($i = 0; $i < 7; $i++) {
            $offset = 0;
            if ($day+$i > $days_in_month) $offset = $days_in_month;
            else if ($day+$i < 1) $offset = -date('t', mktime(1, 1, 1, $prev_month_num, 1, $prev_year));
            $str.= '<th class="cell-header">' . $this->day_list[$i] . '<br/>' . ($day+$i-$offset) . '</th>';
        }
        $str.= '</tr>';
        $str.= '</thead>';
        $str.= '<tbody>';
        for ($hour = $min_hour; $hour < $max_hour; $hour++) {
            $str.= '<tr>';
            for ($i = 0; $i < 7; $i++) {
                $offset = 0;
                if ($day+$i > $days_in_month) $offset = $days_in_month;
                else if ($day+$i < 1) $offset = -date('t', mktime(1, 1, 1, $prev_month_num, 1, $prev_year));
                $cell = '';
                $onClick = '';
                $style = '';
                $class = '';
                if ($i > 4) {
                    $class = ' class="cell-weekend" ';
                }
                if (($day+$i) == $today && $month_num == date('m') && $year == date('Y')) {
                    $class = ' class="cell-today" ';
                }
                if (isset($data[$day+$i-$offset][$hour])) {
                    if (is_array($data[$day+$i-$offset][$hour])) {
                        if (isset($data[$day+$i-$offset][$hour]['onClick'])) {
                            $onClick = ' onClick="' . $data[$day+$i-$offset][$hour]['onClick'] . '"';
                            $style = ' style="cursor:pointer;"';
                        }
                        if (isset($data[$day+$i-$offset][$hour]['content'])) $cell = $data[$day+$i-$offset][$hour]['content'];
                        if (isset($data[$day+$i-$offset][$hour]['class'])) $class = ' class="' . $data[$day+$i-$offset][$hour]['class'] . '"';
                    } else $cell = $data[$day+$i-$offset][$hour];
                }
                $str.= '<td ' . $class . $onClick . $style . ' id="cell-' . ($day+$i-$offset) . '-' . $hour . '"><div class="week-cell-number">' . $hour . ':00' . '</div><div class="cell-data">' . $cell . '</div></td>';
            }
            $str.= '</tr>';
        }
        $str.= '</tbody>';
        $str.= '</table>';
        return $str;
    }
}
?>
