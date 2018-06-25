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
class MusicTypesController extends AppController
{
    public $name = 'MusicTypes';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'MusicType.makeActive',
            'MusicType.makeInactive',
            'MusicType.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Music Types');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['MusicType']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['MusicType.name Like'] = '%' . $this->request->data['MusicType']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['MusicType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['MusicType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'MusicType.id desc'
        );
        $this->set('musicTypes', $this->paginate());
		$this->set('active_count', $this->MusicType->find('count', array(
            'conditions' => array(
                'MusicType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->MusicType->find('count', array(
            'conditions' => array(
                'MusicType.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->MusicType->find('count'));
        $moreActions = $this->MusicType->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Music Type');
        if (!empty($this->request->data)) {
            $this->MusicType->create();
            if ($this->MusicType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Music Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Music Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $events = $this->MusicType->Event->find('list');
        $users = $this->MusicType->User->find('list');
        $venues = $this->MusicType->Venue->find('list');
        $this->set(compact('events', 'users', 'venues'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Music Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->MusicType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Music Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Music Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->MusicType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['MusicType']['name'];
        $events = $this->MusicType->Event->find('list');
        $users = $this->MusicType->User->find('list');
        $venues = $this->MusicType->Venue->find('list');
        $this->set(compact('events', 'users', 'venues'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->MusicType->delete($id)) {
            $this->Session->setFlash(__l('Music Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>