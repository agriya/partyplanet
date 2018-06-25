<ul class="">
		<li>
	   	  <h4><?php echo __l('Affiliates');?></h4>
	   		<ul>
    			<?php $class = ($this->request->params['controller'] == 'affiliates') ? ' class="active"' : null; ?>
				<li <?php echo $class;?>><?php echo $this->Html->link(__l('Affiliates'), array('controller' => 'affiliates', 'action' => 'index'),array('title' => __l('Affiliates'))); ?></li>
				<?php $class = ($this->request->params['controller'] == 'affiliate_requests') ? ' class="active"' : null; ?>
				<li><?php echo $this->Html->link(__l('Requests'), array('controller' => 'affiliate_requests', 'action' => 'index'), array('title' => __l('Affiliate Requests'))); ?></li>
				<li class="setting-overview payment-overview"><?php echo $this->Html->link(__l('Common Settings'), array('controller' => 'settings', 'action' => 'edit', 14), array('title' => __l('Common Settings'), 'class' => 'affiliate-settings')); ?></li>
				<?php $class = ($this->request->params['controller'] == 'affiliate_types') ? ' class="active"' : null; ?>
				<li><?php echo $this->Html->link(__l('Commission Settings'), array('controller' => 'affiliate_types', 'action' => 'edit'),array('title' => __l('Commission Settings'))); ?></li>
			</ul>
		</li>
	</ul>
