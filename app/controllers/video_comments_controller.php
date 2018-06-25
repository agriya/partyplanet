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
class VideoCommentsController extends AppController
{
    public $name = 'VideoComments';
    public $components = array(
        'Email'
    );
    public $uses = array(
        'VideoComment',
        'EmailTemplate'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('Video.is_enable_video_module') && !Configure::read('Video.is_enable_video_comments')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') || !Configure::read('suspicious_detector.auto_suspend_video_comment_on_system_flag')) {
            $this->VideoComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($video_id = null) 
    {
        if (is_null($video_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Video Comments');
        $this->VideoComment->recursive = 0;
        $this->paginate = array(
            'conditions' => array(
                'VideoComment.video_id' => $video_id,
                'VideoComment.admin_suspend' => 0
            ) ,
            'contain' => array(
                'Video' => array(
                    'fields' => array(
                        'Video.user_id'
                    )
                ) ,
                'User' => array(
                    'UserAvatar' => array(
                        'fields' => array(
                            'UserAvatar.id',
                            'UserAvatar.filename',
                            'UserAvatar.dir',
                            'UserAvatar.width',
                            'UserAvatar.height'
                        )
                    ) ,
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    ) ,
                ) ,
            ) ,
            'order' => array(
                'VideoComment.id' => 'desc'
            )
        );
        $this->set('videoComments', $this->paginate());
    }
    public function view($id = null, $view_name = 'view') 
    {
        $this->pageTitle = __l('Video Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $videoComment = $this->VideoComment->find('first', array(
            'conditions' => array(
                'VideoComment.id = ' => $id,
                'VideoComment.admin_suspend' => 0
            ) ,
            'contain'=>array(
            'Video',
            'User' => array(
                'UserAvatar' => array(
                    'fields' => array(
                        'UserAvatar.id',
                        'UserAvatar.dir',
                        'UserAvatar.filename'
                    )
                ) ,
                'fields' => array(
                    'User.user_type_id',
                    'User.username',
                    'User.id',
                    'User.fb_user_id',
                    'User.twitter_avatar_url',
                )
            )) ,
            'recursive' => 2,
        ));
        $this->set('videoComment', $videoComment);
        if ($view_name == 'view_ajax' and empty($videoComment)) {
            $this->Session->setFlash(__l('Video comment has been auto suspended.') , 'default', null, 'error');
        } elseif ($view_name == 'view_ajax') {
            $this->Session->setFlash(__l('Video comment has been added') , 'default', null, 'success');
        }
        $this->pageTitle.= ' - ' . $videoComment['VideoComment']['id'];
        $this->render($view_name);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Video Comment');
        if (!empty($this->request->data)) {
            $video = $this->VideoComment->Video->find('first', array(
                'conditions' => array(
                    'Video.id' => $this->request->data['VideoComment']['video_id']
                ) ,
                'contain' => array(
                    'User' => array(
                        'fields' => array(
                            'User.email',
                            'User.username'
                        )
                    )
                ) ,
                'recursive' => 0
            ));
            if (empty($video)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->VideoComment->create();
            $this->request->data['VideoComment']['ip_id'] =  $this->VideoComment->toSaveIp();
            if ($this->VideoComment->save($this->request->data)) {
                // To send email when post comments
                if (Configure::read('video.is_send_email_on_video_comments')) {
                    $emailFindReplace = array(
                        '##USERNAME##' => $this->Auth->user('username') ,
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##PHOTO_LINK##' => Router::url(array(
                            'controller' => 'videos',
                            'action' => 'view',
                            $video['Video']['slug']
                        ) , true) ,
                        '##COMMENT##' => $this->request->data['VideoComment']['comment']
                    );
                    $email_message = $this->EmailTemplate->selectTemplate('New Comment Video');
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = $video['User']['email'];
                    $this->Email->subject = strtr($email_message['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email_message['email_content'], $emailFindReplace));
                }
                //$this->Session->setFlash(__l('Video Comment has been added') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('view', $this->VideoComment->getLastInsertId() , 'view_ajax');
                } else {
                    $this->redirect(array(
                        'controller' => 'videos',
                        'action' => 'view',
                        $video['Video']['slug'],
                        'admin' => false
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Video Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
            $this->set('video', $video);
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->VideoComment->User->find('list');
            $this->set(compact('users'));
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $video = $this->VideoComment->find('first', array(
            'conditions' => array(
                'Video.user_id' => $this->Auth->user('id') ,
                'VideoComment.id' => $id
            ) ,
            'fields' => array(
                'Video.slug'
            ) ,
            'recursive' => 0
        ));
        if (empty($video)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoComment->delete($id)) {
            $this->Session->setFlash(__l('Video Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'videos',
                'action' => 'view',
                $video['Video']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Video Comments');
        $this->_redirectGET2Named(array(
            'q'
        ));
        $conditions = array();
        if (!empty($this->request->params['named']['user_video_comment'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user_video_comment']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            if (empty($user)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['User.id'] = $user['User']['id'];
        } elseif (!empty($this->request->params['named']['video'])) {
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoComment.created) <= '] = 0;
            $this->pageTitle.= __l(' - Commented today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoComment.created) <= '] = 7;
            $this->pageTitle.= __l(' - Commented in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(VideoComment.created) <= '] = 30;
            $this->pageTitle.= __l(' - Commented in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['VideoComment']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VideoComment.is_active'] = 1;
                $conditions['VideoComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VideoComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['VideoComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['VideoComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->VideoComment->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
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
                ) ,
                'Video' => array(
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
                    'fields' => array(
                        'Video.title',
                        'Video.default_thumbnail_id',
                        'Video.slug'
                    )
                ) ,
            ) ,
            'order' => array(
                'VideoComment.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('active', $this->VideoComment->find('count', array(
            'conditions' => array(
                'VideoComment.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->VideoComment->find('count', array(
            'conditions' => array(
                'VideoComment.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->VideoComment->find('count', array(
            'conditions' => array(
                'VideoComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->VideoComment->find('count', array(
            'conditions' => array(
                'VideoComment.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->VideoComment->moreActions;
        $this->set('pageTitle', $this->pageTitle);
        $this->set('videoComments', $this->paginate());
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VideoComment->delete($id)) {
            $this->Session->setFlash(__l('Video Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>