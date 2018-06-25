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
class PhotoFlagCategoriesController extends AppController
{
    public $name = 'PhotoFlagCategories';
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_flag')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id'
        ));
        $this->pageTitle = __l('Photo Flag Categories');
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['PhotoFlagCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['PhotoFlagCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->PhotoFlagCategory->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'PhotoFlagCategory.id',
                'PhotoFlagCategory.name',
                'PhotoFlagCategory.photo_flag_count',
                'PhotoFlagCategory.is_active'
            ) ,
            'order' => array(
                'PhotoFlagCategory.id' => 'desc'
            )
        );
        $this->set('photoFlagCategories', $this->paginate());
        $filters = $this->PhotoFlagCategory->isFilterOptions;
        $moreActions = $this->PhotoFlagCategory->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('active_count', $this->PhotoFlagCategory->find('count', array(
            'conditions' => array(
                'PhotoFlagCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->PhotoFlagCategory->find('count', array(
            'conditions' => array(
                'PhotoFlagCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->PhotoFlagCategory->find('count'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Photo Flag Category');
        if (!empty($this->request->data)) {
            $this->PhotoFlagCategory->create();
            if ($this->PhotoFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Photo Flag Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Photo Flag Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Photo Flag Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->PhotoFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Photo Flag Category has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Photo Flag Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->PhotoFlagCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['PhotoFlagCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoFlagCategory->delete($id)) {
            $this->Session->setFlash(__l('Photo Flag Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>