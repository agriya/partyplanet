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
class UserLogin extends AppModel
{
    public $name = 'UserLogin';
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ),
        'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'user_login_ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
    );
    function insertUserLogin($user_id)
    {
        $this->data['UserLogin']['user_id'] = $user_id;
        $this->data['UserLogin']['user_login_ip_id'] = $this->toSaveIp();
        $this->data['UserLogin']['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->save($this->data);
    }
    function afterSave($created)
    {
        $this->User->updateAll(array(
            'User.last_login_ip_id' => $this->User->toSaveIp() ,
            'User.last_logged_in_time' => '\'' . date('Y-m-d H:i:s') . '\'',            
        ) , array(
            'User.id' => $_SESSION['Auth']['User']['id']
        ));
    }
    function __construct($id = false, $table = null, $ds = null)
    {
        parent::__construct($id, $table, $ds);
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete')
        );
    }
}
?>