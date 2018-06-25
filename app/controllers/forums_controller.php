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
class ForumsController extends AppController
{
    public $name = 'Forums';
    public function index($slug = null) 
    {
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $forumCategory = $this->Forum->ForumCategory->find('first', array(
            'conditions' => array(
                'ForumCategory.slug' => $slug
            ) ,
            'fields' => array(
                'ForumCategory.id',
                'ForumCategory.title'
            ) ,
            'recursive' => -1,
        ));
        if (empty($forumCategory)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->paginate = array(
            'conditions' => array(
                'ForumCategory.id' => $forumCategory['ForumCategory']['id'],
                'Forum.is_active' => 1,
            ) ,
            'fields' => array(
                'Forum.id',
                'Forum.created',
                'Forum.title',
                'Forum.user_id',
                'Forum.forum_category_id',
                'Forum.forum_comment_count',
                'Forum.forum_view_count',
            ) ,
            'contain' => array(
                'ForumCategory' => array(
                    'fields' => array(
                        'ForumCategory.id',
                        'ForumCategory.title'
                    )
                ) ,
                'User' => array(
                    'UserAvatar',
                    'UserProfile' => array(
                        'Country' => array(
                            'fields' => array(
                                'Country.iso_alpha2',
                                'Country.name'
                            ) ,
                        ) ,
                        'fields' => array(
                            'UserProfile.country_id'
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
                'ForumComment' => array(
                    'User' => array(
                        'UserAvatar',
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
                        'fields' => array(
                            'User.user_type_id',
                            'User.username',
                            'User.id',
                            'User.fb_user_id',
                            'User.twitter_avatar_url',
                        )
                    ) ,
                    'limit' => 1,
                    'order' => array(
                        'ForumComment.id' => 'DESC'
                    )
                )
            ) ,
            'recursive' => 2,
            'order' => array(
                'Forum.id' => 'DESC'
            )
        );
        $this->pageTitle = sprintf(__l('Forums - %s') , $forumCategory['ForumCategory']['title']);
        $this->set('forums', $this->paginate());
    }
    public function view($id = null) 
    {
        $this->pageTitle = __l('Forum');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $forum = $this->Forum->find('first', array(
            'conditions' => array(
                'Forum.id' => $id
            ) ,
            'contain' => array(
                'ForumCategory',
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
        if (empty($forum)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        //Log the Forum view
        $this->request->data['ForumView']['user_id'] = $this->Auth->user('id');
        $this->request->data['ForumView']['forum_id'] = $forum['Forum']['id'];
        $this->request->data['ForumView']['ip_id'] = $this->Forum->ForumView->toSaveIp();
        $this->Forum->ForumView->create();
        $this->Forum->ForumView->save($this->request->data);
        $this->request->data['ForumComment']['forum_id'] = $forum['Forum']['id'];
        $this->pageTitle.= ' - ' . $forum['Forum']['title'];
        $this->set('forum', $forum);
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Forum');
        if (!empty($this->request->data)) {
            $this->Forum->create();
            if ($this->Forum->save($this->request->data)) {
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $this->Session->setFlash(__l('Forum has been added successfully.') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
                $this->Session->setFlash(__l('Forum has been added successfully but after admin approval it will list out in site.') , 'default', null, 'success');
                $forumCategory = $this->Forum->ForumCategory->find('first', array(
                    'conditions' => array(
                        'ForumCategory.id' => $this->request->data['Forum']['forum_category_id']
                    ) ,
                    'fields' => array(
                        'ForumCategory.slug'
                    ) ,
                    'recursive' => -1,
                ));
                $this->redirect(array(
                    'action' => 'index',
                    $forumCategory['ForumCategory']['slug']
                ));
            } else {
                $this->Session->setFlash(__l('Forum could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Forum->User->find('list', array(
                'conditions' => array(
                    'User.is_active' => 1
                )
            ));
            $this->set(compact('users'));
        }
        $forumCategories = $this->Forum->ForumCategory->find('list', array(
            'conditions' => array(
                'ForumCategory.is_active' => 1
            )
        ));
        $this->set(compact('forumCategories'));
    }
    public function edit($id = null) 
    {
        $this->pageTitle = __l('Edit Forum');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $this->request->data['Forum']['id'] = $id;
            if ($this->Forum->save($this->request->data)) {
                $this->Session->setFlash(__l('Forum has been updated') , 'default', null, 'success');
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                }
                $forumCategory = $this->Forum->ForumCategory->find('first', array(
                    'conditions' => array(
                        'ForumCategory.id' => $this->request->data['Forum']['forum_category_id']
                    ) ,
                    'fields' => array(
                        'ForumCategory.slug'
                    ) ,
                    'recursive' => -1,
                ));
                $this->redirect(array(
                    'action' => 'index',
                    $forumCategory['ForumCategory']['slug']
                ));
            } else {
                $this->Session->setFlash(__l('Forum could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Forum->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Forum']['title'];
        if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
            $users = $this->Forum->User->find('list', array(
                'conditions' => array(
                    'User.is_active' => 1
                )
            ));
            $this->set(compact('users'));
        }
        $forumCategories = $this->Forum->ForumCategory->find('list', array(
            'conditions' => array(
                'ForumCategory.is_active' => 1
            )
        ));
        $this->set(compact('forumCategories'));
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $forumCategory = $this->Forum->find('first', array(
            'conditions' => array(
                'Forum.id = ' => $id
            ) ,
            'contain' => array(
                'ForumCategory'
            ) ,
            'recursive' => 1,
        ));
        if ($this->Forum->delete($id)) {
            $this->Session->setFlash(__l('Forum has been deleted sucessfully') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'forums',
                'action' => 'category',
                $forumCategory['ForumCategory']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'keyword'
        ));
        $this->pageTitle = __l('Forums');
        $conditions = array();
        if (!empty($this->request->params['named']['category'])) {
            $forumCategory = $this->{$this->modelClass}->ForumCategory->find('first', array(
                'conditions' => array(
                    'ForumCategory.id' => $this->request->params['named']['category']
                ) ,
                'fields' => array(
                    'ForumCategory.id',
                    'ForumCategory.title',
                ) ,
                'recursive' => -1
            ));
            if (empty($forumCategory)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $conditions['ForumCategory.id'] = $forumCategory['ForumCategory']['id'];
            $this->pageTitle.= sprintf(__l(' - Forum - %s') , $forumCategory['ForumCategory']['title']);
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'day') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Forum.created) <= '] = 0;
            $this->pageTitle.= __l(' - Created today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Forum.created) <= '] = 7;
            $this->pageTitle.= __l(' - Created in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(Forum.created) <= '] = 30;
            $this->pageTitle.= __l(' - Created in this month');
        }
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['Forum']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['OR'] = array(
                'Forum.title LIKE' => '%' . $this->request->data['Forum']['keyword'] . '%',
                'Forum.description LIKE' => '%' . $this->request->data['Forum']['keyword'] . '%'
            );
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['keyword']);
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['Forum']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['Forum']['filter_id'])) {
            if ($this->request->data['Forum']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Forum.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->data['Forum']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Forum.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['Forum']['filter_id'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'recursive' => 0,
            'order' => array(
                'Forum.id' => 'DESC'
            )
        );
        $this->set('active', $this->Forum->find('count', array(
            'conditions' => array(
                'Forum.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Forum->find('count', array(
            'conditions' => array(
                'Forum.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $filters = $this->Forum->isFilterOptions;
        $moreActions = $this->Forum->moreActions;
        $this->set(compact('filters', 'moreActions'));
        $this->set('forums', $this->paginate());
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null) 
    {
        $this->setAction('edit', $id);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Forum->delete($id)) {
            $this->Session->setFlash(__l('Forum deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update() 
    {
        if (!empty($this->request->data['Forum'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $forumIds = array();
            foreach($this->request->data['Forum'] as $forum_category_id => $is_checked) {
                if ($is_checked['id']) {
                    $forumIds[] = $forum_category_id;
                }
            }
            if ($actionid && !empty($forumIds)) {
                if ($actionid == ConstMoreAction::Inactive) {
                    $this->Forum->updateAll(array(
                        'Forum.is_active' => 0
                    ) , array(
                        'Forum.id' => $forumIds
                    ));
                    $this->Session->setFlash(__l('Checked forum has been inactivated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Active) {
                    $this->Forum->updateAll(array(
                        'Forum.is_active' => 1
                    ) , array(
                        'Forum.id' => $forumIds
                    ));
                    $this->Session->setFlash(__l('Checked forum has been activated') , 'default', null, 'success');
                } else if ($actionid == ConstMoreAction::Delete) {
                    $this->Forum->deleteAll(array(
                        'Forum.id' => $forumIds
                    ));
                    $this->Session->setFlash(__l('Checked forum has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
}
?>