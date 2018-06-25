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
/**
 * Setting Model
 *
 * Site settings.
 *
 */
class Setting extends AppModel
{
    var $validate = array();
    public $belongsTo = array(
        'SettingCategory' => array(
            'className' => 'SettingCategory',
            'foreignKey' => 'setting_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasOne = array(
        'WaterMark' => array(
            'className' => 'WaterMark',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'WaterMark.class' => 'WaterMark',
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    /**
     * Find all settings of given type and transform them to key => value array
     *
     * @param string $type
     * @return array
     *
     * @TODO cache settings
     */
    function getKeyValuePairs() 
    {
        $settings = $this->find('all');
        $names = Set::extract($settings, '{n}.Setting.name');
        $values = Set::extract($settings, '{n}.Setting.value');
        $settings = array_combine($names, $values);
        return $settings;
    }
}
