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
class CellProvidersController extends AppController
{
    public $name = 'CellProviders';
    public function admin_index() 
    {
        $this->pageTitle = __l('Cell Providers');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['CellProvider']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['CellProvider.name Like'] = '%' . $this->request->data['CellProvider']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['CellProvider.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['CellProvider.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'CellProvider.id desc'
        );
        $this->set('cellProviders', $this->paginate());
		$this->set('active_count', $this->CellProvider->find('count', array(
            'conditions' => array(
                'CellProvider.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->CellProvider->find('count', array(
            'conditions' => array(
                'CellProvider.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->CellProvider->find('count'));
        $moreActions = $this->CellProvider->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Cell Provider');
        if (!empty($this->request->data)) {
            $this->CellProvider->create();
            if ($this->CellProvider->save($this->request->data)) {
                $this->Session->setFlash(__l(' Cell Provider has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Cell Provider could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Cell Provider');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->CellProvider->save($this->request->data)) {
                $this->Session->setFlash(__l(' Cell Provider has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Cell Provider could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->CellProvider->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['CellProvider']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->CellProvider->delete($id)) {
            $this->Session->setFlash(__l('Cell Provider deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>