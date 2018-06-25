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
class FoodCateringsController extends AppController
{
    public $name = 'FoodCaterings';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'FoodCatering.makeActive',
            'FoodCatering.makeInactive',
            'FoodCatering.makeDelete',
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Food Caterings');
		$conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['FoodCatering.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['FoodCatering.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
		$this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'FoodCatering.id desc'
        );
        $this->set('foodCaterings', $this->paginate());
		$this->set('active_count', $this->FoodCatering->find('count', array(
            'conditions' => array(
                'FoodCatering.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->FoodCatering->find('count', array(
            'conditions' => array(
                'FoodCatering.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->FoodCatering->find('count'));
        $moreActions = $this->FoodCatering->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Food Catering');
        if (!empty($this->request->data)) {
            $this->FoodCatering->create();
            if ($this->FoodCatering->save($this->request->data)) {
                $this->Session->setFlash(__l(' Food Catering has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Food Catering could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
        $partyPlanners = $this->FoodCatering->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Food Catering');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->FoodCatering->save($this->request->data)) {
                $this->Session->setFlash(__l(' Food Catering has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Food Catering could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->FoodCatering->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['FoodCatering']['name'];
        $partyPlanners = $this->FoodCatering->PartyPlanner->find('list');
        $this->set(compact('partyPlanners'));
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FoodCatering->delete($id)) {
            $this->Session->setFlash(__l('Food Catering deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>