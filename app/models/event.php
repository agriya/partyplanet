<?php
/**
 * Party Planet
 *
 * PHP version 5
 *
 * @category   PHP
 * @package    partyplanet
 * @subpackage Core
 * @author     Agriya <info@agriya.com>
 * @copyright  2018 Agriya Infoway Private Ltd
 * @license    http://www.agriya.com/ Agriya Infoway Licence
 * @link       http://www.agriya.com
 */
class Event extends AppModel
{
    public $name = 'Event';
    public $displayField = 'title';
    public $actsAs = array(
        'Aggregatable',
		'Sluggable' => array(
            'label' => array(
                'title'
            )
        ) ,
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'title',
                'description'
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'EventCategory' => array(
            'className' => 'EventCategory',
            'foreignKey' => 'event_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'AgeRequirment' => array(
            'className' => 'AgeRequirment',
            'foreignKey' => 'age_requirement_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Venue' => array(
            'className' => 'Venue',
            'foreignKey' => 'venue_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'EventType' => array(
            'className' => 'EventType',
            'foreignKey' => 'event_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'RepeatType' => array(
            'className' => 'RepeatType',
            'foreignKey' => 'repeat_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'MonthlyRepeatType' => array(
            'className' => 'MonthlyRepeatType',
            'foreignKey' => 'monthly_repeat_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Day' => array(
            'className' => 'Day',
            'foreignKey' => 'repeat_on_month_day_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ),
         'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class' => 'Event'
            ) ,
            'dependent' => true
        ) ,
        'GuestList' => array(
            'className' => 'GuestList',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'dependent' => ''
        )
    );
    public $hasMany = array(
        'EventComment' => array(
            'className' => 'EventComment',
            'foreignKey' => 'event_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Video.class' => 'Event'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'PhotoAlbum' => array(
            'className' => 'PhotoAlbum',
            'foreignKey' => 'event_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'EventUser' => array(
            'className' => 'EventUser',
            'foreignKey' => 'event_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Transaction.class' => 'Event'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
    );
    public $hasAndBelongsToMany = array(
        'EventSponsor' => array(
            'className' => 'EventSponsor',
            'joinTable' => 'events_event_sponsors',
            'foreignKey' => 'event_id',
            'associationForeignKey' => 'event_sponsor_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'EventScene' => array(
            'className' => 'EventScene',
            'joinTable' => 'events_event_scenes',
            'foreignKey' => 'event_id',
            'associationForeignKey' => 'event_scene_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'MusicType' => array(
            'className' => 'MusicType',
            'joinTable' => 'events_music_types',
            'foreignKey' => 'event_id',
            'associationForeignKey' => 'music_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'EventTag' => array(
            'className' => 'EventTag',
            'joinTable' => 'events_event_tags',
            'foreignKey' => 'event_id',
            'associationForeignKey' => 'event_tag_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'title' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'venue_name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'description' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'event_category_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'venue_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'start_date' => array(
                'rule5' => array(
                    'rule' => array(
                        '_checkWithListingDate'
                    ) ,
                    'message' => __l('Start date should not be less than the listing appearance date.')
                ) ,
                'rule4' => array(
                    'rule' => array(
                        '_checkFutureDate'
                    ) ,
                    'message' => __l('You cannot add events coming after 6 months')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        '_checkCurrentDate'
                    ) ,
                    'message' => __l('Start date should not be less than the current date')
                ) ,
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date')
                )
            ) ,
            'end_date' => array(
                'rule3' => array(
                    'rule' => array(
                        '_checkDate'
                    ) ,
                    'message' => __l('End date should not be less than start date')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        '_checkCurrentDate'
                    ) ,
                    'message' => __l('End date should not be less than current date')
                ) ,
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date') ,
                    'allowEmpty' => true,
                )
            ) ,
            'repeat_end_date' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkCurrent_repeatDate'
                    ) ,
                    'message' => __l('Repeat end date should not be less start date')
                ) ,
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date')
                )
            ) ,
            'listing_appears_on_site' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkListingCurrentDate'
                    ) ,
                    'message' => __l('Listing appears date should not be less than the current date')
                ) ,
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date')
                )
            ) ,
            'event_sponsor_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'contact_phone' => array(
                'rule1' => array(
                    'rule' => 'phone',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid phone')
                )
            ) ,
            'contact_email' => array(
                'rule1' => array(
                    'rule' => 'email',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid email')
                )
            ) ,
            'url' => array(
                'rule2' => array(
                    'rule' => array(
                        'url'
                    ) ,
                    'allowEmpty' => true,
                    'message' => 'Must be a valid url, starting with http://'
                ) ,
            ) ,
            'limit' => array(
                'rule2' => array(
                    'rule' => array(
                        'numeric'
                    ) ,
                    'allowEmpty' => true,
                    'message' => 'Please enter number'
                ) ,
            ),
            'ticket_fee' => array(
                'rule2' => array(
                    'rule' => array(
                        'numeric'
                    ) ,
                    'allowEmpty' => true,
                    'message' => 'Please enter number'
                ) ,
            )
        );
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Featured => __l('Featured') ,
            ConstMoreAction::NonFeatured => __l('NonFeatured') ,
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Flagged => __l('Flag') ,
            ConstMoreAction::Unflagged => __l('Clear flag') ,
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::Cancel => __l('Cancel') ,
        );
    }
    // reuturns the nuamrical value corresponding to the bianry value based on the event type and day input array
    function getWeekValue($event_type, $days) 
    {
        $binary_data = '0000000';
        switch ($event_type) {
            case 3:
                // Mon-Friday
                $binary_data = '1111100';
                break;

            case 4:
                // Mon,Wed Fri
                $binary_data = '1010100';
                break;

            case 5:
                // Tue,Thur
                $binary_data = '0101000';
                break;

            case 6:
                // manual selection
                if (!empty($days)) {
                    foreach($days as $day) {
                        $binary_data[$day-1] = 1;
                    }
                }
                break;
        }
        return (base_convert($binary_data, 2, 10));
    }
    // reproduce tthe binary string base  on the decimal value
    function getEventDays($decimal_days) 
    {
        $binary_days = base_convert($decimal_days, 10, 2);
        $days = $this->Day->find('list');
        $days_in_words = array();
        for ($i = 1; $i <= 7; $i++) {
            if ($binary_days[$i-1]) $days_in_words[] = $days[$i];
        }
        if (!empty($days_in_words)) {
            return implode(',', $days_in_words);
        }
        return '';
    }
    function getSelectedDaysArray($decimal_days) 
    {
        $selected_list = array();
        $binary_days = base_convert($decimal_days, 10, 2);
        $days = $this->Day->find('list');
        for ($i = 0; $i < 7; $i++) {
            if ($binary_days[$i]) $selected_list[] = $i+1;
        }
        return $selected_list;
    }
    function getEventTimingInfo($event) 
    {
        // check for event time details
        $repeat = '';
        if (!empty($event['Event']['event_type_id'])) {
            switch ($event['Event']['event_type_id']) {
                case 1:
                    // 10.08.2009 10:00 am to 20.08.2009 11:00 pm (normal)
                    $repeat.= Date('d.m.o H:i A', strtotime($event['Event']['start_date'] . ' ' . $event['Event']['start_time']));
                    $repeat.= ' to ';
                    $repeat.= Date('d.m.o H:i A', strtotime($event['Event']['end_date'] . ' ' . $event['Event']['end_time']));
                    if ($event['Event']['is_all_day'] == 0) {
                        $repeat = Date('d.m.o', strtotime($event['Event']['start_date']));
                        $repeat.= ' to ';
                        $repeat.= Date('d.m.o', strtotime($event['Event']['end_date']));
                    }
                    break;

                case 2:
                    $repeat = $event['EventType']['name'];
                    if ($event['Event']['repeat_value'] > 1) {
                        $repeat = 'Every ' . $event['Event']['repeat_value'] . ' days';
                    }
                    break;

                case 3:
                    $repeat = __l('Weekly on weekdays');
                    break;

                case 4:
                    $repeat = __l('Weekly on Monday, Wednesday, Friday');
                    break;

                case 5:
                    $repeat = __l('Weekly on Tuesday, Thursday');
                    break;

                case 6:
                    $repeat = __l('Weekly on') . ' ';
                    $repeat.= $this->getEventDays($event['Event']['repeat_on_week_bits']);
                    break;

                case 7:
                    $repeat = __l('Monthly on day');
                    if ($event['Event']['repeat_value'] > 1) {
                        $repeat = 'Every ' . $event['Event']['repeat_value'] . ' months';
                    }
                    if ($event['Event']['monthly_repeat_type_id'] == 1) {
                        $repeat.= ' ';
                        $repeat.= $event['Event']['repeat_on_month'];
                    } else {
                        $repeat = __l('Monthly on the');
                        $repeat_on_month_array = array(
                            1 => 'first',
                            2 => 'second',
                            3 => 'third',
                            4 => 'fourth',
                            5 => 'fifth'
                        );
                        $days = $this->Day->find('list');
                        $repeat.= ' ' . $repeat_on_month_array[$event['Event']['repeat_on_month']] . ' ' . $days[$event['Event']['repeat_on_month_day_id']];
                    }
                    break;

                case 8:
                    $repeat = __l('Annually on');
                    if ($event['Event']['repeat_value'] > 1) {
                        $repeat = 'Every ' . $event['Event']['repeat_value'] . ' years on ';
                    }
                    $repeat.= Date('M, j', strtotime($event['Event']['start_date']));
                    break;
            }
        }
        $repeat = '';
        if (!empty($event['Event']['is_repeat_until_never']) && $event['Event']['is_repeat_until_never'] == 1) {
            $repeat.= ', until ' . Date('M j, Y', strtotime($event['Event']['repeat_end_date']));
        }
        if (!empty($event['Event']['event_type_id']) && $event['Event']['event_type_id'] > 1) {
            $repeat = 'Starts: ' . Date('d.m.o', strtotime($event['Event']['start_date'])) . ', ' . $repeat;
        }
        $event_timing['description'] = $repeat;
        return $event_timing;
    }
    function _isValidCaptcha() 
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new Securimage();
        return $img->check($this->data['Event']['captcha']);
    }
    function _checkDate() 
    {
        if ($this->data[$this->name]['start_date'] <= $this->data[$this->name]['end_date']) {
            if ($this->data[$this->name]['is_all_day']) {
                return true;
            } else {
                if ($this->data[$this->name]['start_date'] == $this->data[$this->name]['end_date']) {
                    if ($this->data[$this->name]['start_time'] < $this->data[$this->name]['end_time']) {
                        return true;
                    }
                } else {
                    return true;
                }
            }
        }
        return false;
    }
    function _checkWithListingDate() 
    {
        if ($this->data[$this->name]['start_date'] >= $this->data[$this->name]['listing_appears_on_site']) {
            return true;
        }
        return false;
    }
    function _checkCurrentDate() 
    {
        if (!empty($this->data[$this->name]['id'])) {
            $event = $this->find('first', array(
                'conditions' => array(
                    'Event.start_date'
                ) ,
                'recursive' => -1
            ));
            $startDate = $event['Event']['start_date'];
        } else {
            $startDate = date('Y-m-d');
        }
        if (strtotime($this->data[$this->name]['start_date']) >= strtotime($startDate)) return true;
        return false;
    }
    function _checkListingCurrentDate() 
    {
        if (!empty($this->data[$this->name]['id'])) {
            $event = $this->find('first', array(
                'conditions' => array(
                    'Event.id' => $this->data[$this->name]['id']
                ) ,
                'recursive' => -1
            ));
            $listDate = $event['Event']['listing_appears_on_site'];
        } else {
            $listDate = date('Y-m-d');
        }
        if (strtotime($this->data[$this->name]['listing_appears_on_site']) >= strtotime($listDate)) return true;
        return false;
    }
    function _checkFutureDate() 
    {
        if (strtotime($this->data[$this->name]['start_date']) <= strtotime('+6 months')) return true;
        return false;
    }
    function _checkdaynamecnt($dayname) 
    {
        switch ($dayname) {
            case 'Monday':
                $cnt = 6;
                break;

            case 'Tuesday':
                $cnt = 5;
                break;

            case 'Wednesday':
                $cnt = 4;
                break;

            case 'Thursday':
                $cnt = 3;
                break;

            case 'Friday':
                $cnt = 2;
                break;

            case 'Saturday':
                $cnt = 1;
                break;

                return $cnt;
        }
    }
    function _checkVenueAvailability() 
    {
        if (!empty($this->data[$this->name]['id'])) {
            $event = $this->find('first', array(
                'conditions' => array(
                    'Event.id ' => $this->data[$this->name]['id'],
                ) ,
                'fields' => array(
                    'Event.venue_id',
                ) ,
                'recursive' => -1,
            ));
            if (!empty($event) and $event[$this->name]['venue_id'] == $this->data[$this->name]['venue_id']) {
                // edit case with same venue id
                // So we need to skip the venue availability condition
                return true;
            }
        }
        $event_venue = $this->find('first', array(
            'conditions' => array(
                'Event.venue_id ' => $this->data[$this->name]['venue_id'],
                'OR' => array(
                    array(
                        // event starting in between event timing
                        'Event.start_date >= ' => $this->data[$this->name]['start_date'],
                        'Event.start_date <= ' => $this->data[$this->name]['end_date'],
                    ) ,
                    array(
                        // event ending in between event timing
                        'Event.end_date <= ' => $this->data[$this->name]['start_date'],
                        'Event.end_date >= ' => $this->data[$this->name]['end_date'],
                    ) ,
                    array(
                        // event starting before and ending after this event
                        'Event.start_date >= ' => $this->data[$this->name]['start_date'],
                        'Event.end_date <= ' => $this->data[$this->name]['end_date'],
                    )
                ) ,
            ) ,
            'fields' => array(
                'Event.id',
            ) ,
            'recursive' => -1,
        ));
        if (empty($event_venue)) {
            return true;
        }
        return false;
    }
    function afterSave() 
    {
        if (!empty($this->data['EventSponsor']['EventSponsor'])) {
            // for event_count implemention in event sponsor for each sponsors of the event
            $this->EventSponsor->updateAll(array(
                'EventSponsor.event_count' => 'EventSponsor.event_count+1'
            ) , array(
                'EventSponsor.id' => $this->data['EventSponsor']['EventSponsor']
            ));
        }
    }
    function _checkCurrent_repeatDate() 
    {
        if (strtotime($this->data[$this->name]['repeat_end_date']) >= strtotime($this->data[$this->name]['start_date'])) return true;
        return false;
    }
}
?>