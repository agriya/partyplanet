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
class MaritalStatusesController extends AppController
{
    public $name = 'MaritalStatuses';
    public function admin_index() 
    {
        $this->pageTitle = __l('Marital Statuses');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['MaritalStatus']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['MaritalStatus.name Like'] = '%' . $this->request->data['MaritalStatus']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['MaritalStatus.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['MaritalStatus.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'MaritalStatus.id desc'
        );
        $this->set('maritalStatuses', $this->paginate());
		$this->set('active_count', $this->MaritalStatus->find('count', array(
            'conditions' => array(
                'MaritalStatus.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->MaritalStatus->find('count', array(
            'conditions' => array(
                'MaritalStatus.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->MaritalStatus->find('count'));
        $moreActions = $this->MaritalStatus->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Marital Status');
        if (!empty($this->request->data)) {
            $this->MaritalStatus->create();
            if ($this->MaritalStatus->save($this->request->data)) {
                $this->Session->setFlash(__l(' Marital Status has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Marital Status could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Marital Status');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->MaritalStatus->save($this->request->data)) {
                $this->Session->setFlash(__l(' Marital Status has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Marital Status could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->MaritalStatus->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['MaritalStatus']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->MaritalStatus->delete($id)) {
            $this->Session->setFlash(__l('Marital Status deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>