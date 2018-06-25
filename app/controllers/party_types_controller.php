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
class PartyTypesController extends AppController
{
    public $name = 'PartyTypes';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'PartyType.makeActive',
            'PartyType.makeInactive',
            'PartyType.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Party Types');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['PartyType']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['PartyType.name Like'] = '%' . $this->request->data['PartyType']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['PartyType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['PartyType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'PartyType.id desc'
        );
        $this->set('partyTypes', $this->paginate());
		$this->set('active_count', $this->PartyType->find('count', array(
            'conditions' => array(
                'PartyType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->PartyType->find('count', array(
            'conditions' => array(
                'PartyType.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->PartyType->find('count'));
        $moreActions = $this->PartyType->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Party Type');
        if (!empty($this->request->data)) {
            $this->PartyType->create();
            if ($this->PartyType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Party Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Party Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Party Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->PartyType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Party Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Party Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->PartyType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['PartyType']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PartyType->delete($id)) {
            $this->Session->setFlash(__l('Party Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>