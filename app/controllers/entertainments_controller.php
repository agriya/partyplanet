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
class EntertainmentsController extends AppController
{
    public $name = 'Entertainments';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Entertainment.makeActive',
            'Entertainment.makeInactive',
            'Entertainment.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Entertainments');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['Entertainment']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['Entertainment.name Like'] = '%' . $this->request->data['Entertainment']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Entertainment.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Entertainment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'Entertainment.id desc'
        );
        $this->set('entertainments', $this->paginate());
		$this->set('active_count', $this->Entertainment->find('count', array(
            'conditions' => array(
                'Entertainment.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->Entertainment->find('count', array(
            'conditions' => array(
                'Entertainment.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->Entertainment->find('count'));
        $moreActions = $this->Entertainment->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Entertainment');
        if (!empty($this->request->data)) {
            $this->Entertainment->create();
            if ($this->Entertainment->save($this->request->data)) {
                $this->Session->setFlash(__l(' Entertainment has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Entertainment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $partyPlanners = $this->Entertainment->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Entertainment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->Entertainment->save($this->request->data)) {
                $this->Session->setFlash(__l(' Entertainment has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Entertainment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Entertainment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Entertainment']['name'];
        $partyPlanners = $this->Entertainment->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Entertainment->delete($id)) {
            $this->Session->setFlash(__l('Entertainment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>