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
class Video extends AppModel
{
    public $name = 'Video';
    public $displayField = 'title';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'title',
            ) ,
            'overwrite' => true
        ) ,
        'Versionable' => array(
            'title',
            'description',
            'default_thumbnail_id',
            'is_adult_video',
            'is_private',
            'is_allow_to_comment',
            'is_allow_to_embed',
            'is_allow_to_rating',
            'is_allow_to_download',
        ) ,
        'Taggable',
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'title',
                'description'
            )
        ) ,
    );
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'VideoCategory' => array(
            'className' => 'VideoCategory',
            'foreignKey' => 'id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Venue' => array(
            'className' => 'Venue',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'foreign_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
         'Ip' => array(
            'className' => 'Ip',
            'foreignKey' => 'ip_id',
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
                'Attachment.class' => 'Video'
            ) ,
            'dependent' => true
        ) ,
        'EncodeVideo' => array(
            'className' => 'EncodeVideo',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'EncodeVideo.class' => 'EncodeVideo'
            ) ,
            'dependent' => true
        )
    );
    public $hasMany = array(
        'VideoComment' => array(
            'className' => 'VideoComment',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VideoFlag' => array(
            'className' => 'VideoFlag',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VideoView' => array(
            'className' => 'VideoView',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VideoRating' => array(
            'className' => 'VideoRating',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VideoFavorite' => array(
            'className' => 'VideoFavorite',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VideoDownload' => array(
            'className' => 'VideoDownload',
            'foreignKey' => 'video_id',
            'dependent' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Thumbnail' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Thumbnail.class' => 'Thumbnail'
            ) ,
            'fields' => '',
            'order' => ''
        )
    );
    public $hasAndBelongsToMany = array(
        'VideoTag' => array(
            'className' => 'VideoTag',
            'joinTable' => 'videos_video_tags',
            'foreignKey' => 'video_id',
            'associationForeignKey' => 'video_tag_id',
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
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'is_allow_to_comment' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'is_allow_to_embed' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'is_allow_to_rating' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'video_category_id' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            /*'is_allow_to_download' => array(
            'rule' => 'notempty',
            'message' => __l('Required') ,
            'allowEmpty' => false
            ), */
            'embed_code' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            ) ,
            'title' => array(
                'rule' => 'notempty',
                'message' => __l('Required') ,
                'allowEmpty' => false
            )
        );
        $this->moreActions = array(
            ConstMoreAction::Featured => __l('Featured') ,
            ConstMoreAction::NonFeatured => __l('NonFeatured') ,
            ConstMoreAction::Delete => __l('Delete')
        );
        $this->isFilterOptions = array(
            ConstMoreAction::Approved => __l('Approved') ,
            ConstMoreAction::Disapproved => __l('Pending') ,
            ConstMoreAction::Featured => __l('Featured') ,
            //ConstMoreAction::Notfeatured => __l('Not Featured')
            
        );
    }
}
?>