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
class ParkingTypesController extends AppController
{
    public $name = 'ParkingTypes';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'ParkingType.makeActive',
            'ParkingType.makeInactive',
            'ParkingType.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Parking Types');
        $this->_redirectGET2Named(array(
            'keyword',
        ));
        $conditions = array();
        // check the filer passed through named parameter
        if (isset($this->request->params['named']['keyword'])) {
            $this->request->data['ParkingType']['keyword'] = $this->request->params['named']['keyword'];
            $conditions['ParkingType.name Like'] = '%' . $this->request->data['ParkingType']['keyword'] . '%';
        }
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['ParkingType.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['ParkingType.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'ParkingType.id desc'
        );
        $this->set('parkingTypes', $this->paginate());
        $this->set('active_parking_types', $this->ParkingType->find('count', array(
            'conditions' => array(
                'ParkingType.is_active = ' => 1,
            )
        )));
        $this->set('inactive_parking_types', $this->ParkingType->find('count', array(
            'conditions' => array(
                'ParkingType.is_active = ' => 0,
            )
        )));
        $this->set('total_parking_types', $this->ParkingType->find('count'));
        $moreActions = $this->ParkingType->moreActions;
        $this->set(compact('moreActions'));
        $this->ParkingType->recursive = 0;
        $this->set('parkingTypes', $this->paginate());
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Parking Type');
        if (!empty($this->request->data)) {
            $this->ParkingType->create();
            if ($this->ParkingType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Parking Type has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Parking Type could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $venues = $this->ParkingType->Venue->find('list');
        $this->set(compact('venues'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Parking Type');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->ParkingType->save($this->request->data)) {
                $this->Session->setFlash(__l(' Parking Type has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Parking Type could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->ParkingType->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['ParkingType']['name'];
        $venues = $this->ParkingType->Venue->find('list');
        $this->set(compact('venues'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->ParkingType->delete($id)) {
            $this->Session->setFlash(__l('Parking Type deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>