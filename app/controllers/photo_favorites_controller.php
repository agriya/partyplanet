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
class PhotoFavoritesController extends AppController
{
    public $name = 'PhotoFavorites';
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_favorite')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($photo_id = null) 
    {
        if (is_null($photo_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $photo = $this->PhotoFavorite->Photo->find('first', array(
            'conditions' => array(
                'Photo.id' => $photo_id
            ) ,
            'contain' => array(
                'PhotoAlbum',
                'PhotoFavorite' => array(
                    'fields' => array(
                        'PhotoFavorite.user_id'
                    ) ,
                    'conditions' => array(
                        'PhotoFavorite.user_id' => $this->Auth->user('id')
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($photo)) {
            if (empty($photo['PhotoFavorite'])) {
                $this->PhotoFavorite->create();
                $this->request->data['PhotoFavorite']['user_id'] = $this->Auth->user('id');
                $this->request->data['PhotoFavorite']['photo_id'] = $photo_id;
                $this->request->data['PhotoFavorite']['ip_id'] =  $this->PhotoFavorite->toSaveIp();
                if ($this->PhotoFavorite->save($this->request->data)) {
                    $this->Session->setFlash(__l('Photo has been added as favorite') , 'default', null, 'success');
                    if ($this->RequestHandler->isAjax()) {
                        $id = $this->PhotoFavorite->id;
                        echo $id;
                        exit;
                    }
                } else {
                    $this->Session->setFlash(__l('Photo could not be added as favorite. Please, try again') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You have already added this photo as favorite') , 'default', null, 'error');
            }
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'index',
                'album' => $photo['PhotoAlbum']['slug'],
                'photo' => $photo['Photo']['slug'],
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
        $photoFavorite = $this->PhotoFavorite->find('first', array(
            'conditions' => array(
                'PhotoFavorite.id' => $id
            ) ,
            'contain' => array(
                'Photo' => array(
                    'PhotoAlbum'
                )
            )
        ));
        if (empty($photoFavorite)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoFavorite->delete($id)) {
            $this->Session->setFlash(__l('Photo Favorite deleted') , 'default', null, 'success');
            if ($this->RequestHandler->isAjax()) {
                echo $photoFavorite['Photo']['id'];
                exit;
            }
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'index',
                'album' => $photoFavorite['Photo']['PhotoAlbum']['slug'],
                'photo' => $photoFavorite['Photo']['slug'],
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
        $this->pageTitle = __l('Photo Favorites');
        $conditions = array();
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
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PhotoFavorite']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->PhotoFavorite->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
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
                'PhotoFavorite.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('photoFavorites', $this->paginate());
        $moreActions = $this->PhotoFavorite->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoFavorite->delete($id)) {
            $this->Session->setFlash(__l('Photo Favorite deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
