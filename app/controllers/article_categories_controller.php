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
class ArticleCategoriesController extends AppController
{
    public $name = 'ArticleCategories';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'ArticleCategory.makeActive',
            'ArticleCategory.makeInactive',
            'ArticleCategory.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Article Categories');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['ArticleCategory']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['ArticleCategory.name Like'] = '%' . $this->request->data['ArticleCategory']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ArticleCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ArticleCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 1,
            'order' => 'ArticleCategory.id desc'
        );
        $this->set('articleCategories', $this->paginate());
		$this->set('active_count', $this->ArticleCategory->find('count', array(
            'conditions' => array(
                'ArticleCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->ArticleCategory->find('count', array(
            'conditions' => array(
                'ArticleCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->ArticleCategory->find('count'));
        $moreActions = $this->ArticleCategory->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Article Category');
        if (!empty($this->request->data)) {
            $this->ArticleCategory->create();
            if ($this->ArticleCategory->save($this->request->data)) {
                $this->Session->setFlash(__l(' Article Category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Article Category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Article Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ArticleCategory->save($this->request->data)) {
                $this->Session->setFlash(__l(' Article Category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Article Category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ArticleCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['ArticleCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ArticleCategory->delete($id)) {
            $this->Session->setFlash(__l('Article Category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>