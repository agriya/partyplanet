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
class FeaturedVenueSubscriptionsController extends AppController
{
    public $name = 'FeaturedVenueSubscriptions';
    public function index() 
    {
        $this->pageTitle = __l('Featured Venue Subscriptions');
        $this->FeaturedVenueSubscription->recursive = 0;
        $this->set('featuredVenueSubscriptions', $this->paginate());
    }
    public function view($id = null) 
    {
        $this->pageTitle = __l('Featured Venue Subscription');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $featuredVenueSubscription = $this->FeaturedVenueSubscription->find('first', array(
            'conditions' => array(
                'FeaturedVenueSubscription.id = ' => $id
            ) ,
            'fields' => array(
                'FeaturedVenueSubscription.id',
                'FeaturedVenueSubscription.created',
                'FeaturedVenueSubscription.modified',
                'FeaturedVenueSubscription.name',
                'FeaturedVenueSubscription.amount',
                'FeaturedVenueSubscription.is_active',
            ) ,
            'recursive' => -1,
        ));
        if (empty($featuredVenueSubscription)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $featuredVenueSubscription['FeaturedVenueSubscription']['name'];
        $this->set('featuredVenueSubscription', $featuredVenueSubscription);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Featured Venue Subscription');
        if (!empty($this->request->data)) {
            $this->FeaturedVenueSubscription->create();
            if ($this->FeaturedVenueSubscription->save($this->request->data)) {
                $this->Session->setFlash(__l('Featured Venue Subscription has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Featured Venue Subscription could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Featured Venue Subscription');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->FeaturedVenueSubscription->save($this->request->data)) {
                $this->Session->setFlash(__l('Featured Venue Subscription has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Featured Venue Subscription could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->FeaturedVenueSubscription->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['FeaturedVenueSubscription']['name'];
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FeaturedVenueSubscription->delete($id)) {
            $this->Session->setFlash(__l('Featured Venue Subscription deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Featured Venue Subscriptions');
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['FeaturedVenueSubscription.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['FeaturedVenueSubscription.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'FeaturedVenueSubscription.id desc'
        );
        $this->set('featuredVenueSubscriptions', $this->paginate());
        $this->set('active_featured_venue_subscription', $this->FeaturedVenueSubscription->find('count', array(
            'conditions' => array(
                'FeaturedVenueSubscription.is_active = ' => 1,
            )
        )));
        $this->set('inactive_featured_venue_subscription', $this->FeaturedVenueSubscription->find('count', array(
            'conditions' => array(
                'FeaturedVenueSubscription.is_active = ' => 0,
            )
        )));
        $this->set('total_featured_venue_subscription', $this->FeaturedVenueSubscription->find('count'));
    }
    public function admin_view($id = null) 
    {
        $this->pageTitle = __l('Featured Venue Subscription');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $featuredVenueSubscription = $this->FeaturedVenueSubscription->find('first', array(
            'conditions' => array(
                'FeaturedVenueSubscription.id = ' => $id
            ) ,
            'fields' => array(
                'FeaturedVenueSubscription.id',
                'FeaturedVenueSubscription.created',
                'FeaturedVenueSubscription.modified',
                'FeaturedVenueSubscription.name',
                'FeaturedVenueSubscription.amount',
                'FeaturedVenueSubscription.is_active',
            ) ,
            'recursive' => -1,
        ));
        if (empty($featuredVenueSubscription)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $featuredVenueSubscription['FeaturedVenueSubscription']['name'];
        $this->set('featuredVenueSubscription', $featuredVenueSubscription);
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Featured Venue Subscription');
        if (!empty($this->request->data)) {
            $this->FeaturedVenueSubscription->create();
            if ($this->FeaturedVenueSubscription->save($this->request->data)) {
                $this->Session->setFlash(__l('Featured Venue Subscription has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Featured Venue Subscription could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Featured Venue Subscription');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->FeaturedVenueSubscription->save($this->request->data)) {
                $this->Session->setFlash(__l('Featured Venue Subscription has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Featured Venue Subscription could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->FeaturedVenueSubscription->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['FeaturedVenueSubscription']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FeaturedVenueSubscription->delete($id)) {
            $this->Session->setFlash(__l('Featured Venue Subscription deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>