<div id="breadcrumb">
	<?php
		if (!empty($photos[$count]['PhotoAlbum']['venue_id']) and !empty($photos[$count]['PhotoAlbum']['is_active']) and $photos[$count]['PhotoAlbum']['is_active']==1) {
			$this->Html->addCrumb(__l('Venues'), array('controller' => 'venues', 'action' => 'index'));
			$this->Html->addCrumb($this->Html->cText($photos[$count]['PhotoAlbum']['Venue']['name'], false), array('controller' => 'venues', 'action' => 'view', $photos[$count]['PhotoAlbum']['Venue']['slug']));
		} elseif (!empty($photos[$count]['PhotoAlbum']['event_id']) and $photos[$count]['PhotoAlbum']['is_active']==1) {
			$this->Html->addCrumb(__l('Events'), array('controller' => 'events', 'action' => 'index'));
			$this->Html->addCrumb($this->Html->cText($photos[$count]['PhotoAlbum']['Event']['title'], false), array('controller' => 'events', 'action' => 'view', $photos[$count]['PhotoAlbum']['Event']['slug']));
		}
	?>
	<?php $this->Html->addCrumb($this->Html->cText($photoAlbum['PhotoAlbum']['title'])); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
</div>
<h2><?php echo $this->Html->cText($photoAlbum['PhotoAlbum']['title']);?></h2>
<div class="photos prev-next-opt phots-view-block index">
	<?php if(!empty($this->request->params['named']['album']) && ($this->Auth->user('id') == $photoAlbum['PhotoAlbum']['user_id'] || $this->Auth->user('id') == ConstUserTypes::Admin)): ?>
		<div class="add-block1"><?php echo $this->Html->link(__l('Add More Photos'),array('controller'=>'photos','action'=>'add',$photoAlbum['PhotoAlbum']['id']),array('class'=> 'add', 'title'=>__l('Add More Photos'))); ?></div>
	<?php endif; ?>
	<?php if(!empty($photos)){ ?>
		<div class="form-content-block">
		<?php
			$current_page = !empty($this->request->params['named']['page']) ? $this->request->params['named']['page'] : '1';
			$prev_page = ($count == 1 && $current_page > 1) ? $current_page-1 : $current_page;
			$next_page = ($count % 10 == 0) ? $current_page +1 : $current_page;
			$photo_tag_class = '';
			$add_url = Router::url(array(
				'controller' => 'photos',
				'action' => 'face_addtag',
				$photos[$count-1]['Photo']['id'],
				'admin' => false
			) , true);
			$display_url = Router::url(array(
				'controller' => 'photos',
				'action' => 'face_diplaytag',
				$photos[$count-1]['Photo']['id'],
				'admin' => false
			) , true);
			$delete_url = Router::url(array(
				'controller' => 'photos',
				'action' => 'face_deletetag',
				'admin' => false
			) , true);
			if(empty($fb_session)) {
				$photo_tag_class = "photoTag {'add_url' : '" . $add_url . "', 'display_url' : '" . $display_url . "', 'delete_url' : '" . $delete_url . "', 'add_tag' : 'false'}";
				if(!empty($fb_login_url)):
					if (env('HTTPS')) {
						$fb_prefix_url = 'add-tag.png';
					} else {
						$fb_prefix_url = 'add-tag.png';
					} ?>
					<div class="add-block1">
    					<?php
    					echo $this->Html->link($this->Html->image($fb_prefix_url, array('alt' => __l('[Image: Facebook Connect]'), 'title' => __l('Facebook connect'))), $fb_login_url, array('escape' => false,'class' => 'facebook-link'));
                        ?>
                    </div>
                    <?php
            	endif;
			} else {
				$photo_tag_class = "photoTag {'add_url' : '" . $add_url . "', 'display_url' : '" . $display_url . "', 'delete_url' : '" . $delete_url . "', 'add_tag' : 'true'}";
			}
		?>
	
 	<div class="photos-center-block">
			<?php echo $this->Html->showImage('Photo', $photos[$count-1]['Attachment'], array('class' => $photo_tag_class, 'dimension' => 'view_page_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photos[$count-1]['Photo']['title'], false)), 'title' => $this->Html->cText($photos[$count-1]['Photo']['title'], false))); ?>
    		<?php if (!empty($neighbors)): ?>
			<div class="clearfix prev-next-opt1">
				<?php
					if (!empty($neighbors['next'])):
						echo $this->Html->link(__l('Prev'), array('controller' => 'photos', 'action' => 'index', 'album' => $this->request->params['named']['album'], 'photo' => $neighbors['next']['Photo']['slug'], 'page' => $prev_page), array('class' => 'prev1', 'title' => __l('Previous Photo')));
					endif;
					if (!empty($neighbors['prev'])):
						echo $this->Html->link(__l('Next'), array('controller' => 'photos', 'action' => 'index', 'album' => $this->request->params['named']['album'], 'photo' => $neighbors['prev']['Photo']['slug'], 'page' => $next_page), array('class' => 'next1', 'title' => __l('Next Photo')));
					endif;
				?>
			</div>
	       	<?php endif; ?>
		</div>
		<ol class="list clearfix gallery-list">
			<?php foreach($photos as $photo): ?>
				<li class="grid_left"><?php echo $this->Html->link($this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $this->request->params['named']['album'], 'photo' => $photo['Photo']['slug'], 'page' => $current_page), array('escape' => false)); ?></li>
			<?php endforeach; ?>
		</ol>
	</div>
	<?php
			echo $this->element('../photos/view', array('photo' => $photos[$count-1], 'count' => $count-1, 'cache' => array('config' => '2sec')));
		} else {
	?>
	<ol class="list clearfix">
		<li><p class="notice"><?php echo __l('No Photos available');?></p></li>
	</ol>
	<?php } ?>
</div>