<?php /* SVN: $Id: $ */ ?>
<div class="videoTags clearfix js-ajax-form-container">
<?php 
$this->request->data['VideoTag']['video_slug']=!empty($this->request->params['named']['slug'])?$this->request->params['named']['slug']:$this->request->data['VideoTag']['video_slug'];
if($this->Auth->sessionValid()): ?>
    <div class="form-content-block">
	 <?php echo $this->Form->create('VideoTag', array('class' => 'normal search-form clearfix js-ajax-form {container:"js-ajax-form-container", redirect_url: \''. $this->Html->url(array('controller' => 'videos', 'action' => 'view',$this->request->data['VideoTag']['video_slug'])) .'\'}'));

		echo $this->Form->input('video_slug', array('type'=>'hidden','value'=>$this->request->data['VideoTag']['video_slug']));
		echo $this->Form->input('name');
	?>
	<div class="submit-block clearfix">
        <?php echo $this->Form->end(__l('Add')); ?>
    </div>
</div> <?php
 else:
		echo __l('Log in here to submit a tag');
 endif;
?>
</div>
