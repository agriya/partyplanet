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
class EventCommentsController extends AppController
{
    public $name = 'EventComments';
    public $uses = array(
        'EventComment',
        'Event'
    );
    public function beforeFilter() 
    {
        $this->Security->validatePost = false;
        $this->Security->disabledFields = array(
            'EventComment.title',
            'EventComment.comment',
            'EventComment.event_id',
            'EventComment.event_slug',
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_event_comment_on_system_flag')) {
            $this->EventComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($event_id = null) 
    {
        $this->disableCache();
        $this->pageTitle = __l('Event Comments');
        $this->set('event_id', $event_id);
        $conditions = array();
        $conditions['EventComment.is_active'] = 1;
        $conditions['EventComment.admin_suspend'] = 0;
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
        }
        if (!empty($event_id)) {
            $conditions['EventComment.event_id'] = $event_id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Event' => array(
                    'fields' => array(
                        'Event.user_id'
                    )
                ) ,
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                )
            ) ,
            'fields' => array(
                'EventComment.id',
                'EventComment.created',
                'EventComment.comment',
                'EventComment.user_id',
                'EventComment.title',
                'EventComment.name',
                'EventComment.event_id',
                'User.username'
            ) ,
            'order' => 'EventComment.id desc',
            'limit' => 5,
            'recursive' => 1,
        );
        $this->set('eventComments', $this->paginate());
    }
    public function add($event_id = null) 
    {
        $this->pageTitle = __l('Add Event Comment');
        if (!empty($this->request->data)) {
            $this->request->data['EventComment']['user_id'] = $this->Auth->user('id') ? $this->Auth->user('id') : '0';
            $this->request->data['EventComment']['name'] = $this->Auth->user('username') ? $this->Auth->user('username') : 'guest';
            $this->request->data['EventComment']['ip_id'] = $this->EventComment->toSaveIp();
            $this->EventComment->create();
            if ($this->EventComment->save($this->request->data)) {
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('view', $this->EventComment->getLastInsertId() , 'view_ajax');
                } else {
                    $this->Session->setFlash(__l('Event comment has been added') , 'default', null, 'success');
                    $event = $this->EventComment->Event->find('first', array(
                        'conditions' => array(
                            'Event.id' => $this->request->data['EventComment']['event_id']
                        ) ,
                        'fields' => array(
                            'Event.slug',
                        ) ,
                    ));
                    $this->redirect(array(
                        'controller' => 'events',
                        'action' => 'view',
                        $event['Event']['slug']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->set('event_id', $event_id);
    }
    public function view($id = null, $view_name = 'view') 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $eventComment = $this->EventComment->find('first', array(
            'conditions' => array(
                'EventComment.id' => $id,
                'EventComment.admin_suspend' => 0
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                )
            ) ,
            'recursive' => 0
        ));
        $this->set('eventComment', $eventComment);
        if ($view_name == 'view_ajax' and empty($eventComment)) {
            $this->Session->setFlash(__l('Review has been auto suspended.') , 'default', null, 'error');
        } elseif ($view_name == 'view_ajax') {
            $this->Session->setFlash(__l('Review has been added') , 'default', null, 'success');
        }
        $this->render($view_name);
    }
    public function delete($id = null) 
    {
        $event_slug = $this->EventComment->find('first', array(
            'conditions' => array(
                'EventComment.id = ' => $id
            ) ,
            'contain' => array(
                'Event' => array(
                    'fields' => array(
                        'Event.slug',
                    ) ,
                )
            ) ,
            'recursive' => 1,
        ));
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventComment->delete($id)) {
            $this->Session->setFlash(__l('Review deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'events',
                'action' => 'view',
                $event_slug['Event']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Event Comments');
        $conditions = array();
        if (!empty($this->request->params['named']['user_event_comment']) || !empty($this->request->params['named']['user'])) {
            $eventconditions = array();
            if (!empty($this->request->params['named']['user'])) {
                $eventconditions['User.id'] = $this->request->params['named']['user'];
            } else {
                $eventconditions['User.username'] = $this->request->params['named']['user_event_comment'];
            }
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $eventconditions,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $user['User']['id'];
        } elseif (!empty($this->request->params['named']['event_comment'])) {
            $event = $this->{$this->modelClass}->Event->find('first', array(
                'conditions' => array(
                    'Event.slug' => $this->request->params['named']['event_comment']
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
            $this->pageTitle.= sprintf(__l(' - Event - %s') , $event['Event']['title']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['EventComment.is_active'] = 1;
                $conditions['EventComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['EventComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['EventComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['EventComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->EventComment->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
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
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                    'fields' => array(
                        'Event.title',
                        'Event.slug'
                    )
                )
            ) ,
            'order' => array(
                'EventComment.id' => 'desc'
            )
        );
        $this->set('active', $this->EventComment->find('count', array(
            'conditions' => array(
                'EventComment.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->EventComment->find('count', array(
            'conditions' => array(
                'EventComment.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->EventComment->find('count', array(
            'conditions' => array(
                'EventComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->EventComment->find('count', array(
            'conditions' => array(
                'EventComment.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->EventComment->moreActions;
        $this->set(compact('moreActions'));
        $this->set('eventComments', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Event Comment');
        if (!empty($this->request->data)) {
            $this->EventComment->create();
            if ($this->EventComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Review has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Review could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->EventComment->User->find('list');
        $events = $this->EventComment->Event->find('list');
        $this->set(compact('users', 'events'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Event Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->EventComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Review has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Review could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EventComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EventComment']['id'];
        $users = $this->EventComment->User->find('list');
        $events = $this->EventComment->Event->find('list');
        $this->set(compact('users', 'events'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventComment->delete($id)) {
            $this->Session->setFlash(__l('Review deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>