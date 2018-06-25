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
class Transaction extends AppModel
{
    public $name = 'Transaction';
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
        'TransactionType' => array(
            'className' => 'TransactionType',
            'foreignKey' => 'transaction_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'PaymentGateway' => array(
            'className' => 'PaymentGateway',
            'foreignKey' => 'payment_gateway_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Venue' => array(
            'className' => 'Venue',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
		'GuestListUser' => array(
            'className' => 'GuestListUser',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasOne = array(
        'MoneybookerTransactionLog' => array(
            'className' => 'MoneybookerTransactionLog',
            'foreignKey' => 'transaction_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasMany = array(
        'MoneybookerTransactionLog' => array(
            'className' => 'MoneybookerTransactionLog',
            'foreignKey' => 'transaction_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
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
            'foreign' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'class' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
    }
    function log($data) 
    {
        if (!empty($data)) {
            $this->create();
            $this->save($data);
            return $this->getLastInsertId();
        }
        return false;
    }
}
?>