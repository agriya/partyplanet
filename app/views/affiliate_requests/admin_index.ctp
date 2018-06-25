<?php /* SVN: $Id: $ */ ?>
<div class="affiliateRequests index">
    <div class="clearfix">
        <div class="grid_left">
          <?php echo $this->element('paging_counter');?>
        </div>
        <div class="grid_left">
            <?php echo $this->Form->create('AffiliateRequest', array('type' => 'get', 'class' => 'normal search-form', 'action'=>'index')); ?>
              <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
              <?php echo $this->Form->submit(__l('Search'));?>
        	<?php echo $this->Form->end(); ?>
        </div>
        <div class="add-block grid_right">
        	<?php echo $this->Html->link(__l('Add'), array('controller' => 'affiliate_requests', 'action' => 'add'), array('class' => 'add', 'title'=>__l('Add'))); ?>
        </div>
    </div>
<?php echo $this->Form->create('AffiliateRequest' , array('class' => 'normal','action' => 'update')); ?>
<?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
 <div class="overflow-block">
<table class="list">
    <tr>
        <th><?php echo __l('Select');?></th>
        <th class="actions"><?php echo __l('Actions');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('User'), 'User.username');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Site'), 'site_name');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Site URL'), 'site_url');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Site Category'), 'site_category_id');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Why Do You Want Affiliate'), 'why_do_you_want_affiliate');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Website Marketing?'), 'is_web_site_marketing');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Search Engine Marketing?'),'is_search_engine_marketing');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Email Marketing'),'is_email_marketing');?></th>
        <th class="dl"><?php echo $this->Paginator->sort(__l('Promotional Method'),'special_promotional_method');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Approved?'),'is_approved');?></th>
    </tr>
<?php
if (!empty($affiliateRequests)):

$i = 0;
foreach ($affiliateRequests as $affiliateRequest):
	$class = null;
	if ($i++ % 2 == 0) {
		$class = ' class="altrow"';
	if($affiliateRequest['AffiliateRequest']['is_approved']==1):
		$status_class = 'js-checkbox-active';
	elseif($affiliateRequest['AffiliateRequest']['is_approved']==2):
	       $status_class = 'js-checkbox-inactive';
    elseif($affiliateRequest['AffiliateRequest']['is_approved']==0):
		$status_class = 'js-checkbox-pending';
	endif;
	
	
	}
?>
	<tr<?php echo $class;?>>
         <td><?php echo $this->Form->input('AffiliateRequest.'.$affiliateRequest['AffiliateRequest']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$affiliateRequest['AffiliateRequest']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                         <li><?php echo $this->Html->link(__l('Edit'), array('action' => 'edit', $affiliateRequest['AffiliateRequest']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                         <li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $affiliateRequest['AffiliateRequest']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    			     </ul>
				</div>
				<div class="action-bottom-block"></div>
			  </div>
        </div>
       </td>
		<td class="dl"><?php echo $this->Html->link($this->Html->cText($affiliateRequest['User']['username']), array('controller'=> 'users', 'action'=>'view', $affiliateRequest['User']['username'], 'admin' => false), array('escape' => false));?></td>
		<td class="dl"><?php echo $this->Html->cText($affiliateRequest['AffiliateRequest']['site_name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($affiliateRequest['AffiliateRequest']['site_url']);?></td>
		<td class="dl"><?php echo $this->Html->cText($affiliateRequest['SiteCategory']['name']);?></td>
		<td class="dl"><?php echo $this->Html->cText($affiliateRequest['AffiliateRequest']['why_do_you_want_affiliate']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_web_site_marketing']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_search_engine_marketing']);?></td>
		<td class="dc"><?php echo $this->Html->cBool($affiliateRequest['AffiliateRequest']['is_email_marketing']);?></td>
        <td class="dl"><?php echo $this->Html->cText($affiliateRequest['AffiliateRequest']['special_promotional_method']);?></td>
		<td class="dc"><?php if($affiliateRequest['AffiliateRequest']['is_approved'] ==0){
					echo __l('Waiting for Approval');
				  } else if($affiliateRequest['AffiliateRequest']['is_approved'] ==1){
				  	echo __l('Approved');
				  } else if($affiliateRequest['AffiliateRequest']['is_approved'] ==2){
				  	echo __l('Rejected');
				  }
		?></td>
	</tr>
<?php
    endforeach;
else:
?>
	<tr>
		<td colspan="16" class="notice"><?php echo __l('No Affiliate Requests available');?></td>
	</tr>
<?php
endif;
?>
</table>
</div>
<?php
if (!empty($affiliateRequests)) :
        ?>
        <div class="admin-select-block">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
            <?php echo $this->Html->link(__l('Disapprove'), '#', array('class' => 'js-admin-select-inactive', 'title' => __l('Disapprove'))); ?>
		    <?php echo $this->Html->link(__l('Approve'), '#', array('class' => 'js-admin-select-active', 'title' => __l('Approve'))); ?>
		    <?php echo $this->Html->link(__l('Pending'), '#', array('class' => 'js-admin-select-request-pending', 'title' => __l('Pending'))); ?>
        </div>
        <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        </div>
            <?php echo $this->element('paging_links'); ?>
        <div class="hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>















