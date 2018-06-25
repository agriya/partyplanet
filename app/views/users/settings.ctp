<div class="users settings">
<div class="main-content-block js-corner round-5">
<h2><?php echo __l('privacy Settings'); ?></h2>
<div class="js-tabs">
	<ul class="clearfix menu-tabs">
		<li><?php echo $this->Html->link(__l('Profile'), '#profile');?></li>
		<li><?php echo $this->Html->link(__l('Blogs'), '#blogs');?></li>
		<li><?php echo $this->Html->link(__l('Photos'), '#photos');?></li>
		<li><?php echo $this->Html->link(__l('Albums'), '#albums');?></li>
		<li><?php echo $this->Html->link(__l('Messages'), '#messages');?></li>
	</ul>
	<div id="profile"><?php echo $this->element('user_profile_preferences-index', array( 'cache' => array('config' => 'sec')));?></div>
	<div id="blogs"><?php echo $this->element('user_blog_preferences-edit', array('cache' => array('config' => 'sec')));?></div>	<div id="photos"><?php echo $this->element('user_photo_preferences-edit', 'cache' => array('config' => 'sec'));?></div>
	<div id="albums"><?php echo $this->element('user_album_preferences-edit', array('cache' => array('config' => 'sec')));?></div>
	<div id="messages"><?php echo $this->element('user_message_preferences-edit', array('cache' => array('config' => 'sec')));?></div>
</div>
</div>
</div>