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
class VenueFeaturesController extends AppController
{
    public $name = 'VenueFeatures';
    public function admin_index() 
    {
        $this->pageTitle = __l('Venue Features');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['VenueFeature']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['VenueFeature.name Like'] = '%' . $this->request->data['VenueFeature']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueFeature.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueFeature.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'VenueFeature.id desc'
        );
        $this->set('venueFeatures', $this->paginate());
		$this->set('active_count', $this->VenueFeature->find('count', array(
            'conditions' => array(
                'VenueFeature.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VenueFeature->find('count', array(
            'conditions' => array(
                'VenueFeature.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VenueFeature->find('count'));
        $moreActions = $this->VenueFeature->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Venue Feature');
        if (!empty($this->request->data)) {
            $this->VenueFeature->create();
            if ($this->VenueFeature->save($this->request->data)) {
                $this->Session->setFlash(__l(' Venue Feature has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Venue Feature could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $venues = $this->VenueFeature->Venue->find('list');
        $this->set(compact('venues'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Feature');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VenueFeature->save($this->request->data)) {
                $this->Session->setFlash(__l(' Venue Feature has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Venue Feature could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VenueFeature->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueFeature']['name'];
        $venues = $this->VenueFeature->Venue->find('list');
        $this->set(compact('venues'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueFeature->delete($id)) {
            $this->Session->setFlash(__l('Venue Feature deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>