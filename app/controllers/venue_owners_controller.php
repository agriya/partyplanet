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
class VenueOwnersController extends AppController
{
    public $name = 'VenueOwners';
    public $components = array(
        'Email',
        'RequestHandler',
    );
    public $uses = array(
        'VenueOwner',
        'User',
        'EmailTemplate',
    );
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'City.id'
        );
        parent::beforeFilter();
    }
    public function add() 
    {
        $this->pageTitle = __l('Venue Owner Signup');
        if (!empty($this->request->data)) {
            $this->request->data['VenueOwner']['ip_id'] =  $this->VenueOwner->toSaveIp();
            $this->request->data['VenueOwner']['city_id'] = !empty($this->request->data['City']['id']) ? $this->request->data['City']['id'] : $this->VenueOwner->City->findOrSaveAndGetId($this->request->data['City']['name']);
            $this->VenueOwner->set($this->request->data);
            $this->User->set($this->request->data['User']);			
			$captcha_error = 0;
			if(Configure::read('system.captcha_type') == "Solve media"){
				if(!$this->VenueOwner->_isValidCaptchaSolveMedia()){
					$captcha_error = 1;
				}
			}
            if ($this->VenueOwner->validates() &$this->User->validates() && empty($captcha_error)) {
                $this->request->data['VenueOwner']['email'] = $this->request->data['User']['email'];
                $this->VenueOwner->create();
                $this->VenueOwner->save($this->request->data);
                $venueTypes = $this->VenueOwner->VenueType->find('list');
                $genders = $this->VenueOwner->Gender->find('list');
                $emailFindReplace = array(
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##FIRST_NAME##' => $this->request->data['VenueOwner']['first_name'],
                    '##LAST_NAME##' => !empty($this->request->data['VenueOwner']['last_name']) ? ' ' . $this->request->data['VenueOwner']['last_name'] : '',
                    '##FROM_EMAIL##' => $this->request->data['VenueOwner']['email'],
                    '##SITE_ADDR##' => gethostbyaddr($this->RequestHandler->getClientIP()),
                    '##IP##' => $this->RequestHandler->getClientIP(),
                    '##MOBILE##' => $this->request->data['VenueOwner']['mobile'],
                    '##TELEPHONE##' => $this->request->data['VenueOwner']['other_mobile'],
                    '##VENUE_NAME##' => $this->request->data['VenueOwner']['venue_name'],
                    '##VENUE_TYPE##' => $venueTypes[$this->request->data['VenueOwner']['venue_type_id']],
                    '##POST_DATE##' => date('F j, Y g:i:s A (l) T (\G\M\TP)') ,
                    '##SITE_URL##' => Router::url('/', true) ,
                    '##VENUE_OWNER##' => Router::url(array(
                        'controller' => 'venue_owners',
                        'action' => 'admin_index',
                        'admin' => true,
                        'filter_id' => ConstMoreAction::Inactive,
                    ), true) ,
                    '##GENDER##' => $genders[$this->request->data['VenueOwner']['gender_id']],
                    '##DOB##' => $this->request->data['VenueOwner']['dob']['year'] . '-' . $this->request->data['VenueOwner']['dob']['month'] . '-' . $this->request->data['VenueOwner']['dob']['day'],
                );
                // send to Admin email
                $email = $this->EmailTemplate->selectTemplate('Venue Owner Register');
                $this->Email->from = strtr($email['from'], $emailFindReplace);
                $this->Email->to = Configure::read('site.contact_email');
                $this->Email->subject = $email['subject'];
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(trim(strtr($email['email_content'], $emailFindReplace)));
                // reply email
                $email = $this->EmailTemplate->selectTemplate('Auto Reply for Register Venue Owner');
                $this->Email->from = strtr($email['from'], $emailFindReplace);
                $this->Email->to = $this->request->data['VenueOwner']['email'];
                $this->Email->subject = $email['subject'];
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(trim(strtr($email['email_content'], $emailFindReplace)));
                $this->Session->setFlash(__l('You have successfully registered with our site.') , 'default', null, 'success');
                $this->set('success', 1);
            } else {
				if(!empty($captcha_error)) {
					$this->VenueOwner->validationErrors['captcha'] = __l('Required');
				}
                $this->Session->setFlash(__l('Your registration process is not completed. Please, try again.') , 'default', null, 'error');
            }
        }
        $cities = $this->VenueOwner->City->find('list', array('conditions' => array('City.is_approved' => 1)));
        $countries = $this->VenueOwner->Country->find('list');
        $genders = $this->VenueOwner->Gender->find('list');
        $venueTypes = $this->VenueOwner->VenueType->find('list', array(
			'conditions' => array(
				'VenueType.is_active' => 1
				), 
				'order' => array(
					'VenueType.name' => 'ASC'
			),
			));
        $this->set(compact('cities', 'countries', 'genders', 'venueTypes'));
        unset($this->VenueOwner->validate['dob']);
    }
    public function admin_index() 
    {
        $conditions = array();
        $this->pageTitle = __l('Venue Owners');
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueOwner.is_created'] = 1;
                $this->pageTitle.= __l(' - Active Venue Owner Signup ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueOwner.is_created'] = 0;
                $this->pageTitle.= __l(' - Pending Venue Owner Signup ');
            }
        } else {
            $conditions['VenueOwner.is_created'] = 1;
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'limit' => 15,
            'order' => 'VenueOwner.id DESC',
            'recursive' => 0
        );
        $this->set('active', $this->VenueOwner->find('count', array(
            'conditions' => array(
                'VenueOwner.is_created' => 1,
            ) ,
            'recursive' => -1
        )));
        $this->set('inactive', $this->VenueOwner->find('count', array(
            'conditions' => array(
                'VenueOwner.is_created' => 0,
            ) ,
            'recursive' => -1
        )));
        $this->set('venueOwners', $this->paginate());
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Owner');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            $filterid = !empty($this->request->data['VenueOwner']['is_created']) ? ConstMoreAction::Active : ConstMoreAction::Inactive;
            if ($this->VenueOwner->save($this->request->data)) {
                $this->Session->setFlash(__l('Venue Owner has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'controller' => 'venue_owners',
                    'action' => 'index',
                    'filter_id' => $filterid
                ));
            } else {
                $this->Session->setFlash(__l('Venue Owner could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $conditions['VenueOwner.id'] = $id;
            $this->request->data = $this->VenueOwner->find('first', array(
                'conditions' => $conditions,
            ));
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueOwner']['first_name'];
        $cities = $this->VenueOwner->City->find('list', array('conditions' => array('City.is_approved' => 1)));
        $countries = $this->VenueOwner->Country->find('list');
        $genders = $this->VenueOwner->Gender->find('list');
        $venueTypes = $this->VenueOwner->VenueType->find('list', array('conditions' => array('VenueType.is_active' => 1), 'order' => 'VenueType.name ASC'));
        $this->set(compact('cities', 'countries', 'genders', 'venueTypes'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueOwner->delete($id)) {
            $this->Session->setFlash(__l('Venue Owner deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>