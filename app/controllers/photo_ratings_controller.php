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
class PhotoRatingsController extends AppController
{
    public $name = 'PhotoRatings';
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_rating')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function add($photo_id = null, $rate = null) 
    {
        if (is_null($photo_id) || is_null($rate)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $photo = $this->PhotoRating->Photo->find('first', array(
            'conditions' => array(
                'Photo.id' => $photo_id
            ) ,
            'fields' => array(
                'Photo.user_id',
                'Photo.slug',
            ) ,
            'contain' => array(
                'PhotoRating' => array(
                    'fields' => array(
                        'PhotoRating.user_id'
                    ) ,
                    'conditions' => array(
                        'PhotoRating.user_id' => $this->Auth->user('id')
                    )
                )
            ) ,
            'recursive' => 1
        ));
        if (!empty($photo)) {
            // Find logged in user is owner of the photo
            if ($photo['Photo']['user_id'] != $this->Auth->user('id')) {
                if (empty($photo['PhotoRating'])) {
                    $this->PhotoRating->create();
                    $this->request->data['PhotoRating']['user_id'] = $this->Auth->user('id');
                    $this->request->data['PhotoRating']['rate'] = $rate;
                    $this->request->data['PhotoRating']['photo_id'] = $photo_id;
                    $this->request->data['PhotoRating']['ip_id'] =  $this->PhotoRating->toSaveIp();
                    if ($this->PhotoRating->save($this->request->data)) {
                        $this->PhotoRating->Photo->updateAll(array(
                            'Photo.total_ratings' => 'Photo.total_ratings + ' . $rate
                        ) , array(
                            'Photo.id' => $photo_id
                        ));
                        $this->Session->setFlash(__l('Rating has been added') , 'default', null, 'success');
                    } else {
                        $this->Session->setFlash(__l('Rating could not be added. Please, try again') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('You have already rated this photo') , 'default', null, 'error');
                }
            } else {
                $this->Session->setFlash(__l('You cannot rate your photo') , 'default', null, 'error');
            }
            if ($this->RequestHandler->isAjax()) {
                $photo = $this->PhotoRating->Photo->find('first', array(
                    'conditions' => array(
                        'Photo.id' => $photo_id
                    ) ,
                    'fields' => array(
                        'Photo.id',
                        'Photo.total_ratings',
                        'Photo.photo_rating_count',
                    ) ,
                    'recursive' => -1
                ));
                $this->set('photo', $photo);
            } else {
                $this->redirect(array(
                    'controller' => 'photos',
                    'action' => 'view',
                    $photo['Photo']['slug']
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
        $this->pageTitle = __l('Photo Ratings');
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
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoRating.created) <= '] = 0;
            $this->pageTitle.= __l(' - Rated today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoRating.created) <= '] = 7;
            $this->pageTitle.= __l(' - Rated in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoRating.created) <= '] = 30;
            $this->pageTitle.= __l(' - Rated in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PhotoRating']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->PhotoRating->recursive = 0;
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
                'PhotoRating.id' => 'desc'
            )
        );
        if (isset($this->request->data['PhotoRating']['q'])) {
            //$this->paginate['search'] = $this->request->data['PhotoRating']['q'];
            
        }
        $this->set('photoRatings', $this->paginate());
        $moreActions = $this->PhotoRating->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoRating->delete($id)) {
            $this->Session->setFlash(__l('Photo Rating deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>