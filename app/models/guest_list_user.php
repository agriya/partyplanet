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
class GuestListUser extends AppModel
{
    public $name = 'GuestListUser';
	public $actsAs = array(
        'Aggregatable'
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'GuestList' => array(
            'className' => 'GuestList',
            'foreignKey' => 'guest_list_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'RsvpResponse' => array(
            'className' => 'RsvpResponse',
            'foreignKey' => 'rsvp_response_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'guest_list_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'in_party_count' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'rsvp_response_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
    }
	function afterSave($created) 
    {	
		$guestListUser = $this->find('first', array(
            'conditions' => array(
                'GuestListUser.id' => $this->id,
                'GuestListUser.is_paid' => 1
            ) ,
			'contain' => array(
				'GuestList' => array(
					'fields' => array(
						'GuestList.id',
						'GuestList.event_id'
					),
					'Event' => array(
						'fields' => array(
							'Event.id',
							'Event.ticket_fee'
						),
					)
				)
			),
            'recursive' => 2
        ));
		if(!empty($guestListUser)) {
			$calGuestListUser = $this->find('first', array(
				'conditions' => array(
					'GuestListUser.is_paid' => 1,
					'GuestListUser.guest_list_id' => $guestListUser['GuestListUser']['guest_list_id'],
				),
				'fields' => array(
					'SUM(GuestListUser.site_commission) AS site_revenue',
					'SUM(GuestListUser.amount) AS revenue',
				),
				'recursive' => 1,
			));
			$event_data = array();
			$event_data['Event']['id'] = $guestListUser['GuestList']['event_id'];
			$event_data['Event']['revenue'] = $calGuestListUser['0']['revenue'];
			$event_data['Event']['site_revenue'] = $calGuestListUser['0']['site_revenue'];
			$this->GuestList->Event->save($event_data);
		}
	}

}
?>