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
class PartyPlannersController extends AppController
{
    public $name = 'PartyPlanners';
    public $uses = array(
        'PartyPlanner',
        'CellProvider',
        'PartyType',
        'Country',
        'City',
        'EmailTemplate',
    );
    public $components = array(
        'Email',
        'RequestHandler',
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'PartyPlanner.makeActive',
            'PartyPlanner.makeInactive',
            'PartyPlanner.makeDelete',
            'PartyPlanner.keyword',
        );
        parent::beforeFilter();
    }
    public function add() 
    {
        $this->pageTitle = __l('Add Party Planner');
        $send = 0;
        if (!empty($this->request->data)) {
            $this->request->data['PartyPlanner']['ip_id'] = $this->PartyPlanner->toSaveIp();
            $this->PartyPlanner->create();
            if ($this->PartyPlanner->save($this->request->data)) {
                if ($this->Auth->user('user_type_id') == ConstUserTypes::Admin) {
                    $this->Session->setFlash(__l('Party has been added successfully') , 'default', null, 'success');
                    $this->redirect(array(
                        'action' => 'index'
                    ));
                } else {
                    $cities = $this->PartyPlanner->City->find('list');
                    $countries = $this->PartyPlanner->Country->find('list');
                    $cellProviders = $this->PartyPlanner->CellProvider->find('list');
                    $partyTypes = $this->PartyPlanner->PartyType->find('list');
                    $emailFindReplace = array(
                        '##PARTY_NAME##' => $partyTypes[$this->request->data['PartyPlanner']['party_type_id']],
                        '##CITY_NAME##' => $cities[$this->request->data['PartyPlanner']['city_id']],
                        '##COUNTRY_NAME##' => $countries[$this->request->data['PartyPlanner']['country_id']],
                        '##ZIP_CODE##' => $this->request->data['PartyPlanner']['zip_code'],
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##NAME##' => $this->request->data['PartyPlanner']['name'],
                        '##FROM_EMAIL##' => $this->request->data['PartyPlanner']['email'],
                        '##SITE_ADDR##' => gethostbyaddr($this->request->data['PartyPlanner']['ip']) ,
                        '##IP##' => $this->request->data['PartyPlanner']['ip'],
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##DATE_OF_PARTY##' => $this->request->data['PartyPlanner']['date']['year'] . '-' . $this->request->data['PartyPlanner']['date']['month'] . '-' . $this->request->data['PartyPlanner']['date']['day'],
                    );
                    // send to Admin email
                    $email = $this->EmailTemplate->selectTemplate('Party Plan');
                    $this->Email->from = strtr($email['from'], $emailFindReplace);
                    $this->Email->to = Configure::read('site.contact_email');
                    $this->Email->subject = $email['subject'];
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(trim(strtr($email['email_content'], $emailFindReplace)));
                    $this->Session->setFlash(__l('Your party plan has been submitted') , 'default', null, 'success');
                    $send = 1;
                }
            } else {
                $this->Session->setFlash(__l('Party Planner could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        if ($this->Auth->user('id')) {
            $user_profile = $this->PartyPlanner->User->find('first', array(
                'conditions' => array(
                    'User.id' => $this->Auth->user('id')
                ) ,
                'recursive' => 0,
            ));
            $this->request->data['PartyPlanner']['user_id'] = $user_profile['User']['id'];
            $this->request->data['PartyPlanner']['address1'] = $user_profile['UserProfile']['address'];
            $this->request->data['PartyPlanner']['address2'] = $user_profile['UserProfile']['address2'];
            $this->request->data['PartyPlanner']['city_id'] = $user_profile['UserProfile']['city_id'];
            $this->request->data['PartyPlanner']['country_id'] = $user_profile['UserProfile']['country_id'];
            $this->request->data['PartyPlanner']['zip_code'] = $user_profile['UserProfile']['zip_code'];
            $this->request->data['PartyPlanner']['email'] = $user_profile['User']['email'];
            $this->request->data['PartyPlanner']['name'] = $user_profile['UserProfile']['first_name'] . ' ' . $user_profile['UserProfile']['last_name'];
        }
        $this->set('success', $send);
        $musicTypes = $this->PartyPlanner->MusicType->find('list');
        $barServiceTypes = $this->PartyPlanner->BarServiceType->find('list');
        $foodCaterings = $this->PartyPlanner->FoodCatering->find('list');
        $entertainments = $this->PartyPlanner->Entertainment->find('list');
        $eventScenes = $this->PartyPlanner->EventScene->find('list');
        $users = $this->PartyPlanner->User->find('list');
        $cities = $this->PartyPlanner->City->find('list', array(
            'order' => 'City.name asc',
        ));
        $countries = $this->PartyPlanner->Country->find('list');
        $cellProviders = $this->PartyPlanner->CellProvider->find('list');
        $partyTypes = $this->PartyPlanner->PartyType->find('list', array(
            'conditions' => array(
                'PartyType.is_active' => '1'
            ) ,
            'fields' => array(
                'PartyType.name',
            ) ,
            'order' => 'PartyType.name asc',
        ));
        $this->set(compact('musicTypes', 'foodCaterings', 'entertainments', 'eventScenes', 'barServiceTypes', 'users', 'partyTypes', 'cities', 'countries', 'cellProviders'));
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Party Planners');
        $conditions = array();
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        if (!empty($this->request->params['named'])) {
            $this->request->data['PartyPlanner'] = array(
                'keyword' => (!empty($this->request->params['named']['keyword'])) ? $this->request->params['named']['keyword'] : '',
            );
            if (!empty($this->request->data['PartyPlanner']['keyword'])) {
                $conditions['OR'] = array(
                    'PartyPlanner.name LIKE' => '%' . $this->request->data['PartyPlanner']['keyword'] . '%',
                );
            }
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['PartyPlanner.is_contacted'] = 1;
                $this->pageTitle.= __l(' - Contacted ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['PartyPlanner.is_contacted'] = 0;
                $this->pageTitle.= __l(' - Not Contacted ');
            }
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'recursive' => 0,
            'order' => 'PartyPlanner.id desc',
        );
        $this->set('partyPlanners', $this->paginate());
        $this->set('active', $this->PartyPlanner->find('count', array(
            'conditions' => array(
                'PartyPlanner.is_contacted' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->PartyPlanner->find('count', array(
            'conditions' => array(
                'PartyPlanner.is_contacted' => 0,
            ) ,
            'recursive' => -1
        )));
        $moreActions = $this->PartyPlanner->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_view($slug = null) 
    {
        $this->pageTitle = __l('Party Planner');
        if (is_null($slug)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $partyPlanner = $this->PartyPlanner->find('first', array(
            'conditions' => array(
                'PartyPlanner.slug = ' => $slug
            ) ,
            'contain' => array(
                'User',
                'Country' => array(
                    'fields' => array(
                        'Country.name',
                        'Country.id',
                        'Country.slug'
                    )
                ) ,
                'City' => array(
                    'fields' => array(
                        'City.name',
                        'City.id',
                        'City.slug'
                    )
                ) ,
                'CellProvider' => array(
                    'fields' => array(
                        'CellProvider.name',
                        'CellProvider.id'
                    )
                ) ,
                'PartyType' => array(
                    'fields' => array(
                        'PartyType.name',
                        'PartyType.id'
                    )
                )
            ) ,
            'recursive' => 0,
        ));
        if (empty($partyPlanner)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= ' - ' . $partyPlanner['PartyPlanner']['name'];
        $this->set('partyPlanner', $partyPlanner);
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->PartyPlanner->delete($id)) {
            $this->Session->setFlash(__l('Party Planner deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
