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
class CitiesController extends AppController
{
    public $name = 'Cities';
    public $uses = array(
        'City',
        'Venue',
    );
    public function admin_index() 
    {
        $this->disableCache();
        $this->pageTitle = __l('Cities');
        $this->_redirectGET2Named(array(
            'filter_id',
            'q',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['filter_id'])) {
            $this->request->data['City']['filter_id'] = $this->request->params['named']['filter_id'];
        }
        if (isset($this->request->params['named']['q'])) {
            $this->request->data['City']['q'] = $this->request->params['named']['q'];
            $conditions['City.name Like'] = '%' . $this->request->data['City']['q'] . '%';
        }
        if (!empty($this->request->data['City']['filter_id'])) {
            $conditions['City.is_approved'] = $this->request->data['City']['filter_id'];
            $this->request->params['named']['filter_id'] = $this->request->data['City']['filter_id'];
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['City.is_approved'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['City.is_approved'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.id'
                    ) ,
                ) ,
            ) ,
            'fields' => array(
                'City.id',
                'City.name',
                'City.is_approved',
                'City.country_id',
                'City.city_code',
                'City.created'
            ) ,
            'recursive' => 2,
            'order' => 'City.name asc',
            'limit' => 15
        );
        $this->set('cities', $this->paginate());
		$this->set('active_count', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 0,
            )
        )));
        $this->set('total_count', $this->City->find('count'));
        $this->set('pending', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 0
            )
        )));
        $this->set('approved', $this->City->find('count', array(
            'conditions' => array(
                'City.is_approved = ' => 1
            )
        )));
        $filters = $this->City->isFilterOptions;
        $moreActions = $this->City->moreActions;
        $this->set(compact('filters', 'moreActions'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit City');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->City->save($this->request->data)) {
                $this->Session->setFlash(__l('City has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'admin_index'
                ));
            } else {
                $this->Session->setFlash(__l('City could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->City->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['City']['name'];
        $countries = $this->City->Country->find('list');
        $this->set(compact('countries'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add City');
        if (!empty($this->request->data)) {
            //$this->request->data['City']['is_approved'] = 1;
            $this->City->create();
            if ($this->City->save($this->request->data)) {
                $this->Session->setFlash(__l(' City has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'admin_index'
                ));
            } else {
                $this->Session->setFlash(__l(' City could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $countries = $this->City->Country->find('list');
        $this->set(compact('countries'));
    }
    // To change approve/disapprove status by admin
    public function admin_update_status($id, $status) 
    {
        $this->City->id = $id;
        if ($status == 'disapprove') {
            $this->City->saveField('is_approved', 0);
        }
        if ($status == 'approve') {
            $this->City->saveField('is_approved', 1);
        }
        $this->redirect(array(
            'controller' => 'cities',
            'action' => 'admin_index'
        ));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->City->delete($id)) {
            $this->Session->setFlash(__l('City deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'admin_index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function index() 
    {
        $this->disableCache();
        $this->pageTitle = __l('Cities');
        $conditions = array();
        $conditions['City.is_approved'] = 1;
        if (!empty($this->request->data)) {
            $city = $this->City->find('first', array(
                'conditions' => array(
                    'City.id' => $this->request->data['city_id']
                ) ,
                'fields' => array(
                    'City.slug',
                ) ,
                'recursive' => -1,
            ));
            if ($city['City']['slug']) {
                $url = Router::url('/', true) . $city['City']['slug'];
                $this->redirect($url);
            }
        }
        if (!empty($this->_prefixSlug) && (!empty($this->request->params['named']['type'])) && $this->request->params['named']['type'] != 'home') {
            $country = $this->City->Country->find('first', array(
                'conditions' => array(
                    'Country.slug' => $this->_prefixSlug
                ) ,
                'fields' => array(
                    'Country.id',
                ) ,
                'recursive' => -1,
            ));
            $conditions['City.country_id'] = $country['Country']['id'];
            $country_id = $country['Country']['id'];
        } else {
            if (!empty($this->_prefixSlug)) {
                $city = $this->City->find('first', array(
                    'conditions' => array(
                        'City.slug' => $this->_prefixSlug,
                    ) ,
                    'fields' => array(
                        'City.country_id',
                    ) ,
                    'recursive' => -1,
                ));
                $conditions['City.country_id'] = $city['City']['country_id'];
            }
        }        
        $cities = $this->City->find('all', array(
            'conditions' => $conditions,
            'contain' => array(
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.slug'
                    ) ,
                ) ,
            ) ,
            'fields' => array(
                'City.id',
                'City.name',
                'City.is_approved',
                'City.city_code',
                'City.slug',
                'City.country_id'
            ) ,
            'order' => 'City.country_id ASC',
            'recursive' => 1,
        ));
        $countries = $this->City->Country->find('all', array(
            'contain' => array(
                'City' => array(
                    'conditions' => $conditions,
                    'fields' => array(
                        'City.id',
                        'City.name',
                        'City.is_approved',
                        'City.city_code',
                        'City.slug',
                        'City.country_id'
                    ) ,
                ) ,
            ) ,
            'fields' => array(
                'Country.id',
                'Country.name',
                'Country.slug'
            ) ,
            'order' => 'Country.id ASC',
            'recursive' => 2,
        ));
        $country_cities = $this->City->find('all', array(
            'conditions' => array(
                'City.is_approved' => 1
            ) ,
            'fields' => array(
                'City.id',
                'City.name',
                'City.is_approved',
                'City.city_code',
                'City.slug',
                'City.country_id'
            ) ,
            'order' => 'City.name ASC',
            'recursive' => -1,
        ));
        $this->set('cities', $cities);
        $this->set('countries', $countries);
        $this->set('country_cities', $country_cities);
        if (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'home') {
            $this->autoRender = false;
            $this->render('index_compact');
        }
    }
    public function view($slug = null) 
    {
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $city = $this->City->find('first', array(
            'conditions' => array(
                'City.slug' => $slug
            ) ,
            'fields' => array(
                'City.id',
                'City.slug',
            ) ,
            'recursive' => -1,
        ));
        if ($city['City']['id']) {
            $cookie['city_id'] = $city['City']['id'];
            $this->Cookie->delete('City.city_id');
            $this->Cookie->write('City', $cookie, false, $this->cookieTerm);
            $this->Cookie->write('city_slug', $city['City']['slug'], false, $this->cookieTerm);
            if (!empty($this->request->params['named']['venue'])) {
                $this->redirect(array(
                    'controller' => 'venues',
                    'action' => 'view',
                    $this->request->params['named']['venue']
                ));
            } else {
                $this->redirect(Router::url('/', true));
            }
        }
    }
    public function lst() 
    {
        $conditions = array(
            'City.is_approved' => 1,
        );
        if (!empty($this->request->params['named']['name'])) {
            $conditions['City.country_id'] = $this->request->params['named']['name'];
        }
        $cities = $this->City->find('list', array(
            'conditions' => $conditions
        ));
        $this->set('cities', $cities);
    }
}
?>