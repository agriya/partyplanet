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
class VideoDownloadsController extends AppController
{
    public $name = 'VideoDownloads';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_downloads')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Video Downloads');
        $conditions = array();
        if (!empty($this->request->params['named']['video'])) {
            $video = $this->{$this->modelClass}->Video->find('first', array(
                'conditions' => array(
                    'Video.slug' => $this->request->params['named']['video']
                ) ,
                'fields' => array(
                    'Video.id',
                    'Video.title',
                    'Video.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($video)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Video.id'] = $video['Video']['id'];
            $this->pageTitle.= sprintf(__l(' - Video - %s') , $video['Video']['title']);
        }
        if (!empty($this->request->data['VideoDownload']['q'])) {
            $this->request->params['named']['q'] = $this->request->data['Country']['q'];
        }
        $this->VideoDownload->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'Video' => array(
                    'fields' => array(
                        'Video.title',
                        'Video.default_thumbnail_id',
                        'Video.slug'
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    )
                )
            ) ,
            'order' => array(
                'VideoDownload.id' => 'desc'
            ) ,
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('videoDownloads', $this->paginate());
        $moreActions = $this->VideoDownload->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoDownload->delete($id)) {
            $this->Session->setFlash(__l('Video Download deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>