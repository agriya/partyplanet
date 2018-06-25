<div class="page">
	<?php if ($this->request->params['pass'][0] != 'site-info' || !empty($this->request->params['prefix'])) { ?>
		<h2><?php echo $page['Page']['title']; ?></h2>
	<?php } ?>
	<div class="entry">
		<?php echo $page['Page']['content']; ?>
	</div>
</div>