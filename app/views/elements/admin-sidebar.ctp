<ul class="admin-links clearfix">
	<?php $class = ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'admin_stats') ? ' active' : null; ?>
	<li class="no-bor<?php echo $class;?>">
        <span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center home">
                     <?php echo __l('Dashboard'); ?>
                 </span>
            </span>
         </span>
		 <div class="admin-sub-block">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="">
							<li>
								<h4><?php echo __l('Dashboard'); ?></h4>
								<ul>
      								<li><?php echo $this->Html->link(__l('Snapshot'), array('controller' => 'users', 'action' => 'stats'), array('title' =>__l('Snapshot'), 'escape' => false));?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('users', 'user_profiles',  'user_logins', 'user_views',  'messages');
		$class = (in_array( $this->request->params['controller'], $controller) && !in_array($this->request->params['action'], array('admin_logs', 'admin_stats'))) ? ' active' : null;
	?>
	<li class="no-bor<?php echo $class;?>">
		<span class="amenu-left">
			<span class="amenu-right">
				<span class="menu-center users">
					<?php echo __l('Users'); ?>
				</span>
			</span>
		</span>
		<div class="admin-sub-block">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="">
							<li>
								<h4><?php echo __l('Users'); ?></h4>
								<ul>
									<li><?php echo $this->Html->link(__l('Users'), array('controller' => 'users', 'action' => 'index'), array('title' =>__l('Users'), 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('Pending Venue Owner Signup'), array('controller' => 'venue_owners', 'action' => 'admin_index','filter_id' => ConstMoreAction::Inactive), array('title' =>__l('Pending Venue Owner Signup') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('User Logins'), array('controller' => 'user_logins', 'action' => 'index'), array('title' =>__l('User Logins') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('User Comments'), array('controller' => 'user_comments', 'action' => 'index'), array('title' =>__l('User Comments') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('User Messages'), array('controller' => 'messages', 'action' => 'index'),array('title' => __l('User Messages'))); ?></li>
									<li><?php echo $this->Html->link(__l('User Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index'), array('title' =>__l('User Photo Galleries') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('Send Mail to Users'), array('controller' => 'users', 'action' => 'send_mail'), array('title' =>__l('Send Mail to Users') , 'escape' => false));?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('venues', 'venue_comments');
		$class = (in_array( $this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="no-bor<?php echo $class;?>">
		<span class="amenu-left">
			<span class="amenu-right">
				<span class="menu-center sightings">
					<?php echo __l('Venues'); ?>
				</span>
			</span>
		</span>
		<div class="admin-sub-block">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="">
							<li>
								<h4><?php echo __l('Venues'); ?></h4>
								<ul>
									<li><?php echo $this->Html->link(__l('Venues'), array('controller' => 'venues', 'action' => 'index'), array('title' =>__l('Venues'), 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('Venue Users'), array('controller' => 'venue_users', 'action' => 'index'), array('title' =>__l('Venue Users'), 'escape' => false));?></li>
                        			<li><?php echo $this->Html->link(__l('Venue Comments'), array('controller' => 'venue_comments', 'action' => 'index'), array('title' =>__l('Venue Comments') , 'escape' => false));?></li>
                        			<li><?php echo $this->Html->link(__l('Venue Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index', 'type' => 'venue'), array('title' =>__l('Venue Photo Galleries') , 'escape' => false));?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('events', 'event_users', 'event_comments');
		$class = (in_array( $this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="no-bor<?php echo $class;?>">
		<span class="amenu-left">
             <span class="amenu-right">
                 <span class="menu-center places">
                     <?php echo __l('Events'); ?>
                 </span>
            </span>
         </span>
		 <div class="admin-sub-block">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="">
							<li>
								<h4><?php echo __l('Events'); ?></h4>
								<ul>
									<li><?php echo $this->Html->link(__l('Events'), array('controller' => 'events', 'action' => 'index'), array('title' =>__l('Events') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('User Joined Events'), array('controller' => 'guest_list_users', 'action' => 'index'), array('title' =>__l('User Joined Events'), 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('Event Comments'), array('controller' => 'event_comments', 'action' => 'index'), array('title' =>__l('Event Comments') , 'escape' => false));?></li>
									<li><?php echo $this->Html->link(__l('Event Photo Galleries'), array('controller' => 'photo_albums', 'action' => 'index', 'type' => 'event'), array('title' =>__l('Event Photo Galleries') , 'escape' => false));?></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('photos', 'photo_comments', 'photo_flags', 'articles', 'article_comments', 'videos', 'video_comments', 'video_ratings', 'video_flags', 'forums', 'forum_comments');
		$class = (in_array( $this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="no-bor<?php echo $class;?>">
		<span class="amenu-left">
			<span class="amenu-right">
				<span class="menu-center guides">
					<?php echo __l('Modules'); ?>
				</span>
            </span>
         </span>
		 <div class="admin-sub-block admin-sub-block2">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="admin-sub-links">
							<li>
								<ul class="clearfix">
									<li class="admin-sub-links-left">
										<h4><?php echo __l('Photos'); ?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Photos'), array('controller' => 'photos', 'action' => 'index'), array('title' => __l('Photos'))); ?></li>
											<li><?php echo $this->Html->link(__l('Home Banner Photos'), array('controller' => 'photos', 'action' => 'index', 'type' => 'random'), array('title' => __l('Home Banner Photos'))); ?></li>
											<li><?php echo $this->Html->link(__l('Photo Views'), array('controller' => 'photo_views', 'action' => 'index'), array('title' => __l('Photo Views'))); ?></li>
											<?php if (Configure::read('photo.is_allow_photo_comment')): ?>
												<li><?php echo $this->Html->link(__l('Photo Comments'), array('controller' => 'photo_comments', 'action' => 'index'), array('title' =>__l('Photo Comments') , 'escape' => false));?></li>
											<?php endif; ?>
											<?php if (Configure::read('photo.is_allow_photo_rating')): ?>
												<li><?php echo $this->Html->link(__l('Photo Ratings'), array('controller' => 'photo_ratings', 'action' => 'index'),array('title' => __l('Photo Ratings'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
												<li><?php echo $this->Html->link(__l('Photo Favorites'), array('controller' => 'photo_favorites', 'action' => 'index'),array('title' => __l('Photo Favorites'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('photo.is_allow_photo_flag')): ?>
												<li><?php echo $this->Html->link(__l('Photo Flags'), array('controller' => 'photo_flags', 'action' => 'index'), array('title' => __l('Photo Flags'))); ?></li>
											<?php endif; ?>
										</ul>
										<h4><?php echo __l('News'); ?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('News'), array('controller' => 'articles', 'action' => 'index'), array('title' =>__l('News') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('News Comments'), array('controller' => 'article_comments', 'action' => 'index'), array('title' =>__l('News Comments') , 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Party Planners');?></h4>
										<ul>
			                    			<li><?php echo $this->Html->link(__l('Party Planners'), array('controller' => 'party_planners', 'action' => 'index'), array('title' =>__l('Party planners') , 'escape' => false));?></li>
										</ul>
									</li>
									<li class="admin-sub-links-right">
										<h4><?php echo __l('Videos'); ?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Videos'), array('controller' => 'videos', 'action' => 'index'), array('title' => __l('Videos'))); ?></li>
											<li><?php echo $this->Html->link(__l('Video Views'), array('controller' => 'video_views', 'action' => 'index'),array('title' => __l('Video Views'))); ?></li>
											<?php if (Configure::read('Video.is_enable_video_comments')): ?>
												<li><?php echo $this->Html->link(__l('Video Comments'), array('controller' => 'video_comments', 'action' => 'index'), array('title' => __l('Video Comments'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('Video.is_enable_video_ratings')): ?>
												<li><?php echo $this->Html->link(__l('Video Ratings'), array('controller' => 'video_ratings', 'action' => 'index'),array('title' => __l('Video Ratings'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
												<li><?php echo $this->Html->link(__l('Video Favorites'), array('controller' => 'video_favorites', 'action' => 'index'),array('title' => __l('Video Favorites'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('Video.is_enable_video_downloads')): ?>
												<li><?php echo $this->Html->link(__l('Video Downloads'), array('controller' => 'video_downloads', 'action' => 'index'),array('title' => __l('Video Downloads'))); ?></li>
											<?php endif; ?>
											<?php if (Configure::read('Video.is_enable_video_flags')): ?>
												<li><?php echo $this->Html->link(__l('Video Flags'), array('controller' => 'video_flags', 'action' => 'index'),array('title' => __l('Video Flags'))); ?></li>
											<?php endif; ?>
										</ul>
										<h4><?php echo __l('Forums'); ?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Forums'), array('controller' => 'forums', 'action' => 'index'), array('title' =>__l('Forums') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Forum Comments'), array('controller' => 'forum_comments', 'action' => 'index'), array('title' =>__l('Forum Comments') , 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Contacts'); ?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Contact Us'), array('controller' => 'contacts', 'action' => 'index'), array('title' =>__l('Contact Us') , 'escape' => false));?></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php if (Configure::read('affiliate.is_enabled')): ?>
		<?php
			$controller = array('affiliates', 'affiliate_requests',  'affiliate_types');
			$class = (in_array($this->request->params['controller'], $controller)) ? ' active' : null;
		?>
		<li class="no-bor<?php echo $class;?>">
			<span class="amenu-left">
				<span class="amenu-right">
					<span class="menu-center patner">
						<?php echo __l('Partners'); ?>
					</span>
				</span>
			</span>
			<div class="admin-sub-block">
				<div class="admin-bot-rblock1">
					<div class="admin-bot-cblock1"></div>
				</div>
				<div class="admin-sub-lblock">
					<div class="admin-sub-rblock">
						<div class="admin-sub-cblock">
						<?php echo $this->element('affiliate_admin_sidebar');?>
						
						</div>
					</div>
				</div>
				<div class="admin-bot-lblock">
					<div class="admin-bot-rblock">
						<div class="admin-bot-cblock"></div>
					</div>
				</div>
			</div>
		</li>
	<?php endif; ?>
    <?php
		$controller = array('transactions', 'payment_gateways');
		$class = (in_array( $this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="no-bor<?php echo $class;?>">
		<span class="amenu-left">
			<span class="amenu-right">
				<span class="menu-center payments">
					<?php echo __l('Payments'); ?>
				</span>
			</span>
		</span>
		<div class="admin-sub-block">
			<div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="">							
                            <li>
								<h4><?php echo __l('Payments'); ?></h4>
								<ul>
                                    <li><?php echo $this->Html->link(__l('Transactions'), array('controller' => 'transactions', 'action' => 'index'), array('title' =>__l('Transactions'), 'escape' => false));?></li>									
									<li><?php echo $this->Html->link(__l('Payment Gateways'), array('controller' => 'payment_gateways', 'action' => 'index'), array('title' =>__l('Payment Gateways'), 'escape' => false));?></li>
									
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('settings');
		$class = (in_array( $this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="no-bar <?php echo $class;?>">
		<span class="amenu-left">
			<span class="amenu-right">
				<span class="menu-center settings admin-settings">
                    <?php echo __l('Settings'); ?>
                </span>
			</span>
		</span>
		<div class="admin-sub-block admin-sub-block2">
            <div class="admin-bot-rblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div class="admin-sub-rblock">
					<div class="admin-sub-cblock">
						<ul class="admin-sub-links clearfix">
							<li>
								<ul>
									<li class="setting-overview setting-overview1 clearfix"><?php echo $this->Html->link(__l('Setting Overview'), array('controller' => 'settings', 'action' => 'index'),array('title' => __l('Setting Overview'), 'class' => 'setting-overview grid_right')); ?></li>
									<li>
										<h4 class="setting-title"><?php echo __l('Settings'); ?></h4>
										<ul>
											<li class="admin-sub-links-left  grid_left">
												<ul>
													<li><?php echo $this->Html->link(__l('System'), array('controller' => 'settings', 'action' => 'edit', 1),array('title' => __l('System'))); ?></li>
													<li><?php echo $this->Html->link(__l('Developments'), array('controller' => 'settings', 'action' => 'edit', 2),array('title' => __l('Developments'))); ?></li>
													<li><?php echo $this->Html->link(__l('SEO'), array('controller' => 'settings', 'action' => 'edit', 3),array('title' => __l('SEO'))); ?></li>
													<li><?php echo $this->Html->link(__l('Regional, Currency & Language'), array('controller' => 'settings', 'action' => 'edit', 4),array('title' => __l('Regional, Currency & Language'))); ?></li>
													<li><?php echo $this->Html->link(__l('Account '), array('controller' => 'settings', 'action' => 'edit', 5),array('title' => __l('Account'))); ?></li>
													<li><?php echo $this->Html->link(__l('Photos'), array('controller' => 'settings', 'action' => 'edit', 8),array('title' => __l('Photos'))); ?></li>
													<li><?php echo $this->Html->link(__l('Videos'), array('controller' => 'settings', 'action' => 'edit', 9),array('title' => __l('Videos'))); ?></li>
													<li><?php echo $this->Html->link(__l('Events'), array('controller' => 'settings', 'action' => 'edit', 60),array('title' => __l('Events'))); ?></li>
												</ul>
         									</li>
											<li class="admin-sub-links-right grid_left">
												<ul>
													<li><?php echo $this->Html->link(__l('Suspicious Words Detector'), array('controller' => 'settings', 'action' => 'edit', 10),array('title' => __l('Suspicious Words Detector'))); ?></li>
													<li><?php echo $this->Html->link(__l('Messages'), array('controller' => 'settings', 'action' => 'edit', 11),array('title' => __l('Messages'))); ?></li>
													<li><?php echo $this->Html->link(__l('Friends'), array('controller' => 'settings', 'action' => 'edit', 12),array('title' => __l('Friends'))); ?></li>
													<li><?php echo $this->Html->link(__l('Banners'), array('controller' => 'settings', 'action' => 'edit', 13),array('title' => __l('Banners'))); ?></li>
													<li><?php echo $this->Html->link(__l('CDN'), array('controller' => 'settings', 'action' => 'edit', 55),array('title' => __l('CDN'))); ?></li>
													<li><?php echo $this->Html->link(__l('Third Party API'), array('controller' => 'settings', 'action' => 'edit', 15),array('title' => __l('Third Party API'))); ?></li>
													<li><?php echo $this->Html->link(__l('Module Manager'), array('controller' => 'settings', 'action' => 'edit', 16),array('title' => __l('Module Manager'))); ?></li>
													<li><?php echo $this->Html->link(__l('Revenue'), array('controller' => 'settings', 'action' => 'edit', 59),array('title' => __l('Revenue'))); ?></li>
												</ul>
											</li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
	<?php
		$controller = array('cities', 'states',  'countries', 'banned_ips', 'languages', 'translations', 'party_planners', 'contacts', 'pages', 'email_templates', 'music_types', 'bar_service_types', 'party_types', 'marital_statuses', 'venue_types', 'venue_sponsors', 'venue_categories', 'event_categories', 'event_sponsors', 'photo_flag_categories', 'video_categories', 'video_flag_categories', 'forum_categories', 'article_categories', 'contact_types');
		$class = (in_array($this->request->params['controller'], $controller)) ? ' active' : null;
	?>
	<li class="masters setting-masters-block masters-block no-bar<?php echo $class;?>">
		<span class="amenu-left">
			 <span class="amenu-right">
				 <span class="menu-center master2">
					 <?php echo __l('Masters'); ?>
				 </span>
			</span>
		 </span>
		 <div class="admin-sub-block">
			<div class="admin-bot-lblock1">
				<div class="admin-bot-cblock1"></div>
			</div>
			<div class="admin-sub-lblock">
				<div>
					<div class="admin-sub-cblock">
						<ul class="">
							<li>
                             	<div class="page-info master-page-info"><?php echo __l('Warning! Please edit with caution.'); ?></div>
								<ul class="clearfix">
									<li class="admin-sub-links-left">
										<h4><?php echo __l('Venues');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Venue Types'), array('controller' => 'venue_types', 'action' => 'index'), array('title' =>__l('Venue types') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Venue Categories'), array('controller' => 'venue_categories', 'action' => 'index'), array('title' =>__l('Venue Categories') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Venue Sponsors'), array('controller' => 'venue_sponsors', 'action' => 'index'), array('title' =>__l('Venue Sponsors'), 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Venue Features'), array('controller' => 'venue_features', 'action' => 'index'), array('title' =>__l('Venue Features'), 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Parking Types'), array('controller' => 'parking_types', 'action' => 'index'), array('title' =>__l('Parking Types'), 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Events');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Event Categories'), array('controller' => 'event_categories', 'action' => 'index'), array('title' =>__l('Event Categories') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Events Sponsors'), array('controller' => 'event_sponsors', 'action' => 'index'), array('title' =>__l('Events Sponsors'), 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Events Scenes'), array('controller' => 'event_scenes', 'action' => 'index'), array('title' =>__l('Events Scenes'), 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Age Requirements'), array('controller' => 'age_requirments', 'action' => 'index'), array('title' =>__l('Age Requirements'), 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Languages');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Languages'), array('controller' => 'languages', 'action' => 'admin_index'),array('title' => __l('Languages'))); ?></li>
											<li><?php echo $this->Html->link(__l('Translations'), array('controller' => 'translations', 'action' => 'admin_index'),array('title' => __l('Translations'))); ?></li>
										</ul>
									</li>
									<li class="admin-sub-links-left">
										<h4><?php echo __l('Party Planners');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Bar Service Types'), array('controller' => 'bar_service_types', 'action' => 'index'), array('title' =>__l('Bar Service Types') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Food Caterings'), array('controller' => 'food_caterings', 'action' => 'index'), array('title' =>__l('Food Caterings') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Party Types'), array('controller' => 'party_types', 'action' => 'index'), array('title' =>__l('Party Types') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Entertainments'), array('controller' => 'entertainments', 'action' => 'index'), array('title' =>__l('Entertainments') , 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Demographics');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Ethnicities'), array('controller' => 'ethnicities', 'action' => 'index'), array('title' =>__l('Ethnicities') , 'escape' => false));?></li>
           									<li><?php echo $this->Html->link(__l('Sexual Orientations'), array('controller' => 'sexual_orientations', 'action' => 'index'), array('title' =>__l('Sexual Orientations') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Body Types'), array('controller' => 'body_types', 'action' => 'index'), array('title' =>__l('Body Types') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Marital Statuses'), array('controller' => 'marital_statuses', 'action' => 'index'), array('title' =>__l('Marital statuses') , 'escape' => false));?></li>
           									<li><?php echo $this->Html->link(__l('Favorite Fashion Brand'), array('controller' => 'favorite_fashion_brands', 'action' => 'index'), array('title' =>__l('Favorite Fashion Brand') , 'escape' => false));?></li>
           									<li><?php echo $this->Html->link(__l('Cell Providers'), array('controller' => 'cell_providers', 'action' => 'index'), array('title' =>__l('Cell Providers') , 'escape' => false));?></li>
										</ul>
										<h4><?php echo __l('Static Pages');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Manage Static Pages'), array('controller' => 'pages', 'action' => 'admin_index'),array('title' => __l('Manage Static Pages'))); ?></li>
										</ul>
									</li>
									<li class="admin-sub-links-right">
										<h4><?php echo __l('Email Templates');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Email Templates'), array('controller' => 'email_templates', 'action' => 'admin_index'),array('title' => __l('Email Templates'))); ?></li>
										</ul>
										<h4><?php echo __l('Regional');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'admin_index'),array('title' => __l('Cities'))); ?></li>
											<li><?php echo $this->Html->link(__l('States'), array('controller' => 'states', 'action' => 'index'),array('title' => __l('States'))); ?></li>
											<li><?php echo $this->Html->link(__l('Countries'), array('controller' => 'countries', 'action' => 'admin_index'),array('title' => __l('Countries'))); ?></li>
											<li><?php echo $this->Html->link(__l('Banned IPs'), array('controller' => 'banned_ips', 'action' => 'admin_index'),array('title' => __l('Banned IPs'))); ?></li>
										</ul>
										<h4><?php echo __l('Others');?></h4>
										<ul>
											<li><?php echo $this->Html->link(__l('Music Types'), array('controller' => 'music_types', 'action' => 'index'), array('title' =>__l('Music Types') , 'escape' => false));?></li>
		                        			<li><?php echo $this->Html->link(__l('Photo Flag Categories'), array('controller' => 'photo_flag_categories', 'action' => 'index'), array('title' => __l('Photo Flag Categories'))); ?></li>
		                        			<li><?php echo $this->Html->link(__l('Video Categories'), array('controller' => 'video_categories', 'action' => 'index'), array('title' => __l('Video Categories'))); ?></li>
											<li><?php echo $this->Html->link(__l('Video Flag Categories'), array('controller' => 'video_flag_categories', 'action' => 'index'),array('title' => __l('Video Flag Categories'))); ?></li>
                        					<li><?php echo $this->Html->link(__l('Forum Categories'), array('controller' => 'forum_categories', 'action' => 'index'), array('title' =>__l('Forum Categories') , 'escape' => false));?></li>
                        					<li><?php echo $this->Html->link(__l('Article Categories'), array('controller' => 'article_categories', 'action' => 'index'), array('title' =>__l('Article categories') , 'escape' => false));?></li>
											<li><?php echo $this->Html->link(__l('Contact Types'), array('controller' => 'contact_types', 'action' => 'index'), array('title' =>__l('Contact types') , 'escape' => false));?></li>
										</ul>
									</li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="admin-bot-lblock">
				<div class="admin-bot-rblock">
					<div class="admin-bot-cblock"></div>
				</div>
			</div>
		</div>
	</li>
</ul>