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
class ArticlesController extends AppController
{
    public $name = 'Articles';
	public $components = array(
        'OauthConsumer'
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Article.title',
            'Article.description',
            'Article.makeActive',
            'Article.makeInactive',
            'Article.sort_by',
            'Article.makeDelete',
            'Attachment.filename'
        );
        parent::beforeFilter();
        if (!Configure::read('suspicious_detector.is_enabled') && !Configure::read('suspicious_detector.auto_suspend_artcle_on_system_flag')) {
            $this->Article->Behaviors->detach('SuspiciousWordsDetector');
        }
    }
    public function index($type = null) 
    {
        $this->pageTitle = __l('News');
        if (isset($this->request->params['named']['type']) and ($this->request->params['named']['type'] == 'most_comment')):
            $conditions = array(
                'Article.article_comment_count >' => 0,
                'Article.is_active' => 1
            );
        else:
            $conditions = array(
                'Article.is_active' => 1
            );
        endif;
        if (!empty($this->request->params['named']['category'])) {
            $conditions['ArticleCategory.slug'] = $this->request->params['named']['category'];
        }
        if (isset($this->request->params['named']['sort_by'])) {
            $this->request->data['sort_by'] = $this->request->params['named']['sort_by'];
            switch ($this->request->params['named']['sort_by']) {
                case 'title':
                    $order = 'Article.title asc';
                    break;

                case 'date':
                    $order = 'Article.created desc';
                    break;

                case 'comment':
                    $order = 'Article.article_comment_count desc';
                    break;

                case 'view':
                    //  $order='Article.article_view_count desc';
                    break;
            }
        } else {
            if (isset($this->request->params['named']['type']) and ($this->request->params['named']['type'] == 'most_comment')):
                $order = 'Article.article_comment_count desc';
                if (isset($this->request->params['named']['view']) and ($this->request->params['named']['view'] == 'home')):
                    $type = 'home';
                    $limit = 5;
                endif;
            else:
                $order = 'Article.id desc';
            endif;
        }
        if (!empty($this->request->params['named']['tag'])) {
            $this->pageTitle.= ' ' . $this->request->params['named']['tag'];
            //retreving the list of tag ,which are havig the tag
            $article_tag = $this->Article->ArticleTag->find('first', array(
                'conditions' => array(
                    'ArticleTag.slug LIKE' => '%' . $this->request->params['named']['tag'] . '%'
                ) ,
                'fields' => array(
                    'ArticleTag.id',
                ) ,
                'recursive' => -1,
            ));
            $article_ids = $this->Article->ArticlesArticleTag->find('list', array(
                'conditions' => array(
                    'ArticlesArticleTag.article_tag_id' => $article_tag['ArticleTag']['id']
                ) ,
                'fields' => array(
                    'ArticlesArticleTag.article_id',
                ) ,
                'recursive' => 1,
            ));
            //using that list in the condition for event fetch
            $conditions['Article.id'] = $article_ids;
            $this->set('setTitle', 'Tag : ' . $this->request->params['named']['tag']);
            $this->pageTitle.= ' - ' . $this->request->params['named']['tag'];
        }
        $limit = isset($this->request->params['named']['limit']) ? $this->request->params['named']['limit'] : '10';
        $conditions['Article.admin_suspend'] = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'ArticleCategory' => array(
                    'fields' => array(
                        'ArticleCategory.id',
                        'ArticleCategory.name',
                    )
                ) ,
                'Attachment',
            ) ,
            'recursive' => 0,
            'order' => $order,
            'limit' => $limit
        );
        if (isset($this->request->params['named']['type']) and ($this->request->params['named']['type'] == 'home_more_news')) {
            $cat_limit = 3;
        } else {
            $cat_limit = null;
        }
        $homeArticleCategories = $this->Article->ArticleCategory->find('all', array(
            'conditions' => array(
                'ArticleCategory.is_active' => '1',
            ) ,
            'limit' => $cat_limit,
            'recursive' => -1
        ));
        $this->set(compact('homeArticleCategories'));
        $this->set('articles', $this->paginate());
        $this->set('limit', $limit);
        if ($type == 'home-banner') {
            $this->autoRender = false;
            $this->render('article_home_banner');
        } else if ($type == 'home') {
            $this->autoRender = false;
            $this->render('index_compact');
        } else if ($type == 'lst') {
            $this->autoRender = false;
            $this->render('lst');
        }
    }
    public function view($slug = null) 
    {
        $this->pageTitle = __l('News');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $conditions = array();
        $conditions['Article.slug'] = $slug;
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            $conditions['Article.admin_suspend'] = 0;
        }
        $article = $this->Article->find('first', array(
            'conditions' => $conditions,
            'recursive' => 1,
        ));
        if (empty($article)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($article['Attachment'])) {
            $image_options = array(
                'dimension' => 'medium_thumb',
                'class' => '',
                'alt' => $article['Article']['title'],
                'title' => $article['Article']['title'],
                'type' => 'png',
                'full_url' => true,
            );
            $article_image = getImageUrl('Article', $article['Attachment'], $image_options, true);
            Configure::write('meta.image', $article_image);
        }
        if (!empty($article['Article']['title'])) {
            Configure::write('meta.name', $article['Article']['title']);
        }
        Configure::write('meta.keywords', Configure::read('meta.keywords') . ', ' . $article['Article']['title']);
        Configure::write('meta.description', $article['Article']['title'] . '- Article posted in ' . Configure::read('site.name'));
        $this->request->data['ArticleComment']['article_id'] = $article['Article']['id'];
        $this->request->data['ArticleComment']['article_slug'] = $article['Article']['slug'];
        $this->pageTitle.= ' - ' . $article['Article']['title'];
        $this->set('article', $article);
    }
    public function add() 
    {
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Add News');
        if (!empty($this->request->data)) {
            $this->Article->create();
            if ($this->Article->save($this->request->data)) {
                $article_id = $this->Article->getLastInsertId();
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                }
                $this->Article->Attachment->set($this->request->data);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->Article->Attachment->create();
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'ArticleImage';
                    $this->request->data['Attachment']['foreign_id'] = $article_id;
                    $this->Article->Attachment->save($this->request->data['Attachment']);
                }
				
				$article = $this->Article->find('first', array(
					'conditions' => array(
						'Article.id' => $article_id 
					)
				));
				$url = Router::url(array(
					'controller' => 'articles',
					'action' => 'view',
					'admin' => false,
					$article['Article']['slug'],
				) , true);
				if (!$article['Article']['admin_suspend'] && $article['Article']['is_active']) {
					$image_options = array(
						'dimension' => 'normal_thumb',
						'class' => '',
						'alt' => $article['Article']['title'],
						'title' => $article['Article']['title'],
						'type' => 'jpg'
					);
					$post_data = array();
					$post_data['message'] = '"' . $article['Article']['title'] . __l('" on ') . Configure::read('site.name');
					$post_data['image_url'] = Router::url('/', true) . getImageUrl('Article', $article['Article'], $image_options);
					$post_data['link'] = $url;
					$post_data['description'] = $article['Article']['description'];
					
					if (Configure::read('article.post_on_facebook')) { // post on site facebook
						$getFBReturn = $this->postOnFacebook($post_data, 1);
						unset($post_data['fb_user_id']);
						unset($post_data['fb_access_token']);
					}
					
					if (Configure::read('article.post_on_twitter')) { // post on site twitter
						$getTewwtReturn = $this->postOnTwitter($post_data, 1);
					}
				}
				
                $this->Session->setFlash(__l('News has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('News could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $articleCategories = $this->Article->ArticleCategory->find('list', array(
            'conditions' => array(
                'ArticleCategory.is_active' => 1
            )
        ));
        $this->set(compact('articleCategories'));
    }
    public function edit($id = null) 
    {
        if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle = __l('Edit News');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->Article->save($this->request->data)) {
                $article_id = $this->request->data['Article']['id'];
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->request->data['Attachment']['filename']['type'] = get_mime($this->request->data['Attachment']['filename']['tmp_name']);
                }
                $this->Article->Attachment->set($this->request->data);
                if (!empty($this->request->data['Attachment']['filename']['name'])) {
                    $this->Article->Attachment->create();
                    $this->request->data['Attachment']['class'] = $this->modelClass;
                    $this->request->data['Attachment']['description'] = 'ArticleImage';
                    $this->request->data['Attachment']['foreign_id'] = $article_id;
                    $this->Article->Attachment->save($this->request->data['Attachment']);
                }
                $this->Session->setFlash(__l(' News has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' News could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $conditions['Article.id'] = $id;
            $this->request->data = $this->Article->find('first', array(
                'conditions' => $conditions,
                'fields' => array(
                    'Article.title',
                    'Article.description',
                    'Article.slug',
                    'Attachment.id',
                    'Article.is_active',
                    'Attachment.filename',
                    'Attachment.dir',
                    'Attachment.width',
                    'Attachment.height'
                ) ,
                'contain' => array(
                    'Attachment',
                    'ArticleTag' => array(
                        'fields' => array(
                            'name'
                        )
                    )
                )
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->request->data['Article']['tag'] = $this->Article->formatTags($this->request->data['ArticleTag']);
        }
        $this->pageTitle.= ' - ' . $this->request->data['Article']['title'];
        $articleCategories = $this->Article->ArticleCategory->find('list', array(
            'conditions' => array(
                'ArticleCategory.is_active' => 1
            )
        ));
        $this->set(compact('articleCategories'));
    }
    public function delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Article->delete($id)) {
            $this->Session->setFlash(__l('News deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('News');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['Article']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['Article.title Like'] = '%' . $this->request->data['Article']['keyword'] . '%';
        }
        if (isset($this->request->params['named']['category'])) {
            $conditions['Article.article_category_id'] = $this->request->params['named']['category'];
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['Article.is_active'] = 1;
                $conditions['Article.admin_suspend'] = 0;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['Article.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Flagged) {
                $conditions['Article.is_system_flagged'] = 1;
                $this->pageTitle.= __l(' - Flagged ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Suspend) {
                $conditions['Article.admin_suspend'] = 1;
                $this->pageTitle.= __l(' - Suspend ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions,
            ) ,
            'contain' => array(
                'ArticleCategory' => array(
                    'fields' => array(
                        'ArticleCategory.id',
                        'ArticleCategory.name',
                        'ArticleCategory.slug',
                    )
                ) ,
            ) ,
            'recursive' => 0,
            'order' => 'Article.id desc'
        );
        $this->set('articles', $this->paginate());
        $this->set('active', $this->Article->find('count', array(
            'conditions' => array(
                'Article.is_active' => 1,
                'Article.admin_suspend' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->Article->find('count', array(
            'conditions' => array(
                'Article.is_active' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('system_flagged', $this->Article->find('count', array(
            'conditions' => array(
                'Article.is_system_flagged' => 1,
            )
        )));
        $this->set('suspended', $this->Article->find('count', array(
            'conditions' => array(
                'Article.admin_suspend' => 1,
            )
        )));
        $moreActions = $this->Article->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->setAction('add');
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->setaction('edit', $id);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Article->delete($id)) {
            $this->Session->setFlash(__l('News deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function slider() 
    {
        $conditions = array(
            'Article.is_active' => 1
        );
        $articles = $this->Article->find('all', array(
            'conditions' => $conditions,
            'recursive' => 2,
        ));
        $this->set('articles', $articles);
    }
}
?>