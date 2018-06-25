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
class VenuesController extends AppController
{
    public $name = 'Venues';
    public $uses = array(
        'Venue',
        'EmailTemplate',
        'CellProvider',
    );
    public $components = array(
        'Email',
        'Cookie',
        'OauthConsumer'
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Venue.makeActive',
            'Venue.makeInactive',
            'Venue.makeDelete',
            'Venue.longitude',
            'Venue.latitude',
            'City.id',
            'Attachment.filename',
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') || !Configure::read('suspicious_detector.auto_suspend_venue_on_system_flag')) {
                $this->Venue->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index() 
    {
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'search') && !empty($this->request->data)) {
            $_SESSION['search'] = $this->request->data;
        } elseif (empty($this->request->params['named']['type']) or ($this->request->params['named']['type'] != 'search')) {
            unset($_SESSION['search']);
        }
        $this->pageTitle = __l('Venues');
        $conditions = array();
        $setMore = 1;
        $limit = '10';
        $order = array(
            'Venue.is_bump_up' => 'DESC',
            'Venue.is_sponsor' => 'ASC',
            'Venue.id' => 'ASC'
        );
        if (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'user') {
            $conditions['Venue.admin_suspend'] = 0;
            $conditions['Venue.is_active'] = 1;
        }
        $this->_redirectGET2Named(array(
            'keyword',
            'location'
        ));
        if (!empty($_SESSION['search']['Venue'])) {
            $this->request->data['VenueSearch'] = $_SESSION['search']['Venue'];
        }
        if (!empty($this->request->data['VenueSearch']['VenueType'])) {
            $conditions['Venue.venue_type_id'] = $this->request->data['VenueSearch']['VenueType'];
        }
        if (!empty($this->request->data['VenueSearch']['zip_code'])) {
            $conditions['Venue.zip_code'] = $this->request->data['VenueSearch']['zip_code'];
        }
        if (!empty($this->request->data['VenueSearch']['MusicType'])) {
            $venueIds = $this->Venue->VenuesMusicType->find('list', array(
                'conditions' => array(
                    'VenuesMusicType.music_type_id' => $this->request->data['VenueSearch']['MusicType']
                ) ,
                'fields' => array(
                    'VenuesMusicType.venue_id'
                ) ,
                'recursive' => -1
            ));
            $conditions['Venue.id'] = $venueIds;
        }
        if (!empty($this->request->params['named']['limit'])) {
            $limit = $this->request->params['named']['limit'];
        }
        if (!empty($this->request->params['named'])) {
            $this->request->data['Venue'] = array(
                'keyword' => (!empty($this->request->params['named']['keyword'])) ? $this->request->params['named']['keyword'] : '',
                'location' => (!empty($this->request->params['named']['location'])) ? $this->request->params['named']['location'] : ''
            );
            if (!empty($this->request->data['Venue']['keyword'])) {
                $conditions['OR'] = array(
                    'Venue.name Like' => '%' . $this->request->data['Venue']['keyword'] . '%',
                    'Venue.description Like' => '%' . $this->request->data['Venue']['keyword'] . '%'
                );
            }
            if (!empty($this->request->data['Venue']['location'])) {
                $conditions['OR'] = array(
                    'City.name LIKE' => '%' . $this->request->data['Venue']['location'] . '%',
                    'Country.name LIKE' => '%' . $this->request->data['Venue']['location'] . '%',
                    'Venue.landmark Like' => '%' . $this->request->data['Venue']['location'] . '%',
                    'Venue.address Like' => '%' . $this->request->data['Venue']['location'] . '%',
                );
            }
        }
        if (!empty($this->request->params['named']['category'])) {
            $this->pageTitle.= ' - ' . $this->request->params['named']['category'];
            $conditions['VenueType.slug'] = $this->request->params['named']['category'];
            $conditions['Venue.city_id'] = $this->_prefixId;
            $this->set('category', $this->request->params['named']['category']);
        }
        if (!empty($this->request->params['named']['music'])) {
            $this->pageTitle.= ' - ' . $this->request->params['named']['music'];
            $venueIds = $this->Venue->MusicType->find('first', array(
                'conditions' => array(
                    'MusicType.slug' => $this->request->params['named']['music']
                ) ,
                'contain' => array(
                    'Venue' => array(
                        'fields' => array(
                            'Venue.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            foreach($venueIds['Venue'] as $venueId) {
                $conditions['Venue.id'][] = $venueId['id'];
                $conditions['Venue.city_id'] = $this->_prefixId;
            }
            $this->set('music', $this->request->params['named']['music']);
        }
        if (!empty($this->request->params['named']['venue_beginning'])) {
            $this->pageTitle.= ' - ' . $this->request->params['named']['venue_beginning'];
            $conditions['Venue.slug LIKE'] = $this->request->params['named']['venue_beginning'] . '%';
        }
        if (!empty($this->_prefixId) && (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'search')) {
            if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
                $conditions['Venue.' . Inflector::underscore(Configure::read('site.prefix_parameter_model')) . '_id'] = $this->_prefixId;
            }
        }
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
            $setMore = 0;
        }
        if (!empty($this->request->params['named']['sort'])) {
            $this->set('sort', $this->request->params['named']['sort']);
        }
        if (!empty($this->request->params['named']['type'])) {
            switch ($this->request->params['named']['type']) {
                case 'recent':
                    $this->set('setTitle', __l('Recent Venues'));
                    $setMore = 0;
                    $filter = 'recent';
                    break;

                case 'featured':
                    $conditions['OR'][]['Venue.is_feature'] = '1';
                    $conditions['OR'][] = array(
                        'Venue.is_paid' => 1,
                        'Venue.is_featured' => 1,
                        'Venue.featured_end_date >= ' => date('Y-m-d') ,
                    );
                    break;

                case 'featured-all':
                    $conditions['OR'][]['Venue.is_feature'] = '1';
                    $conditions['OR'][] = array(
                        'Venue.is_paid' => 1,
                        'Venue.is_featured' => 1,
                        'Venue.featured_end_date >= ' => date('Y-m-d') ,
                    );
                    break;

                case 'home':
                    $conditions['OR'][]['Venue.is_feature'] = 1;
                    $conditions['OR'][] = array(
                        'Venue.is_paid' => 1,
                        'Venue.is_featured' => 1,
                        'Venue.featured_end_date >= ' => date('Y-m-d') ,
                    );
                    $limit = 3;
                    break;

                case 'home_newest':
                    $limit = '6';
                    $order = array(
                        'Venue.created' => 'desc'
                    );
                    break;

                case 'user':
                    $conditions['Venue.user_id'] = $this->Auth->user('id');
                    $order = array(
                        'Venue.id' => 'desc'
                    );
                    $limit = 10;
                    break;

                case 'similar':
                    if (!empty($this->request->params['named']['venue'])) {
                        $conditions['Venue.id != '] = $this->request->params['named']['venue'];
                    }
                    $this->set('setTitle', __l('People Who Go Here also go to'));
                    $limit = '5';
                    $setMore = 1;
                    $filter = 'similar';
                    break;

                    $this->set('type', $this->request->params['named']['type']);
            }
            if (!empty($filter)) {
                $this->set('filter', $filter);
            }
        }
        $current_time = getdate();
        if (!empty($this->request->params['named']['filter'])) {
            switch ($this->request->params['named']['filter']) {
                case 'thismonth':
                    $tmstart = "$current_time[year]-$current_time[mon]-01";
                    $tmmonth = ($current_time['mon']);
                    if ($tmmonth == "4" or $tmmonth == "6" or $tmmonth == "9" or $tmmonth == "11") {
                        $tmend = "$current_time[year]-$tmmonth-30";
                    } elseif ($tmmonth == "2") {
                        $tmend = "$current_time[year]-$tmmonth-28";
                    } else {
                        $tmend = "$current_time[year]-$tmmonth-31";
                    }
                    $conditions['Venue.created >='] = $tmstart;
                    $conditions['Venue.created <='] = $tmend;
                    break;

                case 'thisweek':
                    $twstart = $current_time[0]-(86400*$current_time['wday']);
                    $twend = $twstart+518400;
                    $conditions['Venue.created >='] = date('Y-m-d', $twstart);
                    $conditions['Venue.created <='] = date('Y-m-d', $twend);
                    break;
            }
        }
        if (!empty($this->request->params['named']['joined'])) {
            $this->pageTitle.= ' - joined by ' . $this->request->params['named']['joined'];
            $venueIds = $this->Venue->VenueUser->find('list', array(
                'conditions' => array(
                    'User.username LIKE' => '%' . $this->request->params['named']['joined'] . '%'
                ) ,
                'fields' => array(
                    'VenueUser.venue_id'
                ) ,
                'recursive' => 0
            ));
            $conditions['Venue.id'] = $venueIds;
            $this->set('joined', $this->request->params['named']['joined']);
            $setMore = 0;
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'near' && !empty($this->request->params['named']['venue_id'])) {
            $venue = $this->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $this->request->params['named']['venue_id']
                ) ,
                'fields' => array(
                    'Venue.latitude',
                    'Venue.longitude'
                ) ,
                'recursive' => -1
            ));
            $conditions['Venue.id != '] = $this->request->params['named']['venue_id'];
            if (!empty($venue['Venue']['latitude']) && !empty($venue['Venue']['longitude'])) {
                $venues = $this->Venue->find('all', array(
                    'fields' => array(
                        'Venue.id',
                        '( 6371 * acos( cos( radians(' . $venue['Venue']['latitude'] . ') ) * cos( radians( Venue.latitude ) ) * cos( radians( Venue.longitude ) - radians(' . $venue['Venue']['longitude'] . ') ) + sin( radians(' . $venue['Venue']['latitude'] . ') ) * sin( radians( Venue.latitude ) ) ) ) AS distance'
                    ) ,
                    'group' => array(
                        'Venue.id HAVING distance < ' . Configure::read('search.default_search_circle')
                    ) ,
                    'order' => 'distance',
                    'recursive' => -1
                ));
                if (!empty($venues)) {
                    foreach($venues as $venue) {
                        $venueIds[] = $venue['Venue']['id'];
                    }
                    $conditions['Venue.id'] = $venueIds;
                }
            }
            $limit = 3;
        }
        if (!empty($this->request->params['named']['city']) and empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'user') {
            if (empty($this->request->params['named']['city'])) {
                $this->request->params['named']['city'] = $this->_prefixSlug;
            }
            $city = $this->Venue->City->find('first', array(
                'conditions' => array(
                    'City.slug' => $this->request->params['named']['city']
                ) ,
                'recursive' => -1
            ));
            $conditions['Venue.city_id'] = $city['City']['id'];
        }
        if (!empty($this->request->data['VenueSearch']['City'])) {
            array_push($this->request->data['VenueSearch']['City'], $this->_prefixId);
            $conditions['Venue.city_id'] = $this->request->data['VenueSearch']['City'];
        }
        $this->set('setMore', $setMore);
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'VenueType' => array(
                    'fields' => array(
                        'VenueType.id',
                        'VenueType.name',
                        'VenueType.slug',
                    )
                ) ,
                'VenueCategory' => array(
                    'fields' => array(
                        'VenueCategory.id',
                        'VenueCategory.name',
                    )
                ) ,
                'MusicType' => array(
                    'fields' => array(
                        'MusicType.id',
                        'MusicType.name',
                        'MusicType.slug',
                    )
                ) ,
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.slug'
                    )
                ) ,
                'Attachment',
            ) ,
            'order' => $order,
            'recursive' => 2,
            'limit' => $limit
        );
        $this->set('venues', $this->paginate());
        if (empty($this->request->params['requested'])) {
            $this->set('venueCities', $this->Venue->_getVenueCities($this->_prefixId, $this->_prefixSlug));
            $this->set('venueKeywords', $this->Venue->_getVenueKeywords($this->_prefixId));
            $this->set('venueTypes', $this->Venue->_getVenueTypes());
            if (!empty($this->_prefixId)) {
                $this->set('venueTypeVenueCount', $this->Venue->_getVenueTypesVenueCount($this->_prefixId));
            }
            $this->set('musicTypes', $this->Venue->_getMusicTypes());
            if (!empty($this->_prefixId)) {
                $this->set('musicTypeVenueCount', $this->Venue->_getMusicTypesVenueCount($this->_prefixId));
            }
        }
        if ((!empty($this->request->params['named']['list']) && $this->request->params['named']['list'] == 'home')) {
            $this->autoRender = false;
            $this->render('my_venue');
        } else if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'home' || $this->request->params['named']['type'] == 'home_newest')) {
            $this->autoRender = false;
            $this->render('venue_home_index');
            $setMore = 1;
        } else if (!empty($this->request->params['named']['joined']) || (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'featured' || $this->request->params['named']['type'] == 'list' || $this->request->params['named']['type'] == 'near'))) {
            $this->autoRender = false;
            $this->render('index_compact');
        } elseif (!empty($this->request->params['named']['photo'])) {
            $this->autoRender = false;
            $this->render('venue_photo');
            $setMore = 1;
        }
        $this->set('setMore', $setMore);
    }
    public function search() 
    {
        $this->set('venueCities', $this->Venue->_getVenueCities($this->_prefixId, $this->_prefixSlug));
        $this->set('venueKeywords', $this->Venue->_getVenueKeywords($this->_prefixId));
        $this->set('venueTypes', $this->Venue->_getVenueTypes());
        if (!empty($this->_prefixId)) {
            $this->set('venueTypeVenueCount', $this->Venue->_getVenueTypesVenueCount($this->_prefixId));
        }
        $this->set('musicTypes', $this->Venue->_getMusicTypes());
        if (!empty($this->_prefixId)) {
            $this->set('musicTypeVenueCount', $this->Venue->_getMusicTypesVenueCount($this->_prefixId));
        }
    }
    public function view($slug = null) 
    {
        $this->pageTitle = __l('Venue');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $venue = $this->Venue->find('first', array(
            'conditions' => array(
                'Venue.slug' => $slug,
            ) ,
            'contain' => array(
                'VenueType' => array(
                    'fields' => array(
                        'VenueType.id',
                        'VenueType.name',
                        'VenueType.slug',
                    )
                ) ,
                'VenueCategory' => array(
                    'fields' => array(
                        'VenueCategory.id',
                        'VenueCategory.name',
                    )
                ) ,
                'ParkingType' => array(
                    'fields' => array(
                        'ParkingType.id',
                        'ParkingType.name',
                    )
                ) ,
                'MusicType' => array(
                    'fields' => array(
                        'MusicType.id',
                        'MusicType.name',
                    )
                ) ,
                'VenueFeature' => array(
                    'fields' => array(
                        'VenueFeature.id',
                        'VenueFeature.name',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.slug'
                    )
                ) ,
                'PhotoAlbum' => array(
                    'fields' => array(
                        'PhotoAlbum.id',
                        'PhotoAlbum.title',
                        'PhotoAlbum.slug'
                    ) ,
                    'Photo' => array(
                        'User' => array(
                            'fields' => array(
                                'User.username',
                            )
                        ) ,
                        'limit' => 1,
                        'order' => 'Photo.id desc',
                        'Attachment'
                    ) ,
                ) ,
                'Attachment',
                'VenueLogo',
                'WideScreen',
                'VenueGallery' => array(
                    'Photo' => array(
                        'Attachment'
                    )
                ) ,
                'VenueSponsor' => array(
                    'Attachment',
                ) ,
                'Video' => array(
                    'fields' => array(
                        'Video.id',
                        'Video.title',
                        'Video._temp_slug',
                        'Video.slug',
                        'Video.default_thumbnail_id',
                        'Video.video_view_count',
                        'Video.created',
                    ) ,
                    'User' => array(
                        'fields' => array(
                            'User.username',
                        )
                    )
                ) ,
            ) ,
            'recursive' => 3
        ));
        if (empty($venue)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $meta_keywords = '';
        if (!empty($venue['VenueCategory'])) {
            $venueCategories = array();
            foreach($venue['VenueCategory'] as $venueCategory) {
                $venueCategories[] = $venueCategory['name'];
            }
            $meta_keywords.= ', ' . implode(', ', $venueCategories);
        }
        Configure::write('meta.keywords', Configure::read('meta.keywords') . ', ' . $venue['VenueType']['name'] . $meta_keywords);
        Configure::write('meta.description', $venue['Venue']['name'] . ' is a venue at ' . $venue['City']['name']);
        if (!empty($venue['Attachment'])) {
            $image_options = array(
                'dimension' => 'big_thumb',
                'class' => '',
                'alt' => $venue['Venue']['name'],
                'title' => $venue['Venue']['name'],
                'type' => 'png',
                'full_url' => true,
            );
            $venue_image = getImageUrl('Venue', $venue['Attachment'], $image_options, true);
            Configure::write('meta.image', $venue_image);
        }
        if (!empty($venue['Venue']['name'])) {
            Configure::write('meta.name', $venue['Venue']['name']);
        }
        $employee_size = array(
            '1' => '< 200',
            '2' => '200 - 1000',
            '3' => '1000 - 10000',
            '4' => '10000 or more'
        );
        $square_footage = array(
            '1' => '< 500',
            '2' => '500 - 1000',
            '3' => '1000 - 10000',
            '4' => '10000 or more'
        );
        $sales_volume = array(
            '1' => '< 300',
            '2' => '300 - 1000',
            '3' => '1000 - 10000',
            '4' => '10000 or more'
        );
        $venueUsers = $this->Venue->VenueUser->find('all', array(
            'conditions' => array(
                'VenueUser.user_id' => $this->Auth->user('id') ,
            ) ,
            'fields' => array(
                'VenueUser.venue_id',
                'VenueUser.id'
            ) ,
            'recursive' => -1,
        ));
        $venueUser = $this->Venue->VenueUser->find('first', array(
            'conditions' => array(
                'VenueUser.user_id' => $this->Auth->user('id') ,
                'VenueUser.venue_id' => $venue['Venue']['id'],
            ) ,
            'recursive' => -1,
        ));
        $this->set('venueUser', $venueUser);
        if (!empty($conditions)) {
            foreach($venueUsers as $venueUser) {
                $venue_users[$venueUser['VenueUser']['venue_id']] = $venueUser['VenueUser']['id'];
            }
            $this->set('venue_users', $venue_users);
        }
        $this->pageTitle.= ' - ' . $venue['Venue']['name'];
        $this->request->data['VenueComment']['venue_id'] = $venue['Venue']['id'];
        $this->request->data['VenueComment']['venue_slug'] = $venue['Venue']['slug'];
        $this->set('venue', $venue);
        $BeerPrice = $this->Venue->BeerPrice;
        $FoodSold = $this->Venue->FoodSold;
        $LiveBand = $this->Venue->LiveBand;
        $this->set('BeerPrice', $BeerPrice);
        $this->set('LiveBand', $LiveBand);
        $this->set('FoodSold', $FoodSold);
        $this->set('employee_size', $employee_size);
        $this->set('square_footage', $square_footage);
        $this->set('sales_volume', $sales_volume);
    }
    public function add($from_event = null) 
    {
        $this->pageTitle = __l('Add Venue');
        $this->Venue->Attachment->Behaviors->attach('ImageUpload', Configure::read('venue.file'));
        if (!empty($this->request->data)) {
            if (!$this->Auth->user('is_email_confirmed')) {
                $this->Session->setFlash(__l('Oops, Still you are not confirm the email') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'venues',
                    'action' => 'add'
                ));
            }
            if (empty($this->request->params['admin'])) {
                $this->request->data['Venue']['user_id'] = $this->Auth->user('id');
            }
            $this->request->data['Venue']['ip_id'] = $this->Venue->toSaveIp();
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            $this->Venue->Attachment->set($this->request->data);
            $this->Venue->set($this->request->data);
            $this->Venue->City->set($this->request->data);
            $this->Venue->Country->set($this->request->data);
            if ($this->Venue->validates() &$this->Venue->Attachment->validates() &$this->Venue->City->validates()) {
                $this->Venue->create();
                $this->request->data['Venue']['city_id'] = $this->Venue->City->findOrSaveAndGetId($this->request->data['City']['name']);
                $city = $this->Venue->City->find('first', array(
                    'conditions' => array(
                        'City.id' => $this->request->data['Venue']['city_id']
                    ) ,
                    'fields' => array(
                        'City.id',
                        'City.country_id'
                    ) ,
                    'recursive' => -1
                ));
                if (!empty($city['City']['country_id'])) {
                    $this->request->data['Venue']['country_id'] = $city['City']['country_id'];
                }
                if (!empty($this->request->data['VenueSponsor'])) {
                    $this->request->data['Venue']['is_sponsor'] = '1';
                } else {
                    $this->request->data['Venue']['is_sponsor'] = '0';
                }
                if (isset($this->request->data['Venue']['is_featured']) && $this->request->data['Venue']['is_featured'] == 1) {
                    $featured = $this->Venue->FeaturedVenueSubscription->find('first', array(
                        'conditions' => array(
                            'FeaturedVenueSubscription.id' => $this->request->data['Venue']['featured_venue_subscription_id']
                        ) ,
                        'recursive' => -1
                    ));
                    $this->request->data['Venue']['featured_end_date'] = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") +$featured['FeaturedVenueSubscription']['name'], date("Y")));
                }
                if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                    $this->request->data['Venue']['is_active'] = (Configure::read('site.is_admin_activate_after_venue_add')) ? 0 : 1;
                } else {
                    $this->request->data['Venue']['is_active'] = 1;
                }
                if ($this->Venue->save($this->request->data)) {
                    $venue_id = $this->Venue->getLastInsertId();
                    if (!empty($this->request->data['Venue']['venue_category'])) {
                        $venueCategory['venue_id'] = $venue_id;
                        for ($i = 0; $i < count($this->request->data['Venue']['venue_category']); $i++) {
                            $this->Venue->VenuesVenueCategory->create();
                            $venueCategory['venue_category_id'] = $this->request->data['Venue']['venue_category'][$i];
                            $this->Venue->VenuesVenueCategory->save($venueCategory);
                        }
                    }
                    if (!empty($this->request->data['Venue']['venue_music'])) {
                        $venueMusics['venue_id'] = $venue_id;
                        for ($i = 0; $i < count($this->request->data['Venue']['venue_music']); $i++) {
                            $this->Venue->VenuesMusicType->create();
                            $venueMusics['music_type_id'] = $this->request->data['Venue']['venue_music'][$i];
                            $this->Venue->VenuesMusicType->save($venueMusics);
                        }
                    }
                    if (!empty($this->request->data['Attachment']['filename']['name'])) {
                        $this->Venue->Attachment->create();
                        $this->request->data['Attachment']['class'] = $this->modelClass;
                        $this->request->data['Attachment']['description'] = 'VenueImage';
                        $this->request->data['Attachment']['foreign_id'] = $venue_id;
                        $this->Venue->Attachment->save($this->request->data['Attachment']);
                    }
                    if (!empty($this->request->data['Attachment']['logo']['name'])) {
                        $this->Venue->Attachment->create();
                        $this->request->data['Attachment']['class'] = 'VenueLogo';
                        $this->request->data['Attachment']['description'] = 'VenueImage';
                        $this->request->data['Attachment']['foreign_id'] = $venue_id;
                        $this->Venue->Attachment->save($this->request->data['Attachment']);
                    }
                    if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                        if (Configure::read('site.is_admin_activate_after_venue_add')) {
                            $this->Session->setFlash(__l('Venue has been added. After admin approval it will list out in site.') , 'default', null, 'success');
                        } else {
                            $this->Session->setFlash(__l('Venue has been added') , 'default', null, 'success');
                        }
                    } else {
                        $this->Session->setFlash(__l('Venue has been added') , 'default', null, 'success');
                    }
                    $venue = $this->Venue->find('first', array(
                        'conditions' => array(
                            'Venue.id = ' => $venue_id
                        )
                    ));
                    $user = $this->Venue->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $venue['Venue']['user_id']
                        ) ,
                        'recursive' => -1
                    ));
                    $url = Router::url(array(
                        'controller' => 'venues',
                        'action' => 'view',
                        'admin' => false,
                        $venue['Venue']['slug'],
                    ) , true);
                    // venue willn't be posted if it is autoflagged and suspend
                    if (!$venue['Venue']['admin_suspend'] && $venue['Venue']['is_active']) {
                        $image_options = array(
                            'dimension' => 'normal_thumb',
                            'class' => '',
                            'alt' => $venue['Venue']['name'],
                            'title' => $venue['Venue']['name'],
                            'type' => 'jpg'
                        );
                        $post_data = array();
                        $post_data['message'] = $user['User']['username'] . ' ' . __l('addd a new venue "') . '' . $venue['Venue']['name'] . __l('" in ') . Configure::read('site.name');
                        $post_data['image_url'] = Router::url('/', true) . getImageUrl('Venue', $venue['Attachment'], $image_options);
                        $post_data['link'] = $url;
                        $post_data['description'] = $venue['Venue']['description'];
                        // Post on user facebook
                        if (Configure::read('social_networking.post_venue_on_user_facebook')) {
                            if ($user['User']['fb_user_id'] > 0) {
                                $post_data['fb_user_id'] = $user['User']['fb_user_id'];
                                $post_data['fb_access_token'] = $user['User']['fb_access_token'];
                                $getFBReturn = $this->postOnFacebook($post_data, 0);
                            }
                        }
                        // post on user twitter
                        if (Configure::read('social_networking.post_venue_on_user_twitter')) {
                            if (!empty($user['User']['twitter_access_token']) && !empty($user['User']['twitter_access_key'])) {
                                $post_data['twitter_access_key'] = $user['User']['twitter_access_key'];
                                $post_data['twitter_access_token'] = $user['User']['twitter_access_token'];
                                $getTewwtReturn = $this->postOnTwitter($post_data, 0);
                            }
                        }
                        if (Configure::read('venue.post_on_facebook')) { // post on site facebook
                            $getFBReturn = $this->postOnFacebook($post_data, 1);
                        }
                        if (Configure::read('venue.post_on_twitter')) { // post on site twitter
                            $getTewwtReturn = $this->postOnTwitter($post_data, 1);
                        }
                    }
                    if (!empty($this->request->params['admin'])) {
                        $this->redirect(array(
                            'controller' => 'venues',
                            'action' => 'admin_index'
                        ));
                    } else {
                        /*	$this->redirect(array(
                        'controller' => 'payments',
                        'action' => 'order',
                        $venue['Venue']['slug'],
                        'venue'
                        ));*/
                        $this->redirect(array(
                            'controller' => 'users',
                            'action' => 'dashboard',
                        ));
                    }
                }
            } else {
                $this->Session->setFlash(__l('Venue could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $venueTypes = $this->Venue->VenueType->find('list', array(
            'conditions' => array(
                'VenueType.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueType.name' => 'ASC'
            )
        ));
        $venueCategories = $this->Venue->VenueCategory->find('list', array(
            'conditions' => array(
                'VenueCategory.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueCategory.name' => 'ASC'
            )
        ));
        $venueSponsors = $this->Venue->VenueSponsor->find('list', array(
            'conditions' => array(
                'VenueSponsor.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueSponsor.first_name' => 'ASC'
            )
        ));
        $venueMusics = $this->Venue->MusicType->find('list', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            ) ,
            'order' => array(
                'MusicType.name' => 'ASC'
            )
        ));
        if (!empty($this->request->params['admin'])) {
            $user_types = array(
                ConstUserTypes::VenueOwner => ConstUserTypes::VenueOwner,
                ConstUserTypes::Admin => ConstUserTypes::Admin
            );
            $users = $this->Venue->Event->User->find('list', array(
                'conditions' => array(
                    'User.is_active = ' => 1,
                    'User.user_type_id' => $user_types,
                )
            ));
            $this->set('users', $users);
        }
        $cities = $this->Venue->City->find('list');
        $city = $this->Venue->City->find('first', array(
            'conditions' => array(
                'City.slug' => $this->request->params['named']['city']
            ) ,
            'fields' => array(
                'City.id',
                'City.name',
                'City.country_id',
            ) ,
            'recursive' => -1
        ));
        $countries = $this->Venue->Country->find('list');
        $this->request->data['Venue']['country_id'] = $city['City']['country_id'];
        if (empty($this->request->data['City'])) {
            $this->request->data['City']['name'] = $city['City']['name'];
        }
        $featuredVenueSubscription_lists = $this->Venue->FeaturedVenueSubscription->find('all', array(
            'conditions' => array(
                'FeaturedVenueSubscription.is_active' => 1
            ) ,
            'recursive' => -1
        ));
        foreach($featuredVenueSubscription_lists as $featuredVenueSubscription_list) {
            if ($featuredVenueSubscription_list['FeaturedVenueSubscription']['name'] < 30) {
                $name = $featuredVenueSubscription_list['FeaturedVenueSubscription']['name'] . ' Days';
            } else {
                //floor
                $name = floor($featuredVenueSubscription_list['FeaturedVenueSubscription']['name']/30);
                if ($name == 1) {
                    $name.= ' Month';
                } else {
                    $name.= ' Months';
                }
            }
            $featuredVenueSubscriptions[$featuredVenueSubscription_list['FeaturedVenueSubscription']['id']] = $name . ' - ' . Configure::read('site.currency') . $featuredVenueSubscription_list['FeaturedVenueSubscription']['amount'];
        }
        $this->set(compact('venueTypes', 'venueSponsors', 'venueCategories', 'venueMusics', 'countries', 'cities', 'days', 'featuredVenueSubscriptions'));
        if (!empty($from_event)) {
            $this->set('from_event', $from_event);
        }
    }
    public function edit($id = null) 
    {
    $this->disableCache();
        $this->pageTitle = __l('Edit Venue');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $venue_id = $id;
        $venue = $this->Venue->find('first', array(
            'conditions' => array(
                'Venue.id' => $id
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.email'
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir'
                    )
                ) ,
                'VenueLogo' => array(
                    'fields' => array(
                        'VenueLogo.id',
                        'VenueLogo.filename',
                        'VenueLogo.dir'
                    )
                ) ,
                'WideScreen' => array(
                    'fields' => array(
                        'WideScreen.id',
                        'WideScreen.filename',
                        'WideScreen.dir'
                    )
                ) ,
            ) ,
            'recursive' => 2
        ));
          if (!empty($this->request->data)) {
            if (!$this->Auth->user('is_email_confirmed')) {
                $this->Session->setFlash(__l('Oops, Still you are not confirm the email') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'venues',
                    'action' => 'edit'
                ));
            }
            if (empty($this->request->data['Venue']['form_type']) || (!empty($this->request->data['Venue']['form_type']) && $this->request->data['Venue']['form_type'] != 'suggestion')) {
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                    $this->Venue->Attachment->Behaviors->attach('ImageUpload', Configure::read('venue.file'));
                    $this->Venue->Attachment->set($this->request->data);
                }
                if (!empty($this->request->data['Logo']['filename']['name'])) {
                    $this->request->data['Logo']['filename']['type'] = get_mime($this->request->data['Logo']['filename']['tmp_name']);
                    $this->Venue->VenueLogo->Behaviors->attach('ImageUpload', Configure::read('venue.file'));
                    $this->Venue->VenueLogo->set($this->request->data);
                }
                if (!empty($this->request->data['WideScreen']['filename']['name'])) {
                    $this->request->data['WideScreen']['filename']['type'] = get_mime($this->request->data['WideScreen']['filename']['tmp_name']);
                    $this->Venue->WideScreen->Behaviors->attach('ImageUpload', Configure::read('venue.file'));
                    $this->Venue->WideScreen->set($this->request->data);
                }
                if (!empty($this->request->data['VenueSponsor'])) {
                    $this->request->data['Venue']['is_sponsor'] = '1';
                } else {
                    $this->request->data['Venue']['is_sponsor'] = '0';
                }
                $this->Venue->set($this->request->data);
                if ($this->Venue->validates() &$this->Venue->Attachment->validates() &$this->Venue->VenueLogo->validates() &$this->Venue->WideScreen->validates()) {
                    $this->request->data['Venue']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->Venue->City->findOrSaveAndGetId($this->request->data['City']['name']);
                    $this->Venue->save($this->request->data);
                    if (!empty($this->request->data['Venue']['venue_category'])) {
                        $this->Venue->VenuesVenueCategory->deleteAll(array(
                            'VenuesVenueCategory.venue_id' => $this->request->data['Venue']['id']
                        ));
                        $venueCategory['venue_id'] = $this->request->data['Venue']['id'];
                        for ($i = 0; $i < count($this->request->data['Venue']['venue_category']); $i++) {
                            $this->Venue->VenuesVenueCategory->create();
                            $venueCategory['venue_category_id'] = $this->request->data['Venue']['venue_category'][$i];
                            $this->Venue->VenuesVenueCategory->save($venueCategory);
                        }
                    }
                    if (!empty($this->request->data['Venue']['venue_music'])) {
                        $this->Venue->VenuesMusicType->deleteAll(array(
                            'VenuesMusicType.venue_id' => $this->request->data['Venue']['id']
                        ));
                        $venueMusics['venue_id'] = $this->request->data['Venue']['id'];
                        for ($i = 0; $i < count($this->request->data['Venue']['venue_music']); $i++) {
                            $this->Venue->VenuesMusicType->create();
                            $venueMusics['music_type_id'] = $this->request->data['Venue']['venue_music'][$i];
                            $this->Venue->VenuesMusicType->save($venueMusics);
                        }
                    }
                    if (!empty($this->request->data['Venue']['parking_type'])) {
                        $venueParking['venue_id'] = $this->request->data['Venue']['id'];
                        for ($i = 0; $i < count($this->request->data['Venue']['parking_type']); $i++) {
                            $this->Venue->VenuesParkingType->create();
                            $venueParking['parking_type_id'] = $this->request->data['Venue']['parking_type'][$i];
                            $this->Venue->VenuesParkingType->save($venueParking);
                        }
                    }
                    if (!empty($this->request->data['Venue']['venue_feature'])) {
                        $venueFeatures['venue_id'] = $this->request->data['Venue']['id'];
                        for ($i = 0; $i < count($this->request->data['Venue']['venue_feature']); $i++) {
                            $this->Venue->VenuesVenueFeature->create();
                            $venueFeatures['music_type_id'] = $this->request->data['Venue']['venue_feature'][$i];
                            $this->Venue->VenuesVenueFeature->save($venueFeatures);
                        }
                    }
                    if (!empty($this->request->data['Attachment']['filename']['name'])) {
                        $this->request->data['Attachment']['class'] = $this->modelClass;
                        $this->request->data['Attachment']['foreign_id'] = $id;
                        $this->request->data['Attachment']['description'] = 'VenueImage';
                        if (!empty($venue['Attachment']['id'])) {
                            $this->request->data['Attachment']['id'] = $venue['Attachment']['id'];
                        }
                        $this->request->data['Attachment']['foreign_id'] = $this->request->data['Venue']['id'];
                        $this->Venue->Attachment->save($this->request->data['Attachment']);
                        unset($this->Venue->Attachment->id);
                    }
                    if (!empty($this->request->data['Logo']['filename']['name'])) {
                        $this->request->data['Attachment']['filename'] = $this->request->data['Logo']['filename'];
                        $this->request->data['Attachment']['class'] = 'VenueLogo';
                        $this->request->data['Attachment']['foreign_id'] = $id;
                        $this->request->data['Attachment']['description'] = 'VenueImage';
                        if (!empty($venue['VenueLogo']['id'])) {
                            $this->request->data['Attachment']['id'] = $venue['VenueLogo']['id'];
                        }
                        $this->request->data['Attachment']['foreign_id'] = $this->request->data['Venue']['id'];
                        $this->Venue->Attachment->save($this->request->data);
                    }
                    if (!empty($this->request->data['WideScreen']['filename']['name'])) {
                        $this->request->data['Attachment']['filename'] = $this->request->data['WideScreen']['filename'];
                        $this->request->data['Attachment']['class'] = 'WideScreen';
                        $this->request->data['Attachment']['foreign_id'] = $id;
                        $this->request->data['Attachment']['description'] = 'VenueImage';
                        if (!empty($venue['WideScreen']['id'])) {
                            $this->request->data['Attachment']['id'] = $venue['WideScreen']['id'];
                        }
                        $this->request->data['Attachment']['foreign_id'] = $this->request->data['Venue']['id'];
                        $this->Venue->Attachment->save($this->request->data);
                    }
                    $this->Session->setFlash(__l('Venue has been updated') , 'default', null, 'success');
                    $venues = $this->Venue->find('first', array(
                        'conditions' => array(
                            'Venue.id' => $this->request->data['Venue']['id']
                        ) ,
                    ));
                    if (!empty($this->request->params['admin'])) {
                        $this->redirect(array(
                            'controller' => 'venues',
                            'action' => 'admin_index'
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'venues',
                            'action' => 'view',
                            $venues['Venue']['slug']
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Venue could not be updated. Please, try again.') , 'default', null, 'error');
                }
            } else {
				 $country = $this->Venue->Country->find('list', array(
                    'conditions' => array(
                        'Country.id' => $this->request->data['Venue']['country_id']
                    ) ,
                ));
				$venue_country = implode(',', $country);
                $music = $this->Venue->MusicType->find('list', array(
                    'conditions' => array(
                        'MusicType.id' => $this->request->data['Venue']['venue_music']
                    ) ,
                ));
                $venue_music = implode(',', $music);
                $categories = $this->Venue->VenueCategory->find('list', array(
                    'conditions' => array(
                        'VenueCategory.id' => $this->request->data['Venue']['venue_category']
                    )
                ));
                $venue_category = implode(',', $categories);
                $park_type = $this->Venue->ParkingType->find('list', array(
                    'conditions' => array(
                        'ParkingType.id' => $this->request->data['Venue']['parking_type']
                    )
                ));
                $parking_type = implode(',', $park_type);
                $feature = $this->Venue->VenueFeature->find('list', array(
                    'conditions' => array(
                        'VenueFeature.id' => $this->request->data['VenueFeature']['VenueFeature']
                    )
                ));
                $venue_feature = implode(',', $feature);
				
                $email = $this->EmailTemplate->selectTemplate('Venue Suggestion');
                $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                $emailFindReplace = array(
                    '##VenueName##' => $this->request->data['Venue']['name'],
                    '##Address##' => !empty($this->request->data['Venue']['address']) ? $this->request->data['Venue']['address'] : '-',
                    '##Address2##' => !empty($this->request->data['Venue']['address2']) ? $this->request->data['Venue']['address2'] : '-',
                    '##ZipCode##' => !empty($this->request->data['Venue']['zip_code']) ? $this->request->data['Venue']['zip_code'] : '-',
                    '##State##' => !empty($this->request->data['State']['name']) ? $this->request->data['State']['name'] : '',
                    '##City##' => $this->request->data['City']['name'],
                    '##Country##' => !empty($venue_country) ? $venue_country : '-',
                    '##Street##' => !empty($this->request->data['Venue']['street']) ? $this->request->data['Venue']['street'] : '-',
                    '##Phone##' => !empty($this->request->data['Venue']['phone']) ? $this->request->data['Venue']['phone'] : '-',
                    '##Email##' => !empty($this->request->data['Venue']['email']) ? $this->request->data['Venue']['email'] : '-',
                    '##Website##' => !empty($this->request->data['Venue']['website']) ? $this->request->data['Venue']['website'] : '-',
                    '##Description##' => $this->request->data['Venue']['description'],
                    '##DoorPolicy##' => !empty($this->request->data['Venue']['door_policy']) ? $this->request->data['Venue']['door_policy'] : '-',
                    '##Capacity##' => $this->request->data['Venue']['capacity'],
                    '##VenueCategory##' => !empty($venue_category) ? $venue_category : '-',
                    '##Music##' => !empty($venue_music) ? $venue_music : '-',
                    '##OpenStatus##' => !empty($this->request->data['Venue']['is_closed']) ? 'Closed' : 'Open',
                    '##ImportBeerPrice##' => $this->Venue->BeerPrice[$this->request->data['Venue']['import_beer_price_id']],
                    '##DomesticBeerPrice##' => $this->Venue->BeerPrice[$this->request->data['Venue']['domestic_beer_price_id']],
                    '##WellDrinkPrice##' => $this->Venue->BeerPrice[$this->request->data['Venue']['well_drink_price_id']],
                    '##SoftDrinkPrice##' => $this->Venue->BeerPrice[$this->request->data['Venue']['soft_drink_price_id']],
                    '##ParkingType##' => !empty($parking_type) ? $parking_type : '-',
                    '##EmployeeSize##' => $this->Venue->EmployeeSize[$this->request->data['Venue']['employee_size_id']],
                    '##USERNAME##' => !empty($this->request->data['User']['name']) ? $this->request->data['User']['name'] : '',
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##VenueFeature##' => !empty($venue_feature) ? $venue_feature : '-',
                    '##SquareFootage##' => $this->Venue->SquareFootage[$this->request->data['Venue']['square_footage_id']],
                    '##SITE_URL##' => Router::url('/', true)
                );
                $this->Email->to = Configure::read('site.contact_email') . ', ' . $venue['User']['email'];
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(strtr($email['email_content'], $emailFindReplace));
				$this->log(strtr($email['email_content'], $emailFindReplace));
                $this->Session->setFlash(__l('Venue suggested correction has been sent') , 'default', null, 'success');
                if (!empty($this->request->params['admin'])) {
                    $this->redirect(array(
                        'controller' => 'venues',
                        'action' => 'admin_index'
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'venues',
                        'action' => 'view',
                        $venue['Venue']['slug']
                    ));
                }
            }
        } else {
            $this->request->data = $this->Venue->read(null, $id);
            $this->request->data['Venue']['venue_music'] = $this->Venue->VenuesMusicType->find('list', array(
                'conditions' => array(
                    'VenuesMusicType.venue_id' => $id
                ) ,
                'fields' => array(
                    'VenuesMusicType.music_type_id'
                ) ,
            ));
            $this->request->data['Venue']['venue_category'] = $this->Venue->VenuesVenueCategory->find('list', array(
                'conditions' => array(
                    'VenuesVenueCategory.venue_id' => $id
                ) ,
                'fields' => array(
                    'VenuesVenueCategory.venue_category_id'
                ) ,
            ));
            $this->request->data['Venue']['parking_type'] = $this->Venue->VenuesParkingType->find('list', array(
                'conditions' => array(
                    'VenuesParkingType.venue_id' => $id
                ) ,
                'fields' => array(
                    'VenuesParkingType.parking_type_id'
                ) ,
            ));
            $this->request->data['Venue']['venue_feature'] = $this->Venue->VenuesVenueFeature->find('list', array(
                'conditions' => array(
                    'VenuesVenueFeature.venue_id' => $id
                ) ,
                'fields' => array(
                    'VenuesVenueFeature.venue_feature_id'
                ) ,
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Venue']['name'];
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'suggestion') {
            if (empty($this->request->params['admin'])) {
                if ($venue['Venue']['user_id'] != $this->Auth->user('id')) {
                    $this->Session->setFlash(__l(' You can\'t edit other\'s Venues.') , 'default', null, 'error');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
            }
        }
        $venueTypes = $this->Venue->VenueType->find('list', array(
            'conditions' => array(
                'VenueType.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueType.name' => 'ASC'
            )
        ));
        $venueCategories = $this->Venue->VenueCategory->find('list', array(
            'conditions' => array(
                'VenueCategory.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueCategory.name' => 'ASC'
            )
        ));
        $venueMusics = $this->Venue->MusicType->find('list', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            ) ,
            'order' => array(
                'MusicType.name' => 'ASC'
            )
        ));
        $parkingTypes = $this->Venue->ParkingType->find('list', array(
            'conditions' => array(
                'ParkingType.is_active = ' => 1
            )
        ));
        $venueFeatures = $this->Venue->VenueFeature->find('list', array(
            'conditions' => array(
                'VenueFeature.is_active = ' => 1
            )
        ));
        $venueSponsors = $this->Venue->VenueSponsor->find('list', array(
            'conditions' => array(
                'VenueSponsor.is_active = ' => 1
            ) ,
            'order' => array(
                'VenueSponsor.first_name' => 'ASC'
            )
        ));
        if (!empty($this->request->params['admin'])) {
            $users = $this->Venue->Event->User->find('list', array(
                'conditions' => array(
                    'User.is_active = ' => 1
                )
            ));
            $this->set('users', $users);
        }
        $cities = $this->Venue->City->find('list');
        $countries = $this->Venue->Country->find('list');
        $cellproviders = $this->CellProvider->find('list');
        $BeerPrice = $this->Venue->BeerPrice;
        $FoodSold = $this->Venue->FoodSold;
        $LiveBand = $this->Venue->LiveBand;
        $EmployeeSize = $this->Venue->EmployeeSize;
        $SquareFootage = $this->Venue->SquareFootage;
        $SalesVolume = $this->Venue->SalesVolume;
        $this->set('BeerPrice', $BeerPrice);
        $this->set('LiveBand', $LiveBand);
        $this->set('FoodSold', $FoodSold);
        $this->set('EmployeeSize', $EmployeeSize);
        $this->set('SquareFootage', $SquareFootage);
        $this->set('SalesVolume', $SalesVolume);
        $this->set(compact('venue', 'venueTypes', 'venueSponsors', 'parkingTypes', 'venueFeatures', 'venueCategories', 'venueMusics', 'cellproviders', 'countries', 'cities', 'days'));
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'suggestion') {
            $this->autoRender = false;
            $this->render('venue_suggestion');
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $venue = $this->Venue->find('first', array(
            'conditions' => array(
                'Venue.id = ' => $id
            ) ,
            'fields' => array(
                'Venue.user_id'
            ) ,
            'recursive' => -1,
        ));
        if ($venue['Venue']['user_id'] != $this->Auth->user('id')) {
            throw new NotFoundException(__l('Invalid request'));
        } else {
            if ($this->Venue->delete($id, true)) {
                $this->Session->setFlash(__l('Venue deleted') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Venues');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'keyword',
            'user'
        ));
        if (!empty($this->request->params['named'])) {
            $this->request->data['Venue'] = array(
                'keyword' => (!empty($this->request->params['named']['keyword'])) ? $this->request->params['named']['keyword'] : '',
                'user' => (!empty($this->request->params['named']['user'])) ? $this->request->params['named']['user'] : ''
            );
            if (!empty($this->request->data['Venue']['keyword'])) {
                $conditions['OR'] = array(
                    'Venue.name Like' => '%' . $this->request->data['Venue']['keyword'] . '%',
                    'Venue.description Like' => '%' . $this->request->data['Venue']['keyword'] . '%',
                    'Venue.address Like' => '%' . $this->request->data['Venue']['keyword'] . '%',
                    'Venue.landmark Like' => '%' . $this->request->data['Venue']['keyword'] . '%',
                );
            }
            if (!empty($this->request->data['Venue']['user'])) {
                $conditions['Venue.user_id'] = $this->request->data['Venue']['user'];
            }
        }
        if (isset($this->request->params['named']['username'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['username']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Venue.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Venue.created) <= '] = 7;
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['Venue.created >='] = date('Y-m-d 00:00:00', (strtotime('last month', strtotime(date('m/01/y')))));
            $conditions['Venue.created <='] = date('Y/m/d h:i:s', (strtotime('next month', strtotime(date('m/01/y'))) -1));
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (!empty($this->request->params['named']['sponsor'])) {
            $venueSponsors = $this->Venue->VenuesVenueSponsor->find('list', array(
                'conditions' => array(
                    'VenueSponsor.slug' => $this->request->params['named']['sponsor']
                ) ,
                'fields' => array(
                    'VenuesVenueSponsor.venue_id',
                    'VenuesVenueSponsor.venue_sponsor_id',
                ) ,
                'recursive' => 2
            ));
            $conditions['Venue.id'] = $venueSponsors;
            $venueSponsorname = $this->Venue->VenueSponsor->find('first', array(
                'conditions' => array(
                    'VenueSponsor.slug =' => $this->request->params['named']['sponsor']
                ) ,
                'fields' => array(
                    'VenueSponsor.first_name',
                ) ,
                'recursive' => -1
            ));
            $this->pageTitle.= __l(' - Sponsor - ' . $venueSponsorname['VenueSponsor']['first_name']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Venue.is_active'] = 1;
                $conditions['Venue.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Venue.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Venue.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Venue.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Featured) {
                $conditions['Venue.is_feature'] = 1;
                $this->pageTitle.= __l(' - Featured ');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'VenueType' => array(
                    'fields' => array(
                        'VenueType.id',
                        'VenueType.name',
                        'VenueType.slug',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.slug'
                    )
                ) ,
                'Country' => array(
                    'fields' => array(
                        'Country.id',
                        'Country.name',
                        'Country.slug'
                    )
                ) ,
				'Ip' => array(
                    'fields' => array(
                        'Ip.ip',
                    )
                ) ,
                'Attachment',
            ) ,
            'order' => 'Venue.id DESC',
            'recursive' => 3,
        );
        if (!empty($this->request->params['named']['dashboard']) and $this->request->params['named']['dashboard'] == '1') {
            $setDashboard = '1';
        } else {
            $setDashboard = '0';
        }
        $this->set('setDashboard', $setDashboard);
        $this->set('venues', $this->paginate());
        $users = $this->Venue->User->find('list', array(
            'conditions' => array(
                'User.user_type_id' => ConstUserTypes::VenueOwner
            )
        ));
        $this->set('active', $this->Venue->find('count', array(
            'conditions' => array(
                'Venue.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Venue->find('count', array(
            'conditions' => array(
                'Venue.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Venue->find('count', array(
            'conditions' => array(
                'Venue.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->Venue->find('count', array(
            'conditions' => array(
                'Venue.admin_suspend' => 1,
            )
        )));
        $this->set('featured', $this->Venue->find('count', array(
            'conditions' => array(
                'Venue.is_feature' => 1,
            )
        )));
        $this->set('users', $users);
        $moreActions = $this->Venue->moreActions;
        $this->set(compact('moreActions'));
        if (!empty($this->request->params['requested'])) {
            $this->set('requested', $this->request->params['requested']);
        }
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setaction('edit', $id);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Venue->delete($id)) {
            $this->Session->setFlash(__l('Venue deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function list_venue() 
    {
        if (!empty($this->request->params['named']['key'])) {
            $venues = $this->Venue->find('list', array(
                'conditions' => array(
                    'Venue.name LIKE' => $this->request->params['named']['key'] . '%',
                    'Venue.is_active' => '1',
                    'Venue.admin_suspend' => '0',
                ) ,
                'fields' => array(
                    'Venue.name'
                ) ,
                'order' => 'Venue.name asc',
                'recursive' => -1,
            ));
            $this->set('venues', $venues);
        }
        $this->layout = 'ajax';
    }
    public function admin_import() 
    {
        $this->pageTitle = __l('Import Venues');
        if (!empty($this->request->data)) {
            $this->Venue->Attachment->Behaviors->attach('ImageUpload', Configure::read('venuecsv.file'));
            $this->Venue->Attachment->set($this->request->data);
            if ($this->Venue->Attachment->validates()) {
                $messages = $this->Venue->import($this->request->data['Attachment']['filename']['tmp_name']);
                if (!empty($messages['messages'][0])) {
                    $this->Session->setFlash(__l('Venue has been imported successfully') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'venues',
                        'action' => 'index'
                    ));
                } else if (!empty($messages['errors'][0]) or !empty($messages['errors']['empty'])) {
                    if (!empty($messages['errors']['empty'])) {
                        $this->Session->setFlash($messages['errors']['empty'], 'default', null, 'error');
                    } else {
                        $this->Session->setFlash(__l('Venues not imported') , 'default', null, 'error');
                    }
                }
            } else {
                $this->Session->setFlash(__l('Venues not imported') , 'default', null, 'error');
            }
        }
    }
    public function search_keyword() 
    {
        $this->pageTitle = __l('Search');
        $conditions = array();
        if (isset($this->request->params['named']['keyword'])) {
            $conditions['OR']['Venue.name LIKE'] = '%' . $this->request->params['named']['keyword'] . '%';
            $conditions['OR']['Venue.description LIKE'] = '%' . $this->request->params['named']['keyword'] . '%';
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment',
                'VenueType',
                'MusicType',
                'VenueCategory'
            ) ,
            'recursive' => 2,
            'limit' => 15
        );
        $this->set('venues', $this->paginate());
    }
    //send the mail to venue owner when admin activates or deactivates the venue
  public function _sendVenueActionMail($venue_id, $email_template)
    {
      $venue = $this->Venue->find('first', array(
            'conditions' => array(
                'Venue.id' => $venue_id
            ) ,
            'contain' => array(
                'User'
            ) ,
            'recursive' => 1
        ));
        $email = $this->EmailTemplate->selectTemplate($email_template);
        $emailFindReplace = array(
            '##USERNAME##' => $venue['User']['username'],
            '##VENUENAME##' => $venue['Venue']['name'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_LINK##' => Router::url('/', true) ,
            '##FROM_EMAIL##' => ($email['from'] == '##FROM_EMAIL##') ? Configure::read('site.from_email') : $email['from'],
            '##CONTACT_URL##' => Router::url(array(
                'controller' => 'contacts',
                'action' => 'add'
            ) , true) ,
            '##SITE_LOGO##' => Router::url(array(
                'controller' => 'img',
                'action' => 'logo.png',
                'admin' => false
            ) , true)
        );
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('site.reply_to_email') : $email['reply_to'];
        $this->Email->to = $venue['User']['email'];
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
        $this->Email->send(strtr($email['email_content'], $emailFindReplace));
             }
    
}
?>