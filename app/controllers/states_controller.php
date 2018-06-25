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
class StatesController extends AppController
{
    public $name = 'States';
    public function lst() 
    {
        $conditions = array(
            'State.is_approved' => '1',
        );
        if (!empty($this->request->params['named']['current_id'])) {
            $this->request->data['state_id'] = $this->request->params['named']['current_id'];
        }
        if (!empty($this->request->params['named']['name'])) {
            $conditions['State.country_id'] = $this->request->params['named']['name'];
        }
        $states = $this->State->find('list', array(
            'conditions' => $conditions
        ));
        $this->set('states', $states);
    }
    public function admin_index() 
    {
        $this->disableCache();
        $this->pageTitle = __l('States');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'filter_id',
            'q'
        ));
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['State']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['State']['name'] = $this->request->params['named']['q'];
        }
        if (!empty($this->request->data['State']['filter_id'])) {
            $conditions['State.is_approved'] = $this->request->data['State']['filter_id'];
            $this->request->params['named']['filter_id'] = $this->request->data['State']['filter_id'];
        }
        if (!empty($this->request->data['State']['name'])) {
            $conditions['State.name'] = $this->request->data['State']['name'];
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['State.is_approved'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['State.is_approved'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.name'
                    )
                )
            ) ,
            'fields' => array(
                'State.id',
                'State.name',
                'State.created',
                'State.is_approved',
            ) ,
            'recursive' => 1,
            'limit' => 15,
        );
        $this->set('states', $this->paginate());
		$this->set('active_count', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 0,
            )
        )));
        $this->set('total_count', $this->State->find('count'));
        $this->set('pending', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 0
            )
        )));
        $this->set('approved', $this->State->find('count', array(
            'conditions' => array(
                'State.is_approved = ' => 1
            )
        )));
        $filters = $this->State->isFilterOptions;
        $moreActions = $this->State->moreActions;
        $this->set(compact('filters', 'moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add State');
        if (!empty($this->request->data)) {
            $this->request->data['State']['is_approved'] = 1;
            $this->State->create();
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit State');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->State->save($this->request->data)) {
                $this->Session->setFlash(__l('State has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('State could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->State->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['State']['name'];
        $countries = $this->State->Country->find('list');
        $this->set(compact('countries'));
    }
    // To change approve/disapprove status by admin
    public function admin_update_status($id, $status) 
    {
        $this->State->id = $id;
        if ($status == 'disapprove') {
            $this->State->saveField('is_approved', 0);
        }
        if ($status == 'approve') {
            $this->State->saveField('is_approved', 1);
        }
        $this->redirect(array(
            'controller' => 'states',
            'action' => 'index'
        ));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->State->delete($id)) {
            $this->Session->setFlash(__l('State deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>