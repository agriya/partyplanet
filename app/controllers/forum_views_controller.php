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
class ForumViewsController extends AppController
{
    public $name = 'ForumViews';
    public function admin_index() 
    {
        $this->pageTitle = __l('Forum Views');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'forum',
            'q',
            'stat'
        ));
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
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumView.created) <= '] = 0;
            $this->pageTitle.= __l(' - Created today');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'week') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumView.created) <= '] = 7;
            $this->pageTitle.= __l(' - Created in this week');
        }
        if (isset($this->request->params['named']['stat']) && $this->request->params['named']['stat'] == 'month') {
            $conditions['TO_DAYS(NOW()) - TO_DAYS(ForumView.created) <= '] = 30;
            $this->pageTitle.= __l(' - Created in this month');
        }
        if (isset($this->request->params['named']['q'])) {
            $conditions['Forum.title Like'] = '%' . $this->request->params['named']['q'] . '%';
            //$conditions['ForumView.comment like %'] = $this->request->params['named']['q'];
            $this->request->data['ForumView']['q'] = $this->request->params['named']['q'];
            $this->pageTitle.= sprintf(__l(' - Search - %s') , $this->request->params['named']['q']);
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'recursive' => 0,
            'order' => array(
                'ForumView.id' => 'DESC'
            )
        );
        if (isset($this->request->data['ForumView']['q'])) {
            //$this->paginate['search'] = $this->request->data['ForumView']['q'];
            
        }
        $moreActions = $this->ForumView->moreActions;
        $this->set(compact('moreActions'));
        $this->set('forumViews', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ForumView->delete($id)) {
            $this->Session->setFlash(__l('Forum View deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_update() 
    {
        if (!empty($this->request->data['ForumView'])) {
            $r = $this->request->data[$this->modelClass]['r'];
            $actionid = $this->request->data[$this->modelClass]['more_action_id'];
            unset($this->request->data[$this->modelClass]['r']);
            unset($this->request->data[$this->modelClass]['more_action_id']);
            $forumViewIds = array();
            foreach($this->request->data['ForumView'] as $forum_view_id => $is_checked) {
                if ($is_checked['id']) {
                    $forumViewIds[] = $forum_view_id;
                }
            }
            if ($actionid && !empty($forumViewIds)) {
                if ($actionid == ConstMoreAction::Delete) {
                    $this->ForumView->deleteAll(array(
                        'ForumView.id' => $forumViewIds
                    ));
                    $this->Session->setFlash(__l('Checked views has been deleted') , 'default', null, 'success');
                }
            }
        }
        $this->redirect(Router::url('/', true) . $r);
    }
}
?>