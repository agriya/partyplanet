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
class GuestListUsersController extends AppController
{
    public $name = 'GuestListUsers';
    public $components = array(
        'Email',
        'Cookie'
    );
    public $uses = array(
        'GuestListUser',
        'EmailTemplate',
    );
    function index($id = null)
    {
        $this->pageTitle = __l('Guest List Users');
        $this->GuestListUser->recursive = 0;
        $rsvpResponses = $this->GuestListUser->RsvpResponse->find('list');
        $guest = array();
        foreach($rsvpResponses as $rsvp_id => $rsvp) {
            $guest[$rsvp] = $this->GuestListUser->find('all', array(
                'conditions' => array(
                    'GuestList.id' => $id,
                    'GuestListUser.rsvp_response_id' => $rsvp_id,
                    'GuestListUser.is_paid' => 1,
                )
            ));
        }
		$guestList = $this->GuestListUser->GuestList->find('first', array(
            'conditions' => array(
                'GuestList.id' => $id,
            ) ,
            'contain' => array(
                'Event' => array(
                    'fields' => array(
                        'Event.ticket_fee'
                    )
                )
            ) ,
            'recursive' => 3,
        ));
        $this->set('guest', $guest);
        $this->set('guestList', $guestList);
    }
    public function admin_index()
    {
        $this->pageTitle = __l('User Joined Events');
        $this->GuestListUser->recursive = 0;
        $conditions = array();
        if (isset($this->request->params['named']['event'])) {
            $conditions['GuestList.event_id'] = $this->request->params['named']['event'];
        }
        if (isset($this->request->params['named']['user'])) {
            $conditions['GuestListUser.user_id'] = $this->request->params['named']['user'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
            'contain' => array(
                'GuestList' => array(
                    'Event'
                ) ,
                'User'
            ) ,
            'order' => array(
                'GuestListUser.id' => 'desc'
            )
        );
        $this->set('eventUsers', $this->paginate());
    }
    public function user_list($event_id)
    {
        $this->pageTitle = __l('Event Users');
        $conditions = array(
            'GuestList.event_id' => $event_id,
            'GuestListUser.rsvp_response_id' <> ConstRsvpResponse::No,
            'GuestListUser.is_paid' => 1,
        );
        $this->paginate = array(
            'conditions' => $conditions,
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
                ) ,
                'GuestList' => array(
                    'Event' => array(
                        'fields' => array(
                            'Event.title',
                            'Event.slug',
                        ) ,
                    ) ,
                )
            ) ,
            'order' => 'GuestListUser.id DESC',
            'recursive' => 1,
        );
        $this->set('eventUsers', $this->paginate());
    }
    public function add($guest_list_id = null)
    {
        $this->pageTitle = __l('Add Guest List User');
        $guest_list_id = !empty($this->request->data['GuestListUser']['guest_list_id']) ? $this->request->data['GuestListUser']['guest_list_id'] : $guest_list_id;
        $guestList = $this->GuestListUser->GuestList->find('first', array(
            'conditions' => array(
                'GuestList.id' => $guest_list_id,
            ) ,
            'contain' => array(
                'Event' => array(
                    'fields' => array(
                        'Event.id',
                        'Event.start_date',
                        'Event.end_date',
                        'Event.user_id',
                        'Event.title',
                        'Event.slug',
                        'Event.ticket_fee',
                    ) ,
                    'Venue' => array(
                        'City',
                        'State',
                        'Country'
                    )
                )
            ) ,
            'recursive' => 3,
        ));
        if (!empty($this->request->data)) {
            $this->request->data['GuestListUser']['is_paid'] = 1;
            if ($guestList['Event']['ticket_fee'] > 0) {
                $this->request->data['GuestListUser']['is_paid'] = 0;
            }
            $this->GuestListUser->create();
            if ($this->GuestListUser->save($this->request->data)) {
                if (empty($guestList['Event']['ticket_fee'])) {
                    $this->Session->setFlash(__l(' Guest\'s RSVP Status Added') , 'default', null, 'success');
                }
                if ($this->request->data['GuestListUser']['rsvp_response_id'] != 2 && empty($guestList['Event']['ticket_fee'])) {
                    $email = $this->EmailTemplate->selectTemplate('Guest List SignUp User');
                    $this->Email->from = ($email['from'] == '##FROM_EMAIL##') ? Configure::read('EmailTemplate.from_email') : $email['from'];
                    $this->Email->replyTo = ($email['reply_to'] == '##REPLY_TO_EMAIL##') ? Configure::read('EmailTemplate.reply_to_email') : $email['reply_to'];
                    $this->Email->to = $this->Auth->user('email');
                    $time = strftime(Configure::read('site.time.format') , strtotime($guestList['GuestList']['guest_close_time'] . ' GMT'));
                    $emailFindReplace = array(
                        '##USERNAME##' => $this->Auth->user('username') ,
                        '##SITE_NAME##' => Configure::read('site.name') ,
                        '##EVENTNAME##' => $guestList['Event']['title'],
                        '##GUSTLISTDATE##' => date('d/m/Y', strtotime($guestList['Event']['start_date'])),
                        '##TIME##' => $time,
                        '##GUESTCOUNT##' => $this->request->data['GuestListUser']['in_party_count'],
                        '##SITE_URL##' => Router::url('/', true) ,
                        '##VENUEDETAILS##' => $guestList['Event']['Venue']['name'] . ', ' . $guestList['Event']['Venue']['address'] . ', ' . $guestList['Event']['Venue']['City']['name'] . ', ' . $guestList['Event']['Venue']['Country']['name']
                    );
                    $this->Email->subject = strtr($email['subject'], $emailFindReplace);
                    $this->Email->sendAs = ($email['is_html']) ? 'html' : 'text';
                    $this->Email->send(strtr($email['email_content'], $emailFindReplace));
                }
                if ($guestList['Event']['ticket_fee'] > 0) {
                    $this->redirect(array(
                        'controller' => 'payments',
                        'action' => 'pay_now',
                        $this->GuestListUser->getLastInsertId()
                    ));
                } else {
                    $this->redirect(array(
                        'controller' => 'events',
                        'action' => 'view',
                        $guestList['Event']['slug']
                    ));
                }
            } else {
                $this->Session->setFlash(__l('Guest List User could not be added. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data['GuestListUser']['guest_list_id'] = $guest_list_id;
            $this->request->data['GuestListUser']['user_id'] = $this->Auth->user('id');
            $this->request->data['GuestListUser']['rsvp_response_id'] = 2;
        }
        $rsvpResponses = $this->GuestListUser->RsvpResponse->find('list');
        $this->set(compact('rsvpResponses', 'guestList'));
    }
}
?>