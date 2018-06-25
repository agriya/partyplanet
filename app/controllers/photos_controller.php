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
class PhotosController extends AppController
{
    public $name = 'Photos';
    public $uses = array(
        'Photo',
        'PhotoUserTag'
    );
    public $components = array(
        'RequestHandler',
        'OauthConsumer'
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Attachment',
            'Photo.makeDelete'
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_photo_on_system_flag')) {
            $this->Photo->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Photos');
        $conditions = array();
        if (!empty($this->request->params['named']['username'])) {
            $this->pageTitle.= sprintf(__l(' - User - %s') , $this->request->params['named']['username']);
            $conditions['User.username'] = $this->request->params['named']['username'];
            $this->set('username', $this->request->params['named']['username']);
        } else {
            $conditions['Photo.admin_suspend'] = 0;
            $order['Photo.id'] = 'desc';
        }
        if (Configure::read('photo.is_allow_photo_tag') && !empty($this->request->params['named']['tag'])) {
            $photoTag = $this->Photo->PhotoTag->find('first', array(
                'conditions' => array(
                    'PhotoTag.slug' => $this->request->params['named']['tag']
                ) ,
                'fields' => array(
                    'PhotoTag.name',
                    'PhotoTag.slug'
                ) ,
                'contain' => array(
                    'Photo' => array(
                        'fields' => array(
                            'Photo.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($photoTag)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->pageTitle.= sprintf(__l(' - Tag - %s') , $photoTag['PhotoTag']['name']);
            $ids = array();
            if (!empty($photoTag)) {
                foreach($photoTag['Photo'] as $photo) {
                    $ids[] = $photo['id'];
                }
            }
            $conditions['Photo.id'] = $ids;
        }
        if (Configure::read('photo.is_allow_photo_favorite') && !empty($this->request->params['named']['favorite'])) {
            $this->pageTitle.= sprintf(__l(' - Favorite - %s') , $this->request->params['named']['favorite']);
            $photoFavorites = $this->Photo->PhotoFavorite->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['favorite']
                ) ,
                'fields' => array(
                    'PhotoFavorite.photo_id'
                ) ,
                'recursive' => 0
            ));
            $conditions['Photo.id'] = $photoFavorites;
        }
        if (!empty($this->request->params['named']['venue_id'])) {
            // venue photo display need to manage
            //$conditions['Photo.venue_id'] = $this->request->params['named']['venue_id'];
            
        }
        if (!empty($this->request->params['named']['most'])) {
            if ($this->request->params['named']['most'] == ConstURLFilter::Viewed) {
                $this->pageTitle.= __l(' - Most viewed');
                $order['Photo.photo_view_count'] = 'desc';
            } else if (Configure::read('photo.is_allow_photo_comment') && $this->request->params['named']['most'] == ConstURLFilter::Commented) {
                $this->pageTitle.= __l(' - Most commented');
                $order['Photo.photo_comment_count'] = 'desc';
            } else if (Configure::read('photo.is_allow_photo_favorite') && $this->request->params['named']['most'] == ConstURLFilter::Favorited) {
                $this->pageTitle.= __l(' - Most favorited');
                $order['Photo.photo_favorite_count'] = 'desc';
            } else if (Configure::read('photo.is_allow_photo_flag') && $this->request->params['named']['most'] == ConstURLFilter::Flagged) {
                $this->pageTitle.= __l(' - Most flagged');
                $order['Photo.photo_flag_count'] = 'desc';
            } else if (Configure::read('photo.is_allow_photo_rating') && $this->request->params['named']['most'] == ConstURLFilter::Rated) {
                $this->pageTitle.= __l(' - Most rated');
                $order['avg_rating'] = 'desc';
            }
        }
        if (!empty($this->request->params['named']['keyword'])) {
            $conditions['Photo.title Like'] = '%' . $this->request->params['named']['keyword'] . '%';
        }
        $limit = 20;
        if (!empty($this->request->params['named']['location'])) {
            $limit = 5;
        }
        if (Configure::read('photo.is_allow_photo_album') && !empty($this->request->params['named']['album'])) {
            $this->pageTitle.= sprintf(__l(' - Album - %s') , $this->request->params['named']['album']);
            $photoAlbum = $this->Photo->_getPhotoAlbum($this->request->params['named']['album'], 'slug');
            if (empty($photoAlbum)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->set('photoAlbum', $photoAlbum);
            $conditions['Photo.photo_album_id'] = $photoAlbum['PhotoAlbum']['id'];
            $limit = 10;
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'hotties') {
            $this->pageTitle.= __l(' - Hotties');
            $conditions['Photo.is_hotties'] = '1';
            $order['Photo.id'] = 'desc';
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'popular') {
            $this->pageTitle.= __l(' - Popular');
            $order['Photo.photo_view_count'] = 'desc';
        }
        $conditions['Photo.is_random'] = 0;
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['Photo']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $conditions['Photo.is_active'] = 1;
        $conditions['PhotoAlbum.city_id'] = $this->_prefixId;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'Photo.id',
                'Photo.created',
                'Photo.modified',
                'Photo.user_id',
                'Photo.photo_album_id',
                'Photo.title',
                'Photo.slug',
                'Photo.description',
                'Photo.photo_view_count',
                'Photo.photo_comment_count',
                'Photo.photo_flag_count',
                'Photo.total_ratings',
                'Photo.photo_rating_count',
                '(Photo.total_ratings)/(Photo.photo_rating_count) AS avg_rating',
                'Photo.photo_favorite_count',
                'Photo.revision_count',
                'Photo.url',
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                ) ,
                'PhotoAlbum' => array(
                    'Photo' => array(
                        'fields' => array(
                            'Photo.id',
                            'Photo.slug',
                            'Photo.title'
                        )
                    ) ,
                    'Venue' => array(
                        'fields' => array(
                            'Venue.id',
                            'Venue.slug',
                            'Venue.name'
                        )
                    ) ,
                    'Event' => array(
                        'fields' => array(
                            'Event.id',
                            'Event.slug',
                            'Event.title'
                        )
                    ) ,
                    'fields' => array(
                        'PhotoAlbum.title',
                        'PhotoAlbum.slug',
                        'PhotoAlbum.venue_id',
                        'PhotoAlbum.event_id',
                        'photo_count'
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
                'PhotoTag' => array(
                    'fields' => array(
                        'PhotoTag.name',
                        'PhotoTag.slug'
                    )
                ) ,
                'PhotoFavorite'
            ) ,
            'order' => $order,
            'limit' => $limit,
            'recursive' => 3
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        // to find the percentage of the uploaded photos size of the user
        if (!empty($this->request->params['named']['username']) && $this->request->params['named']['username'] == $this->Auth->user('username')) {
            $allowed_size = higher_to_bytes(Configure::read('photo.allowed_photos_size') , Configure::read('photo.allowed_photos_size_unit'));
            $user_size = $this->Photo->User->getPhotoQuota($this->Auth->user('id'));
            $this->set('size_percentage', round((($user_size/$allowed_size) *100) , 2));
            $this->set('used_size', bytes_to_higher($user_size));
        }
        $photos = $this->paginate();
        $this->set('photos', $photos);
        if (Configure::read('photo.is_allow_photo_album') && !empty($this->request->params['named']['album'])) {
            $count = 0;
            if (!empty($photos)) {
                if (!empty($this->request->params['named']['photo'])) {
                    $photo_slug = $this->request->params['named']['photo'];
                }
                foreach($photos as $photo) {
                    $count++;
                    if (empty($photo_slug) || $photo['Photo']['slug'] == $photo_slug) {
                        $photo_id = $photo['Photo']['id'];
                        $photo_album_id = $photo['PhotoAlbum']['id'];
                        //Setting photo id to fetch in photo comment add form
                        $this->request->data['PhotoComment']['photo_id'] = $photo['Photo']['id'];
                        //Setting photo id to fetch in photo flag add form
                        $this->request->data['PhotoFlag']['photo_id'] = $photo['Photo']['id'];
                        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                            $users = $this->Photo->User->find('list');
                            $this->set(compact('users'));
                        }
                        $photoFlagCategories = $this->Photo->PhotoFlag->PhotoFlagCategory->find('list');
                        $this->set(compact('photoFlagCategories'));
						// photo view count
                        $this->request->data['PhotoView']['user_id'] = $this->Auth->user('id');
                        $this->request->data['PhotoView']['photo_id'] = $photo['Photo']['id'];
                        $this->request->data['PhotoView']['ip_id'] = $this->Photo->PhotoView->toSaveIp();
                        $this->Photo->PhotoView->create();
                        $this->Photo->PhotoView->save($this->request->data);
                        break;
                    }
                }
                $this->set('count', $count);
            }
            if (!empty($photo_id)) {
                $neighbors = $this->Photo->find('neighbors', array(
                    'conditions' => array(
                        'Photo.photo_album_id' => $photo_album_id,
                    ) ,
                    'field' => 'id',
                    'value' => $photo_id,
                    'fields' => array(
                        'Photo.id',
                        'Photo.slug'
                    ) ,
                    'order' => array(
                        'Photo.id' => 'desc'
                    ) ,
                    'recursive' => -1
                ));
                $this->set('neighbors', $neighbors);
            }
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search') {
            $this->render('index');
        } elseif (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'home' && empty($this->request->params['named']['view'])) {
            $this->render('index_compact');
        } elseif (!empty($this->request->params['named']['album'])) {
            App::import('Vendor', 'facebook/facebook');
            $this->facebook = new Facebook(array(
                'appId' => Configure::read('facebook.app_id') ,
                'secret' => Configure::read('facebook.fb_secrect_key') ,
                'cookie' => true
            ));
            $fb_return_url = Router::url(array(
                'controller' => $this->request->params['named'][Configure::read('site.prefix_parameter_key') ],
                'action' => 'photos',
                'fb_update',
                'album' => $this->request->params['named']['album'],
                'admin' => false
            ) , true);
            $this->Session->write('fb_return_url', $fb_return_url);
            $this->set('fb_login_url', $this->facebook->getLoginUrl(array(
                'redirect_uri' => Router::url(array(
                    'controller' => 'users',
                    'action' => 'oauth_facebook',
                    'admin' => false
                ) , true) ,
                'scope' => 'email,publish_stream'
            )));
            $fb_sess_check = $this->Session->read('fbuser');
            $this->set('fb_session', $fb_sess_check);
            $this->render('index_single');
        }
    }
    public function view($slug = null) 
    {
        $this->pageTitle = __l('Photo');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $contain = array(
            'User' => array(
                'fields' => array(
                    'User.user_type_id',
                    'User.username',
                    'User.id',
                    'User.fb_user_id',
                    'User.twitter_avatar_url',
                )
            ) ,
			'PhotoAlbum' => array(
                'fields' => array(
                    'PhotoAlbum.id',
                    'PhotoAlbum.slug',
                )
            ) ,
            'Attachment' => array(
                'fields' => array(
                    'Attachment.id',
                    'Attachment.filename',
                    'Attachment.dir',
                    'Attachment.width',
                    'Attachment.height'
                )
            )
        );
        if (Configure::read('photo.is_allow_photo_favorite')) {
            $contain['PhotoFavorite'] = array(
                'fields' => array(
                    'PhotoFavorite.id'
                ) ,
                'conditions' => array(
                    'PhotoFavorite.user_id' => $this->Auth->user('id')
                )
            );
        }
        if (Configure::read('photo.is_allow_photo_tag')) {
            $contain['PhotoTag'] = array(
                'fields' => array(
                    'PhotoTag.slug',
                    'PhotoTag.name'
                ) ,
            );
        }
        $photo = $this->Photo->find('first', array(
            'conditions' => array(
                'Photo.slug = ' => $slug
            ) ,
            'contain' => $contain,
            'recursive' => 2
        ));
        if (empty($photo)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        //Log the photo view
        if (!empty($photo)) {
            $this->request->data['PhotoView']['user_id'] = $this->Auth->user('id');
            $this->request->data['PhotoView']['photo_id'] = $photo['Photo']['id'];
            $this->request->data['PhotoView']['ip_id'] = $this->Photo->PhotoView->toSaveIp();
			$this->Photo->PhotoView->create();
            $this->Photo->PhotoView->save($this->request->data);
        }
        if (!empty($photo['Attachment'])) {
            $image_options = array(
                'dimension' => 'big_thumb',
                'class' => '',
                'alt' => $photo['Photo']['title'],
                'title' => $photo['Photo']['title'],
                'type' => 'png',
                'full_url' => true,
            );
            $photo_image = getImageUrl('Photo', $photo['Attachment'], $image_options, true);
            Configure::write('meta.image', $photo_image);
        }
        if (!empty($photo['Photo']['title'])) {
            Configure::write('meta.name', $photo['Photo']['title']);
        }
        Configure::write('meta.keywords', Configure::read('meta.keywords') . ', ' . $photo['Photo']['title']);
        Configure::write('meta.description', $photo['Photo']['title'] . ' posted in ' . Configure::read('site.name'));
        $this->pageTitle.= ' - ' . $photo['Photo']['title'];
        //Setting photo id to fetch in photo comment add form
        $this->request->data['PhotoComment']['photo_id'] = $photo['Photo']['id'];
        //Setting photo id to fetch in photo flag add form
        $this->request->data['PhotoFlag']['photo_id'] = $photo['Photo']['id'];
        $this->set(compact('photo'));
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Photo->User->find('list');
            $this->set(compact('users'));
        }
        $photoFlagCategories = $this->Photo->PhotoFlag->PhotoFlagCategory->find('list');
        $this->set(compact('photoFlagCategories'));
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_return_url = Router::url(array(
            'controller' => $this->request->params['named'][Configure::read('site.prefix_parameter_key') ],
            'action' => 'photos',
            'fb_update',
            $slug,
            'admin' => false
        ) , true);
        $this->Session->write('fb_return_url', $fb_return_url);
        $this->set('redirect_url', $this->facebook->getLoginUrl(array(
            'fb_login_url' => Router::url(array(
                'controller' => 'users',
                'action' => 'oauth_facebook',
                'admin' => false
            ) , true) ,
            'scope' => 'email,publish_stream'
        )));
        $fb_sess_check = $this->Session->read('fbuser');
        $this->set('fb_session', $fb_sess_check);
    }
    public function admin_random() 
    {
        $this->pageTitle = __l('Add Home Banner Photo');
        $photo = $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.is_random' => 1
            ) ,
            'recursive' => -1
        ));
        if ($photo >= 8) {
            $this->Session->setFlash(__l('You can upload only 8 photos as home banner photos') , 'default', null, 'error');
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'index',
                'type' => 'random'
            ));
        }
        $this->Photo->Attachment->Behaviors->attach('ImageUpload', Configure::read('photo.file'));
        if (!empty($this->request->data)) {
            $this->request->data['Photo']['ip_id'] = $this->Photo->toSaveIp();
            $this->request->data['Photo']['photo_album_id'] = 0;
            $this->request->data['Photo']['is_random'] = 1;
            $this->request->data['Photo']['is_active'] = 1;
            $is_random_photo = 'false';
            if (!empty($this->request->data['Attachment']['filename']['name'])) {
                $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                $is_random_photo = 'true';
            }
            $this->Photo->set($this->request->data);
            $this->Photo->Attachment->set($this->request->data);
            if ($this->Photo->validates() &$this->Photo->Attachment->validates() &$is_random_photo == 'true') {
                $this->Photo->create();
                if ($this->Photo->save($this->request->data)) {
                    $photo_id = $this->Photo->getLastInsertId();
                    $this->Photo->Attachment->create();
                    $this->request->data['Attachment']['filename'] = $this->request->data['Attachment']['filename'];
                    $this->request->data['Attachment']['class'] = 'Photo';
                    $this->request->data['Attachment']['description'] = 'RandomImage';
                    $this->request->data['Attachment']['foreign_id'] = $photo_id;
                    $this->Photo->Attachment->save($this->request->data['Attachment']);
                    $this->Session->setFlash(__l('Random photo has been added') , 'default', null, 'success');
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'index',
                        'type' => 'random',
                        'admin' => true
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Random photo could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function add($photo_album_id = null) 
    {
        $this->pageTitle = __l('Upload Photos');
        if (empty($photo_album_id) && empty($this->request->data['Photo']['photo_album_id'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // allow_size is used in two places. inside ($this->request->data) and outside.
        $allowed_size = higher_to_bytes(Configure::read('photo.allowed_photos_size') , Configure::read('photo.allowed_photos_size_unit'));
        $used_photos_size = $this->Photo->User->getPhotoQuota($this->Auth->user('id'));
        $this->set('size_percentage', round((($used_photos_size/$allowed_size) *100) , 2));
        $this->set('used_size', bytes_to_higher($used_photos_size));
        $this->set('remaining_allowed_size', $allowed_size-$used_photos_size);
        if (!empty($this->request->data['Photo']['photo_album_id'])) {
            $photo_album_id = $this->request->data['Photo']['photo_album_id'];
        }
        if (!empty($photo_album_id)) {
            $photoAlbum = $this->Photo->_getPhotoAlbum($photo_album_id, 'id');
            if (empty($photoAlbum)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->set('photoAlbum', $photoAlbum);
        }
        if (!empty($this->request->data)) {
            $this->request->data['Photo']['ip_id'] = $this->Photo->toSaveIp();
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['Photo']['is_active'] = (Configure::read('photo.is_admin_activate_after_photo_add')) ? 0 : 1;
            } else {
                $this->request->data['Photo']['is_active'] = 1;
            }
            $this->Photo->set($this->request->data);
            if (!isset($this->request->data['Attachment'])) {
                if ($this->Photo->validates()) {
                    $this->XAjax->flashuploadset($this->request->data);
                }
            } else {
                $filesize = 0;
                foreach($this->request->data['Attachment'] as $files) {
                    $filesize+= $files['filename']['size'];
                }
                $is_size_ok = (($used_photos_size+$filesize) <= $allowed_size) ? true : false;
                if ($is_size_ok) {
                    $is_form_valid = true;
                    $upload_photo_count = 0;
                    for ($i = 0; $i < Configure::read('photo.maximum_photos_per_upload'); $i++) {
                        if ($this->request->data['Attachment'][$i]['filename']['error'] == 1) {
                            $attachmentValidationError[$i] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                            $is_form_valid = false;
                            $upload_photo_count++;
                            continue;
                        }
                        if (!empty($this->request->data['Attachment'][$i]['filename']['tmp_name'])) {
                            $upload_photo_count++;
                            $image_info = getimagesize($this->request->data['Attachment'][$i]['filename']['tmp_name']);
                            $this->request->data['Attachment']['filename'] = $this->request->data['Attachment'][$i]['filename'];
                            $this->request->data['Attachment']['filename']['type'] = $image_info['mime'];
                            $this->Photo->Attachment->Behaviors->attach('ImageUpload', Configure::read('photo.file'));
                            $this->Photo->Attachment->set($this->request->data);
                            if (!$this->Photo->validates() |!$this->Photo->Attachment->validates()) {
                                $attachmentValidationError[$i] = $this->Photo->Attachment->validationErrors;
                                $is_form_valid = false;
                                $this->Session->setFlash(__l('Photo could not be added. Please, try again.') , 'default', null, 'error');
                            }
                        }
                    }
                    if (!$upload_photo_count) {
                        $this->Photo->validates();
                        $this->Photo->Attachment->validationErrors[0]['filename'] = __l('Required');
                        $is_form_valid = false;
                    }
                    if (!empty($attachmentValidationError)) {
                        foreach($attachmentValidationError as $key => $error) {
                            $this->Photo->Attachment->validationErrors[$key]['filename'] = $error;
                        }
                    }
                    if ($is_form_valid) {
                        $this->XAjax->normalupload($this->request->data, true);
                        $this->Session->setFlash(__l('Photo has been added') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'photos',
                            'action' => 'update'
                        ));
                    }
                } else {
                    $this->Session->setFlash(__l('Your allowed photo quota is over.') , 'default', null, 'error');
                }
            }
        }
        if (!empty($photoAlbum)) {
            $this->request->data['Photo']['photo_album_id'] = $photo_album_id;
            $this->request->data['Photo']['user_id'] = $photoAlbum['User']['id'];
        }
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Photo');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->Photo->set($this->request->data);
            $photo = $this->Photo->find('first', array(
                'conditions' => array(
                    'Photo.id' => $id
                ) ,
                'fields' => array(
                    'Photo.title',
                    'Photo.slug',
                    'Photo.user_id',
                    'Photo.is_random',
                    'Attachment.filesize',
                    'PhotoAlbum.title',
                    'PhotoAlbum.slug',
                ) ,
                'recursive' => 0
            ));
            if ($this->Photo->save($this->request->data)) {
                //this is for admin_edit
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && $this->request->data['Photo']['user_id'] != $photo['Photo']['user_id']) {
                    // Reduce the used photos size in users table
                    $this->Photo->User->updateAll(array(
                        'User.photo_upload_quota' => 'photo_upload_quota - ' . $photo['Attachment']['filesize']
                    ) , array(
                        'User.id' => $photo['Photo']['user_id']
                    ));
                    // Update the used photos size in users table
                    $this->Photo->User->updateAll(array(
                        'User.photo_upload_quota' => 'photo_upload_quota + ' . $photo['Attachment']['filesize']
                    ) , array(
                        'User.id' => $this->request->data['Photo']['user_id']
                    ));
                }
                $this->Session->setFlash(__l('Photo has been updated') , 'default', null, 'success');
                if ($photo['Photo']['is_random']) {
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'index',
                        'type' => 'random',
                    ));
                }
                if ($this->request->data['Photo']['title'] != $photo['Photo']['title']) {
                    $photo = $this->Photo->find('first', array(
                        'conditions' => array(
                            'Photo.id' => $id
                        ) ,
                        'fields' => array(
                            'Photo.slug',
                        ) ,
                        'recursive' => -1
                    ));
                }
                if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'index',
                        'album' => $photo['PhotoAlbum']['slug'],
                        'photo' => $photo['Photo']['slug'],
                        'admin' => false
                    ));
                } else {
                    $this->redirect(array(
                        'action' => 'index',
                    ));
                }
            }
        } else {
            $conditions['Photo.id'] = $id;
            //this is for admin_edit
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $conditions['Photo.user_id'] = $this->Auth->user('id');
            }
            $this->request->data = $this->Photo->find('first', array(
                'conditions' => $conditions,
                'fields' => array(
                    'Photo.title',
                    'Photo.description',
                    'Photo.photo_album_id',
                    'Photo.slug',
                    'Photo.is_random',
                    'Photo.url',
                    'Photo.user_id',
                    'Photo.is_adult_photo',
                    'Attachment.id',
                    'Attachment.filename',
                    'Attachment.dir',
                    'Attachment.width',
                    'Attachment.height'
                ) ,
                'contain' => array(
                    'Attachment',
                    'PhotoTag' => array(
                        'fields' => array(
                            'name'
                        )
                    )
                )
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Photo']['tag'] = $this->Photo->formatTags($this->request->data['PhotoTag']);
        }
        //this is for admin_edit
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Photo->User->find('list');
            $this->set(compact('users'));
        }
        $this->pageTitle.= ' - ' . $this->request->data['Photo']['title'];
        if (Configure::read('photo.is_allow_photo_album')) {
            $photoAlbums = $this->Photo->PhotoAlbum->find('list', array(
                'conditions' => array(
                    'PhotoAlbum.user_id' => $this->request->data['Photo']['user_id']
                )
            ));
            $this->set(compact('photoAlbums'));
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!$this->Photo->deleteAll(array(
            'Photo.user_id' => $this->Auth->user('id') ,
            'Photo.id' => $id
        ))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Session->setFlash(__l('Photo deleted') , 'default', null, 'success');
        $this->redirect(array(
            'action' => 'index'
        ));
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'username',
            'q'
        ));
        $this->pageTitle = __l('Photos');
        $conditions = array();
        $this->Photo->validate = array();
        if (!empty($this->request->params['named']['username'])) {
            $conditions = array(
                'User.username' => $this->request->params['named']['username']
            );
            $this->pageTitle.= sprintf(__l(' - User - %s') , $this->request->params['named']['username']);
        }
        if (!empty($this->request->params['named']['album'])) {
            $photoAlbum = $this->Photo->_getPhotoAlbum($this->request->params['named']['album'], 'slug');
            if (empty($photoAlbum)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->set('photoAlbum', $photoAlbum);
            $conditions = array(
                'PhotoAlbum.slug' => $this->request->params['named']['album']
            );
            $this->pageTitle.= sprintf(__l(' - Album - %s') , $this->request->params['named']['album']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Photo.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Photo.created) <= '] = 7;
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Photo.created) <= '] = 30;
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['Photo']['q'] = $this->request->params['named']['q'];
            $conditions['Photo.title like'] = '%' . $this->request->params['named']['q'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Photo.is_active'] = 1;
                $conditions['Photo.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Photo.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Photo.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - System Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Photo.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Hotties) {
                $conditions['Photo.is_hotties'] = 1;
                $this->pageTitle.= __l(' - Hotties ');
            }
        }
        $conditions['Photo.is_random'] = '0';
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'random') {
            $this->pageTitle = __l('Home Banner Photos');
            $conditions['Photo.is_random'] = '1';
        }
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'hotties') {
            $conditions['Photo.is_hotties'] = '1';
        }
        $this->Photo->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'Photo.id',
                'Photo.modified',
                'Photo.created',
                'Photo.url',
                'Photo.title',
                'Photo.slug',
                'Photo.user_id',
                'Photo.description',
                'Photo.photo_comment_count',
                'Photo.photo_rating_count',
                'Photo.photo_flag_count',
                'Photo.photo_view_count',
                'Photo.is_active',
                'Photo.is_hotties',
                'Photo.is_system_flagged',
                'Photo.admin_suspend',
                'Photo.photo_favorite_count',
                '(Photo.total_ratings)/(Photo.photo_rating_count) AS avg_rating',
                'Attachment.id',
                'Attachment.filename',
                'Attachment.dir',
                'Attachment.width',
                'Attachment.height',
            ) ,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'PhotoAlbum' => array(
                    'fields' => array(
                        'PhotoAlbum.title',
                        'PhotoAlbum.slug',
                    )
                ) ,
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
            ) ,
            'order' => 'Photo.id desc',
        );
        $this->set('photos', $this->paginate());
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'random') {
            $this->render('admin_random_index');
        }
        $this->set('active', $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.admin_suspend' => 1,
            )
        )));
        $this->set('hotties', $this->Photo->find('count', array(
            'conditions' => array(
                'Photo.is_hotties' => 1,
            )
        )));
        $moreActions = $this->Photo->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_update() 
    {
        $this->setAction('update');
    }
    public function admin_edit($id = null) 
    {
        if (is_null($id) && empty($this->request->data)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Photo->delete($id)) {
            $this->Session->setFlash(__l('Photo deleted') , 'default', null, 'success');
            if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type']) {
                $this->redirect(array(
                    'controller' => 'photos',
                    'action' => 'index',
                    'type' => 'random',
                ));
            }
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function flashupload() 
    {
        $this->Photo->Attachment->Behaviors->attach('ImageUpload', Configure::read('photo.file'));
        $this->XAjax->flashupload(true);
        $this->autoRender = false;
    }
    public function update() 
    {
        $this->pageTitle = __l('Update this uploads');
         if (!empty($this->request->data)) {
            $temp_data = $this->request->data;
            $this->request->data = array();
            $this->request->data['Photo']['photo_album_id'] = $temp_data['Photo']['photo_album_id'];
            unset($temp_data['Photo']['photo_album_id']);
            foreach($temp_data['Photo'] as $photo) {
                $this->request->data['Photo']['id'] = $photo['id'];
                $this->request->data['Photo']['title'] = $photo['title'];
                $this->request->data['Photo']['description'] = $photo['description'];
                if (Configure::read('photo.is_show_adult_photo_option')) {
                    $this->request->data['Photo']['is_adult_photo'] = $photo['is_adult_photo'];
                }
                $this->request->data['Photo']['tag'] = $photo['tag'];
                $this->Photo->save($this->request->data);
            }
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                if (Configure::read('photo.is_admin_activate_after_photo_add')) {
                    $this->Session->setFlash(__l('Photo has been added. After admin approval it will list out in site.') , 'default', null, 'success');
                } else {
                    $this->Session->setFlash(__l('Photo has been added') , 'default', null, 'success');
                }
            } else {
                $this->Session->setFlash(__l('Photo has been added') , 'default', null, 'success');
            }
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'add',
                $this->request->data['Photo']['photo_album_id']
            ));
        } else {
			$uploaded_photos = Cache::read(session_id());
            Cache::delete(session_id());
            $this->Session->delete('flash_uploaded');
            $this->Session->delete('flashupload_data');
             if (!empty($uploaded_photos)) {
                $photos = $this->Photo->find('all', array(
                    'conditions' => array(
                        'Photo.id' => $uploaded_photos
                    ) ,
                    'contain' => array(
                        'PhotoTag',
                        'Attachment',
                        'PhotoAlbum',
                    ) ,
                    'recursive' => 2
                ));
                $photos = Set::merge($photos, $this->Photo->formatTags($photos));
                $this->set('photos', $photos);
                $filesize = 0;
                $this->request->data['Photo']['photo_album_id'] = $photos[0]['Photo']['photo_album_id'];
                $user = $this->Photo->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $photos[0]['Photo']['user_id']
                    ) ,
                    'recursive' => -1
                ));
                foreach($photos as $photo) {
                    $this->request->data['Photo'][$photo['Photo']['id']]['id'] = $photo['Photo']['id'];
                    $this->request->data['Photo'][$photo['Photo']['id']]['title'] = $photo['Photo']['title'];
                    $this->request->data['Photo'][$photo['Photo']['id']]['description'] = $photo['Photo']['description'];
                    $this->request->data['Photo'][$photo['Photo']['id']]['tag'] = $photo['PhotoTag'];
                    if (Configure::read('photo.is_show_adult_photo_option')) {
                        $this->request->data['Photo'][$photo['Photo']['id']]['is_adult_photo'] = $photo['Photo']['is_adult_photo'];
                    }
                    $filesize+= $photo['Attachment']['filesize'];
                    $url = Router::url(array(
                        'controller' => 'photos',
                        'action' => 'view',
                        'admin' => false,
                        'album' => $photo['PhotoAlbum']['slug'],
                        'photo' => $photo['Photo']['slug'],
                    ) , true);
                    // photo willn't be posted if it is autoflagged and suspend
                    if (!$photo['Photo']['admin_suspend'] && $photo['Photo']['is_active']) {
                        $image_options = array(
                            'dimension' => 'normal_thumb',
                            'class' => '',
                            'alt' => $photo['Photo']['title'],
                            'title' => $photo['Photo']['title'],
                            'type' => 'jpg'
                        );
                        $post_data = array();
                        $post_data['message'] = $user['User']['username'] . ' ' . __l('addd a new photo "') . '' . $photo['Photo']['title'] . __l('" in ') . Configure::read('site.name');
                        $post_data['image_url'] = Router::url('/', true) . getImageUrl('Photo', $photo['Attachment'], $image_options);
                        $post_data['link'] = $url;
                        $post_data['description'] = $photo['Photo']['description'];
                        // Post on user facebook
                        if (Configure::read('social_networking.post_photo_on_user_facebook')) {
                            if ($user['User']['fb_user_id'] > 0) {
                                $post_data['fb_user_id'] = $user['User']['fb_user_id'];
                                $post_data['fb_access_token'] = $user['User']['fb_access_token'];
                                $getFBReturn = $this->postOnFacebook($post_data, 0);
                            }
                        }
                        // post on user twitter
                        if (Configure::read('social_networking.post_photo_on_user_twitter')) {
                            if (!empty($user['User']['twitter_access_token']) && !empty($user['User']['twitter_access_key'])) {
                                $post_data['twitter_access_key'] = $user['User']['twitter_access_key'];
                                $post_data['twitter_access_token'] = $user['User']['twitter_access_token'];
                                $getTewwtReturn = $this->postOnTwitter($post_data, 0);
                            }
                        }
                        if (Configure::read('photo.post_on_facebook')) { // post on site facebook
                            $getFBReturn = $this->postOnFacebook($post_data, 1);
                        }
                        if (Configure::read('photo.post_on_twitter')) { // post on site twitter
                            $getTewwtReturn = $this->postOnTwitter($post_data, 1);
                        }
                    }
                }
                // To update used photos size in users table
                $this->Photo->User->updateAll(array(
                    'User.photo_upload_quota' => 'photo_upload_quota + ' . $filesize
                ) , array(
                    'User.id' => $photos[0]['Photo']['user_id']
                ));
            } else {
                $this->Session->setFlash(__l('Sorry, no latest uploaded photo to update') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'photos',
                    'action' => 'index'
                ));
            }
        }
    }
    public function fb_update($slug = null) 
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_session = $this->Session->read('fbuser');
        if (isset($this->request->params['named']['album'])) {
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'index',
                'album' => $this->request->params['named']['album'],
                'admin' => false,
            ));
        } else {
            $this->redirect(array(
                'controller' => 'photo',
                'action' => 'view',
                $slug,
                'admin' => false,
            ));
        }
    }
    public function face_friends() 
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_session = $this->Session->read('fbuser');
        if (!empty($fb_session)) {
            $friend_list = file_get_contents("https://graph.facebook.com/me/friends?access_token=" . $fb_session['access_token']);
            $friends = json_decode($friend_list);
            $return_list = "";
            foreach($friends->data as $friend) {
                if ($return_list != "") {
                    $return_list.= "\n";
                }
                $return_list.= $friend->name . '|' . $friend->id;
            }
            echo $return_list;
        }
        exit;
    }
    public function face_addtag($id = null) 
    {
        App::import('Vendor', 'facebook/facebook');
        $this->facebook = new Facebook(array(
            'appId' => Configure::read('facebook.app_id') ,
            'secret' => Configure::read('facebook.fb_secrect_key') ,
            'cookie' => true
        ));
        $fb_session = $this->Session->read('fbuser');;
        $this->request->data['PhotoUserTag']['photo_id'] = $id;
        if ($this->Auth->user('id')) {
            $this->request->data['PhotoUserTag']['user_id'] = $this->Auth->user('id');
        }
        $this->request->data['PhotoUserTag']['name'] = $_REQUEST['name'];
        $this->request->data['PhotoUserTag']['left'] = $_REQUEST['left'];
        $this->request->data['PhotoUserTag']['top'] = $_REQUEST['top'];
        $this->request->data['PhotoUserTag']['width'] = $_REQUEST['width'];
        $this->request->data['PhotoUserTag']['height'] = $_REQUEST['height'];
        $this->PhotoUserTag->create();
        if ($this->PhotoUserTag->save($this->request->data, true)) {
            $photo = $this->Photo->find('first', array(
                'conditions' => array(
                    'Photo.id' => $id,
                ) ,
                'contain' => array(
                    'PhotoAlbum' => array(
                        'fields' => array(
                            'PhotoAlbum.id',
                            'PhotoAlbum.slug'
                        ) ,
                    ) ,
                ) ,
                'fields' => array(
                    'Photo.id',
                    'Photo.slug',
                ) ,
                'recursive' => 1
            ));
            if (!empty($photo)) {
                $url = Router::url(array(
                    'controller' => 'photos',
                    'action' => 'index',
                    'album' => $photo['PhotoAlbum']['slug'],
                    'photo' => $photo['Photo']['slug']
                ) , true);
                $message = 'Tagged a photo of you.';
                $description = 'Your friend has tagged a photo of your name on' . Configure::read('site.name');
                try {
                    $this->facebook->api('/' . $fb_session['id'] . '/feed', 'POST', array(
                        'access_token' => $fb_session['access_token'],
                        'message' => $message,
                        'link' => $url,
                        'caption' => $url,
                        'description' => $description
                    ));
                }
                catch(Exception $e) {
                    $this->log('Post on facebook error');
                }
                $return_array['result'] = 'true';
                $return_array['tag']['id'] = $this->PhotoUserTag->getLastInsertId();
                $return_array['tag']['text'] = $this->request->data['PhotoUserTag']['name'];
                $return_array['tag']['left'] = $this->request->data['PhotoUserTag']['left'];
                $return_array['tag']['top'] = $this->request->data['PhotoUserTag']['top'];
                $return_array['tag']['width'] = $this->request->data['PhotoUserTag']['width'];
                $return_array['tag']['height'] = $this->request->data['PhotoUserTag']['height'];
                $return_array['tag']['isDeleteEnable'] = true;
                echo '(' . json_encode($return_array) . ')';
            }
        }
        exit;
    }
    public function face_diplaytag($id = null) 
    {
        $return_array = array();
        $tags = $this->PhotoUserTag->find('all', array(
            'conditions' => array(
                'PhotoUserTag.photo_id' => $id
            ) ,
            'recursive' => -1
        ));
        $return_array['Image']['0']['id'] = $id;
        if (!empty($tags)) {
            foreach($tags as $key => $tag) {
                $return_array['Image']['0']['Tags'][$key]['id'] = $tag['PhotoUserTag']['id'];
                $return_array['Image']['0']['Tags'][$key]['text'] = $tag['PhotoUserTag']['name'];
                $return_array['Image']['0']['Tags'][$key]['left'] = $tag['PhotoUserTag']['left'];
                $return_array['Image']['0']['Tags'][$key]['top'] = $tag['PhotoUserTag']['top'];
                $return_array['Image']['0']['Tags'][$key]['width'] = $tag['PhotoUserTag']['width'];
                $return_array['Image']['0']['Tags'][$key]['height'] = $tag['PhotoUserTag']['height'];
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $return_array['Image']['0']['Tags'][$key]['isDeleteEnable'] = true;
                } else {
                    if ($tag['PhotoUserTag']['user_id'] == $this->Auth->user('id')) {
                        $return_array['Image']['0']['Tags'][$key]['isDeleteEnable'] = true;
                    } else {
                        $return_array['Image']['0']['Tags'][$key]['isDeleteEnable'] = false;
                    }
                }
            }
        }
        $return_array['options']['literals']['removeTag'] = "Remove tag";
        $return_array['options']['tag']['flashAfterCreation'] = "true";
        echo '(' . json_encode($return_array) . ')';
        exit;
    }
    public function face_deletetag() 
    {
        if ($this->PhotoUserTag->delete($_REQUEST['tag-id'])) {
            $return_array['result'] = 'true';
            $return_array['message'] = 'ooops';
            echo '(' . json_encode($return_array) . ')';
        }
        exit;
    }
    public function admin_move_to() 
    {
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $selectedIds = array();
            foreach($this->request->data[$this->modelClass] as $primary_key_id => $is_checked) {
                if ($is_checked['id']) {
                    $selectedIds[] = $primary_key_id;
                }
            }
            if ($actionid && !empty($selectedIds)) {
                switch ($actionid) {
                    case ConstMoreAction::Inactive:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_active' => 0
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been unapproved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Active:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_active' => 1
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been approved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
                        $this->{$this->modelClass}->deleteAll(array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been deleted') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Hotties:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_hotties' => 1
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been updated as Hotties') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function random_photo() 
    {
        $conditions = array();
        $conditions['Photo.is_random'] = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Attachment' => array(
                    'fields' => array(
                        'Attachment.id',
                        'Attachment.filename',
                        'Attachment.dir',
                        'Attachment.width',
                        'Attachment.height'
                    )
                ) ,
            ) ,
            'fields' => array(
                'Photo.id',
                'Photo.title',
                'Photo.description',
                'Photo.url',
            ) ,
            'limit' => 8,
            'recursive' => 2
        );
        $photos = $this->paginate();
        $this->set('photos', $photos);
    }
}
?>