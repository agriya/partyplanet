<?php /* SVN: $Id: $ */ ?>
<div class="affiliateCashWithdrawals form js-ajax-form-container">
    <h2><?php echo __l('Affiliate Cash Withdrawals'); ?></h2>
    <div class="add-block1">
        <?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?>
        <?php echo $this->Html->link(__l('Manage your money transfer account.'), array('controller' => 'money_transfer_accounts', 'action'=>'index'), array('title' => __l('Edit money transfer accounts')));
        ?>
    </div>
	<div class="page-info">
    	<?php echo __l('The requested amount will be deducted from your affiliate commission amount and the amount will be blocked until it get approved or rejected by the administrator. Once it\'s approved, the requested amount will be sent to your paypal account. In case of failure, the amount will be refunded to your affiliate commission amount.'); ?>
    	<?php echo __l('(Note: Transaction fee will be also taken during successful withdrawal.)'); ?>
    </div>
    <?php
		if($this->Auth->user('user_type_id') == ConstUserTypes::User){
			$min = Configure::read('affiliate.payment_threshold_for_threshold_limit_reach');	
			$cleared_amount = $user['User']['commission_line_amount'];
			$transaction_fee = Configure::read('affiliate.site_commission_amount');
			$transaction_fee_type = Configure::read('affiliate.site_commission_type');
			if(!empty($transaction_fee)){
				$transactions = ($transaction_fee_type == 'amount') ? $this->Html->cCurrency($transaction_fee) : $transaction_fee.'%';
				$transactions = '<br/>'.__l('Transaction Fee').':'. $transactions;
			}
			else{
				$transactions = '';
			}	
		}
	?>

	<?php	echo $this->Form->create('AffiliateCashWithdrawal', array('class' => "normal  js-ajax-form {container:'js-ajax-form-container',responsecontainer:'js-responses'}"));
			echo $this->Form->input('user_id', array('type' => 'hidden')); ?>
	    	  <?php
        			echo $this->Form->input('amount',array('label' => __l('Amount'),'after' => Configure::read('site.currency') . '<span class="info">' . sprintf(__l('Minimum withdraw amount: %s <br/>  Commission amount: %s  %s'),$this->Html->cCurrency($min),$this->Html->cCurrency($cleared_amount), $transactions . '</span>')));
              	?>
          	 	<div class="submit-block clearfix">
                    <?php echo $this->Form->submit(__l('Request Withdraw'));?>
               </div>
      
            <?php echo $this->Form->end();?>
   

</div>
