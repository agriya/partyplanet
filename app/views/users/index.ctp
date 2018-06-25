<?php if (empty($this->request->params['requested']) && empty($this->request->params['isAjax']) && empty($this->request->params['prefix'])): ?>
	<div class="crumb">
		<?php
			$this->Html->addCrumb(Configure::read('site.name').' '.__l("Users"));
			echo $this->Html->getCrumbs(' &raquo; ', __l('Home'));
		?>
	</div>
<?php endif; ?>
<div class="members-block index js-response">
	<?php
		//if (empty($this->request->params['named']['type']) or $this->request->params['named']['type'] =='search') {
			$country = "";
			if(!empty($this->request->data['User']['country_id'])) {
				$country = $this->request->data['User']['country_id'];
			}
			echo $this->element('user_search', array('country_id' => $country, 'cache' => array('config' => '2sec')));
	//	}
	?>
	<h2><?php echo Configure::read('site.name'); ?>  <span><?php echo __l('Users');?></span></h2>
	<ol class="user-list clearfix">
		<?php
		if (!empty($users)):
				$i = 0;
				foreach ($users as $user):
		?>
		<li>
			<?php echo $this->Html->getUserAvatar($user['User'], 'normalhigh_thumb'); ?>
			<p> <?php echo $this->Html->link($this->Html->ctext($user['User']['username'] ) ,array('controller' => 'users', 'action' => 'view', $user['User']['username']), array('escape' => false));?>
			</p>
		</li>
		<?php
				endforeach;
			else:
		?>
		<li class="no-record">
			<p class="notice"><?php echo __l('No users available');?></p>
		</li>
		<?php
			endif;
		?>
	</ol>
	<?php
  if (!empty($users)) {?>
  	<div class="js-pagination">
			 <?php echo $this->element('paging_links'); ?>
          </div>
	<?php	}
	?>
</div>