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
class PhotoFlagsController extends AppController
{
    public $name = 'PhotoFlags';
    public $components = array(
        'RequestHandler'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_flag')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($photo_id = null) 
    {
        if (!empty($this->request->data)) {
            if (empty($this->request->data['PhotoFlag']['photo_id'])) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->PhotoFlag->create();
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['PhotoFlag']['user_id'] = $this->Auth->user('id');
            }
            $this->request->data['PhotoFlag']['ip_id'] =  $this->PhotoFlag->toSaveIp();
            if ($this->PhotoFlag->save($this->request->data)) {
                $this->Session->setFlash(__l('Photo Flag has been added') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    $photo = $this->PhotoFlag->Photo->find('first', array(
                        'conditions' => array(
                            'Photo.id' => $this->request->data['PhotoFlag']['photo_id']
                        ) ,
                        'contain' => array(
                            'PhotoAlbum',
                        )
                    ));
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'index',
                        'album' => $photo['PhotoAlbum']['slug'],
                        'photo' => $photo['Photo']['slug'],
                        'admin' => false
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Photo Flag could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $this->request->data['PhotoFlag']['photo_id'] = !empty($this->request->data['PhotoFlag']['photo_id']) ? $this->request->data['PhotoFlag']['photo_id'] : $photo_id;
        $photoFlagCategories = $this->PhotoFlag->PhotoFlagCategory->find('list', array(
            'conditions' => array(
                'PhotoFlagCategory.is_active' => 1
            )
        ));
        $photo = $this->PhotoFlag->Photo->find('first', array(
            'conditions' => array(
                'Photo.id' => $this->request->data['PhotoFlag']['photo_id']
            ) ,
            'contain' => array(
                'PhotoAlbum',
            )
        ));
        $this->set('url', Router::url(array(
            'controller' => 'photos',
            'action' => 'index',
            'album' => $photo['PhotoAlbum']['slug'],
            'photo' => $photo['Photo']['slug'],
            'admin' => false
        ) , true));
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->PhotoFlag->User->find('list');
            $this->set(compact('users'));
        }
        $this->set(compact('photoFlagCategories'));
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Photo Flags');
        $conditions = array();
        if (!empty($this->request->params['named']['category'])) {
            $photoFlagCategory = $this->{$this->modelClass}->PhotoFlagCategory->find('first', array(
                'conditions' => array(
                    'PhotoFlagCategory.id' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'PhotoFlagCategory.id',
                    'PhotoFlagCategory.name'
                ) ,
                'recursive' => -1
            ));
            if (empty($photoFlagCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['PhotoFlagCategory.id'] = $photoFlagCategory['PhotoFlagCategory']['id'];
            $this->pageTitle.= sprintf(__l(' - Category - %s') , $photoFlagCategory['PhotoFlagCategory']['name']);
        }
        if (!empty($this->request->params['named']['photo'])) {
            $photo = $this->{$this->modelClass}->Photo->find('first', array(
                'conditions' => array(
                    'Photo.slug' => $this->request->params['named']['photo']
                ) ,
                'fields' => array(
                    'Photo.id',
                    'Photo.title',
                    'Photo.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($photo)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Photo.id'] = $photo['Photo']['id'];
            $this->pageTitle.= sprintf(__l(' - Photo - %s') , $photo['Photo']['title']);
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoFlag.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoFlag.created) <= '] = 7;
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoFlag.created) <= '] = 30;
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PhotoFlag']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->PhotoFlag->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'PhotoFlagCategory' => array(
                    'fields' => array(
                        'PhotoFlagCategory.name'
                    )
                ) ,
                'Photo' => array(
                    'fields' => array(
                        'Photo.title',
                        'Photo.slug'
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
                'PhotoFlag.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('photoFlags', $this->paginate());
        $moreActions = $this->PhotoFlag->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoFlag->delete($id)) {
            $this->Session->setFlash(__l('Photo Flag has been deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>