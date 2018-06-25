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
class GuestList extends AppModel
{
    public $name = 'GuestList';
    public $displayField = 'name';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'event_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasMany = array(
        'GuestListUser' => array(
            'className' => 'GuestListUser',
            'foreignKey' => 'guest_list_id',
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
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'details' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'guest_limit' => array(
                'rule2' => array(
                    'rule' => array(
                        'numeric'
                    ) ,
                    'message' => 'Please enter number'
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'email' => array(
                'rule1' => array(
                    'rule' => 'checkMultipleEmail',
                    'message' => __l('Must be a valid email') ,
                    'allowEmpty' => true
                )
            ) ,
			'website_close_date' => array(
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date') ,
                    'allowEmpty' => false,
                )
            ) ,
        );
    }
    function checkMultipleEmail() 
    {
        $multipleEmails = explode(',', $this->data['GuestList']['email']);
        foreach($multipleEmails as $key => $singleEmail) {
            if (!Validation::email(trim($singleEmail))) {
                return false;
            }
        }
        return true;
    }
}
?>