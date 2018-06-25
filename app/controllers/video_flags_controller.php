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
class VideoFlagsController extends AppController
{
    public $name = 'VideoFlags';
    public $components = array(
        'RequestHandler'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_flags')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($id = null) 
    {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['VideoFlag']['video_id'])) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->VideoFlag->create();
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['VideoFlag']['user_id'] = $this->Auth->user('id');
            }
            $this->request->data['VideoFlag']['ip_id'] = $this->VideoFlag->toSaveIp();
            if ($this->VideoFlag->save($this->request->data)) {
                $this->Session->setFlash(__l('Video Flag has been added') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    $video = $this->VideoFlag->Video->find('first', array(
                        'conditions' => array(
                            'Video.id' => $this->request->data['VideoFlag']['video_id']
                        ) ,
                        'fields' => array(
                            'Video.slug',
                        ) ,
                        'recursive' => -1
                    ));
                    $this->redirect(array(
                        'controller' => 'videos',
                        'action' => 'view',
                        $video['Video']['slug'],
                        'admin' => false
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Video Flag could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['VideoFlag']['video_id'] = $id;
        }
        $video = $this->VideoFlag->Video->find('first', array(
            'conditions' => array(
                'Video.id' => $this->request->data['VideoFlag']['video_id']
            ) ,
            'fields' => array(
                'Video.slug',
            ) ,
            'recursive' => -1
        ));
        $this->set('url', Router::url(array(
            'controller' => 'videos',
            'action' => 'view',
            $video['Video']['slug'],
            'admin' => false
        ) , true));
        $videoFlagCategories = $this->VideoFlag->VideoFlagCategory->find('list', array(
            'conditions' => array(
                'VideoFlagCategory.is_active' => 1
            )
        ));
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->VideoFlag->User->find('list');
            $this->set(compact('users'));
        }
        $this->set(compact('videoFlagCategories'));
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Video Flags');
        $conditions = array();
        if (!empty($this->request->params['named']['category'])) {
            $videoFlagCategory = $this->{$this->modelClass}->VideoFlagCategory->find('first', array(
                'conditions' => array(
                    'VideoFlagCategory.id' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'VideoFlagCategory.id',
                    'VideoFlagCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($videoFlagCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['VideoFlagCategory.id'] = $videoFlagCategory['VideoFlagCategory']['id'];
            $this->pageTitle.= sprintf(__l(' - Category - %s') , $videoFlagCategory['VideoFlagCategory']['name']);
        }
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoFlag.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoFlag.created) <= '] = 7;
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoFlag.created) <= '] = 30;
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['VideoFlag']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->VideoFlag->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'VideoFlagCategory' => array(
                    'fields' => array(
                        'VideoFlagCategory.name'
                    )
                ) ,
                'Video' => array(
                    'fields' => array(
                        'Video.title',
                        'Video.slug',
                        'default_thumbnail_id'
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
                'VideoFlag.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('videoFlags', $this->paginate());
        $moreActions = $this->VideoFlag->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoFlag->delete($id)) {
            $this->Session->setFlash(__l('Video Flag has been deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>