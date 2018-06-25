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
class UserOpenidsController extends AppController
{
    public $name = 'UserOpenids';
    public $components = array(
        'Openid'
    );
    public $uses = array(
        'UserOpenid',
        'User'
    );
    public function index() 
    {
        $this->pageTitle = __l('User Openids');
        $this->paginate = array(
            'conditions' => array(
                'UserOpenid.user_id' => $this->Auth->user('id')
            )
        );
        $this->UserOpenid->recursive = -1;
        $this->set('userOpenids', $this->paginate());
    }
    public function add() 
    {
        $this->pageTitle = __l('Add New Openid');
        if (!empty($this->request->data)) {
            $this->UserOpenid->set($this->request->data);
            if ($this->UserOpenid->validates()) {
                // send to openid public function with open id url and redirect page
                if (!empty($this->request->data['UserOpenid']['openid']) && $this->request->data['UserOpenid']['openid'] != 'Click to Sign In' && $this->request->data['UserOpenid']['openid'] != 'http://') {
                    $this->request->data['UserOpenid']['redirect_page'] = 'add';
                    $this->_openid();
                }
            }
        }
        // handle the fields return from openid
        if (count($_GET) > 1) {
            $returnTo = Router::url(array(
                'controller' => 'user_openids',
                'action' => 'add'
            ) , true);
            $response = $this->Openid->getResponse($returnTo);
            if ($response->status == Auth_OpenID_SUCCESS) {
                $this->request->data['UserOpenid']['openid'] = $response->identity_url;
                $this->request->data['UserOpenid']['user_id'] = $this->Auth->user('id');
            } else {
                $this->Session->setFlash(__l('Authenticated failed or you may not have profile in your OpenID account'));
            }
        }
        // check the auth user id is set in the useropenid data
        if (!empty($this->request->data['UserOpenid']['user_id'])) {
            $this->UserOpenid->create();
            if ($this->UserOpenid->save($this->request->data)) {
                $this->Session->setFlash(__l('User Openid has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('User Openid could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function _openid() 
    {
        $returnTo = Router::url(array(
            'controller' => 'user_openids',
            'action' => $this->request->data['UserOpenid']['redirect_page']
        ) , true);
        $siteURL = Router::url(array(
            '/'
        ) , true);
        // send openid url and fields return to our server from openid
        if (!empty($this->request->data)) {
            try {
                $this->Openid->authenticate($this->request->data['UserOpenid']['openid'], $returnTo, $siteURL, array() , array());
            }
            catch(InvalidArgumentException $e) {
                $this->Session->setFlash(__l('Invalid OpenID') , 'default', null, 'error');
            }
            catch(Exception $e) {
                $this->Session->setFlash(__l($e->getMessage()));
            }
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $user = $this->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.is_openid_register'
            ) ,
            'recursive' => -1
        ));
        //Condition added to check user should have atleast one OpenID account for login
        if ($this->UserOpenid->find('count', array(
            'conditions' => array(
                'UserOpenid.user_id' => $this->Auth->user('id')
            )
        )) > 1 || $user['User']['is_openid_register'] == 0) {
            if ($this->UserOpenid->delete($id)) {
                $this->Session->setFlash(__l('User Openid deleted') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        } else {
            $this->Session->setFlash(__l('Sorry, you registered through OpenID account. So you should have atleast one OpenID account for login') , 'default', null, 'error');
            $this->redirect(array(
                'action' => 'index'
            ));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('User Openids');
        $this->UserOpenid->recursive = 0;
        $this->set('userOpenids', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add User Openid');
        if (!empty($this->request->data)) {
            $this->UserOpenid->create();
            if ($this->UserOpenid->save($this->request->data)) {
                $this->Session->setFlash(__l(' User Openid has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' User Openid could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->UserOpenid->User->find('list');
        $this->set(compact('users'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->UserOpenid->delete($id)) {
            $this->Session->setFlash(__l('User Openid deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>