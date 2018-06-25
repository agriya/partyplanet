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
class Article extends AppModel
{
    public $name = 'Article';
    public $displayField = 'title';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'title'
            ) ,
            'overwrite' => true
        ) ,
        'Versionable' => array(
            'title',
            'description'
        ) ,
        'Taggable',
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'title',
                'description'
            )
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'ArticleCategory' => array(
            'className' => 'ArticleCategory',
            'foreignKey' => 'article_category_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        )
    );
    public $hasOne = array(
        'Attachment' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'Attachment.class =' => 'Article'
            ) ,
            'dependent' => true
        )
    );
    public $hasMany = array(
        'ArticleComment' => array(
            'className' => 'ArticleComment',
            'foreignKey' => 'article_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        )
    );
    public $hasAndBelongsToMany = array(
        'ArticleTag' => array(
            'className' => 'ArticleTag',
            'joinTable' => 'articles_article_tags',
            'foreignKey' => 'article_id',
            'associationForeignKey' => 'article_tag_id',
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
            'title' => array(
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
            'article_category' => array(
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
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Flagged => __l('Flag') ,
            ConstMoreAction::Unflagged => __l('Clear flag') ,
            ConstMoreAction::Delete => __l('Delete')
        );
    }
}
?>