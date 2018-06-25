<?php /* SVN: $Id: admin_edit.ctp 17273 2012-02-06 04:38:44Z josephine_065at09 $ */ ?>
<div class="venueOwners form">
<?php echo $this->Form->create('VenueOwner', array('class' => 'normal'));?>
	<fieldset>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('first_name');
		echo $this->Form->input('last_name');
		echo $this->Form->input('city_id');
		echo $this->Form->input('country_id');
		echo $this->Form->input('other_mobile',array('label'=>__l('Phone')));
		echo $this->Form->input('mobile');
		echo $this->Form->input('email');
		echo $this->Form->input('gender_id');
		echo $this->Form->input('is_created',array('type'=>'hidden'));?>
		<span class="label-content dob-info"><?php echo __l('DOB'); ?></span>
		<div class="js-datetime">
    		<?php echo $this->Form->input('dob', array('type' => 'date','label' => false, 'orderYear' => 'asc', 'maxYear' => date('Y'), 'minYear' => date('Y') - 100, 'div' =>false, 'empty' => __l('Please Select'))); ?>
	   </div>
	   <?php echo $this->Form->input('venue_name');
		echo $this->Form->input('venue_type_id');
		echo $this->Form->input('is_agree_terms_conditions', array('label' => __l('Agree Terms Conditions?')));
		?>
	</fieldset>
	<div class="submit-block clearfix">
<?php echo $this->Form->submit(__l('Update'));?>
</div>
<?php echo $this->Form->end();?>
</div>
