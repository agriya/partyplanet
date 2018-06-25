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
class EventUsersController extends AppController
{
    public $name = 'EventUsers';
    public function index($event_id = null, $type = null) 
    {
        $this->pageTitle = __l('Event Users');
        $conditions = array();
        if (!empty($event_id)) {
            $conditions['EventUser.event_id'] = $event_id;
        }
        if (!empty($this->request->params['named']['user_id'])) {
            $conditions['EventUser.user_id'] = $this->request->params['named']['user_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'EventUser.id',
                'EventUser.created',
                'EventUser.user_id',
                'EventUser.event_id',
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
                ) ,
                'Event' => array(
                    'fields' => array(
                        'Event.title',
                        'Event.slug',
                    ) ,
                ) ,
            ) ,
            'order' => 'EventUser.id DESC',
            'recursive' => 1,
        );
        if (!empty($type) and $type == 'basic') {
            $this->set('type', $type);
        }
        $this->set('eventUsers', $this->paginate());
    }
    public function add($event_id = null) 
    {
        if (!empty($event_id)) {
            $is_success = $valid = false;
            $this->request->data['EventUser']['user_id'] = $this->Auth->user('id');
            $this->request->data['EventUser']['event_id'] = $event_id;
            $this->EventUser->create();
            $event = $this->EventUser->Event->find('first', array(
                'conditions' => array(
                    'Event.id' => $event_id,
                ) ,
                'fields' => array(
                    'Event.slug',
                    'Event.start_date',
                    'Event.end_date',
                    'Event.event_type_id',
                    'Event.is_repeat_until_never',
                    'Event.repeat_end_date'
                ) ,
                'recursive' => -1,
            ));
            if ($event['Event']['event_type_id'] == 1) {
                if (strtotime($event['Event']['end_date']) > strtotime('now')) {
                    $valid = true;
                }
            } else {
                if ($event['Event']['is_repeat_until_never'] == 0) {
                    $valid = true;
                } else {
                    if (strtotime($event['Event']['repeat_end_date']) > strtotime('now')) {
                        $valid = true;
                    }
                }
            }
            if ($valid) {
                $this->EventUser->set($this->request->data);
                $this->EventUser->validates();
                $this->EventUser->create();
                if ($this->EventUser->save($this->request->data)) {
                    if (!$this->RequestHandler->isAjax()) {
                        $this->Session->setFlash(__l('You are joined to the event attending list') , 'default', null, 'success');
                    }
                    $is_success = true;
                } else {
                    $this->Session->setFlash($this->EventUser->validationErrors['event_id'], 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You cannot join the past event') , 'default', null, 'success');
            }
        } else {
            $this->Session->setFlash(__l('Error in adding to the event members list') , 'default', null, 'success');
        }
        if ($this->RequestHandler->isAjax()) {
            if ($is_success) {
                echo "added|" . Router::url(array(
                    'controller' => 'event_users',
                    'action' => 'delete',
                    $this->EventUser->getInsertID() ,
                    $event['Event']['slug']
                ) , true);
            }
            exit;
        } else {
            $this->redirect(array(
                'controller' => 'events',
                'action' => 'view',
                $event['Event']['slug']
            ));
        }
    }
    public function delete($id = null, $event_slug = null) 
    {
        $event = $this->EventUser->Event->find('first', array(
            'conditions' => array(
                'Event.slug' => $event_slug
            ) ,
            'fields' => array(
                'Event.id',
            ) ,
            'recursive' => -1,
        ));
        if (is_null($id) and empty($event)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventUser->delete($id, false)) {
            if ($this->RequestHandler->isAjax()) {
                echo "removed|" . Router::url(array(
                    'controller' => 'event_users',
                    'action' => 'add',
                    $event['Event']['id']
                ) , true);
                exit;
            } else {
                $this->Session->setFlash(__l('You are removed from the event attending list') , 'default', null, 'success');
            }
            $this->redirect(array(
                'controller' => 'events',
                'action' => 'view',
                $event_slug
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('User Joined Events');
        $this->EventUser->recursive = 0;
        $conditions = array();
        if (isset($this->request->params['named']['event'])) {
            $conditions['EventUser.event_id'] = $this->request->params['named']['event'];
        }
        if (isset($this->request->params['named']['user'])) {
            $conditions['EventUser.user_id'] = $this->request->params['named']['user'];
        }
        $this->paginate = array(
            'conditions' => $conditions
        );
        $this->set('eventUsers', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventUser->delete($id)) {
            $this->Session->setFlash(__l('Event user deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>