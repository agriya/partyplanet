<?php /* SVN: $Id: $ */ ?>
<div class="adaptiveTransactionLogs view">
  <dl class="list clearfix">
    <?php $i = 0; $class = ' class="altrow"';?>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cInt($adaptiveTransactionLog['AdaptiveTransactionLog']['id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Created');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($adaptiveTransactionLog['AdaptiveTransactionLog']['created']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Modified');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cDateTime($adaptiveTransactionLog['AdaptiveTransactionLog']['modified']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Class');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['class']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Foreign Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['foreign_id']); ?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Transaction Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['transaction_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Amount');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->siteCurrencyFormat($adaptiveTransactionLog['AdaptiveTransactionLog']['amount']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Email');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['email']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Primary');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['primary']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Invoice Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['invoice_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refunded Amount');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refunded_amount']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Pending Refund');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pending_refund']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sender Transaction Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sender Transaction Status');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_transaction_status']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Timestamp');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['timestamp']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Ack');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['ack']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Correlation Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['correlation_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Build');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['build']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Currency Code');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['currency_code']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Sender Email');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['sender_email']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Status');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['status']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Tracking Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['tracking_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Pay Key');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['pay_key']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Action Type');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['action_type']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Fees Payer');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['fees_payer']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Memo');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['memo']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Reverse All Parallel Payments On Error');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['reverse_all_parallel_payments_on_error']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Status');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_status']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Net Amount');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_net_amount']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Fee Amount');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_fee_amount']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Gross Amount');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_gross_amount']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Total Of Alll Refunds');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cCurrency($adaptiveTransactionLog['AdaptiveTransactionLog']['total_of_alll_refunds']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Has Become Full');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_has_become_full']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Encrypted Refund Transaction Id');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['encrypted_refund_transaction_id']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('Refund Transaction Status');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['refund_transaction_status']);?></dd>
    <dt<?php if ($i % 2 == 0) echo $class;?>><?php echo __l('PayPal Post Vars');?></dt>
    <dd<?php if ($i++ % 2 == 0) echo $class;?>><?php echo $this->Html->cText($adaptiveTransactionLog['AdaptiveTransactionLog']['paypal_post_vars']);?></dd>
  </dl>
</div>