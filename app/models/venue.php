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
class Venue extends AppModel
{
    public $name = 'Venue';
    public $displayField = 'name';
    public $actsAs = array(
        'Sluggable' => array(
            'label' => array(
                'name'
            )
        ) ,
        'SuspiciousWordsDetector' => array(
            'fields' => array(
                'name',
                'description',
            )
        ) ,
    );
	var $aggregatingFields = array(
        'revenue' => array(
            'mode' => 'real',
            'key' => 'venue_id',
            'foreignKey' => 'venue_id',
            'model' => 'Event',
            'function' => 'SUM(Event.revenue)',
            'conditions' => array()
        ) ,
		'site_revenue' => array(
            'mode' => 'real',
            'key' => 'venue_id',
            'foreignKey' => 'venue_id',
            'model' => 'Event',
            'function' => 'SUM(Event.site_revenue)',
            'conditions' => array()
        ) ,
    );
    //$validate set in __construct for multi-language support
    //The Associations below have been created with all possible keys, those that are not needed can be removed
    public $belongsTo = array(
        'VenueType' => array(
            'className' => 'VenueType',
            'foreignKey' => 'venue_type_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
            'counterScope' => array(
                'Venue.is_active' => 1
            )
        ) ,
        'User' => array(
            'className' => 'User',
            'foreignKey' => 'user_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'City' => array(
            'className' => 'City',
            'foreignKey' => 'city_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true,
            'counterScope' => array(
                'Venue.is_active' => 1
            )
        ) ,
        'State' => array(
            'className' => 'State',
            'foreignKey' => 'state_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'Country' => array(
            'className' => 'Country',
            'foreignKey' => 'country_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
        ) ,
        'VenueGallery' => array(
            'className' => 'PhotoAlbum',
            'foreignKey' => 'venue_gallery_id',
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'counterCache' => true
        ) ,
        'FeaturedVenueSubscription' => array(
            'className' => 'FeaturedVenueSubscription',
            'foreignKey' => 'featured_venue_subscription_id',
            'conditions' => '',
            'fields' => '',
            'order' => ''
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
                'Attachment.class' => 'Venue'
            ) ,
            'dependent' => true
        ) ,
        'VenueLogo' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'VenueLogo.class' => 'VenueLogo'
            ) ,
            'dependent' => true
        ) ,
        'WideScreen' => array(
            'className' => 'Attachment',
            'foreignKey' => 'foreign_id',
            'conditions' => array(
                'WideScreen.class' => 'WideScreen'
            ) ,
            'dependent' => true
        )
    );
    public $hasMany = array(
        'Event' => array(
            'className' => 'Event',
            'foreignKey' => 'venue_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Video' => array(
            'className' => 'Video',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Video.class' => 'Venue'
            ) ,
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VenueComment' => array(
            'className' => 'VenueComment',
            'foreignKey' => 'venue_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'PhotoAlbum' => array(
            'className' => 'PhotoAlbum',
            'foreignKey' => 'venue_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'VenueUser' => array(
            'className' => 'VenueUser',
            'foreignKey' => 'venue_id',
            'dependent' => false,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'exclusive' => '',
            'finderQuery' => '',
            'counterQuery' => ''
        ) ,
        'Transaction' => array(
            'className' => 'Transaction',
            'foreignKey' => 'foreign_id',
            'dependent' => true,
            'conditions' => array(
                'Transaction.class' => 'Venue'
            ) ,
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
        'VenueSponsor' => array(
            'className' => 'VenueSponsor',
            'joinTable' => 'venues_venue_sponsors',
            'foreignKey' => 'venue_id',
            'associationForeignKey' => 'venue_sponsor_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'VenueCategory' => array(
            'className' => 'VenueCategory',
            'joinTable' => 'venues_venue_categories',
            'foreignKey' => 'venue_id',
            'associationForeignKey' => 'venue_category_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'ParkingType' => array(
            'className' => 'ParkingType',
            'joinTable' => 'venues_parking_types',
            'foreignKey' => 'venue_id',
            'associationForeignKey' => 'parking_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'MusicType' => array(
            'className' => 'MusicType',
            'joinTable' => 'venues_music_types',
            'foreignKey' => 'venue_id',
            'associationForeignKey' => 'music_type_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
        'VenueFeature' => array(
            'className' => 'VenueFeature',
            'joinTable' => 'venues_venue_features',
            'foreignKey' => 'venue_id',
            'associationForeignKey' => 'venue_feature_id',
            'unique' => true,
            'conditions' => '',
            'fields' => '',
            'order' => '',
            'limit' => '',
            'offset' => '',
            'finderQuery' => '',
            'deleteQuery' => '',
            'insertQuery' => '',
        ) ,
    );
    function __construct($id = false, $table = null, $ds = null) 
    {
        parent::__construct($id, $table, $ds);
        $this->validate = array(
            'name' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'description' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'venue_type_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'address' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'website' => array(
                'rule2' => array(
                    'rule' => array(
                        'url'
                    ) ,
                    'allowEmpty' => true,
                    'message' => 'Must be a valid url, starting with http://',
                ) ,
                'rule1' => array(
                    'rule' => array(
                        'custom',
                        '/^http:\/\//'
                    ) ,
                    'allowEmpty' => true,
                    'message' => 'Must be a valid url, starting with http://',
                ) ,
            ) ,
            'landmark' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'city_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'country_id' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                )
            ) ,
            'email' => array(
                'rule2' => array(
                    'rule' => 'email',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid email')
                ) ,
            ) ,
            'phone' => array(
                'rule1' => array(
                    'rule' => 'notempty',
                    'message' => __l('Required')
                ) ,
            ) ,
            'fax' => array(
                'rule2' => array(
                    'rule' => 'numeric',
                    'allowEmpty' => true,
                    'message' => __l('Must be a valid fax')
                ) ,
            ) ,
        );
        $this->BeerPrice = array(
            ConstNos::First => ConstBeerPrice::First,
            ConstNos::Second => ConstBeerPrice::Second,
            ConstNos::Third => ConstBeerPrice::Third,
        );
        $this->EmployeeSize = array(
            ConstNos::First => ConstEmployeeSize::First,
            ConstNos::Second => ConstEmployeeSize::Second,
            ConstNos::Third => ConstEmployeeSize::Third,
            ConstNos::Four => ConstEmployeeSize::Four,
        );
        $this->SquareFootage = array(
            ConstNos::First => ConstSquareFootage::First,
            ConstNos::Second => ConstSquareFootage::Second,
            ConstNos::Third => ConstSquareFootage::Third,
            ConstNos::Four => ConstSquareFootage::Four,
        );
        $this->SalesVolume = array(
            ConstNos::First => ConstSalesVolume::First,
            ConstNos::Second => ConstSalesVolume::Second,
            ConstNos::Third => ConstSalesVolume::Third,
            ConstNos::Four => ConstSalesVolume::Four,
        );
        $this->FoodSold = array(
            ConstNos::First => ConstFoodSold::First,
            ConstNos::Second => ConstFoodSold::Second,
            ConstNos::Third => ConstFoodSold::Third,
        );
        $this->LiveBand = array(
            ConstNos::First => ConstLiveBand::First,
            ConstNos::Second => ConstLiveBand::Second,
            ConstNos::Third => ConstLiveBand::Third,
            ConstNos::Four => ConstLiveBand::Four,
        );
        $this->moreActions = array(
            ConstMoreAction::Inactive => __l('Inactive') ,
            ConstMoreAction::Active => __l('Active') ,
            ConstMoreAction::Featured => __l('Featured') ,
            ConstMoreAction::NonFeatured => __l('NonFeatured') ,
            ConstMoreAction::Suspend => __l('Suspend') ,
            ConstMoreAction::Unsuspend => __l('Unsuspend') ,
            ConstMoreAction::Flagged => __l('Flag') ,
            ConstMoreAction::Unflagged => __l('Clear flag') ,
            ConstMoreAction::Delete => __l('Delete')
        );
    }
    function _getVenueCities($prefixId, $slug) 
    {
        $city = $this->City->find('first', array(
            'conditions' => array(
                'City.slug' => $slug,
                'City.is_approved' => 1
            ) ,
            'fields' => array(
                'City.country_id',
            ) ,
            'recursive' => -1,
        ));
        $venueCities = $this->find('all', array(
            'conditions' => array(
                'Venue.is_active' => '1',
                'Venue.admin_suspend' => '0',
                'Venue.city_id != ' => $prefixId,
                'City.country_id' => $city['City']['country_id'],
                'City.is_approved' => 1
            ) ,
            'fields' => array(
                'count(Venue.id) as venue_count',
                'Venue.city_id',
            ) ,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.name',
                        'City.slug'
                    ) ,
                )
            ) ,
            'group' => 'Venue.city_id',
            'order' => 'Venue.name asc',
            'recursive' => 1,
        ));
        return $venueCities;
    }
    function _getVenueKeywords($prefixId) 
    {
        $conditionVenueKeywords['Venue.is_active'] = 1;
        $conditionVenueKeywords['Venue.admin_suspend'] = 0;
        if (!empty($prefixId)) {
            $conditionVenueKeywords['Venue.city_id'] = $prefixId;
        }
        $venueKeywords = $this->find('all', array(
            'conditions' => $conditionVenueKeywords,
            'fields' => array(
                'left(Venue.slug, 1) as keyword',
                'count(Venue.id) as venue_count',
            ) ,
            'contain' => array(
                'City' => array(
                    'fields' => array(
                        'City.name',
                        'City.slug'
                    )
                )
            ) ,
            'group' => 'left(Venue.slug, 1)',
            'order' => array(
                'Venue.slug' => 'asc'
            ) ,
            'recursive' => 1
        ));
        return $venueKeywords;
    }
    function _getVenueTypes() 
    {
        $venueTypes = $this->VenueType->find('all', array(
            'conditions' => array(
                'VenueType.is_active' => 1
            ) ,
            'fields' => array(
                'VenueType.id',
                'VenueType.name',
                'VenueType.slug'
            ) ,
            'order' => array(
                'VenueType.name' => 'asc'
            ) ,
            'recursive' => -1
        ));
        return $venueTypes;
    }
    function _getVenueTypesVenueCount($prefixId) 
    {
        $tmpVenueTypes = $this->find('all', array(
            'conditions' => array(
                'Venue.city_id' => $prefixId,
            ) ,
            'fields' => array(
                'Venue.venue_type_id',
                'COUNT(*) as venue_count'
            ) ,
            'group' => array(
                'Venue.venue_type_id'
            ) ,
            'recursive' => -1
        ));
        $venueTypeVenueCount = array();
        if (!empty($tmpVenueTypes)) {
            foreach($tmpVenueTypes as $tmpVenueType) {
                $venueTypeVenueCount[$tmpVenueType['Venue']['venue_type_id']] = $tmpVenueType[0]['venue_count'];
            }
        }
        return $venueTypeVenueCount;
    }
    function _getMusicTypes() 
    {
        $musicTypes = $this->MusicType->find('all', array(
            'conditions' => array(
                'MusicType.is_active ' => 1,
            ) ,
            'fields' => array(
                'MusicType.id',
                'MusicType.name',
                'MusicType.slug'
            ) ,
            'order' => array(
                'MusicType.name' => 'asc'
            ) ,
            'recursive' => -1
        ));
        return $musicTypes;
    }
    function _getMusicTypesVenueCount($prefixId) 
    {
        $venues = $this->find('list', array(
            'conditions' => array(
                'Venue.city_id' => $prefixId
            ) ,
            'fields' => array(
                'Venue.id'
            )
        ));
        $tmpMusicTypes = $this->VenuesMusicType->find('all', array(
            'conditions' => array(
                'VenuesMusicType.venue_id' => $venues
            ) ,
            'fields' => array(
                'VenuesMusicType.music_type_id',
                'COUNT(*) as venue_count'
            ) ,
            'group' => array(
                'VenuesMusicType.music_type_id'
            ) ,
            'recursive' => 1
        ));
        $musicTypeVenueCount = array();
        if (!empty($tmpMusicTypes)) {
            foreach($tmpMusicTypes as $tmpMusicType) {
                $musicTypeVenueCount[$tmpMusicType['VenuesMusicType']['music_type_id']] = $tmpMusicType[0]['venue_count'];
            }
        }
        return $musicTypeVenueCount;
    }
}
?>