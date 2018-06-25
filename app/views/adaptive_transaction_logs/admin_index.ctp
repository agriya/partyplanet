<?php /* SVN: $Id: $ */ ?>
<div class="adaptiveTransactionLogs index"> <?php echo $this->element('paging_counter');?>
  <div class="overflow-block">
    <table class="list">
      <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('id', __l('Id'));?></th>
        <th><?php echo $this->Paginator->sort('created', __l('Created'));?></th>
        <th><?php echo $this->Paginator->sort('foreign_id', __l('Foreign ID'));?></th>
        <th><?php echo $this->Paginator->sort('class', __l('Class'));?></th>
        <th><?php echo $this->Paginator->sort('transaction_id', __l('Transaction'));?></th>
        <th><?php echo $this->Paginator->sort('amount', __l('Amount') . ' (' . Configure::read('site.currency') . ')');?></th>
        <th><?php echo $this->Paginator->sort('email', __l('Email'));?></th>
        <th><?php echo $this->Paginator->sort('primary', __l('Primary'));?></th>
        <th><?php echo $this->Paginator->sort('invoice_id', __l('Invoice'));?></th>
        <th><?php echo $this->Paginator->sort('refunded_amount', __l('Refunded Amount'));?></th>
        <th><?php echo $this->Paginator->sort('pending_refund', __l('Pending Refund'));?></th>
        <th><?php echo $this->Paginator->sort('sender_transaction_id', __l('Sender Transaction'));?></th>
        <th><?php echo $this->Paginator->sort('sender_transaction_status', __l('Sender Transaction Status'));?></th>
        <th><?php echo $this->Paginator->sort('timestamp', __l('TimeStamp'));?></th>
        <th><?php echo $this->Paginator->sort('ack', __l('Acknowledgment'));?></th>
        <th><?php echo $this->Paginator->sort('correlation_id', __l('Correlation'));?></th>
        <th><?php echo $this->Paginator->sort('build', __l('Build'));?></th>
        <th><?php echo $this->Paginator->sort('currency_code', __l('Currency Code'));?></th>
        <th><?php echo $this->Paginator->sort('sender_email', __l('Sender Email'));?></th>
        <th><?php echo $this->Paginator->sort('status', __l('Status'));?></th>
        <th><?php echo $this->Paginator->sort('tracking_id', __l('Tracking'));?></th>
        <th><?php echo $this->Paginator->sort('pay_key', __l('Pay Key'));?></th>
        <th><?php echo $this->Paginator->sort('action_type', __l('Action Type'));?></th>
        <th><?php echo $this->Paginator->sort('fees_payer', __l('Fees Payer'));?></th>
        <th><?php echo $this->Paginator->sort('memo', __l('Memo'));?></th>
        <th><?php echo $this->Paginator->sort('reverse_all_parallel_payments_on_error', __l('Reverse All Parallel Payments On Error'));?></th>
        <th><?php echo $this->Paginator->sort('refund_status', __l('Refund Status'));?></th>
        <th><?php echo $this->Paginator->sort('refund_net_amount', __l('Refund Net Amount'));?></th>
        <th><?php echo $this->Paginator->sort('refund_fee_amount', __l('Refund Fee Amount'));?></th>
        <th><?php echo $this->Paginator->sort('refund_gross_amount', __l('Refund Gross Amount'));?></th>
        <th><?php echo $this->Paginator->sort('total_of_alll_refunds', __l('Total Of Alll Refunds'));?></th>
        <th><?php echo $this->Paginator->sort('refund_has_become_full', __l('Refund Has Become Full'));?></th>
        <th><?php echo $this->Paginator->sort('encrypted_refund_transaction_id', __l('Encrypted Refund Transaction'));?></th>
        <th><?php echo $this->Paginator->sort('refund_transaction_status', __l('Refund Transaction Status'));?></th>
      </tr>
<?php
	if (!empty($adaptiveTransactionLogs)):
		$i = 0;
		foreach ($adaptiveTransactionLogs as $adaptiveTransactionLog):
			$class = null;
			if ($i++ % 2 == 0) {
				$class = ' class="altrow"';
			}
?>
      <tr<?php echo $class;?>>
        <td class="actions"><div class="action-block"> <span class="action-information-block"> <span class="action-left-block">&nbsp;&nbsp;</span> <span class="action-center-block"> <span class="action-info"> <?php echo __l('Actions'); ?> </span> </span> </span>
            <div class="action-inner-block">
              <div class="action-inner-left-block">
                <ul class="action-link clearfix">
                  <li> <span><?php echo $this->Html->link(__l('View'), array('controller' => 'adaptive_transaction_logs', 'action' => 'view', $adaptiveTransactionLog['AdaptiveTransactionLog']['id']), array('class' => 'view', 'title' => __l('View')));?></span></li>
                </ul>
              </div>
              <div class="action-bottom-block"></div>
            </div>
          </div></td>
        <td><?php echo $this->Html->cInt($adaptiveTransactionLog['AdaptiveTransactionLog']['id']);?></td>
        <td><?php echo $this->Html->cDateTimeHighlight($adaptiveTransactionLog['AdaptiveTransactionLog']['created']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['foreign_id']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['class']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['transaction_id']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['amount']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['email']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['primary']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['invoice_id']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refunded_amount']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pending_refund']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_id']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_status']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['timestamp']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['ack']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['correlation_id']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['build']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['currency_code']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_email']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['status']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['tracking_id']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pay_key']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['action_type']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['fees_payer']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['memo']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['reverse_all_parallel_payments_on_error']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_status']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_net_amount']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_fee_amount']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_gross_amount']);?></td>
        <td><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['total_of_alll_refunds']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_has_become_full']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['encrypted_refund_transaction_id']);?></td>
        <td><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_transaction_status']);?></td>
      </tr>
<?php
		endforeach;
	else:
?>
      <tr>
        <td colspan="36" class="notice"><?php echo sprintf(__l('No %s available'), __l('Adaptive Transaction Logs'));?></td>
      </tr>
<?php
	endif;
?>
    </table>
  </div>
<?php
	if (!empty($adaptiveTransactionLogs)) { ?>
		<div class="js-pagination clearfix"><?php echo $this->element('paging_links'); ?></div>
<?php
	}
?>
</div>