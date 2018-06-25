<?php /* SVN: $Id: index.ctp 948 2009-09-17 09:07:42Z boopathi_026ac09 $ */ ?>
<?php $this->Html->meta('rss', array('controller' => 'photos', 'action' => 'index', 'ext' => 'rss') , array('title' => 'RSS - ' . $this->pageTitle) , false); ?>
<div class="photos index">
	<h2>
			<?php
				if (!empty($this->request->params['named']['username'])):
					echo ucfirst($this->request->params['named']['username']) . '\'s photo';
				else:
					echo $this->Html->cText($this->pageTitle,false);
				endif;
			?>
		</h2>
		<?php if (!empty($this->request->params['named']['username']) && $this->Auth->user('username') == $this->request->params['named']['username']): ?>
			<p class="current-size"><?php echo sprintf(__l('You are currently using %s (%s%%) of your %s %s'), $used_size, $size_percentage, Configure::read('photo.allowed_photos_size'), Configure::read('photo.allowed_photos_size_unit')); ?></p>
		<?php endif; ?>
	<?php
	
	if(isset($this->request->params['named']['type']) && $this->request->params['named']['type'] == 'search'): ?>
		 <div class="form-content-block">
			<?php echo $this->Form->create('Photo' , array('type' => 'get', 'class' => 'normal','action' => 'index'.'/type:search')); ?>
				<div class="filter-section">
					<div>
						<?php echo $this->Form->input('q', array('label' => 'Keyword')); ?>
					</div>
					<div>
						<div class="submit-block clearfix">
							<?php echo $this->Form->submit(__l('Search'));?>
						</div>
					</div>
				</div>
			<?php echo $this->Form->end(); ?>
		</div>
	<?php else: 
	
		if((!empty($this->request->params['named']['type']) && $this->request->params['named']['type'] != 'home') || empty($this->request->params['named']['type'])):
	?>
	   <ul class="add-venue-list photo-add-venue-list clearfix">
			<li>
				<?php
					if (!empty($this->request->params['named']['tag']) && !empty($this->request->params['named']['username'])):
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'tag' => $this->request->params['named']['tag'], 'username' => $this->request->params['named']['username'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
					elseif (!empty($this->request->params['named']['username'])):
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'username' => $this->request->params['named']['username'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
					elseif (!empty($this->request->params['named']['album']) && Configure::read('photo.is_allow_photo_album')):
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'album' => $this->request->params['named']['album'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
					elseif (!empty($this->request->params['named']['tag']) && Configure::read('photo.is_allow_photo_tag')):
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'tag' => $this->request->params['named']['tag'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => __l('Subscribe to ') . $this->pageTitle));
					elseif (!empty($this->request->params['named']['favorite'])):
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'favorite' => $this->request->params['named']['favorite'], 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => __l('Subscribe to ') . $this->pageTitle));
					else:
						echo $this->Html->link('RSS', array('controller' => 'photos', 'action' => 'index', 'ext' => 'rss') , array('target' => '_blank', 'class' => 'rss-link', 'title' => sprintf(__l('Subscribe to "%s"'), $this->pageTitle)));
					endif;
				?>
			</li>
			<?php if(!empty($this->request->params['named']['album']) && ($this->Auth->user('id') == $photoAlbum['PhotoAlbum']['user_id'] || $this->Auth->user('id') == ConstUserTypes::Admin)): ?>
			<li>
				<?php echo $this->Html->link(__l('Add more photos'), array('controller' => 'photos', 'action' => 'add', 'album' => $photoAlbum['PhotoAlbum']['slug']),array('title' => __l('Add more photos'))); ?>
			</li>
				<?php endif; ?>
			<li>
				<?php echo $this->Html->link(__l('Most viewed'), array('controller' => 'photos', 'action' => 'index', 'most' => ConstURLFilter::Viewed), array('title' => __l('Most viewed'), 'escape' => false));?>
			</li>
			<?php if (Configure::read('photo.is_allow_photo_comment')): ?>
				<li><?php echo $this->Html->link(__l('Most commented'), array('controller' => 'photos', 'action' => 'index', 'most' => ConstURLFilter::Commented), array('title' => __l('Most commented'), 'escape' => false)); ?>
				</li>
			<?php endif; ?>
			<?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
				<li><?php echo $this->Html->link(__l('Most favorited'), array('controller' => 'photos', 'action' => 'index', 'most' => ConstURLFilter::Favorited), array('title' => __l('Most favorited'), 'escape' => false)); ?></li>
			<?php endif; ?>
			<?php if (Configure::read('photo.is_allow_photo_flag')): ?>
				<li><?php echo $this->Html->link(__l('Most flagged'), array('controller' => 'photos', 'action' => 'index', 'most' => ConstURLFilter::Flagged), array('title' => __l('Most flagged'), 'escape' => false));	?></li>
			<?php endif; ?>
			<?php if (Configure::read('photo.is_allow_photo_rating')): ?>
				<li><?php echo $this->Html->link(__l('Most rated'), array('controller' => 'photos', 'action' => 'index', 'most' => ConstURLFilter::Rated), array('title' => __l('Most rated'), 'escape' => false));	?></li>
			<?php endif; ?>
		</ul>
	<?php endif;
	endif;
	?>
	<div class="clearfix photo-page_counter">
		<?php echo $this->element('paging_counter');?>
	</div>
	<ol class="list feature-list clearfix">
        <?php
			if (!empty($photos)):
				$i = 0;
				foreach ($photos as $photo):
					$class = null;
					if ($i++ % 2 == 0) :
						$class = 'altrow';
					endif;
		?>
			<li class=" clearfix <?php echo $class;?>">
			
					<div class="grid_4 alpha">
						<?php echo $this->Html->link($this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'home_newest_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug'], 'photo' => $photo['Photo']['slug']), array('escape' => false));?>
					</div>
					<div class="grid_11 omega ">
					<h3><?php echo $this->Html->link($this->Html->cText($photo['Photo']['title']), array('controller' => 'photos', 'action' => 'view', $photo['Photo']['slug']), array('escape' => false))?></h3>
					<?php if (Configure::read('photo.is_allow_photo_album') && isset($photo['PhotoAlbum']['title'])): ?>
				    	<p class="photo-title"><?php echo $this->Html->link($photo['PhotoAlbum']['title'], array('controller' => 'photos', 'action' => 'index', 'album' => $photo['PhotoAlbum']['slug']), array('title' => $photo['PhotoAlbum']['title']));?></p>
					<?php endif; ?>
					<p class="posted-info">by <?php echo $this->Html->link($this->Html->cText($photo['User']['username']), array('controller'=> 'users', 'action' => 'view', $photo['User']['username']), array('escape' => false));?> on <?php echo $this->Html->cDateTimeHighlight($photo['Photo']['created']);?></p>
					<p><?php echo $this->Html->cText($this->Html->truncate($photo['Photo']['description']));?></p>
					<dl class="photo-details clearfix">
	       				<dt class="view"><?php echo __l('Views:');?></dt>
						<dd><?php echo $this->Html->cInt($photo['Photo']['photo_view_count']);?></dd>
  	         			<?php if (Configure::read('photo.is_allow_photo_comment')): ?>
    						<dt class="comments"><?php echo __l('Comments:');?></dt>
    						<dd><?php echo $this->Html->cInt($photo['Photo']['photo_comment_count']);?></dd>
   						<?php endif; ?>
					
						<?php if (Configure::read('photo.is_allow_photo_favorite')): ?>
    						<dt class="favorite"><?php echo __l('Favorites:');?></dt>
							<dd><?php echo $this->Html->cInt($photo['Photo']['photo_favorite_count']);?></dd>
 	  					<?php endif; ?>
						<?php if (Configure::read('photo.is_allow_photo_flag')): ?>
      						<dt class="flags"><?php echo __l('Flags:');?></dt>
							<dd><?php echo $this->Html->cInt($photo['Photo']['photo_flag_count']);?></dd>
  						<?php endif; ?>
     				</dl>
     					<?php if (Configure::read('photo.is_allow_photo_rating')): ?>
                        <div class="clearfix">
	       	               <h3 class="tags grid_left"><?php echo __l('Ratings:');?></h3>
        			         <div class="js-rating-display grid_left">
                			     <?php echo $this->element('_star-rating', array('current_rating' => round($photo[0]['avg_rating'], 2), 'canRate' => false)); ?>
                            </div>
			             </div>
			         	<?php endif; ?>
						
						<?php if (Configure::read('photo.is_allow_photo_tag')): ?>
							<div class="tag-block">
								<h3 class="tags grid_left"><?php echo __l('Tags');?></h3>
								<ul class="tags grid_left clearfix">
									<?php
										if (!empty($photo['PhotoTag'])):
											$taglink = array();
											if (!empty($this->request->params['named']['username'])):
												$taglink = array('username' => $photo['User']['username']);
											endif;
											foreach($photo['PhotoTag'] As $photo_tag):
									?>
												<li><?php echo $this->Html->link($this->Html->cText($photo_tag['name']), array_merge($taglink, array('controller' => 'photos', 'action' => 'index', 'tag' => $photo_tag['slug'])), array('escape' => false));?></li>
									<?php
											endforeach;
										else:
									?>
											<li class="notice"><p><?php echo __l('No tags added');?></p></li>
									<?php
										endif;
									?>
								</ul>
							</div>
						<?php endif; ?>
						<?php if ($photo['User']['id'] == $this->Auth->user('id')): ?>
					
								<?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $photo['Photo']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
								<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $photo['Photo']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
						
						<?php endif; ?>
					</div>
		
			</li>
			<?php
					endforeach;
				else:
			?>
			<li class="no-record notice-info">
				<p class="notice"><?php echo __l('No Photos available');?></p>
			</li>
			<?php
				endif;
			?>
			</ol>

	
	<div class="clearfix">
		<?php
			if (!empty($photos)):
				echo $this->element('paging_links');
			endif;
		?>
	</div>
</div>

	<?php
	if (!empty($photos)):
		echo $this->element('photo_tags-index', array('cache' => array('config' => 'sec'))); 
	endif;
	?>
