<?php /* SVN: $Id: $ */ ?>
	<?php
     if (!empty($user)): ?>
            <div class="clearfix">
			<div class="user-image-block grid_left omega alpha">
				<?php
					 echo $this->Html->getUserAvatar($user['User'], 'user_info_thumb');
				?>
			</div>
            <div class="grid_5 omega alpha">
 			<dl class="list user-list clearfix">
				<?php if (!empty($user['User']['username'])): ?>
					<dt><?php echo __l('Name:').' '; ?></dt>
                    <dd>
					<?php echo $this->Html->link($this->Html->cText($user['User']['username'], false), array('controller' => 'users', 'action' => 'view', $user['User']['username']), array('escape' => false)); ?>
                    </dd>
				<?php endif; ?>
				<?php if (!empty($user['UserProfile']['dob'])): ?>
					<dt><?php echo __l('Age:').' '; ?></dt>
                    <dd>
                        <?php echo $this->Html->userAge($user['UserProfile']['dob']); ?>
					</dd>
				<?php endif; ?>
				<?php if (!empty($user['UserProfile']['is_show_month_date'])): ?>
					<dt><?php echo __l('Date of Birth:').' '; ?></dt>
					<dd>
					<?php echo $this->Html->userDob($user['UserProfile']['dob'], $user['UserProfile']['is_show_month_date']); ?>
                    </dd>
				<?php endif; ?>
				<?php if (!empty($user['UserProfile']['City']['name'])): ?>
		            <dt><?php echo __l('City:').' '; ?></dt>
		            <dd>
		    		<?php echo $this->Html->cText($user['UserProfile']['City']['name']); ?>
		    		</dd>
                 <?php endif; ?>
    				    <dt><?php echo __l('Joined:').' '; ?></dt>
                        <dd>
                        <?php echo $this->Html->userDob($user['User']['created'],'0'); ?>
                        </dd>
    				<?php if (!empty($user['UserProfile']['about_me'])): ?>
					        <dt><?php echo __l('About me:').' '; ?></dt>
                            <dd>
							<?php echo $this->Html->cText($user['UserProfile']['about_me']); ?>
                            </dd>
				<?php endif; ?>
						
					</dl>
				<?php
				if(!empty($user['User']['username']) && $user['User']['username']== $this->Auth->user('username')):?>
                 <div class="clearfix">
                    <ul class="userprofile-link">
          				<li><?php echo $this->Html->link(__l('Change Photo'), array('controller' => 'user_profiles', 'action' => 'edit',$user['User']['id'],'basic'), array('title'=>__l('Change Photo'),'escape' => false));?></li>
    					<li><?php echo $this->Html->link(__l('Edit Profile'), array('controller' => 'user_profiles', 'action' => 'edit',$user['User']['id'],'basic'), array('title'=>__l('Edit Profile'),'escape' => false));?></li>
    				</ul>
				</div>
                <?php endif; ?>
               </div>
               </div>
              
		
	
	<?php endif; ?>
