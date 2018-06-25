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
class UserLoginsController extends AppController
{
    public $name = 'UserLogins';
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q',
        ));
        $this->pageTitle = __l('User Logins');
        $conditions = array();
        if (!empty($this->request->params['named']['username'])) {
            $user = $this->UserLogin->User->find('first', array(
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
            $user = $this->UserLogin->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->request->data[$this->modelClass]['user_id']
                ) ,
                'fields' => array(
                    'User.id',
                    'User.username',
                ) ,
                'recursive' => -1
            ));
            $conditions['User.id'] = $this->request->data[$this->modelClass]['user_id'];
            $this->pageTitle.= ' - ' . $this->request->params['named']['username'] = $user['User']['username'];
        }
        if (!empty($user)) {
            $this->request->data[$this->modelClass]['user_id'] = $user['User']['id'];
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 0;
            $this->pageTitle.= __l(' - Registered today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 7;
            $this->pageTitle.= __l(' - Registered in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(UserLogin.created) <= '] = 30;
            $this->pageTitle.= __l(' - Registered in this month');
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::OpenID) {
                $conditions['User.is_openid_register'] = 1;
                $this->pageTitle.= __l(' - Registered through OpenID ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Gmail) {
                $conditions['User.is_gmail_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Gmail ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Yahoo) {
                $conditions['User.is_yahoo_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Yahoo ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['User.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Normal) {
                $conditions['User.is_yahoo_register'] = 0;
                $conditions['User.is_gmail_register'] = 0;
                $conditions['User.is_openid_register'] = 0;
                $conditions['User.is_facebook_register'] = 0;
                $conditions['User.is_twitter_register'] = 0;
                $this->pageTitle.= __l(' - Site ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['User.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Twitter) {
                $conditions['User.is_twitter_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Twitter ');
            } else if ($this->request->params['named']['filter_id'] == ConstMoreAction::Facebook) {
                $conditions['User.is_facebook_register'] = 1;
                $this->pageTitle.= __l(' - Registered through Facebook ');
            }
            $this->request->params['named']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['UserLogin']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->UserLogin->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
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
                'User' => array(
                    'UserAvatar',
                )
            ) ,
            'order' => array(
                'UserLogin.id' => 'desc'
            ) ,
        );
           if (!empty($this->request->data['UserLogin']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->data['UserLogin']['q']
            ));
        }
        $this->set('userLogins', $this->paginate());
        $users = $this->UserLogin->User->find('list');
        $moreActions = $this->UserLogin->moreActions;
        $this->set(compact('moreActions', 'users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserLogin->delete($id)) {
            $this->Session->setFlash(__l('User Login deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>