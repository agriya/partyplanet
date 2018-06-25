<?php /* SVN: $Id: add.ctp 619 2009-07-14 13:25:33Z boopathi_23ag08 $ */ ?>
<div class="videoFlags form js-add-video-flag-response">
<h2><?php echo __l('Flag This Video');?></h2>
<div class="form-content-block">
<?php echo $this->Form->create('VideoFlag', array('class' => "normal js-ajax-form {container:'js-add-video-flag-response', redirect_url:'".$url."'}"));?>
	<fieldset>
	<?php
		if($this->Auth->user('user_type_id') == ConstUserTypes::Admin):
           echo $this->Form->input('user_id', array('empty' => __l('Please Select')));
        endif;
		echo $this->Form->input('video_id', array('type' => 'hidden'));
		echo $this->Form->input('video_flag_category_id');
		echo $this->Form->input('message');
    ?>
	</fieldset>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
        <?php echo $this->Form->end();?>
</div>
</div>