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
class BarServiceType extends AppModel
{
    public $name = 'BarServiceType';
    public $displayField = 'name';
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasAndBelongsToMany = array(
        'PartyPlanner' => array(
            'className' => 'PartyPlanner',
            'joinTable' => 'party_planners_bar_service_types',
            'foreignKey' => 'bar_service_type_id',
            'associationForeignKey' => 'party_planner_id',
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
                'rule2' => array(
                    'rule' => 'isUnique',
                    'on' => 'create',
                    'message' => __l('Name is already exist')
                ) ,
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
        );
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Delete => __l('Delete')
        );
    }
}
?>