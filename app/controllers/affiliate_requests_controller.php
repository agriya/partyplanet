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
class AffiliateRequestsController extends AppController
{
    public $name = 'AffiliateRequests';
    public $components = array(
        'Email'
    );
    public $uses = array(
        'AffiliateRequest',
        'EmailTemplate'
    );
    public function beforeFilter() 
    {
        if (!Configure::read('affiliate.is_enabled')) {
            throw new NotFoundException(__l('Invalid request'));
        }
        parent::beforeFilter();
    }
    public function index() 
    {
        $this->pageTitle = __l('Affiliate Requests');
        $this->AffiliateRequest->recursive = 0;
        $this->set('affiliateRequests', $this->paginate());
    }
    public function add() 
    {
        $status = 'add';
        if ($this->Auth->user('is_affiliate_user')) {
            $this->redirect(array(
                'controller' => 'affiliates',
                'action' => 'index'
            ));
        }
        $this->pageTitle = __l('Request Affiliate');
        if (!empty($this->request->data)) {
            if (empty($this->request->data['AffiliateRequest']['user_id'])) $this->request->data['AffiliateRequest']['user_id'] = $this->Auth->user('id');
            if ($this->AffiliateRequest->save($this->request->data)) {
                if (Configure::read('affiliate.is_admin_mail_after_affiliate_request')) {
                    $this->_sendAffiliateRequestMail($this->Auth->user('id'));
                }
                $this->Session->setFlash(__l('Your request added successfully') , 'default', null, 'success');
                if (empty($this->request->params['isAjax'])) {
                    $this->redirect(array(
                        'controller' => 'affiliates',
                        'action' => 'index'
                    ));
                } else {
                    $ajax_url = Router::url(array(
                        'controller' => 'affiliates',
                        'action' => 'index'
                    ) , true);
                    $success_msg = 'redirect*' . $ajax_url;
                    echo $success_msg;
                    exit;
                }
            } else {
                $this->Session->setFlash(__l('Affiliate request could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $user = $this->AffiliateRequest->User->find('first', array(
            'conditions' => array(
                'User.id' => $this->Auth->user('id')
            ) ,
            'fields' => array(
                'User.is_affiliate_user'
            ) ,
            'contain' => array(
                'AffiliateRequest'
            ) ,
            'recursive' => 1
        ));
        $pending_request = $reject_request = 0;
        if (!$user['User']['is_affiliate_user']) {
            if (!empty($user['AffiliateRequest'])) {
                $pending_request = $this->AffiliateRequest->find('count', array(
                    'conditions' => array(
                        'AffiliateRequest.user_id' => $this->Auth->user('id') ,
                        'AffiliateRequest.is_approved' => 0
                    )
                ));
                $reject_request = $this->AffiliateRequest->find('count', array(
                    'conditions' => array(
                        'AffiliateRequest.user_id' => $this->Auth->user('id') ,
                        'AffiliateRequest.is_approved' => 2
                    ) ,
                ));
            }
            if (($pending_request == 0) && ($reject_request != 0)) {
                $status = 'rejected';
            } else if ($pending_request != 0) {
                $status = 'pending';
            } else {
                $status = 'add';
            }
        } else {
            $status = 'add';
        }
        if (!empty($this->request->data)) {
            $status = 'add';
        }
        $ajax_url = Router::url(array(
            'controller' => 'affiliates',
            'action' => 'index'
        ) , true);
        $this->set('redirect_url', $ajax_url);
        $siteCategories = $this->AffiliateRequest->SiteCategory->find('list');
        $this->set(compact('siteCategories'));
        $this->set('status', $status);
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'q'
        ));
        $this->pageTitle = __l('Affiliate Requests');
        $conditions = array();
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['AffiliateRequest']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(AffiliateRequest.created) <= '] = 0;
            $this->pageTitle.= __l(' - Requested today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(AffiliateRequest.created) <= '] = 7;
            $this->pageTitle.= __l(' - Requested in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(AffiliateRequest.created) <= '] = 30;
            $this->pageTitle.= __l(' - Requested in this month');
        }
        if (isset($this->request->params['named']['is_approved'])) {
            $conditions['AffiliateRequest.is_approved'] = $this->request->params['named']['is_approved'];
        }
        $this->AffiliateRequest->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => array(
                'AffiliateRequest.id' => 'desc'
            )
        );
        if (isset($this->request->data['AffiliateRequest']['q'])) {
            $this->paginate['q'] = $this->request->data['AffiliateRequest']['q'];
        }
        $moreActions = $this->AffiliateRequest->moreActions;
        $this->set(compact('moreActions'));
        $this->set('affiliateRequests', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Affiliate Request');
        if (!empty($this->request->data)) {
            $this->AffiliateRequest->create();
            if ($this->AffiliateRequest->save($this->request->data)) {
                $this->Session->setFlash(__l('Affiliate request has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Affiliate request could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->AffiliateRequest->User->find('list');
        $siteCategories = $this->AffiliateRequest->SiteCategory->find('list');
        $this->set(compact('users', 'siteCategories'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Affiliate Request');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->AffiliateRequest->save($this->request->data)) {
                $id = $this->request->data['AffiliateRequest']['id'];
                $ids = array(
                    $id => $id
                );
                $this->__updateAffiliateUser($ids, $this->request->data['AffiliateRequest']['is_approved']);
                $this->Session->setFlash(__l('Affiliate Request has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Affiliate Request could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->AffiliateRequest->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $users = $this->AffiliateRequest->User->find('list');
        $siteCategories = $this->AffiliateRequest->SiteCategory->find('list');
        $this->set(compact('users', 'siteCategories'));
        $this->set('pageTitle', $this->pageTitle);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->AffiliateRequest->delete($id)) {
            $this->Session->setFlash(__l('Affiliate Request deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update() 
    {
        if (!empty($this->request->data[$this->modelClass])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $ids = array();
            foreach($this->request->data[$this->modelClass] as $id => $is_checked) {
                if ($is_checked['id']) {
                    $ids[] = $id;
                }
            }
            if ($actionid && !empty($ids)) {
                switch ($actionid) {
                    case ConstMoreAction::Disapproved:
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => ConstAffiliateRequests::Rejected
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->__updateAffiliateUser($ids, 0);
                        $this->Session->setFlash(__l('Checked requests has been disapproved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Approved:
                        $this->__updateAffiliateUser($ids, 1);
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->updateAll(array(
                                $this->modelClass . '.is_approved' => ConstAffiliateRequests::Accepted
                            ) , array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked requests has been approved') , 'default', null, 'success');
                        break;

                    case ConstMoreAction::Delete:
                        $this->__updateAffiliateUser($ids, 0);
                        foreach($ids as $id) {
                            $this->{$this->modelClass}->deleteAll(array(
                                $this->modelClass . '.id' => $id
                            ));
                        }
                        $this->Session->setFlash(__l('Checked requests has been deleted') , 'default', null, 'success');
                        break;
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
    public function __updateAffiliateUser($ids, $status) 
    {
        foreach($ids as $id) {
            $affiliateRequest = $this->AffiliateRequest->find('first', array(
                'conditions' => array(
                    'AffiliateRequest.id' => $id
                ) ,
                'recursive' => -1
            ));
            $this->AffiliateRequest->User->updateAll(array(
                'User.is_affiliate_user' => $status
            ) , array(
                'User.id' => $affiliateRequest['AffiliateRequest']['user_id']
            ));
        }
    }
    public function _sendAffiliateRequestMail($user_id) 
    {
        $user = $this->AffiliateRequest->User->find('first', array(
            'conditions' => array(
                'User.id' => $user_id
            ) ,
            'recursive' => -1
        ));
        $emailFindReplace = array(
            '##USERNAME##' => $user['User']['username'],
            '##SITE_NAME##' => Configure::read('site.name') ,
            '##SITE_LINK##' => Router::url('/', true) ,
        );
        $email = $this->EmailTemplate->selectTemplate('Affiliate Request');
        $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? $user['User']['email'] : $email['from'];
        $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? $user['User']['email'] : $email['reply_to'];
        $this->Email->to = Configure::read('site.from_email');
        $this->Email->subject = strtr($email['subject'], $emailFindReplace);
        if ($this->Email->send(strtr($email['email_content'], $emailFindReplace))) {
            return true;
        }
    }
}
?>