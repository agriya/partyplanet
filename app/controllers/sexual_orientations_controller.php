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
class SexualOrientationsController extends AppController
{
    public $name = 'SexualOrientations';
    public function admin_index() 
    {
        $this->pageTitle = __l('Sexual Orientations');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['SexualOrientation']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['SexualOrientation.name Like'] = '%' . $this->request->data['SexualOrientation']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['SexualOrientation.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['SexualOrientation.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'SexualOrientation.id desc'
        );
        $moreActions = $this->SexualOrientation->moreActions;
        $this->set(compact('moreActions'));
        $this->set('sexualOrientations', $this->paginate());
		$this->set('active_count', $this->SexualOrientation->find('count', array(
            'conditions' => array(
                'SexualOrientation.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->SexualOrientation->find('count', array(
            'conditions' => array(
                'SexualOrientation.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->SexualOrientation->find('count'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Sexual Orientation');
        if (!empty($this->request->data)) {
            $this->SexualOrientation->create();
            if ($this->SexualOrientation->save($this->request->data)) {
                $this->Session->setFlash(__l(' Sexual Orientation has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Sexual Orientation could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Sexual Orientation');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->SexualOrientation->save($this->request->data)) {
                $this->Session->setFlash(__l(' Sexual Orientation has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Sexual Orientation could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->SexualOrientation->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['SexualOrientation']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->SexualOrientation->delete($id)) {
            $this->Session->setFlash(__l('Sexual Orientation deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>