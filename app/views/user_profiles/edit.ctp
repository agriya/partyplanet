<?php if ($this->Auth->user('user_type_id') != ConstUserTypes::Admin) { ?>
    <h2><?php echo __l('Update Profile - ').$this->request->data['User']['username']; ?></h2>
    <ul class="menu-tabs clearfix">
        <li><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
    	<li><?php echo $this->Html->link(__l('Inbox'), array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
    	<li class="active"><?php echo $this->Html->link(__l('Edit profile'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'admin' => false), array('title' => __l('Edit profile'), 'escape' => false)); ?></li>
    	<li><?php echo $this->Html->link(__l('My Friends'), array('controller' => 'user_friends', 'action' => 'lst', 'admin' => false), array('title' => 'My Friends'));?></li>
        <li><?php echo $this->Html->link(__l('Invite Friends'), array('controller' => 'user_friends', 'action' => 'import', 'admin' => false), array('title' => __l('Invite Friends'), 'escape' => false));?></li>
    </ul>
<?php } ?>

	<?php
		echo $this->element('user_account');
	?>
	<div class="userProfiles form form-content-block ">
	<div id="breadcrumb">
		<?php
			if($this->Auth->user('user_type_id') != ConstUserTypes::Admin):
			 echo $this->Html->addCrumb(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false));
			 echo $this->Html->addCrumb(__l('Edit Profile'));
			 if($type=='basic'):
			 echo $this->Html->addCrumb(__l('Basic Info'));
			 elseif($type=='general'):
			 echo $this->Html->addCrumb(__l('Contact Info'));
			 elseif($type=='personal'):
			 echo $this->Html->addCrumb(__l('Personal Info'));
			 elseif($type=='photo'):
			 echo $this->Html->addCrumb(__l('Photos'));
			 endif;
				echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
				
			endif;
		?>
	</div>
		<?php echo $this->Form->create('UserProfile', array('action' => 'edit', 'class' => 'normal edit-profile', 'enctype' => 'multipart/form-data'));?>
			<fieldset>
	<?php
				echo $this->Form->input('User.id');
				echo $this->Form->input('type',array('type'=>'hidden','value'=>$type));
				echo $this->Form->input('User.username',array('type'=>'hidden'));
				if($type=='basic'):
                 echo $this->Form->input('first_name');
					echo $this->Form->input('last_name');
					echo $this->Form->input('gender_id', array('empty' => __l('Please Select')));
	?>				
					<div class="clearfix input">
					  <div class="js-datetime">
					    <?php echo $this->Form->input('dob', array('type' => 'date', 'orderYear' => 'asc','dateFormat' => 'DMY H:m', 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, 'div' => false, 'empty' => __l('Please Select')));
						?>
					  </div>
					</div>

	<?php
                  	echo $this->Form->input('is_show_month_date',array('label'=>__l('show only Month and Day')));
					if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
						echo $this->Form->input('User.is_active', array('label' => __l('Active')));
						echo $this->Form->input('User.previous_status',array('type'=>'hidden','value'=>$this->request->data['User']['is_active']));
						endif;
	?>
					<div class="user-img-block clearfix">
						<div class="profile-image">
				<?php $this->request->data['User']['UserAvatar'] = !empty($this->request->data['User']['UserAvatar']) ? $this->request->data['User']['UserAvatar'] : array(); ?>
                <?php
					$current_user_details = array(
						'username' => $this->request->data['User']['username'],
						'user_type_id' => $this->request->data['User']['user_type_id'],
						'id' => $this->request->data['User']['id'],
						'fb_user_id' => $this->request->data['User']['fb_user_id']
					);
					$current_user_details['UserAvatar'] = array(
						'id' => $this->request->data['UserAvatar']['id']
					);
					echo $this->Html->getUserAvatar($current_user_details, 'big_thumb');
				?>
    		</div>
					</div>
	<?php
					echo $this->Form->input('UserAvatar.filename', array('type' => 'file','size' => '33', 'label' => 'Upload Photo','class' =>'browse-field'));

				elseif($type=='general'):
					echo $this->Form->input('address');			
					echo $this->Form->input('address2');
					echo $this->Form->input('zip_code',array('label' => __l('ZIP code')));
					echo $this->Form->input('phone');
					echo $this->Form->input('mobile');
					echo $this->Form->input('cell_provider_id',array('type'=>'select','options'=>$cellproviders,'empty'=>'Please Select'));
				elseif($type=='personal'):
			     	echo $this->Form->input('body_type_id', array('options'=>$bodytypes,'empty' => __l('Please Select a Body Type')));
					echo $this->Form->input('marital_status_id',array('type'=>'select','options'=>$maritalstatus,'empty'=>'Please Select'));
					echo $this->Form->input('daily_quote');
					echo $this->Form->input('about_me');
					echo $this->Form->input('favorite_fashion_brand_id', array('options'=>$favoritefashionbrands,'empty' => __l('Please Select a Brand Type')));
					echo $this->Form->input('favorite_drinks');
					echo $this->Form->input('favorite_pickup_line');
					echo $this->Form->input('ethnicity_id', array('options'=>$ethnicity,'empty' => __l('-Unspecified-')));
					echo $this->Form->input('sexual_orientation_id', array('options'=>$sexualorientations,'empty' => __l('-Unspecified-')));
					
	?>
					<div class="music-types clearfix">
	<?php 
						echo $this->Form->input('music_type_id',array('multiple'=>'checkbox','options'=>$musictypes, 'label'=>'Music types')); 
	?> 
					</div>
	<?php 
				elseif($type=='photo'): ?>
                <div class="add-block1">
            	<?php
					 echo $this->Html->link(__l('Upload Photos'), array('controller' => 'photo_albums', 'action' => 'add', 'admin' => false), array('title' => __l('Upload Photos'), 'class' => 'photo-upload', 'escape' => false));
                ?>
                </div>
                <?php
					 echo $this->element('photo_albums-index', array('username' => $this->request->data['User']['username'],'cache' => array('key' => $this->request->data['User']['id'], 'config' => 'sec'))); 
				endif;
	?>
			</fieldset>
			<?php if(Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event')){?>
            <fieldset class="form-block">
				<h3>
				   <?php echo __l('PayPal Account'); ?>
				</h3>
				<?php $image_url = Router::url('/', true) . 'img/paypal-profile-currencies.png';?>
				<div class="page-info"><?php echo __l('Only verified PayPal account can receive funds. So, entered details will be validated with PayPal.
Also, your PayPal account must set to accept the site currency, which is ') . Configure::read('paypal.currency_code') . __l(' . Please follow the ') . $this->Html->link(__l('instruction to accept ') . Configure::read('site.currency_code'), $image_url, array('target' => '_blank'))  . __l(' , if not done already.');?></div>
				<?php
					echo $this->Form->input('paypal_account', array('label' => __l('PayPal Email'), 'info'=> __l('Verified PayPal Email')));
					echo $this->Form->input('paypal_first_name', array('label' => __l('PayPal First Name'), 'info'=> __l('As given in PayPal')));
					echo $this->Form->input('paypal_last_name', array('label' => __l('PayPal Last Name'), 'info'=> __l('As given in PayPal')));
				?>
			</fieldset>
			<?php } ?>
	<?php  if($type != 'photo'): ?>
			<div class="submit-block clearfix">
	<?php 
				echo $this->Form->submit(__l('Update'));
    	?>
			
		</div>
<?php endif; ?>			
<?php echo $this->Form->end();  ?>
	
	</div>