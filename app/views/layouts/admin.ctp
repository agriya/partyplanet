<?php
/* SVN FILE: $Id: default.ctp 6474 2008-02-24 03:47:41Z nate $ */
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) :  Rapid Development Framework <http://www.cakephp.org/>
 * Copyright 2005-2008, Cake Software Foundation, Inc.
 *								1785 E. Sahara Avenue, Suite 490-204
 *								Las Vegas, Nevada 89104
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @filesource
 * @copyright		Copyright 2005-2008, Cake Software Foundation, Inc.
 * @link				http://www.cakefoundation.org/projects/info/cakephp CakePHP(tm) Project
 * @package			cake
 * @subpackage		cake.cake.console.libs.templates.skel.views.layouts
 * @since			CakePHP(tm) v 0.10.0.1076
 * @version			$Revision: 6474 $
 * @modifiedby		$LastChangedBy: nate $
 * @lastmodified	$Date: 2008-02-24 09:17:41 +0530 (Sun, 24 Feb 2008) $
 * @license			http://www.opensource.org/licenses/mit-license.php The MIT License
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
		echo $this->Html->css('admin.cache', null, array('inline' => true));
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
		echo $this->element('site_tracker');
	?>
</head>
<body class="admin">
   <div class="admin-content">
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
    <div class="admin-content-block">

	<div id="<?php echo $this->Html->getUniquePageId();?>" class="admin-container-24">

	
      <div class="clearfix" id="admin-header">
	    <div class="clearfix">
			<h1 class="grid_5 omega alpha">
	              	<?php echo $this->Html->link((Configure::read('site.name').' '.'<span>Admin</span>'), array('controller' => 'users', 'action' => 'stats', 'admin' => true), array('escape' => false, 'title' => (Configure::read('site.name').' '.'Admin')));?>
    		</h1>
    		 	<ul class="admin-menu clearfix">
		        <li class="view-site"><?php echo $this->Html->link(__l('Visit site'), Router::url('/', true), array('title' => __l('Visit site')));?></li>
				    <li><?php echo $this->Html->link(__l('Diagnostics'), array('controller' => 'users', 'action' => 'diagnostics', 'admin' => true),array('title' => __l('Diagnostics'))); ?></li>
					<li><?php echo $this->Html->link(__l('Tools'), array('controller' => 'pages', 'action' => 'display', 'tools', 'admin' => true), array('escape' => false, 'title' => __l('View Site')));?></li>
					<li><?php echo $this->Html->link(__l('My Account'), array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id')), array('title' => __l('My Account')));?></li>
					<li><?php echo $this->Html->link(__l('Change Password'), array('controller' => 'users', 'action' => 'admin_change_password'), array('title' => __l('Change Password')));?></li>
					<li><?php echo $this->Html->link(__l('Logout'), array('controller' => 'users', 'action' => 'logout'), array('title' => __l('Logout')));?></li>
  			   </ul>
		 </div>
		   <?php echo $this->element('admin-sidebar', array('config' => 'sec')); ?>
		   <div class="header-shadow"></div>
     </div>
    <div id="main" class="clearfix">
				<?php
					$user_menu = array('users', 'user_profiles', 'messages', 'user_logins', 'user_comments', 'user_views', 'venue_owners');
					$venues_menu = array('venues', 'venue_comments','venue_users');
					$events_menu = array('events', 'guest_list_users', 'event_comments');
                    $payments_menu = array('transactions', 'payment_gateways');
					$photos_menu = array('photos', 'photo_views', 'photo_comments', 'photo_ratings', 'photo_albums', 'photo_favorites', 'photo_flags', 'articles', 'article_comments', 'party_planners', 'videos', 'video_views', 'video_comments', 'video_ratings', 'video_favorites', 'video_downloads', 'video_flags', 'forums', 'forum_views', 'forum_comments', 'contacts');
					$affiliates_menu = array('affiliates', 'affiliate_requests', 'affiliate_types', 'affiliate_cash_withdrawals');
					$settings_menu = array('settings');
					$master_menu = array('venue_types', 'venue_sponsors', 'venue_categories', 'venue_features', 'parking_types', 'event_categories', 'event_sponsors', 'event_scenes', 'agr_requirements', 'languages', 'translations', 'bar_service_types', 'food_caterings', 'party_types', 'entertainments', 'ethnicities', 'sexual_orientations', 'body_types', 'marital_statuses', 'favorite_fashion_brands', 'cell_providers',  'pages', 'email_templates', 'cities', 'states', 'countries', 'banned_ips', 'music_types', 'photo_flag_categories', 'video_categories', 'video_flag_categories', 'forum_categories', 'article_categories', 'contact_types','age_requirments');
					$devs_menu = array('devs');
						$class='';
				    if(in_array($this->request->params['controller'], $user_menu) && $this->request->params['action'] != 'admin_diagnostics') {
						$class = "users-title";
					} elseif(in_array($this->request->params['controller'], $venues_menu)) {
						$class = "venues-title";
					} elseif(in_array($this->request->params['controller'], $events_menu)) {
						$class = "events-title";
					} elseif(in_array($this->request->params['controller'], $photos_menu)) {
						$class = "modules-title";
					} elseif(in_array($this->request->params['controller'], $affiliates_menu)) {
						$class = "partners-title";
					} elseif(in_array($this->request->params['controller'], $master_menu)) {
						$class = "master-title";
					} elseif(in_array($this->request->params['controller'], $settings_menu)) {
						$class = "settings-title";
					} elseif(in_array($this->request->params['controller'], $devs_menu)) {
						$class = "devs_title";
					} elseif($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_diagnostics') {
						$class = "diagnostics-title";
					} elseif(in_array($this->request->params['controller'], $payments_menu)) {
						$class = "payments-title";
					}
					if ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_stats') {
						echo $content_for_layout;
					}  else {
				?>
					<div class="admin-side1-tc page-title-info">
						<h2 class="clearfix <?php echo $class; ?> devs_title">
						<?php $diagnostics_menu = array('devs', 'adaptive_ipn_logs', 'adaptive_transaction_logs'); ?>
							<?php if($this->request->params['controller'] == 'settings' && $this->request->params['action'] == 'index') { ?>
								<?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'index'), array('title' => __l('Back to Settings')));?>
							<?php } elseif($this->request->params['controller'] == 'settings' && $this->request->params['action'] == 'admin_edit' ) { ?>
								<?php echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'index'), array('title' => __l('Back to Settings')));?> &raquo; <?php echo $setting_categories['SettingCategory']['name']; ?>
							<?php } elseif(in_array($this->request->params['controller'], $diagnostics_menu) || $this->request->params['controller'] == 'users'  && $this->request->params['action'] == 'admin_logs') { ?>
								<?php echo $this->Html->link(__l('Diagnostics'), array('controller' => 'users', 'action' => 'diagnostics', 'admin' => true), array('title' => __l('Diagnostics')));?> &raquo; <?php echo $this->pageTitle;?>
							<?php } elseif($this->request->params['controller'] == 'payment_gateways'  && $this->request->params['action'] == 'admin_paypal_diagnose') { ?><?php echo $this->Html->link(__l('Diagnostics'), array('controller' => 'users', 'action' => 'diagnostics', 'admin' => true), array('title' => __l('Diagnostics')));?> &raquo; <?php echo $this->pageTitle;?>
							<?php }else { ?>
								<?php echo $this->Html->cText($this->pageTitle,false);?>
							<?php } ?>
							<?php if($this->request->params['controller'] == 'settings') { ?>
								<span class="setting-info info"><?php echo __l('To reflect setting changes, you need to') . ' ' . $this->Html->link(__l('clear cache'), array('controller' => 'devs', 'action' => 'clear_cache', '?f=' . $this->request->url), array('title' => __l('clear cache'), 'class' => 'js-delete'));  ?>.</span>
							<?php } ?>
						</h2>
					</div>
					<div class="admin-center-block clearfix">
						<div>
						  <?php if(!Configure::read('site.is_allow_user_to_enable_ticket_fee_in_guest_list_for_event') && (in_array($this->request->params['controller'], array('payment_gateways', 'transactions', 'adaptive_transaction_logs', 'adaptive_ipn_logs'))) ){ ?>
                            <div class="page-info"><?php echo __l('Event Booking is currently disabled. You can enable it from '); 
                              echo $this->Html->link(__l('Settings'), array('controller' => 'settings', 'action' => 'edit', 60),array('title' => __l('Settings'))). __l(' page'); ?> 
						    </div>
						    <?php } else { ?>
							<?php echo $content_for_layout; ?>
							<?php } ?>
						</div>
					</div>
				<?php } ?>
			</div>
		
	<?php echo $this->element('site_tracker');?>
	<?php echo $this->element('sql_dump'); ?>
	
</div>
<div class="clearfix" id="admin-footer">
	<div class="footer-wrap admin-footer admin-container-24 clearfix">
		<div class="footer-inner clearfix alpha omega">
			<div class="clearfix">
				<p class="copy">&copy;<?php echo date('Y');?> <?php echo $this->Html->link(Configure::read('site.name'), Router::Url('/',true), array('title' => Configure::read('site.name'), 'escape' => false));?>. <?php echo __l('All rights reserved');?>.</p>
				<p class="powered clearfix"><span><?php echo $this->Html->link('Powered by PartyPlanet', 'http://partyplanet.dev.agriya.com/', array('target' => '_blank', 'title' => 'Powered by PartyPlanet', 'class' => 'powered'));?>,</span> <span>made in</span> <?php echo $this->Html->link('Agriya Web Development', 'http://www.agriya.com/', array('target' => '_blank', 'title' => 'Agriya Web Development', 'class' => 'company'));?>  <span><?php echo Configure::read('site.version');?></span></p>
				<a class="cssilize cssilize1" title="CSSilized by CSSilize, PSD to XHTML Conversion" target="_blank" href="http://www.cssilize.com/">CSSilized by CSSilize</a>
			</div>
		</div>
	</div>
</div>
</div>
</div>
</body>
</html>