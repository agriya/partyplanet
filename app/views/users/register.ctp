<div class="users form">
	<div id="breadcrumb">
		<?php
			/*if ($this->request->data['User']['user_type_id'] == ConstUserTypes::Promoter):
				echo $this->Html->addCrumb(__l('Promoter Signup'));
				$register_page_title = __l('Promoter Signup');
			else */
			if ($this->request->data['User']['user_type_id'] == ConstUserTypes::VenueOwner):
				echo $this->Html->addCrumb(__l('Venue Owner Signup'));
				$register_page_title = __l('Venue Owner Signup');
			else:
				echo $this->Html->addCrumb(__l('New Member Signup Form'));
				$register_page_title = __l('New Member Signup Form');
			endif;
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
	<h2 class="title venue-title"><?php echo $register_page_title; ?></h2>
	<div class="form-content-block">
	<?php echo $this->Form->create('User', array('action' => 'register', 'class' => 'normal')); ?>
		<fieldset>
			<?php
				echo $this->Form->input('user_type_id', array('type' => 'hidden',));
				if(!empty($this->request->data['User']['openid_url'])):
					echo $this->Form->input('openid_url', array('type' => 'hidden', 'value' => $this->request->data['User']['openid_url']));
				endif;
				echo $this->Form->input('UserProfile.first_name');
				echo $this->Form->input('UserProfile.last_name');
				echo $this->Form->autocomplete('City.name', array('label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
				echo $this->Form->input('UserProfile.country_id',array('empty'=>__l('Please Select')));
				echo $this->Form->input('UserProfile.mobile');
				echo $this->Form->input('email');
				echo $this->Form->input('UserProfile.gender_id', array('type'=>'radio','legend'=>false, 'before' =>'<span class="label-content">Gender</span>' ));
			?>
			<span class="label-content dob-info"><?php echo __l('DOB'); ?></span>
			<div class="js-datetime">
		
				<?php echo $this->Form->input('UserProfile.dob', array('type' => 'date', 'label' => false, 'orderYear' => 'asc', 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, 'div' =>false, 'empty' => __l('Please Select'))); ?>
			</div>
			<?php	echo $this->Form->input('referred_by_user_id',array('type' => 'hidden'));
				echo $this->Form->input('username', array('info' => __l('Use this username for login')));
				if(empty($this->request->data['User']['openid_url']) && empty($this->request->data['User']['fb_user_id']) && empty($this->request->data['User']['twitter_user_id'])):
					echo $this->Form->input('passwd', array('label' => __l('Password')));
				endif;
			if(!empty($this->request->data['User']['fb_user_id'])) :
                echo $this->Form->input('fb_user_id', array('type' => 'hidden', 'value' => $this->request->data['User']['fb_user_id']));
                echo $this->Form->input('is_facebook_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_facebook_register']));
            endif;
			if(!empty($this->request->data['User']['twitter_user_id'])) :
                echo $this->Form->input('twitter_user_id', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_user_id']));
	            echo $this->Form->input('is_twitter_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_twitter_register']));
            endif;
			if(!empty($this->request->data['User']['twitter_avatar_url'])) :
				echo $this->Form->input('twitter_avatar_url', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_avatar_url']));
			endif;
			if(!empty($this->request->data['User']['twitter_access_token'])) :
                echo $this->Form->input('twitter_access_token', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_access_token']));
            endif;		 
			if(!empty($this->request->data['User']['twitter_access_key'])) :
                echo $this->Form->input('twitter_access_key', array('type' => 'hidden', 'value' => $this->request->data['User']['twitter_access_key']));
            endif;		 
			if(!empty($this->request->data['User']['is_yahoo_register'])) :
                echo $this->Form->input('is_yahoo_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_yahoo_register']));
            endif;
            if(!empty($this->request->data['User']['is_gmail_register'])) :
                echo $this->Form->input('is_gmail_register', array('type' => 'hidden', 'value' => $this->request->data['User']['is_gmail_register']));
            endif;	
			?>
		<?php
		if (Configure::read('system.captcha_type') == 'Solve media') {
  ?>
		  <div class="input captcha-block clearfix">
		  <div class="captcha-left">
			<?php
			App::import('Vendor', 'solvemedialib');  //include the Solve Media library
			echo solvemedia_get_html(Configure::read('captcha.challenge_key'));  //outputs the widget
			?>		   
		  </div>
		  </div>
		  <?php if(!empty($this->validationErrors['User']['captcha'])) { ?>
		   <div class="error-message"><?php echo __l('Required'); ?></div>
		   <?php } ?>
		  <?php } else { ?>
   		<div class="input captcha-block clearfix js-captcha-container">
    			<div class="captcha-left">
    	           <?php echo $this->Html->image(Router::url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
    	        </div>
                <?php if($this->layoutPath != 'mobile'){ ?>
    	        <div class="captcha-right">
        	        <?php echo $this->Html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
        			<div>
		              <?php echo $this->Html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $this->Html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5&height=19&width=19&wmode=transparent", array('class' => 'js-captcha-play'));

					  ?>
			      </div>
                  				<?php 	  } ?>

    	        </div>
         </div>
			<?php echo $this->Form->input('captcha', array('label' => __l('Security Code'))); ?>			
			<?php } ?>
			<?php echo $this->Form->input('is_agree_terms_conditions', array('label' => sprintf(__l('I have read, understood & agree to the %s'), $this->Html->link('Terms & Policies', array('controller' => 'pages', 'action' => 'display', 'terms_conditions'), array('target' => 'blank','escape' => false))))); ?>
		</fieldset>
		<div class="submit-block clearfix">
        	<?php echo $this->Form->submit(__l('Submit')); ?>
        </div>
         <?php echo $this->Form->end(); ?>
	</div>
</div>
<script type="text/javascript" id="__openidselector" src="https://www.idselector.com/widget/button/1"></script>