<?php /* SVN: $Id: index.ctp 620 2009-07-14 14:04:22Z boopathi_23ag08 $ */ ?>
<?php
if($this->request->params['named']['type'] == 'latest'){
     $dimension = "sidebar_thumb";
      $li_class ="clearfix ";
      $ol_class = "list feature-list clearfix";
    }else{
    $dimension = "home_newest_thumb";
    $li_class ="grid_4 alpha omega";
    $ol_class ="list photo-list clearfix";
    }?>
<div class="photoAlbums index clearfix">  
	<ol class="<?php echo $ol_class;?>">
	<?php
	if (!empty($photoAlbums)):
	$i = 0;
	foreach ($photoAlbums as $photoAlbum):
	$album_defalut_image = isset($photoAlbum['Photo'][0]['Attachment']) ? $photoAlbum['Photo'][0]['Attachment'] : array();
	?>
		<li class="<?php echo $li_class;?>">
		<?php 	if($this->request->params['named']['type'] == 'latest'):?>
   <div class="grid_3 alpha omega">
   <?php endif;?>
			<?php
			echo $this->Html->link($this->Html->showImage('Photo', $album_defalut_image, array('dimension' => $dimension, 'alt' => sprintf('[Image: %s]', $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false)), 'title' => $this->Html->cText($photoAlbum['PhotoAlbum']['title'], false))), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']), array('escape' => false));
			?>
			<h3>
   <?php 	if($this->request->params['named']['type'] == 'latest'):?>
     </div>
       <div class="grid_5 alpha omega">
       <?php endif;?>
                <?php echo $this->Html->link($this->Html->truncate($this->Html->cText($photoAlbum['PhotoAlbum']['title'], false),20), array('controller' => 'photos', 'action' => 'index', 'album' => $photoAlbum['PhotoAlbum']['slug']), array('escape' => false)); ?>
                </h3>
    			<p><?php echo $this->Html->cDateTime($photoAlbum['PhotoAlbum']['captured_date']); ?></p>
    			<span><?php echo __l('Photos: '); ?></span>
    			<?php echo $this->Html->cInt($photoAlbum['PhotoAlbum']['photo_count']); ?>
    			   <?php 	if($this->request->params['named']['type'] == 'latest'):?>
       </div>
   <?php endif;?>
		</li>
		<?php
		endforeach;
		else:
		?>
			<li class="notice-info">
				<p class="notice"><?php echo __l('No photo galleries available');?></p>
			</li>
		<?php
		endif;
		?>
	</ol>
	<div class="view-all-links photos-all-links">
	<span>
		<?php
		if (!empty($photoAlbums)):
			echo $this->Html->link(__l('View More'), array('controller' => 'photo_albums', 'action' => 'index'),array('title'=>__l('View all photos')));
		endif;
		?>
    </span>
	</div>
</div>