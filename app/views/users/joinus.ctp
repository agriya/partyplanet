<div class="users form">
<div id="breadcrumb">
				<?php echo $this->Html->addCrumb(__l('Register')); ?>
				<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
			</div>
	<div class="clearfix">
		<div class="join-left grid_left">
		<h2><?php echo __l('Create A') . ' '; ?><span><?php echo __l('New Account'); ?></span></h2>
		<div class="new-account">
  	        <div class="clearfix form-content-block">
			<div class="regular-content1 grid_left">
				<h3><?php echo __l('Venue Owner'); ?></h3>
				<p><?php echo __l('Are you a venue owner? Would you like to manage your venues info, photos, videos and more? click here to signup for free.'); ?></p>
            </div>
			<div class="signup-button signup-button1 grid_left">
				<?php echo $this->Html->link(__l('Sign-Up to be a venue owner'), array('controller' => 'venue_owners', 'action' => 'add'), array('title'=>__l('Sign-Up to be a venue owner'),'escape' => false));?>
			</div>
  	     </div>
    	</div>
		</div>
		<div class="join-right grid_left">
			<?php echo $this->element('user_login', array('type' => 'register', 'cache' => array('config' => 'sec'))); ?>	
		</div>
	</div>
</div>