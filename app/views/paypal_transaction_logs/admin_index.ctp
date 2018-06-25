<?php /* SVN: $Id: admin_index.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */
?>
<h2><?php echo $this->pageTitle;?></h2>
<div class="home-page-block">
<div class="paypalTransactionLogs index">
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
    <tr>
       <th><?php echo $this->Paginator->sort(__l('Date'),'created');?></th>
        <th><?php echo $this->Paginator->sort(__l('User'),'User.username');?></th>
        <th><?php echo $this->Paginator->sort(__l('Transaction ID'),'txn_id');?></th>
        <th><?php echo $this->Paginator->sort(__l('User email'),'payer_email');?></th>
        <th><?php echo $this->Paginator->sort(__l('Amount').' ('.Configure::read('site.currency').')','mc_gross');?></th>
        <th><?php echo $this->Paginator->sort(__l('Fees').' ('.Configure::read('site.currency').')','mc_fee');?></th>
        <th><?php echo $this->Paginator->sort(__l('Status'), 'payment_status');?></th>
        <th><?php echo $this->Paginator->sort('paypal_response');?></th>
        <th><?php echo $this->Paginator->sort(__l('Error Message'),'error_message');?></th>
		<th><?php echo $this->Paginator->sort(__l('Error Code'),'error_no');?></th>
    </tr>
<?php
if (!empty($paypalTransactionLogs)):

$i = 0;
foreach ($paypalTransactionLogs as $paypalTransactionLog):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
	if (!empty($paypalTransactionLog['PaypalTransactionLog']['error_no'])):
		$class = ' class="error-log"';
	endif;
?>
	<tr<?php echo $class;?>>
		<td>
			<div class="actions-block">
				<div class="actions round-5-left">
					<span><?php echo $this->Html->link(__l('View'), array('controller' => 'paypal_transaction_logs', 'action' => 'view', $paypalTransactionLog['PaypalTransactionLog']['id']), array('class' => 'view', 'title' => __l('View')));?></span>
				</div>
			</div>
			<?php echo $this->Html->cDateTime($paypalTransactionLog['PaypalTransactionLog']['created']) ;?>
		</td>
		<td><?php echo ($paypalTransactionLog['User']['username']) ? $this->Html->link($this->Html->cText($paypalTransactionLog['User']['username'], false), array('controller' => 'users', 'action' => 'view', $paypalTransactionLog['User']['username'], 'admin' => false), array('title' => $this->Html->cText($paypalTransactionLog['User']['username'], false))) : __l('New User');?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['txn_id']);?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payer_email']);?></td>
		<td><?php echo $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_gross']);?></td>
		<td><?php echo $this->Html->cFloat($paypalTransactionLog['PaypalTransactionLog']['mc_fee']);?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['payment_status']);?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['paypal_response']);?></td>
		<td><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['error_message']);?></td>
		<td><span class="js-show-tip" title="<?php echo $paypalTransactionLog['PaypalTransactionLog']['error_message'];?>"><?php echo $this->Html->cText($paypalTransactionLog['PaypalTransactionLog']['error_no']);?></span></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="26" class="notice"><?php echo __l('No Paypal Transaction Logs available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($paypalTransactionLogs)) {
    echo $this->element('paging_links', array('cache' => 0));
}
?>
</div>
</div>
