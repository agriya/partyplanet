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
class VideosController extends AppController
{
    public $name = 'Videos';
    public $uses = array(
        'Video',
        'PrivacyType'
    );
    public $components = array(
        'OauthConsumer'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Security->disabledFields = array(
            'Attachment',
            'Video.makeDelete'
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_video_on_system_flag')) {
            $this->Video->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function home() 
    {
        $this->pageTitle = __l('Videos');
    }
    public function index($viewname = 'index') 
    {
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $this->pageTitle = __l('Videos');
        $limit = 20;
        $conditions = array();
        if (!empty($this->request->params['named']['keyword'])) {
            $this->request->data['Video']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['Video.title Like'] = '%' . $this->request->data['Video']['keyword'] . '%';
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->data['Video']['keyword']);
        }
        if (!empty($this->request->params['named']['username'])) {
            $conditions['User.username'] = $this->request->params['named']['username'];
            $this->pageTitle.= __l(sprintf(' - User - %s', $this->request->params['named']['username']));
            $user = $this->Video->User->find('first', array(
                'conditions' => array(
                    'User.username = ' => $this->request->params['named']['username']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                    'User.video_count',
                    'User.video_comment_count',
                ) ,
                'recursive' => -1
            ));
            $this->set('user', $user);
            $this->set('username', $this->request->params['named']['username']);
        } else {
            $conditions['Video.city_id'] = $this->_prefixId;
            $conditions['Video.admin_suspend'] = 0;
        }
        if (!empty($this->request->params['named']['tag']) && Configure::read('Video.is_enable_video_tags')) {
            $videoTag = $this->Video->VideoTag->find('first', array(
                'conditions' => array(
                    'VideoTag.slug' => $this->request->params['named']['tag']
                ) ,
                'fields' => array(
                    'VideoTag.name',
                    'VideoTag.slug'
                ) ,
                'contain' => array(
                    'Video' => array(
                        'fields' => array(
                            'Video.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($videoTag)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->pageTitle.= sprintf(__l(' - Tag - %s') , $videoTag['VideoTag']['name']);
            $this->set('tag_name', $videoTag['VideoTag']['name']);
            $ids = array();
            if (!empty($videoTag)) {
                foreach($videoTag['Video'] as $video) {
                    $ids[] = $video['id'];
                }
            }
            $conditions['Video.id'] = $ids;
            $conditions['Video.is_approved'] = 1;
            $conditions['Video.is_canceled'] = 0;
            
        }
        //video category wise filter
        if (!empty($this->request->params['named']['category'])) {
            $videoCat = $this->Video->VideoCategory->find('first', array(
                'conditions' => array(
                    ' VideoCategory.slug' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'VideoCategory.id'
                ) ,
                'contain' => array(
                    'Video' => array(
                        'fields' => array(
                            'Video.id'
                        )
                    )
                ) ,
                'recursive' => 1
            ));
            if (empty($videoCat)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->pageTitle.= sprintf(__l(' - Tag - %s') , $this->request->params['named']['category']);
            $idc = array();
            if (!empty($videoCat)) {
                foreach($videoCat['VideoCategory'] as $video) {
                    $idc[] = $video['id'];
                }
            }
            $conditions['Video.video_category_id'] = $idc;
            $conditions['Video.is_approved'] = 1;
        }
        // end
        if (!empty($this->request->params['named']['most'])) {
            if ($this->request->params['named']['most'] == ConstURLFilter::Viewed) {
                $this->pageTitle.= __l(' - Most viewed');
                $order['Video.video_view_count'] = 'desc';
            } else if (Configure::read('Video.is_enable_video_comments') && $this->request->params['named']['most'] == ConstURLFilter::Commented) {
                $this->pageTitle.= __l(' - Most commented');
                $order['Video.video_comment_count'] = 'desc';
            } else if (Configure::read('Video.is_enable_video_favorites') && $this->request->params['named']['most'] == ConstURLFilter::Favorited) {
                $this->pageTitle.= __l(' - Most favorited');
                $order['Video.video_favorite_count'] = 'desc';
            } else if (Configure::read('Video.is_enable_video_downloads') && $this->request->params['named']['most'] == ConstURLFilter::Downloaded) {
                $this->pageTitle.= __l(' - Most downloaded');
                $order['Video.video_download_count'] = 'desc';
            } else if (Configure::read('Video.is_enable_video_ratings') && $this->request->params['named']['most'] == ConstURLFilter::Rated) {
                $this->pageTitle.= __l(' - Most rated');
                $order['Video.total_ratings`/`Video.video_rating_count'] = 'desc';
            }
            $conditions['Video.is_approved'] = 1;
        }
        if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'site') {
            $conditions['Video.is_recommend'] = 1;
            $conditions['Video.is_approved'] = 1;
        }
        if (isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home') {
            $order['Video.created'] = 'desc';
            $limit = 6;
            $viewname = 'index_home';
        }
        if (!empty($this->request->params['named']['favorite'])) {
            $this->pageTitle.= sprintf(__l(' - Favorite - %s') , $this->request->params['named']['favorite']);
            $videoFavorites = $this->Video->VideoFavorite->find('list', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['favorite']
                ) ,
                'fields' => array(
                    'VideoFavorite.video_id'
                ) ,
                'recursive' => 0
            ));
            $conditions['Video.id'] = $videoFavorites;
            $viewname = 'index_simple';
        }
        $order['Video.id'] = 'desc';
        $this->Video->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'Video.id',
                'Video.created',
                'Video.title',
                'Video.slug',
                'Video.class',
                'Video.user_id',
                'Video.description',
                'Video.default_thumbnail_id',
                'Video.video_view_count',
                'Video.video_flag_count',
                'Video.video_rating_count',
                '(Video.total_ratings)/(Video.video_rating_count) AS avg_rating',
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
                        'User.video_count',
                        'User.video_comment_count'
                    )
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
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.title',
                        'Event.slug',
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug',
                    )
                ) ,
                'VideoTag' => array(
                    'fields' => array(
                        'VideoTag.name',
                        'VideoTag.slug'
                    )
                )
            ) ,
            'order' => $order,
            'limit' => $limit
        );
        // to find the percentage of the uploaded videos size of the user
        if (!empty($this->request->params['named']['username']) && $this->request->params['named']['username'] == $this->Auth->user('username')) {
            $allowed_size = higher_to_bytes(Configure::read('Video.allowed_videos_size') , Configure::read('Video.allowed_videos_size_unit'));
            $user_size = $this->Video->User->getVideoQuota($this->Auth->user('id'));
            $this->set('size_percentage', round((($user_size/$allowed_size) *100) , 2));
            $this->set('used_size', bytes_to_higher($user_size));
        }
        $this->set('videos', $this->paginate());
        if (!empty($viewname)) {
            $this->render($viewname);
        }
    }
    public function view($slug = null) 
    {
        $this->pageTitle = __l('Video');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $contain = array(
            'User' => array(
                'fields' => array(
                    'User.username'
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
        if (Configure::read('Video.is_enable_video_favorites')) {
            $contain['VideoFavorite'] = array(
                'fields' => array(
                    'VideoFavorite.id'
                ) ,
                'conditions' => array(
                    'VideoFavorite.user_id' => $this->Auth->user('id')
                )
            );
        }
        if (Configure::read('Video.is_enable_video_tags')) {
            $contain['VideoTag'] = array(
                'fields' => array(
                    'VideoTag.slug',
                    'VideoTag.name'
                ) ,
            );
        }
        $contain['Venue'] = array(
            'fields' => array(
                'Venue.id',
                'Venue.name',
                'Venue.slug'
            ) ,
        );
        $contain['Event'] = array(
            'fields' => array(
                'Event.id',
                'Event.title',
                'Event.slug'
            ) ,
        );
        $conditions = array();
        $conditions['Video.slug'] = $slug;
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $conditions['Video.is_approved'] = 1;
            if ($this->Auth->user('id')) {
                $conditions['OR']['Video.user_id'] = $this->Auth->user('id');
                $conditions['OR']['Video.is_private'] = 0;
            } else {
                $conditions['Video.is_private'] = 0;
            }
        }
        $video = $this->Video->find('first', array(
            'conditions' => $conditions,
            'fields' => array(
                'Video.id',
                'Video.created',
                'Video.user_id',
                'Video.title',
                'Video.slug',
                'Video.description',
                'Video.default_thumbnail_id',
                'Video.is_allow_to_comment',
                'Video.is_allow_to_embed',
                'Video.is_allow_to_rating',
                'Video.is_allow_to_download',
                'Video.video_view_count',
                'Video.video_comment_count',
                'Video.video_rating_count',
                'Video.video_favorite_count',
                'Video.video_flag_count',
                'Video.video_download_count',
                'Video.total_ratings',
                '(Video.total_ratings)/(Video.video_rating_count) AS avg_rating',
            ) ,
            'contain' => $contain,
            'recursive' => 2
        ));
        if (empty($video)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        Configure::write('meta.keywords', Configure::read('meta.keywords') . ', ' . $video['Video']['title']);
        Configure::write('meta.description', $video['Video']['title'] . ' posted in ' . Configure::read('site.name'));
        $video['Thumbnail']['id'] = (!empty($video['Video']['default_thumbnail_id'])) ? $video['Video']['default_thumbnail_id'] : '';
        if (!empty($video['Thumbnail'])) {
            $image_options = array(
                'dimension' => 'featured_venue_thumb',
                'class' => '',
                'alt' => $video['Video']['title'],
                'title' => $video['Video']['title'],
                'type' => 'png',
                'full_url' => true,
            );
            $video_image = getImageUrl('Video', $video['Thumbnail'], $image_options, true);
            Configure::write('meta.image', $video_image);
        }
        if (!empty($video['Video']['title'])) {
            Configure::write('meta.name', $video['Video']['title']);
        }
        //Log the video view
        $this->request->data['VideoView']['user_id'] = $this->Auth->user('id');
        $this->request->data['VideoView']['video_id'] = $video['Video']['id'];
        $this->request->data['VideoView']['ip_id'] =  $this->Video->toSaveIp();
        $this->Video->VideoView->create();
        $this->Video->VideoView->save($this->request->data);
        $this->pageTitle.= ' - ' . $video['Video']['title'];
        //Setting video id to fetch in video comment add form
        $this->request->data['VideoComment']['video_id'] = $video['Video']['id'];
        //Setting video id to fetch in video flag add form
        $this->request->data['VideoFlag']['video_id'] = $video['Video']['id'];
        $revisions = $this->Video->getRevisions($video['Video']['id']);
        $this->set(compact('revisions', 'video'));
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Video->User->find('list');
            $this->set(compact('users'));
        }
        $videoFlagCategories = $this->Video->VideoFlag->VideoFlagCategory->find('list');
        $this->set(compact('videoFlagCategories'));
    }
    public function v($slug, $autoplay = false) 
    {
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $video = $this->Video->find('first', array(
            'conditions' => array(
                'Video.slug' => $slug
            ) ,
            'contain' => array(
                'EncodeVideo' => array(
                    'fields' => array(
                        'EncodeVideo.id'
                    )
                )
            ) ,
            'fields' => array(
                'Video.id',
                'Video.default_thumbnail_id',
                'Video.uploaded_via',
                'Video.embed_code'
            ) ,
            'recursive' => 2
        ));
        $flashVars = '?';
        $skin_player_path = Router::url(array(
            'controller' => 'flash',
            'action' => 'videoplayer',
            'overlay.swf'
        ) , true);
        $flashVars.= 'skin=' . $skin_player_path;
        $video_file_hash = 'Video' . '/' . $video['EncodeVideo']['id'] . '.' . md5(Configure::read('Security.salt') . 'Video' . $video['EncodeVideo']['id'] . 'flv' . 'original' . Configure::read('site.name')) . '.' . 'flv';
        $flv_file_path = Router::url(array(
            'controller' => 'files',
            'action' => $video_file_hash
        ) , true);
        $flashVars.= '&file=' . $flv_file_path;
        if (!empty($video['Video']['default_thumbnail_id'])) {
            $image_hash = 'original' . '/' . 'Video' . '/' . $video['Video']['default_thumbnail_id'] . '.' . md5(Configure::read('Security.salt') . 'Video' . $video['Video']['default_thumbnail_id'] . 'jpg' . 'original' . Configure::read('site.name')) . '.' . 'jpg';
            $image_file_path = Router::url(array(
                'controller' => 'img',
                'action' => 'view',
                $image_hash
            ) , true);
            $flashVars.= '&image=' . $image_file_path;
        }
        if (Configure::read('Video.backcolor')) {
            $flashVars.= '&backcolor=' . Configure::read('Video.backcolor');
        }
        if (Configure::read('Video.frontcolor')) {
            $flashVars.= '&frontcolor=' . Configure::read('Video.frontcolor');
        }
        if (Configure::read('Video.lightcolor')) {
            $flashVars.= '&lightcolor=' . Configure::read('Video.lightcolor');
        }
        if (Configure::read('Video.screencolor')) {
            $flashVars.= '&screencolor=' . Configure::read('Video.screencolor');
        }
        if (Configure::read('Video.controlbar')) {
            $flashVars.= '&controlbar=' . Configure::read('Video.controlbar');
        }
        if (Configure::read('Video.logo')) {
            $flashVars.= '&logo=' . Configure::read('Video.logo');
        }
        $flashVars.= '&playlist=none';
        if ($autoplay) {
            $flashVars.= '&autostart=true';
        }
        if (Configure::read('Video.bufferlength')) {
            $flashVars.= '&bufferlength=' . Configure::read('Video.bufferlength');
        }
        if (!Configure::read('Video.icons')) {
            $flashVars.= '&icons=false';
        }
        $flashVars.= '&mute=false';
        if (Configure::read('Video.stretching')) {
            $flashVars.= '&stretching=' . Configure::read('Video.stretching');
        }
        if (Configure::read('Video.volume')) {
            $flashVars.= '&volume=' . Configure::read('Video.volume');
        }
        $video_player_path = Router::url(array(
            'controller' => 'flash',
            'action' => 'videoplayer',
            'player.swf'
        ) , true);
        $this->redirect($video_player_path . $flashVars);
    }
    public function add($slug = null) 
    {
        if (!Configure::read('Video.is_allow_user_to_upload_video') && $this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Add Videos');
        $allowed_size = higher_to_bytes(Configure::read('Video.allowed_videos_size') , Configure::read('Video.allowed_videos_size_unit'));
        $queue_allowed_size = higher_to_bytes(Configure::read('video.file.allowedSize') , Configure::read('video.file.allowedSizeUnits'));
        if ($queue_allowed_size <= $allowed_size) {
            $queue_allowed_size = $allowed_size;
        }
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $used_videos_size = $this->Video->User->getVideoQuota($this->Auth->user('id'));
            $this->set('size_percentage', round((($used_videos_size/$allowed_size) *100) , 2));
            $this->set('used_size', bytes_to_higher($used_videos_size));
            $remaining_allowed_size = $allowed_size-$used_videos_size;
            $this->set('remaining_allowed_size', $remaining_allowed_size);
            $this->set('queue_allowed_size', $queue_allowed_size);
            if ($queue_allowed_size <= $remaining_allowed_size) {
                $this->set('queue_allowed_size', $remaining_allowed_size);
            }
        } else {
            $this->set('remaining_allowed_size', $allowed_size);
            $this->set('queue_allowed_size', $queue_allowed_size);
        }
        if (!empty($this->request->data)) {
            $this->request->data['Video']['ip_id'] = $this->Video->toSaveIp();
            $this->request->data['Video']['city_id'] = $this->_prefixId;
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $this->request->data['Video']['is_approved'] = (Configure::read('Video.is_admin_approved_after_video_upload')) ? 0 : 1;
            } else {
                $this->request->data['Video']['is_approved'] = 1;
            }
            $this->request->data['Video']['is_private'] = (Configure::read('Video.is_show_private_in_video_add')) ? $this->request->data['Video']['is_private'] : 0;
            if (!(Configure::read('Video.is_enable_video_tags'))) {
                unset($this->request->data['Video']['tag']);
            }
            $this->Video->set($this->request->data);
            if ($this->request->data['Video']['uploaded_via'] == ConstUploadedVia::Embed) {
                if ($this->Video->validates()) {
                    $this->request->data['Video']['is_encoded'] = 1;
                    $this->Video->save($this->request->data);
                    $_SESSION['flash_uploaded']['data'][] = $this->Video->id;
                    $this->Session->setFlash(__l('Video has been added but the video are subjected to approval by the admin.') , 'default', null, 'success');
                    $video_update_path = Router::url(array(
                        'controller' => 'videos',
                        'action' => 'update'
                    ) , true);
                    echo 'redirect#' . $video_update_path;
                    exit;
                }
            } elseif ($this->request->data['Video']['uploaded_via'] == ConstUploadedVia::Record) {
                unset($this->Video->validate['title']);
                unset($this->Video->validate['embed_code']);
                if ($this->Video->validates()) {
                    $this->Video->save($this->request->data);
                    $_SESSION['flash_uploaded']['data'][] = $this->Video->id;
                    $video_update_path = Router::url(array(
                        'controller' => 'videos',
                        'action' => 'update'
                    ) , true);
                    //saving the video file after uploading
                    $source = Configure::read('recorder.red5_file_dir') . $this->Session->read('video_name') . '.flv';
                    $this->request->data['Attachment']['class'] = $this->request->data['Video']['type'];
                    $this->request->data['Attachment']['foreign_id'] = $this->Video->id;
                    $this->request->data['Attachment']['filename']['type'] = 'video/x-flv';
                    $this->request->data['Attachment']['filename']['name'] = $this->Session->read('video_name') . '.flv';
                    $this->request->data['Attachment']['filename']['tmp_name'] = $source;
                    $this->request->data['Attachment']['filename']['size'] = $filesize;
                    $this->request->data['Attachment']['filename']['error'] = 0;
                    $this->Attachment->isCopyUpload(true);
                    $this->Attachment->set($this->request->data);
                    $this->Attachment->create();
                    $this->Attachment->save($this->request->data);
                    $this->Session->setFlash(__l('Recorderd Video has been uploaded succesfully but the video are subjected to approval by the admin.') , 'default', null, 'success');
                    echo 'redirect#' . $video_update_path;
                    exit;
                }
            } elseif (!isset($this->request->data['Attachment'])) {
                unset($this->Video->validate['embed_code']);
                unset($this->Video->validate['title']);
                if ($this->Video->validates()) {
                    $this->XAjax->flashuploadset($this->request->data);
                }
            } else {
                // Check for allowed file size
                $filesize = 0;
                foreach($this->request->data['Attachment'] as $files) {
                    $filesize+= $files['filename']['size'];
                }
                if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                    $is_size_ok = (($used_videos_size+$filesize) <= $allowed_size) ? true : false;
                } else {
                    $is_size_ok = true;
                }
                if ($is_size_ok) {
                    $is_form_valid = true;
                    $upload_video_count = 0;
                    for ($i = 0; $i < Configure::read('Video.maximum_videos_per_upload'); $i++) {
                        if ($this->request->data['Attachment'][$i]['filename']['error'] == 1) {
                            $attachmentValidationError[$i] = sprintf(__l('The file uploaded is too big, only files less than %s permitted') , ini_get('upload_max_filesize'));
                            $is_form_valid = false;
                            $upload_video_count++;
                            continue;
                        }
                        if (!empty($this->request->data['Attachment'][$i]['filename']['tmp_name'])) {
                            $upload_video_count++;
                            $image_info = getimagesize($this->request->data['Attachment'][$i]['filename']['tmp_name']);
                            $this->request->data['Attachment']['filename'] = $this->request->data['Attachment'][$i]['filename'];
                            $this->request->data['Attachment']['filename']['type'] = $image_info['mime'];
                            $this->Video->Attachment->Behaviors->attach('ImageUpload', Configure::read('video.file'));
                            $this->Video->Attachment->set($this->request->data);
                            if (!$this->Video->validates() |!$this->Video->Attachment->validates()) {
                                $attachmentValidationError[$i] = $this->Video->Attachment->validationErrors;
                                $is_form_valid = false;
                                $this->Session->setFlash(__l('Video could not be added. Please, try again.') , 'default', null, 'error');
                            }
                        }
                    }
                    if (!$upload_video_count) {
                        $this->Video->validates();
                        $this->Video->Attachment->validationErrors[0]['filename'] = __l('Required');
                        $is_form_valid = false;
                    }
                    if (!empty($attachmentValidationError)) {
                        foreach($attachmentValidationError as $key => $error) {
                            $this->Video->Attachment->validationErrors[$key]['filename'] = $error;
                        }
                    }
                    if ($is_form_valid) {
                        $this->XAjax->normalupload($this->request->data, true);
                        $this->Session->setFlash(__l('Video has been added') , 'default', null, 'success');
                        $this->redirect(array(
                            'controller' => 'videos',
                            'action' => 'update'
                        ));
                    } else {
                        $this->Session->setFlash(__l('Video could not be added. Please, try again.') , 'default', null, 'error');
                    }
                } else {
                    $this->Session->setFlash(__l('Your allowed video quota is over') , 'default', null, 'error');
                }
            }
        }
        if (!empty($this->request->data['Video']['event_id'])) {
            $this->request->params['named']['event_id'] = $this->request->data['Video']['event_id'];
        }
        if (!empty($this->request->params['named']['event_id'])) {
            $event = $this->Video->User->Event->find('first', array(
                'conditions' => array(
                    'Event.id' => $this->request->params['named']['event_id']
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.description',
                    'Event.slug'
                ) ,
                'recursive' => -1
            ));
            $this->request->data['Video']['class'] = 'Event';
            $this->request->data['Video']['foreign_id'] = $event['Event']['id'];
            $this->request->data['Video']['event_id'] = $event['Event']['id'];
            $this->set('event', $event);
        }
        if (!empty($this->request->data['Video']['venue_id'])) {
            $this->request->params['named']['venue_id'] = $this->request->data['Video']['venue_id'];
        }
        if (!empty($this->request->params['named']['venue_id'])) {
            $venue = $this->Video->User->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $this->request->params['named']['venue_id']
                ) ,
                'contain' => array(
                    'City' => array(
                        'fields' => array(
                            'City.id',
                            'City.name'
                        )
                    ) ,
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug',
                    'Venue.address',
                    'Venue.zip_code'
                ) ,
                'recursive' => 0
            ));
            $this->request->data['Video']['class'] = 'Venue';
            $this->request->data['Video']['foreign_id'] = $venue['Venue']['id'];
            $this->request->data['Video']['venue_id'] = $venue['Venue']['id'];
            $this->set('venue', $venue);
        }
        if (empty($this->request->data['Video']['class'])) {
            $this->request->data['Video']['class'] = 'User';
            $this->request->data['Video']['foreign_id'] = $this->Auth->user('id');
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Video->User->find('list');
            $this->set(compact('users'));
        }
        $privacyTypes = $this->PrivacyType->find('list');
        $this->set(compact('privacyTypes'));
        $videoCategories = $this->Video->VideoCategory->find('list', array(
            'conditions' => array(
                'VideoCategory.is_active' => 1
            )
        ));
        $this->set(compact('videoCategories'));
        $this->request->data['Video']['uploaded_via'] = ConstUploadedVia::File;
        $this->set('uploaded_via', ConstUploadedVia::File);
    }
    public function r() 
    {
        $video_recorder_config_path = Router::url(array(
            'controller' => 'videos',
            'action' => 'video_recorder_config',
            'ext' => 'xml',
        ) , true);
        $video_recorder_path = Router::url(array(
            'controller' => 'flash',
            'action' => 'videorecorder',
            'QuickRecorder.swf'
        ) , true);
        $video_recorder_theme_path = Router::url(array(
            'controller' => 'videos',
            'action' => 'video_recorder_theme',
            'ext' => 'xml',
        ) , true);
        $this->redirect($video_recorder_path . '?settingspath=' . $video_recorder_config_path . '&themePath=' . $video_recorder_theme_path);
    }
    public function video_recorder_config() 
    {
        $md5 = md5(microtime() *mktime());
        $video_name = substr($md5, 0, 5);
        $this->Session->write('video_name', $video_name);
        $this->set('video_name', $video_name);
    }
    public function video_recorder_theme() 
    {
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Video');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->Video->set($this->request->data);
            $video = $this->Video->find('first', array(
                'conditions' => array(
                    'Video.id' => $id
                ) ,
                'fields' => array(
                    'Video.slug',
                    'Video.user_id',
                    'Attachment.filesize',
                ) ,
                'recursive' => 0
            ));
            //$this->request->data['VideoTag']['VideoTag'] = $this->Video->VideoTag->_saveTags($this->request->data['Video']['tags']);
            if ($this->Video->save($this->request->data)) {
                //this is for admin_edit
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin && $this->request->data['Video']['user_id'] != $video['Video']['user_id']) {
                    // Reduce the used videos size in users table
                    $this->Video->User->updateAll(array(
                        'User.video_upload_quota' => 'video_upload_quota - ' . $video['Attachment']['filesize']
                    ) , array(
                        'User.id' => $video['Video']['user_id']
                    ));
                    // Update the used videos size in users table
                    $this->Video->User->updateAll(array(
                        'User.video_upload_quota' => 'video_upload_quota + ' . $video['Attachment']['filesize']
                    ) , array(
                        'User.id' => $this->request->data['Video']['user_id']
                    ));
                }
                $this->Session->setFlash(__l('Video has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'videos',
                    'action' => 'view',
                    $video['Video']['slug'],
                    'admin' => false
                ));
            }
        } else {
            $conditions['Video.id'] = $id;
            //this is for admin_edit
            if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
                $conditions['Video.user_id'] = $this->Auth->user('id');
            }
            $this->request->data = $this->Video->find('first', array(
                'conditions' => $conditions,
                'fields' => array(
                    'Video.title',
                    'Video.description',
                    'Video.slug',
                    'Video.user_id',
                    'Video.default_thumbnail_id',
                    'Video.is_allow_to_comment',
                    'Video.is_allow_to_embed',
                    'Video.is_allow_to_rating',
                    'Video.is_allow_to_download',
                    'Video.is_adult_video',
                    'Video.is_private',
                    'Video.is_approved',
                    'Video.is_featured',
                    'Video.is_recommend',
                    'Attachment.filename',
                    'Attachment.dir',
                    'Attachment.id',
                    'Attachment.width',
                    'Attachment.height',
                ) ,
                'contain' => array(
                    'Attachment',
                    'VideoTag' => array(
                        'fields' => array(
                            'name'
                        )
                    )
                )
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Video']['tag'] = $this->Video->formatTags($this->request->data['VideoTag']);
        }
        $privacyTypes = $this->PrivacyType->find('list');
        $this->set(compact('privacyTypes'));
        //this is for admin_edit
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Video->User->find('list');
            $this->set(compact('users'));
        }
        $this->pageTitle.= ' - ' . $this->request->data['Video']['title'];
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!$this->Video->deleteAll(array(
            'Video.user_id' => $this->Auth->user('id') ,
            'Video.id' => $id
        ))) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->Session->setFlash(__l('Video deleted') , 'default', null, 'success');
        $this->redirect(array(
            'action' => 'index',
            'username' => $this->Auth->user('username')
        ));
    }
    public function download($slug = null) 
    {
        $video = $this->Video->find('first', array(
            'conditions' => array(
                'Video.slug' => $slug
            ) ,
            'recursive' => 0
        ));
        $filename = substr($video['Attachment']['filename'], 0, strrpos($video['Attachment']['filename'], '.'));
        $file_extension = substr($video['Attachment']['filename'], strrpos($video['Attachment']['filename'], '.') +1, strlen($video['Attachment']['filename']));
        $file_path = str_replace('\\', '/', 'media' . DS . $video['Attachment']['dir'] . DS . $video['Attachment']['filename']);
        //Log the video download
        $this->request->data['VideoDownload']['user_id'] = $this->Auth->user('id');
        $this->request->data['VideoDownload']['video_id'] = $video['Video']['id'];
        $this->request->data['VideoDownload']['ip_id'] = $this->VideoDownload->toSaveIp();
        $this->Video->VideoDownload->create();
        $this->Video->VideoDownload->save($this->request->data);
        // Code to download
        Configure::write('debug', 0);
        $this->view = 'Media';
        $this->autoLayout = false;
        $this->set('name', trim($filename));
        $this->set('download', true);
        $this->set('extension', trim($file_extension));
        $this->set('mimeType', array(
            $file_extension => get_mime($file_path)
        ));
        $this->set('path', $file_path);
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'username',
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('Videos');
        $conditions = array();
        if (!empty($this->request->params['named']['venue_video'])) {
            $venue = $this->{$this->modelClass}->Venue->find('first', array(
                'conditions' => array(
                    'Venue.slug' => $this->request->params['named']['venue_video']
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($venue)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Venue.id'] = $venue['Venue']['id'];
            $this->pageTitle = __l('Venue Videos');
        }
        if (!empty($this->request->params['named']['category'])) {
            $videoCategory = $this->{$this->modelClass}->VideoCategory->find('first', array(
                'conditions' => array(
                    'VideoCategory.slug' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'VideoCategory.id',
                    'VideoCategory.name',
                    'VideoCategory.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($videoCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Video.video_category_id'] = $videoCategory['VideoCategory']['id'];
            $this->pageTitle.= sprintf(__l(' - Category - %s') , $videoCategory['VideoCategory']['name']);
        }
        if (!empty($this->request->params['named']['event_video'])) {
            $event = $this->{$this->modelClass}->Event->find('first', array(
                'conditions' => array(
                    'Event.slug' => $this->request->params['named']['event_video']
                ) ,
                'fields' => array(
                    'Event.id',
                    'Event.title',
                    'Event.slug'
                ) ,
                'recursive' => -1
            ));
            if (empty($event)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Event.id'] = $event['Event']['id'];
            $this->pageTitle = __l('Event Videos');
        }
        $this->Video->validate = array();
        if (!empty($this->request->params['named']['username'])) {
            $conditions = array(
                'User.username' => $this->request->params['named']['username']
            );
            $this->pageTitle.= sprintf(__l(' - User - %s') , $this->request->params['named']['username']);
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Video']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Video.created) <= '] = 0;
            $this->pageTitle.= __l(' - Added today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Video.created) <= '] = 7;
            $this->pageTitle.= __l(' - Added in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Video.created) <= '] = 30;
            $this->pageTitle.= __l(' - Added in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['Video']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['video']) && $this->request->params['named']['video'] == ConstURLFilter::Commented) {
            $conditions = array(
                'Video.video_comment_count > ' => 0
            );
            $this->pageTitle.= __l(' - Commented videos');
        }
        if (!empty($this->request->params['named']['video']) && $this->request->params['named']['video'] == ConstURLFilter::Flagged) {
            $conditions = array(
                'Video.video_flag_count > ' => 0
            );
            $this->pageTitle.= __l(' - Flagged videos');
        }
        if (!empty($this->request->data['Video']['filter_id'])) {
            if ($this->request->data['Video']['filter_id'] == ConstMoreAction::Approved) {
                $conditions['Video.is_approved'] = 1;
                $conditions['Video.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Approved Videos');
            } else if ($this->request->data['Video']['filter_id'] == ConstMoreAction::Disapproved) {
                $conditions['Video.is_approved'] = 0;
                $this->pageTitle.= __l(' - Pending Videos');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Video.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Video.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            } else if ($this->request->data['Video']['filter_id'] == ConstMoreAction::Featured) {
                $conditions['Video.is_featured'] = 1;
                $this->pageTitle.= __l(' - Featured Videos');
            } else if ($this->request->data['Video']['filter_id'] == ConstMoreAction::NonFeatured) {
                $conditions['Video.is_featured'] = 0;
                $this->pageTitle.= __l(' - Not Featured Videos');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Video']['filter_id'];
        }
        $this->Video->recursive = 1;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.id',
                        'User.username',
                    )
                ) ,
                'Venue' => array(
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug'
                    )
                ) ,
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.title',
                        'Event.slug'
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
                'Thumbnail',
            ) ,
            'limit' => 15,
            'order' => array(
                'Video.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('videos', $this->paginate());
        $this->set('active', $this->Video->find('count', array(
            'conditions' => array(
                'Video.is_approved' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Video->find('count', array(
            'conditions' => array(
                'Video.is_approved' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Video->find('count', array(
            'conditions' => array(
                'Video.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->Video->find('count', array(
            'conditions' => array(
                'Video.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->Video->moreActions;
        $filters = $this->Video->isFilterOptions;
        $this->set('pageTitle', $this->pageTitle);
        $this->set(compact('moreActions', 'filters'));
    }
    public function admin_add($show_upload_via_form = null) 
    {
        $this->setAction('add', $show_upload_via_form);
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
        if ($this->Video->delete($id)) {
            $this->Session->setFlash(__l('Video deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function flashupload() 
    {
        $this->XAjax->flashupload(true);
        $this->autoRender = false;
    }
    public function update() 
    {
        $this->pageTitle = __l('Update videos');
        if (!empty($this->request->data)) {
            $temp_data = $this->request->data;
            $this->request->data = array();
            unset($temp_data['Video']['video_album_id']);
            foreach($temp_data['Video'] as $video) {
                $this->request->data['Video']['id'] = $video['id'];
                $this->request->data['Video']['title'] = $video['title'];
                $this->request->data['Video']['description'] = $video['description'];
                if (Configure::read('Video.is_show_adult_in_video_add')):
                    $this->request->data['Video']['is_adult_video'] = $video['is_adult_video'];
                endif;
                if (Configure::read('Video.is_enable_video_tags')):
                    $this->request->data['Video']['tag'] = $video['tag'];
                endif;
                $this->Video->save($this->request->data);
                if (!empty($this->request->data['Video']['admin_suspend'])) {
                    $this->Session->setFlash(__l('Uploaded Video has been auto suspended.') , 'default', null, 'error');
                } else {
                    $this->Session->setFlash(__l('Video has been added') , 'default', null, 'success');
                }
            }
            $this->redirect(array(
                'controller' => 'videos',
                'action' => 'index',
                'username' => $this->Auth->user('username')
            ));
        } else {
            $uploaded_videos = Cache::read(session_id());
            Cache::delete(session_id());
            $this->Session->delete('flash_uploaded');
            $this->Session->delete('flashupload_data');
            if (!empty($uploaded_videos)) {
                $videos = $this->Video->find('all', array(
                    'conditions' => array(
                        'Video.id' => $uploaded_videos
                    ) ,
                    'contain' => array(
                        'Attachment',
                        'VideoTag' => array(
                            'fields' => array(
                                'VideoTag.name'
                            )
                        ) ,
                        'User'
                    ) ,
                    'order' => array(
                        'Video.id' => 'desc'
                    ) ,
                    'recursive' => 2
                ));
                if (!empty($videos)) {
                    if (Configure::read('Video.is_enable_video_tags')):
                        $videos = Set::merge($videos, $this->Video->formatTags($videos));
                    endif;
                    $this->set('videos', $videos);
                    $filesize = 0;
                    $user = $this->Video->User->find('first', array(
                        'conditions' => array(
                            'User.id' => $videos[0]['Video']['user_id']
                        ) ,
                        'recursive' => -1
                    ));
                    foreach($videos as $video) {
                        $this->request->data['Video'][$video['Video']['id']]['id'] = $video['Video']['id'];
                        $this->request->data['Video'][$video['Video']['id']]['title'] = $video['Video']['title'];
                        $this->request->data['Video'][$video['Video']['id']]['description'] = $video['Video']['description'];
                        if (Configure::read('Video.is_enable_video_tags')):
                            $this->request->data['Video'][$video['Video']['id']]['tag'] = $video['VideoTag'];
                        endif;
                        if (Configure::read('Video.is_show_adult_in_video_add')):
                            $this->request->data['Video'][$video['Video']['id']]['is_adult_video'] = $video['Video']['is_adult_video'];
                        endif;
                        if (Configure::read('Video.is_show_private_in_video_add')):
                            $this->request->data['Video'][$video['Video']['id']]['is_private'] = $video['Video']['is_private'];
                        endif;
                        $this->request->data['Video'][$video['Video']['id']]['is_featured'] = $video['Video']['is_featured'];
                        $this->request->data['Video'][$video['Video']['id']]['is_approved'] = $video['Video']['is_approved'];
                        $this->request->data['Video'][$video['Video']['id']]['is_allow_to_comment'] = $video['Video']['is_allow_to_comment'];
                        $this->request->data['Video'][$video['Video']['id']]['is_allow_to_embed'] = $video['Video']['is_allow_to_embed'];
                        $this->request->data['Video'][$video['Video']['id']]['is_allow_to_rating'] = $video['Video']['is_allow_to_rating'];
                        $this->request->data['Video'][$video['Video']['id']]['is_allow_to_download'] = $video['Video']['is_allow_to_download'];
                        $this->request->data['Video'][$video['Video']['id']]['tag'] = $video['VideoTag'];
                        $filesize+= $video['Attachment']['filesize'];
						$url = Router::url(array(
							'controller' => 'videos',
							'action' => 'view',
							'admin' => false,
							$video['Video']['slug'],
						) , true);
						// video willn't be posted if it is autoflagged and suspend
						if (!$video['Video']['admin_suspend'] && $video['Video']['is_approved']) {
							$image_options = array(
								'dimension' => 'normal_thumb',
								'class' => '',
								'alt' => $video['Video']['title'],
								'title' => $video['Video']['title'],
								'type' => 'jpg'
							);
							$post_data = array();
							$post_data['message'] = $user['User']['username'] . ' ' . __l('addd a new video "') . '' . $video['Video']['title'] . __l('" in ') . Configure::read('site.name');
							$post_data['image_url'] = Router::url('/', true) . getImageUrl('Video', $video['Attachment'], $image_options);
							$post_data['link'] = $url;
							$post_data['description'] = $video['Video']['description'];
							// Post on user facebook
							if (Configure::read('social_networking.post_video_on_user_facebook')) {
								if ($user['User']['fb_user_id'] > 0) {
									$post_data['fb_user_id'] = $user['User']['fb_user_id'];
									$post_data['fb_access_token'] = $user['User']['fb_access_token'];
									$getFBReturn = $this->postOnFacebook($post_data, 0);
								}
							}
							// post on user twitter
							if (Configure::read('social_networking.post_video_on_user_twitter')) {
								if (!empty($user['User']['twitter_access_token']) && !empty($user['User']['twitter_access_key'])) {
									$post_data['twitter_access_key'] = $user['User']['twitter_access_key'];
									$post_data['twitter_access_token'] = $user['User']['twitter_access_token'];
									$getTewwtReturn = $this->postOnTwitter($post_data, 0);
								}
							}
							if (Configure::read('video.post_on_facebook')) { // post on site facebook
								$getFBReturn = $this->postOnFacebook($post_data, 1);
							}
							if (Configure::read('video.post_on_twitter')) { // post on site twitter
								$getTewwtReturn = $this->postOnTwitter($post_data, 1);
							}
						}
                    }
                    $this->Video->User->updateAll(array(
                        'User.video_upload_quota' => 'video_upload_quota + ' . $filesize
                    ) , array(
                        'User.id' => $videos[0]['Video']['user_id']
                    ));
                    $privacyTypes = $this->PrivacyType->find('list');
                    $this->set(compact('privacyTypes'));
                }
            } else {
                $this->Session->setFlash(__l('Sorry, no latest uploaded videos to update') , 'default', null, 'error');
                $this->redirect(array(
                    'controller' => 'videos',
                    'action' => 'index'
                ));
            }
        }
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
                            $this->modelClass . '.is_approved' => 0
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been unapproved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Active:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_approved' => 1
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

                    case ConstMoreAction::Featured:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_featured' => 1
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been featured') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::NonFeatured:
                        $this->{$this->modelClass}->updateAll(array(
                            $this->modelClass . '.is_featured' => 0
                        ) , array(
                            $this->modelClass . '.id' => $selectedIds
                        ));
                        $this->Session->setFlash(__l('Checked ' . $this->modelClass . ' has been unfeatured') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
}
?>