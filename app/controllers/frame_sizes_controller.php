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
class FrameSizesController extends AppController
{
    public $name = 'FrameSizes';
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
        $this->pageTitle = __l('Frame Sizes');
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
        $this->FrameSize->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'FrameSize.id',
                'FrameSize.name',
                'FrameSize.is_active'
            ) ,
            'order' => array(
                'FrameSize.id' => 'desc'
            )
        );
        $this->set('frameSizes', $this->paginate());
        $filters = $this->FrameSize->isFilterOptions;
        $moreActions = $this->FrameSize->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('pending', $this->FrameSize->find('count', array(
            'conditions' => array(
                'FrameSize.is_active' => 0
            )
        )));
        $this->set('approved', $this->FrameSize->find('count', array(
            'conditions' => array(
                'FrameSize.is_active' => 1
            )
        )));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Frame Size');
        if (!empty($this->request->data)) {
            $this->FrameSize->create();
            if ($this->FrameSize->save($this->request->data)) {
                $this->Session->setFlash(__l('Frame Size has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Frame Size could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Frame Size');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->FrameSize->save($this->request->data)) {
                $this->Session->setFlash(__l('Frame Size has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Frame Size could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->FrameSize->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['FrameSize']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FrameSize->delete($id)) {
            $this->Session->setFlash(__l('Frame Size deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update() 
    {
        if (!empty($this->request->data['FrameSize'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $frameSizeIds = array();
            foreach($this->request->data['FrameSize'] as $frame_size_id => $is_checked) {
                if ($is_checked['id']) {
                    $frameSizeIds[] = $frame_size_id;
                }
            }
            if ($actionid && !empty($frameSizeIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->FrameSize->updateAll(array(
                        'FrameSize.is_active' => 0
                    ) , array(
                        'FrameSize.id' => $frameSizeIds
                    ));
                    $this->Session->setFlash(__l('Checked frame sizes has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->FrameSize->updateAll(array(
                        'FrameSize.is_active' => 1
                    ) , array(
                        'FrameSize.id' => $frameSizeIds
                    ));
                    $this->Session->setFlash(__l('Checked frame sizes has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $this->FrameSize->deleteAll(array(
                        'FrameSize.id' => $frameSizeIds
                    ));
                    $this->Session->setFlash(__l('Checked frame sizes has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
}
?>