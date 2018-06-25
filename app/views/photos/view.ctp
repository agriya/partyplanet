<?php /* SVN: $Id: view.ctp 959 2009-09-18 14:08:40Z boopathi_026ac09 $ */ ?>
<div class="photos view phots-view-block">
	<?php if (!isset($count)): ?>
		<h2><?php echo $this->Html->cText($photo['Photo']['title']);?></h2>
	<?php endif; ?>
	<div class="js-corner round-5">
		<?php if (!isset($count)): ?>
            
				<?php 
					$photo_tag_class = "";
					$add_url = Router::url(array(
							'controller' => 'photos',
							'action' => 'face_addtag',
							$photo['Photo']['id'],
							'admin' => false
						) , true);
					$display_url = Router::url(array(
							'controller' => 'photos',
							'action' => 'face_diplaytag',
							$photo['Photo']['id'],
							'admin' => false
						) , true);
					$delete_url = Router::url(array(
							'controller' => 'photos',
							'action' => 'face_deletetag',
							'admin' => false
						) , true);
						if(empty($fb_session)) {
							$photo_tag_class = "photoTag {'add_url' : '" . $add_url . "', 'display_url' : '" . $display_url . "', 'delete_url' : '" . $delete_url . "', 'add_tag' : 'false'}";
				?>		
							<?php if(!empty($fb_login_url)):  ?>
								<?php  if (env('HTTPS')) { $fb_prefix_url = 'add-tag.png';}else{ $fb_prefix_url = 'add-tag.png';}?>
                      
                                      <?php echo $this->Html->link($this->Html->image($fb_prefix_url, array('alt' => __l('[Image: Facebook Connect]'), 'title' => __l('Facebook connect'))), $fb_login_url, array('escape' => false,'class'=>'facebook-link')); ?>
                           
                                 <?php endif; ?>
				<?php
						} else {
							$photo_tag_class = "photoTag {'add_url' : '" . $add_url . "', 'display_url' : '" . $display_url . "', 'delete_url' : '" . $delete_url . "', 'add_tag' : 'true'}";
						}
				?>
              
		
			<div class="form-content-block">
    			<div class="photos-center-block">
    				<?php
    					echo $this->Html->showImage('Photo', $photo['Attachment'], array('class' => $photo_tag_class, 'dimension' => 'view_page_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false)));
    				?>
                </div>
        	</div>
			<?php endif; ?>
		
			<div class="event-option form-content-block clearfix">
			<div class="clearfix">
				<ul class="share-list grid_right clearfix">
					<li>
						<a href="http://twitter.com/share?url=<?php echo Router::url(array('controller' => 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug'], 'photo' => $photo['Photo']['slug']),true); ?>&amp;text=<?php echo $photo['Photo']['title'];?>&amp;lang=en&amp;via=<?php echo Configure::read('site.name'); ?>" class="twitter-share-button" data-count="none"><?php echo __l('Tweet!');?></a>
						<script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>
					</li>
					<li class="article-fb-share">
						<a href="http://www.facebook.com/sharer.php?u=<?php echo Router::url(array('controller' => 'photos', 'action' => 'index', 'album'=> $photo['PhotoAlbum']['slug'], 'photo' => $photo['Photo']['slug']),true); ?>&amp;t=<?php echo $photo['PhotoAlbum']['slug'].' - '.$photo['Photo']['title'] ; ?>" target="_blank" class="fb-share-button"><?php echo __l('fbshare'); ?></a>
					</li>
				</ul>
			<ul class="userprofile-link grid_left">
				<?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
					<li>
						<?php
							if (!empty($photo['PhotoFavorite'])):
								echo $this->Html->link(__l('Remove favorites'), array('controller' => 'photo_favorites', 'action' => 'delete', $photo['PhotoFavorite'][0]['id']),array('class'=>'remove_favorite','title'=>__l('Remove favorites')));
							else:
								echo $this->Html->link(__l('Add as favorites'), array('controller' => 'photo_favorites', 'action' => 'add', $photo['Photo']['id']),array('class' => 'add_favorite  {url:\''.$this->Html->url(array('controller' => 'photo_favorites', 'action' => 'delete', $photo['Photo']['id'])).'\',class:\'remove_favorite\',currentclass:\'add_favorite\',text:\'Remove Favorites\'}', 'title'=>__l('Add as favorites')));
							endif;
						?>
					</li>
				<?php endif; ?>
					<?php if ($photo['Photo']['user_id'] != $this->Auth->user('id')): ?>
				<?php if (Configure::read('photo.is_allow_photo_flag')):?>
           			<li><?php echo $this->Html->link(__l('Flag this photo'), array('controller'=> 'photo_flags', 'action' => 'add', $photo['Photo']['id']), array('title' => __l('Flag this photo'),'class'=>'js-colorbox')); ?></li>
                    <?php endif; ?>
                    <?php endif; ?>
			</ul>
			</div>
			<div class="meta-row date">
				<em title="<?php echo __l('Posted on');?>"><?php echo __l('Posted on');?></em>
				<?php echo $this->Html->cDateTimeHighlight($photo['Photo']['created']);?>
				<em title="<?php echo __l('By');?>"><?php echo __l('By');?></em>
				<?php echo $this->Html->link($this->Html->cText($photo['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $photo['User']['username']), array('escape' => false,'title' => $this->Html->cText($photo['User']['username'], false)));?>
				<div>
					<?php echo sprintf(__l('View more of %s\'s photos in %s'), $this->Html->link($this->Html->cText($photo['User']['username'], false), array('controller'=> 'users', 'action' => 'view', $photo['User']['username']), array('escape' => false)), $this->Html->link(Configure::read('site.name'), array('controller'=> 'users', 'action' => 'view', $photo['User']['username'], '#favorite-events'), array('escape' => false)));?>
				</div>
			</div>
		
				<?php if (Configure::read('photo.is_allow_photo_comment')): ?>
					<span class="meta-row comment-count">
						<em title="<?php echo __l('Comments');?>"><?php echo __l('Comments');?></em>
						<?php echo $this->Html->cInt($photo['Photo']['photo_comment_count']);?>
					</span>
				<?php endif; ?>
				<span class="meta-row view-count">
					<em title="<?php echo __l('Views');?>"><?php echo __l('Views');?></em>
					<?php echo $this->Html->cInt($photo['Photo']['photo_view_count']);?>
				</span>
				<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
					<?php if (Configure::read('photo.is_allow_photo_flag')): ?>
						<span class="meta-row flag-count">
							<em title="<?php echo __l('Flags');?>"><?php echo __l('Flags');?></em>
							<?php echo $this->Html->cInt($photo['Photo']['photo_flag_count']);?>
						</span>
					<?php endif; ?>
				<?php endif; ?>
		
   
	
   			<?php if ($photo['Photo']['user_id'] == $this->Auth->user('id')): ?>
				<div class="clearfix add-block1">
					<?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $photo['Photo']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
					<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $photo['Photo']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
				</div>
			<?php endif; ?>
            </div>
		  <div class="event-option form-content-block clearfix">
		  <?php if (!empty($photo['Photo']['description'])): ?>
            	<h3><span class="title"><?php echo __l(' Photo description');?></span></h3>
        	   <?php echo $this->Html->cText($photo['Photo']['description']);?>
            <?php endif; ?>
            <?php if(Configure::read('photo.is_allow_photo_rating')): ?>
            <div class="clearfix">
	       	<h3 class="tags grid_left"><?php echo __l('Rate this photo')?></h3>
			<div class="js-rating-display grid_left">
				<?php
					$average_rating = (!empty($photo['Photo']['photo_rating_count'])) ? ($photo['Photo']['total_ratings']/$photo['Photo']['photo_rating_count']) : 0;
					echo $this->element('_star-rating', array('photo_id' => $photo['Photo']['id'], 'current_rating' => $average_rating, 'canRate' => ($photo['Photo']['user_id'] != $this->Auth->user('id')) ? 1 : 0));
				?>
			</div>
			</div>
    	<?php endif; ?>

			<?php if (Configure::read('photo.is_allow_photo_tag')): ?>
			<div class="clearfix">
 				<h3 class="tags grid_left"><?php echo __l('Tags');?></h3>
					<ul class="tags grid_left clearfix">
						<?php
							if (!empty($photo['PhotoTag'])) :
								foreach($photo['PhotoTag'] As $photo_tag) :
						?>
							<li><?php echo $this->Html->link($this->Html->cText($photo_tag['name']), array('controller' => 'photos', 'action' => 'index', 'tag' => $photo_tag['slug']), array('escape' => false));?></li>
						<?php
								endforeach;
							else :
						?>
							<li><p class="notice"><?php echo __l('No tags added');?></p></li>
						<?php
							endif;
						?>
					</ul>
				</div>
			
			<?php endif; ?>
		</div>
	</div>
	<?php
		if (Configure::read('photo.is_allow_photo_comment')): ?>
        <div class=" form-content-block clearfix">
    	<?php
			echo $this->element('photo_comments-index', array('photo_id' => $photo['Photo']['id'], 'cache' => array('config' => 'sec', 'key' => $photo['Photo']['id'])));
        ?>
        </div>
        <?php
        	echo $this->element('../photo_comments/add', array('cache' => array('config' => 'sec', 'key' => $photo['Photo']['id'])));
		endif;
	?>
	<?php if($this->Auth->user('user_type_id') == ConstUserTypes::Admin): ?>
	<div class="admin-tabs-block form-content-block">
		<div class="js-tabs">
			<ul class="clearfix menu-tabs">
				<?php if (Configure::read('photo.is_allow_photo_comment')): ?>
					<li><?php echo $this->Html->link(__l('Comments'), array('controller' => 'photo_comments', 'action' => 'index', 'photo' => $photo['Photo']['slug'], 'admin' => true), array('title' => __l('Comments'), 'escape' => false)); ?></li>
				<?php endif; ?>
				<?php if (Configure::read('photo.is_allow_photo_rating')): ?>
					<li><?php echo $this->Html->link(__l('Ratings'), array('controller' => 'photo_ratings', 'action' => 'index', 'photo' => $photo['Photo']['slug'], 'admin' => true), array('title' => __l('Ratings'), 'escape' => false)); ?></li>
				<?php endif; ?>
				<li><?php echo $this->Html->link(__l('Views'), array('controller' => 'photo_views', 'action' => 'index', 'photo' => $photo['Photo']['slug'], 'admin' => true), array('title' => __l('Views'), 'escape' => false)); ?></li>
				<?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
					<li><?php echo $this->Html->link(__l('Favorites'), array('controller' => 'photo_favorites', 'action' => 'index', 'photo' => $photo['Photo']['slug'], 'admin' => true), array('title' => __l('Favorites'), 'escape' => false)); ?></li>
				<?php endif; ?>
				<?php if (Configure::read('photo.is_allow_photo_flag')): ?>
					<li><?php echo $this->Html->link(__l('Flags'), array('controller' => 'photo_flags', 'action' => 'index', 'photo' => $photo['Photo']['slug'], 'admin' => true), array('title' => __l('Flags'), 'escape' => false)); ?></li>
				<?php endif; ?>
			</ul>
		</div>
		</div>
	<?php endif; ?>
</div>