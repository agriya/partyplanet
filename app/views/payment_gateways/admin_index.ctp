<?php /* SVN: $Id: admin_index.ctp 15606 2012-01-06 08:40:39Z rajeshkhanna_146ac10 $ */ ?>
   <div class="index_inner">
<div><?php echo $this->element('paging_counter');?></div>

<table class="list">
    <tr>
        <th><?php echo __l('Action');?></th>
        <th><?php echo $this->Paginator->sort(__l('display_name'));?></th>
        <th><?php echo $this->Paginator->sort(__l('description'));?></th>
        <th><?php echo $this->Paginator->sort(__l('Test Mode'), 'is_test_mode');?></th>
    </tr>
<?php
if (!empty($paymentGateways)):

$i = 0;
foreach ($paymentGateways as $paymentGateway):
	$class = null;
	$status_class = null;
	if ($i++ % 2 == 0) :
		$class = ' class="altrow"';
	endif;
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
                            <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $paymentGateway['PaymentGateway']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
   					   </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
          </div>
          
		</td>
		<td>
			<?php echo $this->Html->cText($paymentGateway['PaymentGateway']['display_name']);?>
		</td>
		<td><?php echo $this->Html->cText($paymentGateway['PaymentGateway']['description']);?></td>
		<td><?php echo $this->Html->cBool($paymentGateway['PaymentGateway']['is_test_mode']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="9"><p class="notice"><?php echo __l('No Payment Gateways available');?></p></td>
	</tr>
<?php
endif;
?>
</table>

<?php if (!empty($paymentGateways)): ?>
	<div><?php echo $this->element('paging_links'); ?></div>
<?php endif; ?>

</div>