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
/*
*
* Using the Schema command line utility
* cake schema run create DbAcl
*
*/
class DbAclSchema extends CakeSchema
{
    var $name = 'DbAcl';
    function before($event = array())
    {
        return true;
    }
    function after($event = array())
    {
    }
    var $acos = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'default' => NULL,
            'length' => 10,
            'key' => 'primary'
        ) ,
        'parent_id' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'model' => array(
            'type' => 'string',
            'null' => true
        ) ,
        'foreign_key' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'alias' => array(
            'type' => 'string',
            'null' => true
        ) ,
        'lft' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'rght' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'indexes' => array(
            'PRIMARY' => array(
                'column' => 'id',
                'unique' => 1
            )
        )
    );
    var $aros = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'default' => NULL,
            'length' => 10,
            'key' => 'primary'
        ) ,
        'parent_id' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'model' => array(
            'type' => 'string',
            'null' => true
        ) ,
        'foreign_key' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'alias' => array(
            'type' => 'string',
            'null' => true
        ) ,
        'lft' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'rght' => array(
            'type' => 'integer',
            'null' => true,
            'default' => NULL,
            'length' => 10
        ) ,
        'indexes' => array(
            'PRIMARY' => array(
                'column' => 'id',
                'unique' => 1
            )
        )
    );
    var $aros_acos = array(
        'id' => array(
            'type' => 'integer',
            'null' => false,
            'default' => NULL,
            'length' => 10,
            'key' => 'primary'
        ) ,
        'aro_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => 10,
            'key' => 'index'
        ) ,
        'aco_id' => array(
            'type' => 'integer',
            'null' => false,
            'length' => 10
        ) ,
        '_create' => array(
            'type' => 'string',
            'null' => false,
            'default' => '0',
            'length' => 2
        ) ,
        '_read' => array(
            'type' => 'string',
            'null' => false,
            'default' => '0',
            'length' => 2
        ) ,
        '_update' => array(
            'type' => 'string',
            'null' => false,
            'default' => '0',
            'length' => 2
        ) ,
        '_delete' => array(
            'type' => 'string',
            'null' => false,
            'default' => '0',
            'length' => 2
        ) ,
        'indexes' => array(
            'PRIMARY' => array(
                'column' => 'id',
                'unique' => 1
            ) ,
            'ARO_ACO_KEY' => array(
                'column' => array(
                    'aro_id',
                    'aco_id'
                ) ,
                'unique' => 1
            )
        )
    );
}
?>