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
class ForumCategoriesController extends AppController
{
    public $name = 'ForumCategories';
    public function index() 
    {
        $this->pageTitle = __l('Discussion Forums');
        $this->paginate = array(
            'conditions' => array(
                'ForumCategory.is_active' => '1',
            ) ,
            'fields' => array(
                'ForumCategory.title',
                'ForumCategory.slug',
                'ForumCategory.description',
                'ForumCategory.forum_count',
                'ForumCategory.forum_post_count',
                'ForumCategory.forum_view_count'
            ) ,
            'contain' => array(
                'Forum' => array(
                    'conditions' => array(
                        'Forum.is_active' => '1',
                    ) ,
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
                        'Forum.id' => 'DESC'
                    ) ,
                ) ,
            ) ,
            'recursive' => 3
        );
        $this->set('forumCategories', $this->paginate());
    }
    public function admin_index() 
    {
        $this->_redirectGET2Named(array(
            'filter_id',
            'keyword'
        ));
        $conditions = array();
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['ForumCategory']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['OR'] = array(
                'ForumCategory.title LIKE' => '%' . $this->request->data['ForumCategory']['keyword'] . '%',
                'ForumCategory.description LIKE' => '%' . $this->request->data['ForumCategory']['keyword'] . '%'
            );
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['keyword']);
        }
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['ForumCategory']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (!empty($this->request->data['ForumCategory']['filter_id'])) {
            if ($this->request->data['ForumCategory']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ForumCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } else if ($this->request->data['ForumCategory']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ForumCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
            $this->request->params['named']['filter_id'] = $this->request->data['ForumCategory']['filter_id'];
        }
        $this->ForumCategory->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
        );
        $forumCategories = $this->paginate();
        $forum_count_categories = $this->ForumCategory->Forum->find('all', array(
            'fields' => array(
                'Forum.id',
                'count(Forum.id) as count',
                'Forum.forum_category_id'
            ) ,
            'group' => array(
                'Forum.forum_category_id'
            ) ,
            'recursive' => -1
        ));
        foreach($forumCategories As $key => $forumCategorie) {
            foreach($forum_count_categories As $forum_count_categorie) {
                if ($forum_count_categorie['Forum']['forum_category_id'] == $forumCategorie['ForumCategory']['id']) {
                    $forumCategories[$key]['ForumCategory']['count'] = $forum_count_categorie[0]['count'];
                }
            }
        }
        $this->pageTitle = __l('Forum Categories');
        $filters = $this->ForumCategory->isFilterOptions;
        $moreActions = $this->ForumCategory->moreActions;
        //$this->ForumCategory->recursive = - 1;
        $this->set('forumCategories', $forumCategories);
        $this->set(compact('filters', 'moreActions'));
		$this->set('active_count', $this->ForumCategory->find('count', array(
            'conditions' => array(
                'ForumCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->ForumCategory->find('count', array(
            'conditions' => array(
                'ForumCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->ForumCategory->find('count'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Forum Category');
        if (!empty($this->request->data)) {
            $this->ForumCategory->create();
            if ($this->ForumCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Forum Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Forum Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Forum Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ForumCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Forum Category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Forum Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ForumCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['ForumCategory']['title'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ForumCategory->delete($id)) {
            $this->Session->setFlash(__l('Forum Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>