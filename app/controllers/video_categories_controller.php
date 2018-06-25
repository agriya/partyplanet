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
class VideoCategoriesController extends AppController
{
    public $name = 'VideoCategories';
    public $uses = array(
        'VideoCategory',
        'Attachment',
        'User'
    );
    public function index() 
    {
        $this->pageTitle = __l('Video Categories');
        $this->set('setTitle', 'Categories');
        $this->VideoCategory->recursive = 0;
        $videoCategories = $this->paginate();
        $video_count_categories = $this->VideoCategory->Video->find('all', array(
            'conditions' => array(
                'Video.city_id' => $this->_prefixId
            ) ,
            'fields' => array(
                'Video.id',
                'count(Video.id) as count',
                'Video.video_category_id'
            ) ,
            'group' => array(
                'Video.video_category_id'
            ) ,
            'recursive' => -1
        ));
        foreach($videoCategories As $key => $videoCategorie) {
            foreach($video_count_categories As $video_count_categorie) {
                if ($video_count_categorie['Video']['video_category_id'] == $videoCategorie['VideoCategory']['id']) {
                    $videoCategories[$key]['VideoCategory']['count'] = $video_count_categorie[0]['count'];
                }
            }
        }
        $this->set('videoCategories', $videoCategories);
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Video Categories');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['VideoCategory']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['VideoCategory.name Like'] = '%' . $this->request->data['VideoCategory']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VideoCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VideoCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->VideoCategory->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
        );
        $videoCategories = $this->paginate();
		$this->set('active_count', $this->VideoCategory->find('count', array(
            'conditions' => array(
                'VideoCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VideoCategory->find('count', array(
            'conditions' => array(
                'VideoCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VideoCategory->find('count'));
        $video_count_categories = $this->VideoCategory->Video->find('all', array(
            'fields' => array(
                'Video.id',
                'count(Video.id) as count',
                'Video.video_category_id'
            ) ,
            'group' => array(
                'Video.video_category_id'
            ) ,
            'recursive' => -1
        ));
        foreach($videoCategories As $key => $videoCategorie) {
            foreach($video_count_categories As $video_coount_categorie) {
                if ($video_coount_categorie['Video']['video_category_id'] == $videoCategorie['VideoCategory']['id']) {
                    $videoCategories[$key]['VideoCategory']['count'] = $video_coount_categorie[0]['count'];
                }
            }
        }
        $this->set('videoCategories', $videoCategories);
        $moreActions = $this->VideoCategory->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $video_category_id = 0;
        $this->pageTitle = __l('Add Video Category');
        if (!empty($this->request->data)) {
            $this->VideoCategory->create();
            if ($this->VideoCategory->save($this->request->data)) {
                $video_category_id = $this->VideoCategory->getLastInsertId();
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                }
                $this->Attachment->set($this->request->data);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->Attachment->create();
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'VideoCategoryImage';
                    $this->request->data['Attachment']['foreign_id'] = $video_category_id;
                    $this->Attachment->save($this->request->data['Attachment']);
                }
                $this->Session->setFlash(__l('Video Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'video_categories',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Video Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VideoCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'video_categories',
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VideoCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VideoCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoCategory->delete($id)) {
            $this->Session->setFlash(__l('Video Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'video_categories',
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>