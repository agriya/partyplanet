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
class ArticleCommentsController extends AppController
{
    public $name = 'ArticleComments';
    public function beforeFilter() 
    {
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_artcle_comment_on_system_flag')) {
            $this->ArticleComment->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($article_id = null) 
    {
        $this->disableCache();
        $this->pageTitle = __l('News Comments');
        $this->set('article_id', $article_id);
        $conditions = array();
        $conditions['ArticleComment.is_active'] = 1;
        $conditions['ArticleComment.admin_suspend'] = 0;
        if (!empty($this->request->params['named']['user'])) {
            $conditions['User.username'] = $this->request->params['named']['user'];
        }
        if (!empty($article_id)) {
            $conditions['ArticleComment.article_id'] = $article_id;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Article',
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                )
            ) ,
            'fields' => array(
                'ArticleComment.id',
                'ArticleComment.created',
                'ArticleComment.comment',
                'ArticleComment.title',
                'ArticleComment.user_id',
                'ArticleComment.article_id',
                'User.username'
            ) ,
            'order' => 'ArticleComment.id desc',
            'limit' => 5,
            'recursive' => 1,
        );
        $this->set('articleComments', $this->paginate());
    }
    public function add() 
    {
        $this->pageTitle = __l('Add News Comment');
        if (!empty($this->request->data)) {
            $this->request->data['ArticleComment']['user_id'] = $this->Auth->user('id');
            $this->request->data['ArticleComment']['ip_id'] =  $this->ArticleComment->toSaveIp();
            $this->ArticleComment->create();
            if ($this->ArticleComment->save($this->request->data)) {
                if ($this->RequestHandler->isAjax()) {
                    $this->setAction('view', $this->ArticleComment->getLastInsertId() , 'view_ajax');
                } else {
                    $this->Session->setFlash(__l('News comment has been added') , 'default', null, 'success');
                    $article = $this->ArticleComment->Article->find('first', array(
                        'conditions' => array(
                            'Article.id' => $this->request->data['ArticleComment']['article_id']
                        ) ,
                        'fields' => array(
                            'Article.slug',
                        ) ,
                    ));
                    $this->redirect(array(
                        'controller' => 'articles',
                        'action' => 'view',
                        $article['Article']['slug'],
                    ));
                }
            } else {
                $this->Session->setFlash(__l('News comment could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $users = $this->ArticleComment->User->find('list');
        $articles = $this->ArticleComment->Article->find('list');
        $this->set(compact('users', 'articles'));
    }
    public function view($id = null, $view_name = 'view') 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $articleComment = $this->ArticleComment->find('first', array(
            'conditions' => array(
                'ArticleComment.id' => $id,
                'ArticleComment.admin_suspend' => 0
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                    'fields' => array(
                        'User.user_type_id',
                        'User.username',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                )
            ) ,
            'recursive' => 0
        ));
        $this->set('articleComment', $articleComment);
        if ($view_name == 'view_ajax' and empty($articleComment)) {
            $this->Session->setFlash(__l('News comment has been auto suspended.') , 'default', null, 'error');
        } elseif ($view_name == 'view_ajax') {
            $this->Session->setFlash(__l('News comment has been added') , 'default', null, 'success');
        }
        $this->render($view_name);
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $article_slug = $this->ArticleComment->find('first', array(
            'conditions' => array(
                'ArticleComment.id = ' => $id
            ) ,
            'contain' => array(
                'Article' => array(
                    'fields' => array(
                        'Article.slug',
                    ) ,
                )
            ) ,
            'recursive' => 1,
        ));
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ArticleComment->delete($id)) {
            $this->Session->setFlash(__l('News comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'articles',
                'action' => 'view',
                $article_slug['Article']['slug']
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('News Comments');
        $conditions = array();
        if (isset($this->request->params['named']['article_comment'])) {
            $article = $this->ArticleComment->Article->find('first', array(
                'conditions' => array(
                    'Article.slug' => $this->request->params['named']['article_comment']
                ) ,
                'recursive' => -1
            ));
            $conditions['ArticleComment.article_id'] = $article['Article']['id'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ArticleComment.is_active'] = 1;
                $conditions['ArticleComment.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ArticleComment.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['ArticleComment.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['ArticleComment.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->ArticleComment->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
              'contain' => array(
                'User' => array(
                    'fields' => array(
                        'User.username'
                    )
                ) ,
              'Article',
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
                    ),
                ) ,
            ) ,
			'order' => array(
				'ArticleComment.id' =>  'DESC',
			),
        );
        $this->set('active', $this->ArticleComment->find('count', array(
            'conditions' => array(
                'ArticleComment.is_active' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->ArticleComment->find('count', array(
            'conditions' => array(
                'ArticleComment.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->ArticleComment->find('count', array(
            'conditions' => array(
                'ArticleComment.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->ArticleComment->find('count', array(
            'conditions' => array(
                'ArticleComment.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->ArticleComment->moreActions;
        $this->set(compact('moreActions'));
        $this->set('articleComments', $this->paginate());
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit News Comment');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ArticleComment->save($this->request->data)) {
                $this->Session->setFlash(__l('News Comment has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('News Comment could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ArticleComment->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['ArticleComment']['id'];
        $users = $this->ArticleComment->User->find('list');
        $articles = $this->ArticleComment->Article->find('list');
        $this->set(compact('users', 'articles'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ArticleComment->delete($id)) {
            $this->Session->setFlash(__l('News Comment deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>