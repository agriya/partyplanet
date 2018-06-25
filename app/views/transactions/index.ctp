<?php /* SVN: $Id: index.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="transactions index">
<h2><?php echo __l('Transactions');?></h2>
<?php echo $this->element('paging_counter');?>
<ol class="list" start="<?php echo $this->Paginator->counter(array(
    'format' => '%start%'
));?>">
<?php
if (!empty($transactions)):

$i = 0;
foreach ($transactions as $transaction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<li<?php echo $class;?>>
		<p><?php echo $this->Html->cInt($transaction['Transaction']['id']);?></p>
		<p><?php echo $this->Html->cDateTime($transaction['Transaction']['created']);?></p>
		<p><?php echo $this->Html->cDateTime($transaction['Transaction']['modified']);?></p>
		<p><?php echo $this->Html->link($this->Html->cText($transaction['User']['username']), array('controller'=> 'users', 'action' => 'view', $transaction['User']['username']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cInt($transaction['Transaction']['foreign']);?></p>
		<p><?php echo $this->Html->cText($transaction['Transaction']['class']);?></p>
		<p><?php echo $this->Html->link($this->Html->cText($transaction['TransactionType']['name']), array('controller'=> 'transaction_types', 'action' => 'view', $transaction['TransactionType']['id']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cCurrency($transaction['Transaction']['amount']);?></p>
		<p><?php echo $this->Html->cText($transaction['Transaction']['description']);?></p>
		<p><?php echo $this->Html->link($this->Html->cText($transaction['PaymentGateway']['name']), array('controller'=> 'payment_gateways', 'action' => 'view', $transaction['PaymentGateway']['id']), array('escape' => false));?></p>
		<p><?php echo $this->Html->cFloat($transaction['Transaction']['gateway_fees']);?></p>
		<div class="actions"><?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $transaction['Transaction']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $transaction['Transaction']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></div>
	</li>
<?php
    endforeach;
else:
?>
	<li>
		<p class="notice"><?php echo __l('No Transactions available');?></p>
	</li>
<?php
endif;
?>
</ol>

<?php
if (!empty($transactions)) {
    echo $this->element('paging_links');
}
?>
</div>
