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
class VideoFavoritesController extends AppController
{
    public $name = 'VideoFavorites';
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_favorites')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($video_id = null) 
    {
        if (is_null($video_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $video = $this->VideoFavorite->Video->find('first', array(
            'conditions' => array(
                'Video.id' => $video_id
            ) ,
            'fields' => array(
                'Video.slug',
            ) ,
            'contain' => array(
                'VideoFavorite' => array(
                    'fields' => array(
                        'VideoFavorite.user_id'
                    ) ,
                    'conditions' => array(
                        'VideoFavorite.user_id' => $this->Auth->user('id')
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($video)) {
            if (empty($video['VideoFavorite'])) {
                $this->VideoFavorite->create();
                $this->request->data['VideoFavorite']['user_id'] = $this->Auth->user('id');
                $this->request->data['VideoFavorite']['video_id'] = $video_id;
                $this->request->data['VideoFavorite']['ip_id'] =  $this->VideoFavorite->toSaveIp();
                if ($this->VideoFavorite->save($this->request->data)) {
                    $this->Session->setFlash(__l('Video has been added as favorite') , 'default', null, 'success');
                    if ($this->RequestHandler->isAjax()) {
                        $id = $this->VideoFavorite->id;
                        echo $id;
                        exit;
                    }
                } else {
                    $this->Session->setFlash(__l('Video could not be added as favorite. Please, try again') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You have already added this video as favorite') , 'default', null, 'error');
            }
            $this->redirect(array(
                'controller' => 'videos',
                'action' => 'view',
                $video['Video']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $videoFavorite = $this->VideoFavorite->find('first', array(
            'conditions' => array(
                'VideoFavorite.id' => $id
            ) ,
            'fields' => array(
                'Video.slug',
                'Video.id'
            ) ,
            'recursive' => 0
        ));
        if (empty($videoFavorite)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoFavorite->delete($id)) {
            $this->Session->setFlash(__l('Video Favorite deleted') , 'default', null, 'success');
            if ($this->RequestHandler->isAjax()) {
                echo $videoFavorite['Video']['id'];
                exit;
            }
            $this->redirect(array(
                'controller' => 'videos',
                'action' => 'view',
                $videoFavorite['Video']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Video Favorites');
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
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['VideoFavorite']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->VideoFavorite->recursive = 0;
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
                    ) ,
                    'Thumbnail',
                ),
				'Ip' => array(
					'City' => array(
						'fields' => array(
							'City.name',
						)
					) ,
					'State' => array(
						'fields' => array(
							'State.name',
						)
					) ,
					'Country' => array(
						'fields' => array(
							'Country.name',
							'Country.iso_alpha2',
						)
					) ,
					'Timezone' => array(
						'fields' => array(
							'Timezone.name',
						)
					) ,
					'fields' => array(
						'Ip.ip',
						'Ip.latitude',
						'Ip.longitude',
						'Ip.host',
					)
				)
            ) ,
            'order' => array(
                'VideoFavorite.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('videoFavorites', $this->paginate());
        $moreActions = $this->VideoFavorite->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoFavorite->delete($id)) {
            $this->Session->setFlash(__l('Video Favorite deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>