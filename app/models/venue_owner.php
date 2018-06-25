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
class VenueOwner extends AppModel
{
    public $name = 'VenueOwner';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Gender' => array(
            'className' => 'Gender',
            'foreignKey' => 'gender_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'VenueType' => array(
            'className' => 'VenueType',
            'foreignKey' => 'venue_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'first_name' => array(
                'rule6' => array(
                    'rule' => 'alphaNumeric',
                    'message' => __l('Must be a valid character')
                ) ,
                'rule4' => array(
                    'rule' => array(
                        'maxLength',
                        20
                    ) ,
                    'message' => __l('Must be maximum 20 characters')
                ) ,
                'rule3' => array(
                    'rule' => 'isUnique',
                    'on' => 'create',
                    'message' => __l('Username is already exist')
                ) ,
                'rule2' => array(
                    'rule' => array(
                        'custom',
                        '/^[a-zA-Z]/'
                    ) ,
                    'message' => __l('Must be start with an alphabets')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'last_name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'mobile' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'gender_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'dob' => array(
                'rule2' => array(
                    'rule' => 'date',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid date')
                ) ,
                'rule3' => array(
                    'rule' => '_isValidDob',
                    'message' => __l('Must be a valid date')
                )
            ) ,
            'venue_name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'venue_type_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'is_agree_terms_conditions' => array(
                'rule' => array(
                    'equalTo',
                    '1'
                ) ,
                'message' => __l('You must agree to the terms and conditions')
            ) ,
            'captcha' => array(
                'rule2' => array(
                    'rule' => '_isValidCaptcha',
                    'message' => __l('Please enter valid captcha')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
        );
    }
    function _isValidCaptcha() 
    {
        include_once VENDORS . DS . 'securimage' . DS . 'securimage.php';
        $img = new Securimage();
        return $img->check($this->data['VenueOwner']['captcha']);
    }
    function _isValidDob() 
    {
        return Date('Y') . '-' . Date('m') . '-' . Date('d') >= $this->data[$this->name]['dob'];
    }
}
?>