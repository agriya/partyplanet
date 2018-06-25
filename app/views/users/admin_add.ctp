<?php /* SVN: $Id: $ */ ?>
<div class="users form">

<?php if (empty($this->request->params['prefix'])): ?>
	<div id="breadcrumb" class="crumb">
	<?php $this->Html->addCrumb(__l('Users'), array('controller' => 'users', 'action' => 'index')); ?>
	<?php $this->Html->addCrumb(__l('Add user')); ?>
	<?php echo $this->Html->getCrumbs(' &raquo; ', __l('Home')); ?>
	</div>
<?php endif; ?>

<div class="form-content-block">
<?php echo $this->Form->create('User', array('class' => 'normal'));?>
	<?php
	   echo $this->Form->input('user_type_id');
        echo $this->Form->input('venue_owner_id',array('type'=>'hidden'));
		echo $this->Form->input('email');
		echo $this->Form->input('username');
		if(!empty($this->request->data['UserProfile']['last_name'])):
        echo $this->Form->input('UserProfile.last_name',array('type'=>'hidden'));
        endif;
        if(!empty($this->request->data['UserProfile']['phone'])):
		echo $this->Form->input('UserProfile.phone',array('type'=>'hidden'));
		endif;
		if(!empty($this->request->data['UserProfile']['mobile'])):
		echo $this->Form->input('UserProfile.mobile',array('type'=>'hidden'));
		endif;
		if(!empty($this->request->data['UserProfile']['gender_id'])):
		echo $this->Form->input('UserProfile.gender_id',array('type'=>'hidden'));
		endif;
		if(!empty($this->request->data['UserProfile']['dob'])):
  		echo $this->Form->input('UserProfile.dob', array('type'=>'hidden','label' => __l('DOB'),'empty' => __l('Please Select'), 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, "orderYear" => 'asc', 'class' => 'js-date'));
  		endif;
  		echo $this->Form->input('passwd', array('label' => __l('Password')));
		$url = Router::url(array('controller' => 'cities', 'action' => 'lst', 'type' => 'user_profile', 'admin' => false), true);
		echo $this->Form->input('UserProfile.country_id',array('empty'=>__l('Please Select'), 'class' => 'js-dropdown {"url":"' . $url . '", "container":"js-city"}'));
	?>
	<div class="js-city">
		<?php echo $this->Form->input('UserProfile.city_id',array('empty'=>__l('Please Select'))); ?>
	</div>
	<div class="submit-block clearfix">
        <?php echo $this->Form->submit(__l('Add'));?>
    </div>
    <?php echo $this->Form->end(); ?>
</div>
</div>