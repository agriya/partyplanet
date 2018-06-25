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
class VenueCategoriesController extends AppController
{
    public $name = 'VenueCategories';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'VenueCategory.makeActive',
            'VenueCategory.makeInactive',
            'VenueCategory.makeDelete',
        );
        parent::beforeFilter();
    }
    public function index() 
    {
        $this->pageTitle = __l('Venue Categories');
        $this->paginate = array(
            'conditions' => array(
                'VenueCategory.is_active' => 1,
            ) ,
            'recursive' => -1,
        );
        $this->set('venues', $this->paginate());
        $this->set('user_id', $this->Auth->user('id'));
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Venue Categories');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['VenueCategory']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['VenueCategory.name Like'] = '%' . $this->request->data['VenueCategory']['keyword'] . '%';
        }
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['VenueCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['VenueCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'contains' => array(
                'Venue',
            ) ,
            'recursive' => 0,
            'order' => 'VenueCategory.id desc'
        );
        $this->set('venueCategories', $this->paginate());
		$this->set('active_count', $this->VenueCategory->find('count', array(
            'conditions' => array(
                'VenueCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->VenueCategory->find('count', array(
            'conditions' => array(
                'VenueCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->VenueCategory->find('count'));
        $moreActions = $this->VenueCategory->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Venue Category');
        if (!empty($this->request->data)) {
            $this->VenueCategory->create();
            if ($this->VenueCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Venue category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Venue category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Venue Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->VenueCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Venue category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Venue category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->VenueCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['VenueCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->VenueCategory->delete($id)) {
            $this->Session->setFlash(__l('Venue category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>