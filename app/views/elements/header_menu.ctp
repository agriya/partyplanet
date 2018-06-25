<ul class="menu clearfix">
    <li class="<?php echo (!empty($this->request->params['pass']) && $this->request->params['pass'][0] == 'home') ? 'active' : ''?>"><?php echo $this->Html->link(__l('Home'), Router::url('/',true));?></li>
      <li class="<?php echo ($this->request->params['controller'] == 'venues') ? 'active' : ''?>"><?php echo $this->Html->link(__l('Venues'), array('controller' => 'venues', 'action' => 'index', 'admin' => false), array('title' => __l('Venues'), 'escape' => false));?>
		
	</li>
	<li class="<?php echo $this->request->params['controller'] == 'events' ? 'active' : ''?>"><?php echo $this->Html->link(__l('Events'), array('controller' => 'events', 'action' => 'index', 'admin' => false), array('title'=>__l('Events'),'escape' => false));?>
		<ul class="sub-menu">
			<li><?php echo $this->Html->link(__l('Event Calendar'), array('controller' => 'events', 'action' => 'index', 'admin' => false), array('title' => __l('Event Calendar'), 'escape' => false));?></li>
			<li><?php echo $this->Html->link(__l('Guestlists'), array('controller' => 'events', 'action' => 'index', 'type' => 'guest', 'admin' => false), array('title' => __l('Guestlists'), 'escape' => false));?></li>
			<li><?php echo $this->Html->link(__l('Search by Type'), array('controller' => 'events', 'action' => 'search', 'type' => 'type', 'admin' => false), array('title' => __l('Search by Type'), 'escape' => false));?></li>
			<li><?php echo $this->Html->link(__l('Search by Location'), array('controller' => 'events', 'action' => 'search', 'type' => 'location', 'admin' => false), array('title' => __l('Search by Location'),'escape' => false));?></li>
			<li><?php echo $this->Html->link(__l('Add Your Event'), array('controller' => 'events', 'action' => 'add', 'admin' => false), array('title' => __l('Add Your Event'), 'escape' => false));?></li>
		</ul>
	</li>
	<li class="<?php echo ($this->request->params['controller'] == 'photo_albums') ? 'active photos-link' : 'photos-link'?>">
		<?php echo $this->Html->link(__l('Photos'), array('controller' => 'photo_albums', 'action' => 'index', 'admin' => false), array('title' => __l('Photos'), 'escape' => false));?>
		<?php echo $this->element('photo-menu', array('cache' => array('config' => '2sec'))); ?>
	</li>
	<?php if(Configure::read('site.is_enable_news_module')) {?>
	<li class="<?php echo $this->request->params['controller'] == 'articles' ? 'active' : ''?>"><?php echo $this->Html->link(__l('News'), array('controller' => 'articles', 'action' => 'index', 'admin' => false), array('title'=>__l('News'), 'escape' => false));?></li>
	<?php } ?>
	<?php if (Configure::read('site.is_enable_forum_module')) { ?>
		<li class="<?php echo ($this->request->params['controller'] == 'forum_categories' || ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'index')) ? 'active' : ''?>"><?php echo $this->Html->link(__l('Community'), array('controller' => 'users', 'action' => 'index', 'admin' => false), array('title'=>__l('Community'), 'escape' => false));?>
		    <ul class="sub-menu">
				<li><?php echo $this->Html->link(__l('Message Boards'), array('controller' => 'forum_categories', 'action' => 'index', 'admin' => false), array('title' => __l('Message Boards'), 'escape' => false));?></li>
				<li><?php echo $this->Html->link(__l('Party Planet Users'), array('controller' => 'users', 'action' => 'index', 'admin' => false), array('title' => __l('Party Planet Users'), 'escape' => false));?></li>
			</ul>
		</li>
	<?php } else { ?>
		<li class="<?php echo ($this->request->params['controller'] == 'users' && $this->request->params['action'] == 'index') ? 'active' : ''?>"><?php echo $this->Html->link(__l('Community'), array('controller' => 'users', 'action' => 'index','type' => 'users', 'admin' => false), array('title' => __l('Community'), 'escape' => false));?>
			<ul class="comm">
				<li><?php echo $this->Html->link(__l('Friends'), array('controller' => 'users', 'action' => 'index', 'admin' => false), array('title' => __l('Friends'), 'escape' => false));?></li>
			</ul>
		</li>
	<?php } ?>
	<?php if (Configure::read('Video.is_enable_video_module')): ?>
		<li class="<?php echo $this->request->params['controller'] == 'videos' ? 'active' : ''?>"><?php echo $this->Html->link(__l('Videos'), array('controller' => 'videos', 'action' => 'index', 'admin' => false), array('title'=>__l('Videos'),'escape' => false));?>
			<?php if (Configure::read('Video.is_allow_user_to_upload_video')): ?>
			     <ul class="sub-menu">
					<?php if($this->Auth->sessionValid()): ?>
					<li><?php echo $this->Html->link(__l('My videos'), array('controller' => 'videos', 'action' => 'index', 'username' => $this->Auth->user('username'), 'admin' => false), array('title' => __l('My Videos'), 'escape' => false));?></li>
					<?php endif;?>
					<li><?php echo $this->Html->link(__l('Upload Videos'), array('controller' => 'videos', 'action' => 'add', 'admin' => false), array('title' => __l('Upload Videos'), 'escape' => false));?></li>
					<li><?php echo $this->Html->link(__l('Search Videos'), array('controller' => 'videos', 'action' => 'index', 'type' => 'search', 'admin' => false), array('title' => __l('Search Videos'), 'escape' => false));?></li>
				</ul>
			<?php endif; ?>
		</li>
	<?php endif; ?>
	<li class="<?php echo $this->request->params['controller'] == 'party_planners' ? 'active' : ''?>"><?php echo $this->Html->link(__l('Plan Your Party'), array('controller' => 'party_planners', 'action' => 'add', 'admin' => false), array('title' => __l('Plan Your Party'), 'escape' => false));?></li>
	<li class="<?php echo ($this->request->params['controller'] == 'cities' and $this->request->params['action'] == 'index') ? 'active' : ''?>"><?php echo $this->Html->link(__l('Cities'), array('controller' => 'cities', 'action' => 'index', 'admin' => false), array('title' => __l('Cities'), 'escape' => false));?></li>
</ul>