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
class UserProfile extends AppModel
{
    public $name = 'UserProfile';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Gender' => array(
            'className' => 'Gender',
            'foreignKey' => 'gender_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'BodyType' => array(
            'className' => 'BodyType',
            'foreignKey' => 'body_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'CellProvider' => array(
            'className' => 'CellProvider',
            'foreignKey' => 'cell_provider_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'MaritalStatus' => array(
            'className' => 'MaritalStatus',
            'foreignKey' => 'marital_status_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'FavoriteFashionBrand' => array(
            'className' => 'FavoriteFashionBrand',
            'foreignKey' => 'favorite_fashion_brand_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Ethnicity' => array(
            'className' => 'Ethnicity',
            'foreignKey' => 'ethnicity_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'SexualOrientation' => array(
            'className' => 'SexualOrientation',
            'foreignKey' => 'sexual_orientation_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Language' => array(
            'className' => 'Language',
            'foreignKey' => 'language_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'dob' => array(
                'rule2' => array(
                    'rule' => 'date',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid date')
                ) ,
                'rule1' => array(
                    'rule' => '_isValidDob',
                    'message' => __l('Must be a valid date')
                )
            ) ,
            'email' => array(
                'rule' => 'email',
                'message' => __l('Must be a valid email') ,
                'allowEmpty' => true
            ) ,
            'paypal_account' => array(
                'rule' => 'email',
                'message' => __l('Must be a valid email') ,
                'allowEmpty' => true
            ) ,
            'gender_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'country_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'state_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'city_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'first_name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'last_name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'zip_code' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
             'mobile' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            )
        );
    }
    function _isValidDob() 
    {
        return Date('Y') . '-' . Date('m') . '-' . Date('d') >= $this->data[$this->name]['dob'];
    }
}
?>