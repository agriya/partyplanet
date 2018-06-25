<ul>
	<li>
		<h3><?php echo __l('Latest Galleries'); ?></h3>
		<?php
			echo $this->requestAction(array('controller' => 'photo_albums', 'action' => 'index', 'sort_by' => 'date', 'location' => 'menu', 'admin' => false), array('return'));
			echo $this->Html->link(__l('more...'), array('controller' => 'photo_albums', 'action' => 'index'), array('class' => 'more-link', 'title'=>__l('more...'),'escape' => false));
		?>
	</li>
	<li class="most">
		<h3><?php echo __l('Most Popular'); ?></h3>
		<?php
			echo $this->requestAction(array('controller' => 'photos', 'action' => 'index', 'type' => 'popular', 'limit' => 5, 'location' => 'menu', 'admin' => false), array('return'));
			echo $this->Html->link(__l('more...'), array('controller' => 'photos', 'action' => 'index', 'type' => 'popular','view'=>'all'), array('class' => 'more-link', 'title' => __l('more...'), 'escape' => false));
		?>
	</li>
	<li>
		<h3><?php echo Configure::read('site.name'); ?> <?php echo __l('Hotties'); ?></h3>
		<?php
			echo $this->requestAction(array('controller' => 'photos', 'action' => 'index', 'type' => 'hotties', 'limit' => 5,'admin' => false), array('return'));
        	echo $this->Html->link(__l('more...'), array('controller' => 'photos', 'action' => 'index', 'type' => 'hotties','view'=>'all'), array('class' => 'more-link', 'title' => __l('more...'), 'escape' => false));
		?>
	</li>
	<li class="las photos-block">
		<h3><?php echo __l('Photos'); ?></h3>
     		<? if($this->Auth->user('id')){
       	       	echo $this->Html->link(__l('My Photos'), array('controller' => 'photo_albums', 'action' => 'index', 'username' => $this->Auth->user('username')), array('title' => __l('My Photos'), 'escape' => false));
        	}else{
        	   echo $this->Html->link(__l('My Photos'), array('controller' => 'users', 'action' => 'login'), array('title' => __l('My Photos'), 'escape' => false));
        	}
        	?>
    	    <?php echo $this->Html->link(__l('Upload Photos'), array('controller' => 'photo_albums', 'action' => 'add'), array('title' => __l('Upload Photos'), 'escape' => false)); ?>
           <?php echo $this->Html->link(__l('Search Photos'), array('controller' => 'photo_albums', 'action' => 'index', 'type' => 'search', 'admin' => false), array('title' => __l('Search Photos'), 'escape' => false));?>
    
	</li>
</ul>