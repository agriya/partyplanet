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
class VenueSponsor extends AppModel
{
    public $name = 'VenueSponsor';
    public $displayField = 'first_name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'first_name'
            )
        ) ,
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
        )
    );
    public $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'VenueSponsor'
            ) ,
            'dependent' => true
        )
    );
    public $hasAndBelongsToMany = array(
        'Venue' => array(
            'className' => 'Venue',
            'joinTable' => 'venues_venue_sponsors',
            'foreignKey' => 'venue_sponsor_id',
            'associationForeignKey' => 'venue_id',
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
            'user_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'first_name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'last_name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'confirm_email' => array(
                'rule2' => array(
                    'rule' => array(
                        'confirm_email'
                    ) ,
                    'message' => __l('New and confirm Phone field must match, please try again')
                ) ,
                'rule1' => array(
                    'rule' => 'email',
                    'allowEmpty' => false,
                    'message' => __l('Required')
                )
            ) ,
            'phone' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'confirm_phone' => array(
                'rule2' => array(
                    'rule' => array(
                        'confirm_phone'
                    ) ,
                    'message' => __l('New and confirm Phone field must match, please try again')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'venue_count' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'slug' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Delete => __l('Delete')
        );
        $this->isFilterOptions = array(
            ConstUserFilter::FirstName => __l('First name') ,
            ConstUserFilter::LastName => __l('Last name') ,
            ConstUserFilter::EmailAddress => __l('Email')
        );
    }
}
?>