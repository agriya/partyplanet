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
class FavoriteFashionBrandsController extends AppController
{
    public $name = 'FavoriteFashionBrands';
    public function admin_index() 
    {
        $this->pageTitle = __l('Favorite Fashion Brands');
		$conditions = array();
		if (!empty($this->request->params['named']['filter_id'])) {
            if ($this->request->params['named']['filter_id'] == ConstMoreAction::Active) {
                $conditions['FavoriteFashionBrand.is_active'] = 1;
                $this->pageTitle.= __l(' - Active ');
            } elseif ($this->request->params['named']['filter_id'] == ConstMoreAction::Inactive) {
                $conditions['FavoriteFashionBrand.is_active'] = 0;
                $this->pageTitle.= __l(' - Inactive ');
            }
        }
        $this->paginate = array(
            'conditions' => array(
                $conditions
            ) ,
            'recursive' => 0,
            'order' => 'FavoriteFashionBrand.id desc'
        );
        $this->set('favoriteFashionBrands', $this->paginate());
		$this->set('active_count', $this->FavoriteFashionBrand->find('count', array(
            'conditions' => array(
                'FavoriteFashionBrand.is_active = ' => 1,
            )
        )));
        $this->set('inactive_count', $this->FavoriteFashionBrand->find('count', array(
            'conditions' => array(
                'FavoriteFashionBrand.is_active = ' => 0,
            )
        )));
        $this->set('total_count', $this->FavoriteFashionBrand->find('count'));
        $moreActions = $this->FavoriteFashionBrand->moreActions;
        $this->set(compact('moreActions'));
    }
    public function admin_add() 
    {
        $this->pageTitle = __l('Add Favorite Fashion Brand');
        if (!empty($this->request->data)) {
            $this->FavoriteFashionBrand->create();
            if ($this->FavoriteFashionBrand->save($this->request->data)) {
                $this->Session->setFlash(__l(' Favorite Fashion Brand has been added') , 'default', null, 'success');
                $this->redirect(array(
                    'action' => 'index'
                ));
            } else {
                $this->Session->setFlash(__l(' Favorite Fashion Brand could not be added. Please, try again.') , 'default', null, 'error');
            }
        }
    }
    public function admin_edit($id = null) 
    {
        $this->pageTitle = __l('Edit Favorite Fashion Brand');
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if (!empty($this->request->data)) {
            if ($this->FavoriteFashionBrand->save($this->request->data)) {
                $this->Session->setFlash(__l(' Favorite Fashion Brand has been updated') , 'default', null, 'success');
            } else {
                $this->Session->setFlash(__l(' Favorite Fashion Brand could not be updated. Please, try again.') , 'default', null, 'error');
            }
        } else {
            $this->request->data = $this->FavoriteFashionBrand->read(null, $id);
            if (empty($this->request->data)) {
                throw new NotFoundException(__l('Invalid request'));
            }
        }
        $this->pageTitle.= ' - ' . $this->request->data['FavoriteFashionBrand']['name'];
    }
    public function admin_delete($id = null) 
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->FavoriteFashionBrand->delete($id)) {
            $this->Session->setFlash(__l('Favorite Fashion Brand deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>