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
class AspectRatiosController extends AppController
{
    public $name = 'AspectRatios';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id'
        ));
        $this->pageTitle = __l('Aspect Ratios');
        $conditions = array();
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data[$this->modelClass]['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data[$this->modelClass]['filter_id'])) {
            if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Active) {
                $conditions[$this->modelClass . '.is_active'] = 1;
                $this->pageTitle.= __l(' - Approved');
            } else if ($this->request->data[$this->modelClass]['filter_id'] == ConstMoreAction::Inactive) {
                $conditions[$this->modelClass . '.is_active'] = 0;
                $this->pageTitle.= __l(' - Unapproved');
            }
            $this->request->params['named']['filter_id'] = $this->request->data[$this->modelClass]['filter_id'];
        }
        $this->AspectRatio->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'AspectRatio.id',
                'AspectRatio.name',
                'AspectRatio.is_active'
            ) ,
            'order' => array(
                'AspectRatio.id' => 'desc'
            )
        );
        $this->set('aspectRatios', $this->paginate());
        $filters = $this->AspectRatio->isFilterOptions;
        $moreActions = $this->AspectRatio->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('pending', $this->AspectRatio->find('count', array(
            'conditions' => array(
                'AspectRatio.is_active' => 0
            )
        )));
        $this->set('approved', $this->AspectRatio->find('count', array(
            'conditions' => array(
                'AspectRatio.is_active' => 1
            )
        )));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Aspect Ratio');
        if (!empty($this->request->data)) {
            $this->AspectRatio->create();
            if ($this->AspectRatio->save($this->request->data)) {
                $this->Session->setFlash(__l('Aspect Ratio has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Aspect Ratio could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Aspect Ratio');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->AspectRatio->save($this->request->data)) {
                $this->Session->setFlash(__l('Aspect Ratio has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Aspect Ratio could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->AspectRatio->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['AspectRatio']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AspectRatio->delete($id)) {
            $this->Session->setFlash(__l('Aspect Ratio deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>