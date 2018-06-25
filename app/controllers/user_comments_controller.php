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
class UserCommentsController extends AppController
{
    public $name = 'UserComments';
    public function beforeFilter() 
    {
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_user_comment_on_system_flag')) {
            $this->UserComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index() 
    {
        $this->pageTitle = __l('User Comments');
        $this->paginate = array(
            'conditions' => array(
                'UserComment.comment_user_id' => isset($this->request->params['named']['user_id']) ? $this->request->params['named']['user_id'] : '',
                'UserComment.admin_suspend' => 0,
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                )
            ) ,
            'order' => 'UserComment.id DESC',
            'limit' => 5,
            'recursive' => 3,
        );
        $usercomments = $this->paginate();
        $username = isset($this->request->params['named']['username']) ? $this->request->params['named']['username'] : '';
        $this->set('username', $username);
        $this->set('userComments', $usercomments);
    }
    public function view($id = null, $view_name = 'view') 
    {
        $this->pageTitle = __l('User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userComment = $this->UserComment->find('first', array(
            'conditions' => array(
                'UserComment.id = ' => $id
            ) ,
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
            ) ,
            'recursive' => 0,
        ));
        if (empty($userComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        //$this->pageTitle.= ' - ' . $userComment['UserComment']['id'];
        $this->set('userComment', $userComment);
        $this->render($view_name);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add User Comment');
        if (!empty($this->request->data)) {
            $user = $this->UserComment->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data['UserComment']['comment_user_id']
                ) ,
                'fields' => array(
                    'User.username',
                    'User.user_type_id',
                    'User.email',
                    'User.id'
                ) ,
                'contain' => array(
                    'UserProfile',
                ) ,
                'recursive' => 1
            ));
            $this->request->data['UserComment']['user_id'] = $this->Auth->user('id');
            $this->request->data['UserComment']['ip_id'] =  $this->UserComment->toSaveIp();
            $this->UserComment->create();
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Comment has been added') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'view',
                        $user['User']['username']
                    ));
                } else {
                    $this->setAction('view', $this->UserComment->getLastInsertId() , 'view_ajax');
                }
            } else {
                $this->Session->setFlash(__l('User Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
            $this->set('user', $user);
        }
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $userComment = $this->UserComment->find('first', array(
                'conditions' => array(
                    'UserComment.comment_user_id' => $this->request->data['UserComment']['comment_user_id']
                ) ,
                'CommentUser' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'recursive' => 2,
            ));
            $this->set('username', $userComment['CommentUser']['username']);
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(__l(' User Comment has been updated') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    $this->redirect(array(
                        'controller' => 'users',
                        'action' => 'view',
                        $userComment['CommentUser']['username']
                    ));
                } else {
                    $this->setAction('view', $this->UserComment->getLastInsertId() , 'view_ajax');
                }
            } else {
                $this->Session->setFlash(__l(' User Comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $userComment = $this->UserComment->find('first', array(
                'conditions' => array(
                    'UserComment.id' => $id
                ) ,
                'CommentUser' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
                'recursive' => 2,
            ));
            $this->set('username', $userComment['CommentUser']['username']);
        }
        $users = $this->UserComment->User->find('list');
        $this->set(compact('users'));
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $userComments = $this->UserComment->find('first', array(
            'conditions' => array(
                'UserComment.id' => $id
            ) ,
            'fields' => array(
                'UserComment.comment_user_id'
            ) ,
            'recursive' => -1
        ));
        $user = $this->UserComment->User->find('first', array(
            'conditions' => array(
                'User.id' => $userComments['UserComment']['comment_user_id']
            ) ,
            'fields' => array(
                'User.username'
            ) ,
            'recursive' => -1
        ));
        if ($this->UserComment->delete($id)) {
            $this->Session->setFlash(__l('User Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'users',
                'action' => 'view',
                $user['User']['username']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('User Comments');
        $conditions = array();
        if (!empty($this->request->params['named']['user_comment'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['user_comment']
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
            $this->pageTitle.= sprintf(__l(' - User - %s') , $user['User']['username']);
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['UserComment.is_active'] = 1;
                $conditions['UserComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['UserComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['UserComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['UserComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->UserComment->recursive = 0;
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
                'CommentUser' => array(
                    'fields' => array(
                        'CommentUser.username'
                    )
                ) ,
            ) ,
            'order' => array(
                'UserComment.id' => 'desc'
            )
        );
        $this->set('active', $this->UserComment->find('count', array(
            'conditions' => array(
                'UserComment.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->UserComment->find('count', array(
            'conditions' => array(
                'UserComment.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->UserComment->find('count', array(
            'conditions' => array(
                'UserComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->UserComment->find('count', array(
            'conditions' => array(
                'UserComment.admin_suspend' => 1,
            )
        )));
        $this->set('userComments', $this->paginate());
        $moreActions = $this->UserComment->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add User Comment');
        if (!empty($this->request->data)) {
            $this->UserComment->create();
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Comment has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' User Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->UserComment->User->find('list');
        $this->set(compact('users'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit User Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->UserComment->save($this->request->data)) {
                $this->Session->setFlash(__l(' User Comment has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' User Comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->UserComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['UserComment']['id'];
        $users = $this->UserComment->User->find('list');
        $this->set(compact('users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserComment->delete($id)) {
            $this->Session->setFlash(__l('User Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>