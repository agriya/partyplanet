	<h3><?php echo __l('Upload your') . ' ' . '<span>' . ' ' . __l('own video') . '</span>'; ?></h3>

	<p><?php echo __l('Now it\'s your turn. Get your own video up on ').Configure::read('site.name').'.'.__l(' How? It\'s easy. Follow these two quick steps:'); ?></p>
	<p><?php echo __l('Upload the video in the site.'); ?></p>
	<p><?php echo __l('Once it encoded your will published.') . ' '; ?><?php echo $this->Html->link(__l('link it here'), array('controller' => 'videos', 'action' => 'add', $this->Auth->user('username'), 'type' => 'user'), array('title' => __l('link it here'), 'escape' => false));?></p>
