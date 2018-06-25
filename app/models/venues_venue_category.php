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
class VenuesVenueCategory extends AppModel
{
    public $name = 'VenuesVenueCategory';
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'VenueCategory' => array(
            'className' => 'VenueCategory',
            'foreignKey' => 'venue_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        ) ,
        'Venue' => array(
            'className' => 'Venue',
            'foreignKey' => 'venue_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
        )
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
    }
}
?>