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
class PhotoAlbumsController extends AppController
{
    public $name = 'PhotoAlbums';
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_album')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->disabledFields = array(
            'User.id'
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_photo_album_on_system_flag')) {
            $this->PhotoAlbum->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index() 
    {
        $this->_redirectGET2Named(array(
            'sort_by',
            'keyword',
        ));
        $this->pageTitle = __l('Galleries');
        $conditions = array();
        if (!empty($this->request->params['named']['username'])) {
            $user = $this->PhotoAlbum->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['username']
                ) ,
                'fields' => array(
                    'User.id'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PhotoAlbum.user_id'] = $user['User']['id'];
            $this->set('user', $user);
        }
        if (!empty($this->request->params['named']['venue_id'])) {
            if (!empty($this->_prefixId)) {
                $conditions['Venue.' . Inflector::underscore(Configure::read('site.prefix_parameter_model')) . '_id'] = $this->_prefixId;
            }
            $venue = $this->PhotoAlbum->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $this->request->params['named']['venue_id']
                ) ,
                'contain' => array(
                    'Country' => array(
                        'fields' => array(
                            'Country.id',
                            'Country.name'
                        )
                    )
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name'
                ) ,
                'recursive' => 0
            ));
            if (empty($venue)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PhotoAlbum.venue_id'] = $this->request->params['named']['venue_id'];
            $this->set('venue', $venue);
        }
        if (!empty($this->request->params['named']['event_id'])) {
            $event = $this->PhotoAlbum->Event->find('first', array(
                'conditions' => array(
                    'Event.id' => $this->request->params['named']['event_id']
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.slug',
                    'Event.description'
                ) ,
                'recursive' => 0
            ));
            if (empty($event)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PhotoAlbum.event_id'] = $this->request->params['named']['event_id'];
            $this->set('event', $event);
        }
        if (!empty($this->request->params['named']['sort_by'])) {
            $this->request->data['sort_by'] = $this->request->params['named']['sort_by'];
            switch ($this->request->params['named']['sort_by']) {
                case 'name':
                    $order = array(
                        'PhotoAlbum.slug' => 'asc'
                    );
                    break;

                case 'date':
                    $order = array(
                        'PhotoAlbum.captured_date' => 'desc'
                    );
                    break;
            }
        } else {
            $order = array(
                'PhotoAlbum.captured_date' => 'desc'
            );
        }
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['PhotoAlbum']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['PhotoAlbum.title Like'] = '%' . $this->request->data['PhotoAlbum']['keyword'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->data['PhotoAlbum']['keyword']);
        }
        if (!empty($this->request->params['named']['location'])) {
            $limit = 5;
        } elseif ((!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'latest' || $this->request->params['named']['type'] == 'home' || $this->request->params['named']['type'] == 'last_night'))) {
            $limit = $this->request->params['named']['type'] == 'last_night' ? 6 : 3;
        } else {
            $limit = 15;
        }
        $conditions['PhotoAlbum.is_active'] = 1;
        $conditions['PhotoAlbum.city_id'] = $this->_prefixId;
        $conditions['PhotoAlbum.photo_count !='] = 0;
//        $conditions['Venue.admin_suspend'] = 0;
        $conditions['Event.admin_suspend'] = 0;
        $conditions['PhotoAlbum.admin_suspend'] = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.id',
                            'UserProfile.city_id',
                        ) ,
                        'City',
                    ) ,
                    'fields' => array(
                        'User.id',
                        'User.username',
                        'User.user_type_id',
                    ) ,
                ) ,
                'Photo' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.dir',
                            'Attachment.filename',
                            'Attachment.height',
                            'Attachment.width'
                        )
                    ) ,
                    'fields' => array(
                        'Photo.id'
                    ) ,
                    'limit' => 1
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug'
                    )
                ) ,
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.title',
                        'Event.slug'
                    )
                )
            ) ,
            'recursive' => '3',
            'limit' => $limit,
            'order' => $order
        );
        $this->set('photoAlbums', $this->paginate());
        if ((!empty($this->request->params['named']['type']) && ($this->request->params['named']['type'] == 'last_night' or $this->request->params['named']['type'] == 'latest'))) {
            $this->autoRender = false;
            $this->render('home_index');
        }
    }
    public function add() 
    {
        $this->pageTitle = __l('Create New Gallery');
        $user_id = !empty($this->request->data['PhotoAlbum']['user_id']) ? $this->request->data['PhotoAlbum']['user_id'] : $this->Auth->user('id');
        if (!empty($this->request->params['named']['venue_id']) || !empty($this->request->data['PhotoAlbum']['venue_id'])) {
            if (!empty($this->request->data['PhotoAlbum']['venue_id'])) {
                $venue_id = $this->request->data['PhotoAlbum']['venue_id'];
            } else {
                $venue_id = $this->request->params['named']['venue_id'];
            }
            $venue = $this->PhotoAlbum->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $venue_id,
                ) ,
                'contain' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name'
                        )
                    ) ,
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug',
                    'Venue.address',
                    'Venue.city_id',
                    'Venue.zip_code'
                ) ,
                'recursive' => 0
            ));
            $this->set('venue', $venue);
        }
        if (!empty($this->request->params['named']['event_id']) || !empty($this->request->data['PhotoAlbum']['event_id'])) {
            if (!empty($this->request->data['PhotoAlbum']['event_id'])) {
                $event_id = $this->request->data['PhotoAlbum']['event_id'];
            } else {
                $event_id = $this->request->params['named']['event_id'];
            }
            $event = $this->PhotoAlbum->Event->find('first', array(
                'conditions' => array(
                    'Event.id' => $event_id,
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.description',
                    'Event.slug'
                ) ,
                'recursive' => -1
            ));
            $this->set('event', $event);
        }
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $user = $this->PhotoAlbum->User->find('first', array(
                'conditions' => array(
                    'User.id' => $user_id
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.photo_album_count'
                ) ,
                'recursive' => -1
            ));
            if ($user['User']['photo_album_count'] >= Configure::read('photo.no_of_albums_per_user')) {
                $this->Session->setFlash(__l('Photo Album could not be added. Your allowed albums count is over. Delete the old one\'s') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'photos',
                    'action' => 'index',
                    $user['User']['username']
                ));
            }
        }
        if (!empty($this->request->data)) {
            $this->PhotoAlbum->create();
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['PhotoAlbum']['user_id'] = $this->Auth->user('id');
            }
            $this->request->data['PhotoAlbum']['ip_id'] =  $this->PhotoAlbum->toSaveIp();
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['PhotoAlbum']['is_active'] = (Configure::read('photo.is_admin_activate_after_photo_album_add')) ? 0 : 1;
            } else {
                $this->request->data['PhotoAlbum']['is_active'] = 1;
            }
            $user_status = true;
            if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && !empty($this->request->data)) {
                if (!empty($this->request->data['User']['username'])) {
                    $user_id = $this->PhotoAlbum->User->find('first', array(
                        'conditions' => array(
                            'User.username' => $this->request->data['User']['username']
                        ) ,
                        'fields' => array(
                            'User.id',
                        ) ,
                        'recursive' => -1
                    ));
                    if (!empty($user_status)) {
                        $this->request->data['PhotoAlbum']['user_id'] = $user_id['User']['id'];
                    } else {
                        $user_status = false;
                    }
                } else {
                    $user_status = false;
                }
            }
            if ($user_status) {
                if ($this->PhotoAlbum->save($this->request->data)) {
                    if (!empty($this->request->data['PhotoAlbum']['photoalbum_type']) && $this->request->data['PhotoAlbum']['photoalbum_type'] == 'venuegallery') {
                        $this->PhotoAlbum->Venue->updateAll(array(
                            'Venue.venue_gallery_id' => $this->PhotoAlbum->getLastInsertId() ,
                        ) , array(
                            'Venue.id' => $this->request->data['PhotoAlbum']['venue_id']
                        ));
                    }
                    if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                        if (Configure::read('photo.is_admin_activate_after_photo_album_add')) {
                            $this->Session->setFlash(__l('Photo Album has been added. Now you can upload the photo but after admin approval it will list out in site.') , 'default', null, 'success');
                        } else {
                            $this->Session->setFlash(__l('Photo Album has been added') , 'default', null, 'success');
                        }
                    } else {
                        $this->Session->setFlash(__l('Photo Album has been added') , 'default', null, 'success');
                    }
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'add',
                        $this->PhotoAlbum->getLastInsertId() ,
                        'admin' => false
                    ));
                } else {
                    $this->Session->setFlash(__l('Photo Album could not be added. Please, try again.') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('Please select the valid user.') , 'default', null, 'error');
            }
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->PhotoAlbum->User->find('list');
            $venues = $this->PhotoAlbum->Venue->find('list');
            $events = $this->PhotoAlbum->Event->find('list');
            $this->set(compact('users', 'venues', 'events'));
        }
        if (!empty($venue_id)) {
            $this->request->data['PhotoAlbum']['venue_id'] = $venue_id;
        }
        if (!empty($event_id)) {
            $this->request->data['PhotoAlbum']['event_id'] = $event_id;
        }
        $city_id = $this->_prefixId;
        $this->set('city_id', $city_id);
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Photo Gallery');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && !empty($this->request->data)) {
            $user = $this->PhotoAlbum->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['PhotoAlbum']['user_id']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.photo_album_count'
                ) ,
                'recursive' => -1
            ));
            if ($user['User']['photo_album_count'] >= Configure::read('photo.no_of_albums_per_user')) {
                $this->Session->setFlash(__l('Photo Album could not be added. Allowed albums count is over. Delete the old one\'s') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'photo_albums',
                    'action' => 'index',
                    'username' => $user['User']['username']
                ));
            }
        }
        if (!empty($this->request->data)) {
            $this->request->data['PhotoAlbum']['ip_id'] =  $this->PhotoAlbum->toSaveIp();
            if ($this->PhotoAlbum->save($this->request->data)) {
                $photoAlbum = $this->PhotoAlbum->find('first', array(
                    'conditions' => array(
                        'PhotoAlbum.id' => $this->PhotoAlbum->id
                    ) ,
                    'fields' => array(
                        'PhotoAlbum.slug',
                        'PhotoAlbum.event_id',
                        'PhotoAlbum.venue_id',
                        'PhotoAlbum.user_id'
                    ) ,
                    'recursive' => -1
                ));
                $this->Session->setFlash(__l('Photo Album has been updated') , 'default', null, 'success');
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    if (!empty($photoAlbum['PhotoAlbum']['event_id'])) {
                        $this->redirect(array(
                            'controller' => 'photo_albums',
                            'action' => 'index',
                            'type' => 'event',
                        ));
                    } elseif (!empty($photoAlbum['PhotoAlbum']['venue_id'])) {
                        $this->redirect(array(
                            'controller' => 'photo_albums',
                            'action' => 'index',
                            'type' => 'venue',
                        ));
                    } else {
                        $this->redirect(array(
                            'controller' => 'photo_albums',
                            'action' => 'index',
                        ));
                    }
                } else {
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'index',
                        'album' => $photoAlbum['PhotoAlbum']['slug'],
                        'admin' => false
                    ));
                }
            } else {
                $this->Session->setFlash('Photo Album could not be updated. Please, try again.', 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->PhotoAlbum->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['PhotoAlbum']['title'];
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->PhotoAlbum->User->find('list');
            $this->set(compact('users'));
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoAlbum->delete($id)) {
            $this->Session->setFlash(__l('Photo Album deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'keyword',
            'type',
        ));
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['PhotoAlbum']['keyword'] = $this->request->params['named']['keyword'];
        }
        $conditions = array();
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue') {
            $this->pageTitle = __l('Venue Photo Galleries');
            $conditions['PhotoAlbum.venue_id != '] = 0;
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event') {
            $this->pageTitle = __l('Event Photo Galleries');
            $conditions['PhotoAlbum.event_id != '] = 0;
        } elseif (!empty($this->request->params['named']['venue_photo'])) {
            $this->pageTitle.= __l('Venue Photo Galleries');
            $venue = $this->{$this->modelClass}->Venue->find('first', array(
                'conditions' => array(
                    'Venue.slug' => $this->request->params['named']['venue_photo']
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($venue)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Venue.id'] = $venue['Venue']['id'];
        } elseif (!empty($this->request->params['named']['event_photo'])) {
            $this->pageTitle.= __l('Event Photo Galleries');
            $event = $this->{$this->modelClass}->Event->find('first', array(
                'conditions' => array(
                    'Event.slug' => $this->request->params['named']['event_photo']
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($event)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Event.id'] = $event['Event']['id'];
        } elseif (!empty($this->request->params['named']['username'])) {
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
            if (!empty($this->request->params['named']['venue'])) {
                $this->pageTitle.= 'User Venue Photo Galleries';
                $conditions['PhotoAlbum.venue_id !='] = 0;
                $conditions['PhotoAlbum.user_id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            } elseif (!empty($this->request->params['named']['event'])) {
                $this->pageTitle.= 'User Event Photo Galleries';
                $conditions['PhotoAlbum.event_id !='] = 0;
                $conditions['PhotoAlbum.user_id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            } else {
                $this->pageTitle.= 'User Photo Galleries';
                $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
            }
            $this->pageTitle.= ' - ' . $user['User']['username'];
        } else {
            $this->pageTitle = __l('User Photo Galleries');
            $conditions['PhotoAlbum.venue_id'] = 0;
            $conditions['PhotoAlbum.event_id'] = 0;
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoAlbum.created) <= '] = 0;
            $this->pageTitle.= __l(' - Created today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoAlbum.created) <= '] = 7;
            $this->pageTitle.= __l(' - Created in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoAlbum.created) <= '] = 30;
            $this->pageTitle.= __l(' - Created in this month');
        }
        if (!empty($this->request->data['PhotoAlbum']['keyword'])) {
            if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue') {
                $conditions['OR'] = array(
                    'PhotoAlbum.title Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'PhotoAlbum.description Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'User.username Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'Venue.name Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%'
                );
            } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event') {
                $conditions['OR'] = array(
                    'PhotoAlbum.title Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'PhotoAlbum.description Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'User.username Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'Event.title Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%'
                );
            } else {
                $conditions['OR'] = array(
                    'PhotoAlbum.title Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'PhotoAlbum.description Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%',
                    'User.username Like' => '%' . $this->request->data['PhotoAlbum']['keyword'] . '%'
                );
            }
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['PhotoAlbum.is_active'] = 1;
                $conditions['PhotoAlbum.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['PhotoAlbum.is_active'] = 0;
                $this->pageTitle.= __l(' - Pending Approval');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['PhotoAlbum.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['PhotoAlbum.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->PhotoAlbum->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username'
                    )
                ) ,
                'Ip' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name',
                        )
                    ) ,
                    'State' => array(
                        'fields' => array(
                            'State.name',
                        )
                    ) ,
                    'Country' => array(
                        'fields' => array(
                            'Country.name',
                            'Country.iso_alpha2',
                        )
                    ) ,
                    'Timezone' => array(
                        'fields' => array(
                            'Timezone.name',
                        )
                    ) ,
                    'fields' => array(
                        'Ip.ip',
                        'Ip.latitude',
                        'Ip.longitude',
                        'Ip.host',
                    )
                ) ,
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.title',
                        'Event.slug'
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug'
                    )
                )
            ) ,
            'order' => array(
                'PhotoAlbum.id' => 'desc'
            )
        );
        $this->set('pageTitle', $this->pageTitle);
        $this->set('photoAlbums', $this->paginate());
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue') {
            $filter_conditions = array(
                'PhotoAlbum.venue_id != ' => 0
            );
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event') {
            $filter_conditions = array(
                'PhotoAlbum.event_id != ' => 0
            );
        } else {
            $this->request->params['named']['type'] = 'user';
            $filter_conditions = array(
                'PhotoAlbum.venue_id' => 0,
                'PhotoAlbum.event_id' => 0
            );
        }
        $this->set('active', $this->PhotoAlbum->find('count', array(
            'conditions' => array_merge($filter_conditions, array(
                'PhotoAlbum.is_active' => 1,
                'PhotoAlbum.admin_suspend' => 0,
            )) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->PhotoAlbum->find('count', array(
            'conditions' => array_merge($filter_conditions, array(
                'PhotoAlbum.is_active' => 0,
            )) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->PhotoAlbum->find('count', array(
            'conditions' => array(
                'PhotoAlbum.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->PhotoAlbum->find('count', array(
            'conditions' => array(
                'PhotoAlbum.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->PhotoAlbum->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoAlbum->delete($id)) {
            $this->Session->setFlash(__l('Photo Album deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function lst() 
    {
        $this->layout = 'ajax';
        $albums = array();
        if (isset($this->request->params['named']['user_id'])) {
            $albums = $this->PhotoAlbum->find('list', array(
                'conditions' => array(
                    'PhotoAlbum.user_id' => $this->request->params['named']['user_id']
                )
            ));
        }
        $this->set('albums', $albums);
    }
}
?>