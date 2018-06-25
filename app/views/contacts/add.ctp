<?php if(!empty($success)) : ?>
	<div class="success-msg">
		<?php echo __l('Thank you, we received your message and will get back to you as soon as possible.'); ?>
	</div>
<?php else: ?>
	<div id="breadcrumb">
		<?php
			if(isset($venue_id) && !empty($venue_id)) {
				echo $this->Html->addCrumb($venue['Venue']['name'], array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug']));
				echo $this->Html->addCrumb(__l('This is My Business'));
			} else {
				if (!empty($type)) :
					echo $this->Html->addCrumb($options[$type]);
				else:
					echo $this->Html->addCrumb(__l('Contact Us'));
				endif;
			}
		?>
		<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
	</div>
	<h2 class="title">
		<?php 
		if(isset($venue_id) && !empty($venue_id)) {
			echo __l('This is my business');				
		} else {
			if (!empty($type)):
				echo $options[$type];
			else:
				echo __l('Contact Us');
			endif;
		}
		?>
	</h2>
	<div class="form-content-block clearfix">
	<fieldset>
		<?php
			echo $this->Form->create('Contact', array('class' => 'normal'));
			if(isset($venue_id) && !empty($venue_id)) {
				echo $this->Form->input('venue_id', array('type' => 'hidden', 'value' => $venue_id));
				echo $this->Form->input('contact_type_id', array('type' => 'hidden', 'value' => 4));
			} else {
				echo $this->Form->input('contact_type_id',array('empty'=>__l('Please Select')));
			}
			echo $this->Form->input('first_name', array('label' => __l('First Name')));
			echo $this->Form->input('last_name', array('label' => __l('Last Name')));
			echo $this->Form->input('email');
			echo $this->Form->input('telephone');
			echo $this->Form->input('subject');
			echo $this->Form->input('message');
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
		  <?php if(!empty($this->validationErrors['Contact']['captcha'])) { ?>
		   <div class="error-message"><?php echo __l('Required'); ?></div>
		   <?php } ?>
				<?php } else { ?>
		<div class="input captcha-block clearfix js-captcha-container">
			<div class="captcha-left">
				<?php echo $this->Html->image($this->Html->url(array('controller' => 'users', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));?>
			</div>
			<div class="captcha-right">
				<?php echo $this->Html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
				<div><?php echo $this->Html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $this->Html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5&height=19&width=19&wmode=transparent", array('class' => 'js-captcha-play')); ?></div>
			</div>
		</div>
		<?php echo $this->Form->input('captcha', array('label' => __l('Security Code'))); ?>
		<?php } ?>
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Send')); ?>
		</div>
		<?php echo $this->Form->end(); ?>
       	</fieldset>
	</div>
<?php endif; ?>