<?php $this->pageTitle = __l('Home'); ?>
<div class="section1 grid_12 alpha">
	<h2><?php echo __l('Upcoming Events'); ?></h2>
	<div class="clearfix js-response"><?php echo $this->element('home_week_events', array('key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?></div>
	<h2> <?php echo __l('Newest photos'); ?></h2>
	<?php echo $this->element('photo-albums-index', array('key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec'))); ?>
   <?php if(Configure::read('Video.is_enable_video_module')):?>
	<h2><?php echo __l('Newest videos'); ?></h2>
	<?php echo $this->element('video-index', array('type'=>'home', 'key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?>
    <?php endif;?>
	<h2><?php echo __l('Newest Venue Listings'); ?></h2>
	<?php echo $this->element('venue_home_index', array('type'=>'home_newest', 'key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?>
	<div class="view-all-links">
		<span><?php echo $this->Html->link(__l('View More'), array('controller' => 'venues', 'action' => 'index'), array('escape' => false)); ?></span>
	</div>
</div>
<div class="section2 grid_5 omega">
	<div class="featured-venue-block">
		<h2><?php echo __l('Featured Events'); ?></h2>
		<?php echo $this->element('event-home-index', array('type' => 'home-featured', 'key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?>
		<div class="view-all-links">
			<span><?php echo $this->Html->link(__l('View More'), array('controller' => 'events', 'action' => 'index'), array('escape' => false)); ?></span>
		</div>
	</div>
	<div class="featured-venue-block">
		<h2><?php echo __l('Featured Venues'); ?></h2>
		<?php echo $this->element('venue_home_index', array('type' => 'home', 'key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?>
		<div class="view-all-links">
			<span><?php echo $this->Html->link(__l('View More'), array('controller' => 'venues', 'action' => 'index','type'=>'featured-all'), array('escape' => false)); ?></span>
		</div>
	</div>
	<div class="featured-venue-block">
		<h2><?php echo __l('Hot News'); ?></h2>
		<?php echo $this->element('article_index', array('type' => 'most_comment', 'view' => 'home', 'key' => $this->request->params['named']['city'], 'cache' => array('config' => '2sec')));?>
		<div class="view-all-links">
			<span><?php echo $this->Html->link(__l('View More'), array('controller' => 'articles', 'action' => 'index'), array('escape' => false)); ?></span>
		</div>
	</div>
</div>