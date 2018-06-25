<?php /* SVN: $Id: admin_index.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="transactionTypes index">
<h2><?php echo __l('Transaction Types');?></h2>
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th><?php echo $this->Paginator->sort('id');?></th>
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('modified');?></th>
        <th><?php echo $this->Paginator->sort('name');?></th>
        <th><?php echo $this->Paginator->sort('message');?></th>
        <th><?php echo $this->Paginator->sort('transaction_variables');?></th>
    </tr>
<?php
if (!empty($transactionTypes)):

$i = 0;
foreach ($transactionTypes as $transactionType):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
		<td class="actions"><span><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $transactionType['TransactionType']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></span> <span><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $transactionType['TransactionType']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></span></td>
		<td><?php echo $this->Html->cInt($transactionType['TransactionType']['id']);?></td>
		<td><?php echo $this->Html->cDateTime($transactionType['TransactionType']['created']);?></td>
		<td><?php echo $this->Html->cDateTime($transactionType['TransactionType']['modified']);?></td>
		<td><?php echo $this->Html->cText($transactionType['TransactionType']['name']);?></td>
		<td><?php echo $this->Html->cText($transactionType['TransactionType']['message']);?></td>
		<td><?php echo $this->Html->cText($transactionType['TransactionType']['transaction_variables']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="7"><p class="notice"><?php echo __l('No Transaction Types available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($transactionTypes)) {
    echo $this->element('paging_links');
}
?>
</div>
