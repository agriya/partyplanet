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
class VenueSponsorsController extends AppController
{
    public $name = 'VenueSponsors';
    public $uses = array(
        'VenueSponsor',
        'Attachment',
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'VenueSponsor.makeActive',
            'VenueSponsor.makeInactive',
            'VenueSponsor.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Venue Sponsors');
        $this->_redirectGET2Named(array(
            'keyword',
            'filter',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['filter'])) {
            $this->request->data['VenueSponsor']['filter'] = $this->request->params['named']['filter'];
        }
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['VenueSponsor']['keyword'] = $this->request->params['named']['keyword'];
        }
        if (!empty($this->request->data['VenueSponsor']['filter'])) {
            if ($this->request->data['VenueSponsor']['filter'] == ConstUserFilter::FirstName) {
                $conditions['VenueSponsor.first_name Like'] = '%' . $this->request->data['VenueSponsor']['keyword'] . '%';
            } else if ($this->request->data['VenueSponsor']['filter'] == ConstUserFilter::LastName) {
                $conditions['VenueSponsor.last_name Like'] = '%' . $this->request->data['VenueSponsor']['keyword'] . '%';
            } else if ($this->request->data['VenueSponsor']['filter'] == ConstUserFilter::EmailAddress) {
                $conditions['VenueSponsor.email Like'] = '%' . $this->request->data['VenueSponsor']['keyword'] . '%';
            }
            $this->request->params['named']['filter'] = $this->request->data['VenueSponsor']['filter'];
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueSponsor.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueSponsor.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'fields' => array(
                'VenueSponsor.id',
                'VenueSponsor.created',
                'VenueSponsor.user_id',
                'VenueSponsor.first_name',
                'VenueSponsor.last_name',
                'VenueSponsor.email',
                'VenueSponsor.phone',
                'VenueSponsor.slug',
                'VenueSponsor.description',
                'VenueSponsor.is_active',
                'User.username',
                'Attachment.id',
                'Attachment.dir',
            ) ,
            'contains' => array(
                'Venue',
            ) ,
            'recursive' => 0,
            'order' => 'VenueSponsor.id desc'
        );
        $this->set('venueSponsors', $this->paginate());
		
		$this->set('active_count', $this->VenueSponsor->find('count', array(
            'conditions' => array(
                'VenueSponsor.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VenueSponsor->find('count', array(
            'conditions' => array(
                'VenueSponsor.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VenueSponsor->find('count'));
		
        $users = $this->VenueSponsor->User->find('list');
        $moreActions = $this->VenueSponsor->moreActions;
        $filterActions = $this->VenueSponsor->isFilterOptions;
        $this->set(compact('moreActions', 'filterActions', 'users'));
        if (!empty($this->request->params['requested'])) {
            $this->set('requested', $this->request->params['requested']);
        }
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Venue Sponsor');
        $this->Attachment->Behaviors->attach('ImageUpload', Configure::read('eventsponsor.file'));
        if (!empty($this->request->data)) {
            $this->VenueSponsor->create();
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
            }
            $this->Attachment->set($this->request->data);
            $this->VenueSponsor->set($this->request->data);
            if ($this->VenueSponsor->validates() &$this->Attachment->validates()) {
                $this->VenueSponsor->save($this->request->data);
                $venue_sponsor_id = $this->VenueSponsor->getLastInsertId();
                // if image is availble
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    // Getting image Details
                    $this->Attachment->create();
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'VenueSponsorImage';
                    $this->request->data['Attachment']['foreign_id'] = $venue_sponsor_id;
                    if ($this->Attachment->save($this->request->data['Attachment'])) {
                        $this->Session->setFlash(__l('Venue sponsor with image has been added') , 'default', null, 'success');
                    } else {
                        $this->Session->setFlash(__l('Venue sponsor has been added') , 'default', null, 'success');
                    }
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Venue Sponsor could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->VenueSponsor->User->find('list');
        $this->set(compact('venues', 'users'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Sponsor');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                $this->Attachment->set($this->request->data);
            }
            $this->VenueSponsor->set($this->request->data);
            if ($this->VenueSponsor->validates() &$this->Attachment->validates()) {
                $this->VenueSponsor->save($this->request->data);
                $venue_sponsor_id = $this->request->data['VenueSponsor']['id'];
                $venuelist = $this->VenueSponsor->VenuesVenueSponsor->find('list', array(
                    'fields' => array(
                        'VenuesVenueSponsor.venue_id',
                    ) ,
                    'group' => 'VenuesVenueSponsor.venue_id',
                ));
                // if image is availble
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    // Getting image Details
                    $this->Attachment->create();
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'VenueSponsorImage';
                    $this->request->data['Attachment']['foreign_id'] = $venue_sponsor_id;
                    if ($this->Attachment->save($this->request->data['Attachment'])) {
                        $this->Session->setFlash(__l('Venue sponsor with image has been updated') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'venue_sponsors',
                            'action' => 'admin_index'
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Venue sponsor has been updated') , 'default', null, 'success');
                }
                $this->redirect(array(
                    'controller' => 'venue_sponsors',
                    'action' => 'admin_index'
                ));
            } else {
                $this->Session->setFlash(__l(' Venue Sponsor could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VenueSponsor->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueSponsor']['id'];
        $users = $this->VenueSponsor->User->find('list');
        $this->set(compact('venues', 'users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueSponsor->delete($id)) {
            $this->Session->setFlash(__l('Venue Sponsor deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>