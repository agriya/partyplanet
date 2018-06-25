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
class EventScenesController extends AppController
{
    public $name = 'EventScenes';
    public function admin_index() 
    {
        $this->pageTitle = __l('Event Scenes');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['EventScene']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['EventScene.name Like'] = '%' . $this->request->data['EventScene']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['EventScene.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['EventScene.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'EventScene.id desc'
        );
        $this->set('eventScenes', $this->paginate());
		$this->set('active_count', $this->EventScene->find('count', array(
            'conditions' => array(
                'EventScene.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->EventScene->find('count', array(
            'conditions' => array(
                'EventScene.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->EventScene->find('count'));
        $moreActions = $this->EventScene->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Event Scene');
        if (!empty($this->request->data)) {
            $this->EventScene->create();
            if ($this->EventScene->save($this->request->data)) {
                $this->Session->setFlash(__l(' Event Scene has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Event Scene could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->set(compact('events'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Event Scene');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->EventScene->save($this->request->data)) {
                $this->Session->setFlash(__l(' Event Scene has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Event Scene could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EventScene->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EventScene']['name'];
        $this->set(compact('events'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventScene->delete($id)) {
            $this->Session->setFlash(__l('Event Scene deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>