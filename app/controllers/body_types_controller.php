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
class BodyTypesController extends AppController
{
    public $name = 'BodyTypes';
    public function admin_index() 
    {
        $this->pageTitle = __l('Body Types');
		$conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['BodyType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['BodyType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
		$this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'BodyType.id desc'
        );
        $this->set('bodyTypes', $this->paginate());
		$this->set('active_count', $this->BodyType->find('count', array(
            'conditions' => array(
                'BodyType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->BodyType->find('count', array(
            'conditions' => array(
                'BodyType.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->BodyType->find('count'));
        $moreActions = $this->BodyType->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Body Type');
        if (!empty($this->request->data)) {
            $this->BodyType->create();
            if ($this->BodyType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Body Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Body Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Body Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->BodyType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Body Type has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Body Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->BodyType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['BodyType']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BodyType->delete($id)) {
            $this->Session->setFlash(__l('Body Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>