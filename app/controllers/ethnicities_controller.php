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
class EthnicitiesController extends AppController
{
    public $name = 'Ethnicities';
    public function admin_index() 
    {
        $this->pageTitle = __l('Ethnicities');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['Ethnicity']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['Ethnicity.name Like'] = '%' . $this->request->data['Ethnicity']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Ethnicity.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Ethnicity.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'Ethnicity.id desc'
        );
        $this->set('ethnicities', $this->paginate());
		$this->set('active_count', $this->Ethnicity->find('count', array(
            'conditions' => array(
                'Ethnicity.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->Ethnicity->find('count', array(
            'conditions' => array(
                'Ethnicity.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->Ethnicity->find('count'));
        $moreActions = $this->Ethnicity->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Ethnicity');
        if (!empty($this->request->data)) {
            $this->Ethnicity->create();
            if ($this->Ethnicity->save($this->request->data)) {
                $this->Session->setFlash(__l(' Ethnicity has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Ethnicity could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Ethnicity');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->Ethnicity->save($this->request->data)) {
                $this->Session->setFlash(__l(' Ethnicity has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'ethnicities',
                    'action' => 'admin_index'
                ));
            } else {
                $this->Session->setFlash(__l(' Ethnicity could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Ethnicity->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Ethnicity']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Ethnicity->delete($id)) {
            $this->Session->setFlash(__l('Ethnicity deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>