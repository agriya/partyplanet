<?php /* SVN: $Id: admin_index.ctp 15310 2011-12-22 05:54:16Z jayashree_028ac09 $ */ ?>
<div class="transactions index">
<?php echo $this->element('paging_counter');?>
<div class="overflow-block">
<table class="list">
    <tr>
        <th class="actions"><?php echo __l('Actions');?></th>        
        <th><?php echo $this->Paginator->sort('created');?></th>
        <th><?php echo $this->Paginator->sort('description');?></th>
        <th><?php echo $this->Paginator->sort('amount') . ' (' . Configure::read('site.currency') . ')';?></th>        
        <th><?php echo $this->Paginator->sort('site_fee') . ' (' . Configure::read('site.currency') . ')';?></th>        
    </tr>
<?php
if (!empty($transactions)):

$i = 0;
foreach ($transactions as $transaction):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
	  <td class="actions">
	    <div class="action-block">
          <span class="action-information-block">
            <span class="action-left-block">&nbsp;&nbsp;</span>
              <span class="action-center-block">
                <span class="action-info">
                  <?php echo __l('Action');?>
                </span>
              </span>
            </span>
            <div class="action-inner-block">
              <div class="action-inner-left-block">
                <ul class="action-link clearfix">
				  <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $transaction['Transaction']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
				  </li>
				</ul>
             </div>
    		 <div class="action-bottom-block"></div>
    	   </div>
        </div>
      </td>	
		<td><?php echo $this->Html->cDateTime($transaction['Transaction']['created']);?></td>
        <td><?php echo $this->Html->transactionDescription($transaction);?></td>
		<td class="dr"><?php echo $this->Html->cCurrency($transaction['Transaction']['amount']);?></td>		
		<td class="dr site-amount"><?php echo $this->Html->cFloat($transaction['Transaction']['site_fee']);?></td>
	</tr>
<?php
    endforeach;
?>    
        <tr class="total-block">
			<td colspan="3" class="dr"><b><?php echo __l('Total');?></b></td>
			<td class="dr"><b><?php echo $this->Html->cFloat($total_amount['0']['total_amount']);?></b></td>
			<td class="dr site-amount"><b><?php echo $this->Html->cFloat($site_fee_total['0']['site_fee_total']);?></b></td>
		</tr>    
<?php        
else:
?>
	<tr>
		<td colspan="12"><p class="notice"><?php echo __l('No Transactions available');?></p></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($transactions)) {
    echo $this->element('paging_links');
}
?>
</div>
</div>
