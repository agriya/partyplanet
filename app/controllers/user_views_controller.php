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
class UserViewsController extends AppController
{
    public $name = 'UserViews';
    public function admin_index() 
    {
        $this->pageTitle = __l('User Views');
        $conditions = array();
        if (!empty($this->request->params['named']['username'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.username' => $this->request->params['named']['username']
                ) ,
                'fields' => array(
                    'User.id'
                ) ,
                'recursive' => -1
            ));
            $conditions['User.username'] = $this->request->params['named']['username'];
            $this->pageTitle.= ' - ' . $this->request->params['named']['username'];
        }
        if (!empty($this->request->data[$this->modelClass]['user_id'])) {
            $user = $this->{$this->modelClass}->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data[$this->modelClass]['user_id']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username'
                ) ,
                'recursive' => -1
            ));
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'];
            $this->pageTitle.= ' - ' . $this->request->params['named']['username'] = $user['User']['username'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserView.created) <= '] = 0;
            $this->pageTitle.= __l(' - User views today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserView.created) <= '] = 7;
            $this->pageTitle.= __l(' - User views in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserView.created) <= '] = 30;
            $this->pageTitle.= __l(' - User views in this month');
        }
        if (!empty($user)) {
            $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
        }
        $this->UserView->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'UserView.id',
                'UserView.created',
                'UserView.ip',
                'User.username',
                'ViewingUser.username'
            ) ,
            'order' => 'UserView.id DESC',
        );
        $this->set('userViews', $this->paginate());
        $users = $this->UserView->User->find('list');
        $moreActions = $this->UserView->moreActions;
        $this->set(compact('moreActions', 'users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserView->delete($id)) {
            $this->Session->setFlash(__l('User View deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>