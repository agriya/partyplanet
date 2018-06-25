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
class Photo extends AppModel
{
    public $name = 'Photo';
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
        'PhotoAlbum' => array(
            'className' => 'PhotoAlbum',
            'foreignKey' => 'photo_album_id',
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
                'Attachment.class =' => 'Photo'
            ) ,
            'dependent' => true
        )
    );
    public $hasMany = array(
        'PhotoComment' => array(
            'className' => 'PhotoComment',
            'foreignKey' => 'photo_id',
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
        'PhotoFlag' => array(
            'className' => 'PhotoFlag',
            'foreignKey' => 'photo_id',
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
        'PhotoView' => array(
            'className' => 'PhotoView',
            'foreignKey' => 'photo_id',
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
        'PhotoRating' => array(
            'className' => 'PhotoRating',
            'foreignKey' => 'photo_id',
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
        'PhotoFavorite' => array(
            'className' => 'PhotoFavorite',
            'foreignKey' => 'photo_id',
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
    );
    public $hasAndBelongsToMany = array(
        'PhotoTag' => array(
            'className' => 'PhotoTag',
            'joinTable' => 'photos_photo_tags',
            'foreignKey' => 'photo_id',
            'associationForeignKey' => 'photo_tag_id',
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
            'url' => array(
                'rule2' => array(
                    'rule' => 'url',
                    'message' => __l('Must be a valid url')
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
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Flagged => __l('Flag') ,
            ConstMoreAction::Unflagged => __l('Clear flag') ,
            ConstMoreAction::Delete => __l('Delete') ,
            ConstMoreAction::Hotties => __l('Hotties')
        );
    }
    function _getPhotoAlbum($idOrSlug, $type = 'id') 
    {
        if ($type == 'id') {
            $conditions['PhotoAlbum.id'] = $idOrSlug;
        } else {
            $conditions['PhotoAlbum.slug'] = $idOrSlug;
        }
        $photoAlbum = $this->PhotoAlbum->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'Venue' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name'
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.id',
                            'State.name'
                        )
                    ) ,
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug',
                        'Venue.address',
                        'Venue.zip_code',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.title',
                        'Event.slug',
                        'Event.description',
                        'Event.is_cancel',
                    )
                )
            ) ,
            'fields' => array(
                'PhotoAlbum.id',
                'PhotoAlbum.title',
                'PhotoAlbum.slug',
                'PhotoAlbum.user_id',
                'PhotoAlbum.venue_id',
                'PhotoAlbum.photo_count'
            ) ,
            'recursive' => 2
        ));
        return $photoAlbum;
    }
}
?>