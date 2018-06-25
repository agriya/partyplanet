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
class EventsController extends AppController
{
    public $name = 'Events';
    public $components = array(
        'Email',
        'Cookie',
        'OauthConsumer'
    );
    public $uses = array(
        'Event',
        'EmailTemplate',
    );
    public $helpers = array(
        'Calendar'
    );
    public function beforeFilter()
    {
        $this->Security->disabledFields = array(
            'Event.makeActive',
            'Event.makeInactive',
            'Event.makeDelete',
            'Event.makeNotFeatured',
            'Event.makeFeatured',
            'Venue.id',
            'Event.venue_id',
            'EventSponsor.EventSponsor',
            'Event.more_action_id',
            'EventComment.event_id',
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_event_on_system_flag')) {
            $this->Event->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($user = null)
    {
        if (!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'search') && !empty($this->request->data)) {
            $_SESSION['search'] = $this->request->data;
        } elseif (empty($this->request->params['named']['type']) or ($this->request->params['named']['type'] != 'search')) {
            unset($_SESSION['search']);
        }
        $this->pageTitle = __l('Events');
        $this->_redirectGET2Named(array(
            'keyword',
            'event_category',
            'start_date',
            'name',
            'end_date',
            'event_category_id',
            'location'
        ));
        $filter = $limit = '';
        $order = array(
            'Event.is_bump_up' => 'desc',
            'Event.id' => 'desc',
            'Event.is_feature' => 'desc',
            'Event.start_date' => 'desc',
        );
        $conditions['Event.is_cancel'] = 0;
        if (!empty($this->_prefixId)) {
            if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
                $conditions['Venue.' . Inflector::underscore(Configure::read('site.prefix_parameter_model')) . '_id'] = $this->_prefixId;
            }
        }
        if (!empty($_SESSION['search']['Event'])) {
            $this->request->data['Event'] = $_SESSION['search']['Event'];
        }
        if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'user') {
            $conditions['Event.admin_suspend'] = 0;
            $conditions['Event.is_active'] = 1;
        }
		if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'booked_events') {
			$guestListUsers = $this->Event->GuestList->GuestListUser->find('all', array(
					'conditions' => array(
						'GuestListUser.user_id' => $this->Auth->user('id')
					),
					'fields' => array(
						'GuestListUser.guest_list_id'
					),
					'contain' => array(
						'GuestList' => array(
							'fields' => array(
								'GuestList.event_id'
							)
						)
					),
					'recursive' => 1
				)
			);
			if(!empty($guestListUsers)) {
				foreach($guestListUsers as $guestListUser) {
					$eventIds[] = $guestListUser['GuestList']['event_id'];
				}
				$conditions['Event.id'] = $eventIds;
			} else {
				$conditions['Event.id'] = 0;
			}
		}
        if (empty($this->request->params['named']['cancel']) && empty($this->request->params['named']['myevents'])) {
            $conditions['Event.is_cancel'] = 0;
            $conditions['Venue.city_id'] = $this->_prefixId;
            $conditions['Event.admin_suspend'] = 0;
            $conditions['Event.is_active'] = 1;
        }
        if (!empty($this->request->params['named']['time_str']) && empty($this->request->params['named']['myevents'])) {
            $conditions['Event.end_date >='] = _formatDate('Y-m-d', $this->request->params['named']['time_str']);
            $conditions['Event.start_date <='] = _formatDate('Y-m-d', $this->request->params['named']['time_str']);
        }
        if (!empty($this->request->params['named']['venue_id'])) {
            $venue = $this->Event->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $this->request->params['named']['venue_id']
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug',
                    'City.name'
                ) ,
                'recursive' => 0
            ));
            $conditions['Event.venue_id'] = $venue['Venue']['id'];
            $this->set('venue', $venue);
        }
        $venu_slug = array();
        if (!empty($this->request->params['named']['event_slug'])) {
            $venu_slug = $this->Event->find('first', array(
                'conditions' => array(
                    'Event.slug = ' => $this->request->params['named']['event_slug']
                ) ,
                'contain' => array(
                    'Venue' => array(
                        'City',
                    ) ,
                ) ,
                'recursive' => 2,
            ));
            $this->set('venu_info', $venu_slug);
            $conditions['Event.venue_id'] = $venu_slug['Venue']['id'];
            $conditions['Event.id != '] = $venu_slug['Event']['id'];
        }
        if (!empty($this->request->params['named'])) {
            if (!empty($this->request->data['Event']['location'])) {
                $this->pageTitle.= ' - ' . $this->request->data['Event']['location'];
                $venues = $this->Event->Venue->find('list', array(
                    'conditions' => array(
                        'or' => array(
                            'City.name LIKE' => '%' . $this->request->data['Event']['location'] . '%',
                            'Country.name LIKE' => '%' . $this->request->data['Event']['location'] . '%',
                            'Venue.address LIKE' => '%' . $this->request->data['Event']['location'] . '%',
                            'Venue.landmark LIKE' => '%' . $this->request->data['Event']['location'] . '%',
                            'Venue.name LIKE' => '%' . $this->request->data['Event']['location'] . '%',
                        )
                    ) ,
                    'contains' => array(
                        'Venue',
                        'City'
                    ) ,
                    'fields' => array(
                        'Venue.id',
                    ) ,
                    'recursive' => 2,
                ));
                $conditions['Event.venue_id'] = $venues;
                $this->set('location', $this->request->data['Event']['location']);
            }
        }
        if (!empty($this->request->data['Event']['event_scene']) or !empty($this->request->params['named']['scene'])) {
            $sceneEvents_conditions = array();
            if (!empty($this->request->data['Event']['event_scene'])) {
                $sceneEvents_conditions['EventsEventScene.event_scene_id'] = $this->request->data['Event']['event_scene'];
            } else {
                $sceneEvents_conditions['EventsEventScene.event_scene_id'] = $this->request->params['named']['scene'];
            }
            $sceneEvents = $this->Event->EventsEventScene->find('list', array(
                'conditions' => $sceneEvents_conditions,
                'fields' => array(
                    'EventsEventScene.event_id',
                ) ,
                'recursive' => 1
            ));
            $ids = array();
            if (!empty($sceneEvents)) {
                foreach($sceneEvents as $event) {
                    $ids[] = $event;
                }
            }
            $conditions['Event.id'] = array_unique($ids);
        }
        if (!empty($this->request->data['Event']['event_music']) or !empty($this->request->params['named']['music'])) {
            $musicType_conditions = array();
            if (!empty($this->request->data['Event']['event_music'])) {
                $musicType_conditions['MusicType.id'] = $this->request->data['Event']['event_music'];
            } else {
                $musicTypeconditions['MusicType.slug'] = $this->request->params['named']['music'];
            }
            //$this->pageTitle.= ' - ' . $this->request->params['named']['music'];
            $musicType = $this->Event->MusicType->find('first', array(
                'conditions' => $musicTypeconditions,
                'fields' => array(
                    'MusicType.name',
                    'MusicType.slug'
                ) ,
                'contain' => array(
                    'Event' => array(
                        'fields' => array(
                            'Event.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($musicType)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->pageTitle.= sprintf(__l(' - Music Type - %s') , $musicType['MusicType']['name']);
            $ids = array();
            if (!empty($musicType)) {
                foreach($musicType['Event'] as $event) {
                    $ids[] = $event['id'];
                }
            }
            $conditions['Event.id'] = $ids;
            //$this->set('category', $this->request->params['named']['music']);

        }
        if (!empty($this->request->data['Event']['start_date']['day']) and $this->request->data['Event']['start_date'] != '--') {
            if (is_array($this->request->data['Event']['start_date'])) {
                $this->request->data['Event']['start_date'] = $this->request->data['Event']['start_date']['year'] . '-' . $this->request->data['Event']['start_date']['month'] . '-' . $this->request->data['Event']['start_date']['day'];
                $this->request->data['Event']['end_date'] = $this->request->data['Event']['end_date']['year'] . '-' . $this->request->data['Event']['end_date']['month'] . '-' . $this->request->data['Event']['end_date']['day'];
            }
            $datearray = explode('-', $this->request->data['Event']['start_date']);
            if (count($datearray) > 1) {
                $conditions['Event.start_date <='] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['start_date'] . " " . date('H:i:s')));
                $conditions['Event.end_date >='] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['end_date'] . " " . date('H:i:s')));
            } else {
                $conditions['Event.start_date ='] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['start_date'] . " " . date('H:i:s')), true);
                $conditions['Event.end_date ='] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['end_date'] . " " . date('H:i:s')), true);
            }
            //$this->set('date', $this->request->params['named']['date']);

        }
        $date = date('Y-m-d');
        $datelist = explode('-', $date);
        $timeStamp = strtotime($date);
        if (!empty($this->request->params['named']['filter'])) {
            switch ($this->request->params['named']['filter']) {
                case 'today':
                    $conditions['Event.start_date <'] = _formatDate('Y-m-d', strtotime(date('Y-m-d H:i:s')), true);
                    $conditions['Event.end_date >'] = _formatDate('Y-m-d', strtotime(date('Y-m-d H:i:s')), true);
                    break;

                case 'tomorrow':
                    $conditions['Event.start_date ='] = _formatDate('Y-m-d', time() +24*60*60*1, true);
                    break;

                case 'nextsevenday':
                    $conditions['Event.start_date >='] = _formatDate('Y-m-d', date('Y-m-d H:i:s'), true);
                    $conditions['Event.start_date <='] = _formatDate('Y-m-d', time() +24*60*60*7, true);
                    break;

                case 'nextweek':
                    $d_var = getdate(mktime(0, 0, 0, $datelist[1], $datelist[2], $datelist[0]));
                    $cnt = $this->Event->_checkdaynamecnt($d_var['weekday']);
                    $conditions['Event.start_date >='] = _formatDate('Y-m-d', time() +24*60*60*($cnt+2), true);
                    $conditions['Event.start_date <='] = _formatDate('Y-m-d', time() +24*60*60*7, true);
                    break;

                case 'thisweekend':
                    $d_var = getdate(mktime(0, 0, 0, $datelist[1], $datelist[2], $datelist[0]));
                    $cnt = $this->Event->_checkdaynamecnt($d_var['weekday']);
                    $conditions['Event.start_date <='] = _formatDate('Y-m-d', time() +24*60*60*($cnt-1), true);
                    $conditions['Event.start_date >='] = _formatDate('Y-m-d', time() +24*60*60*$cnt, true);
                    break;

                case 'nextweekend':
                    $d_var = getdate(mktime(0, 0, 0, $datelist[1], $datelist[2], $datelist[0]));
                    $cnt = $this->Event->_checkdaynamecnt($d_var['weekday']);
                    $conditions['Event.start_date <='] = _formatDate('Y-m-d', time() +24*60*60*($cnt+5), true);
                    $conditions['Event.start_date >='] = _formatDate('Y-m-d', time() +24*60*60*($cnt+6), true);
                    break;
            }
            $this->set('filter', $this->request->params['named']['filter']);
        }
        if (!empty($this->request->params['named']['type'])) {
            switch ($this->request->params['named']['type']) {
                case 'home-featured':
                    $conditions['Event.is_feature'] = '1';
                    $limit = 3;
                    break;

                case 'featured':
                    $conditions['Event.is_feature'] = '1';
                    //  $conditions['Event.start_date <='] = date('Y-m-d', strtotime(date('Y-m-d', time()) . " +6 days"));
                    $this->set('setTitle', 'Featured events');
                    $filter = 'featured';
                    $this->set('filter', $filter);
                    break;

                case 'popular':
                    $conditions['Event.event_user_count !='] = '0';
                    $limit = '5';
                    break;

                case 'sponsor':
                    $limit = '5';
                    break;

                case 'upcoming':
                    $conditions['Event.end_date >='] = date('Y-m-d H:i:s');
                    $this->set('setTitle', 'Upcoming events');
                    $filter = 'upcoming';
                    $this->set('filter', $filter);
                    break;

                case 'myevents':
                    $conditions['Event.user_id'] = $this->Auth->user('id');
                    $this->set('setTitle', 'My events');
                    $filter = 'myevents';
                    $this->set('filter', $filter);
                    break;

                case 'past':
                    $conditions['Event.end_date < '] = date('Y-m-d H:i:s');
                    $this->set('setTitle', 'Past events');
                    $filter = 'past';
                    $this->set('filter', $filter);
                    break;

                case 'cancel':
                    $conditions['Event.is_cancel'] = '1';
                    $this->set('setTitle', 'Canceled events');
                    $filter = 'cancel';
                    $this->set('filter', $filter);
                    break;

                case 'similar':
                    if (!empty($this->request->params['named']['event'])):
                        $conditions['Event.id != '] = $this->request->params['named']['event'];
                    endif;
                    $filter = 'similar';
                    $this->set('filter', $filter);
                    $this->set('setTitle', 'Similar events');
                    break;

                case 'recent':
                    $this->set('setTitle', 'Guestlist events');
                    $filter = 'recent';
                    $this->set('filter', $filter);
                    break;

                case 'guest':
                    $conditions['Event.is_guest_list'] = '1';
                    if (isset($this->request->params['named']['view']) && $this->request->params['named']['view'] == 'upcoming') {
                        $conditions['Event.start_date >='] = date('Y-m-d');
                    }
                    break;

                case 'user':
                    $conditions['Event.user_id'] = $this->Auth->user('id');
                    $limit = 10;
                    break;

                case 'venue':
                    $limit = 3;
                    break;

                case 'cancel':
                    $d_var = getdate(mktime(0, 0, 0, $datelist[1], $datelist[2], $datelist[0]));
                    $cnt = $this->Event->_checkdaynamecnt($d_var['weekday']);
                    $conditions['Event.start_date <='] = _formatDate('Y-m-d', time() +24*60*60*($cnt-1), true);
                    $conditions['Event.start_date >='] = _formatDate('Y-m-d', time() +24*60*60*$cnt, true);
                    break;

                case 'home':
                    $limit = 3;
                    break;
            }
            $this->set('type', $this->request->params['named']['type']);
        }
        if (!empty($this->request->params['named']['sponsor']) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'sponsor')) {
            if (!empty($this->request->params['named']['sponsor'])) {
                $this->pageTitle.= ' - ' . __l('Sponsored by ') . $this->request->params['named']['sponsor'];
                $spon_conditions = array(
                    'EventSponsor.slug LIKE' => '%' . $this->request->params['named']['sponsor'] . '%'
                );
                $this->set('sponsor', $this->request->params['named']['sponsor']);
            } else {
                $spon_conditions = array();
            }
            $event_ids = $this->Event->EventsEventSponsor->find('list', array(
                'conditions' => $spon_conditions,
                'fields' => array(
                    'EventsEventSponsor.event_id',
                ) ,
                'recursive' => 0,
            ));
            $conditions['Event.id'] = array_unique($event_ids);
        }
        if (!empty($this->request->params['named']['joined'])) {
            $this->pageTitle.= ' - joined by ' . $this->request->params['named']['joined'];
            $eventIds = $this->Event->EventUser->find('list', array(
                'conditions' => array(
                    'User.username LIKE' => '%' . $this->request->params['named']['joined'] . '%'
                ) ,
                'fields' => array(
                    'EventUser.event_id',
                ) ,
                'recursive' => 0,
            ));
            $conditions['Event.id'] = $eventIds;
            $this->set('joined', $this->request->params['named']['joined']);
        }
        if (!empty($this->request->params['named']['tag'])) {
            $this->pageTitle.= ' - ' . $this->request->params['named']['tag'];
            $eventTag = $this->Event->EventTag->find('first', array(
                'conditions' => array(
                    'EventTag.name LIKE' => '%' . $this->request->params['named']['tag'] . '%'
                ) ,
                'fields' => array(
                    'EventTag.id'
                ) ,
                'recursive' => -1
            ));
            if (!empty($eventTag)) {
                $event_ids = $this->Event->EventsEventTag->find('list', array(
                    'conditions' => array(
                        'EventsEventTag.event_tag_id' => $eventTag['EventTag']['id']
                    ) ,
                    'fields' => array(
                        'EventsEventTag.event_id',
                    ) ,
                    'recursive' => -1,
                ));
                $conditions['Event.id'] = array_unique($event_ids);
            }
            $this->set('tag', $this->request->params['named']['tag']);
        }
        if (!empty($this->request->params['named']['user'])) {
            $this->pageTitle.= ' -' . __l(' by') . $this->request->params['named']['user'];
            $event_users = $this->Event->EventUser->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user'],
                ) ,
                'fields' => array(
                    'EventUser.event_id',
                ) ,
                'recursive' => 0,
            ));
            $conditions['Event.id'] = $event_users;
            $this->set('user', $this->request->params['named']['user']);
        }
        $user_id = $this->Auth->user('id');
        if (!empty($this->request->params['named']['near'])) {
            $user_lat_log = $this->Event->User->UserProfile->find('first', array(
                'conditions' => array(
                    'UserProfile.user_id' => $this->Auth->user('id') ,
                ) ,
                'fields' => array(
                    'UserProfile.latitude',
                    'UserProfile.longitude',
                ) ,
                'recursive' => -1,
            ));
            $my_latitude = $user_lat_log['UserProfile']['latitude'];
            $my_longitude = $user_lat_log['UserProfile']['longitude'];
            $venues = $this->Event->Venue->find('all', array(
                'fields' => array(
                    'Venue.id',
                    '( 6371 * acos( cos( radians(' . $my_latitude . ') ) * cos( radians( Venue.latitude ) ) * cos( radians( Venue.longitude ) - radians(' . $my_longitude . ') ) + sin( radians(' . $my_latitude . ') ) * sin( radians( Venue.latitude ) ) ) ) AS distance'
                ) ,
                'group' => array(
                    'Venue.id HAVING distance < ' . Configure::read('search.default_search_circle')
                ) ,
                'order' => 'distance',
                'recursive' => -1,
            ));
            $venue_ids = array();
            foreach($venues as $venue) {
                $venue_ids[] = $venue['Venue']['id'];
            }
            $conditions['Event.venue_id'] = $venue_ids;
        }
        if (!empty($this->request->data['Event']['event_music'])) {
            $musicEvents = $this->Event->EventsMusicType->find('list', array(
                'conditions' => array(
                    'EventsMusicType.music_type_id' => $this->request->data['Event']['event_music']
                ) ,
                'fields' => array(
                    'EventsMusicType.event_id',
                ) ,
            ));
            if (!empty($conditions['Event.id'])) {
                $conditions['Event.id'] = array_merge($conditions['Event.id'], $musicEvents);
            } else {
                $conditions['Event.id'] = $musicEvents;
            }
        }
        if (!empty($this->request->data['Event']['event_category'])) {
            $conditions['Event.event_category_id'] = $this->request->data['Event']['event_category'];
        }
        if (!empty($this->request->params['named']['category'])) {
            $conditions['EventCategory.slug'] = $this->request->params['named']['category'];
        }
        if (!empty($this->request->data['Event']['event_scene'])) {
            $sceneEvents = $this->Event->EventsEventScene->find('list', array(
                'conditions' => array(
                    'EventsEventScene.event_scene_id' => $this->request->data['Event']['event_scene']
                ) ,
                'fields' => array(
                    'EventsEventScene.event_id',
                ) ,
            ));
            if (!empty($conditions['Event.id'])) {
                $conditions['Event.id'] = array_merge($conditions['Event.id'], $sceneEvents);
            } else {
                $conditions['Event.id'] = $sceneEvents;
            }
        }
        $featureEventConditions = array(
            'Event.is_feature' => 1
        );
        $featureEventConditions = array_merge($featureEventConditions, $conditions);
        $featureEventCount = $this->Event->find('count', array(
            'conditions' => $featureEventConditions,
        ));
        $nonFeatureEventConditions = array(
            'Event.is_feature !=' => 1
        );
        $nonFeatureEventConditions = array_merge($nonFeatureEventConditions, $conditions);
        $nonFeatureEventCount = $this->Event->find('count', array(
            'conditions' => $nonFeatureEventConditions,
        ));
        $this->set('featureEventCount', $featureEventCount);
        $this->set('nonFeatureEventCount', $nonFeatureEventCount);
        if (!empty($this->request->params['named']['event_view'])) {
            if ($this->request->params['named']['event_view'] == 'feature') {
                $conditions['OR'][]['Event.is_feature'] = 1;
                $conditions['OR'][]['Event.is_featured'] = 1;
            } else if ($this->request->params['named']['event_view'] == 'non-feature') {
                $conditions['Event.is_feature !='] = 1;
                $conditions['Event.is_featured !='] = 1;
            }
        }
        $limit = !empty($limit) ? $limit : '20';
        if (!empty($this->request->params['named']['time_str'])) {
            $this->request->params['named']['date'] = date('Y-m-d', $this->request->params['named']['time_str']);
        }
        if (!empty($this->_prefixId) and (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] != 'myevents')) {
            $conditions['Venue.city_id'] = $this->_prefixId;
        }
        if (!empty($this->request->data['Event']['city'])) {
            array_push($this->request->data['Event']['city'], $this->_prefixId);
            $conditions['Venue.city_id'] = $this->request->data['Event']['city'];
        }
        if (empty($conditions['Event.end_date <=']) and empty($conditions['Event.end_date >=']) and (empty($this->request->params['named']['type']) or ($this->request->params['named']['type'] != 'myevents' && $this->request->params['named']['type'] != 'booked_events'))) {
            $conditions['Event.end_date >='] = date('Y-m-d');
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'EventTag' => array(
                    'fields' => array(
                        'EventTag.id',
                        'EventTag.name',
                        'EventTag.slug',
                    )
                ) ,
                'EventCategory' => array(
                    'fields' => array(
                        'EventCategory.id',
                        'EventCategory.name',
                        'EventCategory.description',
                        'EventCategory.slug',
                    )
                ) ,
                'MusicType' => array(
                    'fields' => array(
                        'MusicType.id',
                        'MusicType.name',
                        'MusicType.slug'
                    )
                ) ,
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                        'User.user_type_id',
                    )
                ) ,
                'EventType' => array(
                    'fields' => array(
                        'EventType.name'
                    )
                ) ,
                'EventUser' => array(
                    'conditions' => array(
                        'EventUser.user_id' => $this->Auth->user('id') ,
                    ) ,
                ) ,
				'GuestList' => array(
					'GuestListUser' => array(
						'conditions' => array(
							'GuestListUser.user_id' => $this->Auth->user('id')
						)		
					)
		
				),
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug',
                        'Venue.is_active',
                        'Venue.city_id',
                        'Venue.admin_suspend',
                    ) ,
                    'City',
                ) ,
                'Attachment',
            ) ,
            'order' => $order,
            'limit' => $limit,
            'recursive' => 1,
        );
        $events = $this->paginate();
        if (!empty($this->request->params['named']['date']) and $this->request->params['named']['date'] != 'all') {
            $this->pageTitle = count($events) . ' ' . $this->pageTitle . ' found on ' . date('F d', $this->request->params['named']['time_str']);
            $conditions = array(
                'Event.start_date <=' => date('Y-m-d', strtotime($this->request->params['named']['date'])) ,
                'Event.end_date >=' => date('Y-m-d', strtotime($this->request->params['named']['date'])) ,
            );
        }
        $upcoming_count = $this->Event->find('count', array(
            'conditions' => array(
                'Event.end_date >=' => date('Y-m-d H:i:s') ,
                'Event.is_active' => 1,
                'Event.is_cancel' => 0
            ) ,
            'recursive' => 0,
        ));
        $past_count = $this->Event->find('count', array(
            'conditions' => array(
                'Event.end_date < ' => date('Y-m-d H:i:s') ,
                'Event.is_active' => 1,
                'Event.is_cancel' => 0
            ) ,
            'recursive' => 0,
        ));
        $my_count = $this->Event->find('count', array(
            'conditions' => array(
                'Event.user_id' => $this->Auth->user('id') ,
                'Event.is_active' => 1,
                'Event.is_cancel' => 0
            ) ,
            'recursive' => 0,
        ));
        $eventCategories = $this->Event->EventCategory->find('list');
        $this->set('eventCategories', $eventCategories);
        $this->set('events', $events);
        $this->set('upcoming_count', $upcoming_count);
        $this->set('past_count', $past_count);
        $this->set('my_count', $my_count);
        if ($this->Auth->user()) {
            $eventUsers = $this->Event->EventUser->find('all', array(
                'conditions' => array(
                    'EventUser.user_id' => $this->Auth->user('id') ,
                ) ,
                'fields' => array(
                    'EventUser.event_id',
                    'EventUser.id'
                ) ,
                'recursive' => -1,
            ));
            $event_users = array();
            foreach($eventUsers as $eventUser) {
                $event_users[$eventUser['EventUser']['event_id']] = $eventUser['EventUser']['id'];
            }
            $this->set('event_users', $event_users);
        }
        if (empty($this->request->params['named']['event_view'])) {
            if ((!empty($this->request->params['named']['list']) && $this->request->params['named']['list'] == 'home')) {
                $this->autoRender = false;
                $this->render('my_event');
            } else if (!empty($this->request->params['named']['view']) && $this->request->params['named']['view'] != 'upcoming') {
                $this->autoRender = false;
                $this->render('home_index_compact');
            } else if (!empty($this->request->params['named']['type'])) {
                if ($this->request->params['named']['type'] == 'home' or $this->request->params['named']['type'] == 'home-featured') {
                    $this->autoRender = false;
                    $this->render('home_events');
                } else if ($this->request->params['named']['type'] == 'sponsor' || $this->request->params['named']['type'] == 'featured') {
                    $this->autoRender = false;
                    $this->render('sponsor_events');
                } elseif ($this->request->params['named']['type'] == 'venue' || $this->request->params['named']['type'] == 'popular' || $this->request->params['named']['type'] == 'samevenue') {
                    $this->autoRender = false;
                    $this->render('event_list');
                }  else if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'booked_events') {
                    $this->autoRender = false;
					$this->render('my_booked_event');
				}
            } else if (!empty($this->request->params['requested']) && empty($this->request->params['named']['photo'])) {
                $this->autoRender = false;
                $this->render('index_compact');
                $this->set('requested', $this->request->params['requested']);
            } else if (!empty($this->request->params['named']['photo'])) {
                $this->autoRender = false;
                $this->render('event_photo');
            }
        }
    }
    public function search_keyword()
    {
        $this->pageTitle = __l('Search');
        $this->_redirectGET2Named(array(
            'keyword',
            'name',
        ));
        $conditions = array();
        if (isset($this->request->params['named']['name'])) {
            $this->request->data['Event']['name'] = $this->request->params['named']['name'];
            $conditions['OR']['Event.title LIKE'] = '%' . $this->request->params['named']['name'] . '%';
            $conditions['OR']['Event.description LIKE'] = '%' . $this->request->params['named']['name'] . '%';
            $conditions['OR']['EventCategory.name LIKE'] = '%' . $this->request->params['named']['name'] . '%';
            $conditions['OR']['EventCategory.description LIKE'] = '%' . $this->request->params['named']['name'] . '%';
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment',
                'EventCategory' => array(
                    'fields' => array(
                        'EventCategory.id',
                        'EventCategory.name',
                        'EventCategory.description',
                        'EventCategory.slug',
                    )
                )
            ) ,
            'recursive' => 1,
            'limit' => 15
        );
        $this->set('events', $this->paginate());
        $this->set('keyword', $this->request->params['named']['name']);
    }
    public function view($slug = null)
    {
        $this->pageTitle = __l('Event');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions['Event.slug'] = $slug;
        $event = $this->Event->find('first', array(
            'conditions' => $conditions,
            'contain' => array(
                'EventTag',
                'MusicType',
                'EventScene',
                'EventUser',
                'EventCategory' => array(
                    'fields' => array(
                        'EventCategory.id',
                        'EventCategory.name',
                        'EventCategory.description',
                        'EventCategory.slug',
                    )
                ) ,
                'EventSponsor' => array(
                    'Attachment',
                ) ,
                'AgeRequirment',
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                        'User.user_type_id',
                    )
                ) ,
                'RepeatType' => array(
                    'fields' => array(
                        'RepeatType.name'
                    )
                ) ,
                'EventType' => array(
                    'fields' => array(
                        'EventType.name'
                    )
                ) ,
                'Day' => array(
                    'fields' => array(
                        'Day.name'
                    )
                ) ,
                'MonthlyRepeatType' => array(
                    'fields' => array(
                        'MonthlyRepeatType.name'
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.description',
                        'Venue.venue_type_id',
                        'Venue.address',
                        'Venue.city_id',
                        'Venue.country_id',
                        'Venue.longitude',
                        'Venue.latitude',
                        'Venue.phone',
                        'Venue.landmark',
                        'Venue.city_id',
                        'Venue.country_id',
                        'Venue.slug',
                        'Venue.event_count',
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
                'GuestList' => array(
                    'GuestListUser' => array(
                        'conditions' => array(
                            'GuestListUser.user_id' => $this->Auth->user('id') ,
                        ) ,
                    ) ,
                ) ,
                'Attachment',
                'Video' => array(
                    'fields' => array(
                        'Video.id',
                        'Video.title',
                        'Video._temp_slug',
                        'Video.slug',
                        'Video.default_thumbnail_id'
                    ) ,
                ) ,
            ) ,
            'recursive' => 2
        ));
        if (($event['Event']['is_active'] == 0 || $event['Event']['admin_suspend'] == 1 || $event['Event']['is_cancel'] == 1) && ($this->Auth->user('id') != $event['Event']['user_id']) && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $countMe1 = $this->Event->EventUser->find('first', array(
            'conditions' => array(
                'EventUser.user_id' => $this->Auth->user('id') ,
                'EventUser.event_id' => $event['Event']['id']
            ) ,
            'fields' => array(
                'EventUser.id'
            ) ,
            'recursive' => -1
        ));
        $countMe = (!empty($countMe1)) ? count($countMe1) : 0;
        if (empty($event)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $is_past_event = true;
        if (date('Y-m-d G:i:s') < $event['Event']['start_date']) $is_past_event = false;
        $this->pageTitle.= ' - ' . $event['Event']['title'];
        $this->set('event', $event);
        $this->set('is_past_event', $is_past_event);
        $joined = $this->Event->EventUser->find('first', array(
            'conditions' => array(
                'EventUser.event_id' => $event['Event']['id'],
                'EventUser.user_id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'EventUser.id'
            ) ,
            'recursive' => -1,
        ));
        $this->set('is_joined', $joined['EventUser']['id']);
        $meta_keywords = !empty($event['EventCategory']['name']) ? ', ' . $event['EventCategory']['name'] : '';
        if (!empty($event['EventTag'])) {
            $eventtag = array();
            foreach($event['EventTag'] as $eventTag) {
                $eventtag[] = $eventTag['name'];
            }
            $meta_keywords.= ', ' . implode(', ', $eventtag);
        }
        $this->request->data['EventComment']['event_id'] = $event['Event']['id'];
        $this->request->data['EventComment']['event_slug'] = $event['Event']['slug'];
        Configure::write('meta.keywords', Configure::read('meta.keywords') . $meta_keywords);
        Configure::write('meta.description', 'Event (' . $event['Event']['title'] . ') held at ' . $event['Venue']['name']);
        if (!empty($event['Attachment'])) {
            $image_options = array(
                'dimension' => 'big_thumb',
                'class' => '',
                'alt' => $event['Event']['title'],
                'title' => $event['Event']['title'],
                'type' => 'png',
                'full_url' => true,
            );
            $event_image = getImageUrl('Event', $event['Attachment'], $image_options);
            Configure::write('meta.image', $event_image);
        }
        if (!empty($event['Event']['title'])) {
            Configure::write('meta.name', $event['Event']['title']);
        }
        $event_timing = $this->Event->getEventTimingInfo($event);
        $this->set('repeat', $event_timing['description']);
        $this->set('countMe', $countMe);
        if (!empty($countMe1)) {
            $this->set('eventUsers', $countMe1['EventUser']['id']);
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue') {
            $this->autoRender = false;
            $this->render('venue_info');
        }
    }
    public function user_events($month = null, $year = null, $user_id = null, $type = null, $sdate = null)
    {
        $tmp_month = $month;
        if ($month <= 9) {
            $tmp_month = '0' . $month;
        }
        $conditions['Event.start_date >='] = $year . '-' . $tmp_month . '-01 00:00:00';
        $conditions['Event.start_date <='] = $year . '-' . $tmp_month . '-31 23:59:59';
        $conditions['Event.is_cancel'] = 0;
        $conditions['Event.is_active'] = 1;
        $conditions['Event.admin_suspend'] = 0;
        if (!empty($this->_prefixId)) {
            $conditions['Venue.city_id'] = $this->_prefixId;
        }
        if (!empty($user_id)) {
            $conditions['or']['Event.user_id'] = $user_id;
            $event_users = $this->Event->EventUser->find('list', array(
                'conditions' => array(
                    'EventUser.user_id' => $user_id
                ) ,
                'fields' => array(
                    'EventUser.event_id'
                )
            ));
            $conditions['or']['Event.id'] = array_unique($event_users);
            $events = $this->Event->find('all', array(
                'conditions' => $conditions,
                'contains' => array(
                    'User'
                ) ,
                'fields' => array(
                    'Event.title',
                    'Event.description',
                    'Event.slug',
                    'DATE_FORMAT(Event.start_date,\'%e\') as date',
                ) ,
                'recursive' => 2,
            ));
            $this->set('user_id', $user_id);
        } else {
            $events = $this->Event->find('all', array(
                'conditions' => $conditions,
                'contains' => array(
                    'Event',
                ) ,
                'fields' => array(
                    'Event.title',
                    'Event.description',
                    'Event.slug',
                    'DATE_FORMAT(Event.start_date,\'%e\') as date',
                ) ,
                'group' => 'Event.start_date',
                'recursive' => 0,
            ));
        }
        $this->set('events', $events);
        $this->set('month', $month);
        $this->set('year', $year);
        $this->set('type', $type);
    }
    public function add($venue_slug = null)
    {
        $event_id = 0;
        $is_paypal_required = 0;
        $this->pageTitle = __l('Add Event');
        $this->Event->Attachment->Behaviors->attach('ImageUpload', Configure::read('event.file'));
		$userprofile = $this->Event->User->UserProfile->find('first', array(
			'conditions' => array(
				'UserProfile.user_id' => $this->Auth->user('id')
			) ,
			'fields' => array(
				'UserProfile.id',
				'UserProfile.paypal_account',
				'UserProfile.paypal_first_name',
				'UserProfile.paypal_last_name',
			) ,
			'recursive' => -1,
		));
		if (empty($userprofile['UserProfile']['paypal_account'])) {
          $is_paypal_required = 1;
		}
        if (!empty($this->request->data)) {
            if (!$this->Auth->user('is_email_confirmed')) {
                $this->Session->setFlash(__l('Oops, Still you are not confirm the email') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'events',
                    'action' => 'add'
                ));
            }
            $this->request->data['Event']['venue_name'] = $this->request->data['Venue']['name'];
            if (!empty($this->request->data['Venue']['id']) || !empty($this->request->data['Event']['venue_id'])) {
                if (empty($this->request->params['admin'])) {
                    $this->request->data['Event']['user_id'] = $this->Auth->user('id');
                }
                $this->request->data['Event']['ip_id'] = $this->Event->toSaveIp();
                if ($this->request->data['Event']['is_all_day']) {
                    $this->request->data['Event']['start_time']['hour'] = '00';
                    $this->request->data['Event']['start_time']['min'] = '00';
                    $this->request->data['Event']['start_time']['meridian'] = '00';
                    $this->request->data['Event']['end_time']['hour'] = '00';
                    $this->request->data['Event']['end_time']['min'] = '00';
                    $this->request->data['Event']['end_time']['meridian'] = '00';
                }
                $this->request->data['Event']['event_type_id'] = 1;
                switch ($this->request->data['Event']['event_type_id']) {
                    case 1:
                        $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                        $this->request->data['Event']['repeat_value'] = 0;
                        $this->request->data['Event']['repeat_type_id'] = 0;
                        break;

                    case 2:
                        $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                        $this->request->data['Event']['repeat_type_id'] = 1;
                        break;

                    case 3:
                    case 4:
                    case 5:
                        $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                        $this->request->data['Event']['repeat_value'] = 0;
                        $this->request->data['Event']['repeat_type_id'] = 0;
                        $this->request->data['Event']['repeat_on_week_bits'] = $this->Event->getWeekValue($this->request->data['Event']['event_type_id'], $this->request->data['Event']['days']);
                        break;

                    case 6:
                        $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                        $this->request->data['Event']['repeat_type_id'] = 2;
                        $this->request->data['Event']['repeat_on_week_bits'] = $this->Event->getWeekValue($this->request->data['Event']['event_type_id'], $this->request->data['Event']['days']);
                        break;

                    case 7:
                        $this->request->data['Event']['repeat_type_id'] = 3;
                        if ($this->request->data['Event']['monthly_repeat_type_id'] == 1) {
                            $this->request->data['Event']['repeat_on_month'] = date('d');
                        } else {
                            $days = $this->Event->Day->find('list');
                            $days = array_flip($days);
                            $this->request->data['Event']['repeat_on_month_day_id'] = $days[date('D') ];
                            $this->request->data['Event']['repeat_on_month'] = floor(date('d') /7) +1;
                        }
                        break;

                    case 8:
                        $this->request->data['Event']['repeat_type_id'] = 4;
                        $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                        break;
                }
                //$this->request->data['Event']['end_date'] = $this->request->data['Event']['repeat_end_date'];
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                }
                $is_guestlist = true;
                if ($this->request->data['Event']['is_guest_list']) {
                    $this->Event->GuestList->set($this->request->data);
                    if ($this->Event->GuestList->validates()) {
                        $is_guestlist = true;
                    } else {
                        $is_guestlist = false;
                    }
                }
                $this->request->data['Event']['venue_id'] = $this->request->data['Event']['venue_id'] ? $this->request->data['Event']['venue_id'] : $this->request->data['Venue']['id'];
                if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                    $this->request->data['Event']['is_active'] = (Configure::read('site.is_admin_activate_after_event_add')) ? 0 : 1;
                } else {
                    $this->request->data['Event']['is_active'] = 1;
                }
                $this->Event->Attachment->set($this->request->data);
                $this->Event->set($this->request->data);
                $this->request->data['EventTag']['EventTag'] = $this->Event->EventTag->_saveTags($this->request->data['Event']['Tags']);
                // paypal account verification
                if (!empty($this->request->data['Event']['ticket_fee']) && $this->request->data['Event']['ticket_fee'] > 0) {                    
                    if (empty($userprofile['UserProfile']['paypal_account']) && !isset($this->request->data['UserProfile']['paypal_account'])) {
                        $this->Session->setFlash(__l('Enter PayPal verification email and name associated with your PayPal') , 'default', null, 'error');
                        $is_paypal_required = 1;
                        $this->Event->User->UserProfile->validationErrors['paypal_account'] = __l('Please enter your verified PayPal account');
                        $this->Event->User->UserProfile->validationErrors['paypal_first_name'] = __l('Enter PayPal First Name. As given in PayPal.');
                        $this->Event->User->UserProfile->validationErrors['paypal_last_name'] = __l('Enter PayPal Last Name. As given in PayPal.');
                    } else if (empty($userprofile['UserProfile']['paypal_account']) && !empty($this->request->data['UserProfile']['paypal_account'])) {
                        App::import('Model', 'Payment');
                        $this->Payment = new Payment();
                        $rsPayStatus = $this->Payment->getVerifiedStatus($this->request->data['UserProfile']);
                        if (strtoupper($rsPayStatus['responseEnvelope.ack']) != 'SUCCESS' || strtoupper($rsPayStatus['accountStatus']) != 'VERIFIED') {
                            $is_paypal_required = 1;
                            $this->Session->setFlash(__l('Enter PayPal verification email and name associated with your PayPal') , 'default', null, 'error');
                            $this->Event->User->UserProfile->validationErrors['paypal_account'] = __l('Enter correct PayPal email and name associated with your PayPal.');
                        } else {
							$is_paypal_required = 0;
						}
                    }
                } else {
					$is_paypal_required = 0;
				}
                if ($this->Event->validates() &$this->Event->Attachment->validates() && $is_guestlist && !$is_paypal_required) {
                    $venue = $this->Event->Venue->find('first', array(
                        'conditions' => array(
                            'Venue.id = ' => $this->request->data['Event']['venue_id'],
                            'Venue.is_active' => 1,
                        ) ,
                        'fields' => array(
                            'Venue.id',
                            'Venue.name'
                        ) ,
                        'recursive' => -1,
                    ));
                    if (!empty($venue) && $venue['Venue']['name'] == $this->request->data['Event']['venue_name']) {
                        $this->Event->create();
                        $this->Event->save($this->request->data, false);
                        $event_id = $this->Event->getLastInsertId();
                        if (($this->request->data['Event']['is_guest_list'])) {
                            $this->request->data['GuestList']['event_id'] = $event_id;
                            $this->Event->GuestList->create();
                            $this->Event->GuestList->save($this->request->data);
                        }
                        if (!empty($this->request->data['Attachment']['filename']['name'])) {
                            $this->Event->Attachment->create();
                            $this->request->data['Attachment']['class'] = $this->modelClass;
                            $this->request->data['Attachment']['description'] = 'EventImage';
                            $this->request->data['Attachment']['foreign_id'] = $event_id;
                            $this->Event->Attachment->save($this->request->data['Attachment']);
                        }
                        if (!empty($this->request->data['Event']['event_scene'])) {
                            $eventScene['event_id'] = $event_id;
                            for ($i = 0; $i < count($this->request->data['Event']['event_scene']); $i++) {
                                $this->Event->EventsEventScene->create();
                                $eventScene['event_scene_id'] = $this->request->data['Event']['event_scene'][$i];
                                $this->Event->EventsEventScene->save($eventScene);
                            }
                        }
                        if (!empty($this->request->data['Event']['event_music'])) {
                            $eventMusics['event_id'] = $event_id;
                            for ($i = 0; $i < count($this->request->data['Event']['event_music']); $i++) {
                                $this->Event->EventsMusicType->create();
                                $eventMusics['music_type_id'] = $this->request->data['Event']['event_music'][$i];
                                $this->Event->EventsMusicType->save($eventMusics);
                            }
                        }
                        if (!empty($this->request->data['UserProfile'])) {
                            $this->request->data['UserProfile']['id'] = $userprofile['UserProfile']['id'];
                            $this->Event->User->UserProfile->save($this->request->data['UserProfile']);
                        }
                        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                            if (Configure::read('site.is_admin_activate_after_event_add')) {
                                $this->Session->setFlash(__l('Event has been added. After admin approval it will list out in site.') , 'default', null, 'success');
                            } else {
                                $this->Session->setFlash(__l('Event has been added') , 'default', null, 'success');
                            }
                        } else {
                            $this->Session->setFlash(__l('Event has been added') , 'default', null, 'success');
                        }
                        $event = $this->Event->find('first', array(
                            'conditions' => array(
                                'Event.id = ' => $this->Event->getLastInsertId()
                            )
                        ));
                        $user = $this->Event->User->find('first', array(
                            'conditions' => array(
                                'User.id' => $event['Event']['user_id']
                            ) ,
                            'recursive' => -1
                        ));
                        $url = Router::url(array(
                            'controller' => 'events',
                            'action' => 'view',
                            'admin' => false,
                            $event['Event']['slug'],
                        ) , true);
                        // event willn't be posted if it is autoflagged and suspend
                        if (!$event['Event']['admin_suspend'] && $event['Event']['is_active']) {
                            $image_options = array(
                                'dimension' => 'normal_thumb',
                                'class' => '',
                                'alt' => $event['Event']['title'],
                                'title' => $event['Event']['title'],
                                'type' => 'jpg'
                            );
                            $post_data = array();
                            $post_data['message'] = $user['User']['username'] . ' ' . __l('addd a new event "') . '' . $event['Event']['title'] . __l('" in ') . Configure::read('site.name');
                            $post_data['image_url'] = Router::url('/', true) . getImageUrl('Event', $event['Attachment'], $image_options);
                            $post_data['link'] = $url;
                            $post_data['description'] = $event['Event']['description'];
                            // Post on user facebook
                            if (Configure::read('social_networking.post_event_on_user_facebook')) {
                                if ($user['User']['fb_user_id'] > 0) {
                                    $post_data['fb_user_id'] = $user['User']['fb_user_id'];
                                    $post_data['fb_access_token'] = $user['User']['fb_access_token'];
                                    $getFBReturn = $this->postOnFacebook($post_data, 0);
                                    unset($post_data['fb_user_id']);
                                    unset($post_data['fb_access_token']);
                                }
                            }
                            if (Configure::read('event.post_on_facebook')) { // post on site facebook
                                $getFBReturn = $this->postOnFacebook($post_data, 1);
                                unset($post_data['fb_user_id']);
                                unset($post_data['fb_access_token']);
                            }
                            // post on user twitter
                            if (Configure::read('social_networking.post_event_on_user_twitter')) {
                                if (!empty($user['User']['twitter_access_token']) && !empty($user['User']['twitter_access_key'])) {
                                    $post_data['twitter_access_key'] = $user['User']['twitter_access_key'];
                                    $post_data['twitter_access_token'] = $user['User']['twitter_access_token'];
                                    $getTewwtReturn = $this->postOnTwitter($post_data, 0);
                                    unset($post_data['twitter_access_key']);
                                    unset($post_data['twitter_access_token']);
                                }
                            }
                            if (Configure::read('event.post_on_twitter')) { // post on site twitter
                                $getTewwtReturn = $this->postOnTwitter($post_data, 1);
                            }
                        }
                        if ((!empty($this->request->data['Event']['is_featured']) && $this->request->data['Event']['is_featured'] == 1) || (!empty($this->request->data['Event']['is_bump_up']) && $this->request->data['Event']['is_bump_up'] == 1)) {
                            $this->redirect(array(
                                'controller' => 'payments',
                                'action' => 'order',
                                $event['Event']['slug'],
                                'event'
                            ));
                        } else {
                            if (!empty($this->request->params['admin'])) {
                                $this->redirect(array(
                                    'controller' => 'events',
                                    'action' => 'admin_index'
                                ));
                            } else {
                                $this->redirect(array(
                                    'controller' => 'events',
                                    'action' => 'view',
                                    $event['Event']['slug']
                                ));
                            }
                        }
                    } else {
                        $this->Session->setFlash(__l('Invalid Venue. please select a correct venue') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Event could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You must select a venue by typing atleast the first 4 letter in the venue and then select the venue from the list or you entered venue name not in list') , 'default', null, 'error');
            }
        } else {
            if ($venue_slug) {
                $venue = $this->Event->Venue->find('first', array(
                    'conditions' => array(
                        'Venue.slug' => $venue_slug
                    ) ,
                    'fields' => array(
                        'Venue.name',
                        'Venue.id',
                    ) ,
                    'recursive' => -1
                ));
                $this->request->data['Venue']['name'] = $venue['Venue']['name'];
                $this->request->data['Event']['venue_id'] = $venue['Venue']['id'];
            }
        }
        $eventSponsors = $this->Event->EventSponsor->find('list', array(
            'conditions' => array(
                'EventSponsor.is_active = ' => 1
            )
        ));
        $eventScenes = $this->Event->EventScene->find('list', array(
            'conditions' => array(
                'EventScene.is_active = ' => 1
            )
        ));
        $eventMusics = $this->Event->MusicType->find('list', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            )
        ));
        $eventCategories = $this->Event->EventCategory->find('list', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 1
            ) ,
            'order' => array(
                'EventCategory.id' => 'DESC'
            ) ,
        ));
        $ageRrequirments = $this->Event->AgeRequirment->find('list', array(
            'conditions' => array(
                'AgeRequirment.is_active = ' => 1
            )
        ));
        $eventTypes = $this->Event->EventType->find('list', array(
            'conditions' => array(
                'EventType.is_active = ' => 1
            )
        ));
        $days = $this->Event->Day->find('list');
        $monthlyRepeatTypes = $this->Event->MonthlyRepeatType->find('list');
        $repeatValue = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeatValue[$i] = $i;
        }
        if (!empty($this->request->params['admin'])) {
            $users = $this->Event->User->find('list', array(
                'conditions' => array(
                    'User.is_active = ' => 1
                )
            ));
            $this->set('users', $users);
        }
        $guestSignups = array();
        for ($i = 1; $i <= 7; $i++) {
            $guestSignups[$i] = $i;
        }
        $users = $this->Event->User->find('list');
        $this->set(compact('eventSponsors', 'eventScenes', 'eventMusics', 'eventCategories', 'venues', 'days', 'eventTypes', 'monthlyRepeatTypes', 'repeatValue', 'ageRrequirments', 'guestSignups', 'users'));
        $this->set('is_paypal_required', $is_paypal_required);
    }
    public function edit($id = null)
    {
        $is_paypal_required = 0;
        $this->pageTitle = __l('Edit Event');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
		$userprofile = $this->Event->User->UserProfile->find('first', array(
			'conditions' => array(
				'UserProfile.user_id' => $this->Auth->user('id')
			) ,
			'fields' => array(
				'UserProfile.id',
				'UserProfile.paypal_account',
				'UserProfile.paypal_first_name',
				'UserProfile.paypal_last_name',
			) ,
			'recursive' => -1,
		));
		if (empty($userprofile['UserProfile']['paypal_account'])) {
          $is_paypal_required = 1;
		}
        $event = $this->Event->find('first', array(
            'conditions' => array(
                'Event.id' => $id
            ) ,
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir'
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.name',
                        'Venue.id',
                    )
                )
            ) ,
            'recursive' => 0
        ));
        if (!empty($this->request->data)) {
            if (!$this->Auth->user('is_email_confirmed')) {
                $this->Session->setFlash(__l('Oops, Still you are not confirm the email') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'events',
                    'action' => 'edit'
                ));
            }
            $this->request->data['EventTag']['EventTag'] = $this->Event->EventTag->_saveTags($this->request->data['Event']['Tags']);
            // paypal account verification
            if (!empty($this->request->data['Event']['ticket_fee']) && $this->request->data['Event']['ticket_fee'] > 0) {
                if (empty($userprofile['UserProfile']['paypal_account']) && !isset($this->request->data['UserProfile']['paypal_account'])) {
                    $this->Session->setFlash(__l('Enter PayPal verification email and name associated with your PayPal') , 'default', null, 'error');
                    $is_paypal_required = 1;
                    $this->Event->User->UserProfile->validationErrors['paypal_account'] = __l('Please enter your verified PayPal account');
                    $this->Event->User->UserProfile->validationErrors['paypal_first_name'] = __l('Enter PayPal First Name. As given in PayPal.');
                    $this->Event->User->UserProfile->validationErrors['paypal_last_name'] = __l('Enter PayPal Last Name. As given in PayPal.');
                } else if (empty($userprofile['UserProfile']['paypal_account']) && !empty($this->request->data['UserProfile']['paypal_account'])) {
                    App::import('Model', 'Payment');
                    $this->Payment = new Payment();
                    $rsPayStatus = $this->Payment->getVerifiedStatus($this->request->data['UserProfile']);
                    if (strtoupper($rsPayStatus['responseEnvelope.ack']) != 'SUCCESS' || strtoupper($rsPayStatus['accountStatus']) != 'VERIFIED') {
                        $is_paypal_required = 1;
                        $this->Session->setFlash(__l('Enter PayPal verification email and name associated with your PayPal') , 'default', null, 'error');
                        $this->Event->User->UserProfile->validationErrors['paypal_account'] = __l('Enter correct PayPal email and name associated with your PayPal.');
                        $this->Event->User->UserProfile->validationErrors['paypal_first_name'] = __l('Enter PayPal First Name. As given in PayPal.');
                        $this->Event->User->UserProfile->validationErrors['paypal_last_name'] = __l('Enter PayPal Last Name. As given in PayPal.');
					} else {
						$is_paypal_required = 0;
					}
                }
            } else {
				$is_paypal_required = 0;
			}
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                if (!empty($event)) {
                    $this->request->data['Event']['id'] = $event['Event']['id'];
                    if (!empty($event['Attachment']['id'])) {
                        $this->request->data['Attachment']['id'] = $event['Attachment']['id'];
                    }
                }
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                $this->Event->Attachment->Behaviors->attach('ImageUpload', Configure::read('event.file'));
                $this->Event->Attachment->set($this->request->data);
            }
            $this->request->data['Event']['venue_id'] = !empty($this->request->data['Venue']['id']) ? $this->request->data['Venue']['id'] : $event['Event']['venue_id'];
            if ($this->request->data['Event']['is_all_day']) {
                $this->request->data['Event']['start_time']['hour'] = '00';
                $this->request->data['Event']['start_time']['min'] = '00';
                $this->request->data['Event']['start_time']['meridian'] = '00';
                $this->request->data['Event']['end_time']['hour'] = '00';
                $this->request->data['Event']['end_time']['min'] = '00';
                $this->request->data['Event']['end_time']['meridian'] = '00';
            }
            switch (!empty($this->request->data['Event']['event_type_id']) && $this->request->data['Event']['event_type_id']) {
                case 1:
                    $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                    $this->request->data['Event']['repeat_value'] = 0;
                    $this->request->data['Event']['repeat_type_id'] = 0;
                    break;

                case 2:
                    $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                    $this->request->data['Event']['repeat_type_id'] = 1;
                    break;

                case 3:
                case 4:
                case 5:
                    $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                    $this->request->data['Event']['repeat_value'] = 0;
                    $this->request->data['Event']['repeat_type_id'] = 0;
                    $this->request->data['Event']['repeat_on_week_bits'] = $this->Event->getWeekValue($this->request->data['Event']['event_type_id'], $this->request->data['Event']['days']);
                    break;

                case 6:
                    $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                    $this->request->data['Event']['repeat_type_id'] = 2;
                    $this->request->data['Event']['repeat_on_week_bits'] = $this->Event->getWeekValue($this->request->data['Event']['event_type_id'], $this->request->data['Event']['days']);
                    break;

                case 7:
                    $this->request->data['Event']['repeat_type_id'] = 3;
                    if ($this->request->data['Event']['monthly_repeat_type_id'] == 1) {
                        $this->request->data['Event']['repeat_on_month'] = date('d');
                    } else {
                        $days = $this->Event->Day->find('list');
                        $days = array_flip($days);
                        $this->request->data['Event']['repeat_on_month_day_id'] = $days[date('D') ];
                    }
                    break;

                case 8:
                    $this->request->data['Event']['repeat_type_id'] = 4;
                    $this->request->data['Event']['monthly_repeat_type_id'] = 0;
                    break;
            }
            $is_guestlist = true;
            if ($this->request->data['Event']['is_guest_list']) {
                $this->Event->GuestList->set($this->request->data);
                if ($this->Event->GuestList->validates()) {
                    $is_guestlist = true;
                } else {
                    $is_guestlist = false;
                }
            }
            $this->Event->set($this->request->data);
            if ($this->Event->validates() &$this->Event->Attachment->validates() && $is_guestlist && !$is_paypal_required) {
                $this->Event->save($this->request->data, false);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'EventImage';
                    $this->request->data['Attachment']['foreign_id'] = $this->request->data['Event']['id'];
                    $this->Event->Attachment->save($this->request->data['Attachment']);
                }
                if (!empty($this->request->data['GuestList'])) {
                    $gus_list = $this->Event->GuestList->find('first', array(
                        'conditions' => array(
                            'GuestList.event_id' => $this->request->data['Event']['id']
                        ) ,
                        'fields' => array(
                            'GuestList.id'
                        )
                    ));
                    $this->request->data['GuestList']['id'] = $gus_list['GuestList']['id'];
                    $this->request->data['GuestList']['event_id'] = $this->request->data['Event']['id'];
                    $this->Event->GuestList->create();
                    $this->Event->GuestList->save($this->request->data);
                }
                if (!empty($this->request->data['Event']['event_scene'])) {
                    $this->Event->EventsEventScene->deleteAll(array(
                        'EventsEventScene.event_id' => $this->request->data['Event']['id']
                    ));
                    $eventScene['event_id'] = $this->request->data['Event']['id'];
                    for ($i = 0; $i < count($this->request->data['Event']['event_scene']); $i++) {
                        $this->Event->EventsEventScene->create();
                        $eventScene['event_scene_id'] = $this->request->data['Event']['event_scene'][$i];
                        $this->Event->EventsEventScene->save($eventScene);
                    }
                }
                if (!empty($this->request->data['Event']['event_music'])) {
                    $this->Event->EventsMusicType->deleteAll(array(
                        'EventsMusicType.event_id' => $this->request->data['Event']['id']
                    ));
                    $eventMusics['event_id'] = $this->request->data['Event']['id'];
                    for ($i = 0; $i < count($this->request->data['Event']['event_music']); $i++) {
                        $this->Event->EventsMusicType->create();
                        $eventMusics['music_type_id'] = $this->request->data['Event']['event_music'][$i];
                        $this->Event->EventsMusicType->save($eventMusics);
                    }
                }
                if (!empty($this->request->data['UserProfile'])) {
                    $this->request->data['UserProfile']['id'] = $userprofile['UserProfile']['id'];
                    $this->Event->User->UserProfile->save($this->request->data['UserProfile']);
                }
                $this->Session->setFlash(__l('Event has been updated') , 'default', null, 'success');
                if (!empty($this->request->params['admin'])) {
                    $this->redirect(array(
                        'controller' => 'events',
                        'action' => 'admin_index'
                    ));
                } else {
                    $new_event_data = $this->Event->find('first', array(
                        'conditions' => array(
                            'Event.id' => $event['Event']['id']
                        ) ,
                        'fields' => array(
                            'Event.slug'
                        ) ,
                        'recursive' => 0
                    ));
                    $this->redirect(array(
                        'controller' => 'events',
                        'action' => 'view',
                        $new_event_data['Event']['slug']
                    ));
                }
            } else {
                $this->Session->setFlash(__l(' Event could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Event->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
			if (!empty($this->request->data['Event']['start_date'])) {
                $this->request->data['Event']['start_date'] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['start_date'] . " " . gmdate('H:i:s', strtotime("now"))));
            }
            if (!empty($this->request->data['Event']['end_date'])) {
                $this->request->data['Event']['end_date'] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['end_date'] . " " . gmdate('H:i:s', strtotime("now"))));
            }
			if (!empty($this->request->data['Event']['listing_appears_on_site'])) {
                $this->request->data['Event']['listing_appears_on_site'] = _formatDate('Y-m-d', strtotime($this->request->data['Event']['listing_appears_on_site'] . " " . gmdate('H:i:s', strtotime("now"))));
            }
            if (!empty($this->request->data['Event']['start_time'])) {
                $this->request->data['Event']['start_time'] = _formatDate('Y-m-d H:i:s', strtotime($this->request->data['Event']['start_time']));
            }
            if (!empty($this->request->data['Event']['end_time'])) {
                $this->request->data['Event']['end_time'] = _formatDate('Y-m-d H:i:s', strtotime($this->request->data['Event']['end_time']));
            }
			if (!empty($this->request->data['GuestList']['guest_close_time'])) {
                $this->request->data['GuestList']['guest_close_time'] = _formatDate('Y-m-d H:i:s', strtotime($this->request->data['GuestList']['guest_close_time']));
            }
            if (!empty($this->request->data['GuestList']['website_close_time'])) {
                $this->request->data['GuestList']['website_close_time'] = _formatDate('Y-m-d H:i:s', strtotime($this->request->data['GuestList']['website_close_time']));
            }
            if (!empty($this->request->params['admin'])) {
                $users = $this->Event->User->find('list', array(
                    'conditions' => array(
                        'User.is_active = ' => 1
                    )
                ));
                $this->set('users', $users);
            }
            $this->request->data['Event']['Tags'] = $this->Event->_formatTags($this->request->data['EventTag']);
        }
        $this->pageTitle.= ' - ' . $this->request->data['Event']['title'];
        if (empty($this->request->params['admin'])) {
            if ($event['Event']['user_id'] != $this->Auth->user('id') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->Session->setFlash(__l(' You cannot edit other\'s events.') , 'default', null, 'error');
                $this->redirect(array(
                    'action' => 'index'
                ));
            }
        }
        $this->request->data['Event']['event_scene'] = $this->Event->EventsEventScene->find('list', array(
            'conditions' => array(
                'EventsEventScene.event_id' => $id
            ) ,
            'fields' => array(
                'EventsEventScene.event_scene_id',
            )
        ));
        $this->request->data['Event']['event_music'] = $this->Event->EventsMusicType->find('list', array(
            'conditions' => array(
                'EventsMusicType.event_id' => $id
            ) ,
            'fields' => array(
                'EventsMusicType.music_type_id',
            )
        ));
        $repeatValue = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeatValue[$i] = $i;
        }
        $eventScenes = $this->Event->EventScene->find('list', array(
            'conditions' => array(
                'EventScene.is_active = ' => 1
            )
        ));
        $eventMusics = $this->Event->MusicType->find('list', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            )
        ));
        $eventCategories = $this->Event->EventCategory->find('list', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 1
            )
        ));
        $eventTypes = $this->Event->EventType->find('list', array(
            'conditions' => array(
                'EventType.is_active = ' => 1
            )
        ));
        $eventSponsors = $this->Event->EventSponsor->find('list', array(
            'conditions' => array(
                'EventSponsor.is_active = ' => 1
            )
        ));
        $days = $this->Event->Day->find('list');
        $monthlyRepeatTypes = $this->Event->MonthlyRepeatType->find('list');
        $repeatValue = array();
        for ($i = 1; $i <= 30; $i++) {
            $repeatValue[$i] = $i;
        }
        if (!empty($this->request->params['admin'])) {
            $users = $this->Event->User->find('list', array(
                'conditions' => array(
                    'User.is_active = ' => 1
                )
            ));
            $this->set('users', $users);
        }
        $ageRrequirments = $this->Event->AgeRequirment->find('list', array(
            'conditions' => array(
                'AgeRequirment.is_active = ' => 1
            )
        ));
        $guestSignups = array();
        for ($i = 1; $i <= 7; $i++) {
            $guestSignups[$i] = $i;
        }
        $this->set(compact('eventSponsors', 'eventScenes', 'eventMusics', 'eventCategories', 'venues', 'days', 'eventTypes', 'monthlyRepeatTypes', 'repeatValue', 'ageRrequirments', 'guestSignups'));
        $event_timing = $this->Event->getEventTimingInfo($this->request->data);
        if (!empty($this->request->data['Event']['repeat_on_week_bits']) && $this->request->data['Event']['repeat_on_week_bits']) {
            $selected_days = $this->Event->getSelectedDaysArray($this->request->data['Event']['repeat_on_week_bits']);
            $this->set('selected_days', $selected_days);
        }
        $this->set('event_timing', $event_timing);
        $this->set('is_paypal_required', $is_paypal_required);
    }
    public function delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $event = $this->Event->find('first', array(
            'conditions' => array(
                'Event.id = ' => $id
            ) ,
            'fields' => array(
                'Event.user_id'
            ) ,
            'recursive' => -1,
        ));
        if ($event['Event']['user_id'] != $this->Auth->user('id')) {
            throw new NotFoundException(__l('Invalid request'));
        } else {
            if ($this->Event->delete($id)) {
                $this->Session->setFlash(__l('Event deleted') , 'default', null, 'success');
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
        $this->pageTitle = __l('Events');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'keyword',
            'type',
            'user'
        ));
        if (!empty($this->request->params['named'])) {
            $this->request->data['Event'] = array(
                'keyword' => (!empty($this->request->params['named']['keyword'])) ? $this->request->params['named']['keyword'] : '',
                'type' => (!empty($this->request->params['named']['type'])) ? $this->request->params['named']['type'] : '',
                'user' => (!empty($this->request->params['named']['user'])) ? $this->request->params['named']['user'] : ''
            );
            if (!empty($this->request->data['Event']['keyword'])) {
                $conditions['OR'] = array(
                    'Event.title LIKE' => '%' . $this->request->data['Event']['keyword'] . '%',
                    'Event.description LIKE' => '%' . $this->request->data['Event']['keyword'] . '%'
                );
            }
            if (!empty($this->request->data['Event']['type'])) {
                if ($this->request->data['Event']['type'] == 'upcoming') {
                    $this->pageTitle.= ' -Upcoming';
                    $conditions['Event.start_date >='] = date('Y-m-d H:i:s');
                    $order = 'Event.start_date ASC';
                } else if ($this->request->data['Event']['type'] == 'past') {
                    $this->pageTitle.= ' -Past';
                    $conditions['Event.start_date <= '] = date('Y-m-d H:i:s');
                    $order = 'Event.start_date DESC';
                }
            }
            if (!empty($this->request->data['Event']['user'])) {
                $conditions['Event.user_id'] = $this->request->data['Event']['user'];
            }
        }
        if (!empty($this->request->params['named']['category'])) {
            $eventCategory = $this->{$this->modelClass}->EventCategory->find('first', array(
                'conditions' => array(
                    'EventCategory.slug' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'EventCategory.id',
                    'EventCategory.name',
                    'EventCategory.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($eventCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['EventCategory.slug'] = $eventCategory['EventCategory']['slug'];
            $this->pageTitle.= sprintf(__l(' - Category -  %s') , $eventCategory['EventCategory']['name']);
        }
        if (!empty($this->request->params['named']['sponsor'])) {
            $this->pageTitle.= ' - ' . __l('Sponsored by ') . $this->request->params['named']['sponsor'];
            $event_ids = $this->Event->EventsEventSponsor->find('list', array(
                'conditions' => array(
                    'EventSponsor.slug LIKE' => '%' . $this->request->params['named']['sponsor'] . '%'
                ) ,
                'fields' => array(
                    'EventsEventSponsor.event_id',
                ) ,
                'recursive' => 0,
            ));
            $conditions['Event.id'] = $event_ids;
            $this->set('sponsor', $this->request->params['named']['sponsor']);
        }
        if (isset($this->request->params['named']['user'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->params['named']['user']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            $this->pageTitle.= ' - ' . $user['User']['username'];
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Event.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['Event.created >='] = date('Y-m-d 00:00:00', strtotime('last monday'));
            $conditions['Event.created <'] = date('Y-m-d 00:00:00', strtotime('last monday +7 days'));
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['Event.created >='] = date('Y-m-d 00:00:00', (strtotime('last month', strtotime(date('m/01/y')))));
            $conditions['Event.created <='] = date('Y/m/d h:i:s', (strtotime('next month', strtotime(date('m/01/y'))) -1));
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Event.is_active'] = 1;
                $conditions['Event.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Event.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Event.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Event.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Cancel) {
                $conditions['Event.is_cancel'] = 1;
                $this->pageTitle.= __l(' - Canceled ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Featured) {
                $conditions['Event.is_feature'] = 1;
                $this->pageTitle.= __l(' - Featured ');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'EventTag' => array(
                    'fields' => array(
                        'EventTag.id',
                        'EventTag.name',
                        'EventTag.slug',
                    )
                ) ,
                'EventCategory',
                'EventSponsor',
                'AgeRequirment',
                'GuestList',
                'Venue',
				'Ip',
                'Attachment',
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                    )
                ) ,
            ) ,
            'order' => 'Event.id DESC',
            'recursive' => 2,
        );
        if (!empty($this->request->params['named']['dashboard'])) {
            $setDashboard = '1';
        } else {
            $setDashboard = '0';
        }
        $this->set('active', $this->Event->find('count', array(
            'conditions' => array(
                'Event.is_active' => 1,
                'Event.admin_suspend' => 0
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Event->find('count', array(
            'conditions' => array(
                'Event.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Event->find('count', array(
            'conditions' => array(
                'Event.is_system_flagged' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('suspended', $this->Event->find('count', array(
            'conditions' => array(
                'Event.admin_suspend' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('canceled', $this->Event->find('count', array(
            'conditions' => array(
                'Event.is_cancel' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('featured', $this->Event->find('count', array(
            'conditions' => array(
                'Event.is_feature' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('setDashboard', $setDashboard);
        $this->set('events', $this->paginate());
        $users = $this->Event->User->find('list', array(
            'conditions' => array(
                'User.is_active' => 1
            )
        ));
        $this->set('users', $users);
        $moreActions = $this->Event->moreActions;
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
        if (is_null($id) && empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Event->delete($id)) {
            $this->Session->setFlash(__l('Event deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function cancel($event_id = null)
    {
        if (is_null($event_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($event_id)) {
            $event = $this->Event->find('first', array(
                'conditions' => array(
                    'Event.id = ' => $event_id
                )
            ));
            $event['Event']['is_cancel'] = 1;
            if ($this->Event->save($event, false)) {
                $this->Event->PhotoAlbum->updateAll(array(
                    'PhotoAlbum.is_active' => 0
                ) , array(
                    'PhotoAlbum.event_id' => $event_id
                ));
            }
            $albums = $this->Event->PhotoAlbum->find('list', array(
                'conditions' => array(
                    'PhotoAlbum.event_id' => $event_id
                ) ,
                'fields' => array(
                    'PhotoAlbum.id'
                )
            ));
            $this->Event->PhotoAlbum->Photo->updateAll(array(
                'Photo.is_active' => 0
            ) , array(
                'Photo.photo_album_id' => $albums
            ));
            $this->Event->Video->updateAll(array(
                'Video.is_approved' => 0,
                'Video.is_canceled' => 1,
            ) , array(
                'Video.foreign_id' => $event_id,
                'Video.class' => 'Event'
            ));
            $this->_sendCancelMail($event_id);
            $this->Session->setFlash(__l('Event canceled') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function create()
    {
        $this->pageTitle = __l('Create Event');
        $cities = $this->Event->Venue->City->find('list');
        $countries = $this->Event->Venue->Country->find('list');
        $venueCategories = $this->Venue->VenueCategory->find('list', array(
            'conditions' => array(
                'VenueCategory.is_active = ' => 1
            ) ,
            'order' => 'VenueCategory.name ASC'
        ));
        $this->set(compact('venueCategories', 'countries', 'cities', 'days'));
    }
    public function _sendCancelMail($event_id)
    {
        $event_user = $this->Event->EventUser->find('list', array(
            'conditions' => array(
                'EventUser.event_id =' => $event_id
            ) ,
            'recursive' => 0
        ));
        if (!empty($event_user)) {
            $email = $this->EmailTemplate->selectTemplate('Event cancelation');
            $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
            $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
            foreach($event_user as $eventusers):
                $eventusr = $this->Event->EventUser->find('first', array(
                    'conditions' => array(
                        'EventUser.id =' => $eventusers
                    ) ,
                    'recursive' => 1
                ));
                $this->Email->to = $eventusr['User']['email'];
                $emailFindReplace = array(
                    '##USERNAME##' => $eventusr['User']['username'],
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##EVENTNAME##' => $eventusr['Event']['title']
                );
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
                    return true;
                }
            endforeach;
        }
        return true;
    }
    public function week_events()
    {
        $this->pageTitle = __l('Events');
        $filter = '';
        $conditions = array();
        $conditions['Event.is_cancel'] = 0;
        if (isset($this->request->params['named']['date']) && $this->request->params['named']['date'] != 'week') {
            if ($this->request->params['named']['date'] == 'up-coming') {
                $conditions = array(
                    'Event.start_date >' => date('Y-m-d', strtotime(date('Y-m-d', time()) . " +6 days")) ,
                );
            } else {
                $conditions = array(
                    'Event.start_date <=' => date('Y-m-d', $this->request->params['named']['date']) ,
                    'Event.end_date >=' => date('Y-m-d', $this->request->params['named']['date']) ,
                );
            }
        } else {
            $conditions = array(
                'Event.start_date <=' => date('Y-m-d', strtotime(date('Y-m-d', time()) . " +6 days")) ,
                'Event.listing_appears_on_site <=' => date('Y-m-d', time()) ,
                'Event.is_cancel' => 0,
                'Event.end_date >=' => date('Y-m-d', time()) ,
            );
        }
        if (!empty($this->_prefixId)) {
            $conditions['Venue.city_id'] = $this->_prefixId;
        }
        $conditions['Event.is_active'] = 1;
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'guest') {
            $conditions['Event.is_guest_list'] = 1;
            $conditions['OR'][]['Event.is_feature'] = 1;
            $conditions['OR'][]['Event.is_featured'] = 1;
        }
        $conditions['Event.admin_suspend'] = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'EventCategory' => array(
                    'fields' => array(
                        'EventCategory.id',
                        'EventCategory.name',
                        'EventCategory.description',
                        'EventCategory.slug',
                    )
                ) ,
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.email',
                    )
                ) ,
                'EventType' => array(
                    'fields' => array(
                        'EventType.name'
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.description',
                        'Venue.venue_type_id',
                        'Venue.address',
                        'Venue.city_id',
                        'Venue.country_id',
                        'Venue.longitude',
                        'Venue.latitude',
                        'Venue.phone',
                        'Venue.landmark',
                        'Venue.slug',
                        'Venue.is_active',
                        'Venue.ip_id',
                    ) ,
                    'City',
                    'Country',
                ) ,
                'Attachment',
            ) ,
            'order' => 'Event.start_date asc',
            'limit' => '5',
            'recursive' => 2,
        );
        $events = $this->paginate();
        $this->set('events', $events);
    }
    public function search()
    {
        $this->pageTitle = __l('Search');
        $eventScenes = $this->Event->EventScene->find('list', array(
            'conditions' => array(
                'EventScene.is_active = ' => 1
            )
        ));
        $eventMusics = $this->Event->MusicType->find('list', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            )
        ));
        if (!empty($_SESSION['search'])) {
            $this->request->data['Event'] = $_SESSION['search'];
        } else {
            $this->request->data['Event']['start_from'] = date('Y-m-d', time());
            $this->request->data['Event']['start_end'] = date('Y-m-d', strtotime(date('Y-m-d', time()) . " +1 week"));
        }
        $eventCategories = $this->Event->EventCategory->find('list', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 1
            )
        ));
        $country_id = $this->Event->Venue->City->find('first', array(
            'conditions' => array(
                'City.id' => $this->_prefixId
            ) ,
            'fields' => array(
                'City.country_id',
            ) ,
            'recursive' => -1
        ));
        $cities = $this->Event->Venue->City->find('list', array(
            'conditions' => array(
                'City.country_id = ' => $country_id['City']['country_id'],
                'City.id != ' => $this->_prefixId,
                'City.is_approved' => 1,
            ) ,
            'recursive' => -1
        ));
        $this->set('country_id', $country_id);
        $this->set(compact('eventScenes', 'eventMusics', 'eventCategories', 'cities'));
    }
    public function home_search()
    {
        $this->pageTitle = __l('Search');
        $eventCategories = $this->Event->EventCategory->find('all', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 1
            ) ,
            'recursive' => -1
        ));
        $eventMusics = $this->Event->MusicType->find('all', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1
            ) ,
            'recursive' => -1
        ));
        $venueTypes = $this->Event->Venue->VenueType->find('all', array(
            'conditions' => array(
                'VenueType.is_active = ' => 1
            ) ,
            'recursive' => -1
        ));
        $this->set(compact('eventCategories', 'eventMusics', 'venueTypes'));
    }
    public function print_ticket($id)
    {
        $guestListUser = $this->Event->GuestList->GuestListUser->find('first', array(
			'conditions' => array(
				'GuestListUser.id' => $id
			),
			'fields' => array(
				'GuestListUser.guest_list_id',
				'GuestListUser.in_party_count',
				'GuestListUser.date',
				'GuestListUser.id',
			),
			'contain' => array(
				'GuestList' => array(
					'Event' => array(
						'fields' => array(
							'Event.title',
							'Event.slug',
							'Event.start_date',
							'Event.start_time',
							'Event.end_date',
							'Event.end_time',
						),
						'Venue' => array(
							'fields' => array(
								'Venue.name',
								'Venue.slug'
							)
						),
					)
				)
			),
			'recursive' => 3
        ));
		if(empty($guestListUser)) {
			throw new NotFoundException(__l('Invalid request'));
		}
		$this->pageTitle = __l('Ticket'). ' - ' . $guestListUser['GuestList']['Event']['title'];
		$this->set('guestListUser', $guestListUser);
		$this->layout = 'print';
    }
}
?>