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
class ContactsController extends AppController
{
    public $name = 'Contacts';
    public $components = array(
        'Email',
        'RequestHandler'
    );
    public $uses = array(
        'Contact',
        'User',
        'EmailTemplate',
        'Venue'
    );
    public function add($id = null) 
    {
        if (!empty($this->request->data)) {
            if (!isset($this->request->data['Contact']['venue_id'])) {
                unset($this->Contact->validate['telephone']);
            }
			$captcha_error = 0;
			if(Configure::read('system.captcha_type') == "Solve media"){
				if(!$this->Contact->_isValidCaptchaSolveMedia()){
					$captcha_error = 1;
				}
			}
            $this->Contact->set($this->request->data);
            if ($this->Contact->validates() && empty($captcha_error)) {
                $this->request->data['Contact']['ip_id'] =  $this->Contact->toSaveIp();
                $this->request->data['Contact']['user_id'] = $this->Auth->user('id') ? $this->Auth->user('id') : '0';
                $this->Contact->save($this->request->data, false);
                $message = $this->request->data['Contact']['message'];
                if (isset($this->request->data['Contact']['venue_id'])) {
                    $venue = $this->Venue->find('first', array(
                        'conditions' => array(
                            'Venue.id' => $this->request->data['Contact']['venue_id']
                        ) ,
                        'fields' => array(
                            'Venue.id',
                            'Venue.name',
                            'Venue.slug',
                        ) ,
                        'recursive' => -1
                    ));
                    $venue_url = Router::url(array(
                        'controller' => 'venues',
                        'action' => 'view',
                        $venue['Venue']['slug']
                    ) , true);
                    $message.= '

Venue Name: ' . $venue['Venue']['name'];
                    $message.= '
Venue URL: ' . $venue_url;
                }
                $emailFindReplace = array(
                    '##SITE_NAME##' => Configure::read('site.name') ,
                    '##FIRST_NAME##' => $this->request->data['Contact']['first_name'],
                    '##LAST_NAME##' => !empty($this->request->data['Contact']['last_name']) ? ' ' . $this->request->data['Contact']['last_name'] : '',
                    '##FROM_EMAIL##' => $this->request->data['Contact']['email'],
                    '##FROM_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add'
                    ) , true) ,
                    '##SITE_ADDR##' => gethostbyaddr($this->request->data['Contact']['ip']) ,
                    '##IP##' => $this->request->data['Contact']['ip'],
                    '##TELEPHONE##' => $this->request->data['Contact']['telephone'],
                    '##MESSAGE##' => $message,
                    '##SUBJECT##' => $this->request->data['Contact']['subject'],
                    '##POST_DATE##' => date('F j, Y g:i:s A (l) T (\G\M\TP)') ,
                    '##CONTACT_URL##' => Router::url(array(
                        'controller' => 'contacts',
                        'action' => 'add'
                    ) , true) ,
                    '##SITE_URL##' => Router::url('/', true) ,
                );
                // send to contact email
                $email = $this->EmailTemplate->selectTemplate('Contact Us');
                $this->Email->from = strtr($email['from'], $emailFindReplace);
                $this->Email->to = Configure::read('site.contact_email');
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(trim(strtr($email['email_content'], $emailFindReplace)));
                // reply email
                $email = $this->EmailTemplate->selectTemplate('Contact Us Auto Reply');
                $this->Email->from = strtr($email['from'], $emailFindReplace);
                $this->Email->to = $this->request->data['Contact']['email'];
                $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                $this->Email->send(trim(strtr($email['email_content'], $emailFindReplace)));
                $this->Session->setFlash(__l('Contact has been sent') , 'default', null, 'success');
                $this->set('success', 1);
            } else {
				if(!empty($captcha_error)) {
					$this->Contact->validationErrors['captcha'] = __l('Required');
				}
                $this->Session->setFlash(__l('Contact could not be sent. Please, try again.') , 'default', null, 'error');
            }
        } else {
            if ($this->Auth->user()) {
                $user = $this->User->find('first', array(
                    'conditions' => array(
                        'User.id' => $this->Auth->user('id')
                    ) ,
                    'contain' => array(
                        'UserProfile' => array(
                            'fields' => array(
                                'UserProfile.first_name',
                                'UserProfile.last_name',
                                'UserProfile.phone'
                            )
                        )
                    ) ,
                    'fields' => array(
                        'User.email'
                    ) ,
                    'recursive' => 0
                ));
                $this->request->data['Contact']['first_name'] = $user['UserProfile']['first_name'];
                $this->request->data['Contact']['last_name'] = $user['UserProfile']['last_name'];
                $this->request->data['Contact']['telephone'] = $user['UserProfile']['phone'];
                $this->request->data['Contact']['email'] = $user['User']['email'];
            }
            if (!is_null($id)) {
                $venue = $this->Venue->find('first', array(
                    'conditions' => array(
                        'Venue.id' => $id
                    ) ,
                    'fields' => array(
                        'Venue.id',
                        'Venue.name',
                        'Venue.slug',
                    ) ,
                    'recursive' => -1
                ));
                $this->set('venue', $venue);
            }
        }
        if (!is_null($id)) {
            $this->set('venue_id', $id);
        } else {
            unset($this->Contact->validate['telephone']);
        }
        $this->pageTitle = __l('Contact Us');
        $contactTypes = $this->Contact->ContactType->find('list');
        $this->set(compact('contactTypes'));
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Contacts');
        $this->_redirectGET2Named(array(
            'keyword',
            'filter',
            'type',
            'contact_type_id',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (!empty($this->request->data['Contact']['contact_type_id'])) {
            $this->request->params['named']['main_filter_id'] = $this->request->data['Contact']['contact_type_id'];
        }
        if (isset($this->request->params['named']['filter'])) {
            $this->request->data['Contact']['filter'] = $this->request->params['named']['filter'];
        }
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['Contact']['keyword'] = $this->request->params['named']['keyword'];
        }
        if (isset($this->request->params['named']['type'])) {
            $this->request->data['Contact']['type'] = $this->request->params['named']['type'];
        }
        if (!empty($this->request->data['Contact']['filter'])) {
            if ($this->request->data['Contact']['filter'] == '1') {
                $conditions['Contact.first_name Like'] = '%' . $this->request->data['Contact']['keyword'] . '%';
            } else if ($this->request->data['User']['filter'] == '2') {
                $conditions['Contact.last_name Like'] = '%' . $this->request->data['Contact']['keyword'] . '%';
            } else if ($this->request->data['Contact']['filter'] == '3') {
                $conditions['Contact.email Like'] = '%' . $this->request->data['Contact']['keyword'] . '%';
            }
            $this->request->params['named']['filter'] = $this->request->data['Contact']['filter'];
        }
        if (!empty($this->request->data['Contact']['type'])) {
            $conditions['Contact.contact_type_id'] = $this->request->data['Contact']['type'];
        }
        if (isset($this->request->params['named']['main_filter_id'])) {
            if ($this->request->params['named']['main_filter_id'] != 'all') {
                $conditions['Contact.contact_type_id'] = $this->request->params['named']['main_filter_id'];
                $this->request->data['Contact']['main_filter_id'] = $this->request->params['named']['main_filter_id'];
            }
        }
        $this->Contact->recursive = 0;
        $this->paginate = array(
            'conditions' => $conditions,
            'order' => 'Contact.id DESC',
            'recursive' => 0
        );
        $this->set('contacts', $this->paginate());
        $moreActions = $this->Contact->moreActions;
        $filterActions = $this->Contact->isFilterOptions;
        $contactTypes = $this->Contact->ContactType->find('list');
        $this->set(compact('moreActions', 'filterActions', 'contactTypes'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Contact');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->Contact->save($this->request->data)) {
                $this->Session->setFlash(__l(' Contact has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Contact could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->Contact->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['Contact']['id'];
        $users = $this->Contact->User->find('list');
        $contactTypes = $this->Contact->ContactType->find('list');
        $this->set(compact('users', 'contactTypes'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Contact->delete($id)) {
            $this->Session->setFlash(__l('Contact deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_view($id = null) 
    {
        $this->pageTitle = __l('Contact');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $contact = $this->Contact->find('first', array(
            'conditions' => array(
                'Contact.id = ' => $id
            ) ,
            'recursive' => 0,
        ));
        if (empty($contact)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $this->pageTitle.= empty($contact['User']['username']) ? ' - ' . $contact['Contact']['first_name'] : ' - ' . $contact['User']['username'];
        $this->set('contact', $contact);
    }
}
?>