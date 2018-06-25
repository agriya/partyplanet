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
class VenueTypesController extends AppController
{
    public $name = 'VenueTypes';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'VenueType.makeActive',
            'VenueType.makeInactive',
            'VenueType.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Venue Types');
        $conditions = array();
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'VenueType.id desc'
        );
		$this->set('venueTypes', $this->paginate());
		$this->set('active_count', $this->VenueType->find('count', array(
            'conditions' => array(
                'VenueType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VenueType->find('count', array(
            'conditions' => array(
                'VenueType.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VenueType->find('count'));
		
        $moreActions = $this->VenueType->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Venue Type');
        if (!empty($this->request->data)) {
            $this->VenueType->create();
            if ($this->VenueType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Venue Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Venue Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VenueType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Venue Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'venue_types',
                    'action' => 'admin_index'
                ));
            } else {
                $this->Session->setFlash(__l(' Venue Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VenueType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueType']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueType->delete($id)) {
            $this->Session->setFlash(__l('Venue Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>