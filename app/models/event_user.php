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
class EventUser extends AppModel
{
    public $name = 'EventUser';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
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
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'event_id' => array(
                'rule4' => array(
                    'rule' => array(
                        '_checkUserAvailability',
                    ) ,
                    'message' => __l('You are already commited another event in the same time')
                ) ,
                'rule3' => array(
                    'rule' => array(
                        '_isJoined',
                    ) ,
                    'message' => __l('You have already joined this event')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        '_isPastEvent',
                    ) ,
                    'message' => __l('You cannot join a past event')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
        );
    }
    function _checkUserAvailability() 
    {
        $event_id = $this->data[$this->name]['event_id'];
        $event_detail = $this->Event->find('first', array(
            'conditions' => array(
                'Event.id' => $event_id
            ) ,
            'fields' => array(
                'Event.id',
                'Event.start_date',
                'Event.end_date',
            ) ,
            'recursive' => 0,
        ));
        if (!empty($event_detail)) {
            $event_start_date = $event_detail['Event']['start_date'];
            $event_end_date = $event_detail['Event']['end_date'];
        }
        $event_user_count = $this->find('count', array(
            'conditions' => array(
                'EventUser.user_id ' => $_SESSION['Auth']['User']['id'],
                'OR' => array(
                    array(
                        'Event.start_date >= ' => $event_start_date,
                        'Event.start_date <= ' => $event_end_date,
                    ) ,
                    array(
                        'Event.end_date <= ' => $event_start_date,
                        'Event.end_date >= ' => $event_end_date,
                    ) ,
                    array(
                        'Event.start_date >= ' => $event_start_date,
                        'Event.end_date <= ' => $event_end_date,
                    )
                ) ,
            ) ,
            'fields' => array(
                'Event.id',
                'Event.start_date',
                'Event.end_date',
            ) ,
            'recursive' => 1,
        ));
        if ($event_user_count == 0) {
            return true;
        }
        return false;
    }
    function _isJoined() 
    {
        $event_id = $this->data[$this->name]['event_id'];
        $joined = $this->find('count', array(
            'conditions' => array(
                'EventUser.event_id' => $event_id,
                'EventUser.user_id' => $_SESSION['Auth']['User']['id'],
            ) ,
            'recursive' => -1,
        ));
        if ($joined == 0) return true;
        return false;
    }
    function _isPastEvent() 
    {
        $event_id = $this->data[$this->name]['event_id'];
        $is_past = $this->Event->find('count', array(
            'conditions' => array(
                'Event.id' => $event_id,
                'AND' => array(
                    'Event.start_date <' => date('Y-m-d H:i:s') ,
                    'Event.end_date <' => date('Y-m-d H:i:s')
                )
            ) ,
            'recursive' => -1,
        ));
        if ($is_past == 0) return true;
        return false;
    }
}
?>