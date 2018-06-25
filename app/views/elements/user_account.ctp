<?php 
if ($this->Auth->user('user_type_id')!= ConstUserTypes::Admin) { 
	$type =isset($this->request->params['pass']['1']) ? $this->request->params['pass']['1'] : $type;
?>
	<ul class="menu-tabs clearfix">
		<li class="<?php echo ((($this->request->params['controller'] == 'user_profiles') and ($this->request->params['action'] == 'edit') and ($type == 'basic')) ? 'active' : '');?>"> 
			<?php echo $this->Html->link(__l('Basic Info') , array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'),'basic'));?>	
		</li>
		<?php if(!$this->Auth->user('fb_user_id') && !$this->Auth->user('is_openid_register') && !$this->Auth->user('is_twitter_register') && !$this->Auth->user('is_yahoo_register') && !$this->Auth->user('is_gmail_register')){?>
		<li class="<?php echo ((($this->request->params['controller'] == 'users') and ($this->request->params['action'] == 'change_password')) ? 'active' : '');?>">		<?php echo $this->Html->link(__l('Change Password') , array('controller' => 'users', 'action' => 'change_password', 'change'));?>
		</li><?php }?>
		<li class="<?php echo ((($this->request->params['controller'] == 'user_profiles') and ($this->request->params['action'] == 'edit') and ($type == 'general')) ? 'active' : '');?>">		<?php echo $this->Html->link(__l('Contact Info') , array('controller' => 'user_profiles', 'action' => 'edit' , $this->Auth->user('id'), 'general'));?>	
		</li>
		<li class="<?php echo ((($this->request->params['controller'] == 'user_profiles') and ($this->request->params['action'] == 'edit') and ($type == 'personal') ) ? 'active' : '');?>">		<?php echo $this->Html->link(__l('Personal Info') , array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'personal'));?>	
		</li>
		
		<li class="<?php echo ((($this->request->params['controller'] == 'user_profiles') and ($this->request->params['action'] == 'edit') and ($type == 'photo') ) ? 'active' : '');?>">
				<?php echo $this->Html->link(__l('Photos') , array('controller' => 'user_profiles', 'action' => 'edit', $this->Auth->user('id'), 'photo'));?>
		</li>
	</ul>
<?php 
} 
?>