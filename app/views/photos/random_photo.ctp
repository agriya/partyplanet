<?php if(!empty($photos)):?>
<div class="coda-slider-no-js">
<div class="coda-slider-wrapper">
<div class="coda-slider preload" id="coda-slider-1">
	<?php 
	$i=1;
		foreach ($photos as $photo) { ?>            
                <!-- slide 1 -->
                  <div class="panel">
                <div class="panel-wrapper">
               <h2 class="title" style="display:none;">
                  <?php echo $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'home_banner_small_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)),'class'=>'js-skip-lazy', 'title' => $this->Html->cText($photo['Photo']['title'], false))); ?>
           </h2>
			<p>
            <?php echo $this->Html->showImage('Photo', $photo['Attachment'], array('dimension' => 'home_banner_big_thumb', 'alt' => sprintf(__l('[Image: %s]'), $this->Html->cText($photo['Photo']['title'], false)),'class'=>'js-skip-lazy','title' => $this->Html->cText($photo['Photo']['title'], false))); ?>
 </p>
  </div>
  </div>
    <?php    }?>
</div>
</div>
</div>
<?php endif;?>



