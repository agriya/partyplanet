<?php $cur_page = $this->request->params['controller'] . '/' . $this->request->params['action']; ?>
<?php if ($cur_page == 'events/index' || $cur_page == 'events/week_events') { ?>
	<?php
		$caltype = !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : 'all';
		$sdate = !empty($this->request->params['named']['date']) ? $this->request->params['named']['date'] : '';
		$time_str = !empty($this->request->params['named']['time_str'])? $this->request->params['named']['time_str']:'';

	?>
	<div class="js-calendar-response">
		<?php echo $this->element('event_calendar', array('type' => $caltype, 'sdate' => $sdate, 'time_str'=>$time_str, 'cache' => array('key' => $this->Auth->user('id'), 'config' => '2sec'))); ?>
	</div>
	<?php echo $this->element('event_search', array('cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('popular_events', array('cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.event_page1'); ?></div>
	<div class="share-block"><?php echo Configure::read('banners.event_page2'); ?></div>
<?php } elseif ($cur_page == 'events/search') { ?>
	<?php
		$caltype = !empty($this->request->params['named']['type']) ? $this->request->params['named']['type'] : 'all';
		$sdate = !empty($this->request->params['named']['date']) ? $this->request->params['named']['date'] : '';
		$time_str = !empty($this->request->params['named']['time_str'])? $this->request->params['named']['time_str']:'';
	?>
	<div class="js-calendar-response">
		<?php echo $this->element('event_calendar', array('user_id' => $this->Auth->user('id'), 'type' => $caltype, 'sdate' => $sdate, 'time_str'=>$time_str, 'cache' => array('key' => $this->Auth->user('id'), 'config' => 'sec'))); ?>
	</div>
	<?php echo $this->element('popular_events', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.event_page1'); ?></div>
<?php } elseif ($cur_page == 'events/add' || $cur_page == 'venues/add' || $cur_page == 'venues/edit' || $cur_page == 'events/edit') { ?>
	<h3><?php echo __l('My'); ?><?php echo __l('Profile'); ?></h3>
	<?php echo $this->element('user_info', array('view' => 'home', 'username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => '2sec'))); ?>
	<h3><?php echo __l('My'); ?><?php echo __l('Friends'); ?></h3>
	<?php echo $this->element('user_friends-myfriends', array('status' => ConstUserFriendStatus::Approved, 'user_id' => $this->Auth->user('id'), 'cache' => array('key' => $this->Auth->user('id'), 'config' => '2sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.' . Inflector::singularize($this->request->params['controller']) . '_page1'); ?></div>
<?php } elseif ($cur_page == 'users/dashboard') { ?>
	<h3><?php echo __l('My Profile'); ?></h3>
	<?php echo $this->element('user_info', array('view' => 'home', 'username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => '2sec'))); ?>
	<h3><?php echo __l('My Friends'); ?></h3>
	<?php echo $this->element('user_friends-myfriends', array('status' => ConstUserFriendStatus::Approved, 'user_id' => $this->Auth->user('id'), 'cache' => array('key' => $this->Auth->user('id'), 'config' => '2sec'))); ?>
<?php } elseif ($cur_page == 'users/view') { ?>
	<div class="share-block"><?php echo Configure::read('banners.user_view_page1'); ?></div>
	<h3>
		<?php
			if(!empty($user['User']['username']) && $user['User']['username']== $this->Auth->user('username')):
				echo __l('My');
			else:
				echo $user['User']['username']; echo "'s";
			endif;
		?><?php echo ' ' . __l('Friends'); ?>
	</h3>
	<?php echo $this->element('user_friends-myfriends', array('status' => ConstUserFriendStatus::Approved, 'user_id' => $user['User']['id'], 'cache' => array('key' => $user['User']['id'], 'config' => 'sec'))); ?>
		<div  class="js-response"><?php echo $this->element('featured_events', array('cache' => array('config' => 'sec')));?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.user_view_page2'); ?></div>
<?php } elseif($cur_page == 'venues/index') { ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<div  class="js-response"><?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page2'); ?></div>
<?php } else if($cur_page == 'events/view') { ?>
	<div class="share-block"><?php echo Configure::read('banners.event_page1'); ?></div>
	<?php if (!empty($event)): ?>
		<?php echo $this->element('venue_info', array('event' => $event['Event']['slug'], 'cache' => array('config' => 'sec'))); ?>
		<?php echo $this->element('events_same-venue-list', array('event' => $event['Event']['slug'], 'cache' => array('config' => 'sec'))); ?>
		<?php echo $this->element('event_tags-index', array('event' => $event['Event']['slug'], 'cache' => array('config' => 'sec')));?>
	<?php endif; ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.event_page2'); ?></div>
<?php } elseif($cur_page == 'venues/view') { ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<?php if (!empty($venue)): ?>
		<?php echo $this->element('events_list', array('type' => 'venue', 'venue_id' => $venue['Venue']['id'], 'cache' => array('config' => 'sec'))); ?>
		<div class="js-response">
		<?php echo $this->element('venue_users', array('venue' => $venue['Venue']['slug'], 'type' => 'home', 'cache' => array('config' => 'sec'))); ?>
		</div>
		<h3><?php echo __l('Venues Near'); ?><span> <?php echo $this->Html->cText($venue['Venue']['name'], false); ?></span></h3>
		<?php echo $this->element('venue_index-near', array('venue_id' => $venue['Venue']['id'], 'cache' => array('key' => $venue['Venue']['id'], 'config' => '2sec'))); ?>
	<?php endif; ?>
	<h3><?php echo __l('Latest Photos') . ' '; ?> <span><?php echo __l('Galleries'); ?></span></h3>
	<?php echo $this->element('photo-albums-latest', array('cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page2'); ?></div>
<?php } else if($cur_page == 'venues/search') { ?>
	<?php echo $this->element('home_articles_index', array('type'=>'lst','cache' => array('config' => 'sec'))); ?>
	<h3><?php echo __l('Featured');?><?php echo __l(' Venues');?></h3>
	<?php echo $this->element('featured_venue', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
<?php } else if($cur_page == 'articles/view') { ?>
	<?php echo $this->element('home_articles_index', array('type' => 'lst', 'cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
<?php } else if($cur_page == 'links/index') { ?>
	<h3><?php echo __l('Latest Photos') . ' '; ?><?php echo __l('Galleries'); ?></h3>
	<?php
		echo $this->element('photo-albums-latest', array('cache' => array('config' => 'sec')));
		echo $this->element('user_index', array('cache' => array('config' => 'sec')));
		echo $this->element('facebook-like', array('cache' => array('config' => 'sec')));
	?>
<?php } elseif ($cur_page == 'photos/view') { ?>
	<?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<?php if ($this->Auth->user('username')) { ?>
		<?php echo $this->element('user_info', array('view'=>'home', 'username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => 'sec'))); ?>
	<?php } ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
<?php }	elseif (($cur_page == 'photos/index' || $cur_page == 'photos/add') && !empty($photoAlbum['Venue']['id'])) { ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<?php if (empty($this->request->params['named']['type'])): ?>
		<?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?>
		<h3><?php echo __l('Gallery is For'); ?></h3>
		<p><?php echo $this->Html->link($this->Html->cText($photoAlbum['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $photoAlbum['Venue']['slug'], 'admin' => false)); ?></p>
		<p><?php echo $this->Html->cText($photoAlbum['Venue']['address'], false); ?></p>
		<p>
			<?php
				if (!empty($photoAlbum['Venue']['City']['name'])):
					echo $this->Html->cText($photoAlbum['Venue']['City']['name'], false) . ', ';
				endif;
				if (!empty($photoAlbum['Venue']['zip_code'])):
					echo $this->Html->cText($photoAlbum['Venue']['zip_code'], false);
				endif;
			?>
		</p>
		<h3><?php echo __l('Gallery Created By') . ' '; ?></h3>
		<p class="userinfo"><span><?php echo $this->Html->link($this->Html->cText($photoAlbum['User']['username']), array('controller'=> 'users', 'action' => 'view', $photoAlbum['User']['username']), array('title'=>$photoAlbum['User']['username'],'escape' => false));?></span></p>
		<?php echo $this->element('user_info', array('username' => $photoAlbum['User']['username'], 'cache' => array('key' => $photoAlbum['User']['username'], 'config' => 'sec'))); ?>
		<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<?php endif; ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page2'); ?></div>
<?php }	elseif(($cur_page == 'photos/index' || $cur_page == 'photos/add') && !empty($photoAlbum['Event']['id'])) { ?>
	<?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?>
	<?php if($photoAlbum['Event']['is_cancel'] == 0) { ?>
		<h3><?php echo __l('Gallery is For'); ?></h3>
		<h4><?php echo $this->Html->link($this->Html->cText($photoAlbum['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $photoAlbum['Event']['slug'], 'admin' => false)); ?></h4>
		<p><?php echo $this->Html->cText($photoAlbum['Event']['description'], false); ?></p>
	<?php } ?>
	<h3><?php echo __l('Gallery Created By'); ?></h3>
	<?php echo $this->element('user_info', array('username' => $photoAlbum['User']['username'], 'cache' => array('key' => $photoAlbum['User']['username'], 'config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
<?php } else if($cur_page == 'photo_albums/index' || $cur_page == 'photo_albums/home') { ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page2'); ?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
<?php } else if($cur_page == 'users/index' || $cur_page == 'articles/index') { ?>
	<div class="share-block"><?php echo Configure::read('banners.article_page1'); ?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<?php echo $this->element('sponsor_events', array('type'=>'featured','cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.article_page2'); ?></div>
<?php } else if($cur_page == 'videos/home' || $cur_page == 'videos/index') {?>
	<div class="share-block"><?php echo Configure::read('banners.video_page1'); ?></div>
	<?php
		echo $this->element('video_upload_info', array('cache' => array('config' => 'sec')));
		echo $this->element('video_tags-index', array('cache' => array('config' => 'sec')));
		echo $this->element('video_category-index', array('cache' => array('config' => 'sec')));
	?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page2'); ?></div>
<?php } elseif ($cur_page == 'videos/view') { ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page1'); ?></div>
	<?php
		echo $this->element('recent-videos', array('cache' => array('config' => 'sec')));
		echo $this->element('video_tags-index', array('video_slug' => !empty($this->request->params['named']['slug']) ? $this->request->params['named']['slug'] : '', 'cache' => array('config' => 'sec')));
	?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page2'); ?></div>
<?php } elseif ($cur_page == 'videos/v') { ?>
	<?php
		echo $this->element('recent-videos', array('cache' => array('config' => 'sec')));
		echo $this->element('video_tags-index', array('video_slug' => $this->request->params['named']['slug'], 'cache' => array('config' => 'sec')));
		echo $this->element('../video_tags/add');
	?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
<?php } elseif ($cur_page == 'videos/index' || $cur_page == 'videos/index' && !empty($this->request->params['named']['username'])) { ?>
	<?php if ($this->Auth->sessionValid()): ?>
		<h3><?php echo __l('My'); ?> <?php echo __l('Profile'); ?></h3>
		<?php echo $this->element('user_info', array('view' => 'home', 'username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => '2sec'))); ?>
		<h3><?php echo __l('My'); ?><?php echo __l('Friends'); ?></h3>
		<?php echo $this->element('user_friends-myfriends', array('status' => ConstUserFriendStatus::Approved, 'user_id' => $this->Auth->user('id'), 'cache' => array('key' => $this->Auth->user('id'), 'config' => '2sec'))); ?>
	<?php endif; ?>
	<h3><?php echo __l('Like us on facebook'); ?></h3>
	<?php echo $this->Html->getFacebookLikeCode(); ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page1'); ?></div>
<?php } elseif ($cur_page == 'photo_albums/add' && empty($this->request->params['named']['venue_id']) && empty($this->request->params['named']['event_id']) && empty($this->request->params['named']['type'])) { ?>
	<div class="share-block"><?php echo Configure::read('banners.photo_page1'); ?></div>
	<h3><?php echo __l('Gallery is For'); ?></h3>
	<?php echo $this->element('user_info', array('username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => 'sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.photo_page2'); ?></div>
<?php } elseif ($cur_page == 'videos/add' && !empty($this->request->params['named']['event_id'])) { ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page1'); ?></div>
	<h3><?php echo __l('Video '); ?><?php echo __l('Is For'); ?></h3>
	<h4><?php echo $this->Html->link($this->Html->cText($event['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin' => false)); ?></h4>
	<p><?php echo $this->Html->cText($event['Event']['description'], false); ?></p>
	<h3><?php echo __l('Video') . ' '; ?><?php echo __l('Created By'); ?></h3>
	<?php echo $this->element('user_info', array('username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => '2sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page2'); ?></div>
<?php } elseif ($cur_page == 'videos/add' && !empty($this->request->params['named']['venue_id'])) { ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page1'); ?></div>
	<h3><?php echo __l('Video '); ?><?php echo __l('Is For'); ?></h3>
	<p><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'], 'admin' => false)); ?></p>
	<p><?php echo $this->Html->cText($venue['Venue']['address'], false); ?></p>
	<p><?php echo $this->Html->cText($venue['City']['name'], false) . ' ' . $this->Html->cText($venue['Venue']['zip_code'], false); ?></p>
	<h3><?php echo __l('Video') . ' '; ?><?php echo __l('Created By'); ?></h3>
	<?php echo $this->element('user_info', array('username' => $this->Auth->user('username'), 'cache' => array('key' => $this->Auth->user('username'), 'config' => '2sec'))); ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.video_page2'); ?></div>
<?php } elseif ($cur_page == 'photos/add') { ?>
	<?php echo $this->element('sponsor_events', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.photo_page1'); ?></div>
	<?php if (!empty($photoAlbum)): ?>
		<h3><?php echo __l('Gallery Created By'); ?></h3>
		<?php echo $this->element('user_info', array('username' => $photoAlbum['User']['username'], 'cache' => array('key' => $photoAlbum['User']['username'], 'config' => 'sec'))); ?>
	<?php endif; ?>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.photo_page2'); ?></div>
<?php } elseif ($cur_page == 'photo_albums/add' && (!empty($this->request->params['named']['venue_id']) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'venue'))) { ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page1'); ?></div>
	<h3><?php echo __l('Gallery is For'); ?></h3>
	<p><?php echo $this->Html->link($this->Html->cText($venue['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $venue['Venue']['slug'], 'admin' => false)); ?></p>
	<p><?php echo $this->Html->cText($venue['Venue']['address'], false); ?></p>
	<p><?php echo $this->Html->cText($venue['City']['name'], false) . ' ' . $this->Html->cText($venue['Venue']['zip_code'], false); ?></p>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.venue_page2'); ?></div>
<?php } elseif ($cur_page == 'photo_albums/add' && (!empty($this->request->params['named']['event_id']) || (!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'event'))) { ?>
	<div class="share-block"><?php echo Configure::read('banners.event_page1'); ?></div>
	<h3><?php echo __l('Gallery is For'); ?></h3>
	<h4><?php echo $this->Html->link($this->Html->cText($event['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $event['Event']['slug'], 'admin' => false)); ?></h4>
	<p><?php echo $this->Html->cText($event['Event']['description'], false); ?></p>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>\
	<div class="share-block"><?php echo Configure::read('banners.event_page2'); ?></div>
<?php } elseif ($cur_page != 'users/login' && $cur_page != 'users/joinus' || ($cur_page == 'cities/index' && isset($_prefixId))) { ?>
	<div class="share-block"><?php echo Configure::read('banners.home_page2'); ?></div>
	<?php echo $this->element('facebook-like', array('cache' => array('config' => 'sec'))); ?>
	<h3><?php echo __l('More News');?></h3>
	<?php echo $this->element('article_index', array('type'=>'home_more_news')); ?>
	<h3><?php echo __l('New Members');?></h3>
	<?php echo $this->element('user_index', array('cache' => array('config' => 'sec'))); ?>
	<div class="share-block"><?php echo Configure::read('banners.home_page1'); ?></div>
<?php } ?>