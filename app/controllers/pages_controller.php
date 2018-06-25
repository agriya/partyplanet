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
class PagesController extends AppController
{
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'Page.Add',
            'Page.Preview',
            'Page.Update'
        );
        parent::beforeFilter();
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Page');
        if (!empty($this->request->data)) {
            $this->Page->set($this->request->data);
            if ($this->Page->validates()) {
                $this->Page->save($this->request->data);
                $this->Session->setFlash(__l('Page has been created') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Page could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Page');
        if (!empty($this->request->data)) {
            $this->Page->set($this->request->data);
            if ($this->Page->validates()) {
                $this->Page->save($this->request->data);
                $this->Session->setFlash(__l('Page has been Updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Page could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Page->read(null, $id);
        }
    }
    public function _createCache() 
    {
        $this->Page->recursive = -1;
        $pages = $this->paginate();
        Cache::write('rootPageCache', $pages);
        return true;
    }
    public function update_rootcache() 
    {
        if (!isset($this->request->params['requested'])) {
            throw new NotFoundException(__l('Invalid request'));
        }
        // Get all pages without a parent except the home page and also all the home page children
        $homePageId = Configure::read('Page.home_page_id');
        $rootPages = $this->
        {
            $this->modelClass}->find('all', array(
                'conditions' => "parent_id IS NULL AND url <> '/' OR parent_id = $homePageId",
                'recursive' => -1,
                'fields' => array(
                    'id',
                    'url',
                    'slug'
                ) ,
            ));
            Cache::write('rootPageCache', $rootPages);
            return $rootPages;
        }
        public function admin_index() 
        {
            $this->pageTitle = 'Pages';
            $this->Page->recursive = -1;
            $this->set('pages', $this->paginate());
        }
        public function admin_view($slug = null) 
        {
            $this->setAction('view', $slug);
        }
        public function view($slug = null) 
        {
            $this->Page->recursive = -1;
            if (!empty($slug)) {
                $page = $this->Page->findBySlug($slug);
            } else {
                $page = $this->Page->find('first', array(
                    'conditions' => array(
                        'Page.is_default' => 1
                    )
                ));
            }
            $this->request->params['named']['city'] = !empty($this->request->params['named']['city']) ? $this->request->params['named']['city'] : '';
            $about_us_url = array(
                'controller' => 'users',
                'action' => 'login',
                'city' => $this->request->params['named']['city']
            );
            $pageFindReplace = array(
                '##FROM_EMAIL##' => Configure::read('EmailTemplate.from_email') ,
                '##SITE_NAME##' => Configure::read('site.name') ,
                '##SITE_URL##' => Router::url('/', true) ,
                '##ABOUT_US_URL##' => Router::url(array(
                    'controller' => 'pages',
                    'action' => 'view',
                    'about',
                    'city' => $this->request->params['named']['city'],
                    'admin' => false
                ) , true) ,
                '##CONTACT_US_URL##' => Router::url(array(
                    'controller' => 'contacts',
                    'action' => 'add',
                    'city' => $this->request->params['named']['city'],
                    'admin' => false
                ) , true) ,
                '##FAQ_URL##' => Router::url(array(
                    'controller' => 'pages',
                    'action' => 'view',
                    'faq',
                    'city' => $this->request->params['named']['city'],
                    'admin' => false
                ) , true) ,
            );
            if ($page) {
                $page['Page']['title'] = strtr($page['Page']['title'], $pageFindReplace);
                $page['Page']['content'] = strtr($page['Page']['content'], $pageFindReplace);
                $this->pageTitle = $page[$this->modelClass]['title'];
                $this->set('page', $page);
                $this->set('currentPageId', $page[$this->modelClass]['id']);
                $this->set('isPage', true);
                $this->_chooseTemplate($page);
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        /**
         * Renders a normal page view or home view
         *
         * @param string $slug
         */
        private function _chooseTemplate($page) 
        {
            $render = 'view';
            if (!empty($page[$this->modelClass]['template'])) {
                $possibleThemeFile = APP . 'views' . DS . 'pages' . DS . 'themes' . DS . $page[$this->modelClass]['template'];
                if (file_exists($possibleThemeFile)) {
                    $render = $possibleThemeFile;
                }
            }
            return $this->render($render);
        }
        public function display() 
        {
            $path = func_get_args();
            $count = count($path);
            if (!$count) {
                $this->redirect(Router::url('/', true));
            }
            $page = $subpage = $title = null;
            if (!empty($path[0])) {
                $page = $path[0];
            }
            if (!empty($path[1])) {
                $subpage = $path[1];
            }
            if (!empty($path[$count-1])) {
                $title = Inflector::humanize($path[$count-1]);
            }
            $this->set(compact('page', 'subpage', 'title'));
            $this->render(join('/', $path));
        }
        public function admin_delete($id = null) 
        {
            if (is_null($id)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            if ($this->Page->delete($id)) {
                $this->Session->setFlash(__l('Page has been deleted') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'pages',
                    'action' => 'index',
                ));
            } else {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        public function admin_display($page) 
        {
            $this->setAction('display', $page);
        }
    }
?>