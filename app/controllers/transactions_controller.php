
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
class TransactionsController extends AppController
{
    public $name = 'Transactions';
    public function index()
    {
        $this->pageTitle = __l('Transactions');
        $this->Transaction->recursive = 0;
        $this->set('transactions', $this->paginate());
    }
    public function admin_index()
    {
        $this->pageTitle = __l('Transactions');
        $this->Transaction->recursive = 0;
        $site_fee_total = $this->Transaction->find('first', array(
            'fields' => array(
                'SUM(Transaction.site_fee) as site_fee_total'
            ) ,
            'recursive' => 1
        ));
        $total_amount = $this->Transaction->find('first', array(
            'fields' => array(
                'SUM(Transaction.amount) as total_amount'
            ) ,
            'recursive' => 0
        ));
        $this->Transaction->recursive = 0;
        $this->paginate = array(
            'contain' => array(
                'User',
                'GuestListUser' => array(
                    'GuestList' => array(
                        'Event'
                    )
                ) ,
                'TransactionType'
            ) ,
            'order' => array(
                'GuestListUser.id' => 'desc'
            ) ,
            'recursive' => 3
        );
        $this->set('transactions', $this->paginate());
        $this->set('site_fee_total', $site_fee_total);
        $this->set('total_amount', $total_amount);
    }
    public function admin_delete($id = null)
    {
        if (is_null($id)) {
            throw new NotFoundException(__l('Invalid request'));
        }
        if ($this->Transaction->delete($id)) {
            $this->Session->setFlash(__l('Transaction deleted') , 'default', null, 'success');
            $this->redirect(array(
                'action' => 'index'
            ));
        } else {
            throw new NotFoundException(__l('Invalid request'));
        }
    }
}
?>