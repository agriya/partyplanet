<?php /* SVN: $Id: view.ctp 1144 2010-02-25 11:25:26Z siva_063at09 $ */ ?>
<div class="videos view clearfix phots-view-block">
	<div class="side1-block">
		<div id="breadcrumb">
				<?php
					if (!empty($video['Venue']['name'])) {
					$this->Html->addCrumb(__l('Venues'), array('controller' => 'venues', 'action' => 'index'));
					$this->Html->addCrumb($this->Html->cText($video['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $video['Venue']['slug']));
				} elseif (!empty($video['Event']['title'])) {
					$this->Html->addCrumb(__l('Events'), array('controller' => 'events', 'action' => 'index'));
					$this->Html->addCrumb($this->Html->cText($video['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $video['Event']['slug']));
				}
			?>
			<?php $this->Html->addCrumb($this->Html->cText($video['Video']['title'], false)); ?>
			<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
		</div>
		<h2><?php echo $this->Html->cText($video['Video']['title']); ?></h2>

		<div class="form-content-block">
			<div class="photos-center-block">
			<?php
				if (!empty($video['Video']['uploaded_via']) && $video['Video']['uploaded_via'] == ConstUploadedVia::Embed):
					echo $video['Video']['embed_code'];
				else:
					echo $this->Html->link($video['Video']['title'], array('controller' => 'videos', 'action' => 'v', '?' => array('width' => '500', 'height' => '400', 'wmode' => 'transparent', 'allowfullscreen' => 'true', 'name' => 'video_player'), $video['Video']['slug'], 1 /* auto play */), array('class' => 'js-flash'));
				endif;
			?>
			</div>
		</div>
			<div class="form-content-block clearfix">
			<div class="clearfix">
					<ul class="share-list grid_right clearfix">
						<li>
							<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;text=<?php echo $video['Video']['title'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button" data-count="none"><?php echo __l('Tweet!');?></a>
							<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
						</li>
						<li class="article-fb-share">
							<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug']),true); ?>&amp;t=<?php echo $video['Video']['title']; ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
						</li>
					 </ul>
					<ul class="userprofile-link grid_left">
        			<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
        				<li>
        					<?php
        						if(!empty($video['VideoFavorite'])):
        							echo $this->Html->link(__l('Remove favorites'), array('controller' => 'video_favorites', 'action' => 'delete', $video['VideoFavorite'][0]['id']),array('class'=>'remove_favorite','title'=>__l('Remove favorites')));
        						else:
        							echo $this->Html->link(__l('Add as favorites'), array('controller' => 'video_favorites', 'action' => 'add', $video['Video']['id']),array('class' => 'add_favorite  {url:\''.$this->Html->url(array('controller' => 'video_favorites', 'action' => 'delete', $video['Video']['id'])).'\',class:\'remove_favorite\',currentclass:\'add_favorite\',text:\'Remove Favorites\'}', 'title'=>__l('Add as favorites')));
        						endif;
        					?>
        				</li>
        			<?php endif; ?>
        			<?php if ($video['Video']['user_id'] != $this->Auth->user('id')): ?>
        			<?php if (Configure::read('Video.is_enable_video_flags')): ?>
        				<li><?php echo $this->Html->link(__l('Flag this video'), array('controller'=> 'video_flags', 'action' => 'add', $video['Video']['id']), array('title' => __l('Flag this video'),'class'=>'js-colorbox')); ?></li>
        			<?php endif; ?>
        			<?php endif; ?>
        		</ul>
				</div>
			
     		<p class="meta clearfix">
    			<span class="meta-row author"><em title="<?php echo __l('Posted on '); ?>"><?php echo __l('Posted on'); ?></em>
    				<span><?php echo $this->Html->cDateTimeHighlight($video['Video']['created']); ?></span>
    			</span>
    			<span class="meta-row date"><em title="<?php echo __l('by'); ?>"><?php echo __l('by'); ?></em>
    				<span><?php echo $this->Html->link($this->Html->cText($video['User']['username']) , array('controller' => 'users', 'action' => 'view', $video['User']['username']) , array('escape' => false)); ?></span>
    			</span>
    		</p>
		<?php if (Configure::read('Video.is_enable_video_comments') && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Html->checkForVideoPrivacy('Video', $video['Video']['is_allow_to_embed'], $this->Auth->user('id'), $video['User']['username']))): ?>
			<p class="clearfix"><?php echo $this->Html->link(__l('Put this video on your website') , '#', array('class' => 'js-toggle-div {"divClass":"js-show-embed"}', 'title' => __l('Put this video on your website'))); ?></p>
			<div class="js-show-embed show-embed-block hide">
				<?php
                   	$embed_url= Router::url(array('controller' => 'videos', 'action' => 'view', $video['Video']['slug'],'city'=>$this->request->params['named']['city']), true);
                	$embed_autoplay_url = Router::url(array('controller' => 'videos', 'action' => 'v', $video['Video']['slug'],'city'=>$this->request->params['named']['city'],1), true);
					$embed_play_url = Router::url(array('controller' => 'videos', 'action' => 'v', $video['Video']['slug'],'city'=>$this->request->params['named']['city'], 0), true); ?>
					<div class="form-content-block">
					<?php echo $this->Form->create('Video', array('class' => 'normal'));
					echo $this->Form->input('embed_url', array('type' => 'textarea', 'value' => $embed_url, 'class' => 'js-embed-selectall', 'label' => __l('Copy & paste this URL into your webpage'), 'readonly' => 'readonly'));
					$embed_code = '<object width="425" height="344"><param name="movie" value="' . $embed_autoplay_url . '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="' . $embed_autoplay_url . '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>';
					echo $this->Form->input('embed_autoplay_code', array('type' => 'textarea', 'value' => $embed_code, 'class' => 'js-embed-selectall', 'label' => __l('Embed auto play code'), 'readonly' => 'readonly'));
					$embed_code = '<object width="425" height="344"><param name="movie" value="' . $embed_play_url . '"></param><param name="allowFullScreen" value="true"></param><param name="allowscriptaccess" value="always"></param><embed src="' . $embed_play_url . '" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="425" height="344"></embed></object>';
					echo $this->Form->input('embed_play_code', array('type' => 'textarea', 'value' => $embed_code, 'class' => 'js-embed-selectall', 'label' => __l('Embed code'), 'readonly' => 'readonly')); ?>
					<div class="submit-block clearfix">
    					<?php echo $this->Form->end(); ?>
                    </div>
                </div>
			</div>
    		<?php endif; ?>
    		<?php if (Configure::read('Video.is_enable_video_downloads') && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Html->checkForVideoPrivacy('Video', $video['Video']['is_allow_to_download'], $this->Auth->user('id'), $video['User']['username']))): ?>
    			<p class="clearfix"><?php echo $this->Html->link(__l('Download'), array('controller' => 'videos', 'action' => 'download', $video['Video']['slug']), array('title' => __l('Download'))); ?></p>
    		<?php endif; ?>
	
    	<div class="clearfix">
    				<?php if (Configure::read('Video.is_enable_video_downloads')): ?>
    			<span class="meta-row download-count"><span class="title"><?php echo __l('Downloads'); ?></span>
    				<span><?php echo $this->Html->cInt($video['Video']['video_download_count']); ?></span>
    			</span>
    		<?php endif; ?>
    		<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
    			<span class="meta-row favorite-count"><span class="title"><?php echo __l('Favorites'); ?></span>
    				<span><?php echo $this->Html->cInt($video['Video']['video_favorite_count']); ?></span>
    			</span>
    		<?php endif; ?>
    		<?php if (Configure::read('Video.is_enable_video_ratings')): ?>
    			<span class="meta-row rating-count"><span class="title"><?php echo __l('Ratings'); ?></span>
    				<span><?php echo $this->Html->cInt($video['Video']['video_rating_count']); ?></span>
    			</span>
    		<?php endif; ?>
    		<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
    			<?php if(Configure::read('Video.is_enable_video_flags')): ?>
    				<span class="meta-row flag-count"><span class="title"><?php echo __l('Flags'); ?></span>
    					<span><?php echo $this->Html->cInt($video['Video']['video_flag_count']); ?></span>
    				</span>
    			<?php endif; ?>
    		<?php endif; ?>
    	</div>
    	
      		<?php if (Configure::read('Video.is_enable_video_ratings') && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Html->checkForVideoPrivacy('Video', $video['Video']['is_allow_to_rating'], $this->Auth->user('id'), $video['User']['username']))): ?>
            	<div class="clearfix">
                	<h3 class="tags grid_left"><?php echo __l('Rate this video')?></h3>
        			<div class="js-rating-display grid_left">
        				<?php
        					$average_rating = (!empty($video['Video']['video_rating_count'])) ? ($video['Video']['total_ratings']/$video['Video']['video_rating_count']) : 0;
        					echo $this->element('_star-rating-video', array('video_id' => $video['Video']['id'], 'current_rating' => $average_rating, 'canRate' => ($video['Video']['user_id'] != $this->Auth->user('id')) ? 1 : 0));
        				?>
        			</div>
				</div>
		<?php endif; ?>
        
    	<?php if (Configure::read('Video.is_enable_video_tags')): ?>
    		<div class="clearfix">
    			<h3 class="tags grid_left"><?php echo __l('Tags'); ?></h3>
    			<ul class="tags clearfix grid_left">
    				<?php if (!empty($video['VideoTag'])) { ?>
    					<?php foreach($video['VideoTag'] As $video_tag) { ?>
    						<li><?php echo $this->Html->link($this->Html->cText($video_tag['name']) , array('controller' => 'videos', 'action' => 'tag', $video_tag['slug']) , array('escape' => false)); ?></li>
    					<?php } ?>
    				<?php } else { ?>
    					<li class="notice"><?php echo __l('No tags added'); ?></li>
    				<?php } ?>
    			</ul>
    		</div>
	   <?php endif; ?>
	<?php if ($video['User']['id'] == $this->Auth->user('id') || $this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
		<div class="clearfix">
			<?php echo $this->Html->link(__l('Edit') , array('action' => 'edit', $video['Video']['id']) , array('class' => 'edit js-edit', 'title' => __l('Edit'))); ?>
			<?php echo $this->Html->link(__l('Delete') , array('action' => 'delete', $video['Video']['id']) , array('class' => 'delete js-delete', 'title' => __l('Delete'))); ?>
		</div>
	<?php endif; ?>
	<?php if (!empty($video['Video']['description'])): ?>
	     <h3><?php echo __l('Video description');?></h3>
	       <p><?php echo $this->Html->cText($video['Video']['description']);?></p>
       <?php endif; ?>

    </div>
	<div class="form-content-block">
		<?php
			if (Configure::read('Video.is_enable_video_comments') && ($this->Auth->user('user_type_id') == ConstUserTypes::Admin || $this->Html->checkForVideoPrivacy('Video', $video['Video']['is_allow_to_comment'], $this->Auth->user('id'), $video['User']['username']))):
				echo $this->element('video_comments-index', array('cache' => array('config' => 'sec')));
		?>
		<?php 
				if($this->Auth->sessionValid()):
					echo $this->element('../video_comments/add');
				else:
		?>
					<div id="video-comments-login">
						<?php echo $this->Html->link(__l('Login'), array('controller' => 'users', 'action' => 'login', 'admin' => false)); ?><?php echo ' ' . __l('to leave a comment'); ?>
					</div>
		<?php
				endif;
			endif;
		?>
	</div>
	</div>
</div>
<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
<div class="admin-tabs-block form-content-block">
	<div class="js-tabs clearfix">
		<ul class="clearfix menu-tabs">
			<?php if (Configure::read('Video.is_enable_video_flags')): ?>
				<li><?php echo $this->Html->link(__l('Flags'), array('controller' => 'video_flags', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Flags'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<?php if (Configure::read('Video.is_enable_video_comments')): ?>
				<li><?php echo $this->Html->link(__l('Comments'), array('controller' => 'video_comments', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Comments'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<?php if (Configure::read('Video.is_enable_video_ratings')): ?>
				<li><?php echo $this->Html->link(__l('Ratings'), array('controller' => 'video_ratings', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Ratings'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<?php if (Configure::read('Video.is_enable_video_favorites')): ?>
				<li><?php echo $this->Html->link(__l('Favorites'), array('controller' => 'video_favorites', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Favorites'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<?php if (Configure::read('Video.is_enable_video_downloads')): ?>
				<li><?php echo $this->Html->link(__l('Downloads'), array('controller' => 'video_downloads', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Downloads'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<li><?php echo $this->Html->link(__l('Views'), array('controller' => 'video_views', 'action' => 'index', 'video' => $video['Video']['slug'], 'view' => 'simple', 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
		</ul>
	</div>
	</div>
<?php endif; ?>
