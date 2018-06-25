<?php /* SVN: $Id: index_list.ctp 99 2008-07-09 09:33:42Z rajesh_04ag02 $ */ ?>
<div class="users index js-response">
<?php if (empty($requested)): ?>
	<div>
		<ul class="filter-list  active-links-block clearfix">
			<li><span class="active round-5"><?php echo $this->Html->link(__l('Active') . ': ' . $this->Html->cInt($active, false), array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Active), array('title' => __l('Active') . ': ' . $this->Html->cInt($active, false))); ?></span></li>
			<li><span class="inactive round-5"><?php echo $this->Html->link(__l('Inactive') . ': ' . $this->Html->cInt($inactive, false), array('controller' => 'users', 'action' => 'index', 'filter_id' => ConstMoreAction::Inactive), array('title' => __l('Inactive') . ': ' . $this->Html->cInt($inactive, false)));?></span></li>
			<li><span class="normal round-5"><?php echo $this->Html->link(__l('Users') . ': ' . $this->Html->cInt($normal_users, false), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstUserTypes::User), array('title' => __l('Users') . ': ' . $this->Html->cInt($normal_users, false)));?></span></li>
			<li><span class="venueowner round-5"><?php echo $this->Html->link(__l('Venue Owners') . ': ' . $this->Html->cInt($venue_owner_users, false), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstUserTypes::VenueOwner), array('title' => __l('Venue Owners') . ': ' . $this->Html->cInt($venue_owner_users, false)));?></span></li>
			<li><span class="admin round-5"><?php echo $this->Html->link(__l('Admin') . ': ' . $this->Html->cInt($admin_users, false), array('controller' => 'users', 'action' => 'index', 'main_filter_id' => ConstUserTypes::Admin), array('title' => __l('Admin') . ': ' . $this->Html->cInt($admin_users, false)));?></span></li>
			<li><span class="all round-5"><?php echo $this->Html->link(__l('All') . ': ' . $this->Html->cInt($active + $inactive, false), array('controller' => 'users', 'action' => 'index'), array('title' => __l('All') . ': ' . $this->Html->cInt($active + $inactive, false)));?></span></li>
		</ul>
	</div>
	<div class="clearfix">
         <div class="grid_left">
            <?php echo $this->element('paging_counter'); ?>
        </div>
		<div class="grid_left">
             <?php
        		echo $this->Form->create('User', array('class' => 'normal search-form  js-ajax-form', 'action'=>'index', 'type' => 'post'));
        	?>
		    <?php echo $this->Form->input('user_type_id', array('type'=>'hidden')); ?>
        	<?php echo $this->Form->input('filter',array('type'=>'select', 'empty' => __l('Please Select'), 'options'=> $isFilterOptions)); ?>
			<?php echo $this->Form->input('keyword'); ?>
			<?php echo $this->Form->submit(__l('Search'));?>
    		<?php echo $this->Form->end(); ?>
		</div>
		  <div class="grid_right">
      	     <?php echo $this->Html->link(__l('Add'), array('controller' => 'users', 'action' => 'add'), array('class' => 'add', 'title' => __l('Add'))); ?>
      	     <?php echo $this->Html->link(__l('CSV'), array_merge(array('controller' => 'users', 'action' => 'index', 'ext' => 'csv', 'city' => $_prefixSlug, 'admin' => true), $this->request->params['named']), array('title' => __l('CSV'), 'class' => 'export')); ?>
          </div>
		</div>
         <?php
		endif;
		if (!empty($users)):
			echo $this->Form->create('User' , array('class' => 'normal','action' => 'update'));
			echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url));
		endif;
	?>
	<table class="list">
		<tr class="js-pagination">
			<th class="select" rowspan="2"><?php echo __l('Select'); ?></th>
			<th class="actions" rowspan="2"><?php echo __l('Actions'); ?></th>
          	<th rowspan="2" class="dl"><?php echo $this->Paginator->sort(__l('User'),'username'); ?></th>
			<th colspan="<?php echo Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event') ? 8 : 5; ?>" class="dc"><?php echo __l('Events'); ?></th>
     		<th colspan="3" class="dc"><?php echo __l('Venues'); ?></th>                        
     		<th colspan="3" class="dc"><?php echo __l('Logins'); ?></th>
            <th rowspan="2" class="dc"><?php echo $this->Paginator->sort(__l('Register On'),'created');?></th>
  		</tr>
		<tr>
 	    <th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'event_count'); ?></th>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
 	    <th class="dc"><?php echo $this->Paginator->sort(__l('Revenue (' . Configure::read('site.currency') . ')'),'revenue'); ?></th>
 	    <th class="dc"><?php echo $this->Paginator->sort(__l('Site Revenue (' . Configure::read('site.currency') . ')'), 'site_revenue'); ?></th>
		<?php } ?>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Joined Events'), 'event_user_count', array('url'=>array('controller'=>'users', 'action'=>'index'))); ?></th>
		<?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Spend (' . Configure::read('site.currency') . ')'),'spend_amount'); ?></th>
		<?php } ?>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Reviews'), 'event_comment_count', array('url'=>array('controller'=>'users', 'action'=>'index'))); ?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Photo Albums'),'photo_album_count');?></th>
        <th class="dc"><?php echo $this->Paginator->sort(__l('Videos'),'video_count');?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Created'),'venue_count');?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Regular'), 'venue_user_count', array('url'=>array('controller'=>'users', 'action'=>'index'))); ?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Reviews'), 'venue_comment_count', array('url'=>array('controller'=>'users', 'action'=>'index'))); ?></th>        		
        <th class="dc"><?php echo $this->Paginator->sort(__l('Count'),'user_login_count');?></th>
		<th class="dc"><?php echo $this->Paginator->sort(__l('Time'), 'last_logged_in_time', array('url'=>array('controller'=>'users', 'action'=>'index'))); ?></th>
        <th class="dl"><?php echo __l('IP'); ?></th>
		</tr>
		<?php
	if (!empty($users)):
	?>
	<?php $i = 0;
	foreach ($users as $user):
    $class = null;
		$active_class = '';
		if ($i++ % 2 == 0):
			$class = 'altrow';
		endif;
		$email_active_class = ' email-not-comfirmed';
    	if($user['User']['is_email_confirmed']):
    	$email_active_class = ' email-comfirmed';
    	endif;
		if($user['User']['is_active']):
		$status_class = 'js-checkbox-active';
	    else:
		$active_class = ' inactive-record';
		$status_class = 'js-checkbox-inactive';
     	endif;
	?>
		<tr class="<?php echo $class.$active_class;?>">
		<td class="select"><?php echo $this->Form->input('User.'.$user['User']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$user['User']['id'], 'label' => false, 'class' => $status_class.' js-checkbox-list')); ?></td>
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
                             <?php if(Configure::read('user.is_email_verification_for_register') and ($user['User']['is_email_confirmed'] == 0)):?>
                			<li>	<?php 	echo $this->Html->link(__l('Resend Activation'), array('controller' => 'users', 'action'=>'resend_activation', $user['User']['id'], 'admin' => false), array('class' => 'activation', 'title' => __l('Resend Activation'))); ?></li>
                			<?php endif;?>
                			<li><?php echo $this->Html->link(__l('Edit'), array('controller' => 'user_profiles', 'action'=>'edit', $user['User']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?></li>
                            <?php if($user['User']['user_type_id'] != ConstUserTypes::Admin){ ?>
                            <li><?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $user['User']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
                            <?php } ?>
                           
    					 </ul>
    					</div>
    					<div class="action-bottom-block"></div>
    				  </div>
              </div>
			</td>
		<td class="dl">
            <div class="clearfix user-info-block">
                <p class="user-img-left grid_left">
                        <?php
                       	$chnage_user_info = $user['User'];
                       	$user['User']['full_name'] = (!empty($user['UserProfile']['first_name']) || !empty($user['UserProfile']['last_name'])) ? $user['UserProfile']['first_name'] . ' ' . $user['UserProfile']['last_name'] :  $user['User']['username'];
						echo $this->Html->getUserAvatar($chnage_user_info, 'micro_thumb');
						echo '  '.$this->Html->link($this->Html->cText($user['User']['username']), array('controller'=> 'users', 'action' => 'view', $user['User']['username'], 'admin' => false), array('escape' => false));?>
						      
                            </p>
                              <p class="user-img-right grid_right clearfix">

                        <?php if($user['User']['is_affiliate_user']):?>
								<span class="grid_right affiliate"> <?php echo __l('Affiliate'); ?> </span>
						<?php endif; ?>
						  <?php if($user['User']['user_type_id'] == ConstUserTypes::Admin):?>
								<span class="grid_right admin"> <?php echo __l('Admin'); ?> </span>
						<?php endif; ?>
						</p>
                        </div>
                        <div class="clearfix user-status-block user-info-block">
                        <?php
							if(!empty($user['UserProfile']['Country'])):
								?>
                                <span class="grid_left flags flag-<?php echo strtolower($user['UserProfile']['Country']['iso_alpha2']); ?>" title ="<?php echo $user['UserProfile']['Country']['name']; ?>">
									<?php echo $user['UserProfile']['Country']['name']; ?>
								</span>
                                <?php
	                        endif;
						?>
					    <?php if($user['User']['is_openid_register']):?>
								<span class="open-id" title="OpenID"> <?php echo __l('OpenID'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_gmail_register']):?>
								<span class="gmail" title="Gmail"> <?php echo __l('Gmail'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['is_yahoo_register']):?>
								<span class="yahoo" title="Yahoo"> <?php echo __l('Yahoo'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['fb_user_id']):?>
								<span class="facebook" title="Facebook"> <?php echo __l('Facebook'); ?> </span>
						<?php endif; ?>
                        <?php if($user['User']['twitter_user_id']):?>
								<span class="twitter" title="Twitter"> <?php echo __l('Twitter'); ?> </span>
						<?php endif;?>
                                  <?php if(!empty($user['User']['email'])):?>
                                  	<span class="email <?php echo $email_active_class; ?>" title="<?php echo $user['User']['email']; ?>">
								<?php
								if(strlen($user['User']['email'])>20) :
									echo '..' . substr($user['User']['email'], strlen($user['User']['email'])-15, strlen($user['User']['email']));
								else:
									echo $user['User']['email'];
								endif;
								?>
                                </span>
						<?php endif; ?>
			 </div>
          
           </td>
		   <td class="dr">
                <?php  
                    echo $this->Html->link($this->Html->cText($user['User']['event_count']), array('controller'=> 'events', 'action' => 'index', 'username' => $user['User']['username']), array('escape' => false));
                ?>
           </td>
		   <?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
		   <td class="dr"><?php echo $this->Html->cFloat($user['User']['revenue']); ?></td>
		   <td class="dr site-amount"><?php echo $this->Html->cFloat($user['User']['site_revenue']); ?></td>
		   <?php } ?>
		   <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['event_user_count']), array('controller'=> 'event_users', 'action' => 'index', 'user' => $user['User']['id']), array('escape' => false));?></td>
		   <?php  if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){ ?>
		   <td class="dr"><?php echo $this->Html->cFloat($user['User']['spend_amount']); ?></td>
		   <?php } ?>
		   <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['event_comment_count']), array('controller'=> 'event_comments', 'action' => 'index', 'user' => $user['User']['id']), array('escape' => false));?></td>
           <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['photo_album_count']), array('controller'=> 'photo_albums', 'action' => 'index', 'username' => $user['User']['username']), array('escape' => false));?></td>
           <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['video_count']), array('controller'=> 'videos', 'action' => 'index', 'user' => $user['User']['username']), array('escape' => false));?></td>
		   <td class="dr">
           <?php  
				echo $this->Html->link($this->Html->cText($user['User']['venue_count']), array('controller'=> 'venues', 'action' => 'index', 'username' => $user['User']['username']), array('escape' => false));
           ?>
           </td>
		   <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['venue_user_count']), array('controller'=> 'venue_users', 'action' => 'index', 'user' => $user['User']['id']), array('escape' => false));?></td>
		   <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['venue_comment_count']), array('controller'=> 'venue_comments', 'action' => 'index', 'user' => $user['User']['id']), array('escape' => false));?></td>           
           <td class="dr"><?php echo $this->Html->link($this->Html->cInt($user['User']['user_login_count']), array('controller'=> 'user_logins', 'action' => 'index', 'user' => $user['User']['id']), array('escape' => false));?></td>
           <td class="dc">
				<?php 
					if($user['User']['last_logged_in_time'] != "0000-00-00 00:00:00") {
						echo $this->Html->cDateTimeHighlight($user['User']['last_logged_in_time']);
					}
				?>
			</td>           
            <td class="dl">
                        <?php if(!empty($user['LastLoginIp']['ip'])): ?>
                            <?php echo  $this->Html->link($user['LastLoginIp']['ip'], array('controller' => 'users', 'action' => 'whois', $user['LastLoginIp']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$user['LastLoginIp']['host']));
							?>
							<p>
							<?php
                            if(!empty($user['LastLoginIp']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($user['LastLoginIp']['Country']['iso_alpha2']); ?>" title ="<?php echo $user['LastLoginIp']['Country']['name']; ?>">
									<?php echo $user['LastLoginIp']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($user['LastLoginIp']['City'])):
                            ?>
                            <span> 	<?php echo $user['LastLoginIp']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
		</td>
        <td class="dc"><?php echo $this->Html->cDateTimeHighlight($user['User']['created']);?></td>           
 		</tr>
	<?php
		endforeach;
	?>
	<?php
	else:
	?>
		<tr>
			<td colspan="12"><p class="notice"><?php echo __l('No users available');?></p></td>
		</tr>
	<?php
		endif;
	?>
	</table>
	<?php
	if (!empty($users)):
	?>
	    <div class="clearfix select-block-bot">
		<div class="admin-select-block grid_left">
            <div>
            <?php echo __l('Select:'); ?>
			<?php echo $this->Html->link(__l('All'), '#', array('class' => 'select js-admin-select-all', 'title' => __l('All'))); ?>
			<?php echo $this->Html->link(__l('None'), '#', array('class' => 'select js-admin-select-none', 'title' => __l('None'))); ?>
			<?php echo $this->Html->link(__l('Inactive'), '#', array('class' => 'select js-admin-select-pending', 'title' => __l('Inactive'))); ?>
			<?php echo $this->Html->link(__l('Active'), '#', array('class' => 'select js-admin-select-approved', 'title' => __l('Active'))); ?>
            </div>
        <div class="admin-checkbox-button"><?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?></div>
        </div>
		<div class="js-pagination grid_right">
	<?php
		echo $this->element('paging_links');
		?>
		</div>
		</div>
		<div class="hide">
		<?php echo $this->Form->submit(); ?>
		</div> <?php
	endif;
	?>
	<?php echo $this->Form->end(); ?>
	</div>
