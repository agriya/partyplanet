<?php /* SVN: $Id: index.ctp 620 2009-07-14 14:04:22Z boopathi_23ag08 $ */ ?>
<div class="photoAlbums index content-block">
		<h2><?php echo __l('Albums');?></h2>
		<?php if($this->Auth->sessionValid()): ?>
			 <div class="add-block1 clearfix">
			     <?php	echo $this->Html->link(__l('Add more albums'), array('controller' => 'photo_albums', 'action' => 'add'), array('class' => 'add', 'title' => __l('Add more albums')));?>
           	</div>
       	<?php endif; ?>
		<?php echo $this->element('paging_counter');?>
		<ol  class="list photo-list clearfix">
			<?php
				if (!empty($photoAlbums)):
					$i = 0;
					foreach ($photoAlbums as $photoAlbum):
						$class = null;
						if ($i++ % 2 == 0) :
							$class = 'altrow';
						endif;
						$album_defalut_image = isset($photoAlbum['Photo'][0]['Attachment']) ? $photoAlbum['Photo'][0]['Attachment'] : array();
			?>
					<li class="grid_4 alpha omega">
					
							<p><?php echo $this->Html->cDateTime($photoAlbum['PhotoAlbum']['captured_date']); ?></p>
							<h3><?php echo $this->Html->link(__l(sprintf('%s',$photoAlbum['PhotoAlbum']['title'])), array('controller' => 'photos','action' => 'index', 'album'=> $photoAlbum['PhotoAlbum']['slug']), array('escape' => false))?></h3>
							<?php if ($photoAlbum['User']['id'] == $this->Auth->user('id')) : ?>
								<div>
									<?php echo $this->Html->link(__l('Edit'), array('action'=>'edit', $photoAlbum['PhotoAlbum']['id']), array('class' => 'edit js-edit', 'title' => __l('Edit')));?>
									<?php echo $this->Html->link(__l('Delete'), array('action'=>'delete', $photoAlbum['PhotoAlbum']['id']), array('class' => 'delete js-delete', 'title' => __l('Delete')));?>
								</div>
							<?php endif; ?>
								<p><?php echo __l('City: '); ?></p>
	           					<p><?php echo $this->Html->cText($photoAlbum['User']['UserProfile']['City']['name']); ?></p>
      					
					</li>
			<?php
					endforeach;
				else:
			?>
					<li class="notice-info">
						<p class="notice"><?php echo __l('No Photo Albums available');?></p>
					</li>
			<?php
				endif;
			?>
		</ol>
		<?php
			if (!empty($photoAlbums)) :
				echo $this->element('paging_links');
			endif;
		?>

</div>