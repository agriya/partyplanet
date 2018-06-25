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
class EventCategoriesController extends AppController
{
    public $name = 'EventCategories';
    public function beforeFilter() 
    {
        $this->Security->disabledFields = array(
            'EventCategory.makeActive',
            'EventCategory.makeInactive',
            'EventCategory.makeDelete'
        );
        parent::beforeFilter();
    }
    public function admin_index() 
    {
        $this->pageTitle = __l('Event Categories');
		$conditions = array();
        if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['EventCategory.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['EventCategory.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
		$this->paginate = array(
			'conditions' => array(
				$conditions,
			),
			'recursive' => 0,
            'order' => 'EventCategory.id desc'
		);
		$eventCategories = $this->paginate();
        $event_count_categories = $this->EventCategory->Event->find('all', array(
            'fields' => array(
                'Event.id',
                'count(Event.id) as count',
                'Event.event_category_id'
            ) ,
            'group' => array(
                'Event.event_category_id'
            ) ,
            'recursive' => -1
        ));
        foreach($eventCategories As $key => $eventCategory) {
            foreach($event_count_categories As $event_count_category) {
                if ($event_count_category['Event']['event_category_id'] == $eventCategory['EventCategory']['id']) {
                    $eventCategories[$key]['EventCategory']['count'] = $event_count_category[0]['count'];
                }
            }
        }
        $this->set('eventCategories', $eventCategories);
		$this->set('active_count', $this->EventCategory->find('count', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->EventCategory->find('count', array(
            'conditions' => array(
                'EventCategory.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->EventCategory->find('count'));
        $moreActions = $this->EventCategory->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Event Category');
        if (!empty($this->request->data)) {
            $this->EventCategory->create();
            if ($this->EventCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Event category has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Event category could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Event Category');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->EventCategory->save($this->request->data)) {
                $this->Session->setFlash(__l('Event category has been updated') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l('Event category could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->EventCategory->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['EventCategory']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->EventCategory->delete($id)) {
            $this->Session->setFlash(__l('Event category deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>