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
class VideoRatingsController extends AppController
{
    public $name = 'VideoRatings';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_ratings')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($video_id = null, $rating = null) 
    {
        if (is_null($video_id) || is_null($rating)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $video = $this->VideoRating->Video->find('first', array(
            'conditions' => array(
                'Video.id' => $video_id
            ) ,
            'fields' => array(
                'Video.user_id',
                'Video.slug',
            ) ,
            'contain' => array(
                'VideoRating' => array(
                    'fields' => array(
                        'VideoRating.user_id'
                    ) ,
                    'conditions' => array(
                        'VideoRating.user_id' => $this->Auth->user('id')
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($video)) {
            // Find logged in user is owner of the video
            if ($video['Video']['user_id'] != $this->Auth->user('id')) {
                if (empty($video['VideoRating'])) {
                    $this->VideoRating->create();
                    $this->request->data['VideoRating']['user_id'] = $this->Auth->user('id');
                    $this->request->data['VideoRating']['rate'] = $rating;
                    $this->request->data['VideoRating']['video_id'] = $video_id;
                    $this->request->data['VideoRating']['ip_id'] =  $this->VideoRating->toSaveIp();
                    if ($this->VideoRating->save($this->request->data)) {
                        $this->VideoRating->Video->updateAll(array(
                            'Video.total_ratings' => 'Video.total_ratings + ' . $rating
                        ) , array(
                            'Video.id' => $video_id
                        ));
                        $this->Session->setFlash(__l('Rating has been added') , 'default', null, 'success');
                    } else {
                        $this->Session->setFlash(__l('Rating could not be added. Please, try again') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('You have already rated this video') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You cannot rating your video') , 'default', null, 'error');
            }
            if ($this->RequestHandler->isAjax()) {
                $video = $this->VideoRating->Video->find('first', array(
                    'conditions' => array(
                        'Video.id' => $video_id
                    ) ,
                    'fields' => array(
                        'Video.id',
                        'Video.total_ratings',
                        'Video.video_rating_count',
                    ) ,
                    'recursive' => -1
                ));
                $this->set('video', $video);
            } else {
                $this->redirect(array(
                    'controller' => 'videos',
                    'action' => 'view',
                    $video['Video']['slug']
                ));
            }
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Video Ratings');
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
        if (!empty($this->request->params['named']['username'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['username']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $user['User']['id'];
            $this->pageTitle.= sprintf(__l(' - User - %s') , $user['User']['username']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoRating.created) <= '] = 0;
            $this->pageTitle.= __l(' - Rated today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoRating.created) <= '] = 7;
            $this->pageTitle.= __l(' - Rated in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoRating.created) <= '] = 30;
            $this->pageTitle.= __l(' - Rated in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['VideoRating']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->VideoRating->recursive = 0;
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
                        'Video.slug',
                        'Video.default_thumbnail_id',
                    ) ,
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height',
                        )
                    ) ,
                    'Thumbnail',
                )
            ) ,
            'order' => array(
                'VideoRating.id' => 'desc'
            )
        );
        if (isset($this->request->data['VideoRating']['q'])) {
            //$this->paginate['search'] = $this->request->data['VideoRating']['q'];
            
        }
        $this->set('videoRatings', $this->paginate());
        $moreActions = $this->VideoRating->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoRating->delete($id)) {
            $this->Session->setFlash(__l('Video Rating deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Video Flag Category');
        if (!empty($this->request->data)) {
            $this->VideoRating->create();
            if ($this->VideoRating->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Rating has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Video Rating could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
}
?>