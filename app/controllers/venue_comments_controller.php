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
class VenueCommentsController extends AppController
{
    public $name = 'VenueComments';
    public function beforeFilter() 
    {
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_venue_comment_on_system_flag')) {
            $this->VenueComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($venue_id = null) 
    {
        $this->disableCache();
        $this->pageTitle = __l('Venue Comments');
        $this->set('venue_id', $venue_id);
        $conditions = array();
        $conditions['VenueComment.is_active'] = 1;
        $conditions['VenueComment.admin_suspend'] = 0;
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
        }
        if (!empty($venue_id)) {
            $conditions['VenueComment.venue_id'] = $venue_id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
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
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.user_id'
                    )
                )
            ) ,
            'order' => array(
                'VenueComment.id' => 'desc',
            ) ,
            'limit' => 5,
            'recursive' => 1,
        );
        $this->set('venueComments', $this->paginate());
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Venue Comment');
        if (!empty($this->request->data)) {
            $this->request->data['VenueComment']['user_id'] = $this->Auth->user('id') ? $this->Auth->user('id') : '0';
            $this->request->data['VenueComment']['ip_id'] = $this->VenueComment->toSaveIp();
            $this->VenueComment->create();
            if ($this->VenueComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Venue comment has been added') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('view', $this->VenueComment->getLastInsertId() , 'view_ajax');
                } else {
                    $this->redirect(array(
                        'controller' => 'venues',
                        'action' => 'view',
                        $this->request->data['VenueComment']['venue_slug']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Venue comment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function view($id = null, $view_name = 'view') 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $venueComment = $this->VenueComment->find('first', array(
            'conditions' => array(
                'VenueComment.id' => $id
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
            'recursive' => 2
        ));
        $this->set('venueComment', $venueComment);
        $this->render($view_name);
    }
    public function delete($id = null) 
    {
        $venue_slug = $this->VenueComment->find('first', array(
            'conditions' => array(
                'VenueComment.id = ' => $id
            ) ,
            'contain' => array(
                'Venue' => array(
                    'fields' => array(
                        'Venue.slug',
                    ) ,
                )
            ) ,
            'recursive' => 1,
        ));
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueComment->delete($id)) {
            $this->Session->setFlash(__l('Venue comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'venues',
                'action' => 'view',
                $venue_slug['Venue']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $conditions = array();
        if (!empty($this->request->params['named']['user_venue_comment']) || !empty($this->request->params['named']['user'])) {
            $venueconditions = array();
            if (!empty($this->request->params['named']['user'])) {
                $venueconditions['User.id'] = $this->request->params['named']['user'];
            } else {
                $venueconditions['User.username'] = $this->request->params['named']['user_venue_comment'];
            }
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => $venueconditions,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['VenueComment.user_id'] = $user['User']['id'];
        } elseif (!empty($this->request->params['named']['venue_comment'])) {
            $venue = $this->{$this->modelClass}->Venue->find('first', array(
                'conditions' => array(
                    'Venue.slug' => $this->request->params['named']['venue_comment']
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
            $this->pageTitle = __l('Venue Reviews');
        } else {
            $this->pageTitle = __l('Venue Comments');
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueComment.is_active'] = 1;
                $conditions['VenueComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['VenueComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['VenueComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->VenueComment->recursive = 0;
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
                  'Venue' => array(
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
                        'Venue.name',
                        'Venue.slug'
                    )
                ) ,
            ) ,
            'order' => array(
                'VenueComment.id' => 'desc'
            )
        );
        $this->set('active', $this->VenueComment->find('count', array(
            'conditions' => array(
                'VenueComment.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->VenueComment->find('count', array(
            'conditions' => array(
                'VenueComment.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->VenueComment->find('count', array(
            'conditions' => array(
                'VenueComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->VenueComment->find('count', array(
            'conditions' => array(
                'VenueComment.admin_suspend' => 1,
            )
        )));
        $this->set('pageTitle', $this->pageTitle);
        $moreActions = $this->VenueComment->moreActions;
        $this->set(compact('moreActions'));
        $this->set('venueComments', $this->paginate());
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VenueComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Venue comment has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Venue comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VenueComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueComment']['id'];
        $users = $this->VenueComment->User->find('list');
        $this->set(compact('users', 'venues'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueComment->delete($id)) {
            $this->Session->setFlash(__l('Venue comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>