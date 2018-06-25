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
class VenueUsersController extends AppController
{
    public $name = 'VenueUsers';
    public function index() 
    {
        $this->pageTitle = __l('Venue Users');
        if (!empty($this->request->params['named']['venue'])) {
            $venue = $this->VenueUser->Venue->find('first', array(
                'conditions' => array(
                    'Venue.slug' => $this->request->params['named']['venue']
                ) ,
                'contain' => array(
                    'City' => array(
                        'fields' => array(
                            'City.name'
                        )
                    )
                ) ,
                'fields' => array(
                    'Venue.id',
                    'Venue.name',
                    'Venue.slug',
                ) ,
                'recursive' => 0
            ));
            if (empty($venue)) {
                throw new NotFoundException(__l('Invalid request'));
            }
            $this->set('venue', $venue);
            $conditions['Venue.slug'] = $this->request->params['named']['venue'];
            $venue_users = $this->VenueUser->find('first', array(
                'conditions' => array(
                    'VenueUser.venue_id' => $venue['Venue']['id'],
                    'VenueUser.user_id' => $this->Auth->user('id') ,
                ) ,
                'recursive' => -1
            ));
            $this->set('venue_users', $venue_users);
            $venueUsercount = $this->VenueUser->find('count', array(
                'conditions' => array(
                    'VenueUser.venue_id' => $venue['Venue']['id'],
                ) ,
                'recursive' => -1,
            ));
            $this->set('venueUsercount', $venueUsercount);
            if (isset($this->request->params['named']['type'])) {
                $this->set('type', $this->request->params['named']['type']);
            }
        } else {
            $conditions['VenueUser.user_id'] = $this->Auth->user('id');
        }
        if (isset($this->request->params['named']['limit'])) {
            $limit = $this->request->params['named']['limit'];
        }
        $limit = !empty($limit) ? $limit : '15';
        $this->paginate = array(
            'conditions' => $conditions,
            'fields' => array(
                'VenueUser.id',
                'VenueUser.user_id',
                'VenueUser.venue_id',
            ) ,
            'contain' => array(
                'User' => array(
                    'UserAvatar',
                    'UserProfile' => array(
                        'fields' => array(
                            'UserProfile.first_name',
                            'UserProfile.last_name',
                            'UserProfile.middle_name',
                            'UserProfile.dob',
                            'UserProfile.about_me',
                            'UserProfile.address',
                            'UserProfile.zip_code',
                            'UserProfile.description',
                            'UserProfile.is_show_month_date'
                        ) ,
                        'City' => array(
                            'fields' => array(
                                'City.name'
                            )
                        ) ,
                        'Country' => array(
                            'fields' => array(
                                'Country.name'
                            )
                        ) ,
                        'Gender' => array(
                            'fields' => array(
                                'Gender.name'
                            )
                        )
                    ) ,
                    'fields' => array(
                        'User.username',
                        'User.created',
                        'User.user_type_id',
                        'User.id',
                        'User.fb_user_id',
                        'User.twitter_avatar_url',
                    )
                ) ,
                'Venue' => array(
                    'Attachment' => array(
                        'fields' => array(
                            'Attachment.filename',
                            'Attachment.dir',
                            'Attachment.id',
                        )
                    ) ,
                    'fields' => array(
                        'Venue.name',
                        'Venue.slug',
                    ) ,
                ) ,
            ) ,
            'recursive' => 1,
            'limit' => $limit,
        );
        $this->set('venueUsers', $this->paginate());
    }
    public function add($venue_id = null) 
    {
        if (!empty($venue_id)) {
            $is_sucees = true;
            $venueSlug = $this->VenueUser->Venue->find('first', array(
                'conditions' => array(
                    'Venue.id' => $venue_id,
                ) ,
                'fields' => array(
                    'Venue.slug',
                ) ,
                'recursive' => -1,
            ));
            if (!empty($venueSlug)) {
                $this->request->data['VenueUser']['user_id'] = $this->Auth->user('id');
                $this->request->data['VenueUser']['venue_id'] = $venue_id;
                $this->VenueUser->create();
                if ($this->VenueUser->save($this->request->data, false)) {
                    if (!$this->RequestHandler->isAjax()) {
                        $this->Session->setFlash(__l('Your are added to regular from venue list') , 'default', null, 'success');
                    }
                }
            } else {
                if (!$this->RequestHandler->isAjax()) {
                    $this->Session->setFlash(__l('Error in adding venue user') , 'default', null, 'success');
                }
            }
        }
        $slug = $this->VenueUser->Venue->find('first', array(
            'conditions' => array(
                'Venue.id' => $venue_id,
            ) ,
            'fields' => array(
                'Venue.slug',
            ) ,
            'recursive' => -1,
        ));
        if ($this->RequestHandler->isAjax()) {
            if ($is_sucees) {
                echo "added|" . Router::url(array(
                    'controller' => 'venue_users',
                    'action' => 'delete',
                    $this->VenueUser->getInsertID() ,
                    $slug['Venue']['slug']
                ) , true);
            }
            exit;
        } else {
            $this->redirect(array(
                'controller' => 'venues',
                'action' => 'view',
                $slug['Venue']['slug']
            ));
        }
    }
    public function delete($id = null, $venue_slug = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        $venue = $this->VenueUser->Venue->find('first', array(
            'conditions' => array(
                'Venue.slug' => $venue_slug
            ) ,
            'fields' => array(
                'Venue.id',
            ) ,
            'recursive' => -1,
        ));
        if ($this->VenueUser->delete($id, false)) {
            if ($this->RequestHandler->isAjax()) {
                echo "removed|" . Router::url(array(
                    'controller' => 'venue_users',
                    'action' => 'add',
                    $venue['Venue']['id']
                ) , true);
                exit;
            }
            $this->Session->setFlash(__l('Your are removed to regular from venue list') , 'default', null, 'success');
            $this->redirect(array(
                'controller' => 'venues',
                'action' => 'view',
                $venue_slug
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Venue Users');
        $conditions = array();
        if (isset($this->request->params['named']['venue'])) {
            $conditions['VenueUser.venue_id'] = $this->request->params['named']['venue'];
        }
        if (isset($this->request->params['named']['user'])) {
            $conditions['VenueUser.user_id'] = $this->request->params['named']['user'];
        }
        $this->paginate = array(
            'conditions' => $conditions,
			'order' => array(
				'VenueUser.id' =>  'DESC'
			),
            'recursive' => 0
        );
        $this->set('venueUsers', $this->paginate());
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueUser->delete($id)) {
            $this->Session->setFlash(__l('Venue User deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>