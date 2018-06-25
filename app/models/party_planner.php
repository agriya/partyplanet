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
class PartyPlanner extends AppModel
{
    public $name = 'PartyPlanner';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
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
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
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
        'CellProvider' => array(
            'className' => 'CellProvider',
            'foreignKey' => 'cell_provider_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'PartyType' => array(
            'className' => 'PartyType',
            'foreignKey' => 'party_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    public $hasAndBelongsToMany = array(
        'FoodCatering' => array(
            'className' => 'FoodCatering',
            'joinTable' => 'food_caterings_party_planners',
            'foreignKey' => 'party_planner_id',
            'associationForeignKey' => 'food_catering_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
        'Entertainment' => array(
            'className' => 'Entertainment',
            'joinTable' => 'entertainments_party_planners',
            'foreignKey' => 'party_planner_id',
            'associationForeignKey' => 'entertainment_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
        'BarServiceType' => array(
            'className' => 'BarServiceType',
            'joinTable' => 'party_planners_bar_service_types',
            'foreignKey' => 'party_planner_id',
            'associationForeignKey' => 'bar_service_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
        'MusicType' => array(
            'className' => 'MusicType',
            'joinTable' => 'party_planners_music_types',
            'foreignKey' => 'party_planner_id',
            'associationForeignKey' => 'music_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => ''
        ) ,
        'EventScene' => array(
            'className' => 'EventScene',
            'joinTable' => 'party_planners_scenes',
            'foreignKey' => 'party_planner_id',
            'associationForeignKey' => 'event_scene_id',
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
            'name' => array(
                'rule' => 'notempty',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'city_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'state_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'zip_code' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'country_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'email' => array(
                'rule' => 'email',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'party_type_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            ) ,
            'date' => array(
                'rule2' => array(
                    'rule' => array(
                        '_checkCurrentDate'
                    ) ,
                    'message' => __l('Party date should not be less than the current date')
                ) ,
                'rule1' => array(
                    'rule' => 'date',
                    'message' => __l('Should be valid date')
                )
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Contacted => __l('Contacted') ,
            ConstMoreAction::NotContacted => __l('Not Contacted') ,
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function _checkCurrentDate() 
    {
        if (strtotime($this->data[$this->name]['date']) >= strtotime(date('Y-m-d'))) return true;
        return false;
    }
}
?>