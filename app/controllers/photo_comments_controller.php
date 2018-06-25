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
class PhotoCommentsController extends AppController
{
    public $name = 'PhotoComments';
    public $components = array(
        'Email'
    );
    public $uses = array(
        'PhotoComment',
        'EmailTemplate'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('photo.is_allow_photo_comment')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_photo_comment_on_system_flag')) {
            $this->PhotoComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($photo_id = null) 
    {
        if (is_null($photo_id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Photo Comments');
        $this->PhotoComment->recursive = 0;
        $this->paginate = array(
            'conditions' => array(
                'PhotoComment.photo_id' => $photo_id,
                'PhotoComment.admin_suspend' => 0,
            ) ,
            'contain' => array(
                'Photo' => array(
                    'fields' => array(
                        'Photo.user_id'
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
                'PhotoComment.id' => 'desc'
            )
        );
        $this->set('photoComments', $this->paginate());
    }
    public function view($id = null, $view_name = 'view') 
    {
        $this->pageTitle = __l('Photo Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $photoComment = $this->PhotoComment->find('first', array(
            'conditions' => array(
                'PhotoComment.id = ' => $id,
                'PhotoComment.admin_suspend' => 0,
            ) ,
            'contain'=>array(
            'Photo',
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
            ) ),
            'recursive' => 2,
        ));
        if (empty($photoComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $photoComment['PhotoComment']['id'];
        $this->set('photoComment', $photoComment);
        $this->render($view_name);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Photo Comment');
        if (!empty($this->request->data)) {
            $photo = $this->PhotoComment->Photo->find('first', array(
                'conditions' => array(
                    'Photo.id' => $this->request->data['PhotoComment']['photo_id']
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
            if (empty($photo)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->PhotoComment->create();
            $this->request->data['PhotoComment']['ip_id'] = $this->PhotoComment->toSaveIp();
            if ($this->PhotoComment->save($this->request->data)) {
                // To send email when post comments
                if (Configure::read('photo.is_send_email_on_photo_comments')) {
                    $emailFindReplace = array(
                        '##USERNAME##' => $this->Auth->user('username') ,
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##PHOTO_LINK##' => Router::url(array(
                            'controller' => 'photos',
                            'action' => 'view',
                            $photo['Photo']['slug']
                        ) , true) ,
                        '##COMMENT##' => $this->request->data['PhotoComment']['comment'],
                        '##PHOTO_USERNAME##' => $photo['User']['username'],
                        '##SITE_URL##' => Router::url('/', true) ,
                    );
                    $email_message = $this->EmailTemplate->selectTemplate('New Comment Photo');
                    $this->Email->from = ($email_message['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email_message['from'];
                    $this->Email->replyTo = ($email_message['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email_message['reply_to'];
                    $this->Email->to = $photo['User']['email'];
                    $this->Email->subject = strtr($email_message['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email_message['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email_message['email_content'], $emailFindReplace));
                }
                $this->Session->setFlash(__l('Photo Comment has been added') , 'default', null, 'success');
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('view', $this->PhotoComment->id, 'view_ajax');
                } else {
                    $this->redirect(array(
                        'controller' => 'photos',
                        'action' => 'view',
                        $photo['Photo']['slug'],
                        'admin' => false
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Photo Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
            $this->set('photo', $photo);
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->PhotoComment->User->find('list');
            $this->set(compact('users'));
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $photo = $this->PhotoComment->find('first', array(
            'conditions' => array(
                'PhotoComment.id' => $id
            ) ,
            'fields' => array(
                'Photo.slug'
            ) ,
            'recursive' => 0
        ));
        if (empty($photo)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PhotoComment->delete($id)) {
            $this->Session->setFlash(__l('Photo Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'photos',
                'action' => 'view',
                $photo['Photo']['slug']
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
        $this->pageTitle = __l('Photo Comments');
        $conditions = array();
        if (!empty($this->request->params['named']['user_photo_comment'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user_photo_comment']
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
        } elseif (!empty($this->request->params['named']['photo']) || !empty($this->request->params['named']['photo_id'])) {
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoComment.created) <= '] = 0;
            $this->pageTitle.= __l(' - Commented today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoComment.created) <= '] = 7;
            $this->pageTitle.= __l(' - Commented in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(PhotoComment.created) <= '] = 30;
            $this->pageTitle.= __l(' - Commented in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['PhotoComment']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['PhotoComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['PhotoComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['PhotoComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['PhotoComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->PhotoComment->recursive = 0;
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
                'Photo' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.id',
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.width',
                            'Attachment.height'
                        )
                    ) ,
                    'fields' => array(
                        'Photo.title',
                        'Photo.slug'
                    )
                )
            ) ,
            'order' => array(
                'PhotoComment.id' => 'desc'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $this->set('system_flagged', $this->PhotoComment->find('count', array(
            'conditions' => array(
                'PhotoComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->PhotoComment->find('count', array(
            'conditions' => array(
                'PhotoComment.admin_suspend' => 1,
            )
        )));
        $this->set('all', $this->PhotoComment->find('count'));
        $moreActions = $this->PhotoComment->moreActions;
        $this->set('photoComments', $this->paginate());
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
        if ($this->PhotoComment->delete($id)) {
            $this->Session->setFlash(__l('Photo Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>