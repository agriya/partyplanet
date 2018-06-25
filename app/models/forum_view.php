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
class ForumView extends AppModel
{
    public $name = 'ForumView';
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
        'Forum' => array(
            'className' => 'Forum',
            'foreignKey' => 'forum_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ),
         'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
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
            'forum_id' => array(
                'rule' => 'numeric',
                'allowEmpty' => false,
                'message' => __l('Required')
            )
        );
        $this->moreActions = array(
            ConstMoreAction::Delete => __l('Delete') ,
        );
    }
    function afterSave() 
    {
        App::import('Model', 'Forum');
        $forum = new Forum();
        $forum_details = $forum->find('first', array(
            'conditions' => array(
                'Forum.id' => $this->data['ForumView']['forum_id']
            ) ,
            'recursive' => 0
        ));
        $forum->ForumCategory->updateAll(array(
            'ForumCategory.forum_view_count' => 'ForumCategory.forum_view_count + 1'
        ) , array(
            'ForumCategory.id' => $forum_details['Forum']['forum_category_id']
        ));
    }
}
?>