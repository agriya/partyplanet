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
class BitstreamFiltersController extends AppController
{
    public $name = 'BitstreamFilters';
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
        $this->pageTitle = __l('Bitstream Filters');
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
        $this->BitstreamFilter->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'BitstreamFilter.id',
                'BitstreamFilter.name',
                'BitstreamFilter.is_active'
            ) ,
            'order' => array(
                'BitstreamFilter.id' => 'desc'
            )
        );
        $this->set('bitstreamFilters', $this->paginate());
        $filters = $this->BitstreamFilter->isFilterOptions;
        $moreActions = $this->BitstreamFilter->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('pending', $this->BitstreamFilter->find('count', array(
            'conditions' => array(
                'BitstreamFilter.is_active' => 0
            )
        )));
        $this->set('approved', $this->BitstreamFilter->find('count', array(
            'conditions' => array(
                'BitstreamFilter.is_active' => 1
            )
        )));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Bitstream Filter');
        if (!empty($this->request->data)) {
            $this->BitstreamFilter->create();
            if ($this->BitstreamFilter->save($this->request->data)) {
                $this->Session->setFlash(__l('Bitstream Filter has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Bitstream Filter could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Bitstream Filter');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->BitstreamFilter->save($this->request->data)) {
                $this->Session->setFlash(__l('Bitstream Filter has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Bitstream Filter could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->BitstreamFilter->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['BitstreamFilter']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BitstreamFilter->delete($id)) {
            $this->Session->setFlash(__l('Bitstream Filter deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>