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
class PhotoTag extends AppModel
{
    public $name = 'PhotoTag';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            ) ,
        )
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $hasAndBelongsToMany = array(
        'Photo' => array(
            'className' => 'Photo',
            'joinTable' => 'photos_photo_tags',
            'foreignKey' => 'photo_tag_id',
            'associationForeignKey' => 'photo_id',
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
    }
}
?>