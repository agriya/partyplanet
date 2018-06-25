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
class VideoFlagCategoriesController extends AppController
{
    public $name = 'VideoFlagCategories';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_flags')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id'
        ));
        $this->pageTitle = __l('Video Flag Categories');
        $conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VideoFlagCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VideoFlagCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->VideoFlagCategory->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'VideoFlagCategory.id',
                'VideoFlagCategory.name',
                'VideoFlagCategory.created',
                'VideoFlagCategory.video_flag_count',
                'VideoFlagCategory.is_active',
            ) ,
            'order' => array(
                'VideoFlagCategory.id' => 'desc'
            ) ,
        );
        $this->set('videoFlagCategories', $this->paginate());
        $filters = $this->VideoFlagCategory->isFilterOptions;
        $moreActions = $this->VideoFlagCategory->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('active_count', $this->VideoFlagCategory->find('count', array(
            'conditions' => array(
                'VideoFlagCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VideoFlagCategory->find('count', array(
            'conditions' => array(
                'VideoFlagCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VideoFlagCategory->find('count'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Video Flag Category');
        if (!empty($this->request->data)) {
            $this->VideoFlagCategory->create();
            if ($this->VideoFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Flag Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Flag Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Video Flag Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VideoFlagCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Flag Category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Flag Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VideoFlagCategory->find('first', array(
                'conditions' => array(
                    'VideoFlagCategory.id' => $id
                ) ,
                'recursive' => -1
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VideoFlagCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoFlagCategory->delete($id)) {
            $this->Session->setFlash(__l('Video FlagCategory deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>