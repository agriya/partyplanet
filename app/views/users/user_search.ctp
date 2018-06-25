	<h2><?php echo __l('Friend Search'); ?></h2>
<div class="userProfiles form form-content-block">
		<?php echo $this->Form->create('User', array('action' => 'index/type:search', 'class' => 'normal clearfix'));?>
		<fieldset>
			<?php $url = Router::url(array('controller' => 'cities', 'action' => 'lst'), true); ?>
			<div class="choose-block clearfix">
			<span class="label-content"><?php echo __l('Choose')?></span>
			<?php echo $this->Form->input('choose', array('label' => __l('choose'),'type' => 'radio','value' => !empty($chooseval) ? $chooseval : '', 'options' => $userSearchFilterOptions, 'class' => 'js-filter-select','legend'=>false)); ?> </div>
			<?php $countryclass = ($this->request->data['User']['choose'] == '3') ? '' : 'hide';
                  ?>
			<div class="<?php echo $countryclass; ?> js-country">
				<?php echo $this->Form->input('country_id', array('empty' => __l('Please Select'), 'type' => 'select', 'options' => $countries, 'class' => 'js-dropdown {"url":"'.$url.'", "container":"js-city"}')); ?>
				<?php $cities = !empty($cities) ? $cities : array(); ?>
				<div class="js-city"><?php echo $this->Form->input('city_id', array('options' => $cities, 'empty' => __l('Please Select'), 'type' => 'select')); ?></div>
			</div>
			<?php
				echo $this->Form->input('keyword',array('info'=>__l('Search in user\'s Email, username, first name and last name.')));
				echo $this->Form->input('gender_id', array('empty' => __l('Please Select'), 'options' => $genders));
			?>
		</fieldset>
		<div class="submit-block clearfix">
			<?php echo $this->Form->submit(__l('Search')); ?>
		</div>
            <?php echo $this->Form->end(); ?>

</div>