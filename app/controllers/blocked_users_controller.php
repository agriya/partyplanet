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
class BlockedUsersController extends AppController
{
    public $name = 'BlockedUsers';
    public function index() 
    {
        $this->pageTitle = __l('Blocked Users');
        $this->BlockedUser->recursive = 0;
        $this->paginate = array(
            'conditions' => array(
                'BlockedUser.user_id' => $this->Auth->user('id')
            ) ,
            'contain' => array(
                'Blocked' => array(
                    'UserAvatar'
                )
            )
        );
        $this->set('blockedUsers', $this->paginate());
    }
    public function view($id = null) 
    {
        $this->pageTitle = __l('Blocked User');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $blockedUser = $this->BlockedUser->find('first', array(
            'conditions' => array(
                'BlockedUser.id = ' => $id
            ) ,
            'fields' => array(
                'BlockedUser.id',
                'BlockedUser.created',
                'BlockedUser.modified',
                'BlockedUser.user_id',
                'BlockedUser.blocked_user_id',
                'User.id',
                'User.created',
                'User.modified',
                'User.user_type_id',
                'User.username',
                'User.email',
                'User.password',
                'User.helper_rating_count',
                'User.total_helper_rating',
                'User.photo_album_count',
                'User.photo_count',
                'User.blog_count',
                'User.question_count',
                'User.answer_count',
                'User.user_comment_count',
                'User.user_openid_count',
                'User.cookie_hash',
                'User.cookie_time_modified',
                'User.is_openid_register',
                'User.is_agree_terms_conditions',
                'User.is_active',
                'User.is_email_confirmed',
                'User.last_login_ip_id',
                'User.last_logged_in_time',
                'User.user_login_count',
                'User.answer_total_ratings',
                'User.answer_rating_count',
            ) ,
            'recursive' => 0,
        ));
        if (empty($blockedUser)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $blockedUser['BlockedUser']['id'];
        $this->set('blockedUser', $blockedUser);
    }
    public function add($username = null) 
    {
        $this->pageTitle = __l('Add Blocked User');
        // check is user exists
        $user = $this->BlockedUser->User->find('first', array(
            'conditions' => array(
                'User.username' => $username
            ) ,
            'fields' => array(
                'User.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($user)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Check is already added
        $blocked = $this->BlockedUser->find('first', array(
            'conditions' => array(
                'BlockedUser.user_id' => $this->Auth->user('id') ,
                'BlockedUser.blocked_user_id' => $user['User']['id']
            ) ,
            'fields' => array(
                'BlockedUser.id'
            ) ,
            'recursive' => -1
        ));
        if (empty($blocked)) {
            $this->request->data['BlockedUser']['user_id'] = $this->Auth->user('id');
            $this->request->data['BlockedUser']['blocked_user_id'] = $user['User']['id'];
            $this->BlockedUser->create();
            if ($this->BlockedUser->save($this->request->data)) {
                $this->Session->setFlash(__l('User blocked successfully.') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'blocked_users',
                    'action' => 'index'
                ));
            } else {
            }
        } else {
            $this->Session->setFlash(__l('Already added') , 'default', null, 'error');
        }
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Blocked User');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->BlockedUser->save($this->request->data)) {
                $this->Session->setFlash(__l(' Blocked User has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Blocked User could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->BlockedUser->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['BlockedUser']['id'];
        $users = $this->BlockedUser->User->find('list');
        $this->set(compact('users'));
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $blocked = $this->BlockedUser->find('count', array(
            'conditions' => array(
                'BlockedUser.user_id' => $this->Auth->user('id') ,
                'BlockedUser.id' => $id
            )
        ));
        if (!$blocked) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BlockedUser->delete($id)) {
            $this->Session->setFlash(__l('Blocked User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Blocked Users');
        $this->BlockedUser->recursive = 0;
        $this->set('blockedUsers', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Blocked User');
        if (!empty($this->request->data)) {
            $this->BlockedUser->create();
            if ($this->BlockedUser->save($this->request->data)) {
                $this->Session->setFlash(__l(' Blocked User has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Blocked User could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->BlockedUser->User->find('list');
        $this->set(compact('users'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Blocked User');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->BlockedUser->save($this->request->data)) {
                $this->Session->setFlash(__l(' Blocked User has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Blocked User could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->BlockedUser->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['BlockedUser']['id'];
        $users = $this->BlockedUser->User->find('list');
        $this->set(compact('users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->BlockedUser->delete($id)) {
            $this->Session->setFlash(__l('Blocked User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>