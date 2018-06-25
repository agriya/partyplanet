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
class BarServiceTypesController extends AppController
{
    public $name = 'BarServiceTypes';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'BarServiceType.makeActive',
            'BarServiceType.makeInactive',
            'BarServiceType.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Bar Service Types');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['BarServiceType']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['BarServiceType.name Like'] = '%' . $this->request->data['BarServiceType']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['BarServiceType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['BarServiceType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'BarServiceType.id desc'
        );
        $this->set('barServiceTypes', $this->paginate());
		$this->set('active_count', $this->BarServiceType->find('count', array(
            'conditions' => array(
                'BarServiceType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->BarServiceType->find('count', array(
            'conditions' => array(
                'BarServiceType.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->BarServiceType->find('count'));
        $moreActions = $this->BarServiceType->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Bar Service Type');
        if (!empty($this->request->data)) {
            $this->BarServiceType->create();
            if ($this->BarServiceType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Bar Service Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Bar Service Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $partyPlanners = $this->BarServiceType->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Bar Service Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->BarServiceType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Bar Service Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Bar Service Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->BarServiceType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['BarServiceType']['name'];
        $partyPlanners = $this->BarServiceType->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BarServiceType->delete($id)) {
            $this->Session->setFlash(__l('Bar Service Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>