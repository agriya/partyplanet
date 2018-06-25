<?php 
	$this->Html->css('slide_style', null, array('inline' => false)); 
	$this->Javascript->link('libs/jquery.easing.1.3', false);
	$this->Javascript->link('libs/jquery.galleryview-1.1', false);
	$this->Javascript->link('libs/jquery.timers-1.1.2', false);
?>
<!-- InstanceBeginEditable name="head" -->
<script type="text/javascript">
	$(document).ready(function(){
		$('#photos').galleryView({
			panel_width: 588,
			panel_height: 300,
			frame_width: 75,
			frame_height: 75,
			img_path: '<?php echo Router::url('/', true) . 'img/slideshow/'; ?>'
		});
	});
</script>
<div id="photos" class="galleryview">
	<?php foreach ($photos as $photo) { ?>
		<div class="panel">
			<?php echo $this->Html->link($this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'photo_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))), $photo['Photo']['url'], array('escape' => false)); ?>
			<div class="panel-overlay">
				<h2><?php echo $photo['Photo']['title']; ?></h2>
				<p><?php echo $photo['Photo']['description']; ?></p>
			</div>
		</div>
	<?php } ?>
	<ul class="filmstrip">
	<?php foreach ($photos as $photo) { ?>
		<li><?php echo $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'photo_slider_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)), 'title' => $this->Html->cText($photo['Photo']['title'], false))); ?></li>
	<?php } ?>
	</ul>
</div>