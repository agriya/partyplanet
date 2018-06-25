<?php /* SVN: $Id: admin_index.ctp 17735 2012-08-17 11:44:21Z beautlin_108ac10 $ */ ?>
	<?php 
		if(!empty($this->request->params['isAjax'])):
			echo $this->element('flash_message');
		endif;
	?>
<div class="userLogins index js-response js-responses">
    <div class="clearfix">
         <div class="grid_left">
            <?php echo $this->element('paging_counter');?>
         </div>
          <div class="grid_left">
             <?php echo $this->Form->create('UserLogin' , array('type' => 'get', 'class' => 'normal search-form clearfix','action' => 'index')); ?>
        	 <?php echo $this->Form->input('q', array('label' => __l('Keyword'))); ?>
        	 <?php echo $this->Form->submit(__l('Search'));?>
            <?php echo $this->Form->end(); ?>
    	 </div>
	</div>
    <?php echo $this->Form->create('UserLogin' , array('class' => 'normal js-ajax-form','action' => 'update')); ?>
    <?php echo $this->Form->input('r', array('type' => 'hidden', 'value' => $this->request->url)); ?>
   
    <table class="list">
        <tr>
            <th class="select"><?php echo __l('Select'); ?></th>
            <th class="actions"><?php echo __l('Actions'); ?></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Login Time'), 'UserLogin.created');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Username'), 'User.username');?></div></th>
            <th class="dc"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('Login IP'), 'Ip.ip');?></div></th>
            <th class="dl"><div class="js-pagination"><?php echo $this->Paginator->sort(__l('User Agent'), 'UserLogin.user_agent');?></div></th>
        </tr>
        <?php
        if (!empty($userLogins)):
            $i = 0;
            foreach ($userLogins as $userLogin):
                $class = null;
                if ($i++ % 2 == 0) :
                    $class = ' class="altrow"';
                endif;
                ?>
                <tr<?php echo $class;?>>
                    <td class="select">
    				
					   <?php echo $this->Form->input('UserLogin.'.$userLogin['UserLogin']['id'].'.id', array('type' => 'checkbox', 'id' => "admin_checkbox_".$userLogin['UserLogin']['id'], 'label' => false, 'class' => 'js-checkbox-list')); ?>
                    </td>
                    <td class="select">
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
                              	<li><?php echo $this->Html->link(__l('Delete'), array('action' => 'delete', $userLogin['UserLogin']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?></li>
    							<li> <?php echo $this->Html->link(__l('Ban Login IP'), array('controller'=> 'banned_ips', 'action' => 'add', $userLogin['UserLogin']['user_login_ip']), array('class' => 'network-ip','title'=>__l('Ban Login IP'), 'escape' => false));?></li>
       					      </ul>
        					</div>
        					<div class="action-bottom-block"></div>
        				  </div>
                     </div>
                    </td>
                    <td class="dc"><?php echo $this->Html->cDateTimeHighlight($userLogin['UserLogin']['created']);?></td>
                    <td class="dl">
					<?php 
						echo $this->Html->getUserAvatar($userLogin['User'], 'micro_thumb');
						//echo $this->Html->link($this->Html->showImage('UserAvatar', $userLogin['User']['UserAvatar'], array('dimension' => 'micro_thumb','title'=>$this->Html->cText($userLogin['User']['username'], false),'alt'=>sprintf('[Image: %s]', $this->Html->cText($userLogin['User']['username'], false)))), array('controller' => 'users', 'action' => 'view', $userLogin['User']['username']), array('escape' => false))
					?>
                    <?php echo $this->Html->link($this->Html->cText($userLogin['User']['username']), array('controller'=> 'users', 'action' => 'view', $userLogin['User']['username'], 'admin' => false), array('escape' => false));?></td>
					<td class="dl">
                         <?php if(!empty($userLogin['Ip']['ip'])): ?>
                            <?php echo  $this->Html->link($userLogin['Ip']['ip'], array('controller' => 'users', 'action' => 'whois', $userLogin['Ip']['ip'], 'admin' => false), array('target' => '_blank', 'title' => 'whois '.$userLogin['Ip']['host'], 'escape' => false));
							?>
							<p>
							<?php
                            if(!empty($userLogin['Ip']['Country'])):
                                ?>
                                <span class="flags flag-<?php echo strtolower($userLogin['Ip']['Country']['iso_alpha2']); ?>" title ="<?php echo $userLogin['Ip']['Country']['name']; ?>">
									<?php echo $userLogin['Ip']['Country']['name']; ?>
								</span>
                                <?php
                            endif;
							 if(!empty($userLogin['Ip']['City'])):
                            ?>
                            <span> 	<?php echo $userLogin['Ip']['City']['name']; ?>    </span>
                            <?php endif; ?>
                            </p>
                        <?php else: ?>
							<?php echo __l('N/A'); ?>
						<?php endif; ?>
						</td>
                    <td class="dl"><?php echo $this->Html->cText($userLogin['UserLogin']['user_agent']);?></td>
                </tr>
                <?php
            endforeach;
        else:
            ?>
            <tr>
                <td colspan="6" class="notice"><?php echo __l('No User Logins available');?></td>
            </tr>
            <?php
        endif;
        ?>
    </table>

    <?php
    if (!empty($userLogins)) :
        ?>
        <div class="admin-select-block">
        <div>
            <?php echo __l('Select:'); ?>
            <?php echo $this->Html->link(__l('All'), '#', array('class' => 'js-admin-select-all','title' => __l('All'))); ?>
            <?php echo $this->Html->link(__l('None'), '#', array('class' => 'js-admin-select-none','title' => __l('None'))); ?>
        </div>
         <div class="admin-checkbox-button">
            <?php echo $this->Form->input('more_action_id', array('class' => 'js-admin-index-autosubmit', 'label' => false, 'empty' => __l('-- More actions --'))); ?>
        </div>
        </div>
         <div class="js-pagination">
            <?php echo $this->element('paging_links'); ?>
        </div>
        <div class = "hide">
            <?php echo $this->Form->submit('Submit');  ?>
        </div>
        <?php
    endif;
    echo $this->Form->end();
    ?>
</div>