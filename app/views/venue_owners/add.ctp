<?php /* SVN: $Id: add.ctp 17575 2012-02-13 05:44:34Z beautlin_108ac10 $ */ ?>
<?php if(!empty($success)) : ?>
	<div class="success-msg">
		<?php echo __l('Thank you for registering as a venue owner at') . ' ' . Configure::read('site.name') . '. You will contacted by our team to provide you with your login information.'; ?>
	</div>
<?php else: ?>
	<div class="venueOwners form">
		<h2><?php echo __l('Venue Owner Signup'); ?></h2>
		<div id="breadcrumb" class="crumb">
			<?php
				echo $this->Html->addCrumb(__l('Venue Owner Signup'));
				echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
			?>
		</div>
		<div class="form-content-block">
		<?php echo $this->Form->create('VenueOwner', array('class' => 'normal'));?>
			<fieldset>
			<?php
				echo $this->Form->input('first_name');
				echo $this->Form->input('last_name');
				echo $this->Form->autocomplete('City.name', array('label' => __l('City'), 'acFieldKey' => 'City.id', 'acFields' => array('City.name'), 'acSearchFieldNames' => array('City.name'), 'maxlength' => '255'));
				echo $this->Form->input('country_id', array('label' => __l('Country'), 'empty' => __l('Please Select')));
				echo $this->Form->input('other_mobile',array('label'=>__l('Phone')));
				echo $this->Form->input('mobile');
				echo $this->Form->input('User.email');
				echo $this->Form->input('gender_id', array('type'=>'radio','legend'=>false, 'before' =>'<span class="label-content">Gender</span>' ));?>
				<span class="label-content dob-info"><?php echo __l('DOB'); ?></span>
				<div class="clearfix input">
				  <div class="js-datetime">
				  <?php echo $this->Form->input('dob', array('type' => 'date', 'label' => false, 'orderYear' => 'asc', 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, 'div' =>false, 'empty' => __l('Please Select'))); ?>
			      </div>
				</div>
			<?php echo $this->Form->input('venue_name');
			echo $this->Form->input('venue_type_id', array('label' => __l('Venue Type'), 'empty' => __l('Please Select')));
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
		  <?php if(!empty($this->validationErrors['VenueOwner']['captcha'])) { ?>
		   <div class="error-message"><?php echo __l('Required'); ?></div>
		   <?php } ?>
				<?php } else { ?>
				<div class="input captcha-block clearfix js-captcha-container">
					<div class="captcha-left">
						<?php
							echo $this->Html->image($this->Html->url(array('controller' => 'venue_owners', 'action' => 'show_captcha', md5(uniqid(time()))), true), array('alt' => __l('[Image: CAPTCHA image. You will need to recognize the text in it; audible CAPTCHA available too.]'), 'title' => __l('CAPTCHA image'), 'class' => 'captcha-img'));
						?>
					</div>
					<div class="captcha-right">
					  <?php echo $this->Html->link(__l('Reload CAPTCHA'), '#', array('class' => 'js-captcha-reload captcha-reload', 'title' => __l('Reload CAPTCHA')));?>
					  <div>
		              <?php echo $this->Html->link(__l('Click to play'), Router::url('/', true)."flash/securimage/play.swf?audio=". $this->Html->url(array('controller' => 'users', 'action'=>'captcha_play'), true) ."&bgColor1=#777&bgColor2=#fff&iconColor=#000&roundedCorner=5&height=19&width=19&wmode=transparent", array('class' => 'js-captcha-play'));
					  ?>
					</div>
					</div>
				</div>
				<?php echo $this->Form->input('captcha', array('label' => __l('Security Code'))); ?>
			<?php
				}
				echo $this->Form->input('is_agree_terms_conditions', array('label' => sprintf(__l('I have read, understood & agree to the %s'), $this->Html->link('Terms & Policies', array('controller' => 'pages', 'action' => 'display', 'terms_conditions'), array('target' => 'blank','escape' => false)))));
			?>
			</fieldset>
			<div class="submit-block clearfix">
				<?php echo $this->Form->end(__l('Submit'));?>
			</div>
		</div>
	</div>
<?php endif; ?>