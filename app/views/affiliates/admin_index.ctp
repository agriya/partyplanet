<?php /* SVN: $Id: $ */ ?>
<div class="affiliates index">
    <div class="clearfix">
		<ul class="affiliates-links list grid_right clearfix">
			<?php $class = ($this->request->params['controller'] == 'affiliate_requests') ? ' class="active"' : null; ?>
				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliate  Requests'), array('controller' => 'affiliate_requests', 'action' => 'index'),array('class'=>'affiliate-requests', 'title' => __l('Affiliates  Requests'))); ?></li>
			<?php $class = ($this->request->params['controller'] == 'affiliate_cash_withdrawals') ? ' class="active"' : null; ?>
				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliate Cash Withdrawal Requests'), array('controller' => 'affiliate_cash_withdrawals', 'action' => 'index'),array('class' =>'affiliate-cash-withdrawals', 'title' => __l('Affiliate Cash Withdrawal Requests'))); ?></li>
				<li><?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 14),array('class' =>'settings-links','title' => __l('Settings'))); ?></li>
                
		</ul>
	</div>
    

<?php echo $this->element('admin_affiliate_stat'); ?>
<h2><?php echo __l('Commission History');?></h2>
<div class="record-info inbox-option">
<ul class="filter-list clearfix">
		<li>
        <span class="suspended round-5">
			<?php $class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateStatus::Pending) ? ' status_selected' : null; ?>
			<?php echo $this->Html->link(__l('Pending'), array('controller'=>'affiliates','action'=>'index','filter_id' => ConstAffiliateStatus::Pending), array('class' => $class, 'title' => __l('Pending')));?>
			 </span>
		</li>
		<li>
        <span class="canceled round-5">
			<?php $class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateStatus::Canceled) ? ' status_selected' : null; ?>
			<?php echo $this->Html->link(__l('Canceled'), array('controller'=>'affiliates','action'=>'index','filter_id' => ConstAffiliateStatus::Canceled), array('class' => $class, 'title' => __l('Canceled')));?>
  		   </span>
		</li>
		<li>
		    <span class="flagged round-5">
			<?php $class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateStatus::PipeLine) ? ' status_selected' : null; ?>
			<?php echo $this->Html->link(__l('Pipeline'), array('controller'=>'affiliates','action'=>'index','filter_id' => ConstAffiliateStatus::PipeLine), array('class' => $class, 'title' => __l('Pipeline')));?>
		    </span>
		</li>
		<li>
		    <span class="all round-5">
			<?php $class = (!empty($this->request->params['named']['filter_id']) && $this->request->params['named']['filter_id'] == ConstAffiliateStatus::Completed) ? ' status_selected' : null; ?>
			<?php echo $this->Html->link(__l('Completed'), array('controller'=>'affiliates','action'=>'index','filter_id' => ConstAffiliateStatus::Completed), array('class' => $class, 'title' => __l('Completed')));?>
		    </span>
		</li>
	</ul>
</div>        
<?php echo $this->element('paging_counter');?>
<table class="list">
    <tr>
        <th><?php echo $this->Paginator->sort(__l('Created'), 'created');?></th>
        <th><?php echo $this->Paginator->sort(__l('Affiliate User'), 'AffiliateUser.username');?></th>
        <th><?php echo __l('User');?></th>
        <th><?php echo $this->Paginator->sort(__l('Type'), 'AffiliateType.name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Status'), 'AffiliateStatus.name');?></th>
        <th><?php echo $this->Paginator->sort(__l('Commission'), 'commission_amount'). ' ('. Configure::read('site.currency').')';?></th>
    </tr>
<?php

if (!empty($affiliates)):

$i = 0;
foreach ($affiliates as $affiliate):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	}
?>
	<tr<?php echo $class;?>>
        <td> <?php echo $this->Html->cDateTimeHighlight($affiliate['Affiliate']['created']);?></td>
		<td><?php echo $this->Html->link($this->Html->cText($affiliate['AffiliateUser']['username']), array('controller'=> 'users', 'action'=>'view', $affiliate['AffiliateUser']['username'], 'admin' => false), array('escape' => false));?></td>
		<td> 
        	<?php 
				if($affiliate['Affiliate']['class'] == 'User'){
			?>	
					<?php echo $this->Html->link($this->Html->cText($affiliate['User']['username']), array('controller'=> 'users', 'action' => 'view', $affiliate['User']['username'], 'admin' => false), array('escape' => false));?>
			<?php
			   } else if($affiliate['Affiliate']['class'] == 'Event'){
			?>
			<?php echo $this->Html->link($this->Html->cText($affiliate['Event']['title'],false), array('controller'=> 'events', 'action' => 'view', $affiliate['Event']['slug'], 'admin' => false), array('escape' => false));?>
				<?php if (!empty($affiliate['Event']['User']['username'])): ?>
					(<?php echo $this->Html->link($this->Html->cText($affiliate['Event']['User']['username']), array('controller'=> 'users', 'action' => 'view', $affiliate['Event']['User']['username'], 'admin' => false), array('escape' => false));?>)
				<?php endif; ?>
			<?php
				
			}else {
			?>	
					<?php echo $this->Html->link($this->Html->cText($affiliate['Venue']['name']), array('controller'=> 'venues', 'action' => 'view', $affiliate['Venue']['slug'], 'admin' => false), array('escape' => false));?>
					(<?php echo $this->Html->link($this->Html->cText($affiliate['User']['username']), array('controller'=> 'users', 'action' => 'view', $affiliate['User']['username'], 'admin' => false), array('escape' => false));?>)
		<?php   } ?>
		</td>
        <td> <?php echo $this->Html->cText($affiliate['AffiliateType']['name']);?> </td>
		
		<td>
           <?php echo $this->Html->cText($affiliate['AffiliateStatus']['name']);   ?>
           <?php  if($affiliate['AffiliateStatus']['id'] == ConstAffiliateStatus::PipeLine): ?>
                   <?php echo '['.__l('Since').': '.$this->Html->cDateTimeHighlight($affiliate['Affiliate']['commission_holding_start_date']). ']';?>
           <?php endif; ?>
        </td>
		<td><?php echo $this->Html->cFloat($affiliate['Affiliate']['commission_amount']);?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="11" class="notice"><?php echo __l('No commission history available');?></td>
	</tr>
<?php
endif;
?>
</table>

<?php
if (!empty($affiliates)) {
    echo $this->element('paging_links');
}
?>
</div>
