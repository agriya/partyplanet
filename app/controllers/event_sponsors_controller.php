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
class EventSponsorsController extends AppController
{
    public $name = 'EventSponsors';
    public $uses = array(
        'EventSponsor',
        'Attachment',
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'EventSponsor.makeActive',
            'EventSponsor.makeInactive',
            'EventSponsor.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Event Sponsors');
        $conditions = array();
        //Redirect Get to namedparams
        $this->_redirectGET2Named(array(
            'keyword',
            'user'
        ));
        $this->request->data['EventSponsor'] = array(
            'keyword' => (!empty($this->request->params['named']['keyword'])) ? $this->request->params['named']['keyword'] : '',
            'user' => (!empty($this->request->params['named']['user'])) ? $this->request->params['named']['user'] : ''
        );
        if (!empty($this->request->data['EventSponsor']['keyword'])) {
            $conditions['OR'] = array(
                'EventSponsor.name Like' => '%' . $this->request->data['EventSponsor']['keyword'] . '%',
                'EventSponsor.description Like' => '%' . $this->request->data['EventSponsor']['keyword'] . '%'
            );
        }
        if (!empty($this->request->data['EventSponsor']['user'])) {
            $conditions['User.id'] = $this->request->data['EventSponsor']['user'];
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['EventSponsor.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['EventSponsor.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $eventSponsor = $this->EventSponsor->find('first', array(
            'conditions' => array(
                $conditions
            ) ,
            'fields' => array(
                'EventSponsor.id',
                'EventSponsor.created',
                'EventSponsor.user_id',
                'EventSponsor.name',
                'EventSponsor.slug',
                'EventSponsor.description',
                'EventSponsor.is_active',
                'User.username',
                'Attachment.id',
                'Attachment.dir',
            ) ,
            'contains' => array(
                'Event',
            ) ,
            'recursive' => 1,
        ));
        $this->EventSponsor->recursive = 0;
        $this->EventSponsor->order = array(
            'EventSponsor.id' => 'DESC'
        );
		$this->paginate = array(
			'conditions' => $conditions,
			'order' => 'EventSponsor.id DESC',
            'recursive' => 1,
		);
        $this->set('eventSponsors', $this->paginate());
		
		$this->set('active_count', $this->EventSponsor->find('count', array(
            'conditions' => array(
                'EventSponsor.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->EventSponsor->find('count', array(
            'conditions' => array(
                'EventSponsor.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->EventSponsor->find('count'));
		
        $users = $this->EventSponsor->User->find('list');
        $this->set('users', $users);
        $moreActions = $this->EventSponsor->moreActions;
        $this->set(compact('moreActions'));
        if (!empty($this->request->params['requested'])) {
            $this->set('requested', $this->request->params['requested']);
        }
    }
    public function admin_add($event_id = null) 
    {
        $this->pageTitle = __l('Add Event Sponsor');
        $this->Attachment->Behaviors->attach('ImageUpload', Configure::read('eventsponsor.file'));
        if (!empty($this->request->data)) {
            $this->request->data['EventSponsor']['user_id'] = $this->Auth->user('id');
            $this->EventSponsor->create();
            $this->Attachment->set($this->request->data);
            $this->EventSponsor->set($this->request->data);
            if ($this->EventSponsor->validates() &$this->Attachment->validates()) {
                if ($this->EventSponsor->save($this->request->data)) {
                    $event_sponsor_id = $this->EventSponsor->getLastInsertId();
                    // if image is availble
                    if (!empty($this->request->data['Attachment']['filename']['name'])) {
                        // Getting image Details
                        $image_info = getimagesize($this->request->data['Attachment']['filename']['tmp_name']);
                        $this->request->data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                        $this->request->data['Attachment']['filename']['type'] = $image_info['mime'];
                        $this->Attachment->set($this->request->data);
                        $this->Attachment->create();
                        $this->request->data['Attachment']['class'] = $this->modelClass;
                        $this->request->data['Attachment']['description'] = 'EventSponsorImage';
                        $this->request->data['Attachment']['foreign_id'] = $event_sponsor_id;
                        if ($this->Attachment->save($this->request->data['Attachment'])) {
                            $this->Session->setFlash(__l('Event sponsor with image has been added') , 'default', null, 'success');
                        } else {
                            $this->Session->setFlash(__l('Event sponsor has been added') , 'default', null, 'success');
                            $this->redirect(array(
                                'action' => 'add'
                            ));
                        }
                        $this->redirect(array(
                            'action' => 'index'
                        ));
                    }
                    $this->Session->setFlash(__l('Event sponsor has been added') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $this->Session->setFlash(__l('Event sponsor could not be added. please, try again.') , 'default', null, 'error');
                }
            }
        }
        $users = $this->EventSponsor->User->find('list', array(
            'conditions' => array(
                'User.is_active = ' => 1
            )
        ));
        $this->set('users', $users);
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Event Sponsor');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $sponsor = $this->EventSponsor->find('first', array(
                'conditions' => array(
                    'EventSponsor.id' => $id
                ) ,
                'contain' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir'
                        )
                    ) ,
                    'Event' => array(
                        'fields' => array(
                            'Event.id',
                            'Event.slug',
                        )
                    ) ,
                ) ,
                'recursive' => 0
            ));
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                if (!empty($sponsor)) {
                    $this->request->data['EventSponsor']['id'] = $sponsor['EventSponsor']['id'];
                    if (!empty($sponsor['Attachment']['id'])) {
                        $this->request->data['Attachment']['id'] = $sponsor['Attachment']['id'];
                    }
                }
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                $this->Attachment->Behaviors->attach('ImageUpload', Configure::read('eventsponsor.file'));
                $this->Attachment->set($this->request->data);
            }
            $this->request->data['EventSponsor']['user_id'] = $this->Auth->user('id');
            $this->EventSponsor->set($this->request->data);
            if ($this->EventSponsor->validates() &$this->Attachment->validates()) {
                $this->EventSponsor->save($this->request->data);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'EventSponsorImage';
                    $this->request->data['Attachment']['foreign_id'] = $this->request->data['EventSponsor']['id'];
                    $this->Attachment->save($this->request->data['Attachment']);
                }
                $this->Session->setFlash(__l('Event Sponsor has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'event_sponsors',
                    'action' => 'index',
                ));
            } else {
                $this->Session->setFlash(__l('Event Sponsor could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EventSponsor->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EventSponsor']['name'];
        $this->set(compact('events'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventSponsor->delete($id)) {
            $this->Session->setFlash(__l('Event sponsor deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>