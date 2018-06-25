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
class AgeRequirmentsController extends AppController
{
    public $name = 'AgeRequirments';
    public function admin_index() 
    {
        $this->pageTitle = __l('Age Requirments');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed thr    ough named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['AgeRequirment']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['AgeRequirment.name Like'] = '%' . $this->request->data['AgeRequirment']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['AgeRequirment.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['AgeRequirment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'AgeRequirment.id desc'
        );
        $this->set('ageRequirments', $this->paginate());
		$this->set('active_count', $this->AgeRequirment->find('count', array(
            'conditions' => array(
                'AgeRequirment.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->AgeRequirment->find('count', array(
            'conditions' => array(
                'AgeRequirment.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->AgeRequirment->find('count'));
        $moreActions = $this->AgeRequirment->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Age Requirment');
        if (!empty($this->request->data)) {
            $this->AgeRequirment->create();
            if ($this->AgeRequirment->save($this->request->data)) {
                $this->Session->setFlash(__l('Age Requirment has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Age Requirment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Age Requirment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->AgeRequirment->save($this->request->data)) {
                $this->Session->setFlash(__l('Age Requirment has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Age Requirment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->AgeRequirment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['AgeRequirment']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AgeRequirment->delete($id)) {
            $this->Session->setFlash(__l('Age Requirment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>