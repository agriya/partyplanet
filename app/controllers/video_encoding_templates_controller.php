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
class VideoEncodingTemplatesController extends AppController
{
    public $name = 'VideoEncodingTemplates';
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
        $this->pageTitle = __l('Video Encoding Templates');
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
        $this->VideoEncodingTemplate->recursive = -1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'VideoEncodingTemplate.id',
                'VideoEncodingTemplate.name',
                'VideoEncodingTemplate.is_active'
            ) ,
            'order' => array(
                'VideoEncodingTemplate.id' => 'desc'
            )
        );
        $this->set('videoEncodingTemplates', $this->paginate());
        $filters = $this->VideoEncodingTemplate->isFilterOptions;
        $moreActions = $this->VideoEncodingTemplate->moreActions;
        $this->set(compact('moreActions', 'filters'));
        $this->set('pending', $this->VideoEncodingTemplate->find('count', array(
            'conditions' => array(
                'VideoEncodingTemplate.is_active' => 0
            )
        )));
        $this->set('approved', $this->VideoEncodingTemplate->find('count', array(
            'conditions' => array(
                'VideoEncodingTemplate.is_active' => 1
            )
        )));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Video Encoding Template');
        if (!empty($this->request->data)) {
            $this->VideoEncodingTemplate->create();
            if ($this->VideoEncodingTemplate->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Encoding Template has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Encoding Template could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Video Encoding Template');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VideoEncodingTemplate->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Encoding Template has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l('Video Encoding Template could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VideoEncodingTemplate->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VideoEncodingTemplate']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoEncodingTemplate->delete($id)) {
            $this->Session->setFlash(__l('Video Encoding Template deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>