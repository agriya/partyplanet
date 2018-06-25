<? if(empty($this->request->params['isAjax'])){
if(empty($this->request->params['named']['type']) and (!empty($this->request->params['prefix'])and $this->request->params['prefix'] != 'admin')){?>
<div id="breadcrumb">
		<?php echo $this->Html->addCrumb(__l('Login')); ?>
		<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
 </div>
<?php } }
?>
<?php if(empty($this->request->params['named']['type']) and (!empty($this->request->params['prefix'])and $this->request->params['prefix'] != 'admin')) { ?>
	<div class="openid-block grid_right clearfix">
        <h5 class="grid_left"><?php echo __l('Sign in using:'); ?></h5>
		<ul class="open-id-list grid_left clearfix">
			<li class="face-book">
				 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
					<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook', 'admin'=>false), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
				 <?php endif; ?>
			</li>
			<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
				<li class="twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_openid')):?>
				<li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type' => 'yahoo', 'admin' => false), array('title' => __l('Sign in with Yahoo')));?></li>
				<li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type' => 'gmail', 'admin' => false), array('title' => __l('Sign in with Gmail')));?></li>
				<li class="open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid', 'admin'=>false), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with Open ID')));?></li>
			<?php endif;?>
		</ul>
	</div>
<?php } ?>
	<h2><?php echo __l('Login'); ?></h2>
 <div class="users form">
	<?php if(!empty($this->request->params['named']['type'])) { ?>
		<div class="regular-login form-content-block clearfix">
		<div class="regular-content">
			<h3><?php echo __l('Regular Member Login'); ?></h3>
			<p class="already-info"><?php echo Configure::read('site.name') . ' ' . __l('made it very easy for users to login'); ?></p>
			</div>
			<div class="openid-block grid_right clearfix">
				<h5 class="grid_left"><?php echo __l('Sign in using:'); ?></h5>
				<ul class="open-id-list grid_left clearfix">
					<li class="face-book">
						 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
							<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook', 'admin'=>false), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
						 <?php endif; ?>
					</li>
					<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
						<li class="twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
					<?php endif;?>
					<?php if(Configure::read('user.is_enable_openid')):?>
						<li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type' => 'yahoo', 'admin' => false), array('title' => __l('Sign in with Yahoo')));?></li>
						<li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type' => 'gmail', 'admin' => false), array('title' => __l('Sign in with Gmail')));?></li>
						<li class="open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid', 'admin'=>false), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with Open ID')));?></li>
					<?php endif;?>
				</ul>
			</div>
		</div>
	<?php } ?>
	<div class="form-content-block">
	<?php if(!empty($this->request->params['named']['type'])) { ?>
		<h3><?php echo __l('Regular Member (or) Venue Owner Login'); ?></h3>
	<?php } ?>
	<?php
	echo $this->Form->create('User', array(
		'action' => 'login',
		'class' => 'normal clearfix'
	));
		echo $this->Form->input(Configure::read('user.using_to_login'));
		echo $this->Form->input('passwd', array('label' => __l('Password'))); ?>


			<div class="connect-block-wrapper">
					<?php   if(!empty($this->request->params['named']['type'])) { ?>
				<div class="connect-block form-content-block">
				<div class="regular-content">
						<h3><?php echo __l('Regular Member'); ?></h3>
						<p class="already-info"><?php echo __l('Becoming a member of the') . ' ' . Configure::read('site.name') . ' ' . __l('community is very easy.'); ?></p>
						<div class="signup-button grid_left">
							<?php echo $this->Html->link(__l('Sign-up to be a regular member'), array('controller' => 'users', 'action' => 'register', 'admin'=>false), array('title' => __l('Sign-up to be a regular member'), 'escape' => false)); ?>
						</div>
						        <?php if (!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin') && empty($this->request->params['isAjax']) && Configure::read('facebook.is_enabled_facebook_connect') && empty($this->request->data['User']['is_requested'])): ?>
						<div class="openid-block grid_right clearfix">
							<h5 class="grid_left"><?php echo __l('Sign up using:'); ?></h5>
							<ul class="open-id-list grid_left clearfix">
								<li class="face-book">
									 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
										<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook', 'admin'=>false), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
									 <?php endif; ?>
								</li>
								<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
									<li class="twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
								<?php endif;?>
								<?php if(Configure::read('user.is_enable_openid')):?>
									<li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type' => 'yahoo', 'admin' => false), array('title' => __l('Sign in with Yahoo')));?></li>
									<li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type' => 'gmail', 'admin' => false), array('title' => __l('Sign in with Gmail')));?></li>
									<li class="open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid', 'admin'=>false), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with Open ID')));?></li>
								<?php endif;?>
							</ul>
						</div>
						<?php endif;?>
				</div>
			</div>					
		<?php } ?>
		</div>
		
                
<?php
		if(empty($this->request->params['named']['type'])) {
			echo $this->Form->input('User.is_remember', array(
				'type' => 'checkbox',
				'label' => __l('Remember me on this computer.')
			));
		}
	?>
	<div class="forgot-password">
	<?php
		echo $this->Html->link(__l('Forgot your password?') , array(
				'controller' => 'users',
				'action' => 'forgot_password',
				'admin' => false
			));
		if(empty($this->request->params['named']['type'])) {
	?> | 
	<?php 
		echo $this->Html->link(__l('Signup') , array(
				'controller' => 'users',
				'action' => 'joinus',
				'admin' => false
			)); 
		}
	?>
	</div>
	<?php
	$f = (!empty($_GET['f'])) ? $_GET['f'] : ((!empty($this->request->data['User']['f'])) ? $this->request->data['User']['f'] : (($this->request->params['controller'] != 'users' && ($this->request->params['action'] != 'login' && $this->request->params['action'] != 'admin_login')) ? $this->request->url : ''));
				if (!empty($f)):
					echo $this->Form->input('f', array('type' => 'hidden', 'value' => $f));
        endif;
		?>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Login'));?>
        </div>
       
    
      <?php echo $this->Form->end(); ?>
</div>
</div>
