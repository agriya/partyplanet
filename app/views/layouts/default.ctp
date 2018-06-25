<?php
/* SVN FILE: $Id: default.ctp 7805 2008-10-30 17:30:26Z AD7six $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework (http://www.cakephp.org)
 * Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright     Copyright 2005-2008, Cake Software Foundation, Inc. (http://www.cakefoundation.org)
 * @link          http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.console.libs.templates.skel.views.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @version       $Revision: 7805 $
 * @modifiedby    $LastChangedBy: AD7six $
 * @lastmodified  $Date: 2008-10-30 23:00:26 +0530 (Thu, 30 Oct 2008) $
 * @license       http://www.opensource.org/licenses/mit-license.php The MIT License
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" xmlns:og="http://opengraphprotocol.org/schema/" xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
    <?php echo $this->Html->charset(), "\n";?>
	<title><?php echo Configure::read('site.name');?> | <?php echo $this->Html->cText($title_for_layout, false);?></title>
	<?php
		echo $this->Html->meta('icon'), "\n";
		echo $this->Html->meta('keywords', $meta_for_layout['keywords']), "\n";
		echo $this->Html->meta('description', $meta_for_layout['description']), "\n";
		echo $this->Html->css( Configure::read('site.theme').'.cache', null, array('inline' => true));
		$js_inline = "document.documentElement.className = 'js';";
		$js_inline .= 'var cfg = ' . $this->Javascript->object($js_vars_for_layout) . ';';
		$js_inline .= "(function() {";
		$js_inline .= "var js = document.createElement('script'); js.type = 'text/javascript'; js.async = true;";
		if (!$_jsPath = Configure::read('cdn.js')) {
			$_jsPath = Router::url('/', true);
		}
		$js_inline .= "js.src = \"" . $_jsPath . 'js/default.cache.js' . "\";";
		$js_inline .= "var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(js, s);";
		$js_inline .= "})();";
		echo $this->Javascript->codeBlock($js_inline, array('inline' => true));
	?>
	<?php
		// For other than Facebook (facebookexternalhit/1.1 (+http://www.facebook.com/externalhit_uatext.php)), wrap it in comments for XHTML validation...
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
			echo '<!--', "\n";
		endif;
	?>
	<meta content="<?php echo Configure::read('facebook.app_id');?>" property="og:app_id" />
	<meta content="<?php echo Configure::read('facebook.app_id');?>" property="fb:app_id" />
    <meta property="og:site_name" content="<?php echo Configure::read('site.name'); ?>"/>
	<?php if(!empty($meta_for_layout['name'])):?>
		<meta property="og:title" content="<?php echo $meta_for_layout['name'];?>"/>
	<?php endif;?>
	<?php if(!empty($meta_for_layout['image'])):?>
		<meta property="og:image" content="<?php echo $meta_for_layout['image'];?>"/>
		<meta name="medium" content="image" />
	<?php else:?>
		<meta property="og:image" content="<?php echo Router::url(array(
				'controller' => 'img',
				'action' => 'logo.png',
				'admin' => false
			) , true);?>"/>
	<?php endif;?>
 <?php
		if (strpos(env('HTTP_USER_AGENT'), 'facebookexternalhit')===false):
			echo '-->', "\n";
		endif;
	?>
</head>
<body>
<div id="<?php echo $this->Html->getUniquePageId();?>" class="content">
	<?php if ($this->Session->check('Message.error') || $this->Session->check('Message.success') || $this->Session->check('Message.flash')): ?>
		<div class="js-flash-message flash-message-block">
			<?php
				if ($this->Session->check('Message.error')):
					echo $this->Session->flash('error');
				endif;
				if ($this->Session->check('Message.success')):
					echo $this->Session->flash('success');
				endif;
				if ($this->Session->check('Message.flash')):
					echo $this->Session->flash();
				endif;
			?>
		</div>
	<?php endif; ?>
	<?php if($this->Auth->sessionValid() && $this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
		<div class="clearfix admin-wrapper">
			<h3 class="admin-site-logo"><?php echo $this->Html->link((Configure::read('site.name').' '.'<span>Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Admin')));?></h3>
			<p class="logged-info"><?php echo __l(' You are logged in as Admin');?></p>
			<ul class="admin-menu clearfix">
				<li class="logout"><span><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></span></li>
			</ul>
		</div>
	<?php endif; ?>
	<div id="header" class="clearfix">
		<div class="left-shad">&nbsp;</div>
		<div class="right-shad">&nbsp;</div>
		<div class="header-inner container_24">
			<div class="clearfix">
				<h1 class="grid_8 omega alpha"><?php echo $this->Html->link(Configure::read('site.name'), array('controller' => 'pages', 'action' => 'display', 'home', 'admin' => false), array('title' => Configure::read('site.name'))); ?></h1>
				<p class="caption"><span><?php echo __l('Your nightlife planet');?></span></p>
				<div class="grid_17 grid_right omega alpha">
					<div class="clearfix header-inner-block">
						<ul class="user-link grid_right">
							<?php if(!($this->Auth->sessionValid())): ?>
								<li><?php echo $this->Html->link(__l('Sign Up'), array('controller' => 'users', 'action' => 'joinus', 'admin' => false), array('title'=>__l('Sign Up'),'escape' => false));?></li>
								<li><?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login', 'admin' => false), array('title'=>__l('Login'),'escape' => false)); ?></li>
							<?php else: ?>
								<?php
									$message_count = $this->Html->getUserUnReadMessages($this->Auth->user('id'));
									$message_count = !empty($message_count) ? ' ('.$message_count.')' : '';
								?>
								<li  class="<?php echo ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'dashboard') ? 'active' : ''?>"><?php echo $this->Html->link(__l('Dashboard'), array('controller' => 'users', 'action' => 'dashboard', 'admin' => false), array('title'=>__l('Dashboard'), 'escape' => false)); ?></li>
								<li><?php echo $this->Html->link(__l('Inbox').$message_count, array('controller' => 'messages', 'action' => 'index', 'admin' => false), array('title'=>__l('Inbox'),'escape' => false)); ?></li>
								<li><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout', 'admin' => false), array('class' => 'logout-link', 'title' => __l('Logout'))); ?></li>
							<?php endif; ?>
						</ul>
						<?php $languages = $this->Html->getLanguage(); ?>
						<?php if(Configure::read('user.is_allow_user_to_switch_language') && !empty($languages)) :?>
							<?php if(($this->Auth->sessionValid())) {?>
								<div class="language-block grid_right">
							<?php } else { ?>
								<div class="language-block language-block1  grid_right">
							<?php } ?>
								<a title="English" href="#"><?php echo isset($_COOKIE['CakeCookie']['user_language']) ? $languages[$_COOKIE['CakeCookie']['user_language']] : $languages[Configure::read('site.language')];?></a>
								<ol class="sub-menu">
									<?php foreach ($languages as $key=>$lang): ?>
										<li class="clearfix"><?php echo $this->Html->link(__l($lang), array('controller' => 'languages', 'action' => 'change_language','language_id'=>$key), array('class' => '', 'title'=>$lang ,'escape' => false)); ?></li>
									<?php endforeach;?>
								</ol>
							</div>
						<?php endif;?>
					</div>
				<div class="clearfix">
				<ul class="add-venue-list grid_left omega clearfix">
					<?php if(!empty($city_name)):?>
        				<li class="cityname-link">
                           <?php  echo $this->Html->link($city_name, Router::url('/',true),array('class' =>'cities-name'));?>
                           <?php echo $this->element('city-index', array('key' => !empty($this->request->params['named']['city']) ? $this->request->params['named']['city'] : '', 'cache' => array('config' => '2sec'))); ?>
                        </li>
                     <?php endif;?>
					<li><?php echo $this->Html->link(__l('Add Venue'), array('controller' => 'venues', 'action' => 'add', 'admin' => false), array('title' => __l('Add Venue'), 'class' => 'add', 'escape' => false));?></li>
                    <li> <?php echo $this->Html->link(__l('Add Event'), array('controller' => 'events', 'action' => 'add', 'admin' => false), array('title' => __l('Add Event'), 'class' => 'add', 'escape' => false));?></li>
				</ul>
				<div class="grid_9 grid_right alpha omega header-right">
   				<?php if(($this->Auth->sessionValid())) {?>
						<div class="clearfix">
							<?php $user_name = $this->Html->getUserName($this->Auth->user('id')); ?>
							<div class="grid_right welcome-info">
								<?php echo __l('Welcome'); ?>
								<?php if($this->Auth->user('user_type_id') == ConstUserTypes::User): ?>
									<?php
										$reg_type_class = '';
										if ($this->Auth->user('is_gmail_register')):
											$reg_type_class = 'open-id-gmail-thumb';
										elseif($this->Auth->user('is_yahoo_register')):
											$reg_type_class = 'open-Ð-thumb';
										elseif ($this->Auth->user('is_openid_register')):
											$reg_type_class = 'open-id-thumb';
										elseif ($this->Auth->user('is_facebook_register')):
											$reg_type_class = 'facebook';
										elseif($this->Auth->user('is_twitter_register')):
											$reg_type_class='twitter';
										endif;

									?>
									<span class="<?php echo $reg_type_class; ?>"><?php echo $this->Html->getUserAvatarLink($this->Auth->user('id'), 'micro_thumb'); ?></span>
								<?php else: ?>
									<?php echo $this->Html->getUserAvatarLink($this->Auth->user('id'), 'micro_thumb'); ?>
								<?php endif; ?>
								<?php echo $this->Html->link(__l($this->Auth->user('username')), array('controller' => 'users', 'action' => 'view', $this->Auth->user('username'), 'admin' => false), array('title' => $this->Auth->user('username'), 'escape' => false)); ?>
							</div>
						</div>
					<?php } ?>
					<div class="form-block clearfix">
						<?php echo $this->Form->create('Event', array('action' => 'search_keyword', 'type'=>'get', 'id'=>'searchForm', 'class' => 'search clearfix')); ?>
						<div class="js-overlabel">
							<?php echo $this->Form->input('name', array('label' => __l('enter search keyword'))); ?>
						</div>
						<?php echo $this->Form->submit(__l('Search')); ?>
						<?php echo $this->Form->end(); ?>
					</div>
					<?php if(!($this->Auth->sessionValid())): ?>
						<div class="clearfix openid-block grid_right">
							<h5 class="grid_left"><?php echo __l('Sign in using:'); ?></h5>
							<ul class="open-id-list grid_left clearfix">
								<?php if(Configure::read('facebook.is_enabled_facebook_connect')):  ?>
									<li class="face-book"><?php echo $this->Html->link(__l('Sign in with Facebook'), array('controller' => 'users', 'action' => 'login','type'=>'facebook', 'admin'=>false), array('title' => __l('Sign in with Facebook'), 'escape' => false)); ?></li>
								<?php endif; ?>
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
					<?php endif; ?>
				</div>
				</div>
				</div>
			</div>
			<?php if(!empty($_prefixSlug)):?>
			<div class="menu-block clearfix">
				<?php echo $this->element('header_menu', array('key' => !empty($this->request->params['named']['city']) ? $this->request->params['named']['city'] : '', 'cache' => array('config' => '2sec'))); ?>
			</div>
			<?php endif;?>
		</div>
	</div>
	<div id="main" class="js-lazyload">
		<div class="main-inner container_24 clearfix">
			<?php $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action']; ?>
			<?php if ($cur_page =='pages/display' && !empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'home'): ?>
				<div class="banner clearfix"> 
					<?php echo $this->element('random_photos', array('key' => $this->request->params['named']['city'], 'cache' => array('config' => 'sec'))); ?>
				</div>
			<?php endif; ?>
			<div class="clearfix">
				<?php if ($cur_page == 'venues/view') { ?>
					<?php if (!empty($venue['WideScreen']['id'])) { ?>
						<?php echo $this->Html->showImage('Venue', $venue['WideScreen'], array('dimension' => 'wide_screen_thumb', 'title' => $this->Html->cText($venue['Venue']['name']), 'alt' => sprintf('[Image: %s]', $this->Html->cText($venue['Venue']['name'])))); ?>
					<?php } ?>
				<?php } ?>
				<div class="<?php echo (!empty($this->request->params['pass'][0]) && $this->request->params['pass'][0] == 'home') ? 'grid_17 alpha omega home-side1' : 'side1 grid_17 alpha omega clearfix' ?>">
					<?php
						if ($this->Session->check('Message.error')):
							echo $this->Session->flash('error');
						endif;
						if ($this->Session->check('Message.success')):
							echo $this->Session->flash('success');
						endif;
						if ($this->Session->check('Message.flash')):
							echo $this->Session->flash();
						endif;
					?>
					<div class="side1-inner">
						<?php echo $content_for_layout;?>
					</div>
				</div>
				<?php if($cur_page != 'cities/index'):
				if(!empty($_prefixSlug)):?>
				<div class="side2 grid_8 alpha omega">
					<?php echo $this->element('sidebar', array('key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec'))); ?>
				</div>
				<?php endif;?>
				<?php endif;?>
			</div>
		</div>
	</div>
	<div id="footer">
		<div class="container_24 clearfix">
			<div class="suffix_1 grid_4 omega alpha">
				<h6><?php echo __l('Get to Know Us'); ?></h6>
				<ul class="footer-list">
					<li><?php echo $this->Html->link(__l('About Us'), array('controller' => 'pages', 'action' => 'view', 'about-us', 'admin' => false), array('title' => __l('About Us'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'add', 'admin' => false), array('title' => __l('Contact Us'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Privacy Guidelines'), array('controller' => 'pages', 'action' => 'view', 'privacy-guidelines', 'admin' => false), array('title' => __l('Privacy Guidelines'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Advertise With Us'), array('controller' => 'pages', 'action' => 'view', 'advertise-with-us', 'admin' => false), array('title' => __l('Advertise With Us'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Terms and Conditions'), array('controller' => 'pages', 'action' => 'view', 'terms-and-conditions', 'admin' => false), array('title' => __l('Terms and Conditions'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Acceptable Use Policy'), array('controller' => 'pages', 'action' => 'view', 'acceptable-use-policy', 'admin' => false), array('title' => __l('Acceptable Use Policy'), 'escape' => false));?></li>
					<?php if(Configure::read('affiliate.is_enabled')):?>
						<?php $class = ($this->request->params['controller'] == 'affiliates') ? ' class="active"' : null; ?>
						<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?></li>
					<?php endif; ?>
				</ul>
			</div>
			<div class="suffix_1 grid_4 omega alpha">
				<h6><?php echo __l('Customer Service'); ?></h6>
				<ul class="footer-list">
					<li><?php echo $this->Html->link(__l('All Venues'), array('controller' => 'venues', 'action' => 'index', 'admin' => false), array('title' => __l('All Venues'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Advanced Search'), array('controller' => 'venues', 'action' => 'search', 'admin' => false), array('title' => __l('Advanced Search'), 'escape' => false));?></li>
				</ul>
			</div>
			<div class="suffix_1 grid_4 omega alpha">
				<h6><?php echo __l('Popular News &amp; Events'); ?></h6>
				<ul class="footer-list">
					<li><?php echo $this->Html->link(__l('Event Calendar'), array('controller' => 'events', 'action' => 'index', 'admin' => false), array('title' => __l('Event Calendar'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Guestlists'), array('controller' => 'events', 'action' => 'index', 'type' => 'guest', 'admin' => false), array('title' => __l('Guestlists'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Search by Type'), array('controller' => 'events', 'action' => 'search', 'type' => 'type', 'admin' => false), array('title' => __l('Search by Type'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Search by Location'), array('controller' => 'events', 'action' => 'search', 'type' => 'location', 'admin' => false), array('title' => __l('Search by Location'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Add Your Event'), array('controller' => 'events', 'action' => 'add', 'admin' => false), array('title' => __l('Add Your Event'), 'escape' => false));?></li>
				</ul>
			</div>
			<div class="grid_4 omega alpha">
				<h6><?php echo __l('Follow us'); ?></h6>
				<ul class="footer-list follow-list clearfix">
					<li class="f-face"> <?php echo $this->Html->link(__l('Facebook'), Configure::read('site.facebook_url'), array('title' => __l('Facebook'), 'escape' => false));?></li>
					<li class="f-twitt"> <?php echo $this->Html->link(__l('Twitter'), Configure::read('site.twitter_url'), array('title' => __l('Twitter'), 'escape' => false));?></li>
				</ul>
			</div>
			<div class="grid_7 footer-inner grid_right clearfix alpha omega">
				<div class="clearfix">
					<h6 class="grid_8 omega alpha"><?php echo $this->Html->link(Configure::read('site.name'), Router::url('/',true));?></h6>
					<p class="caption"><span><?php echo __l('Your nightlife planet');?></span></p>
					<p class="copy">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
					<p class="powered clearfix"><span><?php echo $this->Html->link('Powered by PartyPlanet', 'http://partyplanet.dev.agriya.com/', array('target' => '_blank', 'title' => 'Powered by PartyPlanet', 'class' => 'powered'));?>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
				</div>
				<a class="cssilize cssilize1" title="CSSilized by CSSilize, PSD to XHTML Conversion" target="_blank" href="http://www.cssilize.com/">CSSilized by CSSilize</a>
			</div>
		</div>
	</div>
</div>
<?php echo $this->element('site_tracker');?>
<?php echo $this->element('sql_dump'); ?>
</body>
</html>