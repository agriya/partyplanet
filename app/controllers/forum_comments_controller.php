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
class ForumCommentsController extends AppController
{
    public $name = 'ForumComments';
    public function index($forum_id = null) 
    {
        $this->pageTitle = __l('Forum Comments');
        $this->paginate = array(
            'conditions' => array(
                'ForumComment.forum_id' => $forum_id
            ) ,
            'contain' => array(
                'Forum' => array(
                    'fields' => array(
                        'Forum.user_id'
                    )
                ) ,
                'User' => array(
                    'UserProfile' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.country_code',
                                'Country.name'
                            )
                        ) ,
                        'fields' => array(
                            'UserProfile.country_id'
                        )
                    ) ,
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
                'ForumComment.id' => 'desc'
            ) ,
            'limit' => 5,
            'recursive' => 2
        );
        $this->set('forumComments', $this->paginate());
    }
    public function view($id = null, $view_name = 'view') 
    {
        $this->pageTitle = __l('Forum Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $forumComment = $this->ForumComment->find('first', array(
            'conditions' => array(
                'ForumComment.id = ' => $id
            ) ,
            'contains' => array(
                'Forum' => array(
                    'Forum.user_id'
                ) ,
                'User' => array(
                    'UserProfile' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.iso_alpha2',
                                'Country.name'
                            )
                        ) ,
                        'fields' => array(
                            'UserProfile.country_id'
                        )
                    ) ,
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
            'recursive' => 2,
        ));
        if (empty($forumComment)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $forumComment['ForumComment']['id'];
        $this->set('forumComment', $forumComment);
        $this->render($view_name);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Forum Comment');
        if (!empty($this->request->data)) {
            $this->request->data['ForumComment']['ip_id'] = $this->ForumComment->toSaveIp();
            $this->ForumComment->create();
            if ($this->ForumComment->save($this->request->data)) {
                $this->Session->setFlash(__l('Forum Comment has been added') , 'default', null, 'success');
                if (!$this->RequestHandler->isAjax()) {
                    $this->redirect(array(
                        'controller' => 'forums',
                        'action' => 'view',
                        $forum['ForumComment']['forum_id']
                    ));
                } else {
                    // Ajax: return added answer
                    $this->setAction('view', $this->ForumComment->getLastInsertId() , 'view_ajax');
                }
            } else {
                $this->Session->setFlash(__l('Forum Comment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $forum_id = $this->ForumComment->find('first', array(
            'conditions' => array(
                'ForumComment.id = ' => $id
            ) ,
            'contain' => array(
                'Forum' => array(
                    'fields' => array(
                        'Forum.id',
                    ) ,
                )
            ) ,
            'recursive' => 1,
        ));
        if ($this->ForumComment->delete($id)) {
            $this->Session->setFlash(__l('Forum Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'forums',
                'action' => 'view',
                $forum_id['Forum']['id']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'q'
        ));
        $this->pageTitle = __l('Forum Comments');
        $conditions = array();
        if (!empty($this->request->params['named']['forum'])) {
            $forum = $this->{$this->modelClass}->Forum->find('first', array(
                'conditions' => array(
                    'Forum.id' => $this->request->params['named']['forum']
                ) ,
                'fields' => array(
                    'Forum.id',
                    'Forum.title',
                ) ,
                'recursive' => -1
            ));
            if (empty($forum)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['Forum.id'] = $forum['Forum']['id'];
            $this->pageTitle.= sprintf(__l(' - Forum - %s') , $forum['Forum']['title']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumComment.created) <= '] = 0;
            $this->pageTitle.= __l(' - Created today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumComment.created) <= '] = 7;
            $this->pageTitle.= __l(' - Created in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumComment.created) <= '] = 30;
            $this->pageTitle.= __l(' - Created in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['ForumComment']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['ForumComment']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['ForumComment']['filter_id'])) {
            if ($this->request->data['ForumComment']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ForumComment.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->data['ForumComment']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ForumComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['ForumComment']['filter_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
              'Forum',
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
              ) ,
            'limit' => 20,
            'recursive' => 2,
            'order' => array(
                'ForumComment.id' => 'DESC'
            )
        );
        if (isset($this->request->params['named']['q'])) {
            $this->paginate = array_merge($this->paginate, array(
                'search' => $this->request->params['named']['q']
            ));
        }
        $moreActions = $this->ForumComment->moreActions;
        $this->set(compact('moreActions'));
        $this->set('forumComments', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ForumComment->delete($id)) {
            $this->Session->setFlash(__l('Forum Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update() 
    {
        if (!empty($this->request->data['ForumComment'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $forumCommentIds = array();
            foreach($this->request->data['ForumComment'] as $forum_comment_id => $is_checked) {
                if ($is_checked['id']) {
                    $forumCommentIds[] = $forum_comment_id;
                }
            }
            if ($actionid && !empty($forumCommentIds)) {
                if ($actionid == ConstMoreAction::Delete) {
                    $this->ForumComment->deleteAll(array(
                        'ForumComment.id' => $forumCommentIds
                    ));
                    $this->Session->setFlash(__l('Checked comment has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
}
?>