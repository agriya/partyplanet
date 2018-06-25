<div class="users form js-login-response ajax-login-block">
    <div class="clearfix">
	 <div class="openid-block grid_right">
        <h5 class="grid_left"><?php echo __l('Sign in using:'); ?></h5>
		<ul class="open-id-list grid_left clearfix">
			<li class="face-book">
				 <?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
					<?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook'), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?>
				 <?php endif; ?>
			</li>
			<?php if(Configure::read('twitter.is_enabled_twitter_connect')):?>
				<li class="twiiter"><?php echo $this->Html->link(__l('Sign in with Twitter'), array('controller' => 'users', 'action' => 'login',  'type'=> 'twitter', 'admin'=>false), array('class' => 'Twitter', 'title' => __l('Sign in with Twitter')));?></li>
			<?php endif;?>
			<?php if(Configure::read('user.is_enable_openid')):?>
				<li class="yahoo"><?php echo $this->Html->link(__l('Sign in with Yahoo'), array('controller' => 'users', 'action' => 'login', 'type'=>'yahoo'), array('alt'=> __l('[Image: Yahoo]'),'title' => __l('Sign in with Yahoo')));?></li>
				<li class="gmail"><?php echo $this->Html->link(__l('Sign in with Gmail'), array('controller' => 'users', 'action' => 'login', 'type'=>'gmail'), array('alt'=> __l('[Image: Gmail]'),'title' => __l('Sign in with Gmail')));?></li>
				<li class="open-id"><?php 	echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with Open ID')));?></li>
			<?php endif;?>
		</ul>
	</div>
	</div>
	<?php
	    echo $this->Form->create('User', array('action' => 'login', 'id' => 'AjaxUserLoginForm', 'class' => 'normal js-ajax-login'));
		echo $this->Form->input(Configure::read('user.using_to_login'), array('id' => 'AjaxUserUserName'));
	    echo $this->Form->input('passwd', array('label' => __l('Password'), 'id' => 'AjaxUserPasswd'));
        ?>

		<?php
		echo $this->Form->input('User.is_remember', array('type' => 'checkbox', 'id' => 'AjaxUserIsRemember',  'label' => __l('Remember me on this computer.')));?>
	  	<div class="fromleft"> 	
		<?php echo $this->Html->link(__l('Forgot your password?') , array('controller' => 'users', 'action' => 'forgot_password', 'admin'=>false),array('title' => __l('Forgot your password?'),'class'=>'js-ajax-forgot-colorbox'));
	?>
	<?php if(!(!empty($this->request->params['prefix']) && $this->request->params['prefix'] == 'admin')):	?>
	<?php 
			echo $this->Html->link(__l('Sign Up'), array('controller' => 'users', 'action' => 'register', 'admin' => false), array('class'=>'js-ajax-colorbox-register {source:"js-dialog-body-regsiter"}','title' => __l('Sign Up')));
			?>
			<?php
			if(Configure::read('user.is_enable_openid')):
				echo $this->Html->link(__l('Sign in with Open ID'), array('controller' => 'users', 'action' => 'login','type'=>'openid'), array('class'=>'js-ajax-colorbox-openid {source:"js-dialog-body-open-login"}','title' => __l('Sign in with Open ID')));
			endif;
		endif;
        $f = (!empty($_GET['f'])) ? $_GET['f'] : (!empty($this->request->data['User']['f']) ? $this->request->data['User']['f'] : (($this->request->url != 'admin/users/login' && $this->request->url != 'users/login') ? $this->request->url : ''));
		if(!empty($f)) :
            echo $this->Form->input('f', array('type' => 'hidden', 'id' => 'AjaxUserF', 'value' => $f));
        endif;
        ?>
        	</div>
        	
			<div class="clearfix submit-block">
				<?php echo $this->Form->submit(__l('Submit'));?>	
			</div> 
			<?php echo $this->Form->end();?>
   
</div>